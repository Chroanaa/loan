-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 24, 2024 at 09:19 AM
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
(5, 'OnePuhunan Mandaue', '8WGH+PJM, Lopez Jaena St, Mandaue City, Cebu', '789-012-3456', 'mandaue@example.com', '2024-12-23 04:00:00'),
(6, 'OnePuhunan Pangasinan', '16 Mabini St San Carlos City Pangasinan', '456-789-1343', 'pangasinan@gmail.com', '2024-12-24 07:20:47');

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
(17, 27, 2313.00, -1, 999.99, 'Pending', '2024-12-23 12:02:42', '2024-12-23 12:02:42'),
(18, 27, 32131.00, 321321, 231.00, 'Pending', '2024-12-23 12:02:44', '2024-12-23 12:02:44'),
(19, 27, 3213.00, 13131, 999.99, 'Pending', '2024-12-23 12:02:46', '2024-12-23 12:02:46'),
(20, 26, 10000.00, 12, 10.00, 'Paid', '2024-12-23 12:08:57', '2024-12-23 12:09:45'),
(21, 26, 1000.00, 12, 10.00, 'Approved', '2024-12-23 13:12:37', '2024-12-23 13:13:23');

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
(22, 20, 10000.00, '2024-12-23 12:09:45', 0.00);

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
(34, 26, 'Loan Status Update', 'Your loan application with ID 14 has been approved.', '2024-12-23 11:21:47', 1),
(35, 26, 'Payment Reminder', 'A payment of 2000 has been made for your loan with ID 14.', '2024-12-23 11:36:51', 1),
(36, 26, 'Loan Status Update', 'Your loan application with ID 16 has been approved.', '2024-12-23 11:40:34', 1),
(37, 26, 'Loan Status Update', 'Your loan application with ID 14 has been deleted.', '2024-12-23 12:04:20', 1),
(38, 26, 'Loan Status Update', 'Your loan application with ID 16 has been deleted.', '2024-12-23 12:08:24', 1),
(39, 26, 'Loan Status Update', 'Your loan application with ID 20 has been approved.', '2024-12-23 12:09:34', 1),
(40, 26, 'Payment Reminder', 'A payment of 10000 has been made for your loan with ID 20.', '2024-12-23 12:09:45', 1),
(41, 26, 'Loan Status Update', 'Your loan application with ID 21 has been approved.', '2024-12-23 13:13:23', 1);

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
(12, 'admin', '32132', '09263627271', 'admin', 'admin', 'admin@gmail.com', '$2y$10$P4dhAkQ5BMYLq5ZsJpnx4uAgGuW1H5jS8WVlGdO41kcT1xkEvIpoC', '', '2024-12-23 02:50:03', NULL, NULL, NULL, NULL),
(24, 'Loan Officer Example', NULL, NULL, 'lo', 'loanofficer', 'loanofficer@gmail.com', '$2y$10$tn2wqfrCFErGo9IDnVLYue1x.BeGg/pnGNgAiuuEBqmobiX./Jm0K', '', '2024-12-23 11:14:18', NULL, NULL, NULL, 1),
(25, 'Branch Manager Example', NULL, NULL, 'bm', 'branchmanager', 'branchmanager@gmail.com', '$2y$10$tCMdmhR09TCTG8eY8u6aqe7f8FIMPr216TAzre5/VATPGakIkJRcK', '', '2024-12-23 11:15:31', NULL, NULL, NULL, 6),
(26, 'Kim F. Gamot', 'Dakila St. Batasan Hills Quezon City', '09263627274', 'client', 'duplicationph', 'gamot.kim.fernandez@gmail.com', '$2y$10$7DGzrt7IhXrzD0x7qoFu6ejlG/xS.OhtLI1KPVkPiAY0uNYjfCroO', '', '2024-12-23 11:19:43', NULL, NULL, NULL, 1),
(27, 'client2', 'dakila', '09263627271', 'client', 'client2', 'gamotkim96@gmail.com', '$2y$10$f5ymDMvdYMJPsnv4EoaKVOQdQ356nvavHcOJZGiCCUXE3Z7S8XKl6', '', '2024-12-23 12:02:30', NULL, NULL, NULL, 2);

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
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `loan_applications`
--
ALTER TABLE `loan_applications`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `loan_repayments`
--
ALTER TABLE `loan_repayments`
  MODIFY `repayment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_tbl`
--
ALTER TABLE `user_tbl`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
