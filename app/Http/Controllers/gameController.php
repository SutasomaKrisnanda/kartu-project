<?php

namespace App\Http\Controllers;

use DOMDocument;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
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
                return response()->json(['startGame' => $playerCount >= 2]);
            }
        }
        return response()->json(['startGame' => false]);
    }

    public function pick(Request $request)
    {
        $script = "
            const cardContainer = document.getElementById('card-container');
            const timerElement = document.getElementById('timer');
            const acceptBtn = document.getElementById('accept-btn');

            let selectedCards = [];
            const totalCards = 15;
            const timeLimit = 120;
            let timeRemaining = timeLimit;

            for (let i = 1; i <= totalCards; i++) {
                const card = document.createElement('div');
                card.classList.add('card');
                card.textContent = `Card \${i}`;
                card.addEventListener('click', () => selectCard(card));
                cardContainer.appendChild(card);
            }

            function selectCard(card) {
                if (selectedCards.length < 5) {
                    if (!card.classList.contains('selected')) {
                        card.classList.add('selected');
                        selectedCards.push(card);
                    } else {
                        card.classList.remove('selected');
                        selectedCards = selectedCards.filter(c => c !== card);
                    }
                    acceptBtn.disabled = selectedCards.length !== 5;
                } else {
                    if (card.classList.contains('selected')) {
                        card.classList.remove('selected');
                        selectedCards = selectedCards.filter(c => c != card);
                    }
                    acceptBtn.disabled = selectedCards.length !== 5;
                }
            }

            function startTimer() {
                const timerInterval = setInterval(() => {
                    timeRemaining--;
                    const minutes = Math.floor(timeRemaining / 60);
                    const seconds = timeRemaining % 60;
                    timerElement.textContent = `\${String(minutes).padStart(2, '0')}:\${String(seconds).padStart(2, '0')}`;

                    if (timeRemaining <= 0) {
                        clearInterval(timerInterval);
                        autoCompleteSelection();
                    }
                }, 1000);
            }

            function autoCompleteSelection() {
                while (selectedCards.length < 5) {
                    const remainingCards = Array.from(document.querySelectorAll('.card:not(.selected)'));
                    const randomCard = remainingCards[Math.floor(Math.random() * remainingCards.length)];
                    randomCard.classList.add('selected');
                    selectedCards.push(randomCard);
                }
                acceptBtn.disabled = false;
            }

            acceptBtn.addEventListener('click', () => {
                alert('You have selected: ' + selectedCards.map(c => c.textContent).join(', '));
            });

            startTimer();
        ";

        return response($script, 200, ['Content-Type' => 'text/javascript']);
    }
}
