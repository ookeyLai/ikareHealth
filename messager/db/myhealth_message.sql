-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- 主機: 127.0.0.1
-- 建立日期: Oct 29, 2013, 02:51 PM
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
-- 資料表格式： `accountgroup`
--

CREATE TABLE IF NOT EXISTS `accountgroup` (
  `accountID` int(11) NOT NULL,
  `groupID` int(11) NOT NULL,
  KEY `accountID` (`accountID`),
  KEY `groupID` (`groupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 列出以下資料庫的數據： `accountgroup`
--

INSERT INTO `accountgroup` (`accountID`, `groupID`) VALUES
(3, 4),
(1, 4),
(4, 5),
(2, 6),
(4, 7),
(6, 7),
(3, 7);

-- --------------------------------------------------------

--
-- 資料表格式： `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `groupname` varchar(80) NOT NULL,
  `parentID` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `parentID` (`parentID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 列出以下資料庫的數據： `groups`
--

INSERT INTO `groups` (`ID`, `groupname`, `parentID`) VALUES
(1, 'GP1', 0),
(2, 'GP2', 1),
(4, 'GP3', 0),
(5, 'GP3-1', 4),
(6, 'GP3-2', 4),
(7, 'GP3-3', 4);

-- --------------------------------------------------------

--
-- 資料表格式： `messagegroup`
--

CREATE TABLE IF NOT EXISTS `messagegroup` (
  `messageID` int(11) NOT NULL,
  `grouped` tinyint(4) NOT NULL DEFAULT '0',
  `groupID` int(11) NOT NULL,
  KEY `messageID` (`messageID`),
  KEY `grouped` (`grouped`),
  KEY `groupID` (`groupID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 列出以下資料庫的數據： `messagegroup`
--

INSERT INTO `messagegroup` (`messageID`, `grouped`, `groupID`) VALUES
(2, 1, 2);

-- --------------------------------------------------------

--
-- 資料表格式： `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `message` blob NOT NULL,
  `grouped` tinyint(1) NOT NULL DEFAULT '0',
  `published` datetime NOT NULL,
  `closed` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `grouped` (`grouped`),
  KEY `published` (`published`),
  KEY `closed` (`closed`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 列出以下資料庫的數據： `messages`
--

INSERT INTO `messages` (`ID`, `message`, `grouped`, `published`, `closed`) VALUES
(2, 0x5445535420312075706461746564, 1, '2013-10-29 00:00:00', '0000-00-00 00:00:00'),
(3, 0x546573742032200d0a4c696e652032, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 資料表格式： `requestlog`
--

CREATE TABLE IF NOT EXISTS `requestlog` (
  `messageID` int(11) NOT NULL,
  `accountID` int(11) NOT NULL,
  `requested` datetime NOT NULL,
  KEY `messageID` (`messageID`),
  KEY `accountID` (`accountID`),
  KEY `requested` (`requested`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 列出以下資料庫的數據： `requestlog`
--

