
const gameContainer = document.createElement('div');
const pickContainer = document.createElement('div');
const timerElement = document.createElement('div');
const cardContainer = document.createElement('div');
const acceptBtn = document.createElement('button');
gameContainer.classList.add('body-game');
pickContainer.classList.add('pick-container');
timerElement.classList.add('timer');
cardContainer.classList.add('card-container');
timerElement.id = 'timer';
timerElement.textContent = '02:00';
cardContainer.id = 'card-container';
acceptBtn.id = 'accept-btn';
acceptBtn.textContent = 'Accept';
acceptBtn.disabled = true;
pickContainer.append(timerElement, cardContainer, acceptBtn);
gameContainer.appendChild(pickContainer);
document.body.appendChild(gameContainer);


let selectedCards = [];
const timeLimit = 120;
let timeRemaining = timeLimit;

fetch('/get-cards', { headers: { 'X-CSRF-TOKEN': CSRF } })
    .then(response => response.json())
    .then(cardsData => {
        cardsData.forEach(cardData => {
            const card = document.createElement('div');
            card.classList.add('card');
            card.dataset.cardToken = cardData.token;

            const img = document.createElement('img');
            img.src = cardData.image;
            img.alt = cardData.name;
            card.appendChild(img);
            card.addEventListener('click', () => selectCard(card));
            cardContainer.appendChild(card);
        });
    });

function selectCard(card) {
    if (selectedCards.length < 5 || card.classList.contains('selected')) {
        if (!card.classList.contains('selected')) {
            card.classList.add('selected');
            selectedCards.push(card);
        } else {
            card.classList.remove('selected');
            selectedCards = selectedCards.filter(c => c !== card);
        }
    }
    // Pastikan selectedCards hanya berisi kartu yang saat ini memiliki class 'selected'
    selectedCards = Array.from(document.querySelectorAll('.card.selected'));
    acceptBtn.disabled = selectedCards.length !== 5;
}


function startTimer() {
    const timerInterval = setInterval(() => {
        timeRemaining--;
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

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
        acceptBtn.click();
    }
    acceptBtn.disabled = false;
}

acceptBtn.addEventListener('click', async () => {
    acceptBtn.removeEventListener('click', arguments.callee);
    selectedCards = Array.from(document.querySelectorAll('.card.selected'));

    if (selectedCards.length !== 5) {
        alert('Please select exactly 5 cards.');
        return;
    }

    const selectedCardTokens = selectedCards.map(c => c.dataset.cardToken);
    try {
        const response = await fetch('/start-game', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF
            },
            body: JSON.stringify({ selectedCards: selectedCardTokens })
        });
        const data = await response.json();
        document.getElementById('game-container').style.display = 'flex';
        document.getElementById('game-container').innerHTML = data.html;
        const script = document.createElement('script');
        script.src = '/js/game/game.js';
        document.body.appendChild(script);
        gameContainer.remove();
    } catch (error) {
        console.error('Error starting game:', error);
        alert('An error occurred while starting the game: ' + error.message);
    }
});




startTimer();
