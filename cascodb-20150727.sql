-- phpMyAdmin SQL Dump
-- version 4.2.8.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-07-27 15:07:58
-- 服务器版本： 5.6.19
-- PHP Version: 5.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cascodb`
--

-- --------------------------------------------------------

--
-- 表的结构 `result`
--

CREATE TABLE IF NOT EXISTS `result` (
  `id` varchar(36) NOT NULL,
  `tc_id` varchar(36) NOT NULL,
  `testjob_id` varchar(36) NOT NULL,
  `result` int(11) NOT NULL DEFAULT '0',
  `cr` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `step_result_json` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tc rs_version build关联表';

-- --------------------------------------------------------

--
-- 表的结构 `testjob`
--

CREATE TABLE IF NOT EXISTS `testjob` (
  `id` varchar(36) NOT NULL,
  `name` varchar(255) NOT NULL,
  `project_id` varchar(36) NOT NULL,
  `build_id` varchar(36) NOT NULL,
  `tc_version_id` varchar(36) NOT NULL,
  `rs_version_id` varchar(36) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `result`
--
ALTER TABLE `result`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testjob`
--
ALTER TABLE `testjob`
 ADD PRIMARY KEY (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
