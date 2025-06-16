
<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';
date_default_timezone_set('Asia/Phnom_Penh');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = trim($_POST['email'] ?? '');

if (empty($email)) {
    die("Email is required.");
}

// Check if user exists
$stmt = $conn->prepare("SELECT User_id FROM Tbl_user WHERE User_email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$res = $stmt->get_result();

if ($user = $res->fetch_assoc()) {
    // 6-digit code
    $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $expiry = date("Y-m-d H:i:s", strtotime('+10 minutes'));

    // ✅ Fix: use correct column and variable
    $update = $conn->prepare("UPDATE Tbl_user SET reset_code = ?, code_expiry = ? WHERE User_id = ?");
    $update->bind_param('ssi', $code, $expiry, $user['User_id']);
    $update->execute();

    // Send email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'heang015873174@gmail.com';
        $mail->Password = 'qtbbunolnqrlehwk';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('heang015873174@gmail.com', 'PHP01');
        $mail->addAddress($email);
        $mail->Subject = 'Your Password Reset Code';
        $mail->Body = "Your 6-digit password reset code is: $code\n\nThis code will expire in 10 minutes.";

        $mail->send();

        header('Location: code_sent.php');
        exit;

    } catch (Exception $e) {
        echo "❌ Failed to send email: {$mail->ErrorInfo}";
    }

} else {
    echo "❌ Email not found.";
}
