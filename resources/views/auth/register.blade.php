<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ReCash</title>
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
            background-image: url('https://images.unsplash.com/photo-1554118811-1e0d58224f24');
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

        .form-control,
        .form-select {
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
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width: 80px;" class="mb-3">
                    <h2 class="fw-bold mt-2">Create Account</h2>
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email address"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">Select Account Type</option>
                            <option value="manager">Manager (Dashboard View)</option>
                            <option value="admin">Admin (Full Access + CRUD)</option>
                            <option value="cashier">Employee (Cashier POS)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Create password"
                            required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Confirm password" required>
                    </div>

                    <button type="submit" class="btn btn-custom mb-3">Register</button>

                    <div class="text-center">
                        Already have an account? <a href="{{ route('login') }}"
                            class="text-muted fw-bold text-decoration-none">Login here</a>
                    </div>
                </form>

                @if($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-7 image-side d-none d-md-block">
            <div class="image-overlay">
                <h1 class="display-4 fw-bold">Join ReCash Today</h1>
                <p class="lead">Simplifying restaurant management for everyone.</p>
            </div>
        </div>
    </div>
</body>

</html>