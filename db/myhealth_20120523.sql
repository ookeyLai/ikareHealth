-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- 主機: 127.0.0.1
-- 建立日期: May 23, 2012, 02:37 PM
-- 伺服器版本: 5.5.10
-- PHP 版本: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 資料庫: `myhealth`
--

-- --------------------------------------------------------

--
-- 資料表格式： `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` char(1) NOT NULL DEFAULT 'M',
  `birthday` date NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` varchar(40) NOT NULL,
  `timezone` varchar(30) NOT NULL,
  `authority` varchar(30) NOT NULL,
  `password` varchar(200) NOT NULL,
  `weight` varchar(20) NOT NULL,
  `glucose` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 列出以下資料庫的數據： `account`
--

INSERT INTO `account` (`ID`, `username`, `name`, `gender`, `birthday`, `email`, `phone`, `timezone`, `authority`, `password`, `weight`, `glucose`) VALUES
(1, 'admin', 'Superuser ä¸­æ–‡', 'M', '1964-08-25', 'ookey.lai@gmail.com', '0920190140', '+8', 'ADMIN', 'd7dadc62db2dc1a06927925d0822b61b', '', ''),
(2, 'user1', 'Test User1', 'M', '1978-12-12', 'ookey.lai@twgrid.org', '0920-111222', '+8', 'USER', 'd7dadc62db2dc1a06927925d0822b61b', 'kg', 'mmol/L'),
(3, 'user2', 'Ookey è³´å½¥ä¸ž', 'M', '1988-06-20', 'ookeykimo@yahoo.com.tw', '0920-190140', '+8', 'USER', 'd7dadc62db2dc1a06927925d0822b61b', 'kg', 'mmol/L'),
(4, 'user3', 'Cherry', 'F', '1964-08-25', 'admin@domain.com', '886227898313', '+2', 'USER', 'd7dadc62db2dc1a06927925d0822b61b', 'kg', 'mmol/L');

-- --------------------------------------------------------

--
-- 資料表格式： `accountdevice`
--

CREATE TABLE IF NOT EXISTS `accountdevice` (
  `accountID` int(11) NOT NULL,
  `deviceID` int(11) NOT NULL,
  `deviceSN` varchar(100) NOT NULL,
  KEY `accountID` (`accountID`),
  KEY `deviceID` (`deviceID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 列出以下資料庫的數據： `accountdevice`
--


-- --------------------------------------------------------

--
-- 資料表格式： `accountitem`
--

