-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: 209.99.16.19:3306
-- Generation Time: Jun 21, 2018 at 10:56 AM
-- Server version: 5.6.39
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `OnlineStore`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladdress`
--

CREATE TABLE `tbladdress` (
  `AddressID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `PhoneNo` varchar(10) NOT NULL,
  `Pincode` varchar(10) NOT NULL,
  `Locality` varchar(100) NOT NULL,
  `Address` text NOT NULL,
  `City` varchar(100) NOT NULL,
  `State` varchar(100) NOT NULL,
  `Landmark` varchar(100) DEFAULT NULL,
  `Country` varchar(100) NOT NULL,
  `AddressType` varchar(50) NOT NULL,
  `IsActive` tinyint(1) NOT NULL,
  `CreatedOn` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbladdress`
--

INSERT INTO `tbladdress` (`AddressID`, `UserID`, `Name`, `PhoneNo`, `Pincode`, `Locality`, `Address`, `City`, `State`, `Landmark`, `Country`, `AddressType`, `IsActive`, `CreatedOn`) VALUES
(1, 1, 'Ritesh', '8866699878', '394510', 'Pal', 'D203 Legend Harmony Residency', 'Surat', 'Gujarat', 'Pal', 'India', 'Home', 1, '2018-06-20 11:57:36');

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
(1, 'Rishabh Kochar', '20180522163728_photo2.png', '9016111959', 'rishabhkochar58@gmail.com', '1234', '12', '7FiTnAEj', NULL, NULL, '2018-05-22 16:39:38', '2018-05-09 00:00:00', 1),
(4, 'Bhumi Patel', '20180523150948_2013-12-14_zh-cn11425960734_e58aa0e588a9e7a68fe5b0bce4ba9ae5b79ee79a84e6aca7e69687e696afe8b0b7e4b8adefbc8ce799bde69da8efbc8ce69fb3e6a091e.jpg', '1234567890', 'bhumi@gmail.com', 'abc', '123', NULL, NULL, NULL, '2018-05-30 16:49:39', '2018-05-16 00:00:00', 1),
(5, 'Ritesh Tailor', '20180621120947_user6-128x128.jpg', '8866699878', 'rdtailor@gmail.com', '159', '123', 'PjlUZ2ua', 'AeCuYftGn9', '2018-05-17 13:43:00', '2018-06-19 13:17:56', '2018-05-16 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblcart`
--

CREATE TABLE `tblcart` (
  `CartID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Qty` int(11) NOT NULL,
  `AddedOn` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`CategoryId`, `CategoryName`, `CategoryDesc`, `CategoryImage`, `CategoryImageAlt`, `ShopID`, `IsActive`, `IsApproved`, `CreatedOn`, `LastUpdatedOn`) VALUES
(3, 'Mobiles', 'All Mobiles ncjs hjvs gvj ierngiorngion moesm All Mobiles ncjs hjvs gvj ierngiorngion moesm All Mobiles ncjs hjvs gvj ierngiorngion moesm All Mobiles ncjs hjvs gvj ierngiorngion moesm All Mobiles ncjs hjvs gvj ierngiorngion moesm All Mobiles ncjs hjvs gvj ierngiorngion moesm', '20180604153329_images.png', 'Mobile', 1, 1, 1, '2018-06-04 15:33:29', '2018-06-08 15:34:09'),
(4, 'Plastic Bottle', 'Used for drinking Water', '20180604153425_625146-plastic-bottles.jpg', 'Plastic Bottle', 2, 1, 1, '2018-06-04 15:34:25', NULL),
(5, 'Plastic Toys', 'For Childrens', '20180604154028_index.jpg', 'Toys', 2, 1, 1, '2018-06-04 15:40:28', NULL),
(9, 'Air Conditioners', 'AC Desc', '20180620114705_Hitachi-1-5-Ton-5-SDL800982567-1-a0a03.jpg', 'AC Image', 4, 1, 1, '2018-06-20 11:47:05', NULL);

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

--
-- Dumping data for table `tblcategoryproperties`
--

