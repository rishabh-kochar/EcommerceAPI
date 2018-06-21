-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 28, 2018 at 10:11 AM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `onlinestore`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `AdminId` int(11) NOT NULL,
  `AdminName` varchar(50) NOT NULL,
  `AdminImage` text,
  `PhoneNo` varchar(10) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Password` varchar(15) NOT NULL,
  `OldPassword` varchar(15) DEFAULT NULL,
  `VerificationCode` text,
  `RandomString` varchar(10) DEFAULT NULL,
  `RandomStringTime` datetime DEFAULT NULL,
  `PasswordUpdatedOn` datetime DEFAULT NULL,
  `CreatedOn` datetime NOT NULL,
  `IsSessionActive` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`AdminId`, `AdminName`, `AdminImage`, `PhoneNo`, `Email`, `Password`, `OldPassword`, `VerificationCode`, `RandomString`, `RandomStringTime`, `PasswordUpdatedOn`, `CreatedOn`, `IsSessionActive`) VALUES
(1, 'Rishabh ', '20180522163728_photo2.png', '9016111959', 'rishabhkochar58@gmail.com', '1234', '12', '7FiTnAEj', NULL, NULL, '2018-05-22 16:39:38', '2018-05-09 00:00:00', 1),
(4, 'Bhumi Patel', '20180523150948_2013-12-14_zh-cn11425960734_e58aa0e588a9e7a68fe5b0bce4ba9ae5b79ee79a84e6aca7e69687e696afe8b0b7e4b8adefbc8ce799bde69da8efbc8ce69fb3e6a091e.jpg', '1234567890', 'bhumi@gmail.com', '123', NULL, NULL, NULL, NULL, NULL, '2018-05-16 00:00:00', 1),
(5, 'Ritesh Tailor', '20180522151336_avatar04.png', '8866699878', 'rdtailor@gmail.com', '123', '12', 'PjlUZ2ua', 'AeCuYftGn9', '2018-05-17 13:43:00', '2018-05-22 17:22:53', '2018-05-16 00:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `CategoryId` int(11) NOT NULL,
  `CategoryName` varchar(100) NOT NULL,
  `CategoryDesc` text,
  `CategoryImage` text NOT NULL,
  `CategoryImageAlt` varchar(50) NOT NULL,
  `ShopID` int(11) NOT NULL,
  `IsActive` tinyint(1) NOT NULL,
  `IsApproved` tinyint(1) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `LastUpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblcategoryproperties`
--

CREATE TABLE `tblcategoryproperties` (
  `CategoryPropertyID` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `PropertyName` varchar(100) NOT NULL,
  `PropertyDesc` text,
  `IsFilter` tinyint(1) NOT NULL,
  `ColumnOrder` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct`
--

