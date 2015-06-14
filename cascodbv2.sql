-- phpMyAdmin SQL Dump
-- version 4.4.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2015-06-14 13:31:16
-- 服务器版本： 5.5.41-MariaDB
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cascodb0604`
--

-- --------------------------------------------------------

--
-- 表的结构 `build`
--

CREATE TABLE IF NOT EXISTS `build` (
  `id` varchar(36) NOT NULL,
  `version` varchar(100) NOT NULL,
  `project_id` varchar(36) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `document`
--

CREATE TABLE IF NOT EXISTS `document` (
  `id` varchar(36) NOT NULL COMMENT '默认索引',
  `name` varchar(100) NOT NULL COMMENT '文档名称',
  `type` enum('rs','tc','ad','tr','folder') NOT NULL COMMENT '文档类型',
  `project_id` varchar(36) NOT NULL COMMENT '所属项目',
  `fid` varchar(36) NOT NULL DEFAULT '0' COMMENT '目录结构',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `id` varchar(36) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `graph` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `project_user`
--

CREATE TABLE IF NOT EXISTS `project_user` (
  `project_id` varchar(36) NOT NULL COMMENT '工程id',
  `user_id` varchar(36) NOT NULL COMMENT '员工id',
  `role` varchar(10) NOT NULL COMMENT '与user_role里面的role_type是否重复？'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `relation`
--

CREATE TABLE IF NOT EXISTS `relation` (
  `id` int(11) NOT NULL,
  `src` varchar(36) NOT NULL COMMENT '文档关系起点',
  `dest` varchar(36) NOT NULL COMMENT '文档关系终点',
  `src_type` varchar(10) NOT NULL COMMENT '源item所属类别',
  `dest_type` varchar(10) NOT NULL COMMENT '覆盖item所属类别',
  `src_tag` varchar(20) NOT NULL COMMENT '源tag内容',
  `dest_tag` varchar(20) NOT NULL COMMENT '目标tag内容'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `result`
--

CREATE TABLE IF NOT EXISTS `result` (
  `id` varchar(36) NOT NULL,
  `tc_id` varchar(36) NOT NULL,
  `rs_version_id` varchar(36) NOT NULL,
  `build_id` varchar(36) NOT NULL,
  `result` int(11) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tc rs_version build关联表';

-- --------------------------------------------------------

--
-- 表的结构 `rs`
--

CREATE TABLE IF NOT EXISTS `rs` (
  `id` varchar(36) NOT NULL COMMENT 'rsitems的主键',
  `document_id` varchar(36) NOT NULL COMMENT 'rsitems所属文献',
  `tag` varchar(100) NOT NULL COMMENT '标签名称，添加索引',
  `description` text NOT NULL COMMENT '标签描述',
  `implement` varchar(10) DEFAULT NULL COMMENT '手段',
  `priority` varchar(20) NOT NULL COMMENT '优先级',
  `contribution` varchar(10) NOT NULL COMMENT '安全性',
  `category` varchar(20) NOT NULL COMMENT '类别',
  `allocation` varchar(200) NOT NULL COMMENT '分配对象',
  `vatstr_id` varchar(36) NOT NULL COMMENT '对应管理员分配的vat',
  `varstr_result` int(1) NOT NULL DEFAULT '0',
  `version_id` varchar(36) NOT NULL,
  `source_json` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `rs_vat`
--

CREATE TABLE IF NOT EXISTS `rs_vat` (
  `id` int(11) NOT NULL,
  `rs_id` varchar(36) NOT NULL,
  `vat_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 替换视图以便查看 `tag`
--
CREATE TABLE IF NOT EXISTS `tag` (
`id` varchar(36)
,`tag` varchar(100)
,`document_id` varchar(36)
);

-- --------------------------------------------------------

--
-- 表的结构 `tc`
--

