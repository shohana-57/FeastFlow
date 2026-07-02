<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FeastFlow - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card shadow" style="width: 400px;">
            <div class="card-body p-4">
                
                <h3 class="text-center mb-4 text-danger">🍽️ FeastFlow Login</h3>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="/login">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-danger w-100">Login</button>
                </form>

                <p class="text-center mt-3 text-muted">
                    Try: admin@feastflow.com / 1234
                </p>

            </div>
        </div>
    </div>

</body>
</html>
