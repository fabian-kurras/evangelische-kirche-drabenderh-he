# Installation & Setup

1. Create the database and tables
   - Open **phpMyAdmin** (http://localhost/phpmyadmin) or use the MySQL CLI
   - Run the SQL in `init_db.sql` (this creates database `ev_kirche` and tables)

2. Configure database connection
   - Edit `config.php` if your MySQL username/password/host differ from defaults

3. Create an admin user
   - From the command line (recommended):
     `php create_admin.php admin strongpassword`
   - Or open `create_admin.php` in your browser and fill the form (less secure)

5. Run migration for images (optional, required if you want to upload news images)
   - Run: `php migrations/migrate_add_news_image.php`
   - Create uploads dir: `mkdir uploads\news` and ensure the webserver can write to it.

6. Open the site
   - Public site: http://localhost/evangelische-kirche-drabenderhöhe/
   - Admin panel: http://localhost/evangelische-kirche-drabenderhöhe/admin/

7. Notes & security
   - The admin panel uses PHP sessions and CSRF tokens for forms.
   - Image uploads: jpg/png/webp up to 2MB; stored in `uploads/news/`.
   - Use HTTPS in production and create a strong admin password.
   - Consider adding role management and stricter validation for production.

6. Next improvements you may want
   - Add edit pages for events/news
   - Add image uploads and previews
   - Add scheduled publishing / recurring events

If you want, I can run through the steps interactively and test the login/create workflow locally if you provide the MySQL credentials or allow me to run commands in your environment.
