<?php
// C:\xampp\htdocs\Barcode\auth\validate.php
// require_once PROJECT_ROOT . '/config/db.php';

require_once __DIR__ . '/../config/db.php';

session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/auth/login.php');
    exit;
}
