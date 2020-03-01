-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2020 at 08:57 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vote`
--

-- --------------------------------------------------------

--
-- Table structure for table `governors`
--

CREATE TABLE `governors` (
  `id` int(11) NOT NULL,
  `party_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `governors`
--

INSERT INTO `governors` (`id`, `party_id`, `name`) VALUES
(1, 1, 'goodluck jonathan'),
(2, 2, 'okonjo iweala'),
(3, 4, 'atiku abubakar'),
(4, 7, 'chimamanda adichie'),
(5, 9, 'gbenga daniels');

-- --------------------------------------------------------

--
-- Table structure for table `house_of_representatives`
--

CREATE TABLE `house_of_representatives` (
  `id` int(11) NOT NULL,
  `party_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `house_of_representatives`
--

INSERT INTO `house_of_representatives` (`id`, `party_id`, `name`) VALUES
(1, 1, 'jim iyke'),
(2, 2, 'seun akindele'),
(3, 7, 'mike ezuruonye'),
(4, 9, 'daniel lloyd');

-- --------------------------------------------------------

--
-- Table structure for table `parties`
--

CREATE TABLE `parties` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `parties`
--

INSERT INTO `parties` (`id`, `name`) VALUES
(1, 'PDP'),
(2, 'APC'),
(3, 'DRP'),
(4, 'ANPP'),
(5, 'PPP'),
(6, 'RPC'),
(7, 'VAT'),
(8, 'LRP'),
(9, 'WMA'),
(10, 'RAR');

-- --------------------------------------------------------

--
-- Table structure for table `presidents`
--

CREATE TABLE `presidents` (
  `id` int(11) NOT NULL,
  `party_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `presidents`
--

INSERT INTO `presidents` (`id`, `party_id`, `name`) VALUES
(1, 2, 'muhammadu buhari'),
(2, 1, 'orji uzor kalu'),
(3, 10, 'chinua achebe'),
(4, 5, 'desmond elliott'),
(5, 7, 'toni tones'),
(6, 6, 'ruth kadiri');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'User'),
(2, 'Administrator');

-- --------------------------------------------------------

--
-- Table structure for table `senators`
--

CREATE TABLE `senators` (
  `id` int(11) NOT NULL,
  `party_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `senators`
--

INSERT INTO `senators` (`id`, `party_id`, `name`) VALUES
(1, 2, 'robert downey'),
(2, 4, 'chris evans'),
(3, 6, 'scarlett johansson'),
(4, 9, 'zoe saldana'),
(5, 3, 'gal gadot'),
(6, 8, 'chadwick boseman');

-- --------------------------------------------------------

--
-- Table structure for table `state_assemblies`
--

CREATE TABLE `state_assemblies` (
  `id` int(11) NOT NULL,
  `party_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `state_assemblies`
--

INSERT INTO `state_assemblies` (`id`, `party_id`, `name`) VALUES
(1, 1, 'rachel mcadams'),
(2, 2, 'amanda seyfried'),
(3, 4, 'channing tatum'),
(4, 3, 'james marsden'),
(5, 6, 'tessa thompson'),
(6, 5, 'frederick leonard'),
(7, 10, 'ramsey noah'),
(8, 8, 'ini edo'),
(9, 9, 'nonso diobi'),
(10, 7, 'susan patrick');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `sex` varchar(255) NOT NULL,
  `voted` tinyint(1) NOT NULL DEFAULT 0,
  `profile_image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) NOT NULL DEFAULT 1,
  `date_registered` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `president_vote` int(11) DEFAULT NULL,
  `senate_vote` int(11) DEFAULT NULL,
  `house_of_reps_vote` int(11) DEFAULT NULL,
  `governor_vote` int(11) DEFAULT NULL,
  `state_assembly_vote` int(11) DEFAULT NULL,
  `date_voted` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `governors`
--
ALTER TABLE `governors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `party_id` (`party_id`);

--
-- Indexes for table `house_of_representatives`
--
ALTER TABLE `house_of_representatives`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `party_id` (`party_id`);

--
-- Indexes for table `parties`
--
ALTER TABLE `parties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `presidents`
--
ALTER TABLE `presidents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `party_id` (`party_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `senators`
--
ALTER TABLE `senators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `party_id` (`party_id`);

--
-- Indexes for table `state_assemblies`
--
ALTER TABLE `state_assemblies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `party_id` (`party_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `profile_image` (`profile_image`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `governors`
--
ALTER TABLE `governors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `house_of_representatives`
--
ALTER TABLE `house_of_representatives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `parties`
--
ALTER TABLE `parties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `presidents`
--
ALTER TABLE `presidents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `senators`
--
ALTER TABLE `senators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `state_assemblies`
--
ALTER TABLE `state_assemblies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
