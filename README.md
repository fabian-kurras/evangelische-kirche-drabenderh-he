Evangelische Kirche Drabenderhöhe — Website (PHP + MySQL)

Quickstart

1. Ensure XAMPP is running (Apache + MySQL).
2. Import the database schema: open `init_db.sql` in phpMyAdmin or run:
   mysql -u root -p < init_db.sql
3. Edit `config.php` if necessary (DB credentials).
4. Create an admin user:
   - CLI: `php create_admin.php admin yourpassword`
   - Or open `create_admin.php` in the browser (not recommended on open servers)
5. Open the public site: http://localhost/evangelische-kirche-drabenderhöhe/
6. Open admin panel: http://localhost/evangelische-kirche-drabenderhöhe/admin/

Notes
- Passwords are hashed with PHP's password_hash.
- The admin panel uses PHP sessions and a CSRF token for form submissions.
- The public site fetches news and events from `api/get_items.php` and polls every 15 s. News may include an `image` URL if available; images are served from `/uploads/news/`.

Security & production tips
- Use HTTPS and secure cookies for production.
- Restrict `create_admin.php` or remove it after creating your first admin.
- Add role management if you need multiple admin roles.
- Sanitize and validate user input if you allow richer content (e.g., HTML uploads).

If you'd like, I can:
- Add editing capabilities for events/news
- Add image uploads and media handling
- Add pagination and filtering on the public list
