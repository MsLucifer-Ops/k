<?php
// C:\xampp\htdocs\Barcode\auth\login.php

// Load DB config (defines BASE_URL and gives you $conn)
require_once __DIR__ . '/../config/db.php';

// Start session and redirect if already logged in
session_start();
if (!empty($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/views/dashboard.php');
    exit;
}

$error = '';
$u     = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pull from the form
    $u = trim($_POST['User_email'] ?? '');
    $p = $_POST['password']  ?? '';

    // Query user by email
    $stmt = $conn->prepare("
      SELECT User_id, User_email, Password, Role
        FROM Tbl_user
       WHERE User_email = ?
       LIMIT 1
    ");
    $stmt->bind_param('s', $u);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $row = $res->fetch_assoc()) {
        // For plain‐text (not recommended): direct compare
        // Or if hashed: password_verify($p, $row['Password'])
        if ($row['Password'] === $p) {
            session_regenerate_id(true);
            $_SESSION['user_id']    = $row['User_id'];
            $_SESSION['User_email'] = $row['User_email'];
            $_SESSION['role']       = $row['Role'];
            header('Location: ' . BASE_URL . '/views/dashboard.php');
            exit;
        }
    }
    $error = 'Invalid email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>POSBARCODE — Login</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background: #f4f6f9; }
    .login-card {
      max-width: 400px;
      margin: 5% auto;
      border: 1px solid #ddd;
      border-radius: 6px;
      background: #fff;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .login-header {
      border-bottom: 1px solid #eee;
      padding: 1rem;
      text-align: center;
    }
    .login-header h1 {
      margin: 0;
      font-size: 1.75rem;
      font-weight: 700;
    }
    .login-body { padding: 1.5rem; }
    .form-control:focus {
      box-shadow: none;
      border-color: #0d6efd;
    }
    .input-group-text {
      background: #e9ecef;
      border: 1px solid #ced4da;
    }
    .btn-login {
      background: #0d6efd;
      border-color: #0d6efd;
    }
    .btn-login:hover { background: #0b5ed7; }
    .forgot-link { font-size: 0.9rem; }
  </style>
</head>
<body>

  <div class="login-card">
    <div class="login-header">
      <h1>POS<span style="font-weight:400;">BARCODE</span></h1>
    </div>
    <div class="login-body">
      <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <p class="text-center mb-4">Sign in to start your session</p>

      <form method="post" action="<?= BASE_URL ?>/auth/login.php">
        <div class="mb-3">
          <div class="input-group">
            <input
              type="email"
              name="User_email"
              class="form-control"
              placeholder="Email"
              required
              autofocus
              value="<?= htmlspecialchars($u) ?>"
            >
            <span class="input-group-text">
              <i class="bi bi-envelope"></i>
            </span>
          </div>
        </div>

        <div class="mb-3">
          <div class="input-group">
            <input
              type="password"
              name="password"
              class="form-control"
              placeholder="Password"
              required
            >
            <span class="input-group-text">
              <i class="bi bi-lock"></i>
            </span>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
          <a href="#" class="forgot-link">I forgot my password</a>
        </div>

        <button type="submit" class="btn btn-login w-100 text-white">
          Login
        </button>
      </form>
    </div>
  </div>

  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
