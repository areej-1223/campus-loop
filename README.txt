CAMPUSLOOP — STUDENT EVENT HUB
================================
Technology: HTML, CSS, JavaScript, PHP, MySQL
Local environment: XAMPP / Apache + PHP and a MySQL-compatible database

INSTALLATION
1. Extract the CampusLoop folder into your web server's document root.
   Typical XAMPP path: C:\xampp\htdocs\CampusLoop
   If your XAMPP is installed in a custom folder, use that folder's htdocs.
2. Start Apache and your MySQL service. If you use a separate MySQL80 Windows service,
   you may leave XAMPP's MySQL module stopped to avoid port conflicts.
3. Open MySQL Workbench and connect to your MySQL server.
4. Open database.sql in Workbench and execute the entire script.
5. Open config.php and set dbHost, dbPort, dbUser, and dbPass to match your connection.
   Common MySQL port is 3306. Use the port shown in your Workbench connection.
6. Open this URL in your browser:
   http://localhost/CampusLoop/
   Do not use VS Code Live Server for PHP pages.
7. Create a normal account through the Register page.
8. To make your account an administrator, run this SQL in Workbench, replacing the email:
   USE campusloop;
   UPDATE users SET role='admin' WHERE email='your-email@example.com';
9. Log out and log back in. The Admin link will appear in the navigation.

FEATURES
- Home page and upcoming events
- Browse/search events and view details
- Student registration, login, logout
- Register for events, duplicate registration prevention, capacity checks
- My Events page and cancellation
- Admin event creation, editing, deletion, and participant list
- Prepared SQL statements, hashed passwords, session checks, CSRF tokens

DATABASE TABLES
users: 6 columns
  user_id (PK), name, email, password, role, created_at
events: 8 columns
  event_id (PK), title, description, event_date, venue, capacity, created_by, created_at
registrations: 4 columns
  registration_id (PK), user_id (FK), event_id (FK), registered_at

TROUBLESHOOTING
- Database connection failed: verify port, username, password, server status, and database.sql.
- Access denied: use the same credentials as your MySQL Workbench connection.
- Page not found: check that the folder is inside htdocs and open the localhost URL.
- PHP source appears as text: start Apache and use http://localhost/... rather than Live Server.
- Cannot register as admin: promote your account using the SQL in step 8, then log out/in.

This is an academic starter project. Review and test it in your own environment before submission.
