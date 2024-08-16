
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Document</title>
    
<style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        body {
            width: 100%;
            background-color: black;
        }


    </style>
</head>
<body>

<div class="container bg-light p-4 rounded shadow col-lg-5">
    <div class="content"> 
        <h1 class="text-center mb-4">Login Page</h1>
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="/login" method="post">
            @csrf
            <div class="mb-3">
                <label for="user" class="form-label">User</label>
                <input type="text" class="form-control" name="username" id="user" placeholder="Input Username/Email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Input password" required>
            </div>
            <div class="gap-2 d-md-flex justify-content-between">
                <a class="btn btn-secondary me-md-2" href="/register">Create New Account Instead</a>
                <button type="submit" class="btn btn-primary">Login</button>
            </div>

        </form>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
