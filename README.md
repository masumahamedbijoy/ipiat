# Infra Polytechnic Institute — Computer Department Attendance Tracker

Responsive PHP + MySQL project for XAMPP.

## Install
1. Extract the folder to `C:\xampp\htdocs\infra_attendance_tracker`.
2. Start Apache and MySQL in XAMPP.
3. Open `http://localhost/phpmyadmin` and import `database.sql`.
4. Verify `config.php` database credentials (default XAMPP user is `root`, blank password).
5. Open `http://localhost/infra_attendance_tracker/`.
6. Register the first account; it becomes Admin. Later registrations become Teachers.

## Features
- Responsive dashboard, login/registration, hashed passwords, role-aware navigation
- 8 seeded semesters and subject catalogue
- Student management, search, semester filter, individual student profile
- Attendance by semester, subject and date; Present/Absent and bulk mark
- Friday and Saturday blocked for attendance
- Duplicate attendance prevented by database unique key; resaving updates records
- Daily overview and student attendance summaries
- CSV export for attendance records and individual student history
- Admin tools for subject management and staff roles
- CSRF token helpers included; core forms can be further hardened before deployment

## Important
- Sample subjects are placeholders. Replace them with the institute's official curriculum in Administration.
- Registration is open in this starter; subsequent accounts receive Teacher role. For public deployment, disable public registration or require Admin approval.
- Before public hosting, configure HTTPS, rate limiting, secure backups, audit logs, and have the application security-reviewed.
