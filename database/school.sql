-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2017 at 08:14 AM
-- Server version: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `C_ID` int(11) NOT NULL,
  `C_Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`C_ID`, `C_Name`) VALUES
(1, 'A-1'),
(7, 'A-2'),
(8, 'A-3'),
(9, 'A-4');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `S_ID` int(11) NOT NULL,
  `S_Name` varchar(255) NOT NULL,
  `S_Level` int(11) NOT NULL,
  `C_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`S_ID`, `S_Name`, `S_Level`, `C_id`) VALUES
(1, 'Peter Singer', 2, 7),
(15, 'Mark Sheehan', 3, 8),
(16, 'Walter Sinnott', 2, 7),
(17, 'Toby Ord ', 4, 9),
(18, 'Thomas Douglas', 1, 1),
(19, 'Shelly Kagan', 2, 7),
(20, 'Roger Crisp ', 1, 1),
(21, 'Rebecca Brown', 3, 8),
(22, 'Philip Pettit', 3, 8),
(23, 'Paul van Lange ', 4, 9),
(24, 'Nicholas Shackel', 4, 9),
(25, 'Michael Robillard ', 1, 1),
(26, 'Luciano Floridi', 1, 1),
(27, 'Katrien Devolder', 2, 7),
(28, 'Joshua Shepherd ', 4, 9),
(29, 'Jonathan Glover', 4, 9),
(30, 'John Broome', 3, 8),
(31, 'Jeff McMahan ', 2, 7),
(32, 'Janet Radcliffe', 3, 8),
(33, 'Ingmar Persson ', 4, 9),
(34, 'Ichinose Masaki', 4, 9),
(35, 'Hannah Maslen ', 1, 1),
(36, 'Frances Kamm ', 1, 1),
(37, ' 	 Dominic Wilkinson ', 3, 8),
(38, 'Deborah Sheehan', 3, 8),
(39, ' 	 Clare Heyward ', 2, 7),
(40, 'Christine Korsgaard ', 1, 1),
(41, 'Brian Earp ', 2, 7),
(42, 'Bart Gremmen', 3, 8),
(43, 'Andreas Kappes ', 4, 9),
(44, 'Allen Buchanan', 1, 1),
(45, 'Valentin Muresan', 1, 1),
(46, 'Tony Coady', 2, 7),
(47, 'Tim Scanlon', 2, 7),
(48, 'Stephen Rainey', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `T_ID` int(11) NOT NULL,
  `T_Name` varchar(255) NOT NULL,
  `T_Salary` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`T_ID`, `T_Name`, `T_Salary`) VALUES
(1, 'Allen Buchanan', 2500),
(4, 'John Broome', 3000),
(5, 'Christine Korsgaard', 3200),
(6, 'Brian Earp', 2900),
(7, 'Tony Hope', 4000),
(8, 'Rachel Gaminiratne', 3624),
(9, 'Ilina Singh', 3952);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_class`
--

CREATE TABLE `teacher_class` (
  `TC_ID` int(11) NOT NULL,
  `T_id` int(11) NOT NULL,
  `C_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teacher_class`
--

INSERT INTO `teacher_class` (`TC_ID`, `T_id`, `C_id`) VALUES
(10, 1, 8),
(11, 4, 9),
(14, 7, 1),
(15, 9, 1),
(17, 9, 9),
(18, 8, 7),
(20, 7, 9),
(21, 7, 7),
(22, 4, 8),
(23, 5, 7),
(24, 5, 1),
(25, 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `User_ID` int(11) NOT NULL,
  `User_Name` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Full_Name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`User_ID`, `User_Name`, `Password`, `Email`, `Full_Name`) VALUES
(1, 'mm', '976d3005cf91cbd39ea2fcaf599abecf48a3a256', 'as@def.sfg', 'x w'),
(2, 'admin1', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'admin1@yahoo.com', 'Mahmoud Fekry');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`C_ID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`S_ID`),
  ADD KEY `C_id` (`C_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`T_ID`);

--
-- Indexes for table `teacher_class`
--
ALTER TABLE `teacher_class`
  ADD PRIMARY KEY (`TC_ID`),
  ADD KEY `C_id` (`C_id`),
  ADD KEY `T_id` (`T_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `User_Name` (`User_Name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `C_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `S_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `T_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `teacher_class`
--
ALTER TABLE `teacher_class`
  MODIFY `TC_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`C_id`) REFERENCES `classes` (`C_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teacher_class`
--
ALTER TABLE `teacher_class`
  ADD CONSTRAINT `teacher_class_ibfk_1` FOREIGN KEY (`C_id`) REFERENCES `classes` (`C_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teacher_class_ibfk_2` FOREIGN KEY (`T_id`) REFERENCES `teachers` (`T_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
