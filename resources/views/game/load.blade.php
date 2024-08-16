<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Game Room</title>
    <style>
        /* reset */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

    </style>
    <link rel="stylesheet" href="{{ asset('css/game/load.css') }}">
    <link rel="stylesheet" href="{{ asset('css/game/pick.css') }}">
</head>
<body>
    @csrf
    <div class="body-loading">
        <div class="loading-container">
            <div class="loading-spinner"></div>
            <div class="loading-text">Waiting for opponent<span class="dots">...</span></div>
        </div>
    </div>
    <div class="body-game" style="display: none">
        <div id="game-container">
            <div class="game-container">
                <div class="timer" id="timer">02:00</div>
                <div class="card-container" id="card-container">
                    <!-- Cards will be generated here -->
                </div>
                <button id="accept-btn" disabled>Accept</button>
            </div>

        </div>
    </div>
    <script>
        // Check every 3 seconds
        const loadGame = setInterval(async function() {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch('/check-room', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                const data = await response.json();
                console.log(data);
                console.log(data.startGame);
                if (data.startGame) {
                    document.querySelector('.body-loading').style.display = 'none';
                    document.querySelector('.body-game').style.display = 'flex';
                    clearInterval(loadGame);
                    fetch('/pick', {
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.text()) // Get the HTML content as text
                    .then(jsCode => {
                        console.log(jsCode);
                        eval(jsCode);
                        // document.getElementById('game-container').innerHTML = html; // Inject the HTML
                    })
                    .catch(error => {
                        console.error('Error fetching game content:', error);
                    });
                }
            } catch (error) {
                console.error('Error checking room or fetching game content:', error);
                // Handle errors (e.g., display an error message)
            }
        }, 3000); // Check every 3 seconds
    </script>
</body>
</html>