CREATE TABLE IF NOT EXISTS `tc` (
  `id` varchar(36) NOT NULL COMMENT '单个tc标签id',
  `document_id` varchar(36) NOT NULL COMMENT 'tc所属文献id',
  `tag` varchar(100) NOT NULL COMMENT 'tc名称',
  `description` text NOT NULL COMMENT 'tc描述',
  `testmethod_id` varchar(32) NOT NULL COMMENT '测试方法',
  `test_item` varchar(100) NOT NULL,
  `pre_condition` text NOT NULL COMMENT '前提条件',
  `input` varchar(255) NOT NULL,
  `exec_step` varchar(255) NOT NULL,
  `exp_step` varchar(255) NOT NULL,
  `version_id` varchar(36) NOT NULL,
  `source_json` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `tc_step`
--

CREATE TABLE IF NOT EXISTS `tc_step` (
  `id` int(11) NOT NULL,
  `tc_id` varchar(36) NOT NULL COMMENT '所属tc的id',
  `num` int(11) NOT NULL COMMENT '步骤数',
  `indata` varchar(255) NOT NULL,
  `actions` text NOT NULL COMMENT '采取的行动',
  `expected_result` text NOT NULL COMMENT '所希望的现象',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `testmethod`
--

CREATE TABLE IF NOT EXISTS `testmethod` (
  `id` varchar(32) NOT NULL,
  `name` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `testmethod`
--

INSERT INTO `testmethod` (`id`, `name`, `created_at`, `updated_at`) VALUES
('34af2239-94c5-486b-98a3-b118a75f', 'EG', '2015-03-12 04:36:39', '2015-03-12 04:37:45'),
('405b5232-5592-4303-8d79-307c80b7', 'EP', '2015-03-12 04:37:52', '2015-03-12 06:06:28'),
('beaa1f6d-f851-4522-80df-78bd4070', 'Other', '2015-03-12 04:38:06', '2015-03-12 04:38:06');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` varchar(36) NOT NULL,
  `jobnumber` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `account` varchar(30) NOT NULL,
  `realname` varchar(30) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'staff',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `jobnumber`, `password`, `account`, `realname`, `role`, `created_at`, `updated_at`) VALUES
('a7b12e32-b0f5-11e4-abb7-c17404b78885', '123', '202cb962ac59075b964b07152d234b70', 'guodong', '郭栋', 'admin', '2015-02-10 00:00:00', '2015-02-10 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `vat`
--

CREATE TABLE IF NOT EXISTS `vat` (
  `id` varchar(36) NOT NULL,
  `name` varchar(30) NOT NULL,
  `project_id` varchar(36) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `version`
--

CREATE TABLE IF NOT EXISTS `version` (
  `id` varchar(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `document_id` varchar(36) NOT NULL,
  `filename` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 视图结构 `tag`
--
DROP TABLE IF EXISTS `tag`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tag` AS select `rs`.`id` AS `id`,`rs`.`tag` AS `tag`,`rs`.`document_id` AS `document_id` from `rs` union select `tc`.`id` AS `id`,`tc`.`tag` AS `tag`,`tc`.`document_id` AS `document_id` from `tc`;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `build`
--
ALTER TABLE `build`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `document`
--
ALTER TABLE `document`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `relation`
--
ALTER TABLE `relation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `result`
--
ALTER TABLE `result`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rs`
--
ALTER TABLE `rs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `title` (`tag`),
  ADD FULLTEXT KEY `title_2` (`tag`);

--
-- Indexes for table `rs_vat`
--
ALTER TABLE `rs_vat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tc`
--
ALTER TABLE `tc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doc_id` (`document_id`),
  ADD KEY `title` (`tag`);

--
-- Indexes for table `tc_step`
--
ALTER TABLE `tc_step`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tc_id` (`tc_id`);

--
-- Indexes for table `testmethod`
--
ALTER TABLE `testmethod`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account` (`account`);

--
-- Indexes for table `vat`
--
ALTER TABLE `vat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `version`
--
ALTER TABLE `version`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `relation`
--
ALTER TABLE `relation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rs_vat`
--
ALTER TABLE `rs_vat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tc_step`
--
ALTER TABLE `tc_step`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
