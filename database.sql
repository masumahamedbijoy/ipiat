CREATE DATABASE IF NOT EXISTS infra_attendance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE infra_attendance;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','teacher') NOT NULL DEFAULT 'teacher',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE semesters (
  id INT AUTO_INCREMENT PRIMARY KEY,
  semester_no TINYINT NOT NULL UNIQUE,
  title VARCHAR(50) NOT NULL
);

CREATE TABLE subjects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  semester_id INT NOT NULL,
  subject_code VARCHAR(30) NOT NULL,
  subject_name VARCHAR(150) NOT NULL,
  FOREIGN KEY (semester_id) REFERENCES semesters(id) ON DELETE CASCADE,
  UNIQUE KEY unique_subject (semester_id, subject_code)
);

CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_name VARCHAR(150) NOT NULL,
  roll_no VARCHAR(40) NOT NULL UNIQUE,
  registration_no VARCHAR(60) DEFAULT NULL,
  phone VARCHAR(30) DEFAULT NULL,
  session_name VARCHAR(30) DEFAULT NULL,
  semester_id INT NOT NULL,
  status ENUM('active','archived') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (semester_id) REFERENCES semesters(id)
);

CREATE TABLE attendance (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  subject_id INT NOT NULL,
  attendance_date DATE NOT NULL,
  status ENUM('present','absent') NOT NULL,
  marked_by INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id),
  FOREIGN KEY (subject_id) REFERENCES subjects(id),
  FOREIGN KEY (marked_by) REFERENCES users(id),
  UNIQUE KEY unique_attendance (student_id, subject_id, attendance_date),
  INDEX idx_attendance_date (attendance_date),
  INDEX idx_attendance_subject_date (subject_id, attendance_date)
);

INSERT IGNORE INTO semesters (semester_no, title) VALUES
(1,'1st Semester'),(2,'2nd Semester'),(3,'3rd Semester'),(4,'4th Semester'),
(5,'5th Semester'),(6,'6th Semester'),(7,'7th Semester'),(8,'8th Semester');

-- Sample editable subjects. Replace these with the institute's official subject list.
INSERT IGNORE INTO subjects (semester_id, subject_code, subject_name)
SELECT id, 'CST-101', 'Computer Fundamentals' FROM semesters WHERE semester_no=1;
INSERT IGNORE INTO subjects (semester_id, subject_code, subject_name)
SELECT id, 'CST-102', 'Programming Fundamentals' FROM semesters WHERE semester_no=1;
INSERT IGNORE INTO subjects (semester_id, subject_code, subject_name)
SELECT id, 'CST-201', 'Object Oriented Programming' FROM semesters WHERE semester_no=2;
INSERT IGNORE INTO subjects (semester_id, subject_code, subject_name)
SELECT id, 'CST-301', 'Database Management Systems' FROM semesters WHERE semester_no=3;
INSERT IGNORE INTO subjects (semester_id, subject_code, subject_name)
SELECT id, 'CST-401', 'Data Communication' FROM semesters WHERE semester_no=4;
INSERT IGNORE INTO subjects (semester_id, subject_code, subject_name)
SELECT id, 'CST-501', 'Web Development' FROM semesters WHERE semester_no=5;
INSERT IGNORE INTO subjects (semester_id, subject_code, subject_name)
SELECT id, 'CST-601', 'Networking' FROM semesters WHERE semester_no=6;
INSERT IGNORE INTO subjects (semester_id, subject_code, subject_name)
SELECT id, 'CST-701', 'Project Work' FROM semesters WHERE semester_no=7;
INSERT IGNORE INTO subjects (semester_id, subject_code, subject_name)
SELECT id, 'CST-801', 'Industrial Attachment' FROM semesters WHERE semester_no=8;
