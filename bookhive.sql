-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 13, 2026 at 12:46 AM
-- Server version: 8.4.7
-- PHP Version: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookhive`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isbn` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year` int DEFAULT NULL,
  `total_copies` int NOT NULL,
  `available_copies` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `author`, `isbn`, `category`, `year`, `total_copies`, `available_copies`) VALUES
(1, 'Harry Potter', 'J.K Rowling', '111', 'Fantasy', 1997, 5, 4),
(2, 'Clean Code', 'Robert Martin', '222', 'Programming', 2008, 4, 4),
(3, 'The Hobbit', 'Tolkien', '333', 'Fantasy', 1937, 2, 2),
(4, 'Atomic Habits', 'James Clear', '444', 'Self Help', 2018, 4, 4),
(5, 'Database System', 'Elmasri', '555', 'Technology', 2015, 3, 3),
(6, 'Java Basics', 'John Doe', '666', 'Programming', 2020, 5, 5),
(7, 'History World', 'Smith', '777', 'History', 2010, 2, 2),
(8, 'AI Future', 'Lee', '888', 'Technology', 2022, 3, 3),
(9, 'Math Guide', 'Brown', '999', 'Education', 2019, 4, 4),
(10, 'Web Design', 'Clark', '1010', 'Design', 2021, 2, 2),
(11, 'Test Book', 'John Doe', NULL, 'Programming', NULL, 5, 5),
(12, 'King Kong', 'Clifford', NULL, 'Wild', NULL, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

DROP TABLE IF EXISTS `loans`;
CREATE TABLE IF NOT EXISTS `loans` (
  `id` int NOT NULL AUTO_INCREMENT,
  `book_id` int DEFAULT NULL,
  `member_id` int DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('active','returned','overdue') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fine` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `book_id` (`book_id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`id`, `book_id`, `member_id`, `borrow_date`, `due_date`, `return_date`, `status`, `fine`) VALUES
(1, 3, 1, '2026-06-12', '2026-06-19', NULL, 'returned', 0.00),
(2, 3, 1, '2026-06-12', '2026-06-19', NULL, 'returned', 0.00),
(3, 3, 1, '2026-06-12', '2026-06-19', NULL, 'returned', 0.00),
(4, 3, 1, '2026-06-12', '2026-06-19', NULL, 'returned', 0.00),
(5, 3, 1, '2026-06-12', '2026-06-19', NULL, 'returned', 0.00),
(6, 3, 1, '2026-06-12', '2026-06-19', NULL, 'returned', 0.00),
(7, 3, 1, '2026-06-12', '2026-06-19', NULL, 'returned', 0.00),
(8, 3, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(9, 2, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(10, 2, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(11, 2, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(12, 2, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(13, 2, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(14, 2, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(15, 5, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(16, 1, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(17, 1, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(18, 5, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(19, 6, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(20, 2, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(21, 1, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(22, 1, 1, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(23, 2, 1, '2026-06-12', '2026-06-19', NULL, 'active', 0.00),
(24, 1, 1, '2026-06-12', '2026-06-19', NULL, 'active', 0.00),
(25, 2, 4, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(26, 2, 4, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(27, 2, 4, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(28, 2, 4, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(29, 2, 4, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(30, 2, 4, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(31, 2, 4, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(32, 1, 4, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(33, 2, 4, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(34, 2, 4, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(35, 2, 4, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(36, 2, 4, '2026-06-12', '2026-06-19', '2026-06-12', 'returned', 0.00),
(37, 2, 4, '2026-06-12', '2026-06-19', NULL, 'active', 0.00),
(38, 2, 4, '2026-06-12', '2026-06-19', NULL, 'active', 0.00),
(39, 2, 2, '2026-06-12', '2026-06-19', NULL, 'active', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `loan_history`
--

DROP TABLE IF EXISTS `loan_history`;
CREATE TABLE IF NOT EXISTS `loan_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `book_id` int NOT NULL,
  `book_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` enum('BORROW','RETURN') COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loan_history`
--

INSERT INTO `loan_history` (`id`, `user_id`, `book_id`, `book_title`, `action`, `transaction_date`) VALUES
(1, 1, 3, 'The Hobbit', 'RETURN', '2026-06-12 17:24:08'),
(2, 1, 3, 'The Hobbit', 'RETURN', '2026-06-12 17:51:07'),
(3, 1, 2, 'Clean Code', 'BORROW', '2026-06-12 19:32:11'),
(4, 1, 2, 'Clean Code', 'BORROW', '2026-06-12 19:33:53'),
(5, 1, 2, 'Clean Code', 'BORROW', '2026-06-12 19:37:07'),
(6, 1, 5, 'Database System', 'BORROW', '2026-06-12 19:44:29'),
(7, 1, 1, 'Harry Potter', 'BORROW', '2026-06-12 19:49:47'),
(8, 1, 1, 'Harry Potter', 'BORROW', '2026-06-12 20:00:44'),
(9, 1, 1, 'Harry Potter', 'RETURN', '2026-06-12 20:00:48'),
(10, 1, 5, 'Database System', 'BORROW', '2026-06-12 20:01:16'),
(11, 1, 5, 'Database System', 'RETURN', '2026-06-12 20:01:22'),
(12, 1, 6, 'Java Basics', 'BORROW', '2026-06-12 20:02:40'),
(13, 1, 6, 'Java Basics', 'RETURN', '2026-06-12 20:02:45'),
(14, 1, 2, 'Clean Code', 'BORROW', '2026-06-12 20:15:13'),
(15, 1, 2, 'Clean Code', 'RETURN', '2026-06-12 20:39:38'),
(16, 1, 1, 'Harry Potter', 'RETURN', '2026-06-12 20:45:16'),
(17, 1, 1, 'Harry Potter', 'RETURN', '2026-06-12 20:54:36'),
(18, 1, 2, 'Clean Code', 'RETURN', '2026-06-12 20:55:10'),
(19, 4, 2, 'Clean Code', 'RETURN', '2026-06-12 22:50:56'),
(20, 4, 2, 'Clean Code', 'RETURN', '2026-06-12 22:51:05');

-- --------------------------------------------------------

--
-- Table structure for table `loan_transactions`
--

DROP TABLE IF EXISTS `loan_transactions`;
CREATE TABLE IF NOT EXISTS `loan_transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_id` int DEFAULT NULL,
  `book_id` int DEFAULT NULL,
  `book_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` enum('BORROW','RETURN') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loan_transactions`
--

INSERT INTO `loan_transactions` (`id`, `member_id`, `book_id`, `book_title`, `action`, `action_date`) VALUES
(1, 1, 1, 'Harry Potter', 'BORROW', '2026-06-13 04:54:32'),
(2, 1, 2, 'Clean Code', 'BORROW', '2026-06-13 04:54:56'),
(3, 1, 1, 'Harry Potter', 'BORROW', '2026-06-13 05:01:49'),
(4, 4, 2, 'Clean Code', 'BORROW', '2026-06-13 06:46:17'),
(5, 4, 2, 'Clean Code', 'BORROW', '2026-06-13 06:50:17'),
(6, 4, 2, 'Clean Code', 'BORROW', '2026-06-13 06:50:33'),
(7, 4, 2, 'Clean Code', 'BORROW', '2026-06-13 06:50:39'),
(8, 4, 2, 'Clean Code', 'BORROW', '2026-06-13 06:52:42'),
(9, 4, 2, 'Clean Code', 'RETURN', '2026-06-13 06:52:47'),
(10, 4, 2, 'Clean Code', 'BORROW', '2026-06-13 06:53:04'),
(11, 4, 2, 'Clean Code', 'BORROW', '2026-06-13 06:58:13'),
(12, 4, 2, 'Clean Code', 'RETURN', '2026-06-13 06:58:20'),
(13, 4, 1, 'Harry Potter', 'BORROW', '2026-06-13 06:58:34'),
(14, 4, 1, 'Harry Potter', 'RETURN', '2026-06-13 06:58:37'),
(15, 4, 2, 'Clean Code', 'RETURN', '2026-06-13 07:07:08'),
(16, 4, 2, 'Clean Code', 'BORROW', '2026-06-13 07:10:49'),
(17, 4, 2, 'Clean Code', 'RETURN', '2026-06-13 07:11:51'),
(18, 4, 2, 'Clean Code', 'RETURN', '2026-06-13 07:11:52'),
(19, 4, 2, 'Clean Code', 'RETURN', '2026-06-13 07:12:02'),
(20, 4, 2, 'Clean Code', 'BORROW', '2026-06-13 07:15:53'),
(21, 4, 2, 'Clean Code', 'BORROW', '2026-06-13 07:16:00'),
(22, 4, 2, 'Clean Code', 'BORROW', '2026-06-13 07:16:18'),
(23, 4, 2, 'Clean Code', 'BORROW', '2026-06-13 07:16:27'),
(24, 4, 2, 'Clean Code', 'RETURN', '2026-06-13 07:16:37'),
(25, 4, 2, 'Clean Code', 'RETURN', '2026-06-13 07:16:49'),
(26, 4, 2, 'Clean Code', 'RETURN', '2026-06-13 07:19:53'),
(27, 4, 2, 'Clean Code', 'BORROW', '2026-06-13 07:21:30'),
(28, 2, 2, 'Clean Code', 'BORROW', '2026-06-13 07:53:21');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
CREATE TABLE IF NOT EXISTS `members` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('member','librarian') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `name`, `email`, `password`, `role`) VALUES
(2, 'Clifford Avanzado', 'clifford@bookhive.com', '$2y$10$yBCaEp02BOTDlTG5PL9ss.JnCLFp.PaD9L8T/Km6ETSoQ2E76Az1a', 'librarian'),
(3, 'ford', 'ford@gmail.com', '$2y$10$6WD/e3ojh0xmHJ3OOu93SeXIp81XBFj3IU9MuV2mAS2ZX8UORvtne', 'member'),
(4, 'ella', 'ella@gmail.com', '$2y$10$u2q8iKn8yrXExdrm4E5q/Oyr7vfDLEVD0MzE8V8.9zsLk28kl.kuK', 'member');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
