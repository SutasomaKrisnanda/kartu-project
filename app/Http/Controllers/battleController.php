<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomUserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class battleController extends Controller
{
    public function index(){
        $user = Auth::user();
        $rooms = Room::where('visibility', 'public')->where('status', 'waiting')->get();
        $data = [
            'user' => $user,
            'rooms' => $rooms
        ];
        return view('battle', $data);
    }

    public function getRoomList(){
        $rooms = Room::where('visibility', 'public')->where('status', 'waiting')->get();
        return response()->json($rooms);
    }


    public function createGame(Request $request){
        try {
            $request->validate([
                'name' => 'nullable|string|max:255',
                'visibility' => 'required|in:public,private',
                'password' => 'nullable|string|max:255',
                'gameMode' => 'required|string',
            ]);

            $room = new Room;
            $room->name = $request->input('name');
            $room->visibility = $request->input('visibility');
            $room->password = $request->input('password');
            $room->mode = $request->input('gameMode');
            $room->status = 'waiting';

            // Generate room code
            $room->code = $this->generateRoomCode();
            $room->save();

            // Add the creator to the room (adjust relationship as needed)
            $room->users()->attach(Auth::user()->id, ['role' => 'creator']);

            $roomUserStatus = new RoomUserStatus([
                'room_id' => $room->id,
                'user_id' => Auth::user()->id,
                'hp' => 8
            ]);
            $roomUserStatus->save();

            // Return the room code
            return response()->json([
                'success' => true,
                'roomCode' => $room->code,
                'message' => 'Game created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function generateRoomCode() {
        $characters = '0123456789ABCDEFG2HIJKLMNOPQRSTUVWXYZ';
        $roomCode = '';
        for ($i = 0; $i < 6; $i++) {
            $roomCode .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $roomCode;
    }

    public function joinGame(Request $request, $roomCode){
        try {
            $room = Room::where('code', $roomCode)->first();

            if (!$room) {
                return response()->json(['error' => 'Room not found'], 404);
            }

            if ($room->password && $room->password !== $request->input('password')) {
                return response()->json(['error' => 'Incorrect password'], 401);
            }
            $room->status = 'in progress';
            $room->save();

            $room->users()->attach(Auth::user()->id, ['role' => 'player']);

            $roomUserStatus = new RoomUserStatus([
                'room_id' => $room->id,
                'user_id' => Auth::user()->id,
                'hp' => 8
            ]);
            $roomUserStatus->save();

            return response()->json([
                'success' => true,
                'roomCode' => $room->code,
                'message' => 'Joined game successfully'
            ]);


        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
