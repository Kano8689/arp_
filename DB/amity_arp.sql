-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2025 at 09:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `amity_arp`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses_table`
--

CREATE TABLE `courses_table` (
  `_id` int(11) NOT NULL,
  `course_owner_id` int(11) NOT NULL,
  `course_code` varchar(10) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `course_type` int(11) NOT NULL,
  `theory_marks` int(11) NOT NULL DEFAULT 0,
  `practical_marks` int(11) NOT NULL DEFAULT 0,
  `credit_marks` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses_table`
--

INSERT INTO `courses_table` (`_id`, `course_owner_id`, `course_code`, `course_name`, `course_type`, `theory_marks`, `practical_marks`, `credit_marks`, `created_at`, `updated_at`) VALUES
(1, 4, 'CSE2052', 'Introduction to AI and ML', 3, 3, 2, 4, '2025-10-29 18:54:10', '2025-12-03 11:01:45'),
(2, 4, 'CSE1021', 'Python Programming', 2, 0, 4, 2, '2025-10-29 18:54:10', NULL),
(3, 4, 'CSE1030', 'Introduction to Cloud Computing', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(4, 4, 'MAT1009', 'Discrete Mathematics', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(5, 4, 'CSE3048', 'NoSQL Databases', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(6, 4, 'CSE1028', 'Introduction to Software Engineering', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(7, 4, 'MAT1013', 'Statistics and Probability', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(8, 4, 'MAT1008', 'Mathematics for Computer Applications', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(9, 4, 'MGT1001', 'Introduction to Business Management', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(10, 4, 'CHE1001', 'Environmental Studies', 1, 2, 0, 0, '2025-10-29 18:54:10', NULL),
(11, 4, 'CSE1032', 'Linux Fundamentals', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(12, 4, 'CSE2051', 'Introduction to Database Management Systems', 3, 3, 2, 4, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(13, 4, 'FRE1002', 'Communicative French', 2, 0, 2, 1, '2025-10-29 18:54:10', NULL),
(14, 4, 'ENG1004', 'Functional English', 2, 0, 2, 1, '2025-10-29 18:54:10', NULL),
(15, 4, 'CSE5011', 'Advanced Java Programming', 2, 0, 4, 2, '2025-10-29 18:54:10', NULL),
(16, 4, 'CSE5067', 'Advanced Data Structures and Algorithms', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(17, 4, 'CSE5017', 'Full Stack Development', 2, 0, 4, 2, '2025-10-29 18:54:10', NULL),
(18, 4, 'CSE5042', 'Blockchain Technology and Applications', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(19, 4, 'CSE5024', 'Advanced Software Testing', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(20, 4, 'CSE5004', 'Distributed Operating Systems', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(21, 4, 'CSE5019', 'Deep Learning Techniques', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(22, 4, 'CSE3050', 'Programming Skills for Employment', 2, 0, 2, 1, '2025-10-29 18:54:10', NULL),
(23, 4, 'SSK2002', 'Being Corporate Ready', 2, 0, 2, 1, '2025-10-29 18:54:10', '2025-12-02 10:57:07'),
(24, 4, 'CSE6009', 'MCA Project - 3', 0, 0, 0, 2, '2025-10-29 18:54:10', NULL),
(25, 4, 'CSE6004', 'Research Paper', 0, 0, 0, 2, '2025-10-29 18:54:10', NULL),
(26, 4, 'CSE5010', 'Advanced Python', 2, 0, 4, 2, '2025-10-29 18:54:10', NULL),
(27, 4, 'MAT5009', 'Applied Statistics Using R', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:52:00'),
(28, 4, 'CSE5129', 'Computer Science Fundamentals (Bridge Course)', 1, 3, 0, 0, '2025-10-29 18:54:10', NULL),
(29, 4, 'CSE5008', 'Data Communications and Networks', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:52:09'),
(30, 4, 'CSE5050', 'Artificial Intelligence and Machine Learning', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(31, 4, 'CSE5009', 'Web Design and Development', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(32, 4, 'CSE5006', 'Relational Database', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(33, 4, 'CSE6007', 'MCA Project - 1', 0, 0, 0, 2, '2025-10-29 18:54:10', NULL),
(34, 4, 'CSE5053', 'Cloud Infrastructure, Services and APIs', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(36, 4, 'CSE5028', 'Network System Administration and Security', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(37, 4, 'CSE5051', 'Big Data Analytics and Business Intelligence', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(38, 4, 'ENG5002', 'Technical Proficiency and Career Building', 2, 0, 2, 1, '2025-10-29 18:54:10', NULL),
(39, 4, 'GER1002', 'Communicative German', 2, 0, 2, 1, '2025-10-29 18:54:10', NULL),
(40, 4, 'CSE6010', 'M.Sc. Minor Project', 0, 0, 0, 3, '2025-10-29 18:54:10', NULL),
(41, 4, 'CSE5060', 'Operating Systems and Virtual Machines', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(42, 4, 'CSE5065', 'Linux Programming', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(43, 4, 'CSE5039', 'Ethical Hacking Techniques', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(44, 4, 'CSE5066', 'Research Methodologies', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(45, 4, 'CSE5080', 'Quantitative Text Analysis', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(46, 4, 'MAT5007', 'Mathematics for Data Science', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-09 11:54:11'),
(47, 4, 'MAT5006', 'Time Series Analysis', 3, 2, 2, 3, '2025-10-29 18:54:10', '2025-12-02 10:53:07'),
(96, 4, 'CSE5041', 'Penetration Testing, Incident Response and Forensics', 3, 2, 2, 3, '2025-12-02 10:57:07', '2025-12-09 11:48:12');

-- --------------------------------------------------------

--
-- Table structure for table `course_owner`
--

CREATE TABLE `course_owner` (
  `_id` int(11) NOT NULL,
  `course_owner_name` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_owner`
--

INSERT INTO `course_owner` (`_id`, `course_owner_name`, `created_at`, `updated_at`) VALUES
(1, 'ABS', '2025-10-02 16:41:02', '2025-10-02 16:44:48'),
(2, 'AIB', '2025-10-02 16:41:02', '2025-10-02 16:44:52'),
(3, 'AIBHAS', '2025-10-02 16:42:27', '2025-10-02 16:45:03'),
(4, 'AIIT', '2025-10-02 16:42:27', '2025-10-02 16:45:08'),
(5, 'ASAS', '2025-10-02 16:42:27', NULL),
(6, 'ASCENT', '2025-10-02 16:42:27', '2025-10-02 16:45:20'),
(7, 'ASET', '2025-10-02 16:42:27', '2025-10-02 16:45:25'),
(8, 'ASET & AIIT', '2025-10-02 16:42:27', '2025-10-02 16:45:30'),
(9, 'ASL', '2025-10-02 16:42:27', '2025-10-02 16:45:34');

-- --------------------------------------------------------

--
-- Table structure for table `department_table`
--

CREATE TABLE `department_table` (
  `_id` int(11) NOT NULL,
  `department_name` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_table`
--

INSERT INTO `department_table` (`_id`, `department_name`, `created_at`, `updated_at`) VALUES
(1, 'AIIT', '2025-10-03 16:45:03', NULL),
(2, 'Engineering', '2025-10-03 16:45:03', NULL),
(6, 'Low', '2025-10-03 16:45:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `faculties_details`
--

CREATE TABLE `faculties_details` (
  `_id` int(11) NOT NULL,
  `faculty_code` varchar(20) NOT NULL,
  `faculty_name` varchar(50) NOT NULL,
  `facultie_department` int(11) NOT NULL,
  `faculty_email` varchar(255) NOT NULL,
  `faculty_join_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculties_details`
--

INSERT INTO `faculties_details` (`_id`, `faculty_code`, `faculty_name`, `facultie_department`, `faculty_email`, `faculty_join_date`, `created_at`, `updated_at`) VALUES
(1, '336541', 'Dr. Gopal R', 1, 'dr.gopal@gmail.com', '2025-05-15', '2025-12-02 15:59:05', NULL),
(3, '345345', 'Dr. Padmasudha', 1, 'dr.padmasudha@gmail.com', '2024-04-18', '2025-12-04 18:03:31', NULL),
(4, '654453', 'Dr. Chandreshekhar', 1, 'dr.chandreshekhar@gmail.com', '2023-11-16', '2025-12-04 18:05:45', '2025-12-09 16:50:50');

-- --------------------------------------------------------

--
-- Table structure for table `freeze_push_permission`
--

CREATE TABLE `freeze_push_permission` (
  `id` int(11) NOT NULL,
  `fac_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `ca1_freeze` int(11) NOT NULL DEFAULT 0,
  `ca1_push` int(11) NOT NULL DEFAULT 0,
  `ca2_freeze` int(11) NOT NULL DEFAULT 0,
  `ca2_push` int(11) NOT NULL DEFAULT 0,
  `ca3_freeze` int(11) NOT NULL DEFAULT 0,
  `ca3_push` int(11) NOT NULL DEFAULT 0,
  `internal_freeze` int(11) NOT NULL DEFAULT 0,
  `internal_push` int(11) NOT NULL DEFAULT 0,
  `lab_freeze` int(11) NOT NULL DEFAULT 0,
  `lab_push` int(11) NOT NULL DEFAULT 0,
  `academic_year` year(4) NOT NULL,
  `academic_sem` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `freeze_push_permission`
--

INSERT INTO `freeze_push_permission` (`id`, `fac_id`, `course_id`, `ca1_freeze`, `ca1_push`, `ca2_freeze`, `ca2_push`, `ca3_freeze`, `ca3_push`, `internal_freeze`, `internal_push`, `lab_freeze`, `lab_push`, `academic_year`, `academic_sem`, `created_at`, `updated_at`) VALUES
(9, 1, 32, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, '2025', 1, '2025-12-10 09:47:31', '2025-12-11 08:22:15'),
(10, 4, 47, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, '2025', 1, '2025-12-10 09:48:11', '2025-12-11 08:22:15'),
(11, 3, 46, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, '2025', 1, '2025-12-10 09:55:41', '2025-12-11 08:22:15'),
(12, 4, 2, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, '2025', 1, '2025-12-10 09:56:51', '2025-12-11 08:22:15');

-- --------------------------------------------------------

--
-- Table structure for table `login_table`
--

CREATE TABLE `login_table` (
  `_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_table`
--

INSERT INTO `login_table` (`_id`, `user_name`, `user_password`, `user_type`, `created_at`, `updated_at`) VALUES
(33, 'admin@amity.blr.edu', 'kT7xg0YRq0OQUjdr+14lYS6nGgeLLwo4mIKtSIpJ0S8=', 1, '2025-10-06 08:41:59', '2025-10-07 18:53:15'),
(40, 'ceo@amity.blr.edu', 'LpdhZzbvY8iTjQ7bQaUovDM9JRyMvf+jieWHRN88C+0=', 2, '2025-10-06 08:41:59', '2025-12-11 08:10:49'),
(288, '654453@amity.blr.edu', 'thmY6r5z8dEyThLqrZCSwCKXfcHv17UPLxb3/G3WSJ4=', 3, '2025-10-06 08:41:59', '2025-12-11 08:12:21'),
(289, '345345@amity.blr.edu', '6wKP8q3MjvMZMI2UszhDaVUc4zVFxieRMKBnCrK1Zjs=', 3, '2025-10-06 08:41:59', '2025-12-09 16:50:50'),
(290, '336541@amity.blr.edu', '6wKP8q3MjvMZMI2UszhDaVUc4zVFxieRMKBnCrK1Zjs=', 3, '2025-10-06 08:41:59', '2025-12-09 16:50:50'),
(291, 'A869117725001@amity.blr.edu', '6wKP8q3MjvMZMI2UszhDaVUc4zVFxieRMKBnCrK1Zjs=', 4, '2025-10-06 08:41:59', '2025-12-09 16:50:50'),
(292, 'A869117725002@amity.blr.edu', '6wKP8q3MjvMZMI2UszhDaVUc4zVFxieRMKBnCrK1Zjs=', 4, '2025-10-06 08:41:59', '2025-12-09 16:50:50'),
(293, 'A869117725003@amity.blr.edu', '6wKP8q3MjvMZMI2UszhDaVUc4zVFxieRMKBnCrK1Zjs=', 4, '2025-10-06 08:41:59', '2025-12-09 16:50:50');

-- --------------------------------------------------------

--
-- Table structure for table `mapping_faculty`
--

CREATE TABLE `mapping_faculty` (
  `_id` int(11) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `semester_year` varchar(10) NOT NULL,
  `semester_type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mapping_faculty`
--

INSERT INTO `mapping_faculty` (`_id`, `faculty_id`, `course_id`, `slot_id`, `semester_year`, `semester_type`, `created_at`, `updated_at`) VALUES
(2, 1, 32, 13, '2025', 1, '2025-12-09 17:07:51', NULL),
(3, 4, 47, 4, '2025', 1, '2025-12-09 17:16:31', NULL),
(8, 3, 46, 40, '2025', 1, '2025-12-10 09:55:41', NULL),
(9, 4, 2, 24, '2025', 1, '2025-12-10 09:56:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mapping_student`
--

CREATE TABLE `mapping_student` (
  `_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `registration_type` varchar(20) NOT NULL,
  `semester_year` varchar(10) NOT NULL,
  `semester_type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mapping_student`
--

INSERT INTO `mapping_student` (`_id`, `student_id`, `course_id`, `slot_id`, `registration_type`, `semester_year`, `semester_type`, `created_at`, `updated_at`) VALUES
(1, 205, 32, 13, 'Fresh', '2025', 1, '2025-12-09 17:40:31', '2025-12-11 08:05:40'),
(3, 207, 47, 13, 'Fresh', '2025', 1, '2025-12-10 10:01:09', '2025-12-11 08:05:42'),
(4, 207, 17, 5, 'Backlog', '2025', 1, '2025-12-11 08:07:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `program_table`
--

CREATE TABLE `program_table` (
  `_id` int(11) NOT NULL,
  `program_name` varchar(50) NOT NULL,
  `program_semester` int(11) NOT NULL,
  `graduation_type` int(11) NOT NULL,
  `program_department` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `program_table`
--

INSERT INTO `program_table` (`_id`, `program_name`, `program_semester`, `graduation_type`, `program_department`, `created_at`, `updated_at`) VALUES
(1, 'BCA', 6, 1, 1, '2025-10-29 19:22:56', NULL),
(2, 'MCA', 4, 2, 1, '2025-10-29 19:32:21', NULL),
(3, 'MSC (Cyber Security)', 4, 2, 1, '2025-10-29 19:32:32', '2025-10-29 19:32:58'),
(4, 'MSC (Data Science)', 4, 2, 1, '2025-10-29 19:32:47', '2025-12-09 15:47:32');

-- --------------------------------------------------------

--
-- Table structure for table `result_semester`
--

CREATE TABLE `result_semester` (
  `_id` int(11) NOT NULL,
  `std_dtl_id` int(11) NOT NULL,
  `student_course_id` int(11) NOT NULL,
  `ca_1` double DEFAULT 0,
  `ca_2` double DEFAULT 0,
  `ca_3` double DEFAULT 0,
  `practical_marks` double DEFAULT 0,
  `internal_marks` double DEFAULT 0,
  `total_credit` int(11) NOT NULL DEFAULT 0,
  `result_year` year(4) NOT NULL DEFAULT current_timestamp(),
  `result_sem_type` int(11) DEFAULT 0,
  `remarks` text NOT NULL,
  `declare_date` date DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `result_semester`
--

INSERT INTO `result_semester` (`_id`, `std_dtl_id`, `student_course_id`, `ca_1`, `ca_2`, `ca_3`, `practical_marks`, `internal_marks`, `total_credit`, `result_year`, `result_sem_type`, `remarks`, `declare_date`, `created_at`, `updated_at`) VALUES
(89, 205, 32, 50, 0, 0, 22, 0, 0, '2025', 1, '', '2025-12-04', '2025-12-04 16:34:43', '2025-12-08 10:13:34'),
(90, 206, 32, 30, 0, 0, 10, 0, 0, '2025', 1, '', '2025-12-04', '2025-12-04 16:34:43', '2025-12-08 10:13:34'),
(92, 207, 32, 22, 0, 0, 10, 0, 0, '2025', 1, '', '2025-12-04', '2025-12-04 16:49:23', '2025-12-08 10:13:34');

-- --------------------------------------------------------

--
-- Table structure for table `slot_table`
--

CREATE TABLE `slot_table` (
  `_id` int(11) NOT NULL,
  `slot_name` varchar(15) NOT NULL,
  `slot_type` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slot_table`
--

INSERT INTO `slot_table` (`_id`, `slot_name`, `slot_type`, `created_at`, `updated_at`) VALUES
(1, 'A1', 1, '2025-10-09 05:28:36', NULL),
(2, 'A2', 1, '2025-10-09 05:28:36', NULL),
(3, 'B1', 1, '2025-10-09 05:28:36', NULL),
(4, 'B2', 1, '2025-10-09 05:28:36', NULL),
(5, 'C1', 1, '2025-10-09 05:28:36', NULL),
(6, 'C2', 1, '2025-10-09 05:28:36', NULL),
(7, 'D1', 1, '2025-10-09 05:28:36', NULL),
(8, 'D2', 1, '2025-10-09 05:28:36', NULL),
(9, 'E1', 1, '2025-10-09 05:28:36', NULL),
(10, 'E2', 1, '2025-10-09 05:28:36', NULL),
(11, 'F1', 1, '2025-10-09 05:28:36', NULL),
(12, 'F2', 1, '2025-10-09 05:28:36', NULL),
(13, 'G1', 1, '2025-10-09 05:28:36', NULL),
(14, 'G2', 1, '2025-10-09 05:28:36', NULL),
(15, 'TA1', 1, '2025-10-09 05:31:04', NULL),
(16, 'TA2', 1, '2025-10-09 05:31:04', NULL),
(17, 'TB1', 1, '2025-10-09 05:31:04', NULL),
(18, 'TB2', 1, '2025-10-09 05:31:04', NULL),
(19, 'TC1', 1, '2025-10-09 05:31:04', NULL),
(20, 'TC2', 1, '2025-10-09 05:31:04', NULL),
(21, 'L1+L2', 2, '2025-10-09 05:31:18', NULL),
(22, 'L3+L4', 2, '2025-10-09 05:31:18', NULL),
(23, 'L5+L6', 2, '2025-10-09 05:31:18', NULL),
(24, 'L7+L8', 2, '2025-10-09 05:31:18', NULL),
(25, 'L9+L10', 2, '2025-10-09 05:31:18', NULL),
(26, 'L11+L12', 2, '2025-10-09 05:31:18', NULL),
(27, 'L13+L14', 2, '2025-10-09 05:31:18', NULL),
(28, 'L15+L16', 2, '2025-10-09 05:31:18', NULL),
(29, 'L17+L18', 2, '2025-10-09 05:31:18', NULL),
(30, 'L19+L20', 2, '2025-10-09 05:31:18', NULL),
(31, 'L21+L22', 2, '2025-10-09 05:31:18', NULL),
(32, 'L23+L24', 2, '2025-10-09 05:31:18', NULL),
(33, 'L25+L26', 2, '2025-10-09 05:31:18', NULL),
(34, 'L27+L28', 2, '2025-10-09 05:31:18', NULL),
(35, 'L29+L30', 2, '2025-10-09 05:31:18', NULL),
(36, 'L31+L32', 2, '2025-10-09 05:31:18', NULL),
(37, 'L33+L34', 2, '2025-10-09 05:31:18', NULL),
(38, 'L35+L36', 2, '2025-10-09 05:31:18', NULL),
(39, 'L37+L38', 2, '2025-10-09 05:31:18', NULL),
(40, 'L39+L40', 2, '2025-10-09 05:31:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `students_details`
--

CREATE TABLE `students_details` (
  `_id` int(11) NOT NULL,
  `enrollment_no` varchar(20) NOT NULL,
  `student_name` varchar(50) NOT NULL,
  `program_id` int(11) NOT NULL,
  `year_admitted` year(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students_details`
--

INSERT INTO `students_details` (`_id`, `enrollment_no`, `student_name`, `program_id`, `year_admitted`, `created_at`, `updated_at`) VALUES
(205, 'A869117725001', 'Mavani Krishnam Vajubhai', 4, '2025', '2025-12-02 11:17:42', '2025-12-09 16:25:42'),
(206, 'A869117725002', 'Radadiya Vansh Ashokbhai', 4, '2025', '2025-12-02 11:17:42', NULL),
(207, 'A869117725003', 'Hirpara Tirth', 4, '2025', '2025-12-02 11:17:42', '2025-12-09 16:17:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses_table`
--
ALTER TABLE `courses_table`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `courses_table_owner_name_id_relation` (`course_owner_id`);

--
-- Indexes for table `course_owner`
--
ALTER TABLE `course_owner`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `department_table`
--
ALTER TABLE `department_table`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `faculties_details`
--
ALTER TABLE `faculties_details`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `freeze_push_permission`
--
ALTER TABLE `freeze_push_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `freeze_push_course_fac_id_relation` (`fac_id`),
  ADD KEY `freeze_push_course_id_relation` (`course_id`);

--
-- Indexes for table `login_table`
--
ALTER TABLE `login_table`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `mapping_faculty`
--
ALTER TABLE `mapping_faculty`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `mapping_faculty_table_faculty_id_relation_ship` (`faculty_id`),
  ADD KEY `mapping_course_table_faculty_id_relation_ship` (`course_id`),
  ADD KEY `mapping_faculty_table_slot_id_relation_ship` (`slot_id`);

--
-- Indexes for table `mapping_student`
--
ALTER TABLE `mapping_student`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `program_table`
--
ALTER TABLE `program_table`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `program_department_id_constraint_relation` (`program_department`);

--
-- Indexes for table `result_semester`
--
ALTER TABLE `result_semester`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `student_courses_final_result_id_relation` (`student_course_id`),
  ADD KEY `student_details_final_result_id_relation` (`std_dtl_id`);

--
-- Indexes for table `slot_table`
--
ALTER TABLE `slot_table`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `students_details`
--
ALTER TABLE `students_details`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `student_details_program_id_relation` (`program_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `courses_table`
--
ALTER TABLE `courses_table`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `course_owner`
--
ALTER TABLE `course_owner`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `department_table`
--
ALTER TABLE `department_table`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `faculties_details`
--
ALTER TABLE `faculties_details`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `freeze_push_permission`
--
ALTER TABLE `freeze_push_permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `login_table`
--
ALTER TABLE `login_table`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=294;

--
-- AUTO_INCREMENT for table `mapping_faculty`
--
ALTER TABLE `mapping_faculty`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `mapping_student`
--
ALTER TABLE `mapping_student`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `program_table`
--
ALTER TABLE `program_table`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `result_semester`
--
ALTER TABLE `result_semester`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `slot_table`
--
ALTER TABLE `slot_table`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `students_details`
--
ALTER TABLE `students_details`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=285;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses_table`
--
ALTER TABLE `courses_table`
  ADD CONSTRAINT `courses_table_owner_name_id_relation` FOREIGN KEY (`course_owner_id`) REFERENCES `course_owner` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `freeze_push_permission`
--
ALTER TABLE `freeze_push_permission`
  ADD CONSTRAINT `freeze_push_course_fac_id_relation` FOREIGN KEY (`fac_id`) REFERENCES `faculties_details` (`_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `freeze_push_course_id_relation` FOREIGN KEY (`course_id`) REFERENCES `courses_table` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `mapping_faculty`
--
ALTER TABLE `mapping_faculty`
  ADD CONSTRAINT `mapping_course_table_faculty_id_relation_ship` FOREIGN KEY (`course_id`) REFERENCES `courses_table` (`_id`),
  ADD CONSTRAINT `mapping_faculty_table_faculty_id_relation_ship` FOREIGN KEY (`faculty_id`) REFERENCES `faculties_details` (`_id`),
  ADD CONSTRAINT `mapping_faculty_table_slot_id_relation_ship` FOREIGN KEY (`slot_id`) REFERENCES `slot_table` (`_id`);

--
-- Constraints for table `program_table`
--
ALTER TABLE `program_table`
  ADD CONSTRAINT `program_department_id_constraint_relation` FOREIGN KEY (`program_department`) REFERENCES `department_table` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `result_semester`
--
ALTER TABLE `result_semester`
  ADD CONSTRAINT `student_course_id_final_result_id_relation` FOREIGN KEY (`student_course_id`) REFERENCES `courses_table` (`_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `student_details_id_final_result_id_relation` FOREIGN KEY (`std_dtl_id`) REFERENCES `students_details` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `students_details`
--
ALTER TABLE `students_details`
  ADD CONSTRAINT `student_details_program_id_relation` FOREIGN KEY (`program_id`) REFERENCES `program_table` (`_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
