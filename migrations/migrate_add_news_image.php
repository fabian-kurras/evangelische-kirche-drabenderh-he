<?php
// Run this script once to add an `image` column to `news` if it doesn't exist.
require_once __DIR__ . '/../db.php';
try {
    $res = $pdo->query("SHOW COLUMNS FROM news LIKE 'image'")->fetch();
    if ($res) {
        echo "Column 'image' already exists.\n";
        exit;
    }
    $pdo->exec("ALTER TABLE news ADD COLUMN image VARCHAR(255) DEFAULT NULL");
    echo "Column 'image' added to 'news'.\n";
} catch (Exception $e) {
    echo 'Migration failed: ' . $e->getMessage() . "\n";
}
?>