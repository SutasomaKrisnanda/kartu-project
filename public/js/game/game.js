fetch('/start-game', {
    headers: {
        'X-CSRF-TOKEN': CSRF
    }
}).then(response => response.text())
    .then(html => {
        document.getElementById('game-container').style.display = 'flex';
        document.getElementById('game-container').innerHTML = html;
        pageLoaded();
    });

function pageLoaded() {
    // Initialize variable
    let locked = false, timer = 60, timeCount = 0, timerInterval, updateInterval, moveCount = 0, successCount = 0;
    const playerChosed = document.querySelector('.player-play-game .dummy-played-game');
    const targetPlayerCd = document.querySelector('.cooldown-player-game .dummy-cooldown-game');
    const targetOpponentCd = document.querySelector('.right-menu-game .dummy-cooldown-game');
    const waitGameStatus = () => updateInterval = setInterval(getGameStatus, 500);
    const updateCooldown = (target, cooldown, hero) => {
        const img = document.createElement('img');
        img.src = cooldown[0].image;
        img.alt = cooldown[0].name;
        img.title = cooldown[0].name;
        target.firstChild.replaceWith(img);
        target.dataset.cardToken = cooldown[0].token;
        hero.textContent = cooldown[0].duration;
        target.appendChild(hero.cloneNode(true));
    };

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


    // Call startTimer() to initially start the timer
    startTimer();

    function cardConfirmed() {
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
                waitGameStatus();
                console.log("Move " + (++moveCount) + " : " + card);
            })
        } else {
            playerChosed.innerHTML = ''
            startTimer()
            playerChosed.removeAttribute('data-card-token');
            console.log("Move " + (++moveCount) + " : Skipped");
        }
    }

    function confirmCardListener(element) {
        element.onclick = () => {
            document.documentElement.style.setProperty('--progress', `100`);
            if (!locked) cardConfirmed();
        }
    }

    function startTimer() {
        clearInterval(timerInterval);
        timeCount = 0;
        timerInterval = setInterval(() => {
            timeCount++;
            document.documentElement.style.setProperty('--progress', `${timeCount / timer * 100}`);
            if (timeCount >= timer && !locked) {
                cardConfirmed();
            }
        }, 1000);
    }

    function getGameStatus() {
        fetch('/game-status', { headers: { 'X-CSRF-TOKEN': CSRF } })
            .then(response => response.json()).then(data => {
                if (data.success) {
                    console.log(data);
                    updateInGameStatus(data);
                    locked = false;
                    if (++successCount >= 2) {
                        clearInterval(updateInterval);
                        successCount = 0;
                    }
                }
            });
    }

    function updateInGameStatus(data) {
        playerChosed.innerHTML = '';
        playerChosed.removeAttribute('data-card-token');

        const { playerCooldown, opponentCooldown } = data;
        const cooldownHero = document.createElement('div');
        cooldownHero.classList.add('cooldown-count');

        // Set the HP value
        document.querySelector('.bar-player-game .hp-left-game').style.width = data.player.hp * 12.5 + '%';
        document.querySelector('.bar-opponent-game .hp-left-game').style.width = data.opponent.hp * 12.5 + '%';
        // Set Cooldown
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
        checkGameStatus();
    }
}
