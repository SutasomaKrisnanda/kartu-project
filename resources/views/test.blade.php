<!DOCTYPE html>
<html lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/game/common.css">
    <link rel="stylesheet" href="/css/game/small.css">
    <link rel="stylesheet" href="/css/game/medium.css">
    <link rel="stylesheet" href="/css/game/large.css">
    <title>Game</title>
<body>
    <div class='container-game'>
        <div class='play-area-game'>
            <div class="left-menu-container">
                <div class='left-menu-game'>
                    <div class="left-box">
                        <div class='menu-settings-game'><i class='fa-solid fa-bars'></i></div>
                        <div class='menu-history-game'><i class='fa-solid fa-clock-rotate-left'></i></div>
                    </div>
                    <div class="history-box">
                        <div class="dummy-card-game" data-card-token="test">
                            <img src="/images/card/test-card.png" alt="test" title="test">
                        </div>
                        <div class="dummy-card-game" data-card-token="test">
                            <img src="/images/card/test-card.png" alt="test" title="test">
                        </div>
                        <div class="dummy-card-game" data-card-token="test">
                            <img src="/images/card/test-card.png" alt="test" title="test">
                        </div>
                        <div class="dummy-card-game" data-card-token="test">
                            <img src="/images/card/test-card.png" alt="test" title="test">
                        </div>
                        <div class="dummy-card-game" data-card-token="test">
                            <img src="/images/card/test-card.png" alt="test" title="test">
                        </div>
                        <div class="dummy-card-game" data-card-token="test">
                            <img src="/images/card/test-card.png" alt="test" title="test">
                        </div>
                        <div class="dummy-card-game" data-card-token="test">
                            <img src="/images/card/test-card.png" alt="test" title="test">
                        </div>
                    </div>
                </div>
            </div>
            <div class='left-view-game'>
                <div class='bar-player-game'>
                    <div class='hp-left-game' style="width: 100%;"></div>
                    <img src='/images/game/bar-hp.png' alt='Bar'>
                </div>
                <div class='player-name-game'>Player</div>
                <div class='player-effect-game'>Some Effect</div>
            </div>
            <div class='center-view-game'>
                <div class='opponent-play-game'>
                    <div class='dummy-played-game'><div class='pl-text'>Played</div></div>
                </div>
                <div class='player-play-game'>
                    <div class='dummy-played-game'><div class='pl-text'>Played</div></div>
                </div>
            </div>
            <div class='right-view-game'>
                <div class='bar-opponent-game'>
                    <div class='hp-left-game' style="width: 100%;"></div>
                    <img src='/images/game/bar-hp.png' alt='Bar'>
                </div>
                <div class='opponent-name-game'>Opponent</div>
                <div class='opponent-effect-game'>Some Effect</div>
            </div>
            <div class='right-menu-game'>
                <div class='dummy-cooldown-game'><div class='cd-text'>Cooldown</div></div>
            </div>
        </div>
        <div class='card-area-game'>
            <div class='card-container-game'>
                <div class='cooldown-player-game'>
                    <div class='dummy-cooldown-game'><div class='cd-text'>Cooldown</div></div>
                </div>
                <div class='card-ready-game'>
                    @foreach($cards as $card)
                    <div class="dummy-card-game" data-card-token="{{ $card->token }}">
                        <img src="{{ $card->image }}" alt="{{ $card->name }}" title="{{ $card->name }}">
                    </div>
                    @endforeach
                </div>
                <div class='right-card-container-game'></div>
            </div>
        </div>
    </div>

    <div class="menu-box">
        <div class="menu-content">
            <section class="timer-game"> 00 : 00</section>
            <section class="menu-section-1">Music : ON</section>
            <section class="menu-section-2">Volume : 50%</section>
            <section class="menu-section-3">Exit</section>
        </div>
    </div>

    <script>

    // Initialize Variable
    let locked = false;
    let timer = 10;
    let timeCount = 0;
    let timerInterval, updateInterval;
    let moveCount = 0;
    const playerChosed = document.querySelector('.player-play-game .dummy-played-game');


    document.querySelectorAll('.dummy-card-game').forEach(each => {
        each.addEventListener('click', () => {
            if(!locked){
                playerChosed.dataset.cardToken = each.dataset.cardToken;
                playerChosed.innerHTML = each.innerHTML;

                const checkIcon = document.createElement('i');
                checkIcon.className = 'fa-solid fa-check';

                const loadCircle = document.createElement('div');
                loadCircle.className = 'load-circle';

                loadCircle.appendChild(checkIcon);
                playerChosed.appendChild(loadCircle);

                confirmCardListener(checkIcon);
                if(document.querySelector('.dummy-card-game.hidden')) {
                    document.querySelector('.dummy-card-game.hidden').classList.toggle('hidden');
                }
                each.classList.toggle('hidden');
            }
        });
    });


    document.querySelector('.menu-settings-game').addEventListener('click', () => {
        document.querySelector('.menu-settings-game').classList.toggle('opened');
        document.querySelector('.menu-box').classList.toggle('active');
    });

    document.querySelector('.menu-history-game').addEventListener('click', () => {
        document.querySelector('.left-menu-game').classList.toggle('active');
    });

    // Call startTimer() to initially start the timer
    startTimer();

    function cardConfirmed(){
        if(playerChosed.dataset.cardToken){
            const icon = playerChosed.querySelector('i');
            icon.classList.remove('fa-check');
            icon.classList.add('fa-lock');
            locked = true;

            const card = playerChosed.dataset.cardToken;
            // fetch('/update-game-status/card', {
            //     method: 'POST',
            //     headers: {
            //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            //         'Content-Type': 'application/json'
            //     },
            //     body: JSON.stringify({ cardChosed : card })
            // }).then(response => response.json()).then(data => {
            //     waitGameStatus();
            //     console.log(data.message);
            // })
            console.log('Fetch to Update Game');
            timeCount = timer;
            setTimeout(() => {
                addCooldown()
            }, 5000)
            console.log("Move " + (++moveCount) + " : " + card);
        } else {
            playerChosed.innerHTML= ''
            // setTimeout(() =>{
                startTimer()
            // }, 1000)
            playerChosed.removeAttribute('data-card-token');
            console.log("Move " + (++moveCount) + " : Skipped");

            return;
        }
    }

    function confirmCardListener(element){
        element.onclick = ()=>{
            document.documentElement.style.setProperty('--progress', `100`);
            cardConfirmed();
        }
    }

    function addCooldown(){
        // For medium and large screen
        const cooldown = document.querySelector('.cooldown-player-game .dummy-cooldown-game');
        playerChosed.lastChild.remove();
        cooldown.innerHTML = playerChosed.innerHTML;
        playerChosed.innerHTML= '';
        playerChosed.removeAttribute('data-card-token');

        const cooldownHero = document.createElement('div');
        cooldownHero.classList.add('cooldown-count');
        cooldownHero.textContent = '1';
        cooldown.appendChild(cooldownHero.cloneNode(true));
        addCooldownTimer(cooldown);

        // // For small screen
        const cardHidden = document.querySelector('.dummy-card-game.hidden');
        cardHidden.appendChild(cooldownHero);
        addCooldownTimer(cardHidden);
    }

    function addCooldownTimer(element){
        let cooldownCount = 1;
        setTimeout(() => {
            document.querySelector('.cooldown-player-game .dummy-cooldown-game').innerHTML = '';
            let cooldownCount = element.querySelector('.cooldown-count');
            if(cooldownCount) {
                cooldownCount.remove();
                element.classList.remove('hidden');
            }
            console.log('cooldown over!');
            locked = false;
            startTimer();
        }, cooldownCount*2000);
    }

    function startTimer() {
        clearInterval(timerInterval);
        timeCount = 0; // Reset timeCount
        timerInterval = setInterval(() => {
            timeCount++;
            document.documentElement.style.setProperty('--progress', `${timeCount / timer * 100}`);
            if (timeCount >= timer && !locked) {
                cardConfirmed();
            }
            console.log(timeCount)
        }, 1000);
        // return timeCount;
    }

    function waitGameStatus(){
        updateInterval = setInterval(getGameStatus, 500);
    }

    function getGameStatus(){
        // fetch('/game-status', {
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        //     }
        // }).then(response => response.json()).then(data => {
        //         console.log(data);
        //         if(data.success){
        //             clearInterval(updateInterval);
        //             getGameStatus();
        //         }
        //         updateInGameStatus(data);
        //     });
    }

    function updateInGameStatus(data){
        const playerLifePercent = data.player.hp * 12.5 + '%';
        const opponentLifePercent = data.opponent.hp * 12.5 + '%';

        document.documentElement.style.setProperty('--player-life-width', playerLifePercent);
        document.documentElement.style.setProperty('--opponent-life-width', opponentLifePercent);

        document.querySelector('.bar-player-game .hp-left-game').style.width = playerLifePercent;
        document.querySelector('.bar-opponent-game .hp-left-game').style.width = opponentLifePercent;
    }

    </script>
</body>
</html>
