<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
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

        .img-hover:hover {
            transform: scale(1.1);
            /* Make the image slightly larger on hover */
            transition: transform 0.2s ease;
            /* Add a smooth transition */
        }

        #profilePicture {
            width: 50px;
            height: 50px;
            transition: width 0.3s, height 0.3s;
        }

        #imgToggle:checked~#profilePicture {
            width: 100px;
            height: 100px;
        }
    </style>
</head>

<body>

    <x-home.navbar>
        <x-slot:nickname>{{ $nickname }}</x-slot>
    </x-home.navbar>

    <x-home.sidebar></x-home.sidebar>

    <div class="container">
        <div class="row">
            <div class="ool-md-8">
                <article>
                    {{ $slot }}
                </article>
            </div>
        </div>
    </div>
</body>

</html>
