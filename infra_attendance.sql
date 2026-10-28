-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2026 at 11:00 PM
-- Server version: 10.11.16-MariaDB
-- PHP Version: 8.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `infra_attendance`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` bigint(20) NOT NULL,
  `student_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('present','absent') NOT NULL,
  `marked_by` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` int(11) NOT NULL,
  `semester_no` tinyint(4) NOT NULL,
  `title` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `semester_no`, `title`) VALUES
(1, 1, '1st Semester'),
(2, 2, '2nd Semester'),
(3, 3, '3rd Semester'),
(4, 4, '4th Semester'),
(5, 5, '5th Semester'),
(6, 6, '6th Semester'),
(7, 7, '7th Semester'),
(8, 8, '8th Semester');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_name` varchar(150) NOT NULL,
  `roll_no` varchar(40) NOT NULL,
  `registration_no` varchar(60) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `session_name` varchar(30) DEFAULT NULL,
  `semester_id` int(11) NOT NULL,
  `status` enum('active','archived') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_name`, `roll_no`, `registration_no`, `phone`, `session_name`, `semester_id`, `status`, `created_at`) VALUES
(1, 'Masum Ahamed Bijoy', '220046', NULL, '01402471230', '22-23', 6, 'active', '2026-10-28 21:15:46'),
(2, 'Mahajabin', '230012', NULL, '01767352964', '23-24', 6, 'active', '2026-10-28 21:19:52'),
(3, 'Md. Aminul Islam', '230031', NULL, '01749597604', '23-24', 6, 'active', '2026-10-28 21:22:21'),
(4, 'Saidul Islam Shohan', '230036', NULL, '01401244839', '23-24', 6, 'active', '2026-10-28 21:23:33'),
(5, 'Ringkon Halder', '230039', NULL, '01879673531', '23-24', 6, 'active', '2026-10-28 21:24:31'),
(6, 'Md. Readul Islam', '231010', NULL, '01742174115', '23-24', 6, 'active', '2026-10-28 21:25:28'),
(7, 'Md. Tanzilur Rahman', '231011', NULL, '01321185911', '23-24', 6, 'active', '2026-10-28 21:26:13'),
(8, 'Mithila Rahman Asha', '231016', NULL, '01793221261', '23-24', 6, 'active', '2026-10-28 21:27:16'),
(9, 'Nusrat Jahan Dina', '231024', NULL, '01710651478', '23-24', 6, 'active', '2026-10-28 21:28:39'),
(10, 'Tamim Akter', '231031', NULL, '01982706412', '23-24', 6, 'active', '2026-10-28 21:29:46'),
(11, 'Md. Jahidul Islam Howlader', '230046', NULL, '01787371877', '23-24', 6, 'active', '2026-10-28 21:32:35'),
(12, 'Nipun Madhu', '230060', NULL, '0195356505', '23-24', 6, 'active', '2026-10-28 21:34:06'),
(13, 'Joy Roy', '230062', NULL, '01770459978', '23-24', 6, 'active', '2026-10-28 21:35:00'),
(14, 'Hafejul Islam', '230065', NULL, '01772289885', '23-24', 6, 'active', '2026-10-28 21:35:45'),
(15, 'Md. Jayed Gazi', '230069', NULL, '01720902212', '23-24', 6, 'active', '2026-10-28 21:36:50'),
(16, 'Md. Abu Talha Jubayer', '230074', NULL, '01728438698', '23-24', 6, 'active', '2026-10-28 21:38:04'),
(17, 'Md. Toufiqur Rahman', '230089', NULL, '01751886228', '23-24', 6, 'active', '2026-10-28 21:40:03'),
(18, 'Digonto Biswas', '230090', NULL, '01743678819', '23-24', 6, 'active', '2026-10-28 21:40:42'),
(19, 'Abdullah Al Lemon Khan', '230093', NULL, '01736659756', '23-24', 6, 'active', '2026-10-28 21:42:18'),
(20, 'Abida Sultana Meghla', '230095', NULL, '01772884909', '23-24', 6, 'active', '2026-10-28 21:43:22'),
(21, 'Tanjil Al Mumin', '231006', NULL, '01734045904', '23-24', 6, 'active', '2026-10-28 21:45:08'),
(22, 'Arif Gazi', '231007', NULL, '01845807038', '23-24', 6, 'active', '2026-10-28 21:45:46'),
(23, 'Jubayer Ahammed Anik', '220011', NULL, '01739520043', '22-23', 6, 'active', '2026-10-28 21:48:13'),
(24, 'Muhammad Asikulla Atik', '220025', NULL, '01760024072', '22-23', 6, 'active', '2026-10-28 21:48:45'),
(25, 'Md. Sulayman Hossen', '220034', NULL, '01706823951', '22-23', 6, 'active', '2026-10-28 21:49:18'),
(26, 'Maruf', '220036', NULL, '01795252374', '22-23', 6, 'active', '2026-10-28 21:49:44'),
(27, 'Sourav Ojha', '220059', NULL, '01633682453', '22-23', 6, 'active', '2026-10-28 21:50:12'),
(28, 'Md. Eajmul Islam', '221021', NULL, '01319469684', '22-23', 6, 'active', '2026-10-28 21:51:08'),
(29, 'K.M. Israkul Islam Noman', '221026', NULL, '01913262700', '22-23', 6, 'active', '2026-10-28 21:51:33');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `subject_code` varchar(30) NOT NULL,
  `subject_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `semester_id`, `subject_code`, `subject_name`) VALUES
(10, 6, '28561', 'Database Management System'),
(11, 6, '25851', 'Principles Of Marketing'),
(12, 6, '28562', 'Computer Networking'),
(14, 6, '28565', 'Survaillance Security System'),
(15, 6, '28563', 'Sensor & IoT System'),
(16, 6, '25852', 'Industrial Management'),
(17, 6, '28564', 'Microcontroller Based Sy; De; & Dev;'),
(18, 6, '28566', 'Web Development Project');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','teacher') NOT NULL DEFAULT 'teacher',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$12$fdhqwfODUMYfNd7LHu7v0erp6t7iFAuspOUWr7hEbZrlQaXspbsYi', 'admin', '2026-10-28 21:02:11'),
(2, 'Masum Ahamed Bijoy', 'masumahamedbijoy@gmail.com', '$2y$12$pCHuQ7KuyLRuuPVWIllyd.WuY/WnrIP.CHCM0270zmXUmUS/Wzg2K', 'teacher', '2026-10-28 21:54:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`student_id`,`subject_id`,`attendance_date`),
  ADD KEY `marked_by` (`marked_by`),
  ADD KEY `idx_attendance_date` (`attendance_date`),
  ADD KEY `idx_attendance_subject_date` (`subject_id`,`attendance_date`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `semester_no` (`semester_no`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roll_no` (`roll_no`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_subject` (`semester_id`,`subject_code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`),
  ADD CONSTRAINT `attendance_ibfk_3` FOREIGN KEY (`marked_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`);

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
