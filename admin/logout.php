<?php
session_start();
require_once '../includes/db.php';
if (isset($_SESSION['admin_id'])) {
    logActivity($pdo, 'Admin Logout', $_SESSION['admin_user'] ?? 'admin', 'Logged out');
}
session_destroy();
header('Location: login.php');
exit;