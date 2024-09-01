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
            height: 100vh;
            margin: 0;
        }
    </style>
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css" >
    <link rel="stylesheet" href="{{ asset('css/game/load.css') }}">
    <link rel="stylesheet" href="{{ asset('css/game/pick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/game/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/game/small.css') }}">
    <link rel="stylesheet" href="{{ asset('css/game/medium.css') }}">
    <link rel="stylesheet" href="{{ asset('css/game/large.css') }}">
</head>
<body>
    <div id="game-container" style="display: none;"></div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const loadingBody = document.createElement('div');
        const loadingContainer = document.createElement('div');
        const loadingSpinner = document.createElement('div');
        const loadingText = document.createElement('div');
        const dots = document.createElement('span');
        loadingBody.classList.add('body-loading');
        loadingContainer.classList.add('loading-container');
        loadingSpinner.classList.add('loading-spinner');
        loadingText.classList.add('loading-text');
        loadingText.textContent = 'Waiting for Opponent';
        dots.classList.add('dots');
        dots.textContent = '...';
        loadingBody.appendChild(loadingContainer);
        loadingText.appendChild(dots);
        loadingContainer.append(loadingSpinner, loadingText);

        // Add Loading Screen
        document.body.appendChild(loadingBody);
        const loadGame = setInterval(async function() {
            try {
                const response = await fetch('/check-room', {
                    headers: {
                        'X-CSRF-TOKEN': CSRF
                    }
                });
                const data = await response.json();
                if(data.inProgress) {
                    clearInterval(loadGame);
                    document.querySelector('.body-loading').remove();

                    // Load game.js when the game starts
                    const script = document.createElement('script');
                    script.src = "{{ asset('js/game/game.js') }}";
                    document.body.appendChild(script);
                } else
                if (data.startGame) {
                    clearInterval(loadGame);
                    document.querySelector('.body-loading').remove();

                    // Load pick.js when the game starts
                    const script = document.createElement('script');
                    script.src = "{{ asset('js/game/pick.js') }}";
                    document.body.appendChild(script);
                }
            } catch (error) {
                console.error('Error checking room or fetching game content:', error);
            }
        }, 3000);
    </script>
</body>
</html>