CREATE TABLE IF NOT EXISTS `accountitem` (
  `accountID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  KEY `accountID` (`accountID`),
  KEY `itemID` (`itemID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 列出以下資料庫的數據： `accountitem`
--

INSERT INTO `accountitem` (`accountID`, `itemID`) VALUES
(1, 5),
(1, 4),
(1, 3),
(1, 2),
(1, 1);

-- --------------------------------------------------------

--
-- 資料表格式： `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `itemID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type_Def` varchar(30) NOT NULL DEFAULT 'System',
  `accountID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `itemID` (`itemID`),
  KEY `accountID` (`accountID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 列出以下資料庫的數據： `category`
--

INSERT INTO `category` (`ID`, `itemID`, `name`, `type_Def`, `accountID`) VALUES
(1, 0, 'Breakfast', 'SYSTEM', 0),
(2, 0, 'Lunch', 'SYSTEM', 0),
(3, 0, 'Dinner', 'SYSTEM', 0);

-- --------------------------------------------------------

--
-- 資料表格式： `device`
--

CREATE TABLE IF NOT EXISTS `device` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `information` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 列出以下資料庫的數據： `device`
--

INSERT INTO `device` (`ID`, `name`, `model`, `information`) VALUES
(1, 'è¡€å£“è¨ˆ', 'AK-001', '2012ç‰ˆ (V 12.1)'),
(2, 'è¡€ç³–æ©Ÿ', 'G-123', 'ver 500');

-- --------------------------------------------------------

--
-- 資料表格式： `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `deviceID` int(11) NOT NULL,
  `articleMode` varchar(20) NOT NULL,
  `userArticle` varchar(20) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `deviceID` (`deviceID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 列出以下資料庫的數據： `item`
--

INSERT INTO `item` (`ID`, `name`, `deviceID`, `articleMode`, `userArticle`) VALUES
(1, 'Blood Pressure', 1, 'LIST', 'NO'),
(2, 'Glucose', 2, 'LIST', 'NO'),
(3, 'Weight', 0, 'LIST', 'NO'),
(4, 'Medication', 0, 'SELECT', 'YES'),
(5, 'Exercise', 0, 'SELECT', 'YES');

-- --------------------------------------------------------

--
-- 資料表格式： `itemarticle`
--

CREATE TABLE IF NOT EXISTS `itemarticle` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `itemID` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `measurement` varchar(30) NOT NULL,
  `min_Value` decimal(11,2) NOT NULL,
  `max_Value` decimal(11,2) NOT NULL,
  `type_Def` varchar(30) NOT NULL DEFAULT 'SYSTEM',
  `accountID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `itemID` (`itemID`),
  KEY `accountID` (`accountID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 列出以下資料庫的數據： `itemarticle`
--

INSERT INTO `itemarticle` (`ID`, `itemID`, `name`, `measurement`, `min_Value`, `max_Value`, `type_Def`, `accountID`) VALUES
(1, 1, 'Systolic', 'mm', 0.00, 400.00, 'SYSTEM', 0),
(2, 1, 'Diastolic', 'mm', 0.00, 400.00, 'SYSTEM', 0),
(3, 1, 'Pulse', '', 0.00, 200.00, 'SYSTEM', 0),
(4, 2, 'Glucose', '', 0.00, 400.00, 'SYSTEM', 0),
(5, 3, 'Weight', 'kg', 0.00, 600.00, 'SYSTEM', 0),
(6, 4, 'Aspirin', 'pellet', 0.00, 10.00, 'USER', 3),
(7, 5, 'Jog', 'minutes', 0.00, 180.00, 'USER', 3),
(8, 4, 'Vitamin B', 'pellet', 0.00, 5.00, 'SYSTEM', 0),
(9, 5, 'Swimming', 'm', 0.00, 0.00, 'SYSTEM', 0);

-- --------------------------------------------------------

--
-- 資料表格式： `itementry`
--

CREATE TABLE IF NOT EXISTS `itementry` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `accountID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `deviceID` int(11) NOT NULL,
  `createdTime` datetime NOT NULL,
  `categoryID` int(11) NOT NULL,
  `dataPath` varchar(120) NOT NULL,
  `note` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `accountID` (`accountID`),
  KEY `itemID` (`itemID`),
  KEY `deviceID` (`deviceID`),
  KEY `categoryID` (`categoryID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- 列出以下資料庫的數據： `itementry`
--

INSERT INTO `itementry` (`ID`, `accountID`, `itemID`, `deviceID`, `createdTime`, `categoryID`, `dataPath`, `note`) VALUES
(1, 1, 1, 0, '2012-05-18 11:53:45', 2, '', 'test 1'),
(2, 1, 4, 0, '2012-05-18 12:59:05', 3, '', ''),
(3, 1, 1, 0, '2012-05-11 15:01:06', 2, '', ''),
(4, 1, 1, 0, '2012-05-12 15:01:45', 1, '', ''),
(5, 1, 1, 0, '2012-05-13 15:02:45', 1, '', ''),
(6, 1, 1, 0, '2012-05-14 15:03:17', 2, '', ''),
(7, 1, 1, 0, '2012-05-15 15:03:43', 2, '', ''),
(8, 1, 1, 0, '2012-05-16 15:04:12', 2, '', ''),
(9, 1, 1, 0, '2012-05-17 15:04:33', 2, '', ''),
(10, 1, 4, 0, '2012-05-17 15:57:02', 3, '', ''),
(19, 1, 3, 0, '2012-03-18 16:36:57', 1, '', ''),
(13, 1, 4, 0, '2012-05-18 15:58:38', 0, '', ''),
(14, 1, 4, 0, '2012-05-11 16:06:26', 3, '', ''),
(15, 1, 4, 0, '2012-05-16 16:17:03', 2, '', ''),
(16, 1, 4, 0, '2012-05-16 16:20:10', 1, '', ''),
(17, 1, 4, 0, '2012-05-13 16:29:43', 3, '', ''),
(18, 1, 4, 0, '2012-05-17 16:31:55', 2, '', ''),
(20, 1, 3, 0, '2011-12-18 16:38:25', 1, '', '1111'),
(22, 1, 3, 0, '2012-05-21 10:43:09', 1, '', '');

-- --------------------------------------------------------

--
-- 資料表格式： `itementryrow`
--

CREATE TABLE IF NOT EXISTS `itementryrow` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `itemEntryID` int(11) NOT NULL,
  `itemArticleID` int(11) NOT NULL,
  `value` decimal(11,2) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `accountID` (`itemEntryID`),
  KEY `itemArticleID` (`itemArticleID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

--
-- 列出以下資料庫的數據： `itementryrow`
--

INSERT INTO `itementryrow` (`ID`, `itemEntryID`, `itemArticleID`, `value`) VALUES
(1, 1, 1, 120.00),
(2, 1, 2, 90.00),
(3, 1, 3, 80.00),
(4, 2, 8, 2.00),
(5, 3, 1, 130.00),
(6, 3, 2, 89.00),
(7, 3, 3, 74.00),
(8, 4, 1, 166.00),
(9, 4, 2, 89.00),
(10, 4, 3, 88.00),
(11, 5, 1, 148.00),
(12, 5, 2, 90.00),
(13, 5, 3, 83.00),
(14, 6, 1, 142.00),
(15, 6, 2, 88.00),
(16, 6, 3, 73.00),
(17, 7, 1, 142.00),
(18, 7, 2, 89.00),
(19, 7, 3, 71.00),
(20, 8, 1, 132.00),
(21, 8, 2, 94.00),
(22, 8, 3, 82.00),
(23, 9, 1, 152.00),
(24, 9, 2, 85.00),
(25, 9, 3, 79.00),
(26, 10, 8, 2.00),
(36, 20, 5, 74.30),
(35, 19, 5, 76.30),
(29, 13, 6, 1.00),
(30, 14, 8, 1.00),
(31, 15, 8, 2.20),
(32, 16, 6, 1.20),
(33, 17, 6, 2.10),
(34, 18, 6, 1.10),
(38, 22, 5, 78.40);
