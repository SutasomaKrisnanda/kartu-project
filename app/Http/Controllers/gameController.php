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
    public function index(Request $request){
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

    public function checkRoom(Request $request){
        $user = User::find(Auth::user()->id); // Fetch user using User model
        if ($user) {
            $room = $user->rooms()->first(); // Get the user's room
            if ($room) {
                $playerCount = $room->users()->count();
                $inProgress = RoomUserItem::where('room_id', $room->id)->where('user_id', $user->id)->exists();
                return response()->json(['startGame' => $playerCount >= 2, 'inProgress' => $inProgress]);
            }
        }
        return response()->json(['startGame' => false, 'inProgress' => false]);
    }

    public function getCards(Request $request){
        $user = User::find(Auth::user()->id);
        $cards = $user->items()->where('type', 'card')->get();

        return response()->json($cards);
    }

    public function startGame(Request $request) {
        // Get data from fetch
        $selectedCards = $request->input('selectedCards');

        // Save user selected Cards
        $user = User::find(Auth::user()->id);
        $room = $user->rooms()->first();

        // Find the selected cards
        $cards = Item::whereIn('token', $selectedCards)->get();

        // Get the opponent in the same room
        $opponent = $room->users()->where('users.id', '!=', $user->id)->first();

        // Save selected cards to RoomUserItem table
        $roomUserItems = $cards->map(function($card) use ($room, $user) {
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
        $roomUserItems = $room->items()->where('room_user_items.user_id', $user->id)->get();
        $cards = $roomUserItems->map(fn($roomUserItem) => $roomUserItem->item);

        // Prepare data for the view
        $data = [
            'cards' => $cards,
            'player' => $user,
            'opponent' => $opponent,
            'playerStatus' => $playerStatus,
            'opponentStatus' => $opponentStatus,
        ];

        // Render game.blade.php into HTML
        $htmlContent = view('game/game', $data)->render();

        // Return the data and the HTML content
        return response()->json([
            'data' => $data,
            'html' => $htmlContent
        ]);
    }


    public function getGame(Request $request){
        $user = User::find(Auth::user()->id);
        $room = $user->rooms()->first();

        // Mengambil lawan secara langsung dari relasi users di Room
        $opponent = $room->users()
                         ->where('users.id', '!=', $user->id)
                         ->first();

        // Mengambil items dari user yang sedang login di room saat ini
        $roomUserItems = $room->items()->where('user_id', $user->id)->get();
        $cards = $roomUserItems->map(fn($roomUserItem) => $roomUserItem->item);

        // Mengambil status dari player
        $playerStatus = $room->status()->where('user_id', $user->id)->latest()->first();

        // Mengambil status dari opponent jika ada
        $opponentStatus = $opponent ? $room->status()->where('user_id', $opponent->id)->latest()->first() : null;

        $data = [
            'cards' => $cards,
            'player' => $user,
            'opponent' => $opponent,
            'playerStatus' => $playerStatus,
            'opponentStatus' => $opponentStatus,
        ];

        return view('game/game', $data);
    }



    public function getGameStatus(Request $request){
        $user = User::find(Auth::user()->id);
        $room = $user->rooms()->first();
        if (!$room) {
            return response()->json(['error' => 'User is not in any room'], 404);
        }

        $playerStatus = RoomUserStatus::where('user_id', $user->id)->latest()->first();
        $opponentStatus = RoomUserStatus::where('user_id', '!=', $user->id)->latest()->first();
        $playerMove = RoomMove::where('room_id', $room->id)->where('user_id', $user->id)->latest()->first();
        $opponentMove = RoomMove::where('room_id', $room->id)->where('user_id', '!=', $user->id)->latest()->first();
        if ($playerMove && $opponentMove)
        if (!$playerMove->success && !$opponentMove->success){
            $this->runCardEffect($this->checkElement($playerMove, $opponentMove));
            $playerMove->success = true;
            $opponentMove->success = true;
            $playerMove->save();
            $opponentMove->save();
        }

        return response()->json([
            'player' => $playerStatus ? $playerStatus->only(['hp', 'effect']) : ['hp' => 8, 'effect' => 'Not Found'],
            'opponent' => $opponentStatus ? $opponentStatus->only(['hp', 'effect']) : ['hp' => 8, 'effect' => 'Not Found'],
            'success' => ($playerMove->success && $opponentMove->success)
        ]);
    }

    public function updateGameStatus(Request $request, $type){
        $user = User::find(Auth::user()->id);
        $room = $user->rooms()->first();

        switch ($type){
            case 'card':
                $card = Item::where('token', $request->input('cardChosed'))->first();
                if (!$card) return response()->json(['message' => 'Card not found', 'data' => $request->input('cardChosed')], 404);
                $data = new RoomMove([
                    'room_id' => $room->id,
                    'user_id' => $user->id,
                    'move_data' => $card->id
                ]);
                $data->save();
            break;
            default:
                return response()->json(['message' => 'Invalid type']);
        }
        $playerMove = RoomMove::where('room_id', $room->id)->where('user_id', $user->id)->latest()->first();
        $opponentMove = RoomMove::where('room_id', $room->id)->where('user_id', '!=', $user->id)->latest()->first();
        return response()->json(['message' => 'Success Load updateGameStatus']);
        if (!$playerMove->success && !$opponentMove->success) {
            $this->runCardEffect($this->checkElement($playerMove, $opponentMove));
        return response()->json(['message' => 'success']);
        } else {
            return response()->json(['message' => 'Wait for opponent to play a card']);
        }
    }

    private function checkElement($move1, $move2){
        return [$move1, $move2];

        $element1 = $move1->move()->element;
        $element2 = $move2->move()->element;
        switch($element1){
            case 'fire':
                if($element2 == 'air') return [$move1];
                else if($element2 == 'water') return [$move2];
                break;
            case 'water':
                if($element2 == 'fire') return [$move1];
                else if($element2 == 'earth') return [$move2];
                break;
            case 'earth':
                if($element2 == 'water') return [$move1];
                else if($element2 == 'air') return [$move2];
                break;
            case 'air':
                if($element2 == 'earth') return [$move1];
                else if($element2 == 'fire') return [$move2];
                break;
            default:
                return [$move1, $move2];
        }
    }

    private function runCardEffect(array $moves){
        foreach($moves as $move){
            $user = $move->user;
            $opponentStatus = RoomUserStatus::where('user_id', '!=', $user->id)->latest()->first();
            $opponentStatus->hp = $opponentStatus->hp - 1;
            $opponentStatus->save();
        }
    }
}