INSERT INTO `tblcategoryproperties` (`CategoryPropertyID`, `CategoryID`, `PropertyName`, `PropertyDesc`, `IsFilter`, `ColumnOrder`) VALUES
(1, 3, 'Size', 'Size of Phone', 1, 1),
(2, 3, 'Network', 'Network Supported by Mobile', 0, 2),
(4, 4, 'Capacity', 'Capacity', 1, 1),
(5, 4, 'Color', 'Color', 1, 2),
(6, 4, 'Highlights', 'Highlights', 0, 3),
(7, 5, 'HighLights', 'HighLights', 0, 1),
(8, 3, 'RAM', 'RAM of Mobile', 1, 4),
(10, 3, 'Storage', 'Storage of mobile', 1, 6),
(11, 3, 'Camera Mega Pixel', 'Camera features', 0, 7),
(12, 3, 'OS', 'OS of mobile', 0, 5),
(13, 9, 'Energy Saving Star', 'Number of energy saving stars', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblcategorypropertiesvalues`
--

CREATE TABLE `tblcategorypropertiesvalues` (
  `ProductID` int(11) NOT NULL,
  `CategoryPropertyID` int(11) NOT NULL,
  `Value` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblcategorypropertiesvalues`
--

INSERT INTO `tblcategorypropertiesvalues` (`ProductID`, `CategoryPropertyID`, `Value`) VALUES
(1, 1, '5.5'),
(1, 2, '4g LTE'),
(1, 3, '15000'),
(1, 8, '4'),
(1, 10, '32 gb'),
(1, 11, '16 mp'),
(1, 12, 'Android 6.0'),
(2, 4, '450 ml'),
(2, 5, 'Green'),
(2, 6, 'Color: Green Plastic Type: Bottle Capacity: 450 ml'),
(3, 7, 'Type: Track Sets & Play Sets Material: Plastic Non-battery Operated Non-rechargeable Batteries Minimum Age: 4 years'),
(4, 4, '200'),
(4, 5, 'Green'),
(4, 6, 'Highlight'),
(6, 7, 'hjkbkj'),
(7, 7, 'krjgn'),
(8, 1, '6 nch'),
(8, 2, '4g LTE'),
(8, 3, '32000'),
(8, 8, '6 gb'),
(8, 10, '64 gb'),
(8, 11, '21 mp'),
(8, 12, 'Android '),
(9, 1, '6.2'),
(9, 2, '4g LTE'),
(9, 3, '52000'),
(9, 8, '6 gb'),
(9, 10, '128 gb'),
(9, 11, '18 mp'),
(9, 12, 'Android 7.0'),
(10, 13, '4');

-- --------------------------------------------------------

--
-- Table structure for table `tbldiscount`
--

CREATE TABLE `tbldiscount` (
  `ProdID` int(11) NOT NULL,
  `Flat` double DEFAULT NULL,
  `Percentage` float DEFAULT NULL,
  `IsActive` tinyint(1) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbldiscount`
--

INSERT INTO `tbldiscount` (`ProdID`, `Flat`, `Percentage`, `IsActive`, `CreatedOn`, `UpdatedOn`) VALUES
(1, 500, NULL, 1, '2018-06-08 15:44:59', NULL),
(2, NULL, 10, 1, '2018-06-07 16:37:33', NULL),
(8, NULL, 30, 1, '2018-06-15 10:53:35', NULL),
(9, 2000, NULL, 1, '2018-06-15 10:53:04', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblfeedback`
--

CREATE TABLE `tblfeedback` (
  `FeedbackID` int(11) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Type` varchar(10) NOT NULL,
  `Response` text,
  `Feedback` text NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `RepliedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblfeedback`
--

INSERT INTO `tblfeedback` (`FeedbackID`, `Name`, `Email`, `Type`, `Response`, `Feedback`, `CreatedOn`, `RepliedOn`) VALUES
(1, 'CCD', 'riteshdlab@gmail.com', 'seller', 'abc', 'hello world', '2018-05-30 15:29:46', '2018-05-31 11:43:50'),
(16, 'CCD', 'riteshdlab@gmail.com', 'seller', 'hello', 'Online shopping is a form of electronic commerce which allows consumers to directly buy goods or services from a seller over the Internet using a web browser. Consumers find a product of interest by visiting the website of the retailer directly or by searching among alternative vendors using a shopping search engine, which displays the same product\'s availability and pricing at different e-retailers. As of 2016, customers can shop online using a range of different computers and devices, including desktop computers, laptops, tablet computers and smartphones.', '2018-05-31 12:12:36', '2018-05-31 12:13:42'),
(17, 'CCD', 'riteshdlab@gmail.com', 'seller', 'test3', 'mmmmm', '2018-05-31 12:12:50', '2018-05-31 12:17:09'),
(18, 'CCD', 'riteshdlab@gmail.com', 'seller', 'test4', 'hello', '2018-05-31 12:20:07', '2018-05-31 12:20:27'),
(19, 'CCD', 'riteshdlab@gmail.com', 'seller', 'lol', 'testing', '2018-05-31 12:22:17', '2018-05-31 12:22:45'),
(20, 'CCD', 'riteshdlab@gmail.com', 'seller', 'hola', 'hola', '2018-05-31 12:23:59', '2018-05-31 12:24:29'),
(21, 'CCD', 'riteshdlab@gmail.com', 'seller', 'dsvsd', 'hola', '2018-05-31 12:25:13', '2018-05-31 12:25:50'),
(22, 'CCD', 'riteshdlab@gmail.com', 'seller', 'fdsvd', 'hola', '2018-05-31 12:26:21', '2018-05-31 12:26:41'),
(23, 'CCD', 'riteshdlab@gmail.com', 'seller', 'df', 'sdvsd', '2018-05-31 12:27:42', '2018-05-31 12:28:02'),
(24, 'CCD', 'riteshdlab@gmail.com', 'seller', 'hello', 'hello', '2018-05-31 13:01:39', '2018-05-31 13:02:10'),
(25, 'CCD', 'riteshdlab@gmail.com', 'seller', 'df', 'ssd', '2018-05-31 13:03:46', '2018-05-31 13:04:14'),
(26, 'CCD', 'riteshdlab@gmail.com', 'seller', 'fsv', 'sdv', '2018-05-31 13:05:13', '2018-05-31 13:05:31'),
(27, 'CCD', 'riteshdlab@gmail.com', 'seller', NULL, 'jjjj', '2018-05-31 13:06:49', NULL),
(28, 'CCD', 'riteshdlab@gmail.com', 'seller', NULL, 'hhh', '2018-05-31 13:29:29', NULL),
(29, 'CCD', 'riteshdlab@gmail.com', 'seller', NULL, 'mmmm', '2018-05-31 13:30:29', NULL),
(30, 'CCD', 'riteshdlab@gmail.com', 'seller', 'mkmk,', 'hello', '2018-05-31 13:31:52', '2018-06-01 12:52:49'),
(31, 'CCD', 'riteshdlab@gmail.com', 'seller', NULL, 'vvvv', '2018-05-31 13:33:19', NULL),
(32, 'CCD', 'riteshdlab@gmail.com', 'seller', NULL, 'bbnmbm', '2018-05-31 13:35:05', NULL),
(33, 'CCD', 'riteshdlab@gmail.com', 'seller', NULL, 'hghh', '2018-05-31 13:37:57', NULL),
(34, 'CCD', 'riteshdlab@gmail.com', 'seller', NULL, 'jijiji', '2018-05-31 13:38:17', NULL),
(35, 'Arihant Plastic', 'rishabhkochar58@gmail.com', 'seller', 'Yes Yes', 'Hello There....\nCan You Add a CAtegory.', '2018-06-06 14:49:06', '2018-06-19 13:17:20'),
(36, 'bhumi', 'bhumipatel1196@gmail.com', 'seller', NULL, 'hello', '2018-06-12 11:13:32', NULL),
(37, 'Om', 'om1@gmail.com', 'user', NULL, 'hello', '2018-06-12 11:15:16', NULL),
(38, 'Ritesh', 'riteshdlab@gmail.com', 'user', 'I Don\'t care....', 'I am having some issues placing order.', '2018-06-20 12:00:50', '2018-06-20 12:03:21'),
(39, 'Ritesh', 'riteshdlab@gmail.com', 'user', 'Rishah Kochar<blockquote> <b> Your Query: </b>Issues placing order on your website.</blockquote> ', 'Issues placing order on your website.', '2018-06-20 12:32:34', '2018-06-20 12:33:36'),
(40, 'Get Mobiles', 'riteshdlab@gmail.com', 'seller', 'Got it<blockquote> <b> Your Query: </b>Hello host email test</blockquote> ', 'Hello host email test', '2018-06-21 12:03:05', '2018-06-21 12:04:01');

-- --------------------------------------------------------

--
-- Table structure for table `tblnotification`
--

CREATE TABLE `tblnotification` (
  `NotificationID` int(11) NOT NULL,
  `URL` varchar(100) NOT NULL,
  `Type` int(11) NOT NULL,
  `NotificationText` varchar(50) NOT NULL,
  `Image` varchar(100) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `IsRead` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblnotification`
--

INSERT INTO `tblnotification` (`NotificationID`, `URL`, `Type`, `NotificationText`, `Image`, `CreatedOn`, `IsRead`) VALUES
(1, '/sucategory', 0, 'Air Conditioners Category Added.', 'fa-list', '2018-06-20 11:47:05', 1),
(2, '/suproduct', 0, 'Blue Star Inverter AC Product Added.', 'fa-gift', '2018-06-20 11:50:22', 1),
(3, '/userdata', 0, 'Ritesh Tailor Joined.', 'fa-user', '2018-06-20 11:55:21', 1),
(4, '/orderDetail', 4, 'Order for Blue Star Inverter AC Arrived.', 'fa-file-alt', '2018-06-20 11:58:27', 0),
(5, '/feedback', 0, 'Ritesh Added a query.', 'fa-comments', '2018-06-20 12:00:50', 1),
(6, '/feedback', 0, 'Ritesh Added a query.', 'fa-comments', '2018-06-20 12:32:34', 1),
(7, '/feedback', 0, 'Get Mobiles Added a query.', 'fa-comments', '2018-06-21 12:03:05', 1),
(8, '/shops', 0, 'Mahavir Farsan Requested to Approve.', 'fa-store', '2018-06-21 12:19:22', 1),
(9, '/shopProfile', 5, 'Welcome to Flipkart.', 'fa-handshake', '2018-06-21 12:22:18', 0),
(10, '/orderDetail', 1, 'Order for Moto G5 Plus Arrived.', 'fa-file-alt', '2018-06-21 14:05:15', 0),
(11, '/tracking', 0, 'Order No 2 Confirmed.', 'fa-calendar-check', '2018-06-21 14:06:21', 1),
(12, '/orderDetail', 2, 'Order for HotWheels Arrived.', 'fa-file-alt', '2018-06-21 14:12:36', 1),
(13, '/tracking', 0, 'Order No 3 Confirmed.', 'fa-calendar-check', '2018-06-21 14:13:23', 1),
(14, '/orderDetail', 1, 'Order for Oneplus 6 Arrived.', 'fa-file-alt', '2018-06-21 14:14:31', 0),
(15, '/tracking', 0, 'Order No 4 Confirmed.', 'fa-calendar-check', '2018-06-21 14:14:59', 1),
(16, '/orderDetail', 1, 'Order for Google Pixel 2 Arrived.', 'fa-file-alt', '2018-06-21 14:16:58', 0),
(17, '/orderDetail', 2, 'Order for Mastercool AMemo Green Bottle Arrived.', 'fa-file-alt', '2018-06-21 14:18:49', 1),
(18, '/tracking', 0, 'Order No 5 Confirmed.', 'fa-calendar-check', '2018-06-21 14:22:22', 1),
(19, '/tracking', 0, 'Order No 6 Confirmed.', 'fa-calendar-check', '2018-06-21 14:27:46', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblorderdetails`
--

CREATE TABLE `tblorderdetails` (
  `OrderDetailID` int(11) NOT NULL,
  `OrderID` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Qty` int(11) NOT NULL,
  `Price` double NOT NULL,
  `Status` text NOT NULL,
  `IsActive` tinyint(1) NOT NULL,
  `OrderUpdatedOn` datetime NOT NULL,
  `ExpectedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblorderdetails`
--

INSERT INTO `tblorderdetails` (`OrderDetailID`, `OrderID`, `ProductID`, `Qty`, `Price`, `Status`, `IsActive`, `OrderUpdatedOn`, `ExpectedDate`) VALUES
(1, 1, 10, 2, 15000, 'Pending', 1, '2018-06-20 11:58:27', NULL),
(2, 2, 1, 2, 14500, 'Shipped', 1, '2018-06-21 14:07:00', NULL),
(3, 3, 3, 2, 1199, 'Shipped', 1, '2018-06-21 14:13:43', NULL),
(4, 4, 8, 1, 22400, 'Shipped', 1, '2018-06-21 14:15:22', NULL),
(5, 5, 9, 1, 50000, 'Shipped', 1, '2018-06-21 14:22:38', NULL),
(6, 6, 2, 1, 166.5, 'Shipped', 1, '2018-06-21 14:27:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblorders`
--

CREATE TABLE `tblorders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `AddressID` int(11) NOT NULL,
  `OrderedOn` datetime NOT NULL,
  `TotalAmount` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblorders`
--

INSERT INTO `tblorders` (`OrderID`, `UserID`, `AddressID`, `OrderedOn`, `TotalAmount`) VALUES
(1, 1, 1, '2018-06-20 11:58:27', 30000),
(2, 1, 1, '2018-06-21 14:05:15', 29000),
(3, 1, 1, '2018-06-21 14:12:36', 2398),
(4, 1, 1, '2018-06-21 14:14:31', 22400),
(5, 1, 1, '2018-06-21 14:16:58', 50000),
(6, 1, 1, '2018-06-21 14:18:49', 166.5);

-- --------------------------------------------------------

--
-- Table structure for table `tblproduct`
--

CREATE TABLE `tblproduct` (
  `ProductId` int(11) NOT NULL,
  `CategoryID` int(11) NOT NULL,
  `ShopID` int(11) NOT NULL,
  `ProductName` text NOT NULL,
  `ProductDesc` text NOT NULL,
  `ImageAlt` varchar(50) DEFAULT NULL,
  `Price` float NOT NULL,
  `Unit` varchar(100) NOT NULL,
  `MinStock` int(11) DEFAULT NULL,
  `CurrentStock` int(11) DEFAULT NULL,
  `LastStockUpdatedOn` datetime DEFAULT NULL,
  `IsActive` tinyint(1) NOT NULL,
  `IsApproved` tinyint(1) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `LastUpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblproduct`
--

INSERT INTO `tblproduct` (`ProductId`, `CategoryID`, `ShopID`, `ProductName`, `ProductDesc`, `ImageAlt`, `Price`, `Unit`, `MinStock`, `CurrentStock`, `LastStockUpdatedOn`, `IsActive`, `IsApproved`, `CreatedOn`, `LastUpdatedOn`) VALUES
(1, 3, 1, 'Moto G5 Plus', 'Planning on surprising your kid with a Hot Wheels Set? Now, here is one that does more than just sending cars racing. With a fancy waterfall and cars that change color, this set is designed to thrill and excite your child. The game is filled with challenges that your child is going to love.', 'Get Mobile', 15000, 'Piece', 5, 43, '2018-06-04 15:41:27', 1, 1, '2018-06-04 15:41:16', '2018-06-08 17:03:35'),
(2, 4, 2, 'Mastercool AMemo Green Bottle', 'Pani', 'MastercoolA5', 185, 'Piece', 10, 7, '2018-06-04 15:47:15', 1, 1, '2018-06-04 15:45:36', '2018-06-15 13:54:16'),
(3, 5, 2, 'HotWheels', 'Planning on surprising your kid with a Hot Wheels Set? Now, here is one that does more than just sending cars racing. With a fancy waterfall and cars that change color, this set is designed to thrill and excite your child. The game is filled with challenges that your child is going to love.', 'Hot Wheels', 1199, 'Piece', 5, 8, '2018-06-18 10:38:10', 1, 1, '2018-06-04 15:49:58', '2018-06-04 15:51:17'),
(4, 4, 2, 'XYZ', 'vkmdv k kf', 'jknjk', 200, 'Piece', 10, 10, '2018-06-11 12:18:25', 1, 0, '2018-06-11 12:04:35', '2018-06-11 12:18:09'),
(5, 4, 2, 'RTY', 'ajkanjk', 'jknjk', 10000, 'Piece', 20, 0, NULL, 1, 0, '2018-06-11 12:27:02', '2018-06-11 12:37:35'),
(6, 5, 2, 'Rahul', 'nvjkdn', '5616', 100, 'Piece', 10, 0, NULL, 1, 0, '2018-06-11 12:30:32', NULL),
(7, 5, 2, 'Rahul', 'nvjkdn', '5616', 100, 'Piece', 10, 10, '2018-06-11 15:43:01', 1, 0, '2018-06-11 12:33:09', NULL),
(8, 3, 1, 'Oneplus 6', 'Oneplus 6 Mobile', 'Oneplus 6', 32000, 'Piece', 5, 22, '2018-06-14 15:35:29', 1, 1, '2018-06-14 10:42:14', NULL),
(9, 3, 1, 'Google Pixel 2', 'Google Pixel 2 Mobile', 'Pixel 2', 52000, 'Piece', 2, 9, '2018-06-14 15:35:24', 1, 1, '2018-06-14 15:35:10', NULL),
(10, 9, 4, 'Blue Star Inverter AC', 'Blue star AC Description', 'AC Image', 15000, 'Piece', 2, 3, '2018-06-20 11:50:29', 1, 1, '2018-06-20 11:50:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblproductimage`
--

CREATE TABLE `tblproductimage` (
  `id` int(11) NOT NULL,
  `ProductID` int(11) NOT NULL,
  `Image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tblproductimage`
--

INSERT INTO `tblproductimage` (`id`, `ProductID`, `Image`) VALUES
(1, 1, '20180604154116_315201740806PM_635_moto_g5_plus (1).jpeg'),
(2, 1, '20180604154116_download.jpg'),
(5, 3, '20180604154958_color-shifters-color-splash-science-lab-playset-hot-wheels-original-imaey4tzrbgfdsx7.jpeg'),
(6, 3, '20180604154958_color-shifters-color-splash-science-lab-playset-hot-wheels-original-imaf3b6pgj86vtgp.jpeg'),
(7, 3, '20180604154959_color-shifters-color-splash-science-lab-playset-hot-wheels-original-imaf3b6pmmffqg9g.jpeg'),
(8, 9, '20180611120435_sky.jpg'),
(9, 9, '20180611120435_traffic.jpg'),
(10, 4, '20180611121809_bridge.jpg'),
(13, 11, '20180611123032_coast.jpg'),
(14, 7, '20180611123309_bridge.jpg'),
(15, 7, '20180611123309_park.jpg'),
(16, 5, '20180611123735_rocks.jpg'),
(17, 8, '20180614104214_IMG_20171210_065805.jpg'),
(19, 2, '20180615135416_render-1.png'),
(20, 10, '20180620115022_Hitachi-1-5-Ton-5-SDL800982567-1-a0a03.jpg');

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
  `Pincode` varchar(10) NOT NULL,
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

INSERT INTO `tblshops` (`ShopID`, `ShopName`, `Tagline`, `LogoImage`, `Address`, `City`, `State`, `Pincode`, `PhoneNo`, `Email`, `Website`, `OwnerName`, `FacebookLink`, `InstagramLink`, `TwitterLink`, `YoutubeLink`, `LogoAlt`, `GSTNo`, `UserName`, `Password`, `OldPassword`, `PasswordUpdatedOn`, `RandomString`, `VerificationCode`, `RandomStringTime`, `IsActive`, `IsApproved`, `CreatedOn`, `ShopType`, `ApprovedOn`, `IsSessionActive`, `IsInitialSetup`, `IsMember`) VALUES
(1, 'Get Mobiles', 'Get Mobiles at best price', '20180604153140_mobileshop.png', 'LegendHarmony', 'Surat', 'Gujarat', '394510', '8866699878', 'riteshdlab@gmail.com', NULL, 'Ritesh Tailor', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'www.facebook.com', 'Get Mobile', '897645321078956', 'getmobile', '159', '147', '2018-06-04 17:22:48', '', NULL, '2018-06-04 15:58:17', 1, 1, '2018-06-04 15:15:48', 'MobileandAccessories', NULL, 1, 1, 1),
(2, 'Arihant Plastic', 'Plastics EveryWhere', '20180604153103_favicon.png', 'MahalaxmiComplex', 'Surat', 'Gujarat', '395017', '9016111959', 'rishabhkochar85@yahoo.com', NULL, 'Rishabh Kochar', 'www.facebook.com', 'www.instagram.com', 'www.twitter.com', 'www.youtube.com', 'Arihant', '123456787854635', 'Arihant', '1234', NULL, NULL, NULL, NULL, NULL, 1, 1, '2018-06-04 15:16:30', 'PlasticShop', NULL, 1, 1, 1),
(4, 'New Shop', NULL, 'Default.png', NULL, NULL, NULL, '', '7896541230', 'rdtailor@gmail.com', NULL, 'New Owner', NULL, NULL, NULL, NULL, NULL, NULL, 'newshop', '1234', NULL, NULL, NULL, NULL, NULL, 1, 1, '2018-06-04 17:35:02', 'New Type', NULL, 0, 1, 1),
(5, 'Mahavir Farsan', 'Our farsan is our farsan', '20180621122648_paypal.png', 'Prime Arcade', 'Surat', 'Gujarat', '395009', '1478520369', 'riteshtailor20@outlook.com', NULL, 'Mahavirbhai ', NULL, NULL, NULL, NULL, 'Mahavir Farsan', '123456789456122', 'mahavirf', '147', '123', '2018-06-21 12:28:06', NULL, NULL, NULL, 0, 1, '2018-06-21 12:19:20', 'Farsan Shop', NULL, 0, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbltracking`
--

CREATE TABLE `tbltracking` (
  `TrackingID` int(11) NOT NULL,
  `OrderDetailsID` int(11) NOT NULL,
  `TrackingText` text NOT NULL,
  `ArrivedTime` datetime NOT NULL,
  `DispatchedTime` datetime NOT NULL,
  `Status` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbltracking`
--

INSERT INTO `tbltracking` (`TrackingID`, `OrderDetailsID`, `TrackingText`, `ArrivedTime`, `DispatchedTime`, `Status`) VALUES
(1, 1, 'Order has been Placed.', '2018-06-20 11:58:30', '2018-06-20 11:58:30', 'Pending'),
(2, 2, 'Order has been Placed.', '2018-06-21 14:05:18', '2018-06-21 14:05:18', 'Pending'),
(3, 2, 'Your Order has Been Confirmed By the Seller.', '2018-06-21 14:06:21', '2018-06-21 14:06:21', 'Confirmed'),
(4, 2, 'Your Order has Been Shipped for your Address.', '2018-06-21 14:07:00', '2018-06-21 14:07:00', 'Shipped'),
(5, 3, 'Order has been Placed.', '2018-06-21 14:12:38', '2018-06-21 14:12:38', 'Pending'),
(6, 3, 'Your Order has Been Confirmed By the Seller.', '2018-06-21 14:13:23', '2018-06-21 14:13:23', 'Confirmed'),
(7, 3, 'Your Order has Been Shipped for your Address.', '2018-06-21 14:13:43', '2018-06-21 14:13:43', 'Shipped'),
(8, 4, 'Order has been Placed.', '2018-06-21 14:14:33', '2018-06-21 14:14:33', 'Pending'),
(9, 4, 'Your Order has Been Confirmed By the Seller.', '2018-06-21 14:14:59', '2018-06-21 14:14:59', 'Confirmed'),
(10, 4, 'Your Order has Been Shipped for your Address.', '2018-06-21 14:15:22', '2018-06-21 14:15:22', 'Shipped'),
(11, 5, 'Order has been Placed.', '2018-06-21 14:17:00', '2018-06-21 14:17:00', 'Pending'),
(12, 6, 'Order has been Placed.', '2018-06-21 14:18:50', '2018-06-21 14:18:50', 'Pending'),
(13, 5, 'Your Order has Been Confirmed By the Seller.', '2018-06-21 14:22:22', '2018-06-21 14:22:22', 'Confirmed'),
(14, 5, 'Your Order has Been Shipped for your Address.', '2018-06-21 14:22:38', '2018-06-21 14:22:38', 'Shipped'),
(15, 6, 'Your Order has Been Confirmed By the Seller.', '2018-06-21 14:27:46', '2018-06-21 14:27:46', 'Confirmed'),
(16, 6, 'Your Order has Been Shipped for your Address.', '2018-06-21 14:27:55', '2018-06-21 14:27:55', 'Shipped');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

CREATE TABLE `tbluser` (
  `UserID` int(11) NOT NULL,
  `PhoneNo` varchar(10) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `OldPassword` varchar(100) DEFAULT NULL,
  `ProfileImage` varchar(100) NOT NULL,
  `Gender` varchar(1) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `IsActive` tinyint(1) NOT NULL,
  `VerificationCode` varchar(10) DEFAULT NULL,
  `RandomString` varchar(10) DEFAULT NULL,
  `RandomStringTime` datetime DEFAULT NULL,
  `PasswordUpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbluser`
--

INSERT INTO `tbluser` (`UserID`, `PhoneNo`, `Email`, `Password`, `OldPassword`, `ProfileImage`, `Gender`, `Name`, `CreatedOn`, `IsActive`, `VerificationCode`, `RandomString`, `RandomStringTime`, `PasswordUpdatedOn`) VALUES
(1, '8866699878', 'rdtailor@gmail.com', '159', '123', 'index.png', 'M', 'Ritesh', '2018-06-20 11:55:21', 1, 'zcrWXFJO', '', '2018-06-21 14:01:28', '2018-06-21 14:02:02');

-- --------------------------------------------------------

--
-- Table structure for table `tblwebsite`
--

CREATE TABLE `tblwebsite` (
  `id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Logo` text NOT NULL,
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
(1, 'Online Store', '20180619130254_if_Cart_877015.png', 'Flipkart', 'rtemp2520@gmail.com', '8866699878', 'rtemp2520$', 'About us', 'Contact us', 'https://www.facebook.com/', 'https://twitter.com/login?lang=en', 'https://www.instagram.com/', 'https://www.youtube.com', '111111111111111', '2018-05-22 11:50:52', '2018-06-21 14:17:06', 'Shop Tag Line');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladdress`
--
ALTER TABLE `tbladdress`
  ADD PRIMARY KEY (`AddressID`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`AdminId`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `PhoneNo` (`PhoneNo`),
  ADD UNIQUE KEY `RandomString` (`RandomString`);

--
-- Indexes for table `tblcart`
--
ALTER TABLE `tblcart`
  ADD PRIMARY KEY (`CartID`);

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
-- Indexes for table `tblcategorypropertiesvalues`
--
ALTER TABLE `tblcategorypropertiesvalues`
  ADD PRIMARY KEY (`ProductID`,`CategoryPropertyID`);

--
-- Indexes for table `tbldiscount`
--
ALTER TABLE `tbldiscount`
  ADD PRIMARY KEY (`ProdID`);

--
-- Indexes for table `tblfeedback`
--
ALTER TABLE `tblfeedback`
  ADD PRIMARY KEY (`FeedbackID`);

--
-- Indexes for table `tblnotification`
--
ALTER TABLE `tblnotification`
  ADD PRIMARY KEY (`NotificationID`);

--
-- Indexes for table `tblorderdetails`
--
ALTER TABLE `tblorderdetails`
  ADD PRIMARY KEY (`OrderDetailID`);

--
-- Indexes for table `tblorders`
--
ALTER TABLE `tblorders`
  ADD PRIMARY KEY (`OrderID`);

--
-- Indexes for table `tblproduct`
--
ALTER TABLE `tblproduct`
  ADD PRIMARY KEY (`ProductId`);

--
-- Indexes for table `tblproductimage`
--
ALTER TABLE `tblproductimage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblshops`
--
ALTER TABLE `tblshops`
  ADD PRIMARY KEY (`ShopID`),
  ADD UNIQUE KEY `PhoneNo` (`PhoneNo`,`Email`,`Website`,`UserName`);

--
-- Indexes for table `tbltracking`
--
ALTER TABLE `tbltracking`
  ADD PRIMARY KEY (`TrackingID`);

--
-- Indexes for table `tbluser`
--
ALTER TABLE `tbluser`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `PhoneNo` (`PhoneNo`,`Email`);

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
-- AUTO_INCREMENT for table `tbladdress`
--
ALTER TABLE `tbladdress`
  MODIFY `AddressID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `AdminId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tblcart`
--
ALTER TABLE `tblcart`
  MODIFY `CartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `CategoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tblcategoryproperties`
--
ALTER TABLE `tblcategoryproperties`
  MODIFY `CategoryPropertyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `tblfeedback`
--
ALTER TABLE `tblfeedback`
  MODIFY `FeedbackID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- AUTO_INCREMENT for table `tblnotification`
--
ALTER TABLE `tblnotification`
  MODIFY `NotificationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `tblorderdetails`
--
ALTER TABLE `tblorderdetails`
  MODIFY `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tblorders`
--
ALTER TABLE `tblorders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tblproduct`
--
ALTER TABLE `tblproduct`
  MODIFY `ProductId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tblproductimage`
--
ALTER TABLE `tblproductimage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `tblshops`
--
ALTER TABLE `tblshops`
  MODIFY `ShopID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbltracking`
--
ALTER TABLE `tbltracking`
  MODIFY `TrackingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `tbluser`
--
ALTER TABLE `tbluser`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tblwebsite`
--
ALTER TABLE `tblwebsite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
