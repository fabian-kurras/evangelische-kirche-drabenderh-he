<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

try {
    $db = Database::getInstance();
    
    // Create images table
    $db->exec("CREATE TABLE IF NOT EXISTS images (
      id INT AUTO_INCREMENT PRIMARY KEY,
      title VARCHAR(255) NOT NULL,
      filename VARCHAR(255) NOT NULL UNIQUE,
      description TEXT,
      created_by INT,
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
      INDEX idx_created_at (created_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Create pages table
    $db->exec("CREATE TABLE IF NOT EXISTS pages (
      id INT AUTO_INCREMENT PRIMARY KEY,
      slug VARCHAR(100) UNIQUE NOT NULL,
      title VARCHAR(255) NOT NULL,
      content LONGTEXT,
      edited_by INT,
      edited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      FOREIGN KEY (edited_by) REFERENCES users(id) ON DELETE SET NULL,
      INDEX idx_slug (slug)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    
    // Insert default pages
    $db->exec("INSERT IGNORE INTO pages (slug, title, content) VALUES
    ('kontakt', 'Kontakt', 'Adresse: Evangelische Kirche Drabenderhöhe, Adresse der Kirche, PLZ Ort\n\nTelefon: Telefonnummer hier\n\nEmail: kontakt@kirche-drabenderhoehe.de'),
    ('pfarrblatt', 'Pfarrblatt', 'Willkommen zum Pfarrblatt. Hier können Sie aktuelle Informationen und Ankündigungen finden.'),
    ('themen', 'Themen', 'Verschiedene Themen und Kategorien unserer Gemeinde.'),
    ('impressum', 'Impressum', 'Impressum der Evangelischen Kirche Drabenderhöhe')");
    
    echo "Migration successful!";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage();
}
?>
