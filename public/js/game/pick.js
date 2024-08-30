// Membuat elemen-elemen HTML
const gameContainer = document.createElement('div');
gameContainer.classList.add('body-game');

const pickContainer = document.createElement('div');
pickContainer.classList.add('pick-container');

const timerElement = document.createElement('div'); // Menggunakan timerElement langsung untuk konsistensi
timerElement.classList.add('timer');
timerElement.id = 'timer';
timerElement.textContent = '02:00';

const cardContainer = document.createElement('div'); // Menggunakan cardContainer langsung untuk konsistensi
cardContainer.classList.add('card-container');
cardContainer.id = 'card-container';

const acceptBtn = document.createElement('button');
acceptBtn.id = 'accept-btn';
acceptBtn.textContent = 'Accept';
acceptBtn.disabled = true;

// Menambahkan elemen anak ke dalam container
pickContainer.appendChild(timerElement); // Menggunakan timerElement
pickContainer.appendChild(cardContainer); // Menggunakan cardContainer
pickContainer.appendChild(acceptBtn);
gameContainer.appendChild(pickContainer);

// Menambahkan gameContainer ke dalam body (atau elemen lain yang Anda inginkan)
document.body.appendChild(gameContainer);

let selectedCards = [];
const timeLimit = 120;
let timeRemaining = timeLimit;

// Fetch the cards data from the server
fetch('/get-cards', {
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    }
})
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
    if (selectedCards.length < 5) {
        if (!card.classList.contains('selected')) {
            card.classList.add('selected');
            selectedCards.push(card);
        } else {
            card.classList.remove('selected');
            selectedCards = selectedCards.filter(c => c !== card);
        }
    } else {
        if (card.classList.contains('selected')) {
            card.classList.remove('selected');
            selectedCards = selectedCards.filter(c => c != card);
        }
    }
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

acceptBtn.addEventListener('click', () => {
    const selectedCardTokens = selectedCards.map(c => c.dataset.cardToken);

    const csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
    fetch('/start-game', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ selectedCards: selectedCardTokens })
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            return response.json().then(errorData => {
                throw new Error(errorData.message);
            });
        }
    })
    .then(data => {
        document.getElementById('game-container').style.display = 'flex';
        document.getElementById('game-container').innerHTML = data.html;

        const script = document.createElement('script');
        script.src = '/js/game/game.js';
        document.body.appendChild(script);

        gameContainer.remove(); // Menghapus gameContainer setelah game dimulai
    })
    .catch(error => {
        console.error('Error starting game:', error);
        alert('An error occurred while starting the game: ' + error.message);
    });
});

startTimer();
