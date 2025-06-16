<?php
require_once __DIR__ . '/../config/db.php';

session_start(); // Start session for auto login

$email  = trim($_POST['email'] ?? '');
$code   = trim($_POST['code'] ?? '');
$pass1  = $_POST['password'] ?? '';
$pass2  = $_POST['confirm_password'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

<?php
// 1. Validate input
if (empty($email) || empty($code) || empty($pass1) || empty($pass2)) {
    echo "<div class='alert alert-danger'>❌ All fields are required.</div>";
    exit;
}

if ($pass1 !== $pass2) {
    echo "<div class='alert alert-warning'>❌ Passwords do not match.</div>";
    exit;
}

// 2. Check valid code + expiry
$stmt = $conn->prepare("
    SELECT User_id FROM Tbl_user 
    WHERE User_email = ? AND reset_code = ? AND code_expiry > NOW()
");
$stmt->bind_param('ss', $email, $code);
$stmt->execute();
$res = $stmt->get_result();

if ($user = $res->fetch_assoc()) {
    $plain = $pass1;

    $update = $conn->prepare("
        UPDATE Tbl_user
        SET Password = ?, reset_code = NULL, code_expiry = NULL
        WHERE User_id = ?
    ");
    $update->bind_param('si', $plain, $user['User_id']);
    $update->execute();

    // Auto-login and redirect
    $_SESSION['user_id'] = $user['User_id'];
    header('Location: ../views/dashboard.php');
    exit;

} else {
    echo "<div class='alert alert-danger'>❌ Invalid or expired code.</div>";
}
?>

        </div>
    </div>
</div>

</body>
</html>
