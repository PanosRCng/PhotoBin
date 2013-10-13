-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 01, 2012 at 01:02 AM
-- Server version: 5.1.63
-- PHP Version: 5.3.3-7+squeeze13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `photobin_db`
--
CREATE DATABASE `photobin_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `photobin_db`;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `picture_id` int(11) NOT NULL,
  `username` varchar(30) NOT NULL,
  `comment_text` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`comment_id`,`picture_id`,`username`),
  KEY `C1` (`picture_id`),
  KEY `C2` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=214 ;

--
-- Dumping data for table `comment`
--


-- --------------------------------------------------------

--
-- Table structure for table `picture`
--

CREATE TABLE IF NOT EXISTS `picture` (
  `picture_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `picture_title` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(256) NOT NULL,
  `longtitude` double NOT NULL,
  `latitude` double NOT NULL,
  `likes` int(11) NOT NULL DEFAULT '0',
  `dislikes` int(11) NOT NULL DEFAULT '0',
  `tags` varchar(256) DEFAULT NULL,
  `public` tinyint(1) NOT NULL,
  PRIMARY KEY (`picture_id`,`username`,`picture_title`),
  KEY `C1` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=392 ;

--
-- Dumping data for table `picture`
--


-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `tag_title` varchar(30) NOT NULL,
  `counter` int(11) NOT NULL,
  PRIMARY KEY (`tag_title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tag`
--


-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(30) NOT NULL,
  `first_name` varchar(30) NOT NULL DEFAULT 'unknown',
  `last_name` varchar(30) NOT NULL DEFAULT 'unknown',
  `email_addres` varchar(40) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

