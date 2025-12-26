Uploads directory for user content (news images)

- Path: /evangelische-kirche-drabenderhöhe/uploads/news/
- Ensure the webserver can write to this directory (chown/chmod on Linux; on XAMPP on Windows the web server user usually has access).
- For security: the parent /uploads/ directory contains an .htaccess that denies PHP execution.
- Run migrations/migrate_add_news_image.php to add the `image` column to `news` table (php migrate_add_news_image.php)

Created files:
- uploads/.htaccess
- Please create 'uploads/news' directory and make it writable by the webserver.
