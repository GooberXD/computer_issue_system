-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2026 at 10:47 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `computer_issue_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_ID` int(11) NOT NULL,
  `admin_isActive` tinyint(1) DEFAULT 1,
  `admin_Position` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_ID`, `admin_isActive`, `admin_Position`) VALUES
(5, 1, 'Lab Tech'),
(6, 1, 'Lab Tech');

-- --------------------------------------------------------

--
-- Table structure for table `computer`
--

CREATE TABLE `computer` (
  `computer_ID` int(11) NOT NULL,
  `computer_Number` varchar(20) DEFAULT NULL,
  `computer_RoomNumber` varchar(20) DEFAULT NULL,
  `computer_Specs` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `issue_report`
--

CREATE TABLE `issue_report` (
  `issueRpt_ID` int(11) NOT NULL,
  `issueRpt_computerRoom` varchar(20) DEFAULT NULL,
  `issueRpt_computerID` varchar(50) DEFAULT NULL,
  `issueRpt_problemDescription` text DEFAULT NULL,
  `issueRpt_Date` date DEFAULT NULL,
  `issueRpt_status` enum('Unresolved','Pending','Resolved') DEFAULT 'Pending',
  `reporter_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issue_report`
--

INSERT INTO `issue_report` (`issueRpt_ID`, `issueRpt_computerRoom`, `issueRpt_computerID`, `issueRpt_problemDescription`, `issueRpt_Date`, `issueRpt_status`, `reporter_ID`) VALUES
(3, 'NGE102', '34', '121', '2026-05-01', 'Pending', 1),
(4, 'NGE101', 'PC-35', 'GPU broken', '2026-05-14', 'Pending', 2),
(5, 'NGE102', 'PC-06', 'Keyboard Broken', '2026-05-14', 'Resolved', 2),
(6, 'NGE102', 'Teacher Desk PC', 'No monitor\r\n', '2026-05-14', 'Resolved', 7),
(7, 'NGE102', 'PC-27', 'Keyboard non-functional, needs replacement\r\n', '2026-05-14', 'Unresolved', 8),
(8, 'NGE101', 'PC-18', 'Broken Mouse', '2026-05-14', 'Unresolved', 8);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_Number` varchar(20) NOT NULL,
  `room_NumberOfComputers` int(11) DEFAULT NULL,
  `room_InUse` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_Number`, `room_NumberOfComputers`, `room_InUse`) VALUES
('NGE101', NULL, 1),
('NGE102', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_ID` int(11) NOT NULL,
  `student_YearLevel` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_ID`, `student_YearLevel`) VALUES
(1, 0),
(2, 2),
(3, 2),
(4, 2);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `teacher_ID` int(11) NOT NULL,
  `teacher_Department` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacher_ID`, `teacher_Department`) VALUES
(7, 'CCS'),
(8, 'ccs');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_ID` int(11) NOT NULL,
  `user_FullName` varchar(100) NOT NULL,
  `user_Course` varchar(100) DEFAULT NULL,
  `isAdmin` tinyint(1) DEFAULT 0,
  `isStudent` tinyint(1) DEFAULT 0,
  `isTeacher` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_ID`, `user_FullName`, `user_Course`, `isAdmin`, `isStudent`, `isTeacher`) VALUES
(1, 'hi', 'hi', 0, 1, 0),
(2, 'Student1', 'BSIT-F1', 0, 1, 0),
(3, 'Student2', 'BSIT - F2', 0, 1, 0),
(4, 'Student3', 'BSIT - F3', 0, 1, 0),
(5, 'Admin1', 'Admin', 1, 0, 0),
(6, 'Admin2', 'Admin', 1, 0, 0),
(7, 'Teacher1', 'Teacher', 0, 0, 1),
(8, 'Teacher2', 'Teacher', 0, 0, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_ID`);

--
-- Indexes for table `computer`
--
ALTER TABLE `computer`
  ADD PRIMARY KEY (`computer_ID`),
  ADD KEY `computer_RoomNumber` (`computer_RoomNumber`);

--
-- Indexes for table `issue_report`
--
ALTER TABLE `issue_report`
  ADD PRIMARY KEY (`issueRpt_ID`),
  ADD KEY `issueRpt_computerRoom` (`issueRpt_computerRoom`),
  ADD KEY `issueRpt_computerID` (`issueRpt_computerID`),
  ADD KEY `reporter_ID` (`reporter_ID`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_Number`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_ID`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`teacher_ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `computer`
--
ALTER TABLE `computer`
  MODIFY `computer_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `issue_report`
--
ALTER TABLE `issue_report`
  MODIFY `issueRpt_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`admin_ID`) REFERENCES `user` (`user_ID`) ON DELETE CASCADE;

--
-- Constraints for table `computer`
--
ALTER TABLE `computer`
  ADD CONSTRAINT `computer_ibfk_1` FOREIGN KEY (`computer_RoomNumber`) REFERENCES `room` (`room_Number`) ON DELETE SET NULL;

--
-- Constraints for table `issue_report`
--
ALTER TABLE `issue_report`
  ADD CONSTRAINT `issue_report_ibfk_1` FOREIGN KEY (`issueRpt_computerRoom`) REFERENCES `room` (`room_Number`),
  ADD CONSTRAINT `issue_report_ibfk_3` FOREIGN KEY (`reporter_ID`) REFERENCES `user` (`user_ID`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`student_ID`) REFERENCES `user` (`user_ID`) ON DELETE CASCADE;

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`teacher_ID`) REFERENCES `user` (`user_ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
