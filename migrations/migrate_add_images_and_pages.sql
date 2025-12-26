-- Create images table for gallery
CREATE TABLE IF NOT EXISTS images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  filename VARCHAR(255) NOT NULL UNIQUE,
  description TEXT,
  created_by INT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create pages table for editable page content
CREATE TABLE IF NOT EXISTS pages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(100) UNIQUE NOT NULL,
  title VARCHAR(255) NOT NULL,
  content LONGTEXT,
  edited_by INT,
  edited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (edited_by) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default pages
INSERT IGNORE INTO pages (slug, title, content) VALUES
('kontakt', 'Kontakt', 'Adresse: Evangelische Kirche Drabenderhöhe, Adresse der Kirche, PLZ Ort\n\nTelefon: Telefonnummer hier\n\nEmail: kontakt@kirche-drabenderhoehe.de'),
('pfarrblatt', 'Pfarrblatt', 'Willkommen zum Pfarrblatt. Hier können Sie aktuelle Informationen und Ankündigungen finden.'),
('themen', 'Themen', 'Verschiedene Themen und Kategorien unserer Gemeinde.'),
('impressum', 'Impressum', 'Impressum der Evangelischen Kirche Drabenderhöhe');
