-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2018 at 11:32 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: u414557330_jason
--

-- --------------------------------------------------------

--
-- Table structure for table Burn
--

CREATE TABLE Burn (
  Id int(11) NOT NULL,
  Burnee varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  BurnType varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  Description mediumtext COLLATE utf8_unicode_ci NOT NULL,
  CreatedDate datetime NOT NULL,
  CreatedBy varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table CurrentState
--

CREATE TABLE CurrentState (
  CurrentName varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  ClockStart24h datetime NOT NULL,
  ClockStart72h datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table NotificationRecipient
--

CREATE TABLE NotificationRecipient (
  Email varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table Burn
--
ALTER TABLE Burn
  ADD PRIMARY KEY (Id),
  ADD KEY IX_Burn_Burnee (Burnee),
  ADD KEY IX_Burn_CreatedDate (CreatedDate);

--
-- Indexes for table NotificationRecipient
--
ALTER TABLE NotificationRecipient
  ADD PRIMARY KEY (Email);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table Burn
--
ALTER TABLE Burn
  MODIFY Id int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
