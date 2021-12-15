-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 15, 2021 at 11:51 AM
-- Server version: 5.7.36
-- PHP Version: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oneupmeta`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_attempts`
--

DROP TABLE IF EXISTS `failed_attempts`;
CREATE TABLE IF NOT EXISTS `failed_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `login_attempt` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `failed_attempts`
--

INSERT INTO `failed_attempts` (`id`, `user_id`, `login_attempt`) VALUES
(4, 7, 1);

-- --------------------------------------------------------

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `log_date_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log`
--

INSERT INTO `log` (`id`, `user_id`, `log_date_time`, `status`) VALUES
(3, 7, '2021-12-15 10:51:18', 'Out');

-- --------------------------------------------------------

--
-- Table structure for table `suscribers`
--

DROP TABLE IF EXISTS `suscribers`;
CREATE TABLE IF NOT EXISTS `suscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `PostingDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `suscribers`
--

INSERT INTO `suscribers` (`id`, `email`, `PostingDate`) VALUES
(1, 'jun@gmail.com', '2021-12-07 07:53:54'),
(2, 'juniord.dj88@gmail.com', '2021-12-12 12:54:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_code` varchar(255) DEFAULT NULL,
  `verified_email` int(11) DEFAULT NULL,
  `registered_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `role` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `lastname`, `firstname`, `email`, `password`, `verification_code`, `verified_email`, `registered_on`, `role`) VALUES
(1, 'Davidruph', 'junior', 'David', 'jun@gmail.com', '$2y$10$GkhAHUduht8K7YxX/xCXuOkKTT64tY84hOMyT0vtWQvy.sBnij4JK', NULL, NULL, '2021-12-07 09:29:04', 'admin'),
(2, 'Davidruph1', 'Manny', 'David', 'paytest432@gmail.com', '$2y$10$1LZ06MB82G7aSPlPWAT/kOp.7a1zLk3wMxRX1/bKlLDxmawGffMgG', 'jdcJ3bh96d077NJ078cd9jfuGghMN2bDahe0VdF534Dg8T1cE', NULL, '2021-12-15 11:17:16', 'user'),
(7, 'Johnny', 'Rogers', 'John', 'juniord.dj88@gmail.com', '$2y$10$k11x7eo/.MUQK/3kixQ69OQlWLAQ8lMZI/a52ByghIzTN6tkpoYVi', '0', 1, '2021-12-15 11:49:38', 'user');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
