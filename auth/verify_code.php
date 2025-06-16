<!-- auth/verify_code.php -->
<?php
// Always set timezone before any output
date_default_timezone_set('Asia/Phnom_Penh'); // or 'Asia/Phnom_Penh' for Cambodia
?>
<!DOCTYPE html>
<html>
    <head>
    
    <title>Reset Password with Code</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm p-4">
        <h4 class="mb-4">🔁 Reset Password</h4>
        <form action="reset_with_code.php" method="post">
            <div class="mb-3">
                <label class="form-label">Your Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label class="form-label">6-Digit Code</label>
                <input type="text" class="form-control" name="code" required>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" required>
            </div>
            <button class="btn btn-success w-100" type="submit">Update Password</button>
        </form>
    </div>
</div>
</body>
</html>
