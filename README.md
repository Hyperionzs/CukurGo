# CukurGo

Modern barbershop booking app built with PHP, MySQL, and XAMPP.

## Why CukurGo?

- Fast booking flow for customers.
- Simple admin panel to monitor reservations.
- Lightweight structure, easy to run and customize.

## Requirements

- XAMPP (Apache + MySQL)
- PHP 8.0+
- Composer

## Quick Start

1. Clone this repository into your `htdocs` folder.
2. Install dependencies:

   ```bash
   composer install
   ```

3. Create your environment file:

   ```bash
   copy .env.example .env
   ```

4. Update database settings in `.env`:
   - `DB_HOST`
   - `DB_NAME`
   - `DB_USER`
   - `DB_PASS`

5. Generate an admin password hash and set it in `ADMIN_PASSWORD_HASH`:

   ```bash
   php -r "echo password_hash('your_admin_password', PASSWORD_DEFAULT);"
   ```

6. Refresh Composer autoload:

   ```bash
   composer dump-autoload
   ```

## Run on XAMPP

1. Start Apache and MySQL from XAMPP Control Panel.
2. Open the customer booking page:
   - `http://localhost/CukurGo/public/Index.php`
3. Open the admin login page:
   - `http://localhost/CukurGo/public/admin/login.php`

## Project Structure

- `app/` - application logic (controllers, config, helpers)
- `public/` - public entry points and static assets
