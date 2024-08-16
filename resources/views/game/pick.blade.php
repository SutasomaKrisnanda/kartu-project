<body>
    <script>
        const cardContainer = document.getElementById('card-container');
        const timerElement = document.getElementById('timer');
        const acceptBtn = document.getElementById('accept-btn');

        let selectedCards = [];
        const totalCards = 15; // You can adjust the number of cards
        const timeLimit = 120; // 2 minutes in seconds
        let timeRemaining = timeLimit;

        // Generate cards
        for (let i = 1; i <= totalCards; i++) {
            const card = document.createElement('div');
            card.classList.add('card');
            card.textContent = `Card ${i}`;
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
                acceptBtn.disabled = selectCard.length !== 5;
            }
        }

        // Timer logic
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

        // Auto-complete selection with random cards
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
            // Proceed to the next phase of the game
        });

        // Start the game
        startTimer();
    </script>
