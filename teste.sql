-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 26, 2024 at 03:54 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teste`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
CREATE TABLE IF NOT EXISTS `attendance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` varchar(50) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `entry_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `subject_name` (`subject_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_records`
--

DROP TABLE IF EXISTS `attendance_records`;
CREATE TABLE IF NOT EXISTS `attendance_records` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `entry_time` datetime NOT NULL,
  `subject_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `fk_subject` (`subject_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_sad`
--

DROP TABLE IF EXISTS `attendance_sad`;
CREATE TABLE IF NOT EXISTS `attendance_sad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `entry_time` datetime NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Present',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attendance_sia`
--

DROP TABLE IF EXISTS `attendance_sia`;
CREATE TABLE IF NOT EXISTS `attendance_sia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `entry_time` datetime NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Present',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance_sia`
--

INSERT INTO `attendance_sia` (`id`, `student_id`, `student_name`, `entry_time`, `status`) VALUES
(1, 100023, 'rod', '0000-00-00 00:00:00', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `attendance_ws101`
--

DROP TABLE IF EXISTS `attendance_ws101`;
CREATE TABLE IF NOT EXISTS `attendance_ws101` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `entry_time` datetime NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Present',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `attendance_ws101`
--

INSERT INTO `attendance_ws101` (`id`, `student_id`, `student_name`, `entry_time`, `status`) VALUES
(1, 12345, 'John Doe', '2024-11-26 10:57:57', 'Present'),
(2, 2200657, 'john', '0000-00-00 00:00:00', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `scanned_qr`
--

DROP TABLE IF EXISTS `scanned_qr`;
CREATE TABLE IF NOT EXISTS `scanned_qr` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` varchar(50) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `entry_time` datetime NOT NULL,
  `scan_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance`
--

DROP TABLE IF EXISTS `student_attendance`;
CREATE TABLE IF NOT EXISTS `student_attendance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` varchar(50) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `status` enum('Present') DEFAULT 'Present',
  `scan_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `subject_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subject_name` (`subject_name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subject_name`) VALUES
(1, 'Mathematics'),
(2, 'Science'),
(3, 'History'),
(4, 'English');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `student_name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `student_id`, `student_name`, `created_at`) VALUES
(13, 'rod', '$2y$10$pnJlDQa.Sd1HLGhQjoZnXOG..vWoBIM6Q0Mn9bKfXx89vXBL7840e', 'lirak@gmail.com', '100023', 'rod', '2024-11-26 03:01:33'),
(12, 'john', '$2y$10$sqUDlLanAhXS6hHZCPDk3uWWjLTllt9xQiCl0syn/3KbgpfzHJFsu', '2200657@ub.edu.ph', '2200657', 'john', '2024-11-26 02:47:58');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
