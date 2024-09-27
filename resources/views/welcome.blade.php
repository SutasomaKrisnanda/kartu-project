<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu - Game Project</title>

    <!-- Bootstrap & FontAwesome CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="shortcut icon" href="images/kartu-logo-medium.png">
    <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    <style>
        :root {
            --phone-height: 30vw;
            --phone-width: 15vw;
            --animation-duration: 25s;
        }

        /* Global Styling */
        body {
            overflow-x: hidden;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 2.5px;
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: #888 transparent;
        }

        @media (prefers-color-scheme: dark) {
            ::-webkit-scrollbar-thumb {
                background: #ccc;
            }

            * {
                scrollbar-color: #ccc transparent;
            }
        }

        /* Navbar */
        .navbar.sticky-top {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1020;
        }

        /* Button Back to Top */
        #btn-back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
        }

        /* Section 1 (Hero) */
        .sect-1 {
            display: flex;
            background-color: #00c880;
            height: calc(100vh + 3em);
            overflow: hidden;
            border-radius: 0 0 3em 3em;
            margin: 0 -12px;
        }

        .phone {
            height: var(--phone-height);
            width: var(--phone-width);
            margin: 20px;
            border-radius: 1.3em;
        }

        .phone img {
            height: 100%;
            width: 100%;
            object-fit: cover;
            border-radius: inherit;
        }

        .phone-container>.phone-flex {
            animation: slide-up var(--animation-duration) linear infinite;
        }

        .phone-container>.phone-flex:nth-child(2) {
            animation: slide-down var(--animation-duration) linear infinite;
        }

        .text-flex {
            width: 50%;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }

        .phone-container {
            display: flex;
            position: relative;
            width: 50%;
        }

        /* Animations */
        @keyframes slide-up {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(calc(-150vw - 3px));
            }
        }

        @keyframes slide-down {
            0% {
                transform: translateY(calc(-150vw - 3px));
            }

            100% {
                transform: translateY(0);
            }
        }

        .phone-flex {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-transparent sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="images/kartu-logo-medium.png" alt="Logo" height="50">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-3">
                        <a class="nav-link rounded-pill border border-light bg-light bg-opacity-50 fw-bold px-3 py-2"
                            href="#about">About</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link rounded-pill border border-light bg-light bg-opacity-50 fw-bold px-3 py-2"
                            href="login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link rounded-pill border border-light bg-light bg-opacity-50 fw-bold px-3 py-2"
                            href="register">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Back to Top Button -->
    <button type="button" class="btn btn-primary btn-lg" id="btn-back-to-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Main Section -->
    <div class="container-fluid">
        <section class="sect-1">
            <div class="phone-container">
                <div class="phone-flex">
                    @for ($i = 0; $i < 2; $i++)
                        <div class="phone"><img src="images/welcome/air.webp" alt="Air Element"></div>
                        <div class="phone"><img src="images/welcome/fire.webp" alt="Fire Element"></div>
                        <div class="phone"><img src="images/welcome/water.webp" alt="Water Element"></div>
                        <div class="phone"><img src="images/welcome/earth.webp" alt="Earth Element"></div>
                        <div class="phone"><img src="images/welcome/crown.webp" alt="Crown Icon"></div>
                    @endfor
                </div>
                <div class="phone-flex">
                    @for ($i = 0; $i < 2; $i++)
                        <div class="phone"><img src="images/welcome/earth.webp" alt="Earth Element"></div>
                        <div class="phone"><img src="images/welcome/water.webp" alt="Water Element"></div>
                        <div class="phone"><img src="images/welcome/fire.webp" alt="Fire Element"></div>
                        <div class="phone"><img src="images/welcome/air.webp" alt="Air Element"></div>
                        <div class="phone"><img src="images/welcome/crown.webp" alt="Crown Icon"></div>
                    @endfor
                </div>
                <div class="phone-flex">
                    @for ($i = 0; $i < 2; $i++)
                        <div class="phone"><img src="images/welcome/fire.webp" alt="Fire Element"></div>
                        <div class="phone"><img src="images/welcome/water.webp" alt="Water Element"></div>
                        <div class="phone"><img src="images/welcome/earth.webp" alt="Earth Element"></div>
                        <div class="phone"><img src="images/welcome/crown.webp" alt="Crown Icon"></div>
                        <div class="phone"><img src="images/welcome/air.webp" alt="Air Element"></div>
                    @endfor
                </div>
            </div>
            <div class="text-flex">
                <div>
                    <h1>Game Project</h1>
                    <p>Simple Card Game</p>
                    <small>Presentasi ini bersifat sementara</small>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <div class="row bg-light text-dark" id="about" style="height: 80vh;">
            <div class="col-md-12 d-flex flex-column justify-content-center align-items-center text-center">
                <h1>Kartu Game Project</h1>
                <hr class="w-75">
                <p class="lead text-muted">Dare to face the ultimate card game challenge with Card Game Project. Armed
                    with only 5 cards, you'll need quick thinking and strategic prowess to emerge victorious. Can you
                    master the elements and become the ultimate card master?</p>
            </div>
        </div>

        <!-- Game Showcase -->
        <div class="row bg-dark text-light" id="game-showcase"
            style="height: calc(100vh + 3em); padding-top: 3em; border-radius: 3em 3em 0 0;">
            <div class="col-md-12 d-flex flex-column justify-content-center align-items-center text-center">
                <h1>Game Showcase</h1>
                <hr class="w-75">
                <section class="splide" aria-label="Splide Basic HTML Example"
                    style="width: 50vw; height: 28.125vw;">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <li class="splide__slide"><img src="/images/welcome/beta-1.webp" alt="Beta invitation"
                                    class="img-fluid"></li>
                            <li class="splide__slide"><img src="/images/welcome/beta-2.webp" alt="Beta invitation"
                                    class="img-fluid"></li>
                            <li class="splide__slide"><img src="/images/welcome/beta-3.webp" alt="Beta invitation"
                                    class="img-fluid"></li>
                            <li class="splide__slide"><img src="/images/welcome/beta-4.webp" alt="Beta invitation"
                                    class="img-fluid"></li>
                        </ul>
                    </div>
                </section>
                <p class="lead text-center px-5"
                style="font-family: 'Arial', sans-serif; font-weight: 400; line-height: 1.6; margin-top: 20px;">
                Here's a glimpse of how the game looks. You'll have to try it yourself to experience the thrill!</p>

            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        var myButton = document.getElementById("btn-back-to-top");
        window.onscroll = function() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                myButton.style.display = "block";
            } else {
                myButton.style.display = "none";
            }
        };
        myButton.addEventListener("click", function() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        });

        new Splide('.splide', {
            type: 'loop',
            focus: 'center',
            autoplay: true,
            interval: 2500
        }).mount();
    </script>
</body>

</html>
