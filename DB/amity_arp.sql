-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 29, 2025 at 08:34 PM
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
-- Table structure for table `ceo_permissions`
--

CREATE TABLE `ceo_permissions` (
  `id` int(11) NOT NULL,
  `fac_id` int(11) NOT NULL,
  `freeze` int(11) NOT NULL DEFAULT 1,
  `course_id` int(11) NOT NULL,
  `push` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `courses_table`
--

CREATE TABLE `courses_table` (
  `_id` int(11) NOT NULL,
  `course_owner_id` int(11) NOT NULL,
  `course_code` varchar(10) NOT NULL,
  `course_name` varchar(50) NOT NULL,
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
(1, 4, 'CSE2052', 'Introduction to AI and ML', 0, 3, 2, 4, '2025-10-29 18:54:10', NULL),
(2, 4, 'CSE1021', 'Python Programming', 2, 0, 4, 2, '2025-10-29 18:54:10', NULL),
(3, 4, 'CSE1030', 'Introduction to Cloud Computing', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(4, 4, 'MAT1009', 'Discrete Mathematics', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(5, 4, 'CSE3048', 'NoSQL Databases', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(6, 4, 'CSE1028', 'Introduction to Software Engineering', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(7, 4, 'MAT1013', 'Statistics and Probability', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(8, 4, 'MAT1008', 'Mathematics for Computer Applications', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(9, 4, 'MGT1001', 'Introduction to Business Management', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(10, 4, 'CHE1001', 'Environmental Studies', 1, 2, 0, 0, '2025-10-29 18:54:10', NULL),
(11, 4, 'CSE1032', 'Linux Fundamentals', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(12, 4, 'CSE2051', 'Introduction to Database Management Systems', 0, 3, 2, 4, '2025-10-29 18:54:10', NULL),
(13, 4, 'FRE1002', 'Communicative French', 2, 0, 2, 1, '2025-10-29 18:54:10', NULL),
(14, 4, 'ENG1004', 'Functional English', 2, 0, 2, 1, '2025-10-29 18:54:10', NULL),
(15, 4, 'CSE5011', 'Advanced Java Programming', 2, 0, 4, 2, '2025-10-29 18:54:10', NULL),
(16, 4, 'CSE5067', 'Advanced Data Structures and Algorithms', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(17, 4, 'CSE5017', 'Full Stack Development', 2, 0, 4, 2, '2025-10-29 18:54:10', NULL),
(18, 4, 'CSE5042', 'Blockchain Technology and Applications', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(19, 4, 'CSE5024', 'Advanced Software Testing', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(20, 4, 'CSE5004', 'Distributed Operating Systems', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(21, 4, 'CSE5019', 'Deep Learning Techniques', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(22, 4, 'CSE3050', 'Programming Skills for Employment', 2, 0, 2, 1, '2025-10-29 18:54:10', NULL),
(23, 4, 'SSK2002', 'Being Corporate Ready', 2, 0, 2, 1, '2025-10-29 18:54:10', '2025-10-29 18:54:10'),
(24, 4, 'CSE6009', 'MCA Project - 3', 0, 0, 0, 2, '2025-10-29 18:54:10', NULL),
(25, 4, 'CSE6004', 'Research Paper', 0, 0, 0, 2, '2025-10-29 18:54:10', NULL),
(26, 4, 'CSE5010', 'Advanced Python', 2, 0, 4, 2, '2025-10-29 18:54:10', NULL),
(27, 4, 'MAT5009', 'Applied Statistics Using R', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(28, 4, 'CSE5129', 'Computer Science Fundamentals (Bridge Course)', 1, 3, 0, 0, '2025-10-29 18:54:10', NULL),
(29, 4, 'CSE5008', 'Data Communications and Networks', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(30, 4, 'CSE5050', 'Artificial Intelligence and Machine Learning', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(31, 4, 'CSE5009', 'Web Design and Development', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(32, 4, 'CSE5006', 'Relational Database', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(33, 4, 'CSE6007', 'MCA Project - 1', 0, 0, 0, 2, '2025-10-29 18:54:10', NULL),
(34, 4, 'CSE5053', 'Cloud Infrastructure, Services and APIs', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(35, 4, 'CSE5041', 'Penetration Testing, Incident Response and Forensi', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(36, 4, 'CSE5028', 'Network System Administration and Security', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(37, 4, 'CSE5051', 'Big Data Analytics and Business Intelligence', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(38, 4, 'ENG5002', 'Technical Proficiency and Career Building', 2, 0, 2, 1, '2025-10-29 18:54:10', NULL),
(39, 4, 'GER1002', 'Communicative German', 2, 0, 2, 1, '2025-10-29 18:54:10', NULL),
(40, 4, 'CSE6010', 'M.Sc. Minor Project', 0, 0, 0, 3, '2025-10-29 18:54:10', NULL),
(41, 4, 'CSE5060', 'Operating Systems and Virtual Machines', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(42, 4, 'CSE5065', 'Linux Programming', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(43, 4, 'CSE5039', 'Ethical Hacking Techniques', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(44, 4, 'CSE5066', 'Research Methodologies', 1, 3, 0, 3, '2025-10-29 18:54:10', NULL),
(45, 4, 'CSE5080', 'Quantitative Text Analysis', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(46, 4, 'MAT5007', 'Mathematics for Data Science', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL),
(47, 4, 'MAT5006', 'Time Series Analysis', 0, 2, 2, 3, '2025-10-29 18:54:10', NULL);

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
  `faculty_password` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculties_details`
--

INSERT INTO `faculties_details` (`_id`, `faculty_code`, `faculty_name`, `facultie_department`, `faculty_email`, `faculty_join_date`, `faculty_password`, `created_at`, `updated_at`) VALUES
(71, '332331', 'Dr. Chandrashekhar B N', 2, 'dr.cha@yahoo.in', '2025-10-01', 'IbIt1y8PzK6N5DwjHSldNDIn5uehtgZQLK893OEEdfI=', '2025-10-05 10:03:46', '2025-10-12 16:08:16'),
(72, '544443', 'Ms. Neethu Tressa', 2, 'dr.nt@outlook.com', '2024-11-11', 'Dm7reyDyh0xsOBK3tpYGtzUgkdp8/QTslzElPCY46Rw=', '2025-10-05 10:03:46', '2025-10-10 19:52:14'),
(73, '123443', 'Dr. Padmasudha', 1, 'dr.pmg@gmail.com', '2023-01-15', '83HyUzA1j9dMVvLlZX7S9MT1i4062YnP54aRZQtRCu8=', '2025-10-05 10:03:46', '2025-10-07 14:44:49'),
(74, '336541', 'Dr. Gopal R', 1, 'dr.gopal@gmail.com', '2024-12-18', 'dwT9Q7P2TkGqLqrb+fLAdYwirRs9CD/nNYpEh8a2vJQ=', '2025-10-07 14:46:37', '2025-10-10 16:33:34'),
(75, '8877665', 'Dr. Kumar Shashvat', 2, 'dr.kumarshashvat@gmail.com', '2025-09-29', 'EeVdFKR41aztsIbHT230bULeQdO3oEk1ExRn8+GykME=', '2025-10-07 15:26:48', NULL);

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
(24, '332331@amity.blr.edu', 'IbIt1y8PzK6N5DwjHSldNDIn5uehtgZQLK893OEEdfI=', 3, '2025-10-05 10:03:46', '2025-10-12 16:08:16'),
(25, '544443@amity.blr.edu', 'Dm7reyDyh0xsOBK3tpYGtzUgkdp8/QTslzElPCY46Rw=', 3, '2025-10-05 10:03:46', '2025-10-10 19:52:14'),
(26, '123443@amity.blr.edu', '83HyUzA1j9dMVvLlZX7S9MT1i4062YnP54aRZQtRCu8=', 3, '2025-10-05 10:03:46', '2025-10-05 10:05:29'),
(30, 'A869117725004@amity.blr.edu', 'RIYHYFIyUauU91DCjB+6JZk3GfBC6rDZ5LOYx5DvHCE=', 4, '2025-10-05 11:40:35', '2025-10-11 16:57:37'),
(31, 'A869117725007@amity.blr.edu', '2DXqE5Q+bYhwu/hgnGow2w86h6zEXzW9zgZRl3vHDuc=', 4, '2025-10-05 11:40:35', '2025-10-05 11:44:10'),
(32, 'A869117725008@amity.blr.edu', '8bUU2yj4MuRzCmKcHBs3xZWCS6RExQV0cDiPwabV7jE=', 4, '2025-10-05 11:40:35', '2025-10-13 09:47:26'),
(33, 'admin@amity.blr.edu', 'kT7xg0YRq0OQUjdr+14lYS6nGgeLLwo4mIKtSIpJ0S8=', 1, '2025-10-06 08:41:59', '2025-10-07 18:53:15'),
(34, '336541@amity.blr.edu', 'dwT9Q7P2TkGqLqrb+fLAdYwirRs9CD/nNYpEh8a2vJQ=', 3, '2025-10-07 14:46:37', '2025-10-10 16:33:34'),
(37, 'A866175124003@amity.blr.edu', 'KMCO/iTjpn/xDHEkSNqZQxMbvaWcnyiM87ud9Y8JcNg=', 4, '2025-10-07 15:23:45', NULL),
(39, 'A86605224099@amity.blr.edu', 'ZyYdREmsVLmKgpdmKIDY/lofYHJOUPUWfNI7UcUCh0o=', 4, '2025-10-09 06:16:53', NULL),
(40, 'ceo@amity.blr.edu', 'LuxKqp0yalSZvcKHRNw07xXf6N1JxBifDQ1W5EGfDAY=', 2, '2025-10-06 08:41:59', '2025-10-11 19:30:47'),
(41, 'ceo123456789@amity.blr.edu', 'H9pdpvWGC+JG/FhNLL/qPu4aQBkASJA7Fu21WF87i1k=', 2, '2025-10-11 19:32:05', '2025-10-11 19:32:36');

-- --------------------------------------------------------

--
-- Table structure for table `mapping_table`
--

CREATE TABLE `mapping_table` (
  `_id` int(11) NOT NULL,
  `stu_id` int(11) NOT NULL,
  `fac_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `slot_id` int(11) NOT NULL,
  `slot_year` varchar(10) NOT NULL,
  `semester_type` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(4, 'MSC (Data Science)', 4, 2, 1, '2025-10-29 19:32:47', '2025-10-29 19:33:06');

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
  `student_password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students_details`
--

INSERT INTO `students_details` (`_id`, `enrollment_no`, `student_name`, `program_id`, `year_admitted`, `student_password`, `created_at`, `updated_at`) VALUES
(43, 'A869117725004', 'SANKHA SAHA', 1, '2025', 'RIYHYFIyUauU91DCjB+6JZk3GfBC6rDZ5LOYx5DvHCE=', '2025-10-05 11:40:35', '2025-10-29 19:30:39'),
(44, 'A869117725007', 'PRAJVALL GOWDA', 1, '2025', '2DXqE5Q+bYhwu/hgnGow2w86h6zEXzW9zgZRl3vHDuc=', '2025-10-05 11:40:35', '2025-10-29 19:30:43'),
(45, 'A869117725008', 'Shredhika', 1, '2023', '8bUU2yj4MuRzCmKcHBs3xZWCS6RExQV0cDiPwabV7jE=', '2025-10-05 11:40:35', '2025-10-29 19:30:45'),
(46, 'A866175124003', 'Mr NITHIN GOWDA M', 1, '2024', 'KMCO/iTjpn/xDHEkSNqZQxMbvaWcnyiM87ud9Y8JcNg=', '2025-10-07 15:23:45', '2025-10-29 19:30:48'),
(47, 'A86605224099', 'Mr HIMANSU PRAFUL VASOYA', 1, '2025', 'ZyYdREmsVLmKgpdmKIDY/lofYHJOUPUWfNI7UcUCh0o=', '2025-10-09 06:16:53', '2025-10-29 19:30:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ceo_permissions`
--
ALTER TABLE `ceo_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `freeze_push_course_fac_id_relation` (`fac_id`),
  ADD KEY `freeze_push_course_id_relation` (`course_id`);

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
-- Indexes for table `login_table`
--
ALTER TABLE `login_table`
  ADD PRIMARY KEY (`_id`);

--
-- Indexes for table `mapping_table`
--
ALTER TABLE `mapping_table`
  ADD PRIMARY KEY (`_id`),
  ADD KEY `mapping_table_student_id_relation` (`stu_id`),
  ADD KEY `mapping_table_faculty_id_relation` (`fac_id`),
  ADD KEY `mapping_table_course_id_relation` (`course_id`),
  ADD KEY `mapping_table_slot_id_relation` (`slot_id`);

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
-- AUTO_INCREMENT for table `ceo_permissions`
--
ALTER TABLE `ceo_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `courses_table`
--
ALTER TABLE `courses_table`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

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
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `login_table`
--
ALTER TABLE `login_table`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `mapping_table`
--
ALTER TABLE `mapping_table`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `program_table`
--
ALTER TABLE `program_table`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `result_semester`
--
ALTER TABLE `result_semester`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slot_table`
--
ALTER TABLE `slot_table`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `students_details`
--
ALTER TABLE `students_details`
  MODIFY `_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ceo_permissions`
--
ALTER TABLE `ceo_permissions`
  ADD CONSTRAINT `freeze_push_course_fac_id_relation` FOREIGN KEY (`fac_id`) REFERENCES `faculties_details` (`_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `freeze_push_course_id_relation` FOREIGN KEY (`course_id`) REFERENCES `courses_table` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `courses_table`
--
ALTER TABLE `courses_table`
  ADD CONSTRAINT `courses_table_owner_name_id_relation` FOREIGN KEY (`course_owner_id`) REFERENCES `course_owner` (`_id`) ON UPDATE CASCADE;

--
-- Constraints for table `mapping_table`
--
ALTER TABLE `mapping_table`
  ADD CONSTRAINT `mapping_table_course_id_relation` FOREIGN KEY (`course_id`) REFERENCES `courses_table` (`_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `mapping_table_faculty_id_relation` FOREIGN KEY (`fac_id`) REFERENCES `faculties_details` (`_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `mapping_table_slot_id_relation` FOREIGN KEY (`slot_id`) REFERENCES `slot_table` (`_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `mapping_table_student_id_relation` FOREIGN KEY (`stu_id`) REFERENCES `students_details` (`_id`) ON UPDATE CASCADE;

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
