// load the game
fetch('/start-game', {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(response => response.text())
    .then(html => {
        document.getElementById('game-container').style.display = 'flex';
        document.getElementById('game-container').innerHTML = html;
        pageLoaded();
    });

function pageLoaded(){
    let locked = false;
    let timer = 10;
    let timeCount = 0;
    let timerInterval, updateInterval;
    let moveCount = 0;
    let successCount = 0;
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
            fetch('/update-game-status/card', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ cardChosed : card })
            }).then(response => response.json()).then(data => {
                timeCount = timer;
                waitGameStatus();
                console.log(data.message);

                console.log("Move " + (++moveCount) + " : " + card);
            })
            console.log('Fetch to Update Game');
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
        fetch('/game-status', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => response.json()).then(data => {
                console.log(data);
                if(data.success){
                    successCount++; // Increment counter ketika success = true
                    if(successCount >= 2){
                        clearInterval(updateInterval); // Hentikan interval setelah 2 kali success = true
                        return;
                    }
                }
                updateInGameStatus(data);
            });
    }

    function updateInGameStatus(data){
        const playerLifePercent = data.player.hp * 12.5 + '%';
        const opponentLifePercent = data.opponent.hp * 12.5 + '%';

        document.documentElement.style.setProperty('--player-life-width', playerLifePercent);
        document.documentElement.style.setProperty('--opponent-life-width', opponentLifePercent);

        document.querySelector('.bar-player-game .hp-left-game').style.width = playerLifePercent;
        document.querySelector('.bar-opponent-game .hp-left-game').style.width = opponentLifePercent;
    }
}
