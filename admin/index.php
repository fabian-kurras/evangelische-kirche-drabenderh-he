<?php
// Admin login page
session_start();
// Load site config so SITE_NAME is available and errors are consistent
require_once __DIR__ . '/../config.php';
if (isset($_SESSION['user_id'])) {
    header('Location: /evangelische-kirche-drabenderhöhe/admin/panel.php');
    exit;
}
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../db.php';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id, password_hash FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
        header('Location: /evangelische-kirche-drabenderhöhe/admin/panel.php');
        exit;
    }
    $err = 'Login failed';
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Login - <?=htmlspecialchars(SITE_NAME)?></title>
  <link rel="stylesheet" href="/evangelische-kirche-drabenderhöhe/assets/css/style.css">
</head>
<body>
  <main class="container">
    <h1>Admin Login</h1>
    <?php if ($err): ?>
      <p class="error"><?=htmlspecialchars($err)?></p>
    <?php endif; ?>
    <form method="post">
      <label>Benutzername<br><input name="username" required></label><br>
      <label>Passwort<br><input name="password" type="password" required></label><br>
      <button type="submit">Login</button>
    </form>
    <p><a href="/evangelische-kirche-drabenderhöhe/index.php">Zur Webseite</a></p>
  </main>
</body>
</html>