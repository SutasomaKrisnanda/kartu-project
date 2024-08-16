
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        <h1 class="text-center mb-4">Register Page</h1>
        <form action="/register" method="post">
            @csrf
            <div class="mb-3 row">
                <div class="col-md-6">
                    <label for="username"
            class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Input Username" required>
                    @error('username')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="nickname" class="form-label">Nickname</label>
                    <input type="text" class="form-control" name="nickname" id="nickname" placeholder="Input Nickname" required>
                    @error('nickname')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Input Username/Email" required>
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="Input password" required>
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="confirm-password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="password_confirmation" id="confirm-password" placeholder="Confirm password again" required>
            </div>
            <div class="gap-2 d-md-flex justify-content-between">
                <a class="btn btn-secondary me-md-2" href="/login">Login Instead</a>
                <button type="submit" class="btn btn-primary">Create Account</button>
            </div>

        </form>
    </div>
</div>
@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
        });
    </script>
@endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
