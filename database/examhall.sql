-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 23, 2024 at 07:41 PM
-- Server version: 8.0.39-0ubuntu0.24.04.2
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `examhall`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin', '123', '2024-05-03 12:38:22', '2024-05-03 12:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `department_description` text,
  `date_of_school` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_name`, `department_description`, `date_of_school`) VALUES
(1, 'software development', 'IT department', '2024-05-03 13:16:13'),
(2, 'BIT', 'IT Department', '2024-05-03 13:16:46'),
(3, 'Computer Engineering', 'desc', '2024-05-03 18:49:30');

-- --------------------------------------------------------

--
-- Table structure for table `exam_room`
--

CREATE TABLE `exam_room` (
  `room_id` int NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `exam_room`
--

INSERT INTO `exam_room` (`room_id`, `room_name`, `createdAt`, `updatedAt`, `status`) VALUES
(1, 'Room 1', '2024-05-03 13:18:50', '2024-05-03 13:18:50', NULL),
(2, 'Room 2', '2024-05-03 13:19:00', '2024-05-03 13:19:00', NULL),
(3, 'Room 3', '2024-05-03 13:19:08', '2024-05-03 13:19:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `seating_arrangements`
--

CREATE TABLE `seating_arrangements` (
  `id` int NOT NULL,
  `department_id` int NOT NULL,
  `year` varchar(10) NOT NULL,
  `exam_name` varchar(255) NOT NULL,
  `sessions` varchar(255) NOT NULL,
  `exam_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `teacher_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `seating_arrangements`
--

INSERT INTO `seating_arrangements` (`id`, `department_id`, `year`, `exam_name`, `sessions`, `exam_date`, `start_time`, `end_time`, `teacher_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'III', 'Programming', 'Day', '2024-05-08', '01:06:00', '01:06:00', 2, '2024-05-03 22:06:52', '2024-05-03 22:06:52'),
(2, 1, 'I', 'Big Data', 'Night', '2024-11-24', '20:17:00', '22:17:00', 1, '2024-11-23 18:17:51', '2024-11-23 18:17:51');

-- --------------------------------------------------------

--
-- Table structure for table `seating_details`
--

CREATE TABLE `seating_details` (
  `id` int NOT NULL,
  `seating_arrangement_id` int NOT NULL,
  `room_id` int NOT NULL,
  `num_students` int NOT NULL,
  `remaining_on_left` int NOT NULL DEFAULT '0',
  `remaining_in_middle` int NOT NULL DEFAULT '0',
  `remaining_on_right` int NOT NULL DEFAULT '0',
  `total_booked_seats` int NOT NULL DEFAULT '0',
  `reached_left` int NOT NULL DEFAULT '1',
  `reached_middle` int DEFAULT '1',
  `reached_right` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `seating_details`
--

INSERT INTO `seating_details` (`id`, `seating_arrangement_id`, `room_id`, `num_students`, `remaining_on_left`, `remaining_in_middle`, `remaining_on_right`, `total_booked_seats`, `reached_left`, `reached_middle`, `reached_right`) VALUES
(1, 1, 1, 71, 22, 24, 23, 2, 3, 1, 1),
(2, 2, 1, 35, 11, 12, 11, 1, 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `id` int NOT NULL,
  `student_id` int DEFAULT NULL,
  `set_number` varchar(50) DEFAULT NULL,
  `seating_arrangement_id` int DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`id`, `student_id`, `set_number`, `seating_arrangement_id`, `createdAt`, `updatedAt`) VALUES
(1, 1, 'L1', 1, '2024-05-03 22:07:11', '2024-05-03 22:07:11'),
(2, 3, 'L2', 1, '2024-05-03 22:14:42', '2024-05-03 22:14:42'),
(3, 4, 'L1', 2, '2024-11-23 18:52:21', '2024-11-23 18:52:21');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `reg_no` varchar(20) NOT NULL,
  `department_id` int NOT NULL,
  `year` varchar(3) NOT NULL,
  `session` varchar(100) DEFAULT 'day'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `reg_no`, `department_id`, `year`, `session`) VALUES
(1, 'Mucyo Jean', '2343/2024', 1, 'III', 'Day'),
(2, 'Mucyo Eric', '23123/2024', 1, 'I', 'Night'),
(3, 'Mugabo Elvis', '1343/2024', 1, 'III', 'Day'),
(4, 'Murenzi', '202412', 1, 'I', 'Night');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `username`, `email`) VALUES
(1, 'eric', 'tr@seating.com'),
(2, 'Kagabo Jean', 'kagabo@jean.com'),
(3, 'Mugisha', 'mugisha@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_name` (`department_name`);

--
-- Indexes for table `exam_room`
--
ALTER TABLE `exam_room`
  ADD PRIMARY KEY (`room_id`);

--
-- Indexes for table `seating_arrangements`
--
ALTER TABLE `seating_arrangements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`,`teacher_id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `seating_details`
--
ALTER TABLE `seating_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `seating_arrangement_id` (`seating_arrangement_id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`,`seating_arrangement_id`),
  ADD KEY `seating_arrangement_id` (`seating_arrangement_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `exam_room`
--
ALTER TABLE `exam_room`
  MODIFY `room_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `seating_arrangements`
--
ALTER TABLE `seating_arrangements`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `seating_details`
--
ALTER TABLE `seating_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `seating_arrangements`
--
ALTER TABLE `seating_arrangements`
  ADD CONSTRAINT `seating_arrangements_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seating_arrangements_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `seating_details`
--
ALTER TABLE `seating_details`
  ADD CONSTRAINT `seating_details_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `exam_room` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seating_details_ibfk_2` FOREIGN KEY (`seating_arrangement_id`) REFERENCES `seating_arrangements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seats_ibfk_3` FOREIGN KEY (`seating_arrangement_id`) REFERENCES `seating_arrangements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
