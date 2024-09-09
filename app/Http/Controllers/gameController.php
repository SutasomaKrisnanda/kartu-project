<?php

namespace App\Http\Controllers;

use DOMDocument;
use App\Models\Item;
use App\Models\Room;
use App\Models\User;
use App\Models\Inventory;
use App\Models\RoomMove;
use App\Models\RoomUser;
use App\Models\RoomUserItem;
use App\Models\RoomUserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class gameController extends Controller
{
    public function index(Request $request)
    {
        $gameCode = $request->route('gameCode');
        $room = Room::where('code', $gameCode)->first();

        if ($room) {
            $users = $room->users; // Get users associated with the room
            return view('game/load', ['room' => $room, 'users' => $users]);
        } else {
            // Handle case where room is not found
            abort(404);
        }
    }

    public function checkRoom(Request $request)
    {
        $user = User::find(Auth::user()->id); // Fetch user using User model
        if ($user) {
            $room = $user->rooms->last(); // Get the user's room
            if ($room) {
                $playerCount = $room->users()->count();
                $inProgress = RoomUserItem::where('room_id', $room->id)->where('user_id', $user->id)->exists();
                return response()->json(['startGame' => $playerCount >= 2, 'inProgress' => $inProgress]);
            }
        }
        return response()->json(['startGame' => false, 'inProgress' => false]);
    }

    public function getCards(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $cards = $user->items()->where('type', 'card')->get();

        return response()->json($cards);
    }

    public function startGame(Request $request)
    {
        // Get data from fetch
        $selectedCards = $request->input('selectedCards');

        // Save user selected Cards
        $user = User::find(Auth::user()->id);
        $room = $user->rooms->last();

        // Find the selected cards
        $cards = Item::whereIn('token', $selectedCards)->get();

        // Get the opponent in the same room
        $opponent = $room->users()->where('users.id', '!=', $user->id)->first();

        // Save selected cards to RoomUserItem table
        $roomUserItems = $cards->map(function ($card) use ($room, $user) {
            return [
                'room_id' => $room->id,
                'user_id' => $user->id,
                'item_id' => $card->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });
        RoomUserItem::insert($roomUserItems->toArray());

        // Get the player's status in the room
        $playerStatus = RoomUserStatus::where('user_id', $user->id)
            ->where('room_id', $room->id)
            ->latest()
            ->first();

        // Get the opponent's status in the room
        $opponentStatus = $opponent ? RoomUserStatus::where('user_id', $opponent->id)
            ->where('room_id', $room->id)
            ->latest()
            ->first()
            : null;

        // Get the player's selected cards (now saved in RoomUserItem)
        $playerMove = RoomMove::where('room_id', $room->id)->where('user_id', $user->id)->get();
        $opponentMove = RoomMove::where('room_id', $room->id)->where('user_id', '!=', $user->id)->get();

        $roomUserItems = $room->items()->where('room_user_items.user_id', $user->id)->get();
        $cards = $roomUserItems->map(fn($roomUserItem) => $roomUserItem->item);

        // Prepare data for the view
        $data = [
            'cards' => $cards,
            'player' => $user,
            'opponent' => $opponent,
            'playerStatus' => $playerStatus,
            'opponentStatus' => $opponentStatus,
            'history' => $room->moves,
            'historya' => $playerMove,
            'historyb' => $opponentMove
        ];

        // Render game.blade.php into HTML
        $htmlContent = view('game/game', $data)->render();

        // Return the data and the HTML content
        return response()->json([
            'data' => $data,
            'html' => $htmlContent
        ]);
    }

    public function getGame(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $room = $user->rooms->last();
        $opponent = $room->users()
            ->where('users.id', '!=', $user->id)
            ->first();
        $roomUserItems = $room->items()->where('user_id', $user->id)->get();
        $cards = $roomUserItems->map(fn($roomUserItem) => $roomUserItem->item);
        $playerStatus = $room->status()->where('user_id', $user->id)->latest()->first();
        $opponentStatus = $opponent ? $room->status()->where('user_id', $opponent->id)->latest()->first() : null;
        $playerMove = RoomMove::where('room_id', $room->id)->where('user_id', $user->id)->orderByDesc('id')->get();
        $opponentMove = RoomMove::where('room_id', $room->id)->where('user_id', '!=', $user->id)->orderByDesc('id')->get();

        $data = [
            'cards' => $cards,
            'player' => $user,
            'opponent' => $opponent,
            'playerStatus' => $playerStatus,
            'opponentStatus' => $opponentStatus,
            'history' => $room->moves,
            'historya' => $playerMove,
            'historyb' => $opponentMove
        ];
        return response()->json([
            'html' => view('game/game', $data)->render(),
            'time' => $room->updated_at,
            'start' => $room->created_at
        ]);
    }

    public function getGameStatus(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $room = $user->rooms->last();
        if (!$room) return response()->json(['error' => 'User is not in any room'], 404);
        $playerStatus = RoomUserStatus::where('user_id', $user->id)->where('room_id', $room->id)->latest()->first();
        $opponentStatus = RoomUserStatus::where('user_id', '!=', $user->id)->where('room_id', $room->id)->latest()->first();
        $playerMove = RoomMove::where('room_id', $room->id)->where('user_id', $user->id)->with('move')->get();
        $opponentMove = RoomMove::where('room_id', $room->id)->where('user_id', '!=', $user->id)->with('move')->get();
        // THis is for debug
        // return response()->json([
        //     'success' => false,
        //     'message' => $opponentMove
        // ]);
        if (!$playerMove->isEmpty() && !$opponentMove->isEmpty())
            if (!$playerMove->last()->success && !$opponentMove->last()->success) {
                $this->runCardEffect($this->checkElement($playerMove->last(), $opponentMove->last()));
                $playerMove->last()->success = true;
                $opponentMove->last()->success = true;
                $playerMove->last()->save();
                $opponentMove->last()->save();
                $this->updateCooldown($playerMove->last(), $opponentMove->last(), $playerStatus, $opponentStatus);
                $room->save();
                $room->touch();
            }
        if ($playerStatus->hp == 0 && $opponentStatus->hp == 0) $room->winner_id = 'draw';
        elseif ($playerStatus->hp == 0) $room->winner_id = $opponentStatus->user_id;
        elseif ($opponentStatus->hp == 0) $room->winner_id = $playerStatus->user_id;
        $room->save();

        $historya = $playerMove->sortByDesc('id')->map(function ($move) {
            return [
                'move' => $move->move ? $move->move->only(['name', 'image', 'token']) : false,
                'win' => $move->win
            ];
        });

        $historyb = $opponentMove->sortByDesc('id')->map(function ($move) {
            return [
                'move' => $move->move ? $move->move->only(['name', 'image', 'token']) : false,
                'win' => $move->win
            ];
        });

        return response()->json([
            'player' => $playerStatus ? $playerStatus->only(['hp', 'effect']) : ['hp' => 8, 'effect' => 'Not Found'],
            'opponent' => $opponentStatus ? $opponentStatus->only(['hp', 'effect']) : ['hp' => 8, 'effect' => 'Not Found'],
            'success' => ($playerMove && $opponentMove) ? ($playerMove->last()->success && $opponentMove->last()->success) : false,
            'playerCooldown' => json_decode($playerStatus->cooldown),
            'opponentCooldown' => json_decode($opponentStatus->cooldown),
            'historya' => $historya,
            'historyb' => $historyb,
            'message' => [$opponentMove->isEmpty() ? 'Null' : $opponentMove->last()->move, $playerMove->isEmpty() ? 'Null' : $playerMove->last()->move]
        ]);
    }

    private function updateCooldown($playerMove, $opponentMove, $playerStatus, $opponentStatus)
    {
        $updateCooldownList = function (&$cooldowns) {
            if ($cooldowns) {
                foreach ($cooldowns as $index => &$cd) {
                    $cd['duration']--;
                    if ($cd['duration'] <= 0) {
                        unset($cooldowns[$index]);
                    }
                }
                $cooldowns = array_values($cooldowns);
            }
        };
        $playerCd = $playerStatus->cooldown ? json_decode($playerStatus->cooldown, true) : [];
        $opponentCd = $opponentStatus->cooldown ? json_decode($opponentStatus->cooldown, true) : [];
        $updateCooldownList($playerCd);
        $updateCooldownList($opponentCd);

        $playerCd[] = [
            'name' => $playerMove->move->name,
            'token' => $playerMove->move->token,
            'image' => $playerMove->move->image,
            'duration' => $playerMove->move->effect->cooldown
        ];
        $opponentCd[] = [
            'name' => $opponentMove->move->name,
            'token' => $opponentMove->move->token,
            'image' => $opponentMove->move->image,
            'duration' => $opponentMove->move->effect->cooldown
        ];
        $playerStatus->update(['cooldown' => json_encode($playerCd)]);
        $opponentStatus->update(['cooldown' => json_encode($opponentCd)]);
        $playerStatus->save();
        $opponentStatus->save();
    }

    public function updateGameStatus(Request $request, $type)
    {
        $user = User::find(Auth::user()->id);
        $room = $user->rooms->last();

        switch ($type) {
            case 'card':
                $card = Item::where('token', $request->input('cardChosed'))->first();
                if (!$card) return response()->json(['message' => 'Card not found', 'data' => $request->input('cardChosed')], 404);
                $data = new RoomMove([
                    'room_id' => $room->id,
                    'user_id' => $user->id,
                    'move_data' => $card->id
                ]);
                $data->save();
                return response()->json(['message' => $card->element]);
            case 'skip':
                $data = new RoomMove([
                    'room_id' => $room->id,
                    'user_id' => $user->id,
                ]);
                $data->save();
                return response()->json(['message' => 'Done Skipped']);
            default:
                return response()->json(['message' => 'Invalid type']);
        }
    }

    private function checkElement($move1, $move2)
    {
        $card1 = $move1->move;
        $card2 = $move2->move;
        if (!$card1 && $card2) return [$move2];
        if (!$card2 && $card1) return [$move1];
        if (!$card1 && !$card2) return [];
        $element1 = $card1->element;
        $element2 = $card2->element;
        if ($element1 == $element2) return [$move1, $move2];
        switch ($element1) {
            case 'fire':
                if ($element2 == 'air') return [$move1];
                else if ($element2 == 'water') return [$move2];
                else return [$move1, $move2];
            case 'water':
                if ($element2 == 'fire') return [$move1];
                else if ($element2 == 'earth') return [$move2];
                else return [$move1, $move2];
            case 'earth':
                if ($element2 == 'water') return [$move1];
                else if ($element2 == 'air') return [$move2];
                else return [$move1, $move2];
            case 'air':
                if ($element2 == 'earth') return [$move1];
                else if ($element2 == 'fire') return [$move2];
                else return [$move1, $move2];
            default:
                return [];
        }
    }

    private function runCardEffect(array $moves)
    {
        foreach ($moves as $move) {
            $card = $move->move;
            $effect = $card->effect;
            switch($effect->type) {
                case 'damage':
                    $move->win = $this->damage($move, $effect);
                    break;
                case 'heal':
                    $move->win = $this->heal($move, $effect);
                    break;
                // case 'block':
                //     $move->win = $this->block($move, $effect);
                //     break;
                // case 'counter':
                //     $move->win = $this->counter($move, $effect);
                //     break;
                // case 'poison':
                //     $move->win = $this->poison($move, $effect);
                //     break;
                default:
                    $move->win = false;
                    break;
            }
        }
    }

    private function damage($move, $effect)
    {
        if ($effect->value) {
            $user = $move->user;
            $opponentStatus = RoomUserStatus::where('user_id', '!=', $user->id)->latest()->first();
            $opponentStatus->hp -= $effect->value;
            if ($opponentStatus->hp < 0) $opponentStatus->hp = 0 ;
            $opponentStatus->save();
            $move->win = true;
            $move->save();
        }
        return 1;
    }

    private function heal($move, $effect)
    {
        if ($effect->value) {
            $user = $move->user;
            $opponentStatus = RoomUserStatus::where('user_id', $user->id)->latest()->first();
            $opponentStatus->hp += $effect->value;
            if ($opponentStatus->hp > 10) $opponentStatus->hp = 10;
            $opponentStatus->save();
            $move->win = true;
            $move->save();
        }
        return 1;
    }

    // private function block($move, $effect)
    // {
    //     if ($effect->value) {
    //         $user = $move->user;
    //         $opponentStatus = RoomUserStatus::where('user_id', '!=', $user->id)->latest()->first();
    //         $opponentStatus->block += $effect->value;
    //         $opponentStatus->save();
    //         $move->win = true;
    //         $move->save();
    //     }
    //     return 0;
    // }
}
