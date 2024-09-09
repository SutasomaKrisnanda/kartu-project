fetch('/start-game', {
    headers: {
        'X-CSRF-TOKEN': CSRF
    }
}).then(response => response.json())
    .then(json => {
        const time = new Date(json.time);
        const startTime = new Date(json.start);
        const html = json.html; // Ini sudah berupa HTML
        document.getElementById('game-container').style.display = 'flex';
        document.getElementById('game-container').innerHTML = html;
        pageLoaded(time, startTime);
    });


function pageLoaded(time, startTime) {

    const timerElement = document.querySelector('.timer-game');
    let timerInterval,
        locked = false,
        timer = 300,
        timeCount = 0,
        updateInterval,
        moveCount = 0,
        successCount = 0;

    function startTimer() {
        clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            if (!locked) {
                const now = new Date();
                const elapsedTime = Math.floor((now - time) / 1000) % timer;
                console.log(Math.floor((now - time) / 1000));
                timeCount = elapsedTime;
                document.documentElement.style.setProperty('--progress', `${timeCount / timer * 100}`);
                if (timeCount >= (timer - 1)) {
                    cardConfirmed();
                }
            }
        }, 1000);
    }

    function updateTimer(time) {
        const diffInSeconds = Math.floor((new Date() - startTime) / 1000);
        const hours = Math.floor(diffInSeconds / 3600);
        const minutes = Math.floor((diffInSeconds % 3600) / 60);
        const seconds = diffInSeconds % 60;
        let timeText = hours > 0 ? `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}` : `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        timerElement.textContent = timeText;
    }

    // Initialize timer and update it every second
    let TimerGameInterval = setInterval(() => updateTimer(time), 1000);

    // Initialize variable
    const playerChosed = document.querySelector('.player-play-game .dummy-played-game');
    const targetPlayerCd = document.querySelector('.cooldown-player-game .dummy-cooldown-game');
    const targetOpponentCd = document.querySelector('.right-menu-game .dummy-cooldown-game');
    const waitGameStatus = () => updateInterval = setInterval(getGameStatus, 500);
    const updateCooldown = (target, cooldown, hero) => {
        const img = document.createElement('img');
        img.src = cooldown[0].image;
        img.alt = cooldown[0].name;
        img.title = cooldown[0].name;
        target.innerHTML = img.outerHTML;
        target.dataset.cardToken = cooldown[0].token;
        hero.textContent = cooldown[0].duration;
        target.appendChild(hero.cloneNode(true));
    };
    const addHistoryCard = (history, selector) => {
        const card = document.createElement('div');
        card.classList.add('history-card-game', history.win ? 'active' : 'nonactive');
        const img = document.createElement('img');
        img.src = history.move ? history.move.image : '/images/card/slot-skipped.webp';
        img.alt = history.move ? history.move.name : 'Skipped';
        img.title = history.move ? history.move.name : 'Skip';
        card.appendChild(img);
        document.querySelector(selector).prepend(card);
    };

    // For Debug
    // setInterval(() => {
    //     getGameStatus();
    // }, 500);

    checkGameStatus();
    function checkGameStatus() {
        if (document.querySelector('.bar-player-game .hp-left-game').style.width == '0%') {
            Swal.fire({
                title: 'You Lose!',
                icon: 'error'
            });
            locked = true;
            clearInterval(timerInterval);
        }
        if (document.querySelector('.bar-opponent-game .hp-left-game').style.width == '0%') {
            Swal.fire({
                title: 'You Win!',
                icon: 'success'
            });
            locked = true;
            clearInterval(timerInterval);
        }
    }

    document.querySelectorAll('.dummy-card-game').forEach(each => {
        each.addEventListener('click', () => {
            if (!locked && !each.classList.contains('cooldown')) {
                playerChosed.dataset.cardToken = each.dataset.cardToken;
                playerChosed.innerHTML = each.innerHTML;
                const checkIcon = document.createElement('i');
                checkIcon.className = 'fa-solid fa-check';
                const loadCircle = document.createElement('div');
                loadCircle.className = 'load-circle';
                loadCircle.appendChild(checkIcon);
                playerChosed.appendChild(loadCircle);
                confirmCardListener(checkIcon);
                document.querySelector('.dummy-card-game.hidden')?.classList.toggle('hidden');
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
    document.querySelector('.skip-button-game').addEventListener('click', () => {
        if (!locked) {
            document.querySelector('.skip-button-game').classList.toggle('skip');
            console.log('card confirmed from skip button');
            cardConfirmed();
        }
    });


    startTimer();

    function cardConfirmed() {
        console.log('card confirmed')
        if (playerChosed.dataset.cardToken) {
            const icon = playerChosed.querySelector('i');
            icon.classList.remove('fa-check');
            icon.classList.add('fa-lock');
            locked = true;
            const card = playerChosed.dataset.cardToken;
            fetch('/update-game-status/card', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ cardChosed: card })
            }).then(response => response.json()).then(data => {
                timeCount = timer;
                successCount = 0
                waitGameStatus();
                console.log("Move " + (++moveCount) + " : " + card);
            })
        } else {
            fetch('/update-game-status/skip', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ cardChosed: false })
            }).then(response => response.json()).then(data => {
                timeCount = timer;
                successCount = 0
                waitGameStatus();
                console.log("Move " + (++moveCount) + " : Skipped");
            })
            playerChosed.innerHTML = ''
            playerChosed.removeAttribute('data-card-token');
        }
    }

    function confirmCardListener(element) {
        element.onclick = () => {
            document.documentElement.style.setProperty('--progress', `100`);
            console.log('card confirmed from Confirm Button');
            if (!locked) cardConfirmed();
        }
    }



    function getGameStatus() {
        console.log('fetching');
        fetch('/game-status', { headers: { 'X-CSRF-TOKEN': CSRF } })
            .then(response => response.json()).then(data => {
                if (data.success) {
                    updateInGameStatus(data);
                    if (++successCount >= 2) {
                        clearInterval(updateInterval);
                        locked = false;
                        time = new Date();
                        return;
                    }
                    console.log(data.message);
                    // locked = false;
                } else {
                    console.log(data.message);
                }
            });
    }

    function updateInGameStatus(data) {
        playerChosed.innerHTML = '';
        playerChosed.removeAttribute('data-card-token');

        const { playerCooldown, opponentCooldown } = data;
        const cooldownHero = document.createElement('div');
        cooldownHero.classList.add('cooldown-count');

        document.querySelector('.bar-player-game .hp-left-game').style.width = data.player.hp * 12.5 + '%';
        document.querySelector('.bar-opponent-game .hp-left-game').style.width = data.opponent.hp * 12.5 + '%';
        updateCooldown(targetPlayerCd, playerCooldown, cooldownHero);
        updateCooldown(targetOpponentCd, opponentCooldown, cooldownHero);

        document.querySelectorAll('.dummy-card-game').forEach(card => {
            card.classList.remove('cooldown');
            card.querySelector('.cooldown-count')?.remove();
        });

        [playerCooldown, opponentCooldown].forEach(cooldownList => {
            cooldownList.forEach(cooldown => {
                const cardElement = document.querySelector('.dummy-card-game[data-card-token="' + cooldown.token + '"]');
                if (cardElement) {
                    cooldownHero.textContent = cooldown.duration;
                    cardElement.classList.add('cooldown');
                    cardElement.appendChild(cooldownHero.cloneNode(true));
                }
            })
        })

        let playerH = data.historya;
        let opponentH = data.historyb;

        document.querySelector('.player-history').innerHTML = '';
        Object.values(data.historya).forEach(history => addHistoryCard(history, '.player-history'));
        document.querySelector('.opponent-history').innerHTML = '';
        Object.values(data.historyb).forEach(history => addHistoryCard(history, '.opponent-history'));

        checkGameStatus();
    }
}
