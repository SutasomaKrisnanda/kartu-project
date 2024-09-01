<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            overflow-x: hidden;
        }
        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
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
            ::-webkit-scrollbar-track {
                background: transparent;
            }
            ::-webkit-scrollbar-thumb {
                background: #ccc;
            }
            * {
                scrollbar-color: #ccc transparent;
            }
        }
        .btn-primary {
            background-color: #f8f9fa;
            color: #6c757d;
            border: none;
        }
        .btn-primary:hover {
            background-color: #6c757d;
            color: #e2e6ea;
            transform: translateY(-2px);
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar {
            transition: top 0.3s;
        }
        .navbar.sticky-top {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1020; /* Make sure it stays above other content */
        }
        #btn-back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
        }
    </style>
</head>
<body>
    <!-- Single Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
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
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <button type="button" class="btn btn-primary btn-floating btn-lg" id="btn-back-to-top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <div class="container-fluid">
        <div class="row vh-100 vw-100">
            <div class="col-md-12 d-flex flex-column justify-content-center align-items-center">
                <h1 class="text-center">Welcome!</h1>
                <p class="lead text-center">This is the welcome page of our website.</p>
                <div class="column">
                    <a href="#about" class="btn btn-primary mt-3 px-5 py-3 rounded-pill shadow">Learn More</a>
                </div>
                <div class="text-center mt-2">
                    <i class="fa-solid fa-chevrons-down"></i>
                </div>
            </div>
        </div>

        <div class="row vh-100 bg-dark text-white vw-100" id="about">
            <div class="col-md-12 d-flex flex-column justify-content-center align-items-center">
                <h1 class="text-center">About Us</h1>
                <p class="lead text-center">This is the about section of our website.</p>
            </div>
        </div>
    </div>

    <script>
        let mybutton = document.getElementById("btn-back-to-top");
        window.onscroll = function () {
            scrollFunction();
        };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        mybutton.addEventListener("click", backToTop);

        function backToTop() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