CREATE TABLE `tblproduct` (
  `ProductId` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `ProductName` text NOT NULL,
  `ProductDesc` text NOT NULL,
  `Price` float NOT NULL,
  `ProductImage1` text NOT NULL,
  `ProductImage2` text NOT NULL,
  `ProductImage3` text NOT NULL,
  `ProductImage4` text NOT NULL,
  `Unit` varchar(100) NOT NULL,
  `MinStock` int(11) NOT NULL,
  `CurrentStock` int(11) DEFAULT NULL,
  `LastStockUpdatedOn` datetime DEFAULT NULL,
  `IsActive` tinyint(1) NOT NULL,
  `IsApproved` tinyint(1) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `LastUpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tblshops`
--

CREATE TABLE `tblshops` (
  `ShopID` int(11) NOT NULL,
  `ShopName` varchar(100) NOT NULL,
  `Tagline` varchar(100) DEFAULT NULL,
  `LogoImage` varchar(50) DEFAULT NULL,
  `Address` text,
  `City` varchar(50) DEFAULT NULL,
  `State` varchar(50) DEFAULT NULL,
  `PhoneNo` varchar(10) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Website` varchar(50) DEFAULT NULL,
  `OwnerName` varchar(50) NOT NULL,
  `FacebookLink` text,
  `InstagramLink` text,
  `TwitterLink` text,
  `YoutubeLink` text,
  `LogoAlt` varchar(50) DEFAULT NULL,
  `GSTNo` varchar(15) DEFAULT NULL,
  `UserName` varchar(50) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `OldPassword` varchar(20) DEFAULT NULL,
  `PasswordUpdatedOn` datetime DEFAULT NULL,
  `RandomString` varchar(10) DEFAULT NULL,
  `VerificationCode` varchar(10) DEFAULT NULL,
  `RandomStringTime` datetime DEFAULT NULL,
  `IsActive` tinyint(1) NOT NULL,
  `IsApproved` tinyint(1) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `ShopType` varchar(50) NOT NULL,
  `ApprovedOn` datetime DEFAULT NULL,
  `IsSessionActive` tinyint(1) NOT NULL,
  `IsInitialSetup` tinyint(1) NOT NULL,
  `IsMember` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblshops`
--

INSERT INTO `tblshops` (`ShopID`, `ShopName`, `Tagline`, `LogoImage`, `Address`, `City`, `State`, `PhoneNo`, `Email`, `Website`, `OwnerName`, `FacebookLink`, `InstagramLink`, `TwitterLink`, `YoutubeLink`, `LogoAlt`, `GSTNo`, `UserName`, `Password`, `OldPassword`, `PasswordUpdatedOn`, `RandomString`, `VerificationCode`, `RandomStringTime`, `IsActive`, `IsApproved`, `CreatedOn`, `ShopType`, `ApprovedOn`, `IsSessionActive`, `IsInitialSetup`, `IsMember`) VALUES
(2, 'CCD', 'A lot can happen over coffee', NULL, 'Aditya Complex, Prime Arcade, Anand Mahal Road, Adajan Patiya,', 'Surat', 'Gujarat', '123', 'riteshdlab@gmail.com', 'www.ccd.com', 'XYZ', 'www.facebook.com', 'www.instagram.com', 'www.twitter.com', 'www.youtube.com', NULL, '123456789125425', 'xy', '147', '123', '2018-05-24 14:13:05', '', NULL, '2018-05-24 13:48:35', 1, 1, '2018-05-22 00:00:00', 'Cafe', NULL, 1, 1, 1),
(4, 'Delete Wala', 'dm fk', NULL, NULL, NULL, NULL, '7821321321', 'dkfvndkjb', NULL, 'kjnkjn', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, '2018-05-23 00:00:00', ' nsv', NULL, 0, 0, 1),
(5, 'Raju Chacha Vadapav', NULL, NULL, NULL, NULL, NULL, '1478523690', 'riteshtailor20@outlook.com', NULL, 'Rajesh Gangani', NULL, NULL, NULL, NULL, NULL, NULL, 'Kd7LP5JB', 'oGTVY1AU', NULL, NULL, NULL, NULL, NULL, 0, 1, '2018-05-24 14:31:38', 'Fast Food', NULL, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblwebsite`
--

CREATE TABLE `tblwebsite` (
  `id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Logo` varchar(50) NOT NULL,
  `LogoAlt` varchar(20) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `PhoneNo` varchar(10) NOT NULL,
  `Password` varchar(50) DEFAULT NULL,
  `AboutUs` text NOT NULL,
  `ContactUs` text NOT NULL,
  `FacebookLink` text,
  `TwitterLink` text,
  `InstagramLink` text,
  `YoutubeLink` text,
  `GSTNo` varchar(15) DEFAULT NULL,
  `CreatedOn` datetime NOT NULL,
  `LastUpdatedOn` datetime DEFAULT NULL,
  `TagLine` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblwebsite`
--

INSERT INTO `tblwebsite` (`id`, `Name`, `Logo`, `LogoAlt`, `Email`, `PhoneNo`, `Password`, `AboutUs`, `ContactUs`, `FacebookLink`, `TwitterLink`, `InstagramLink`, `YoutubeLink`, `GSTNo`, `CreatedOn`, `LastUpdatedOn`, `TagLine`) VALUES
(1, 'Online Store', '20180524110253_apple-icon.png', 'Klorofill', 'rtemp2520@gmail.com', '8866699878', 'rtemp2520$', 'About us', 'Contact us', '', '', '', '', '879456213465978', '2018-05-22 11:50:52', '2018-05-24 15:47:15', 'Tag Line');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`AdminId`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `PhoneNo` (`PhoneNo`),
  ADD UNIQUE KEY `RandomString` (`RandomString`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`CategoryId`);

--
-- Indexes for table `tblcategoryproperties`
--
ALTER TABLE `tblcategoryproperties`
  ADD PRIMARY KEY (`CategoryPropertyID`);

--
-- Indexes for table `tblproduct`
--
ALTER TABLE `tblproduct`
  ADD PRIMARY KEY (`ProductId`);

--
-- Indexes for table `tblshops`
--
ALTER TABLE `tblshops`
  ADD PRIMARY KEY (`ShopID`),
  ADD UNIQUE KEY `PhoneNo` (`PhoneNo`,`Email`,`Website`,`UserName`);

--
-- Indexes for table `tblwebsite`
--
ALTER TABLE `tblwebsite`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`Email`,`PhoneNo`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `AdminId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `CategoryId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblcategoryproperties`
--
ALTER TABLE `tblcategoryproperties`
  MODIFY `CategoryPropertyID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblproduct`
--
ALTER TABLE `tblproduct`
  MODIFY `ProductId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tblshops`
--
ALTER TABLE `tblshops`
  MODIFY `ShopID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblwebsite`
--
ALTER TABLE `tblwebsite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
