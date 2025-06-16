<!-- auth/forgot_password.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h4 class="mb-4">🔐 Forgot Password</h4>
        <form action="send_reset_code.php" method="post">
            <div class="mb-3">
                <label class="form-label">Enter your email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">Send Reset Code</button>
        </form>
    </div>
</div>
</body>
</html>
