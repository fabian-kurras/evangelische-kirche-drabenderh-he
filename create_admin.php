<?php
// Simple CLI or browser helper to create an initial admin user
require_once __DIR__ . '/db.php';

function create_admin($username, $password) {
    global $pdo;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
    $stmt->execute([$username, $hash]);
    return $pdo->lastInsertId();
}

if (php_sapi_name() === 'cli') {
    if ($argc < 3) {
        echo "Usage: php create_admin.php username password\n";
        exit(1);
    }
    $username = $argv[1];
    $password = $argv[2];
    $id = create_admin($username, $password);
    echo "Created admin user $username with id $id\n";
    exit(0);
}

// Browser usage (not recommended without protection)
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    try {
        $id = create_admin($username, $password);
        echo "Admin created with id: " . htmlspecialchars($id);
    } catch (Exception $e) {
        echo "Error: " . htmlspecialchars($e->getMessage());
    }
    exit;
}
?>

<!doctype html>
<html>
<head><meta charset="utf-8"><title>Create Admin</title></head>
<body>
<h2>Create Admin (CLI recommended)</h2>
<form method="post">
  <label>Username: <input name="username"></label><br>
  <label>Password: <input name="password" type="password"></label><br>
  <button type="submit">Create</button>
</form>
</body>
</html>