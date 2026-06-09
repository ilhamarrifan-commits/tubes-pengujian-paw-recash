<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ReCash</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #F5F5EC;
            height: 100vh;
            overflow: hidden;
        }

        .login-sidebar {
            background-color: #F5F5EC;
            height: 100vh;
            overflow-y: auto;
            padding: 4rem;
        }

        .login-content {
            min-height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .image-side {
            background-color: #333;
            /* Fallback */
            background-image: url('https://images.unsplash.com/photo-1554118811-1e0d58224f24');
            /* Restaurant vibe */
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
            padding: 3rem;
            color: white;
        }

        .btn-custom {
            background-color: #4A4A35;
            color: white;
            border: none;
            padding: 0.8rem;
            width: 100%;
            border-radius: 8px;
        }

        .btn-custom:hover {
            background-color: #3A3A25;
            color: white;
        }

        .form-control {
            background-color: #EBEBE0;
            border: none;
            padding: 0.8rem;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="row h-100 g-0">
        <div class="col-md-5 login-sidebar">
            <div class="login-content">
                <div class="text-center mb-5">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width: 48px;" class="mb-3">
                    <h6 class="mt-3">Welcome back</h6>
                    <h2 class="fw-bold">Login your account</h2>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="enter your email" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="enter your password"
                            required>
                    </div>

                    <button type="submit" class="btn btn-custom mb-4">Login</button>

                    <div class="text-center">
                        <a href="#" class="text-muted text-decoration-none d-block mb-2">Forgot Password</a>
                        <div class="small">Don't have an account? <a href="{{ route('register') }}"
                                class="fw-bold text-decoration-none text-dark">Create one</a></div>
                    </div>
                </form>

                @if($errors->any())
                    <div class="alert alert-danger mt-3">
                        {{ $errors->first() }}
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-7 image-side d-none d-md-block">
            <div class="image-overlay">
                <h1 class="display-4 fw-bold">Manage your Restaurant with ReCash</h1>
                <p class="lead">More easier & efficient!</p>
            </div>
        </div>
    </div>
</body>

</html>