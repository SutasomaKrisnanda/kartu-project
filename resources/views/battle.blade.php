<x-home.layout>
    {{-- This for the variables --}}
    <x-slot:nickname>{{ $user->nickname }}</x-slot>

    {{-- This is the main content --}}
    @csrf
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h1 class="text-center mt-4">Game Matchmaking</h1>
                <div class="d-flex flex-column align-items-center main-menu my-4">
                    <button id="createGameBtn" class="btn btn-primary mb-2">Create Game</button>
                    <button id="findGameBtn" class="btn btn-primary mb-2">Find Game</button>
                    <button id="joinGameBtn" class="btn btn-primary mb-2">Join via Code</button>
                    <button id="gameHistoryBtn" class="btn btn-primary mb-2">Game History</button>
                </div>
            </div>
            <div class="col-md-8">
                <!-- Create Game Section -->
                <div id="createGameSection" class="section d-none p-4">
                    <h2 class="text-center mb-4">Create a New Game</h2>
                    <div class="form-group mb-3">
                        <label for="gameName">Game Name:</label>
                        <input type="text" class="form-control" id="gameName" placeholder="Enter game name (optional)">
                    </div>
                    <div class="form-group mb-3">
                        <label for="visibility">Visibility:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visibility" id="publicVisibility" value="public" checked>
                            <label class="form-check-label" for="publicVisibility">
                                Public
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visibility" id="privateVisibility" value="private">
                            <label class="form-check-label" for="privateVisibility">
                                Private
                            </label>
                        </div>
                    </div>
                    <div class="form-group mb-3" id="passwordField">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" placeholder="Enter password">
                    </div>
                    <div class="form-group mb-3">
                        <label for="gameMode">mode:</label>
                        <select class="form-control" id="gameMode">
                            <option value="blitz">Blitz (5 mins)</option>
                            <option value="rapid">Rapid (10 mins)</option>
                            <option value="classic">Classic (30 mins)</option>
                        </select>
                    </div>
                    <button id="createGameConfirmBtn" class="btn btn-primary">Create Game</button>
                </div>

                <!-- Find Game Section -->
                <div id="findGameSection" class="section d-none p-4">
                    <h2 class="text-center mb-4">Find a Game</h2>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="searchBar" placeholder="Search games...">
                        <button class="btn btn-outline-secondary" type="button" id="searchButton">Search</button>
                    </div>
                    <ul id="gameList" class="list-group">
                        @foreach ($rooms as $room)
                            <li class="list-group-item" style="{{ ($room->status == 'waiting') ? 'cursor: pointer' : 'opacity: 0.5; cursor: not-allowed' }}" data-room-code="{{ ($room->code) }}" data-room-name="{{ $room->name }}" data-room-has-password="{{ !empty($room->password) }}">{{ $room->name }} - {{ ucwords($room->status) }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Join Game Section -->
                <div id="joinGameSection" class="section d-none p-4">
                    <h2 class="text-center mb-4">Join Game via Code</h2>
                    <div class="form-group mb-3">
                        <label for="roomCode">Enter Room Code:</label>
                        <input type="text" class="form-control" id="roomCode" placeholder="Enter code here">
                    </div>
                    <button id="joinGameConfirmBtn" class="btn btn-primary">Join Game</button>
                </div>

                <!-- Game History Section -->
                <div id="gameHistorySection" class="section d-none p-4">
                    <h2 class="text-center mb-4">Your Game History</h2>
                    <ul id="historyList" class="list-group">
                        <!-- Dynamic history list items will be added here -->
                    </ul>
                </div>

            </div>
        </div>
    </div>
</div>


    <script>
        // script.js
        document.addEventListener('DOMContentLoaded', function () {
            const createGameBtn = document.getElementById('createGameBtn');
            const findGameBtn = document.getElementById('findGameBtn');
            const joinGameBtn = document.getElementById('joinGameBtn');
            const gameHistoryBtn = document.getElementById('gameHistoryBtn');

            const createGameSection = document.getElementById('createGameSection');
            const findGameSection = document.getElementById('findGameSection');
            const joinGameSection = document.getElementById('joinGameSection');
            const gameHistorySection = document.getElementById('gameHistorySection');

            createGameBtn.addEventListener('click', () => {
                showSection(createGameSection);
            });

            findGameBtn.addEventListener('click', () => {
                showSection(findGameSection);
            });

            joinGameBtn.addEventListener('click', () => {
                showSection(joinGameSection);
            });

            gameHistoryBtn.addEventListener('click', () => {
                showSection(gameHistorySection);
                loadGameHistory(); // Simulate loading game history
            });

            document.getElementById('createGameConfirmBtn').addEventListener('click', function() {
                // Get form values
                var gameName = document.getElementById('gameName').value;
                var visibility = document.querySelector('input[name="visibility"]:checked').value;
                var password = document.getElementById('password').value;
                var gameMode = document.getElementById('gameMode').value;
                // Send AJAX request to create game and join
                fetch('/create-game', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name: gameName,
                        visibility: visibility,
                        password: password,
                        gameMode: gameMode
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to game room or update UI as needed
                        // You can use data.roomId to redirect
                        console.log('Game created successfully!', data);
                        window.location.href = '/game/' + data.roomCode;
                    } else {
                        alert(data.message); // Display error message
                    }
                })
                .catch(error => {
                    console.error('Error creating game:', error);
                    if (error.response) {
                        if (error.response.status === 422) { // Validation error
                            const errors = error.response.data.errors;
                            // Display specific validation errors in the UI
                        } else {
                            alert(`Server error: ${error.response.status} - ${error.response.statusText}`);
                        }
                    } else {
                        alert('An error occurred. Please check your connection.');
                    }
                });
            });


            // This to help other Function
            function ucwords(str) {
                return str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                });
            }

            function attachGameEventListener(li, game) {
                if (game.status === 'waiting') {
                    li.style.cursor = 'pointer';
                    li.addEventListener('click', () => {
                        let roomCode = li.dataset.roomCode;
                        let roomName = li.dataset.roomName;
                        let roomHasPassword = li.dataset.roomHasPassword === 'true';

                        console.log('Room Code:', roomCode);
                        console.log('Room Name:', roomName);
                        console.log('Room Has Password:', roomHasPassword);

                        if(roomHasPassword){
                            swal.fire({
                                title: 'Enter Password',
                                input: 'password',
                                inputLabel: 'Please enter the password to join this game',
                                inputPlaceholder: 'Enter password',
                                showCancelButton: true,
                                confirmButtonText: 'Join Game',
                                cancelButtonText: 'Cancel',
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'Password is required!';
                                    }
                                }
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const password = result.value;
                                    // Send a request to join the game with the provided password
                                    fetch(`/joinGame/${roomCode}`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                        },
                                        body: JSON.stringify({ password: password })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Redirect to game room
                                            window.location.href = `/game/${roomCode}`;
                                        } else {
                                            Swal.fire('Error', data.message, 'error');
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error joining game:', error);
                                        Swal.fire('Error', 'An error occurred while joining the game.', 'error');
                                    });
                                }
                            })
                        } else {
                            // Send a request to the server to join the game
                            fetch(`/joinGame/${roomCode}`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        window.location.href = `/game/${roomCode}`;
                                    } else {
                                        Swal.fire('Error', data.message, 'error');
                                    }
                                })
                                .catch(error => {
                                    console.error('Error joining game:', error);
                                    Swal.fire('Error', 'An error occurred while joining the game.', 'error');
                                });
                        }
                    });
                } else {
                    li.style.opacity = '0.5';
                    li.style.cursor = 'not-allowed';
                }
            }

            function showSection(section) {
                createGameSection.classList.add('d-none');
                findGameSection.classList.add('d-none');
                joinGameSection.classList.add('d-none');
                gameHistorySection.classList.add('d-none');

                if (section) {
                    section.classList.remove('d-none');
                }
            }

            function loadGameList() {
                const gameList = document.getElementById('gameList');

                fetch('/battle/room_list') // Replace with your actual endpoint
                    .then(response => response.json())
                    .then(games => {
                        gameList.innerHTML = '';
                        games.forEach(game => {
                            const li = document.createElement('li');
                            li.classList.add('list-group-item');
                            li.textContent = `${game.name} - ${ucwords(game.status)}`;
                            li.dataset.roomCode = game.code;
                            li.dataset.roomName = game.name;
                            li.dataset.roomHasPassword = !!game.password;
                            attachGameEventListener(li, game);
                            gameList.appendChild(li);
                        });
                    });
            }

            function loadGameHistory() {
                const historyList = document.getElementById('historyList');
                historyList.innerHTML = '';

                // Simulate dynamic content
                const history = [
                    { name: 'Game 1', result: 'Won' },
                    { name: 'Game 2', result: 'Lost' }
                ];

                history.forEach(game => {
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = `${game.name} - ${game.result}`;
                    li.addEventListener('click', () => {
                        alert(`Viewing history of ${game.name}`);
                        // Logic to view game history
                    });
                    historyList.appendChild(li);
                });
            }
            // Set Find Game as the default section
            createGameSection.classList.add('d-none');
            joinGameSection.classList.add('d-none');
            gameHistorySection.classList.add('d-none');
            showSection(findGameSection);
            setInterval(loadGameList, 500);
        });
    </script>
</x-home.layout>

