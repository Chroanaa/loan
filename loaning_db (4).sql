-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2024 at 05:58 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `loaning_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `branch_id` int(11) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `branch_address` text DEFAULT NULL,
  `branch_contact` varchar(20) DEFAULT NULL,
  `branch_email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`branch_id`, `branch_name`, `branch_address`, `branch_contact`, `branch_email`, `created_at`) VALUES
(1, 'OnePuhunan North Caloocan 01', 'Q22W+22R, Zabarte Rd, Novaliches, Quezon City, Metro Manila', '123-456-7890', 'northcaloocan01@example.com', '2024-12-23 04:00:00'),
(2, 'OnePuhunan Cainta', 'H4GC+Q6F, A. Bonifacio Corner Espiritu Drive, Cainta, 1900 Rizal', '987-654-3210', 'cainta@example.com', '2024-12-23 04:00:00'),
(3, 'OnePuhunan Las Piñas', '354, 1747 Alabang–Zapote Rd, Las Piñas, Metro Manila', '456-789-0123', 'laspinas@example.com', '2024-12-23 04:00:00'),
(4, 'OnePuhunan North Caloocan 02', 'Q3C2+9J, Caloocan, Metro Manila', '321-654-0987', 'northcaloocan02@example.com', '2024-12-23 04:00:00'),
(5, 'OnePuhunan Mandaue', '8WGH+PJM, Lopez Jaena St, Mandaue City, Cebu', '789-012-3456', 'mandaue@example.com', '2024-12-23 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `loan_applications`
--

CREATE TABLE `loan_applications` (
  `loan_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `loan_amount` decimal(10,2) NOT NULL,
  `loan_term` int(11) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `status` enum('Pending','Approved','Rejected','Paid') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_applications`
--

INSERT INTO `loan_applications` (`loan_id`, `client_id`, `loan_amount`, `loan_term`, `interest_rate`, `status`, `created_at`, `updated_at`) VALUES
(6, 5, 213.00, -1, 321.00, 'Approved', '2024-12-23 02:51:53', '2024-12-23 04:56:38'),
(7, 5, 231.00, 3213, 213.00, 'Paid', '2024-12-23 03:28:57', '2024-12-23 04:56:16'),
(8, 5, 321.00, 321313, 999.99, 'Approved', '2024-12-23 03:44:44', '2024-12-23 04:51:35'),
(10, 5, 321321.00, 321321, 321.00, 'Rejected', '2024-12-23 03:44:48', '2024-12-23 03:55:23'),
(11, 5, 12.00, 32321, 321.00, 'Pending', '2024-12-23 04:25:38', '2024-12-23 04:25:38');

-- --------------------------------------------------------

--
-- Table structure for table `loan_repayments`
--

CREATE TABLE `loan_repayments` (
  `repayment_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `remaining_balance` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loan_repayments`
--

INSERT INTO `loan_repayments` (`repayment_id`, `loan_id`, `payment_amount`, `payment_date`, `remaining_balance`) VALUES
(3, 6, 321.00, '2024-12-23 03:22:04', -108.00),
(4, 7, 3232.00, '2024-12-23 03:43:48', -3001.00),
(11, 8, 323232.00, '2024-12-23 03:58:47', -322911.00),
(12, 6, 323232.00, '2024-12-23 04:49:37', -323553.00),
(13, 6, 3232.00, '2024-12-23 04:50:27', -326785.00),
(14, 6, 3232.00, '2024-12-23 04:52:31', -329804.00),
(15, 6, 3232.00, '2024-12-23 04:52:58', -333036.00),
(16, 6, 3232.00, '2024-12-23 04:54:40', -336268.00),
(17, 6, 3232.00, '2024-12-23 04:54:43', -339500.00),
(18, 6, 3232.00, '2024-12-23 04:55:16', -342732.00),
(19, 6, 3232.00, '2024-12-23 04:55:21', -345964.00),
(20, 7, 3232.00, '2024-12-23 04:56:16', -6233.00);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `notification_type` enum('Payment Reminder','Loan Status Update','Overdue Reminder') NOT NULL,
  `message` text NOT NULL,
  `notification_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `client_id`, `notification_type`, `message`, `notification_date`, `is_read`) VALUES
(4, 5, 'Payment Reminder', 'A payment of 3232 has been made for your loan with ID 7.', '2024-12-23 03:43:48', 1),
(5, 5, 'Loan Status Update', 'Your loan application with ID 8 has been approved.', '2024-12-23 03:49:10', 1),
(13, 5, 'Loan Status Update', 'Your loan application with ID 10 has been rejected.', '2024-12-23 03:55:23', 1),
(15, 5, 'Loan Status Update', 'Your loan application with ID 9 has been deleted.', '2024-12-23 03:59:47', 1),
(20, 5, 'Overdue Reminder', 'Dear Kim F. Gamot, your loan with ID 6 is overdue. Please make a payment as soon as possible.', '2024-12-23 04:24:43', 1),
(21, 5, 'Overdue Reminder', 'Dear Kim F. Gamot, your loan with ID 6 is overdue. Please make a payment as soon as possible.', '2024-12-23 04:37:37', 1),
(22, 5, 'Payment Reminder', 'A payment of 3232 has been made for your loan with ID 6.', '2024-12-23 04:52:31', 1),
(23, 5, 'Payment Reminder', 'A payment of 3232 has been made for your loan with ID 6.', '2024-12-23 04:52:58', 1),
(24, 5, 'Payment Reminder', 'A payment of 3232 has been made for your loan with ID 6.', '2024-12-23 04:54:40', 1),
(25, 5, 'Payment Reminder', 'A payment of 3232 has been made for your loan with ID 6.', '2024-12-23 04:54:43', 1),
(26, 5, 'Payment Reminder', 'A payment of 3232 has been made for your loan with ID 6.', '2024-12-23 04:55:16', 1),
(27, 5, 'Payment Reminder', 'A payment of 3232 has been made for your loan with ID 6.', '2024-12-23 04:55:21', 1),
(28, 5, 'Payment Reminder', 'A payment of 3232 has been made for your loan with ID 7.', '2024-12-23 04:56:16', 1),
(29, 5, 'Overdue Reminder', 'Dear Kim F. Gamot, your loan with ID 6 is overdue. Please make a payment as soon as possible.', '2024-12-23 04:56:42', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_tbl`
--

CREATE TABLE `user_tbl` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `role` enum('lo','admin','bm','client') NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `repeat_password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `remember_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_tbl`
--

INSERT INTO `user_tbl` (`user_id`, `name`, `address`, `contact`, `role`, `username`, `email`, `password`, `repeat_password`, `created_at`, `remember_token`, `reset_token`, `reset_expires`, `branch_id`) VALUES
(3, 'Duplication', '323232', '09263627273', 'admin', 'kimkim021', 'gamotkim96@gmail.com', '$2y$10$zUdmFVvNGri16Io2JAUxeeMxhPW41/tDl17VH4lIwZx0TROLh6lZq', '', '2024-12-22 03:24:35', '556a05dae031b8f29a2bfa70549c461006afcc1b85c1201bcd92e35ab01386957cb3326ae9e6c0e02c331d3d732099ede53f4414e803a88ba3678d65bb5c7965', NULL, NULL, NULL),
(5, 'Kim F. Gamot', '32132', '09263627271', 'client', 'kim', 'gamot.kim.fernandez@gmail.com', '$2y$10$P4dhAkQ5BMYLq5ZsJpnx4uAgGuW1H5jS8WVlGdO41kcT1xkEvIpoC', '', '2024-12-23 02:50:03', NULL, NULL, NULL, 3),
(7, 'Kim F. Gamot', '32132', '09263627271', 'lo', 'kim1', 'gamot.kim.fernandez@gmail.com1', '$2y$10$P4dhAkQ5BMYLq5ZsJpnx4uAgGuW1H5jS8WVlGdO41kcT1xkEvIpoC', '', '2024-12-23 02:50:03', NULL, NULL, NULL, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`branch_id`);

--
-- Indexes for table `loan_applications`
--
ALTER TABLE `loan_applications`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `loan_repayments`
--
ALTER TABLE `loan_repayments`
  ADD PRIMARY KEY (`repayment_id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `branch_id` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loan_applications`
--
ALTER TABLE `loan_applications`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `loan_repayments`
--
ALTER TABLE `loan_repayments`
  MODIFY `repayment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loan_applications`
--
ALTER TABLE `loan_applications`
  ADD CONSTRAINT `loan_applications_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `user_tbl` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `loan_repayments`
--
ALTER TABLE `loan_repayments`
  ADD CONSTRAINT `loan_repayments_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `loan_applications` (`loan_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `user_tbl` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_tbl`
--
ALTER TABLE `user_tbl`
  ADD CONSTRAINT `user_tbl_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`branch_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
