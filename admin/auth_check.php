<?php
// include from admin pages to require login
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /evangelische-kirche-drabenderhöhe/admin/index.php');
    exit;
}
?>