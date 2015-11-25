-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-11-02 08:06:11
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

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
-- 表的结构 `build`
--

CREATE TABLE IF NOT EXISTS `build` (
  `id` varchar(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `project_id` varchar(36) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `build`
--

INSERT INTO `build` (`id`, `name`, `project_id`, `created_at`, `updated_at`) VALUES
('af196842-d459-11e4-8857-8f88fc59c6d4', 'testv1', '90640116-ad10-450c-9d30-91b8a6acc607', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `document`
--

CREATE TABLE IF NOT EXISTS `document` (
  `id` varchar(36) NOT NULL COMMENT '默认索引',
  `name` varchar(100) NOT NULL COMMENT '文档名称',
  `type` enum('rs','tc','ad','tr','folder') NOT NULL COMMENT '文档类型',
  `fid` varchar(36) NOT NULL DEFAULT '0' COMMENT '层次结构-系统/子系统/软件',
  `filename` varchar(40) NOT NULL,
  `headers` varchar(100) NOT NULL DEFAULT 'h1,h2.h3',
  `project_id` varchar(36) NOT NULL COMMENT '所属项目',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `document`
--

INSERT INTO `document` (`id`, `name`, `type`, `fid`, `filename`, `headers`, `project_id`, `created_at`, `updated_at`) VALUES
('5c144c2c-f983-4d30-936b-0a17e00f8bfc', 'vv', 'rs', '0', '', '', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', '2015-05-04 21:35:14', '2015-05-04 21:35:14'),
('af31405d-3dbc-4349-95d2-d75ed03c5993', 'test1', 'rs', '0', '', '', '90640116-ad10-450c-9d30-91b8a6acc607', '2015-06-13 22:29:21', '2015-06-13 22:29:21'),
('c00dc5ad-c45f-410c-9f88-b27562414c59', 'adf', 'rs', '0', '', '', '90640116-ad10-450c-9d30-91b8a6acc607', '2015-02-06 01:36:36', '2015-02-06 01:36:36'),
('d6889236-ad21-11e4-aa9b-cf2d72b432dc', 'TSP-SYRS', 'rs', 'dc14a208-ad21-11e4-aa9b-cf2d72b432dc', '111', '', '90640116-ad10-450c-9d30-91b8a6acc607', '2014-11-23 14:05:41', '2015-02-05 01:16:30'),
('dc14a208-ad21-11e4-aa9b-cf2d72b432dc', 'sytem', 'folder', '0', '', '', '90640116-ad10-450c-9d30-91b8a6acc607', '2014-12-08 22:31:02', '2014-12-08 22:31:02'),
('e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'TSP-SYTC', 'tc', 'dc14a208-ad21-11e4-aa9b-cf2d72b432dc', '', '', '90640116-ad10-450c-9d30-91b8a6acc607', '2014-11-23 13:21:30', '2015-01-28 00:00:44'),
('e7146468-ad21-11e4-aa9b-cf2d72b432dc', 'subsystem', 'folder', '0', '', '', '90640116-ad10-450c-9d30-91b8a6acc607', '2014-12-08 15:42:01', '2014-12-08 15:42:01'),
('eca32cc0-ad21-11e4-aa9b-cf2d72b432dc', 'test case', 'tc', 'e7146468-ad21-11e4-aa9b-cf2d72b432dc', '', '', '90640116-ad10-450c-9d30-91b8a6acc607', '2014-12-08 15:58:51', '2015-01-28 00:00:51');

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
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `project`
--

INSERT INTO `project` (`id`, `name`, `description`, `graph`, `created_at`, `updated_at`) VALUES
('7', 'RAIL-FORWARD', '12312', '{"cells":[{"type":"basic.Rect","position":{"x":207,"y":71},"size":{"width":150,"height":30},"angle":0,"id":"144c2cff-9158-4b2b-bae9-231ba370b048","z":1,"attrs":{"rect":{"fill":"#E74C3C"},"text":{"text":"11","fill":"white"}}}]}', '2014-11-23 18:32:34', '2015-03-16 05:20:07'),
('90640116-ad10-450c-9d30-91b8a6acc607', 'TSP-PJG', '卡斯卡项目', '{"cells":[{"type":"basic.Rect","position":{"x":64,"y":171},"size":{"width":150,"height":30},"angle":0,"id":"d6889236-ad21-11e4-aa9b-cf2d72b432dc","z":1,"attrs":{"rect":{"fill":"#E74C3C"},"text":{"text":"TSP-SYRS","fill":"white"}}},{"type":"basic.Rect","position":{"x":387,"y":60},"size":{"width":150,"height":30},"angle":0,"id":"e1c83444-ad21-11e4-aa9b-cf2d72b432dc","z":2,"attrs":{"rect":{"fill":"#8E44AD"},"text":{"text":"TSP-SYTC","fill":"white"}}},{"type":"basic.Rect","position":{"x":399,"y":162},"size":{"width":150,"height":30},"angle":0,"id":"eca32cc0-ad21-11e4-aa9b-cf2d72b432dc","z":3,"attrs":{"rect":{"fill":"#8E44AD"},"text":{"text":"test case","fill":"white"}}},{"type":"basic.Rect","position":{"x":38,"y":20},"size":{"width":150,"height":30},"angle":0,"id":"c00dc5ad-c45f-410c-9f88-b27562414c59","z":5,"attrs":{"rect":{"fill":"#E74C3C"},"text":{"text":"adf","fill":"white"}}},{"type":"fsa.Arrow","smooth":true,"source":{"id":"c00dc5ad-c45f-410c-9f88-b27562414c59"},"target":{"id":"d6889236-ad21-11e4-aa9b-cf2d72b432dc"},"id":"4e8924ea-ef36-4d1c-b0c1-4537371842b6","z":6,"attrs":{}},{"type":"fsa.Arrow","smooth":true,"source":{"id":"d6889236-ad21-11e4-aa9b-cf2d72b432dc"},"target":{"id":"eca32cc0-ad21-11e4-aa9b-cf2d72b432dc"},"id":"624b0f68-07be-4755-b9a4-85fb364c52d3","z":7,"attrs":{}},{"type":"fsa.Arrow","smooth":true,"source":{"id":"e1c83444-ad21-11e4-aa9b-cf2d72b432dc"},"target":{"id":"d6889236-ad21-11e4-aa9b-cf2d72b432dc"},"id":"e5aa1834-4c97-4c0f-83f9-949575b5ef0f","z":8,"attrs":{}},{"type":"fsa.Arrow","smooth":true,"source":{"id":"e1c83444-ad21-11e4-aa9b-cf2d72b432dc"},"target":{"id":"c00dc5ad-c45f-410c-9f88-b27562414c59"},"id":"beb173b0-fcbc-4c31-a207-46805f848769","z":9,"attrs":{}},{"type":"basic.Rect","position":{"x":96,"y":301},"size":{"width":150,"height":30},"angle":0,"id":"af31405d-3dbc-4349-95d2-d75ed03c5993","z":10,"attrs":{"rect":{"fill":"#E74C3C"},"text":{"text":"test1","fill":"white"}}}]}', '2014-11-24 00:00:00', '2015-06-14 06:29:27'),
('d942b32c-3002-403b-a29d-90f11e30061b', 'test', 'dd', '', '2015-03-10 11:40:20', '2015-03-10 11:40:20');

-- --------------------------------------------------------

--
-- 表的结构 `project_user`
--

CREATE TABLE IF NOT EXISTS `project_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` varchar(36) NOT NULL COMMENT '工程id',
  `user_id` varchar(36) NOT NULL COMMENT '员工id',
  `doc_edit` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- 转存表中的数据 `project_user`
--

INSERT INTO `project_user` (`id`, `project_id`, `user_id`, `doc_edit`, `created_at`, `updated_at`) VALUES
(1, '90640116-ad10-450c-9d30-91b8a6acc607', 'a7b12e32-b0f5-11e4-abb7-c17404b78885', 'af31405d-3dbc-4349-95d2-d75ed03c5993,c00dc5ad-c45f-410c-9f88-b27562414c59,dc14a208-ad21-11e4-aa9b-cf2d72b432dc,d6889236-ad21-11e4-aa9b-cf2d72b432dc,e1c83444-ad21-11e4-aa9b-cf2d72b432dc,e7146468-ad21-11e4-aa9b-cf2d72b432dc,eca32cc0-ad21-11e4-aa9b-cf2d72b432dc', '0000-00-00 00:00:00', '2015-11-02 08:33:13'),
(4, '90640116-ad10-450c-9d30-91b8a6acc607', '8515062e-9a1a-407c-bb18-e5c77266e8d3', 'af31405d-3dbc-4349-95d2-d75ed03c5993,c00dc5ad-c45f-410c-9f88-b27562414c59,dc14a208-ad21-11e4-aa9b-cf2d72b432dc,d6889236-ad21-11e4-aa9b-cf2d72b432dc,e1c83444-ad21-11e4-aa9b-cf2d72b432dc', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'd942b32c-3002-403b-a29d-90f11e30061b', '8515062e-9a1a-407c-bb18-e5c77266e8d3', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'd942b32c-3002-403b-a29d-90f11e30061b', '57f64694-4b12-45b7-934f-4b3465dfc3d2', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, '7', '57f64694-4b12-45b7-934f-4b3465dfc3d2', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, '90640116-ad10-450c-9d30-91b8a6acc607', '639bd000-52ce-4ad5-aa90-9e50f43f8853', 'c00dc5ad-c45f-410c-9f88-b27562414c59,dc14a208-ad21-11e4-aa9b-cf2d72b432dc,d6889236-ad21-11e4-aa9b-cf2d72b432dc,e1c83444-ad21-11e4-aa9b-cf2d72b432dc', '0000-00-00 00:00:00', '2015-10-20 15:07:51'),
(29, '90640116-ad10-450c-9d30-91b8a6acc607', '4fb60451-915f-455f-ab44-e47a23615c46', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, '7', '4fb60451-915f-455f-ab44-e47a23615c46', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(34, '7', 'b9e80cdd-60cc-41cf-bce3-dc3c9a34d257', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(35, '90640116-ad10-450c-9d30-91b8a6acc607', 'b9e80cdd-60cc-41cf-bce3-dc3c9a34d257', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(36, 'd942b32c-3002-403b-a29d-90f11e30061b', 'b9e80cdd-60cc-41cf-bce3-dc3c9a34d257', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `relation`
--

CREATE TABLE IF NOT EXISTS `relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `src` varchar(36) NOT NULL COMMENT '文档关系起点',
  `dest` varchar(36) NOT NULL COMMENT '文档关系终点',
  PRIMARY KEY (`id`),
  KEY `src` (`src`),
  KEY `dest` (`dest`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30 ;

--
-- 转存表中的数据 `relation`
--

INSERT INTO `relation` (`id`, `src`, `dest`) VALUES
(1, 'c00dc5ad-c45f-410c-9f88-b27562414c59', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(2, 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', 'eca32cc0-ad21-11e4-aa9b-cf2d72b432dc'),
(3, 'c00dc5ad-c45f-410c-9f88-b27562414c59', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(4, 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', 'eca32cc0-ad21-11e4-aa9b-cf2d72b432dc'),
(5, 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(6, 'c00dc5ad-c45f-410c-9f88-b27562414c59', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(7, 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', 'eca32cc0-ad21-11e4-aa9b-cf2d72b432dc'),
(8, 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(9, 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'c00dc5ad-c45f-410c-9f88-b27562414c59'),
(10, 'c00dc5ad-c45f-410c-9f88-b27562414c59', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(11, 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', 'eca32cc0-ad21-11e4-aa9b-cf2d72b432dc'),
(12, 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(13, 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'c00dc5ad-c45f-410c-9f88-b27562414c59'),
(14, 'c00dc5ad-c45f-410c-9f88-b27562414c59', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(15, 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', 'eca32cc0-ad21-11e4-aa9b-cf2d72b432dc'),
(16, 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(17, 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'c00dc5ad-c45f-410c-9f88-b27562414c59'),
(18, 'c00dc5ad-c45f-410c-9f88-b27562414c59', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(19, 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', 'eca32cc0-ad21-11e4-aa9b-cf2d72b432dc'),
(20, 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(21, 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'c00dc5ad-c45f-410c-9f88-b27562414c59'),
(22, 'c00dc5ad-c45f-410c-9f88-b27562414c59', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(23, 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', 'eca32cc0-ad21-11e4-aa9b-cf2d72b432dc'),
(24, 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(25, 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'c00dc5ad-c45f-410c-9f88-b27562414c59'),
(26, 'c00dc5ad-c45f-410c-9f88-b27562414c59', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(27, 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', 'eca32cc0-ad21-11e4-aa9b-cf2d72b432dc'),
(28, 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc'),
(29, 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'c00dc5ad-c45f-410c-9f88-b27562414c59');

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
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='tc rs_version build关联表';

--
-- 转存表中的数据 `result`
--

INSERT INTO `result` (`id`, `tc_id`, `testjob_id`, `result`, `cr`, `comment`, `step_result_json`, `created_at`, `updated_at`) VALUES
('34e402c0-9c88-4d8e-8013-de1c56ff05f8', '01e87349-d3b1-4985-b261-2de78719a825', '7f2d49ae-3b76-448a-93ae-66050ab0f670', 0, '', '', '', '2015-09-07 11:00:41', '2015-09-07 11:00:41'),
('7c705202-a4dc-4190-aef1-3d057810500a', 'fd7bdad4-c56c-410c-95b6-910e53f1dd6b', '7f2d49ae-3b76-448a-93ae-66050ab0f670', 0, '', '', '', '2015-09-07 11:00:41', '2015-09-07 11:00:41'),
('7d6a4024-9482-49fb-b1ec-c9bd478a09dc', 'bf9839fe-31e2-495a-bf51-116ad2364c64', '7f2d49ae-3b76-448a-93ae-66050ab0f670', 0, '', '', '', '2015-09-07 11:00:41', '2015-09-07 11:00:41'),
('843be002-5215-4d55-9ef1-74ff98c2c98b', '2e47eb3e-3582-49f8-9cf0-40a554841e7d', '7f2d49ae-3b76-448a-93ae-66050ab0f670', 0, '', '', '', '2015-09-07 11:00:41', '2015-09-07 11:00:41'),
('d64bc4ca-9312-4591-9d66-a3f2c3ca029e', '399b97e7-776f-49f3-b4fe-ffc218f0ff55', '7f2d49ae-3b76-448a-93ae-66050ab0f670', 0, '', '', '', '2015-09-07 11:00:41', '2015-09-07 11:00:41');

-- --------------------------------------------------------

--
-- 表的结构 `result_step`
--

CREATE TABLE IF NOT EXISTS `result_step` (
  `id` varchar(36) NOT NULL,
  `result_id` varchar(36) NOT NULL,
  `step_id` varchar(36) NOT NULL,
  `result` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `result_step`
--

INSERT INTO `result_step` (`id`, `result_id`, `step_id`, `result`, `comment`, `created_at`, `updated_at`) VALUES
('00dd47ca-6d2e-4dcc-a776-92025e1956be', '34e402c0-9c88-4d8e-8013-de1c56ff05f8', '16', 0, '', '2015-09-15 11:13:47', '2015-09-15 11:13:47'),
('01a13401-89b3-46f1-9f88-5e87d5d87175', 'd64bc4ca-9312-4591-9d66-a3f2c3ca029e', '10', 0, '', '2015-09-15 11:13:47', '2015-09-15 11:13:47'),
('07a6ef94-9e69-49a3-a66d-0b2243fa3a07', 'd64bc4ca-9312-4591-9d66-a3f2c3ca029e', '15', 0, '', '2015-09-15 11:13:47', '2015-09-15 11:13:47'),
('3e0f9f42-5aba-4a31-b92b-b12e298c8281', 'd64bc4ca-9312-4591-9d66-a3f2c3ca029e', '18', 0, '', '2015-09-15 11:13:47', '2015-09-15 11:13:47'),
('50916975-8570-4649-b665-4e7d20437698', '7c705202-a4dc-4190-aef1-3d057810500a', '13', 0, '', '2015-09-15 11:13:47', '2015-09-15 11:13:47'),
('5cd6e287-4083-4a4e-80c7-e1af703f3af1', '34e402c0-9c88-4d8e-8013-de1c56ff05f8', '11', 0, '', '2015-09-15 11:13:47', '2015-09-15 11:13:47'),
('84cc6167-3bc8-48d0-8a33-472f8b74f100', '34e402c0-9c88-4d8e-8013-de1c56ff05f8', '17', 0, '', '2015-09-15 11:13:47', '2015-09-15 11:13:47'),
('9c77d2aa-7956-4388-b293-4ac06c638cb5', '7d6a4024-9482-49fb-b1ec-c9bd478a09dc', '14', 0, '', '2015-09-15 11:13:47', '2015-09-15 11:13:47'),
('aad1f29a-a7f5-41fe-86e0-ed3ac9769db0', '7d6a4024-9482-49fb-b1ec-c9bd478a09dc', '12', 0, '', '2015-09-15 11:13:47', '2015-09-15 11:13:47');

-- --------------------------------------------------------

--
-- 表的结构 `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(40) CHARACTER SET utf32 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `role`
--

INSERT INTO `role` (`id`, `name`, `description`) VALUES
(0, 'Staff', '普通员工'),
(1, 'SysManager', '系统管理员'),
(2, 'ComManager', '普通管理员');

-- --------------------------------------------------------

--
-- 表的结构 `rs`
--

CREATE TABLE IF NOT EXISTS `rs` (
  `id` varchar(36) NOT NULL COMMENT 'rsitems的主键',
  `tag` varchar(100) NOT NULL COMMENT '标签名称，添加索引',
  `column` text NOT NULL,
  `description` text NOT NULL COMMENT '标签描述',
  `implement` varchar(10) DEFAULT NULL COMMENT '手段',
  `priority` varchar(20) NOT NULL COMMENT '优先级',
  `contribution` varchar(10) NOT NULL COMMENT '安全性',
  `category` varchar(20) NOT NULL COMMENT '类别',
  `allocation` varchar(200) NOT NULL COMMENT '分配对象',
  `vatstr_id` varchar(36) NOT NULL COMMENT '对应管理员分配的vat',
  `varstr_result` int(1) NOT NULL DEFAULT '0',
  `source_json` text NOT NULL,
  `vat_json` text NOT NULL,
  `version_id` varchar(36) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `rs`
--

INSERT INTO `rs` (`id`, `tag`, `column`, `description`, `implement`, `priority`, `contribution`, `category`, `allocation`, `vatstr_id`, `varstr_result`, `source_json`, `vat_json`, `version_id`, `created_at`, `updated_at`) VALUES
('001a1b0b-2a20-4edf-af99-e8b50f832276', '[TSP-SyRS-0073]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('0033936f-a8e2-47b9-9b14-185adfc55a36', '[TSP-SyRS-0137]', '"allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]","source":"[TSP-SyUR-0117]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('00de6b60-4fc0-4771-b98c-797b05f864a9', '[TSP-SyRS-0343]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:28'),
('0212a1c1-8833-4dbf-94bd-6caf550d4f02', '[TSP-SyRS-0007]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('0316f7ca-9cdf-42ff-9004-5efecce794b3', '[TSP-SyRS-0023]', '"implement":"1.1.0","priority":"High","contribution":"RAM","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0003]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:17'),
('0410bf80-56ac-45b4-b8f4-3a52a77f1edd', '[TSP-SyRS-0010]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('041629b1-6523-4f9a-8696-a4eb936cf494', '[TSP-SyRS-0017]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:11', '2015-10-20 11:36:11'),
('0427030e-e768-4df3-8c85-d47daa82199e', '[TSP-SyRS-0204]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0027]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('04439112-1772-4b1e-ad78-87e114ae2366', '[TSP-SyRS-0020]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:02', '2015-11-02 11:13:40'),
('04d85306-fec4-4716-8b07-1a70c7ed4f08', '[TSP-SyRS-0126]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('04e49aa1-be4d-41f3-b69e-547199bc637e', '[TSP-SyRS-0364]', '"implement":"1.2.0","priority":"High","contribution":"RAM","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0100]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:18'),
('05b29d5c-561d-4e1e-aeaf-3e23df641c56', '[TSP-SyRS-0138]', '"priority":"High","allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('05bc0e2b-7daa-45d4-abde-a2b48dba60e3', '[TSP-SyRS-0118]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('05eaca0a-5972-4976-a4f5-73c5ed709b13', '[TSP-SyRS-0138]', '"allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('06d11dd1-3e79-4b78-9bf5-98b42b330c81', '[TSP-SyRS-0073]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:27'),
('06f0a94e-8caf-40af-8e14-39ef781fa20f', '[TSP-SyRS-0122]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('078c8007-411a-4dc9-a3d8-ab992a4b1658', '[TSP-SyRS-0009]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:41'),
('084f23de-1a3e-432d-8fbd-0d59a2137161', '[TSP-SyRS-0255]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:12', '2015-11-02 11:23:24'),
('08acd136-7f1c-453b-9d00-51dc939a31b3', '[TSP-SyRS-0024]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:29'),
('09139702-52bf-4674-be99-f5375d8cc86b', '[TSP-SyRS-0004]', '"priority":"Middle","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:12', '2015-11-02 11:23:24'),
('0921c314-9131-48d4-9f59-abe6f17d8001', '[TSP-SyRS-0016]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('0956b5b2-3cdd-4b67-a948-225d62f633f9', '[TSP-SyRS-0073]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:39', '2015-10-18 18:40:17'),
('098b1904-461a-423a-bb3c-d035d71a8c74', '[TSP-SyRS-0255]', '"source":"[TSP-SyUR-0115], [TSP-SyPHA-0023]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('09b73d2b-c3cc-470a-9ab1-e915035f969c', '[TSP-SyRS-0128]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0023]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:17'),
('09dd9923-be1d-495a-9cd5-873c58760146', '[TSP-SyRS-0003]', '"source":"[TSP-SyUR-0004]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:40', '2015-10-18 21:57:46'),
('0a586c9b-9ee8-4452-8ad2-25312ee021a2', '[TSP-SyRS-0024]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('0a661bc1-d4f0-4541-b75c-83e9abda4370', '[TSP-SyRS-0116]', '"implement":"1.1.0","priority":"High","contribution":"SIL4","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0037]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:17'),
('0b50cb07-3638-4b53-bd67-d974f86edda9', '[TSP-SyRS-0128]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:42'),
('0bc9e588-66db-4f84-b7ac-5d1a331ff7ba', '[TSP-SyRS-0009]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0037], [TSP-SyUR-0101]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('0d1b3f53-259d-4cf6-8d46-6ae764e127bd', '[TSP-SyRS-0008]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0019]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:16'),
('0d233b00-c546-4650-9e22-ccf0589ae70b', '[TSP-SyRS-0327]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-SDMS-GGW-SyID]","source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('0dddeac4-ce20-4158-a446-972c0b198ab4', '[TSP-SyRS-0205]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('0e0b1d71-2740-4746-858f-e816c2328b54', '[TSP-SyRS-0343]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('0e2a475a-bd99-40f7-bb73-bbf91d5bfa95', '[TSP-SyRS-0255]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:27'),
('0ec49cf4-ffa2-410e-a716-6169ce9fa9bb', '[TSP-SyRS-0126]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('0f802818-88a6-4597-95fa-8d9620a7a96b', '[TSP-SyRS-0203]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('0f8c70cd-788e-49a2-aa39-0d89e5d83ca0', '[TSP-SyRS-0005]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('10e33694-57c8-4749-8f93-ef2669d6abfd', '[TSP-SyRS-0125]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:42'),
('10ef45af-56f0-40fa-a53b-ed1edb466cc9', '[TSP-SyRS-0203]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0025]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('1111556f-3723-4113-bd9d-a87cabf632c9', '[TSP-SyRS-0004]', '"source":"[TSP-SyUR-0001]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('1119614b-6b69-4142-883d-44e8ade776a4', '[TSP-SyRS-0017]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD], [Exported constraint]","source":"[TSP-SyUR-0019], [TSP-SyUR-0104], [TSP-SyPHA-0010], [TSP-RAMSRs-0007]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:17'),
('1270170f-019f-4ac4-b97f-e9131067217d', '[TSP-SyRS-0138]', '"allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:42', '2015-10-20 11:23:42'),
('13d0bb6a-b21a-4539-9997-848ce770c2be', '[TSP-SyRS-0001]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0001]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('1450a78c-8131-4a7e-a142-43b8288b429b', '[TSP-SyRS-0338]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('148ee4d5-b2be-4bc9-a15a-33d3a7e57562', '[TSP-SyRS-0255]', '"source":"[TSP-SyUR-0115], [TSP-SyPHA-0023]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:40', '2015-10-18 21:57:48'),
('14e8bbe6-3e65-4e67-86f2-22f7c42d9f2c', '[TSP-SyRS-0015]', '"priority":"Middle","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('14f2451c-7a27-43c5-8c80-a955cff6029b', '[TSP-SyRS-0364]', '"source":"[TSP-SyUR-0100]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:50', '2015-10-18 23:11:50'),
('1502a673-e87a-439a-87c7-cef65c990721', '[TSP-SyRS-0010]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('15d6fa54-09a4-486e-ad32-d9872e85e99c', '[TSP-SyRS-0013]', '"source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('1692c63a-f168-4fc9-b426-3a2c3fc41c22', '[TSP-SyRS-0005]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0004]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('16c545a7-928b-46db-a178-3e77e519c6eb', '[TSP-SyRS-0326]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('1707f1dc-16f8-40a2-97dd-98e3c051b3b5', '[TSP-SyRS-0003]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('1764e353-83f9-452c-8a2d-a3a3f968b7af', '[TSP-SyRS-0327]', '"source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:48'),
('183b861b-50de-4e6e-904a-a01773f19897', '[TSP-SyRS-0006]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('1904b604-d284-4b0e-b701-a8f900b8a5e8', '[TSP-SyRS-0128]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('196db538-0214-409c-ae7a-e09b03486fa1', '[TSP-SyRS-0128]', '"source":"[TSP-SyUR-0023]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:47'),
('196f7114-5c90-48a1-8621-683da2a9c836', '[TSP-SyRS-0327]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('19761852-b5fd-49eb-a6a3-1a16b635f821', '[TSP-SyRS-0127]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('19c900c2-6fca-4c9c-8301-c46e181d71f8', '[TSP-SyRS-0002]', '"allocation":"[TSP-SyAD]","source":"[TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('19f1439d-7b70-4566-aba6-1e901f5223fd', '[TSP-SyRS-0024]', '"implement":"1.1.0","priority":"High","contribution":"RAM","allocation":"[TSP-SyAD]","source":"[TSP-RAMSRs-0015]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:17'),
('1a5898c6-5bd8-418f-b1db-e5c3d64ce96c', '[TSP-SyRS-0118]', '"source":"[TSP-SyUR-0038]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:47'),
('1b22fa95-5f64-4a81-b901-e9448beb3331', '[TSP-SyRS-0327]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-SDMS-GGW-SyID]","source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:18'),
('1b924c9d-1f3a-45a2-aae0-fb85478825a3', '[TSP-SyRS-0327]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('1bf23a93-f67c-40ed-b7b9-6a502ee4c83a', '[TSP-SyRS-0125]', '"source":"[TSP-SyUR-0023]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('1bfd9915-e6fc-4233-aab9-dd191b667d0a', '[TSP-SyRS-0205]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0028], [TSP-SyPHA-0026]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('1c0a2fae-c8ae-4cd1-94a2-0777318d1233', '[TSP-SyRS-0005]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:27'),
('1c2ca112-7e2a-4e62-b9f2-2a52f3dfb296', '[TSP-SyRS-0137]', '"priority":"Middle","allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('1cb87841-7a74-4960-b641-6a1506bb036c', '[TSP-SyRS-0027]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('1cd638b0-0f39-4268-a7f0-c791d67bf7be', '[TSP-SyRS-0007]', '"source":"[TSP-SyUR-0021]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:47'),
('1d603d48-1804-47d8-a78c-b8873a4ea265', '[TSP-SyRS-0007]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('1dda9cf5-5300-44b4-98c4-f331404d2d19', '[TSP-SyRS-0004]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('1e12b033-2d86-427d-aa07-56b5d1a97936', '[TSP-SyRS-0122]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:27'),
('1ed805a1-ac2b-434e-aada-2d3061b1789a', '[TSP-SyRS-0327]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('1efb30a0-a5d4-4be0-909f-2991d1b82f65', '[TSP-SyRS-0365]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-MPS-GGW-SyID], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('1fd667bb-ab8a-429e-bf61-fd4ca11037a7', '[TSP-SyRS-0343]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('1fe64c9c-0ae3-46ce-879f-f6625e2f5107', '[TSP-SyRS-0009]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('2039d2c2-f425-440d-8889-c7dd66d1932a', '[TSP-SyRS-0256]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:42', '2015-10-20 11:23:42'),
('214c1374-da41-48f6-97d8-577afa8d00d4', '[TSP-SyRS-0120]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:09', '2015-10-20 11:36:09'),
('217bddc9-349a-4cd3-bf5d-8c8d4aecc4c2', '[TSP-SyRS-0118]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('23863797-ff50-4ccc-b491-ab0e2fc665a7', '[TSP-SyRS-0125]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('23c17c76-a6fa-407d-942e-52b7e7b06ac4', '[TSP-SyRS-0118]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:25'),
('241b1113-5700-47c5-bc34-4d6b6ff1f7f7', '[TSP-SyRS-0139]', '"priority":"Middle","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('2436ff58-f765-4b9c-b928-7b7038366671', '[TSP-SyRS-0003]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:09', '2015-10-20 11:36:09'),
('247024ff-6475-4202-81d0-d15b6999e709', '[TSP-SyRS-0027]', '"source":"[TSP-SyUR-0054]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:47'),
('2492f3ac-b8d8-46d0-8b25-608981fc8340', '[TSP-SyRS-0116]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('251bc045-0de9-4bed-8dc1-b6388478aaac', '[TSP-SyRS-0361]', '"source":"[TSP-SyUR-0032], [TSP-SyUR-0038], [TSP-SyUR-0101], [TSP-SyUR-0048], [TSP-SyUR-0117], [TSP-SyUR-0001]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:40', '2015-10-18 21:57:48'),
('2529f552-db78-47d8-aeb6-b9d4fae6fe6f', '[TSP-SyRS-0006]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:28'),
('2588f6a8-41ba-4675-acac-a78f2adc6101', '[TSP-SyRS-0022]', '"implement":"1.1.0","priority":"High","contribution":"RAM","allocation":"[TSP-SyAD]","source":"[TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:17'),
('2639ad05-bd08-4a68-a057-2cfe75d97f83', '[TSP-SyRS-0127]', '"priority":"High","allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('264522fe-7c4d-4eb1-ae37-d84b8f29101d', '[TSP-SyRS-0365]', '"priority":"High","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-MPS-GGW-SyID], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:12', '2015-11-02 11:23:24'),
('265fc12b-dc80-4505-a5bc-3092c53c3be3', '[TSP-SyRS-0122]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('26a2d7e6-8969-4e84-a520-36f68970f1f1', '[TSP-SyRS-0016]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-SDMS-GGW-SyID]","source":"[TSP-SyUR-0054], [TSP-SyUR-0115], [TSP-RAMSRs-0016], [TSP-RAMSRs-0019]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('26b64f00-5598-42c3-b927-f3e2b45a889b', '[TSP-SyRS-0006]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0049], [TSP-SyPHA-0015], [TSP-SyPHA-0017]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('26f6231f-4205-4b81-8e42-0866c6582909', '[TSP-SyRS-0007]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('27453644-fcb5-4080-ba7d-bf25f6f01859', '[TSP-SyRS-0008]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:28'),
('27fb260b-2cd1-4f7c-805d-3f4f8fb70fbc', '[TSP-SyRS-0003]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD], [Exported constraint]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:27'),
('280dde2b-a6a7-4b6c-a562-c41659e475de', '[TSP-SyRS-0256]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]","source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('2888f553-2c04-41f1-80eb-50f04f1049bf', '[TSP-SyRS-0023]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:11', '2015-10-20 11:36:11'),
('29166a3b-285e-4289-a459-52cd686c12f5', '[TSP-SyRS-0328]', '"allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('29990652-d5bd-4bff-b536-e80941a59265', '[TSP-SyRS-0027]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:39'),
('2afe8299-86a5-456b-9da7-03eca4ab498a', '[TSP-SyRS-0126]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('2b5ce88c-9aab-4da3-a728-5cffb9f08994', '[TSP-SyRS-0024]', '"allocation":"[TSP-SyAD]","source":"[TSP-RAMSRs-0015]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:49', '2015-10-18 23:15:10'),
('2e1be86d-1c3d-42b3-94db-5ea0c18d4b60', '[TSP-SyRS-0002]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:27'),
('2e74f2e4-e900-4457-9585-01ffb887b4e5', '[TSP-SyRS-0139]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('2e808656-f6f7-4961-a24c-c98ed47e5377', '[TSP-SyRS-0010]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('2fc30b7b-35fc-4611-8050-a96ec639fb7b', '[TSP-SyRS-0008]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('3046cd74-db15-4f16-a050-f49e79e5b9d4', '[TSP-SyRS-0365]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-MPS-GGW-SyID], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:40', '2015-10-20 11:23:40'),
('3050d01b-59f9-4ecc-9c84-a1781623ac86', '[TSP-SyRS-0165]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('317ce05d-edc2-4d32-ac62-568c5e97b6f7', '[TSP-SyRS-0122]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:09', '2015-10-20 11:36:09'),
('3446c2e9-16e9-4d58-97f7-3f32f3ec05e4', '[TSP-SyRS-0017]', '"priority":"High","allocation":"[TSP-SyAD], [Exported constraint]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('34d9382b-d0fa-4b4d-8294-4156aff6dfe8', '[TSP-SyRS-0127]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('3584b959-0ab4-46d4-a6b2-fdf326cb4dd1', '[TSP-SyRS-0116]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('361f1ca0-3532-4733-afbe-bdda54cb767b', '[TSP-SyRS-0016]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('3628991d-f6f5-4dca-ba18-3b926d7348a1', '[TSP-SyRS-0203]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0025]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:17'),
('3649802a-3661-4db3-b9ad-c10ee11de21f', '[TSP-SyRS-0017]', '"source":"[TSP-SyUR-0019], [TSP-SyUR-0104], [TSP-SyPHA-0010], [TSP-RAMSRs-0007]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:50', '2015-10-18 23:11:50'),
('36aee400-3f5c-4a29-8ab5-505ceae09b09', '[TSP-SyRS-0205]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:28'),
('37cdd80e-a008-4027-af87-f505c1e790e7', '[TSP-SyRS-0005]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:40', '2015-10-20 11:23:41'),
('38d918e9-f568-404e-92a3-ab34a2e68e07', '[TSP-SyRS-0137]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('38ed8c7e-ccec-4d47-aea5-0db03ad1aa59', '[TSP-SyRS-0205]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('39f6b529-a369-4c7b-9590-010fd6ed5b90', '[TSP-SyRS-0022]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:11', '2015-10-20 11:36:11'),
('3a2d9b52-1799-4c05-8004-4e0001444a6e', '[TSP-SyRS-0073]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('3ce8e807-574a-460c-968d-b62264350b8a', '[TSP-SyRS-0020]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('3d748adb-6ff6-4c01-a5b9-2dbe70647ec2', '[TSP-SyRS-0122]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:40', '2015-10-20 11:23:40'),
('3e235923-498e-4df3-b0f4-cde9289a2e62', '[TSP-SyRS-0361]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:13:59', '2015-11-02 11:13:39'),
('3e4da333-c694-46d3-949f-9ad2463e63c1', '[TSP-SyRS-0364]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('3e905bdd-6868-4e40-a1da-a3767d4527c7', '[TSP-SyRS-0206]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0029]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('3f3c6b00-0899-4376-aabf-3369bd638da2', '[TSP-SyRS-0017]', '"source":"[TSP-SyUR-0019], [TSP-SyUR-0104], [TSP-SyPHA-0010], [TSP-RAMSRs-0007]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:47'),
('3f6ec8f3-1f50-4dde-8c47-9fda50ca6423', '[TSP-SyRS-0009]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('3fae1536-6e7c-467b-b333-fa7e79952380', '[TSP-SyRS-0206]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('40c733c8-4b1c-41c6-90b2-572ce73875c3', '[TSP-SyRS-0015]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('418fa7e9-685b-49dc-8870-67fc54b0e165', '[TSP-SyRS-0326]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('424761de-1c66-449b-8660-2bdc5db990a8', '[TSP-SyRS-0126]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:42'),
('434fa976-2a94-405a-b57b-1b542a24568b', '[TSP-SyRS-0128]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('43888369-2db9-4477-aed9-7297f694a9a7', '[TSP-SyRS-0017]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:02', '2015-11-02 11:13:40'),
('43a8716a-57e7-4b60-b1df-3854c4efe20f', '[TSP-SyRS-0116]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:28'),
('43ccc358-08d4-482f-804b-64d0deab8992', '[TSP-SyRS-0002]', '"source":"[TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('43dc0b4f-ad45-49e5-a227-c377994892ed', '[TSP-SyRS-0127]', '"allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('44a1ed46-624b-48ea-b2c2-0a9b5d65e505', '[TSP-SyRS-0024]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('45283625-2527-47d9-8cad-e7e1f213fdf5', '[TSP-SyRS-0203]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('4698399c-6f85-4a03-b210-defb5e3226e8', '[TSP-SyRS-0016]', '"priority":"Middle","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('493cdc99-b167-4102-b19c-3d9c4a0166a7', '[TSP-SyRS-0328]', '"source":"[TSP-SyUR-0106]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:50', '2015-10-18 23:11:50'),
('4980e612-0672-49ca-943d-a2ff04c59e34', '[TSP-SyRS-0128]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('49d8b179-5aba-4512-82a6-384197b8f187', '[TSP-SyRS-0118]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('4b5b2142-0d30-43ab-8ff2-e227596ad8ec', '[TSP-SyRS-0020]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:11', '2015-10-20 11:36:11'),
('4bdb51f7-38e8-41c2-9e24-988abca37aab', '[TSP-SyRS-0026]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('4cf806d6-3597-45cd-aad1-87ce661cfb7f', '[TSP-SyRS-0327]', '"source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:50', '2015-10-18 23:11:50'),
('4d033adf-b60e-4c77-bd1e-523cb269e1ed', '[TSP-SyRS-0122]', '"source":"[TSP-SyPHA-0021]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:47'),
('4d71ffac-9f54-446a-865f-2733b8f8e9b0', '[TSP-SyRS-0126]', '"source":"[TSP-SyPHA-0003]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:47'),
('4d8ff00e-7c10-4f3e-8e0c-9531f00ef13e', '[TSP-SyRS-0023]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0003]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:49', '2015-10-18 23:15:10'),
('4dc319e4-5f4b-449b-a2dc-8de420b8b224', '[TSP-SyRS-0002]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:12', '2015-11-02 11:23:24'),
('4f17f71e-7eb4-42a2-9fa9-f1062c37d59c', '[TSP-SyRS-0010]', '"source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('4f3d84c9-6297-40ab-a9f7-91c56a48bfac', '[TSP-SyRS-0365]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-MPS-GGW-SyID], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:27'),
('5000321c-bb92-4701-844a-4ffeed238f83', '[TSP-SyRS-0205]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('50934475-9a08-415b-ac80-867ef99e7164', '[TSP-SyRS-0020]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('517d16f4-39c8-4e5d-9531-3d0062030d5c', '[TSP-SyRS-0125]', '"source":"[TSP-SyUR-0023]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:47'),
('51e6cc00-651f-4019-830a-f4872bbf4736', '[TSP-SyRS-0011]', '"source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:47'),
('526c2105-e123-4271-81a4-74e5e3d9e1d4', '[TSP-SyRS-0005]', '"implement":"1.1.0","priority":"High","contribution":"N/A","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0004]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:16'),
('5345ea49-6871-4ed8-827e-5225236889af', '[TSP-SyRS-0013]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('535ee5f4-7e3a-43f0-a3b0-5b353cc15d49', '[TSP-SyRS-0165]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:41'),
('53c4e6c7-9907-45f9-a573-13530fbe1d62', '[TSP-SyRS-0120]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0007], [TSP-SyPHA-0008]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:39', '2015-10-18 18:40:17'),
('546c4766-32d8-431f-a56c-943bdd6087ca', '[TSP-SyRS-0206]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('55d3089d-ec03-4019-8985-57765df96f06', '[TSP-SyRS-0016]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('560a08ca-860f-4797-8a77-723fbadc51a6', '[TSP-SyRS-0027]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]","source":"[TSP-SyUR-0054]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:17'),
('568815ba-c722-4bb4-956d-ba824714eade', '[TSP-SyRS-0022]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:42', '2015-10-20 11:23:42'),
('5724573d-9915-4b75-88c3-fe1c0ee46021', '[TSP-SyRS-0017]', '"allocation":"[TSP-SyAD], [Exported constraint]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('5745a034-25e2-494d-95cf-ee8627cc72b2', '[TSP-SyRS-0007]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:28'),
('57496840-723a-44e6-879b-f7cf367016cd', '[TSP-SyRS-0361]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:12', '2015-11-02 11:23:24'),
('57e08ec6-8ac5-4e60-87d9-f3ff988ab597', '[TSP-SyRS-0255]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:09', '2015-10-20 11:36:09'),
('584d8546-f896-4f4e-a2ce-09c9d024365f', '[TSP-SyRS-0122]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('585c5ba6-d150-40f7-a59f-1339a80eb8c6', '[TSP-SyRS-0016]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-SDMS-GGW-SyID]","source":"[TSP-SyUR-0054], [TSP-SyUR-0115], [TSP-RAMSRs-0016], [TSP-RAMSRs-0019]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:17'),
('58d528b9-bae8-4fd7-bb06-a402bfa69e72', '[TSP-SyRS-0009]', '"source":"[TSP-SyUR-0037], [TSP-SyUR-0101]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:47'),
('5968a44a-9454-4aa8-a8e0-3cfecc38fe46', '[TSP-SyRS-0015]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('5a45a273-1c93-4cda-9062-7a241fc5dd03', '[TSP-SyRS-0137]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('5a5b7676-89fe-4124-b3f2-1aed907c401f', '[TSP-SyRS-0126]', '"source":"[TSP-SyPHA-0003]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('5acb62a7-a4f2-45f6-906e-5cf9a65a46e8', '[TSP-SyRS-0013]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('5b0a6085-b8d3-4b87-87ac-33fef15a0396', '[TSP-SyRS-0020]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0030], [TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:49', '2015-10-18 23:15:10'),
('5bffd633-3135-49ed-b1e0-2057255a9762', '[TSP-SyRS-0073]', '"priority":"Middle","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:12', '2015-11-02 11:23:24'),
('5c5c53c0-8d7d-4486-91dd-8f864424e798', '[TSP-SyRS-0127]', '"source":"[TSP-SyPHA-0001], [TSP-SyPHA-0002], [TSP-SyPHA-0003], [TSP-SyPHA-0004], [TSP-SyPHA-0005]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('5ccc3cab-8c08-4403-8710-29675fd66a14', '[TSP-SyRS-0006]', '"source":"[TSP-SyUR-0049], [TSP-SyPHA-0015], [TSP-SyPHA-0017]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('5cef2590-cf8c-4548-84ed-64d15046ea24', '[TSP-SyRS-0010]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:41'),
('5d317cf4-d67c-4c48-b922-d805cc61aee6', '[TSP-SyRS-0003]', '"allocation":"[TSP-SyAD], [Exported constraint]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:40', '2015-10-20 11:23:40'),
('5d899544-4a53-4975-9702-8fbaf3e0f26b', '[TSP-SyRS-0125]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('5dcbea4f-5874-4cd4-b0cb-87abb8fe6886', '[TSP-SyRS-0338]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:41'),
('5de21faa-9f51-4e00-85a0-03a36bb50834', '[TSP-SyRS-0338]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('5e1c8643-1215-45bf-9277-1483eff1fdab', '[TSP-SyRS-0010]', '"source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:47'),
('5f101923-1641-4f8a-aef9-867e11298441', '[TSP-SyRS-0206]', '"source":"[TSP-SyPHA-0029]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:48'),
('5f1d82b0-37ff-4fe1-8a52-2338b9393760', '[TSP-SyRS-0003]', '"allocation":"[TSP-SyAD], [Exported constraint]","source":"[TSP-SyUR-0004]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('5f687b89-3fbc-4d63-9cd0-441768408a74', '[TSP-SyRS-0027]', '"priority":"Middle","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:25'),
('5fac8a9e-18d4-4f1c-8048-26dd1db96bc0', '[TSP-SyRS-0139]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0070]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:49', '2015-10-18 23:15:10'),
('60fe0604-f6e2-49ac-b1e0-fd775847b648', '[TSP-SyRS-0205]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:41'),
('61720c4f-a373-4793-86dd-1ca8551db89c', '[TSP-SyRS-0073]', '"source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:40', '2015-10-18 21:57:47'),
('61877b25-5713-43ff-86e7-e9e73ec46673', '[TSP-SyRS-0328]', '"implement":"1.1.0","priority":"High","contribution":"SIL4","allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]","source":"[TSP-SyUR-0106]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:18'),
('61a39bea-c64d-4270-9284-7ee15ef4c3ac', '[TSP-SyRS-0004]', '"source":"[TSP-SyUR-0001]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:40', '2015-10-18 21:57:46'),
('633a2b4f-2fa4-4800-8e25-f573ff97eaa2', '[TSP-SyRS-0364]', '"implement":"1.2.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:29'),
('636606df-fa28-406d-b6a2-8dfa622ad28b', '[TSP-SyRS-0328]', '"allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]","source":"[TSP-SyUR-0106]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:49', '2015-10-18 23:15:10'),
('63785b85-2d4f-4c23-9458-b40f594cb3cd', '[TSP-SyRS-0361]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:40', '2015-10-20 11:23:40'),
('645547f3-4b18-425e-8dfc-8b9015cd4594', '[TSP-SyRS-0206]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('646a169e-810b-4ce8-9298-e6883efbb8e5', '[TSP-SyRS-0009]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('6494ada8-2b5a-4b26-8aab-7f4330be8997', '[TSP-SyRS-0127]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]","source":"[TSP-SyPHA-0001], [TSP-SyPHA-0002], [TSP-SyPHA-0003], [TSP-SyPHA-0004], [TSP-SyPHA-0005]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:17'),
('64fe9d96-5143-4bef-a251-8f88f1ba5a0c', '[TSP-SyRS-0001]', '"source":"[TSP-SyUR-0001]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:40', '2015-10-18 21:57:46'),
('650ef3bf-87c7-42b7-9f4a-185395da9f8a', '[TSP-SyRS-0361]', '"implement":"1.1.0","priority":"High","contribution":"SIL4","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0032], [TSP-SyUR-0038], [TSP-SyUR-0101], [TSP-SyUR-0048], [TSP-SyUR-0117], [TSP-SyUR-0001]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:39', '2015-10-18 18:40:18'),
('659cc9fc-8e90-4d07-acf4-ade10b912e71', '[TSP-SyRS-0137]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('673482b6-be03-4a85-b9e1-91186e1f19b5', '[TSP-SyRS-0127]', '"allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:42'),
('684dafd9-ed88-42ee-9e13-dd98b9b58d47', '[TSP-SyRS-0015]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:42'),
('6880a302-aa0f-46bc-8341-31bb9df94ea9', '[TSP-SyRS-0165]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:28'),
('691050e0-e87e-4f1f-9e85-9983ca3b120b', '[TSP-SyRS-0001]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:15', '2015-10-20 11:17:15'),
('6980746d-5eda-4181-bd4e-91cb466461a0', '[TSP-SyRS-0139]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('699ada71-dd50-4ad8-9bca-cd3276caaefd', '[TSP-SyRS-0328]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:29'),
('6b047692-3d1e-4f2c-af32-3e4b985819bb', '[TSP-SyRS-0024]', '"source":"[TSP-RAMSRs-0015]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:50', '2015-10-18 23:11:50'),
('6bebd2e9-befd-4779-abd8-03b6daf191fc', '[TSP-SyRS-0024]', '"source":"[TSP-RAMSRs-0015]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:47'),
('6c238264-b28d-4ef5-8e33-69a6c0d594c5', '[TSP-SyRS-0120]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:12', '2015-11-02 11:23:24'),
('6ceee3bd-c363-4458-b37d-806a688f6944', '[TSP-SyRS-0137]', '"allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('6d33b763-4333-4376-b9ee-49436399ad61', '[TSP-SyRS-0027]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]","source":"[TSP-SyUR-0054]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('6d5e325f-b801-4f50-bb78-5338ead0e961', '[TSP-SyRS-0205]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('6dfca70b-f644-46a8-8e6e-99846114dd39', '[TSP-SyRS-0008]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:40', '2015-10-20 11:23:41');
INSERT INTO `rs` (`id`, `tag`, `column`, `description`, `implement`, `priority`, `contribution`, `category`, `allocation`, `vatstr_id`, `varstr_result`, `source_json`, `vat_json`, `version_id`, `created_at`, `updated_at`) VALUES
('6e0a82b4-71fc-45f3-b475-a7755cbd2ea7', '[TSP-SyRS-0137]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]","source":"[TSP-SyUR-0117]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:17'),
('6e3b467e-9c37-4803-8511-e6b9b85973e3', '[TSP-SyRS-0004]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:40', '2015-10-20 11:23:40'),
('6e4574f1-42ef-4ad0-b7bf-f091554d77f7', '[TSP-SyRS-0204]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0027]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:17'),
('6efcf4f8-0fc8-4fe6-bd78-a2e854e88e3e', '[TSP-SyRS-0165]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('6f09e635-92a7-4c88-821d-84b8f4ec99d8', '[TSP-SyRS-0125]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:25'),
('6f75d610-9da7-4c5a-a93c-e06cffbab1a7', '[TSP-SyRS-0125]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0023]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:17'),
('6f8f4b1f-405e-4eb3-8355-b13d99f7c6bb', '[TSP-SyRS-0009]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('705737c7-ca8e-4e1b-af3b-b0bc54a55c0d', '[TSP-SyRS-0026]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('70819c23-f6d6-47cb-8146-c1df9ad8da48', '[TSP-SyRS-0127]', '"source":"[TSP-SyPHA-0001], [TSP-SyPHA-0002], [TSP-SyPHA-0003], [TSP-SyPHA-0004], [TSP-SyPHA-0005]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:47'),
('710a0f09-3e10-4ef9-803a-3834cc28d499', '[TSP-SyRS-0023]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('715d147d-face-4218-b178-845645df1c79', '[TSP-SyRS-0120]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('71978187-f78f-4a09-939e-ab90a0fec812', '[TSP-SyRS-0011]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:28'),
('7290c9c4-dd59-4f71-b3d6-30e7e0e2842d', '[TSP-SyRS-0122]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0021]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:39', '2015-10-18 18:40:17'),
('72e9494a-83cd-4ca4-ab19-8a29d57a909a', '[TSP-SyRS-0365]', '"source":"[TSP-SyUR-0004]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:40', '2015-10-18 21:57:48'),
('730b5489-5edc-4145-9c11-8c4e0d085007', '[TSP-SyRS-0023]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:42', '2015-10-20 11:23:42'),
('730fe975-e322-40b3-8512-07445eabacaa', '[TSP-SyRS-0016]', '"source":"[TSP-SyUR-0054], [TSP-SyUR-0115], [TSP-RAMSRs-0016], [TSP-RAMSRs-0019]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:47'),
('738a4153-3e2f-49eb-b640-07fee06ffc12', '[TSP-SyRS-0017]', '"allocation":"[TSP-SyAD], [Exported constraint]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:42', '2015-10-20 11:23:42'),
('73f74d49-d886-46a2-bdf1-2170f89c6305', '[TSP-SyRS-0127]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('74c64cc1-c645-45a5-9da9-3e6d01159c2e', '[TSP-SyRS-0120]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:27'),
('757624e6-4868-4c43-8458-bbcfdf57d5bc', '[TSP-SyRS-0011]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('766c71f8-9119-4015-8612-95e8a72d3b5a', '[TSP-SyRS-0017]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD], [Exported constraint]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:29'),
('7764f07f-4cb4-463f-84d7-ed0c73b2b95d', '[TSP-SyRS-0361]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:09', '2015-10-20 11:36:09'),
('7776553c-a91f-424d-8a26-77097aad947b', '[TSP-SyRS-0006]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:40', '2015-10-20 11:23:41'),
('779fad71-2d1f-4d9f-9f88-eb9670b67aec', '[TSP-SyRS-0206]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('77baf51c-c56c-4eba-b695-abe32196ac78', '[TSP-SyRS-0184]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('781757dc-ced1-4610-90e3-bc42b236c16e', '[TSP-SyRS-0011]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('796ac5f9-3297-43c2-a08b-30f782c866ac', '[TSP-SyRS-0003]', '"allocation":"[TSP-SyAD], [Exported constraint]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('7a5916cc-044d-41b2-b157-f85cc43c0646', '[TSP-SyRS-0116]', '"source":"[TSP-SyUR-0037]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('7aaa911f-0f33-40c3-8ee9-25b46ea6b353', '[TSP-SyRS-0027]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('7b0d8581-706e-403a-bda8-03a28dc3c9a8', '[TSP-SyRS-0364]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0100]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:49', '2015-10-18 23:15:10'),
('7b69542b-a12e-4184-a070-c989626bcde2', '[TSP-SyRS-0022]', '"source":"[TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:50', '2015-10-18 23:11:50'),
('7b8ca144-1da8-4a46-a899-73ba954a2e7f', '[TSP-SyRS-0139]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:29'),
('7bae006e-85a4-459b-8220-84166d9ee408', '[TSP-SyRS-0001]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:40', '2015-10-20 11:23:40'),
('7bce20b2-d765-476d-92dd-80aa3a9ac38a', '[TSP-SyRS-0364]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('7c0369e4-0934-4bc5-a3e2-e91bcd5d309f', '[TSP-SyRS-0365]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-MPS-GGW-SyID], [TSP-SDMS-GGW-SyID]","source":"[TSP-SyUR-0004]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('7c19be8a-b8ef-4dc3-8561-df72fcd7fd0e', '[TSP-SyRS-0255]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:40', '2015-10-20 11:23:40'),
('7d60f0cc-e432-4654-9c38-9ae7d74a8d05', '[TSP-SyRS-0326]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('7dac821d-123e-4f86-90e0-cd9a5c02c87e', '[TSP-SyRS-0343]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('7df9fa9a-c5ae-44e3-a58f-85fe52ecd3d7', '[TSP-SyRS-0204]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:41'),
('7e1d2081-5607-43ac-9bc1-8a93eee64fbd', '[TSP-SyRS-0364]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:02', '2015-11-02 11:13:40'),
('7efee252-5e6a-424f-9b20-79a76147bd9b', '[TSP-SyRS-0116]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:41'),
('81167dbb-af4c-4905-8437-70889bf6ffff', '[TSP-SyRS-0138]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('8186fa55-b4f9-42f1-8bb1-c8146633a5cd', '[TSP-SyRS-0022]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('8195a4e9-6148-4b30-9c9f-273fb7dbd583', '[TSP-SyRS-0206]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('81dd2bd4-125f-4e7a-8af5-4ac3273dab94', '[TSP-SyRS-0128]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('821b1b59-905b-4d3d-ad77-bd2c7f6cab33', '[TSP-SyRS-0015]', '"source":"[TSP-SyUR-0054]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('82205c74-b30b-4abf-8ba9-8b1500ffacf0', '[TSP-SyRS-0165]', '"source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:47'),
('8382dac6-c1a9-4293-8334-3dda2d7b164e', '[TSP-SyRS-0008]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:09', '2015-10-20 11:36:09'),
('8390c675-17d1-4a18-ae98-ab20e0c5437c', '[TSP-SyRS-0017]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('83af129d-0832-45b1-8e77-d65e3e3ef8b1', '[TSP-SyRS-0138]', '"source":"[TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:50', '2015-10-18 23:11:50'),
('84a8f434-e98c-459a-9875-7e7dc3ebec2e', '[TSP-SyRS-0184]', '"source":"[TSP-SyUR-0038], [TSP-SyUR-0054]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:47'),
('85371e31-1733-4ee4-9066-1111b8c1567c', '[TSP-SyRS-0026]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('85f2d2dd-5d54-4a7e-ac5d-4374b1c5ac7d', '[TSP-SyRS-0020]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('86001a70-de14-4507-a976-a5af86ee2907', '[TSP-SyRS-0204]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:28'),
('8635ab8f-f4a7-4031-9c63-afc88fcd438c', '[TSP-SyRS-0015]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]","source":"[TSP-SyUR-0054]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:17'),
('86a679a1-0605-47cb-9070-c6f04ee4a5e5', '[TSP-SyRS-0118]', '"source":"[TSP-SyUR-0038]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('87d383f5-0dbd-486f-9c60-4a5c93e5db32', '[TSP-SyRS-0343]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('885aee28-4630-49ed-83e3-73284c45735b', '[TSP-SyRS-0007]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0021]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:16'),
('895826ec-7c97-4b78-8ee8-591eb80bbfc9', '[TSP-SyRS-0365]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:09', '2015-10-20 11:36:09'),
('89c40ed2-d70e-40ae-8b4c-09e1597f7355', '[TSP-SyRS-0003]', '"source":"[TSP-SyUR-0004]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('89d3625e-d871-4140-94eb-cbfcdbdeeecb', '[TSP-SyRS-0256]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('89ed516c-e66f-424c-a4d7-8d40cd02df66', '[TSP-SyRS-0326]', '"allocation":"[TSP-SyAD], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:42', '2015-10-20 11:23:42'),
('8a2c9c3a-062d-4447-89c4-43b66510373c', '[TSP-SyRS-0165]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('8a3711cd-49ab-4b8b-ac3d-afdae915ca52', '[TSP-SyRS-0027]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:41'),
('8a847007-e955-4aee-a2e9-7dafaaa39640', '[TSP-SyRS-0122]', '"source":"[TSP-SyPHA-0021]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('8b2788de-a938-4b88-bbac-a21f797d3ea6', '[TSP-SyRS-0023]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:02', '2015-11-02 11:13:40'),
('8c577332-2fac-4419-8563-a82ce061b06c', '[TSP-SyRS-0326]', '"allocation":"[TSP-SyAD], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('8c61bbfa-e22c-4fe6-ac2d-4e5ca002e09d', '[TSP-SyRS-0118]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('8d0835fc-4d31-4e7e-b199-e0ae50d6daa6', '[TSP-SyRS-0118]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('8e429cd0-8a2c-4003-b780-220fd875466c', '[TSP-SyRS-0027]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('8ffa0648-3b41-4eda-9ccd-37d7937f5bb7', '[TSP-SyRS-0007]', '"source":"[TSP-SyUR-0021]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('9123ffb2-a6c5-11e4-b3f2-2eb11a8cf52a', '[TSP-SyRS-0004]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0001]"', 'Safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n', ' 1.1.0', 'Average', ' SIL0', ' Functional', ' [TSP-SyAD]\r\n', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2014-12-09 03:50:31', '2015-10-17 16:07:39'),
('9123ffb2-a6c5-11e4-b3f2-2eb1e18cf52a', '[TSP-SyRS-0013]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]","source":"[TSP-SyUR-0115]"', 'Safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n', ' 1.1.0', 'Average', ' SIL0', ' Functional', ' [TSP-SyAD]\r\n', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2014-12-09 03:50:31', '2015-10-17 16:07:41'),
('9123ffb2-a6c5-11e4-b3f2-2eb1ea8cf52a', '[TSP-SyRS-0003]', '"implement":"1.1.0","priority":"High","contribution":"N/A","allocation":"[TSP-SyAD], [Exported constraint]","source":"[TSP-SyUR-0004]"', 'Safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n', ' 1.1.0', 'Average', ' SIL0', ' Functional', ' [TSP-SyAD]\r\n', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2014-12-09 03:50:31', '2015-10-17 16:07:39'),
('9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52a', '[TSP-SyRS-0002]', '"implement":"1.1.0","priority":"High","contribution":"RAM","allocation":"[TSP-SyAD]","source":"[TSP-RAMSRs-0010]"', 'Safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n', ' 1.1.0', 'Average', ' SIL0', ' Functional', ' [TSP-SyAD]\r\n', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2014-12-09 03:50:31', '2015-10-17 16:07:39'),
('9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b', '[TSP-SyRS-0001]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0001]"', 'Trackside safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n', ' 1.1.0', 'High', ' SIL0', ' Functional', ' [TSP-SyAD]\r\n', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2014-12-09 03:50:31', '2015-10-17 16:07:39'),
('91934398-63a3-47b5-81bc-ac07fa412ec1', '[TSP-SyRS-0256]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]","source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:18'),
('91cc23d0-22e2-42a2-9423-0fe15c510d11', '[TSP-SyRS-0184]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]","source":"[TSP-SyUR-0038], [TSP-SyUR-0054]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('923ed834-a7e3-4fd8-aa80-426f51e70a39', '[TSP-SyRS-0022]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('92ae57b9-1a02-4ed5-a6c8-37c9e485715c', '[TSP-SyRS-0138]', '"allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]","source":"[TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('94b0dd90-386b-4d5a-8ab0-538db6ef60bf', '[TSP-SyRS-0009]', '"implement":"1.1.0","priority":"High","contribution":"SIL4","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0037], [TSP-SyUR-0101]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:16'),
('9522db25-75db-4040-8256-0fbe23bf2a1f', '[TSP-SyRS-0203]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:39'),
('976774c7-0a95-45f1-80dc-1530c51e7dea', '[TSP-SyRS-0165]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:17'),
('976b6891-cb27-48e5-a6ec-c7292cc071f2', '[TSP-SyRS-0002]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:15', '2015-10-20 11:17:15'),
('97c235d1-b113-41e7-ae06-46282fb490f4', '[TSP-SyRS-0206]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:41'),
('983f5214-fef9-4ab1-83c0-e836802c8f6f', '[TSP-SyRS-0203]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('9896a951-cf46-4a0d-b590-b467c5202517', '[TSP-SyRS-0204]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('99820a64-28b9-4fc0-be4d-d8e6ba870c75', '[TSP-SyRS-0003]', '"priority":"High","allocation":"[TSP-SyAD], [Exported constraint]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:12', '2015-11-02 11:23:24'),
('9a7a4129-01d4-468a-a8e8-d3316c777890', '[TSP-SyRS-0326]', '"source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:48'),
('9bda3bdc-1f88-4bbf-87c0-b5abefccfe87', '[TSP-SyRS-0073]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:13:59', '2015-11-02 11:13:39'),
('9bf69eb8-b780-42b7-b130-bcd9455c953d', '[TSP-SyRS-0343]', '"source":"[TSP-SyUR-0101]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:48'),
('9c593ba4-0926-4588-920d-5efd9c7d7d7b', '[TSP-SyRS-0022]', '"source":"[TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:47'),
('9c7d80ef-ad10-482b-a0fb-5ca844245d5c', '[TSP-SyRS-0139]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0070]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:17'),
('9cbbaa0e-e10d-47ba-968a-613359edbd34', '[TSP-SyRS-0126]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0003]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:17'),
('9d732f6c-6c30-4b52-8adf-b822239ae298', '[TSP-SyRS-0011]', '"priority":"Middle","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('9e887ca5-7617-4410-8a20-8e8763c84b3c', '[TSP-SyRS-0139]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('9ea938d5-9800-4c27-9dd0-366a9b0f4e77', '[TSP-SyRS-0338]', '"source":"[TSP-SyUR-0097]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:48'),
('9f8aa891-fdfd-4166-a38c-d659798128c9', '[TSP-SyRS-0139]', '"source":"[TSP-SyUR-0070]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:50', '2015-10-18 23:11:50'),
('9fd4547a-ad3a-4b4d-a42d-dde64fff1945', '[TSP-SyRS-0326]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD], [TSP-SDMS-GGW-SyID]","source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:18'),
('9ffbe8ee-5eb2-4a27-80d8-7dcc11482cfa', '[TSP-SyRS-0206]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0029]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:17'),
('a08c59b5-ba27-4207-a392-ceea935a6131', '[TSP-SyRS-0326]', '"allocation":"[TSP-SyAD], [TSP-SDMS-GGW-SyID]","source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('a09eaba3-9e1c-4b62-81d8-980adeed49c0', '[TSP-SyRS-0005]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:09', '2015-10-20 11:36:09'),
('a0a2d29a-71a2-4157-be66-6ee0a13dbe76', '[TSP-SyRS-0126]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('a13d5570-0cfa-45b7-ab9b-3e2ee9802f0d', '[TSP-SyRS-0120]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('a173a9db-4482-440a-93b9-5a5c45759af5', '[TSP-SyRS-0027]', '"source":"[TSP-SyUR-0054]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('a1a28e54-4302-4bdb-a4d3-fa2d898dbe6c', '[TSP-SyRS-0008]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('a253e312-34a5-422a-b5b5-15c757c5abcf', '[TSP-SyRS-0004]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0001]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('a2b280d5-a466-4155-b425-a8bc3c8f1bdb', '[TSP-SyRS-0116]', '"source":"[TSP-SyUR-0037]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:47'),
('a2f8f8e8-3045-47b4-a832-6a852456d9f5', '[TSP-SyRS-0184]', '"priority":"High","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:25'),
('a447ec8e-3548-4a38-88ff-60f31d8c59d5', '[TSP-SyRS-0118]', '"implement":"1.1.0","priority":"High","contribution":"SIL4","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0038]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:17'),
('a4a6482e-5f10-497f-81d5-205bfd57d7ca', '[TSP-SyRS-0127]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('a5129f03-6ab2-4576-8065-6992506b8daa', '[TSP-SyRS-0015]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('a5c032f4-62e0-4b3d-b7f3-71fb21428580', '[TSP-SyRS-0203]', '"source":"[TSP-SyPHA-0025]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('a5ff7096-fc76-478a-a6bc-d572806aaf0b', '[TSP-SyRS-0009]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:28'),
('a619f5a0-2801-47a6-9f0d-55f7a6c24c9f', '[TSP-SyRS-0001]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:09', '2015-10-20 11:36:09'),
('a62450fb-aebc-48fd-8af7-f348b68cfc7e', '[TSP-SyRS-0184]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('a6ed0908-d297-40cf-8b5f-d3f69bc757e2', '[TSP-SyRS-0026]', '"source":"[TSP-SyUR-0003]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('a75d2911-626e-4387-be5a-fd7640ca1c17', '[TSP-SyRS-0204]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('a7ac70f3-771d-4dc7-b60c-ee59a8913c36', '[TSP-SyRS-0005]', '"source":"[TSP-SyUR-0004]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('a7f4be0f-3445-4f77-a217-215f4c4fd436', '[TSP-SyRS-0338]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0097]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:09'),
('a829800a-5150-44df-b7ab-0c28cbe887fd', '[TSP-SyRS-0026]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:28'),
('a89d1b3b-298e-4540-87b5-9ec007c36c80', '[TSP-SyRS-0256]', '"priority":"Middle","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('a8adefdb-6b5b-4d31-a0d8-d370c85f4cc8', '[TSP-SyRS-0015]', '"source":"[TSP-SyUR-0054]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:47'),
('a93bf8a5-c2f9-4bdb-930c-0a727a66d9ea', '[TSP-SyRS-0204]', '"source":"[TSP-SyPHA-0027]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:48'),
('a9687652-fa4e-409c-8b11-f26808015937', '[TSP-SyRS-0203]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:41'),
('a9d5d048-0c89-4286-b0b6-7c1bd049062f', '[TSP-SyRS-0026]', '"implement":"1.1.0","priority":"High","contribution":"RAM","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0003]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:17'),
('aa247d9a-68b6-4169-ab33-a4498d42de95', '[TSP-SyRS-0206]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('aab6eddd-482c-44c4-bda4-72a9414baaa9', '[TSP-SyRS-0203]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('ac16f2be-3a67-49e8-b96f-89f106d22f80', '[TSP-SyRS-0023]', '"source":"[TSP-SyUR-0003]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:50', '2015-10-18 23:11:50'),
('ac608522-1730-4aa4-932a-52f97c2bf9e2', '[TSP-SyRS-0204]', '"source":"[TSP-SyPHA-0027]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('acb51f8a-75a7-4981-af05-a1dc1553ef9b', '[TSP-SyRS-0139]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:42', '2015-10-20 11:23:42'),
('acd144d2-85b4-41f8-a878-e319e9a11c76', '[TSP-SyRS-0205]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('ada0c863-b7d2-4bd4-8dff-319ec9be406a', '[TSP-SyRS-0203]', '"source":"[TSP-SyPHA-0025]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:48'),
('ae6a43c2-4aef-43f8-8804-618af98b935f', '[TSP-SyRS-0016]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('ae9288ad-d51b-4643-a6b8-a94eef4b091e', '[TSP-SyRS-0015]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('aebee09e-62d1-43f3-bbfe-7752ffd41041', '[TSP-SyRS-0328]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:02', '2015-11-02 11:13:40'),
('af433ad7-aa95-4227-b609-2adc8b96e7b7', '[TSP-SyRS-0003]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:13:59', '2015-11-02 11:13:39'),
('af928ff7-7618-4482-9bfb-6b4efa28e0ef', '[TSP-SyRS-0125]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('b0b8d0d2-7bb9-423f-b959-0eb074f8c035', '[TSP-SyRS-0006]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('b0d2e17f-175b-45a4-a62b-cd3c3a05f398', '[TSP-SyRS-0327]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('b112dc38-eed4-4c66-9e3b-df925de9458a', '[TSP-SyRS-0255]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('b17b9df5-22b5-4d71-b226-a450d171aba5', '[TSP-SyRS-0009]', '"source":"[TSP-SyUR-0037], [TSP-SyUR-0101]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('b1a29c15-006d-46a1-87a0-03a1e8827b06', '[TSP-SyRS-0328]', '"source":"[TSP-SyUR-0106]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:48'),
('b1c2607d-27fa-472f-89cf-cc1ec8143501', '[TSP-SyRS-0128]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('b1c3ab45-8b37-4cc1-9b96-325d8d01d0c1', '[TSP-SyRS-0256]', '"source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:48'),
('b1dceddd-4ac7-4b49-81fa-49859289cf05', '[TSP-SyRS-0328]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:11', '2015-10-20 11:36:11'),
('b2931a45-ba98-4262-aa3c-16301f595ab6', '[TSP-SyRS-0328]', '"priority":"High","allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('b355f5e6-11f6-4cea-a370-c603f039d107', '[TSP-SyRS-0137]', '"source":"[TSP-SyUR-0117]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:50', '2015-10-18 23:11:50'),
('b3d1f257-ac90-4193-a71e-4b3d4cce0304', '[TSP-SyRS-0138]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('b44d28bf-ac7f-45ae-848f-e7db43877192', '[TSP-SyRS-0126]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('b57f2153-29f3-4fb0-b97d-091c858f0f53', '[TSP-SyRS-0026]', '"source":"[TSP-SyUR-0003]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:47'),
('b63f856d-f78b-4f15-925b-a64a5bc8e475', '[TSP-SyRS-0073]', '"source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('b65651b0-b9be-401a-b11b-4a1057d2f739', '[TSP-SyRS-0184]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('b75f1ce1-0d44-4616-af66-bb83ef0ad02d', '[TSP-SyRS-0365]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:15', '2015-10-20 11:17:15'),
('b7c4a746-58c4-498a-9449-7b5c011b9002', '[TSP-SyRS-0010]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('b7fe1f01-f74e-4040-b635-f21b97f006ec', '[TSP-SyRS-0013]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('b8115eda-6ea5-4052-9ec7-0863a82ad49e', '[TSP-SyRS-0328]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('b83383d5-aaf3-4a2c-9bfc-e7b6d9508426', '[TSP-SyRS-0010]', '"priority":"Middle","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('b856f7ef-329d-480c-a128-7030f198ebea', '[TSP-SyRS-0006]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('b8aa26a6-7673-4326-974a-9d69a72f8d76', '[TSP-SyRS-0138]', '"source":"[TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:47'),
('b8d543fa-fab5-49b3-964f-832e50e5674e', '[TSP-SyRS-0023]', '"source":"[TSP-SyUR-0003]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:47'),
('b903e224-8952-4a84-a540-b27aeadc1462', '[TSP-SyRS-0365]', '"source":"[TSP-SyUR-0004]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('b91b14c8-dde2-4bf9-93e0-34ef57a893b1', '[TSP-SyRS-0328]', '"allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:42', '2015-10-20 11:23:42'),
('ba5338d8-3cf5-40de-9082-ff4be9470e5b', '[TSP-SyRS-0361]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('ba960bef-ccc5-441d-828f-4aca224dfc84', '[TSP-SyRS-0255]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('baa1c631-5979-4d9e-931c-80ba9de1b732', '[TSP-SyRS-0011]', '"source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('bab87e7b-5d2f-42ec-8348-d08d0de8f8ec', '[TSP-SyRS-0011]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('baf05109-fd5f-4d59-a988-4bddc8c33771', '[TSP-SyRS-0011]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:16'),
('bba89396-39ee-437f-9dcf-9bb8d4e323f3', '[TSP-SyRS-0365]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:13:59', '2015-11-02 11:13:39'),
('bbd2beef-562b-476b-a1a2-8b455dec9fa3', '[TSP-SyRS-0073]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:40', '2015-10-20 11:23:40'),
('bbfcf506-7b59-4de9-99f5-1490ec329a6b', '[TSP-SyRS-0016]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:42', '2015-10-20 11:23:42'),
('bc4b5a3f-6a30-4b1c-90ca-6270cadfb833', '[TSP-SyRS-0204]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('bc56937c-5372-4246-a4b5-2c9b47d982f2', '[TSP-SyRS-0026]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('bd1304ea-b246-4fa4-8f69-1492c12a4641', '[TSP-SyRS-0016]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('bd4864c7-3ff5-4d7d-8083-8fc2c8393fc6', '[TSP-SyRS-0364]', '"source":"[TSP-SyUR-0100]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:48'),
('bdc2f2b9-2bd7-4fe2-b839-4e786a7a1b5f', '[TSP-SyRS-0006]', '"source":"[TSP-SyUR-0049], [TSP-SyPHA-0015], [TSP-SyPHA-0017]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:46'),
('bde94624-8366-40de-9e90-fb3655cd0afb', '[TSP-SyRS-0013]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('be1546ee-32c7-4f3d-80fb-57696d72830e', '[TSP-SyRS-0361]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0032], [TSP-SyUR-0038], [TSP-SyUR-0101], [TSP-SyUR-0048], [TSP-SyUR-0117], [TSP-SyUR-0001]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('be8720d4-adae-4ce0-ac09-203d4bc1b684', '[TSP-SyRS-0008]', '"source":"[TSP-SyUR-0019]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('bf33d5e6-9c48-4f86-ae1d-e6e2f7a10934', '[TSP-SyRS-0024]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('bf642a26-05a8-454b-9979-be61d714b3ad', '[TSP-SyRS-0013]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('bfd88b09-7207-4347-82d2-72006090980a', '[TSP-SyRS-0024]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:42', '2015-10-20 11:23:42'),
('bff16fd0-e3bf-4775-aed8-77ff1bb60535', '[TSP-SyRS-0116]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0037]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('c0e53408-b04a-4df7-ac50-df913fefc409', '[TSP-SyRS-0023]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:29'),
('c18bf4d8-297e-4b0b-a68f-28b9d22af2bb', '[TSP-SyRS-0255]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('c2097cc1-e4a1-4306-8a13-ccedd96c4eb9', '[TSP-SyRS-0184]', '"source":"[TSP-SyUR-0038], [TSP-SyUR-0054]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('c2ea1c11-d27b-49c8-a054-e37f553e43be', '[TSP-SyRS-0338]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('c2f9d372-a447-4511-b7ed-10ef5fc83333', '[TSP-SyRS-0015]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('c31438fa-7936-4697-a9ac-dae5edee1258', '[TSP-SyRS-0165]', '"priority":"Middle","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('c40d21fc-3040-4083-9038-cbcd49f37f71', '[TSP-SyRS-0001]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:27'),
('c490615a-a931-44f8-a45d-97439c7c8785', '[TSP-SyRS-0010]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:28'),
('c4a580dc-43d4-47bb-a579-8fb52a92e6aa', '[TSP-SyRS-0120]', '"source":"[TSP-SyPHA-0007], [TSP-SyPHA-0008]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:40', '2015-10-18 21:57:47'),
('c551e85e-3eee-49dc-afcc-fe1047d9da2c', '[TSP-SyRS-0137]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('c55b9e02-4ea8-498e-8902-9b2a70fd5d12', '[TSP-SyRS-0137]', '"source":"[TSP-SyUR-0117]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:47'),
('c57feec4-6eed-46bb-b307-cbe26bfc360c', '[TSP-SyRS-0256]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('c629e6e2-6245-4333-855a-729a6e5add9e', '[TSP-SyRS-0122]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:12', '2015-11-02 11:23:24'),
('c6927be5-8e70-45be-8788-e024aa5a1e8e', '[TSP-SyRS-0364]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('c6dd76de-30ea-4921-bad6-9f4733a0331a', '[TSP-SyRS-0138]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('c778733d-acc9-4ea1-871f-44de515ace19', '[TSP-SyRS-0008]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0019]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('c7b6974f-2e78-44e7-beff-07f4f8e38eba', '[TSP-SyRS-0007]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('c7bfef14-ab66-4c42-b450-cf55ffbed0ac', '[TSP-SyRS-0128]', '"source":"[TSP-SyUR-0023]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('c8b7ff82-af9c-47c8-b935-53613ede85b1', '[TSP-SyRS-0338]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('c8cb6db9-7430-452d-a602-079eb8fa83e1', '[TSP-SyRS-0120]', '"source":"[TSP-SyPHA-0007], [TSP-SyPHA-0008]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('c983086a-77d9-4a74-9fa0-2a3401109d04', '[TSP-SyRS-0006]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0049], [TSP-SyPHA-0015], [TSP-SyPHA-0017]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:16'),
('c9bde591-027b-4d62-a17c-08575fe1882a', '[TSP-SyRS-0184]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:41'),
('ca3012db-a4f5-4fb4-8352-24f72fcd8488', '[TSP-SyRS-0010]', '"implement":"1.1.0","priority":"Middle","contribution":"SIL0","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:16'),
('caf04b1d-8607-4fd9-9b45-264ba87e2ac8', '[TSP-SyRS-0015]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]","source":"[TSP-SyUR-0054]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('cb042865-0d94-4283-b5cf-8a60f72d9670', '[TSP-SyRS-0024]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('cb1c1702-2583-4eee-acb2-5435d04b828d', '[TSP-SyRS-0022]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:02', '2015-11-02 11:13:40'),
('cd74c26b-18a0-41a1-9742-ee030b5a0634', '[TSP-SyRS-0361]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:27');
INSERT INTO `rs` (`id`, `tag`, `column`, `description`, `implement`, `priority`, `contribution`, `category`, `allocation`, `vatstr_id`, `varstr_result`, `source_json`, `vat_json`, `version_id`, `created_at`, `updated_at`) VALUES
('cd9b0797-9ed0-42ca-bf41-220ac01f0c79', '[TSP-SyRS-0024]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('cdf63017-f2ac-4471-98b0-f29795572488', '[TSP-SyRS-0139]', '"source":"[TSP-SyUR-0070]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:47'),
('cdfb1c7f-8314-4365-9c4d-a6096ddb508b', '[TSP-SyRS-0004]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('cefa4164-1752-4363-beea-fbb384842de1', '[TSP-SyRS-0255]', '"implement":"1.1.0","priority":"High","contribution":"SIL4","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0115], [TSP-SyPHA-0023]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:39', '2015-10-18 18:40:17'),
('cf6d1355-62b5-4f7d-a9d5-01080bfa3edf', '[TSP-SyRS-0020]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:29'),
('cf9df975-c255-4f61-89da-84f20fc429be', '[TSP-SyRS-0116]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('cfa69744-2ce0-40c4-8e9f-a91b5ee2c2a3', '[TSP-SyRS-0027]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('d017fa1d-fd1a-4fb8-89cc-149224c9c0ec', '[TSP-SyRS-0327]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('d0a938b4-b779-4f5c-bbd5-1b732c8f6140', '[TSP-SyRS-0011]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('d0b10a18-c420-473d-a86a-dd5f3ea25760', '[TSP-SyRS-0364]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:42', '2015-10-20 11:23:42'),
('d110d0d3-9f80-436d-a1d4-6e1c7334f507', '[TSP-SyRS-0004]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:09', '2015-10-20 11:36:09'),
('d3031955-0622-4718-ae44-9eac0f8fcb1a', '[TSP-SyRS-0138]', '"implement":"1.1.0","priority":"High","contribution":"RAM","allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]","source":"[TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:17'),
('d34ac392-166f-426c-a78d-0700d23298c7', '[TSP-SyRS-0002]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:09', '2015-10-20 11:36:09'),
('d35e153c-5bb9-40ca-be1e-9e51e1e14d7f', '[TSP-SyRS-0020]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:42', '2015-10-20 11:23:42'),
('d36bdac8-9de2-4f65-b4f6-5338bbb8a65f', '[TSP-SyRS-0118]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:42'),
('d3827d2f-e38c-48ef-820c-c07a7382612c', '[TSP-SyRS-0116]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('d3eaeb7b-fe96-4eb1-84aa-0ea48a3515ca', '[TSP-SyRS-0338]', '"implement":"1.2.0","priority":"High","contribution":"SIL4","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0097]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:18'),
('d43cbbe2-39ed-47e6-a1e6-420545da5e92', '[TSP-SyRS-0004]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:27'),
('d47a40d2-4c80-42e5-bd5d-8e6ccf237d71', '[TSP-SyRS-0165]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('d49cee25-0fa2-46fa-b250-11a4ebbedd9e', '[TSP-SyRS-0013]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:42'),
('d4e018ac-7d7e-4995-8862-6b0d368d8367', '[TSP-SyRS-0326]', '"priority":"Middle","allocation":"[TSP-SyAD], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('d641c7c1-2b1c-4d3f-bc84-b7e4daa8834d', '[TSP-SyRS-0022]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:29'),
('d750ecde-2852-420f-849c-ce53aa43634a', '[TSP-SyRS-0256]', '"source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('d77beb97-24ee-4b27-913a-de10a0d60139', '[TSP-SyRS-0126]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0003]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('d844e185-e59b-4f11-8026-bdef644281ec', '[TSP-SyRS-0327]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:42', '2015-10-20 11:23:42'),
('d8ac05d1-9d09-45bf-9631-e0b0e89f0ce4', '[TSP-SyRS-0255]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0115], [TSP-SyPHA-0023]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('d9635a14-26b8-4b46-a5b0-3dfebf385785', '[TSP-SyRS-0137]', '"allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:42', '2015-10-20 11:23:42'),
('d9f6b9d8-adb5-4824-8a62-39a5b54cb113', '[TSP-SyRS-0008]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('da44e70d-da72-4b23-bcf0-c19a1c27492d', '[TSP-SyRS-0343]', '"implement":"1.1.0","priority":"High","contribution":"SIL4","allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0101]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:18'),
('da62b739-5d1a-4179-bc16-ed1f48da957b', '[TSP-SyRS-0016]', '"source":"[TSP-SyUR-0054], [TSP-SyUR-0115], [TSP-RAMSRs-0016], [TSP-RAMSRs-0019]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('daaa1d18-108e-4d5b-bf36-f27896bb9d26', '[TSP-SyRS-0023]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('db32438b-f192-4a16-adbb-993447fe2531', '[TSP-SyRS-0343]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('db8b1260-cfd4-408a-a325-2ed2c7d4f558', '[TSP-SyRS-0010]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('db9992be-57d2-403f-8a6b-c4e90dde98ac', '[TSP-SyRS-0343]', '"source":"[TSP-SyUR-0101]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('dbdf2cd8-b27a-43f5-aa17-4c7d4e128ffd', '[TSP-SyRS-0005]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('dcaaef0d-bf53-430d-be26-6629b80ec087', '[TSP-SyRS-0116]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:13', '2015-11-02 11:23:24'),
('dcbf776b-fe40-4254-baab-c81c2a795b56', '[TSP-SyRS-0023]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('dcefa630-c305-4947-970b-479d6e1fe43b', '[TSP-SyRS-0073]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('dd804e46-e97d-4338-82d0-3453adc6e91a', '[TSP-SyRS-0338]', '"implement":"1.2.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:00', '2015-10-20 11:21:28'),
('dddff0bd-2c9e-4e5c-bbd5-ab66e0432f3c', '[TSP-SyRS-0013]', '"priority":"Middle","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('de0a2347-f78f-451f-8f33-ab8a6be16242', '[TSP-SyRS-0020]', '"source":"[TSP-SyPHA-0030], [TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:47'),
('de7d80df-9912-4a8a-b0ba-8769d39705cd', '[TSP-SyRS-0020]', '"source":"[TSP-SyPHA-0030], [TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:50', '2015-10-18 23:11:50'),
('ded1387b-5d7d-4fcb-9d77-2a6a0f890f49', '[TSP-SyRS-0184]', '"implement":"1.1.0","priority":"High","contribution":"SIL4","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]","source":"[TSP-SyUR-0038], [TSP-SyUR-0054]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:17'),
('df3db2d1-9f21-4925-a686-9553f4f5d1dd', '[TSP-SyRS-0128]', '"priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('e01e680f-f163-44ee-9718-b9481d1de85e', '[TSP-SyRS-0001]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('e02fb7ee-9008-423a-a20e-e98077aa5f8d', '[TSP-SyRS-0122]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0021]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('e0da110e-4b1b-4790-9877-5f2100b5668f', '[TSP-SyRS-0017]', '"allocation":"[TSP-SyAD], [Exported constraint]","source":"[TSP-SyUR-0019], [TSP-SyUR-0104], [TSP-SyPHA-0010], [TSP-RAMSRs-0007]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:49', '2015-10-18 23:15:10'),
('e2b46e06-4457-49a9-b54e-84de68808ac2', '[TSP-SyRS-0009]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('e349f985-1af2-4b43-bce1-9491f5c18b40', '[TSP-SyRS-0184]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:39'),
('e3602ef3-25a5-4c97-8c53-5d71e2b02adf', '[TSP-SyRS-0256]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('e3dda6ea-3895-4740-ace8-811426ee950b', '[TSP-SyRS-0013]', '"allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]","source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('e4660c73-2173-4acb-b965-0c6e3f7bc1c8', '[TSP-SyRS-0073]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:09', '2015-10-20 11:36:09'),
('e4adebb2-3dee-42dc-ae68-b18c16a1cee3', '[TSP-SyRS-0139]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('e4dc4528-271f-4a4b-93f3-61b3212b8279', '[TSP-SyRS-0165]', '"source":"[TSP-SyUR-0032]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('e4e65cf0-4cda-4899-bd2a-5d14bfa15710', '[TSP-SyRS-0007]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:40', '2015-10-20 11:23:41'),
('e4f01d67-95d0-4a28-895f-e0dc6d2a07ea', '[TSP-SyRS-0120]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0007], [TSP-SyPHA-0008]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('e4fba5eb-0193-4d2d-a549-6d9d5d3d5ede', '[TSP-SyRS-0165]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('e4ff9a15-ba6c-4c6e-90f4-6fe4c86af7ca', '[TSP-SyRS-0007]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:09', '2015-10-20 11:36:09'),
('e550d59d-5500-419f-9420-25041f24371e', '[TSP-SyRS-0002]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('e58e2b77-24d2-43c4-886e-3ee6791a7347', '[TSP-SyRS-0256]', '"implement":"1.1.0","priority":"Middle","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('e5a1565d-72fe-48ff-b727-88cd4a06bb17', '[TSP-SyRS-0008]', '"source":"[TSP-SyUR-0019]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:47'),
('e622b48c-cc6f-4c72-99da-a442d47fbc48', '[TSP-SyRS-0022]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:24', '2015-10-18 23:35:24'),
('e6aa9532-5c94-4e68-b8a3-4388f74b5480', '[TSP-SyRS-0005]', '"source":"[TSP-SyUR-0004]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:46'),
('e70b635c-cc03-4674-860c-6911e59a51c5', '[TSP-SyRS-0343]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:41'),
('e73a7509-a1be-4ced-b00c-d5b2216ae07d', '[TSP-SyRS-0020]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0030], [TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:41', '2015-10-18 18:40:17'),
('e7fc47d5-f57b-40f4-abdb-7323b11ed476', '[TSP-SyRS-0011]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:41'),
('e7fdc260-1651-4c00-b03d-e32a3207869b', '[TSP-SyRS-0125]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:01', '2015-11-02 11:13:40'),
('e803a739-e95b-4432-bc76-889cbcf07766', '[TSP-SyRS-0127]', '"allocation":"[TSP-SyAD], [TSP-MPS-GGW-SyID]","source":"[TSP-SyPHA-0001], [TSP-SyPHA-0002], [TSP-SyPHA-0003], [TSP-SyPHA-0004], [TSP-SyPHA-0005]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('e821848b-3034-4c99-9d6d-24a0c66dafc1', '[TSP-SyRS-0005]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('e8ba4a08-0c70-4d97-9ee5-1de97d4cea57', '[TSP-SyRS-0006]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('e8dafbb9-c465-4070-ad82-6fb63a6e1b0b', '[TSP-SyRS-0022]', '"allocation":"[TSP-SyAD]","source":"[TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:49', '2015-10-18 23:15:10'),
('e92f0927-405c-4c99-aca3-39464a07409e', '[TSP-SyRS-0256]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('e93aa93a-720b-4db1-ae33-2e3e56448204', '[TSP-SyRS-0004]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('eb093ea7-f913-4237-b6dd-64cd8aa267f1', '[TSP-SyRS-0205]', '"source":"[TSP-SyPHA-0028], [TSP-SyPHA-0026]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:41', '2015-10-18 21:57:48'),
('eb86607b-e8d8-4deb-b3cf-f423d436b3c4', '[TSP-SyRS-0361]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:15', '2015-10-20 11:17:15'),
('ec6a54ec-301b-453c-a88c-94efc68bc3dc', '[TSP-SyRS-0120]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:40', '2015-10-20 11:23:40'),
('edfe2fef-bc84-432c-9a91-baa446a7299f', '[TSP-SyRS-0026]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:41', '2015-10-20 11:23:41'),
('ee4d0f31-3113-46e8-8f95-fa4a2065258b', '[TSP-SyRS-0005]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('ee9351b0-ab57-4589-bef3-893d8469870c', '[TSP-SyRS-0326]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('eefe058a-f25b-4613-8119-236550de316d', '[TSP-SyRS-0326]', '"source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:50', '2015-10-18 23:11:50'),
('ef1f40c8-c97c-42c9-a52f-89a002c44126', '[TSP-SyRS-0338]', '"source":"[TSP-SyUR-0097]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('ef7d8cb1-9a18-42f4-bce4-a60b6c0e4609', '[TSP-SyRS-0013]', '"source":"[TSP-SyUR-0115]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:42', '2015-10-18 21:57:47'),
('f130a525-4839-492a-8320-8be14e532dc7', '[TSP-SyRS-0002]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:13:59', '2015-11-02 11:13:39'),
('f1b2921f-9562-4444-8095-ce348bd7fb5a', '[TSP-SyRS-0002]', '"source":"[TSP-RAMSRs-0010]"', '', NULL, '', '', '', '', '', 0, '', '[]', '29f5e6cd-0283-41ed-b49e-69f0d86b7a32', '2015-10-18 19:41:40', '2015-10-18 21:57:46'),
('f1e85807-4640-4ecd-a9a4-c685573a9c15', '[TSP-SyRS-0206]', '"source":"[TSP-SyPHA-0029]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('f2d92d4f-b96b-49c6-8f80-c9de4c89e211', '[TSP-SyRS-0205]', '"source":"[TSP-SyPHA-0028], [TSP-SyPHA-0026]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('f41089c8-c531-4e07-8463-5a0ec2904b36', '[TSP-SyRS-0026]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0003]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('f44ad165-0257-41ee-bff1-778907f9f795', '[TSP-SyRS-0008]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('f4cba09a-1323-4652-ab7b-e9436e3da70d', '[TSP-SyRS-0205]', '"implement":"1.1.0","priority":"High","contribution":"Safety","allocation":"[TSP-SyAD]","source":"[TSP-SyPHA-0028], [TSP-SyPHA-0026]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:40', '2015-10-18 18:40:17'),
('f51105b8-06c2-4053-9083-d7a5dbf1f1c2', '[TSP-SyRS-0204]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('f5a60630-e461-4571-83db-4d4f171c54a0', '[TSP-SyRS-0125]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0023]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('f5bfeeac-8520-4835-8995-3b1cafeb4b62', '[TSP-SyRS-0138]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:17', '2015-10-20 11:17:17'),
('f65f75d5-5222-47ad-a6cb-bdee13bd0453', '[TSP-SyRS-0007]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0021]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:47', '2015-10-18 23:15:09'),
('f6654054-6704-487f-a625-2507fe401256', '[TSP-SyRS-0365]', '"implement":"1.1.0","priority":"High","contribution":"N/A","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-MPS-GGW-SyID], [TSP-SDMS-GGW-SyID]","source":"[TSP-SyUR-0004]"', '', NULL, '', '', '', '', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2015-10-17 16:07:39', '2015-10-18 18:40:18'),
('f6cdb89f-673e-4787-866a-91ddf126d637', '[TSP-SyRS-0364]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:11', '2015-10-20 11:36:11'),
('f7447954-9525-43ca-afc4-25357c516197', '[TSP-SyRS-0001]', '"priority":"Middle"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:13:59', '2015-11-02 11:13:39'),
('f7594b8f-f1c1-48c1-b085-7c2c662d6b65', '[TSP-SyRS-0343]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0101]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:09'),
('f789fa43-705a-4e65-b9e3-7d1fca415e57', '[TSP-SyRS-0204]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '69774c53-967f-4c17-a0c8-fca815820951', '2015-10-20 13:14:00', '2015-11-02 11:13:39'),
('f879ec66-6c99-4fd5-8b68-4d7430d344fa', '[TSP-SyRS-0001]', '"priority":"Middle","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:12', '2015-11-02 11:23:24'),
('f8831f23-cdad-4e95-93c4-eac8de161cf8', '[TSP-SyRS-0203]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('f94d9be6-bad1-4c88-a1d3-0988a699883e', '[TSP-SyRS-0002]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'v4', '2015-10-20 11:13:40', '2015-10-20 11:23:40'),
('f9d36e55-e29d-405a-a605-47150b58e4fb', '[TSP-SyRS-0327]', '"priority":"Middle","allocation":"[TSP-SyAD], [TSP-MPS-SDMS-SyID], [TSP-SDMS-GGW-SyID]"', '', NULL, '', '', '', '', '', 0, '', '', 'a27b6482-6d73-4209-8f81-d1ec6499c3bd', '2015-10-20 11:42:14', '2015-11-02 11:23:25'),
('fa4c301e-157c-4ae4-9d33-ae14cf2cc71d', '[TSP-SyRS-0184]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:10', '2015-10-20 11:36:10'),
('fc1c1535-8b69-42ea-81eb-4f2395fd38cd', '[TSP-SyRS-0125]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('fc3957d8-e641-4fe4-bf3a-c48744987061', '[TSP-SyRS-0026]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('fc50848b-9046-4bfa-a54e-49186db00743', '[TSP-SyRS-0338]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16'),
('fc6c7cf3-d20e-42bc-bf45-56131c809720', '[TSP-SyRS-0006]', '"priority":"High"', '', NULL, '', '', '', '', '', 0, '', '', '4b3df65f-c2da-4e9b-a991-c64c49dad8c3', '2015-10-20 11:36:09', '2015-10-20 11:36:09'),
('fc98cbd9-fe40-435c-9727-87589df16dd8', '[TSP-SyRS-0128]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0023]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('fd51adc3-a497-4b52-8737-29b2d168f56a', '[TSP-SyRS-0126]', '"implement":"1.1.0","priority":"High","allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '[]', '07bfe4a0-2e09-4379-94e7-8495937a1da7', '2015-10-18 23:19:01', '2015-10-20 11:21:28'),
('fec80b4e-c5f2-4430-872d-5a2ad825c9be', '[TSP-SyRS-0361]', '"source":"[TSP-SyUR-0032], [TSP-SyUR-0038], [TSP-SyUR-0101], [TSP-SyUR-0048], [TSP-SyUR-0117], [TSP-SyUR-0001]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('ff39bec9-e626-4d49-9cd4-bd7feefa31fe', '[TSP-SyRS-0118]', '"allocation":"[TSP-SyAD]","source":"[TSP-SyUR-0038]"', '', NULL, '', '', '', '', '', 0, '', '', 'v3', '2015-10-18 23:08:48', '2015-10-18 23:15:10'),
('ff82dbce-73d9-40ee-a6ba-671ff5e0e2f6', '[TSP-SyRS-0001]', '"source":"[TSP-SyUR-0001]"', '', NULL, '', '', '', '', '', 0, '', '', '35181f82-4f42-4328-ac36-bf585e65b244', '2015-10-18 23:11:49', '2015-10-18 23:11:49'),
('ffc869d3-219f-439e-82d5-b785a071ada9', '[TSP-SyRS-0011]', '"allocation":"[TSP-SyAD]"', '', NULL, '', '', '', '', '', 0, '', '', 'd1b9c3ce-d78d-4368-af03-69efa29af14f', '2015-10-18 23:35:23', '2015-10-18 23:35:23'),
('ffed2392-b116-48fd-b01c-f737f387e91d', '[TSP-SyRS-0120]', '0', '', NULL, '', '', '', '', '', 0, '', '', '55187e06-a13c-4742-ae16-b7fa37ba9330', '2015-10-20 11:17:16', '2015-10-20 11:17:16');

-- --------------------------------------------------------

--
-- 表的结构 `rs_vat`
--

CREATE TABLE IF NOT EXISTS `rs_vat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rs_id` varchar(36) NOT NULL,
  `vat_id` varchar(36) NOT NULL,
  `comment` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- 转存表中的数据 `rs_vat`
--

INSERT INTO `rs_vat` (`id`, `rs_id`, `vat_id`, `comment`) VALUES
(1, '28b38466-a6c0-11e4-b3f2-2eb1ec8cf52b', 'fd7bdad4-c56c-410c-95b6-910e53f1dd6b', NULL),
(2, '28b38466-a6c0-11e4-b3f2-2eb1ec8cf52b', '399b97e7-776f-49f3-b4fe-ffc218f0ff55', NULL),
(3, '28b38466-a6c0-11e4-b3f2-2eb1ec8cf52b', '01e87349-d3b1-4985-b261-2de78719a825', NULL),
(4, '9123ffb2-a6c5-11e4-b3f2-2eb1e18cf52a', '76037616-9f91-427e-8457-4eccef1cd9bd', NULL),
(5, '9123ffb2-a6c5-11e4-b3f2-2eb1ea8cf52a', '76037616-9f91-427e-8457-4eccef1cd9bd', NULL),
(6, '9123ffb2-a6c5-11e4-b3f2-2eb1ea8cf52a', '687c4397-f6ea-422b-99ba-656fbeb1640a', NULL),
(7, '9123ffb2-a6c5-11e4-b3f2-2eb11a8cf52a', '76037616-9f91-427e-8457-4eccef1cd9bd', NULL),
(8, '9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b', '399b97e7-776f-49f3-b4fe-ffc218f0ff55', NULL),
(9, '9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b', '01e87349-d3b1-4985-b261-2de78719a825', NULL),
(10, '9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b', '9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b', NULL),
(11, '9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b', '9123ffb2-a6c5-11e4-b3f2-2eb11a8cf52a', NULL),
(12, '9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52a', '9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b', NULL),
(13, '9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52a', '399b97e7-776f-49f3-b4fe-ffc218f0ff55', NULL),
(14, '9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52a', '9123ffb2-a6c5-11e4-b3f2-2eb1e18cf52a', NULL),
(15, '9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52a', '76037616-9f91-427e-8457-4eccef1cd9bd', NULL);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `tag`
--
CREATE TABLE IF NOT EXISTS `tag` (
`id` varchar(36)
,`tag` varchar(100)
,`version_id` varchar(36)
);
-- --------------------------------------------------------

--
-- 表的结构 `tc`
--

CREATE TABLE IF NOT EXISTS `tc` (
  `id` varchar(36) NOT NULL COMMENT '单个tc标签id',
  `tag` varchar(100) NOT NULL COMMENT 'tc名称',
  `column` text NOT NULL,
  `description` text NOT NULL COMMENT 'tc描述',
  `testmethod_id` text NOT NULL COMMENT '测试方法',
  `test_item` varchar(100) NOT NULL,
  `pre_condition` text NOT NULL COMMENT '前提条件',
  `input` varchar(255) NOT NULL,
  `exec_step` varchar(255) NOT NULL,
  `exp_step` varchar(255) NOT NULL,
  `result` tinyint(1) NOT NULL DEFAULT '0' COMMENT '结果，0未测试，1表示成功，2表示失败',
  `source_json` text NOT NULL,
  `version_id` varchar(36) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tc`
--

INSERT INTO `tc` (`id`, `tag`, `column`, `description`, `testmethod_id`, `test_item`, `pre_condition`, `input`, `exec_step`, `exp_step`, `result`, `source_json`, `version_id`, `created_at`, `updated_at`) VALUES
('06ee0445-364f-4579-b945-b6948a490a8c', '[TSP-SyRTC-0111]', '"test case description":"[TSP-SyRTC-0111]Check that TSP shall supervise the APP’s execution time(336ms).[Source:[TSP-SyRS-0330]] [Source:[TSP-External-SyID-0015]] [Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('094c5d42-0db3-41b7-b10f-7de835efb01b', '[TSP-SyRTC-0189]', '"test case description":"[TSP-SyRTC-0189]Check that TSP can transit Product information to MSS when received from APP(336ms).[Source:[TSP-External-SyID-0038]][Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:12'),
('0d154dd9-6109-4b9e-9001-09d199f74191', '[TSP-SyRTC-0093]', '"test case description":"[TSP-SyRTC-0093]Check the interface of GetTSPRunState between TSP and the upper application.[Source:[TSP-External-SyID-0029]] [Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('0db965ef-a8db-4a7c-98dc-8d4e6e26bc50', '[TSP-SyRTC-0179]', '"test case description":"[TSP-SyRTC-0179]Check the interface of GetGatewayMobileConnected between TSP and the upper application(500ms).[Source:[TSP-External-SyID-0063]] [Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:14'),
('111b5672-bd32-4877-ac29-e11906a2c41f', '[TSP-SyRTC-0090]', '"pre-condition":"Set MPS-A as MPS-N and MPS-B as MPS-R, they are normal and synchronized.The communications between TSP and TSP test bench are normal with blue and red networks.APP simulator print the head structure of message sent to MPU.APP simulator call ReadNewMSG in Background funcation."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:02'),
('1626b2b4-afec-42c7-9357-b80447786968', '[TSP-SyRTC-0036]', '"test case description":"[TSP-SyRTC-0036]Check that TSP can receive SACEM messages from external device(336ms).[Source:[TSP-SyRS-0161]] [Source:[TSP-SyRS-0078]][Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:36', '2015-10-20 16:27:11'),
('176def1f-932c-4655-916a-6dbf10cb3512', '[TSP-SyRTC-0029]', '"test case description":"[TSP-SyRTC-0029]Check that TSP can receive FSFB2 messages from external device(336ms).[Source:[TSP-SyRS-0106]] [Source:[TSP-SyRS-0140]] [Source:[TSP-SyRS-0347]][Source:[TSP-SyRS-0272]] [Source:[TSP-SyRS-0078]] [Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:36', '2015-10-20 16:27:11'),
('19f076c6-fd7f-415e-810c-d2b1e436367a', '[TSP-SyRTC-0150]', '"test case description":"[TSP-SyRTC-0150]Check that the size of MIB data supported by TSP shall reach to1396bytes(336ms).[Source:[TSP-External-SyID-0018]][Safety:Yes][End] "', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:13'),
('1e87a06c-a16c-4130-8ff0-a2e9d4e2c3f3', '[TSP-SyRTC-0181]', '"pre-condition":"The communications between TSP and TSP test bench are normal.Set the STBY in AUTO."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:03'),
('1f404294-7cbe-4fdf-a9fa-013db2e31895', '[TSP-SyRTC-0127]', '"pre-condition":"STBY is on automatic status. The compile method of IDE is provided by TSP."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:03'),
('22a27f7d-e563-4550-b8b9-92aabec2ff43', '[TSP-SyRTC-0051]', '"test case description":"[TSP-SyRTC-0051]Check that TSP shall synchronize with the NTP server periodically (no more than 60 second) and use its internal clock when doesn''t synchronize with NTP server.[Source: [TSP-SyRS-0345]] [Source: [TSP-External-SyID-0041]] [Source: [TSP-External-SyID-0039]] [Source: [TSP-External-SyID-0027]] [Source: [TSP-SyRS-0130]] [Source:[TSP-External-SyID-0068]][Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:12'),
('297aedb2-fea5-4177-b59d-25cf4d9559bd', '[TSP-SyRTC-0156]', '"pre-condition":"The communications between TSP and TSP test bench are normal.Let APP simulator call GetSysTypeAndState and print the resultSet the STBY in AUTO."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:02'),
('298252a0-7c9e-4cc9-81c9-66a2a5952b26', '[TSP-SyRTC-0141]', '"test case description":"[TSP-SyRTC-0141]Check that TSP can send RSSP-II message to external device correctly(500ms).[Source:[TSP-SyRS-0385] [Source:[TSP-SyRS-0386][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('2ccb2f20-b7dd-4819-a03d-f70044b24e9f', '[TSP-SyRTC-0004]', '"test case description":"[TSP-SyRTC-0004]Check if MPS-N and MPS-R can be switched manually without interrupting the normal operation when they are synchronized(336ms).[Source:[TSP-SyRS-0002]] [Source:[TSP-SyRS-0073]] [Source:[TSP-SyRS-0254]] [Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:12'),
('2d42fd9a-4886-414f-aad3-504c68aa04b8', '[TSP-SyRTC-0148]', '"test case description":"[TSP-SyRTC-0148]Check that TSP shall exchange Functional message with external device installed inside.[Source:[TSP-SyRS-0137]] [Source:[TSP-External-SyID-0018]][Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('2e23e717-e730-481c-80b8-95d490730ba5', '[TSP-SyRTC-0084]', '"test case description":"[TSP-SyRTC-0084]Check that if the TSP system applies redundant network architecture based on switch Ethernet technology.[Source:[TSP-SyRS-0022]] [Source:[TSP-SyRS-0027]] [Source:[TSP-SyRS-0403]] [Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('33ff80ea-f325-49f7-8f62-82c9cad33065', '[TSP-SyRTC-0149]', '"test case description":"[TSP-SyRTC-0149]Check that TSP can send FUNCTIONAL message to external device correctly.[Source:[TSP-SyRS-0285] [Source:[TSP-SyRS-0286] [Safety:No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('3872dacc-d8e6-4b71-be3a-cc4f936e61f3', '[TSP-SyRTC-0106]', '"test case description":"[TSP-SyRTC-0106]Check that if TSP shall send the LRU state of TSP to MSS(336ms).[Source:[TSP-SyRS-0085] [Source:[TSP-SyRS-0356] [Source:[TSP-External-SyID-0038]] [Source:[TSP-External-SyID-0018]] [Source:[TSP-External-SyID-0026]]  [Source:[TSP-External-SyID-0068]] [Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:12'),
('3a34d63b-a107-43b4-9892-1b7fff86e60d', '[TSP-SyRTC-0039]', '"test case description":"[TSP-SyRTC-0039]Check that TSP can send SACEM message to external device correctly(336ms).[Source:[TSP-SyRS-0162] [Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('3a388dd6-7fff-4dd6-975b-6a48b64a8281', '[TSP-SyRTC-0135]', '"test case description":"[TSP-SyRTC-0135]Check that TSP can receive RSSP-I messages from external device(500ms).[Source:[TSP-SyRS-0370]] [Source:[TSP-SyRS-0373]] [Source:[TSP-SyRS-0078]][Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('3ad373b2-bcf8-4aa7-b82b-710e3463c0dc', '[TSP-SyRTC-0193]', '"test case description":"[TSP-SyRTC-0193]Check that SDMS can set lruHwNumber, otherEquipments, rMonCounter, redundancyService to be 0(336ms).[Source:[TSP-External-SyID-0038]][Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:13'),
('4364ced3-8a1b-4879-bcbc-caaf02d541c1', '[TSP-SyRTC-0179]', '"pre-condition":"The communications between TSP and TSP test bench are normal.Set the STBY in AUTO."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:03'),
('473f2505-4a26-4d85-903e-5cdb636d7626', '[TSP-SyRTC-0192]', '"test case description":"[TSP-SyRTC-0192]Check that TSP can transit State information to MSS when received from APP(336ms).[Source:[TSP-External-SyID-0038]][Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:13'),
('4811ad08-f06e-4b10-9857-14b202f1dd7a', '[TSP-SyRTC-0181]', '"test case description":"[TSP-SyRTC-0181]Check the interface of DisConnect between TSP and the upper application(500ms).[Source:[TSP-External-SyID-0066]] [Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:14'),
('4a44b00b-7e97-4fe8-af83-0c0a64fc84f7', '[TSP-SyRTC-0091]', '"test case description":"[TSP-SyRTC-0091]Check the interface of AddNewMSG between TSP and APP(336ms). [Source:[TSP-External-SyID-0018]] [Source:[TSP-SyRS-0299]] [Source:[TSP-SyRS-0300]] [Source:[TSP-External-SyID-0016]] [Source:[TSP-External-SyID-0028]] [Source:[TSP-External-SyID-0001]] [Source:[TSP-External-SyID-0004]][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('4bb2af6d-47a1-4890-adfd-b721630bd989', '[TSP-SyRTC-0016]', '"test case description":"[TSP-SyRTC-0016]Check that TSP shall provide tool to flash image and data, the whole time shall be less than 5 minutes.[Source:[TSP-SyRS-0350]] [Source:[TSP-SyRS-0348]] [Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('4c444a12-4091-4a67-aaff-ff8126361482', '[TSP-SyRTC-0180]', '"pre-condition":"The communications between TSP and TSP test bench are normal.Let APP simulator call GetExecuteMode and print the resultSet the STBY in AUTO."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:03'),
('4d2ed21e-d83e-4fd5-a9d9-86fa1384e54a', '[TSP-SyRTC-0132]', '"test case description":"[TSP-SyRTC-0132]Check if when one MPS is already running as MPS-N, the other MPS can start successfully.[Source:[TSP-SyRS-0002]] [Safety:No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:36', '2015-10-20 16:27:10'),
('4e8cf1b3-31c1-4171-b573-1692d29e69f4', '[TSP-SyRTC-0113]', '"test case description":"[TSP-SyRTC-0113]Check that TSP shall supervise the APP’s execution time(500ms).[Source:[TSP-SyRS-0330]] [Source:[TSP-External-SyID-0015]]  [Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:12'),
('5279f40c-9c7d-4e78-af9e-04b6577ca8e6', '[TSP-SyRTC-0057]', '"test case description":"[TSP-SyRTC-0057]Check that if the initialization time after TSP power on shall not exceed 3 minute.[Source:[TSP-SyRS-0074]] [Source:[TSP-SyRS-0435][Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('52b835ec-cad4-43cd-b980-733964ed7045', '[TSP-SyRTC-0175]', '"test case description":"[TSP-SyRTC-0175]Check that TSP provides redundant and signal Layers protocol communication interface.(500ms) [Source:[TSP-External-SyID-0023]]  [Source :[TSP-External-SyID-0022]] [Source :[TSP-External-SyID-0037]] [Source :[TSP-External-SyID-0028]] [Source:[TSP-SyRS-0022]] [Source :[TSP-External-SyID-0068]] [Source :[TSP-External-SyID-0069]][Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('53cb04dc-0997-4232-9466-603597594f07', '[TSP-SyRTC-0171]', '"pre-condition":"Set MPS-A as MPS-N and MPS-B as MPS-RAPP simulator use the interface provide by TSP."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:02'),
('586f6aa8-fe34-4df2-b10c-21f37670e317', '[TSP-SyRTC-0186]', '"test case description":"[TSP-SyRTC-0186]Check if MPS-N and MPS-R can be switched manually without interrupting the normal operation when they are synchronized(500ms).[Source:[TSP-SyRS-0002]] [Source:[TSP-SyRS-0073]] [Source:[TSP-SyRS-0254]] [Source:[TSP-SyRS-0184]][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:12'),
('5bfa29b7-694a-490d-b2ff-cad3ff029224', '[TSP-SyRTC-0174]', '"test case description":"[TSP-SyRTC-0174]Check that TSP can not send/receive 1379 bytes FSFB2 data(336ms).[Source:[TSP-SyRS-0264]] [Source:[TSP-External-SyID-0024]][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('5fa21495-1a8d-412a-9c02-7cb6fd8a788e', '[TSP-SyRTC-0101]', '"test case description":"[TSP-SyRTC-0101]Check that TSP provides redundant and signal Layers protocol communication interface(336ms). [Source:[TSP-External-SyID-0023]]  [Source :[TSP-External-SyID-0022]] [Source :[TSP-External-SyID-0037]] [Source :[TSP-External-SyID-0028]] [Source:[TSP-SyRS-0022]] [Source :[TSP-External-SyID-0068]] [Source :[TSP-External-SyID-0069]][Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('5fca0001-36fb-46ea-a6af-851a4e95963d', '[TSP-SyRTC-0025]', '"test case description":"[TSP-SyRTC-0025]Check that TSP shall communicate with external device through GGW-R and GGW-B.[Source:[TSP-SyRS-0137]] [Source:[TSP-SyRS-0329]] [Source:[TSP-SyRS-0426]][Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:36', '2015-10-20 16:27:10'),
('5fe7fdc3-8516-48d5-b249-0cd407e9491a', '[TSP-SyRTC-0137]', '"test case description":"[TSP-SyRTC-0137]Check that TSP can send RSSP-I message to external device correctly(500ms).[Source:[TSP-SyRS-0376] [Source:[TSP-SyRS-0377][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('61b987df-4c15-4b99-b9a2-0b28c6222cf4', '[TSP-SyRTC-0170]', '"test case description":"[TSP-SyRTC-0170]Check that if TSP can work normally when the communication quantity is over 60KB (500ms).[Source:[TSP-SyRS-0075]][Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('63d60c20-afcf-42e6-abaf-f0494e4432dc', '[TSP-SyRTC-0182]', '"test case description":"[TSP-SyRTC-0182]Check IP address of external network and opening service port of TSP can be configured.[Source:[TSP-SyRS-0139]] [Source:[TSP-SyRS-0024]] [Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('6634834d-8faf-435b-974e-38fc346b78e6', '[TSP-SyRTC-0145]', '"test case description":"[TSP-SyRTC-0145]Check that TSP can send SUBSET037 message to external device correctly(500ms).[Source:[TSP-SyRS-0395] [Source:[TSP-SyRS-0394][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('665d1a7a-0244-4325-87cf-2d007bd72875', '[TSP-SyRTC-0115]', '"test case description":"[TSP-SyRTC-0115]Check setShutDown shall be used for APP to shutdown the error MPS.[Source:[TSP-External-SyID-0036]] [Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('699cdd53-5fe2-43f2-8997-d907a90729cb', '[TSP-SyRTC-0091]', '"pre-condition":"Set MPS-A as MPS-N and MPS-B as MPS-R, they are normal and synchronized.The communications between TSP and TSP test bench are normal with blue and red networks.APP simulator print the head structure of message sent to MPU.The ITF Ver configured in the TSP_MAP.ini is same as configured in GM_GAPP.iniAPP simulator call ReadNewMSG in Background funcation."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:02'),
('69b6acd5-3a4f-4ff4-bb7f-76eaddc13940', '[TSP-SyRTC-0138]', '"test case description":"[TSP-SyRTC-0138]Check that the size of RSSP-I data that TSP shall support range from 1-1000(500ms).[Source:[TSP-SyRS-0375]] [Source:[TSP-External-SyID-0008]][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('6ab65aa6-17e1-4db5-a790-6daa257e6609', '[TSP-SyRTC-0171]', '"test case description":"[TSP-SyRTC-0171]Check that TSP can interface for APP to initialize SACEM, send and receive SACEM message (336ms).[Source: [TSP-External-SyID-0033]] [Source: [TSP-External-SyID-0034]] [Source: [TSP-External-SyID-0035]]  [Source:[TSP-SyRS-0277]][Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('6bdd071e-f327-475c-bcca-1c3f2e61cf55', '[TSP-SyRTC-0151]', '"test case description":"[TSP-SyRTC-0151]Check if MPS-N and MPS-R can be switched automatically when MPS-N is unhealth and MPS-R is health.[Source:[TSP-SyRS-0011]] [Source:[TSP-SyRS-0073]] [Source:[TSP-External-SyID-0052]] [Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:12'),
('6c4bd478-8500-4e93-ad5d-37755de8eb07', '[TSP-SyRTC-0188]', '"test case description":"[TSP-SyRTC-0188]Check the communication of one MPS shall not impacted by the other MPS(336ms).[Source:[TSP-SyRS-0002]] [Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:36', '2015-10-20 16:27:10'),
('6f72d7ed-384a-4b82-8585-7c485974f5ec', '[TSP-SyRTC-0155]', '"test case description":"[TSP-SyRTC-0155]Check the interface of AddNewMSG between TSP and APP(500ms). [Source:[TSP-External-SyID-0018]] [Source:[TSP-SyRS-0299]] [Source:[TSP-SyRS-0300]] [Source:[TSP-External-SyID-0016]] [Source:[TSP-External-SyID-0028]] [Source:[TSP-External-SyID-0001]] [Source:[TSP-External-SyID-0004]][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('733d93ad-e9a6-4e07-bb7b-34b0addd64b2', '[TSP-SyRTC-0180]', '"test case description":"[TSP-SyRTC-0180]Check the interface of GetExecuteMode between TSP and the upper application(500ms).[Source:[TSP-External-SyID-0064]] [Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:14'),
('753aa6ef-f621-4a3d-b900-9ea6a28c9aa4', '[TSP-SyRTC-0015]', '"test case description":"[TSP-SyRTC-0015]Check SDMS can display the connection status of MPS with SDMS and GGW with SDMS.[Source:[TSP-SyRS-0403]] [Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('759de73d-a7ce-452a-a72c-8bb3950febea', '[TSP-SyRTC-0178]', '"pre-condition":"The communications between TSP and TSP test bench are normal.Set the STBY in AUTO."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:03'),
('7b21c574-3fb4-418a-bf26-008c6abf25e6', '[TSP-SyRTC-0177]', '"pre-condition":"The communications between TSP and TSP test bench are normal.Set the STBY in AUTO."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:03'),
('7fbd55df-149e-443f-aa14-179bc6b801a3', '[TSP-SyRTC-0083]', '"test case description":"[TSP-SyRTC-0083]Check that if switching the power supply doesn’t influence the normal operation of the system[Source:[TSP-SyRS-0020]] [Source:[TSP-SyRS-0045]] [Source:[TSP-SyRS-0429]] [Source:[TSP-SyRS-0017]] [Source:[TSP-SyRS-0217]][Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('802c7c95-2ee3-4bbe-9f0e-1acff6852137', '[TSP-SyRTC-0154]', '"test case description":"[TSP-SyRTC-0154]Check the interface of ReadNewMSG between TSP and APP(500ms). [Source:[TSP-External-SyID-0019]] [Source:[TSP-SyRS-0299]] [Source:[TSP-SyRS-0300]] [Source:[TSP-External-SyID-0016]] [Source:[TSP-External-SyID-0028]] [Source:[TSP-External-SyID-0001]] [Source:[TSP-External-SyID-0004]][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('810708fe-3425-46a2-9635-166e5ccd3d60', '[TSP-SyRTC-0090]', '"test case description":"[TSP-SyRTC-0090]Check the interface of ReadNewMSG between TSP and APP(336ms). [Source:[TSP-External-SyID-0019]] [Source:[TSP-SyRS-0299]] [Source:[TSP-SyRS-0300]] [Source:[TSP-External-SyID-0016]] [Source:[TSP-External-SyID-0028]] [Source:[TSP-SyRS-0277]] [Source:[TSP-External-SyID-0001]] [Source:[TSP-External-SyID-0004]][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('8573b489-d733-4d98-aac6-7e999c58ea98', '[TSP-SyRTC-0117]', '"test case description":"[TSP-SyRTC-0117]Check TSP shall be a hot-redundant 2×2oo2 system.[Source:[TSP-SyRS-0002]] [Source:[TSP-SyRS-0106]] [Source:[TSP-SyRS-0234]] [Source:[TSP-SyRS-0011]] [Source:[TSP-SyRS-0368]] [Source:[TSP-SyRS-0310]] [Source:[TSP-SyRS-0056]] [Source:[TSP-SyRS-0216][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:36', '2015-10-20 16:27:10'),
('862be43b-6af8-459c-8439-8296a5e350eb', '[TSP-SyRTC-0157]', '"pre-condition":"The communications between TSP and TSP test bench are normal.Set the STBY in AUTO."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:03'),
('8cf8dba8-7730-44eb-802f-09021bd1f07b', '[TSP-SyRTC-0125]', '"test case description":"[TSP-SyRTC-0125]Check that the size of FSFB2 data that TSP shall support range from 2-1378(336ms).[Source:[TSP-SyRS-0264]][Source:[TSP-External-SyID-0024]][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:36', '2015-10-20 16:27:11'),
('99598d26-d309-4021-93fc-8a415d565e48', '[TSP-SyRTC-0187]', '"test case description":"[TSP-SyRTC-0187]Check the communication of one MPS shall not impacted by the other MPS(500ms).[Source:[TSP-SyRS-0002]] [Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:36', '2015-10-20 16:27:10'),
('9b54edba-8874-4e91-a742-f9f547d74513', '[TSP-SyRTC-0178]', '"test case description":"[TSP-SyRTC-0178]Check the interface of GetPeripheralState between TSP and the upper application(500ms).[Source:[TSP-External-SyID-0062]] [Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('9f521b57-3dbd-4d20-9e97-11cbe61a33b4', '[TSP-SyRTC-0154]', '"pre-condition":"Set MPS-A as MPS-N and MPS-B as MPS-R, they are normal and synchronized.The communications between TSP and TSP test bench are normal with blue and red networks.APP simulator print the head structure of message received from MPU.APP simulator call ReadNewMSG in Background funcation."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:02'),
('a1502e40-cf94-419b-a470-e458e50452c1', '[TSP-SyRTC-0168]', '"test case description":"[TSP-SyRTC-0168]Check that the size of FUNCTIONAL data that TSP shall support range from 1-1400.[Source:[TSP-External-SyID-0019]][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('a524d7e1-56e1-4b12-8b3c-ff583117c48f', '[TSP-SyRTC-0157]', '"test case description":"[TSP-SyRTC-0157]Check the interface of GetCurTime, CalcTimeGap between TSP and the upper application.[Source:[TSP-External-SyID-0007]] [Source:[TSP-External-SyID-0049]] [Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('a957f564-513b-4211-b11a-a82ebcec13d2', '[TSP-SyRTC-0169]', '"test case description":"[TSP-SyRTC-0169]Check that if TSP can work normally when the communication quantity is over 30KB (336ms).[Source:[TSP-SyRS-0075]][Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('ab2b3951-3e86-4885-9b47-ff60a4478c84', '[TSP-SyRTC-0130]', '"test case description":"[TSP-SyRTC-0130]Check if TSP shall check communication state with external system.[Source:[TSP-SyRS-0412]] [Safety:No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:36', '2015-10-20 16:27:10'),
('ad96d993-ed77-4ff9-af3f-03fb29e2371e', '[TSP-SyRTC-0155]', '"pre-condition":"Set MPS-A as MPS-N and MPS-B as MPS-R, they are normal and synchronized.The communications between TSP and TSP test bench are normal with blue and red networks.APP simulator print the head structure of message received from MPU.The ITF Ver configured in the TSP_MAP.ini is same as configured in GM_GAPP.iniAPP simulator call ReadNewMSG in Background funcation."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:02'),
('b11dd495-01b1-4331-a6e7-3ce195414a7d', '[TSP-SyRTC-0107]', '"test case description":"[TSP-SyRTC-0107]Check that TSP can send TRAP message to MSS (336ms).[Source:[TSP-SyRS-0085] [Source:[TSP-External-SyID-0038]] [Source:[TSP-External-SyID-0026]][Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:12'),
('b50a61a4-6223-49bf-b1f9-cb8204a21ce1', '[TSP-SyRTC-0106]', '"pre-condition":"Set MPS-A as MPS-N and MPS-B as MPS-R, they are normal and synchronized.MSS simulator(Minit tool )is prepared.Reboot SDMS."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:27', '2015-10-20 11:10:02'),
('b554af48-0232-44a2-951e-8731962da99e', '[TSP-SyRTC-0072]', '"test case description":"[TSP-SyRTC-0072]Check that if TSP can receive/transmit 180 messages each cycle(336ms).[Source:[TSP-SyRS-0075]] [Source:[TSP-SyRS-0076]] [Source:[ TSP-External-SyID-0001]][Source:[ TSP-External-SyID-0004]][Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('b8ec9a1d-6932-45cf-8faf-b220b37dd42b', '[TSP-SyRTC-0101]', '"pre-condition":"Set MPS-A as MPS-N and MPS-B as MPS-R, they are normal and synchronized.The communications of TSP and all the external simulators are normal. "', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:27', '2015-10-20 11:10:02'),
('b9be2a8e-7960-4d09-9b0d-d2ed6d9928f5', '[TSP-SyRTC-0015]', '"pre-condition":"TSP is power off."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:27', '2015-10-20 11:10:02'),
('ba7ab038-a77d-43b0-b1aa-7f627814d4ce', '[TSP-SyRTC-0147]', '"test case description":"[TSP-SyRTC-0147]Check that TSP can receive FUNCTIONAL messages from external device through GGW.[Source:[TSP-SyRS-0322]] [Source:[TSP-SyRS-0319]] [Source:[TSP-SyRS-0283]] [Source:[TSP-SyRS-0078]][Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('ba83fd95-ff36-4c56-abd1-73a38b774abe', '[TSP-SyRTC-0032]', '"test case description":"[TSP-SyRTC-0032]Check that TSP can send FSFB2 message to external device correctly(336ms).[Source:[TSP-SyRS-0160] [Source:[TSP-SyRS-0106]] [TSP-SyRS-0274][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:36', '2015-10-20 16:27:11'),
('bdd4f5a0-c7da-4ffd-90de-57ca1b75b9a3', '[TSP-SyRTC-0192]', '"pre-condition":"SDMS is running.MSS simulator(Minit tool )is prepared."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:27', '2015-10-20 11:10:02'),
('c2c9c178-fadc-4766-b99a-63e36fdd9e52', '[TSP-SyRTC-0139]', '"test case description":"[TSP-SyRTC-0139]Check that TSP can receive RSSP-II messages from external device(500ms).[Source:[TSP-SyRS-0379]] [Source:[TSP-SyRS-0382]] [Source:[TSP-SyRS-0078]][Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('c388cf2c-1ec1-4ee5-8b8a-10e503331052', '[TSP-SyRTC-0120]', '"pre-condition":"Set MPS-A as MPS-N and MPS-B as MPS-R, they are normal and synchronized.The communications between TSP and TSP test bench are normal with blue and red networks."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:03'),
('c3cf8ba5-2216-44a5-ba04-fb0ad24c2ff0', '[TSP-SyRTC-0191]', '"test case description":"[TSP-SyRTC-0191]Check that TSP can transit Software information to MSS when received from APP(336ms).[Source:[TSP-External-SyID-0038]][Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:13'),
('c433f90c-3552-42ba-b325-1687a42b7cae', '[TSP-SyRTC-0115]', '"pre-condition":"STBY is on automatic status. The communications of TSP and TSP test bench are normal.GGW and SDMS are running."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:02'),
('c4518bf0-f353-487c-9701-fbb2154c1edc', '[TSP-SyRTC-0177]', '"test case description":"[TSP-SyRTC-0177]Check the interface of ErrFlush between TSP and the upper application.[Source:[TSP-External-SyID-0060]] [Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('ca215aa6-8397-4e6d-8241-ab83dfc15de9', '[TSP-SyRTC-0170]', '"pre-condition":"The cycle of MPS is 500ms.Set MPS-A as MPS-N and MPS-B as MPS-R."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:27', '2015-10-20 11:10:02'),
('cc4bc494-541f-453d-8e60-2cc61551edc9', '[TSP-SyRTC-0191]', '"pre-condition":"SDMS is running.MSS simulator(Minit tool )is prepared."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:27', '2015-10-20 11:10:02'),
('cdb8a56b-2f61-421c-841e-c8f1b2a4ec64', '[TSP-SyRTC-0182]', '"pre-condition":"Set the STBY in AUTO."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:27', '2015-10-20 11:10:02'),
('cf1e2076-fa0e-47cb-8225-3efa4c88e609', '[TSP-SyRTC-0131]', '"test case description":"[TSP-SyRTC-0131]Check if TSP shall check communication state with GGW.[Source:[TSP-SyRS-0412]] [Safety:No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:36', '2015-10-20 16:27:10'),
('cf675d12-7cf1-4f38-941a-e06c6ecc9940', '[TSP-SyRTC-0118]', '"test case description":"[TSP-SyRTC-0118]Check TSP shall detect the failure of VPS when TSP is running normally.[Source:[TSP-SyRS-0412]] [Source:[TSP-SyRS-0106]] [Source:[TSP-SyRS-0248]][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:12'),
('d07cd7d2-d65d-4aa0-b89d-56f44fb94542', '[TSP-SyRTC-0127]', '"test case description":"[TSP-SyRTC-0127]Check the TSP’s stability though running a long time.[Source:[TSP-SyRS-0002]] [Source:[TSP-SyRS-0360]] [Source:[TSP-SyRS-0359]] [Source:[TSP-SyRS-0045]][Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:39', '2015-10-20 16:27:14'),
('d2a0f450-6664-4b7f-ada5-ccfdeb9660e6', '[TSP-SyRTC-0112]', '"test case description":"[TSP-SyRTC-0112]Check if MPS will not generate dangerous output when there is a position error of the network cable''s connection.[Source: [TSP-SyRS-0324]] [Source: [TSP-SyRS-0060]][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('d4a201f0-0d0e-475c-9060-675453cf660e', '[TSP-SyRTC-0146]', '"test case description":"[TSP-SyRTC-0146]Check that the size of SUBSET037 data that TSP shall support range from 5-1000(500ms).[Source:[TSP-SyRS-0393]] [Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('d613c99d-36a6-49ae-8cbe-c56c09e4105c', '[TSP-SyRTC-0121]', '"test case description":"[TSP-SyRTC-0121]Check if TSP has implement GetIdDevice interface.[Source: [TSP-External-SyID-0040]][Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:39', '2015-10-20 16:27:14'),
('d76a6465-6821-410b-ae22-6d5a6ba64bbf', '[TSP-SyRTC-0121]', '"pre-condition":"Set MPS-A as MPS-N and MPS-B as MPS-R, they are normal and synchronized.The communications between TSP and TSP test bench are normal."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:03'),
('db476de0-4cae-4c13-afaf-7a23a5477396', '[TSP-SyRTC-0174]', '"pre-condition":"The STBY key is on Auto position.Set MPS-A as MPS-N and MPS-B as MPS-R."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:27', '2015-10-20 11:10:02'),
('dcc56193-62e0-4469-823a-5354e85216f8', '[TSP-SyRTC-0072]', '"pre-condition":"The cycle of MPS is 336ms.Set MPS-A as MPS-N and MPS-B as MPS-R."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:27', '2015-10-20 11:10:02'),
('e16e96cd-862c-4b33-adcd-0643f10d9490', '[TSP-SyRTC-0152]', '"test case description":"[TSP-SyRTC-0152]Check TSP shall detect the failure of VPS in the initialization stage.[Source:[TSP-SyRS-0412]] [Source:[TSP-SyRS-0106]] [Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:12'),
('e1f71b7b-9710-4e4b-b566-13da38fc180d', '[TSP-SyRTC-0176]', '"test case description":"[TSP-SyRTC-0176]Check the interface of GetMsgCounter between TSP and the upper application.[Source:[TSP-External-SyID-0059]] [Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('e2863ff2-00be-4c44-8d83-36481e81821a', '[TSP-SyRTC-0129]', '"pre-condition":"STBY is on automatic status. The compile method of processing batch is provided by TSP."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:03'),
('e4801558-6e7a-4740-af57-e678963e44aa', '[TSP-SyRTC-0112]', '0', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:27', '2015-10-20 11:10:02'),
('e74336a5-90ae-4928-8173-16a61c473962', '[TSP-SyRTC-0143]', '"test case description":"[TSP-SyRTC-0143]Check that TSP can receive SUBSET037 messages from external device(500ms).[Source:[TSP-SyRS-0388]] [Source:[TSP-SyRS-0391]] [Source:[TSP-SyRS-0078]][Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('e88fb9c3-4ebd-4ca3-9cfa-e81fcaeaf435', '[TSP-SyRTC-0120]', '"test case description":"[TSP-SyRTC-0120]Check if TSP has implement GetAppConfigAddr interface.[Source: [TSP-External-SyID-0044]][Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:39', '2015-10-20 16:27:14'),
('e8beded4-d5ae-4070-9ee4-ad38b1cf11b7', '[TSP-SyRTC-0142]', '"test case description":"[TSP-SyRTC-0142]Check that the size of RSSP-II data that TSP shall support range from 1-1000(500ms).[Source:[TSP-SyRS-0384]] [Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('ebf9f366-4ca8-4f57-bf36-9b8863652e9f', '[TSP-SyRTC-0176]', '"pre-condition":"The communications between TSP and TSP test bench are normal.Set the STBY in AUTO."', '', '', '', '', '', '', '', 0, '', '7bb99792-ec8c-11e4-bbec-081d7a0ed70e', '2015-10-20 10:59:28', '2015-10-20 11:10:03'),
('ed66f1f4-9819-460b-a704-26851305d2b7', '[TSP-SyRTC-0183]', '"test case description":"[TSP-SyRTC-0183]Check TSP can exchange message with external device when STBY is changed or MPS-N shutdown(500ms).[Source:[TSP-SyRS-0002]] [Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:36', '2015-10-20 16:27:10'),
('f2848cf9-1e06-488d-95bb-977e0696edea', '[TSP-SyRTC-0128]', '"test case description":"[TSP-SyRTC-0128]Check that the size of SACEM data that TSP shall support range from 1-1394(336ms).[Source:[TSP-SyRS-0095]] [Source:[TSP-External-SyID-0025]][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:11'),
('f6bf6eff-b129-4e37-9020-c3772be3df9f', '[TSP-SyRTC-0129]', '"test case description":"[TSP-SyRTC-0129]Check TSP can supply the compile method of processing batch.[Source:[TSP-SyRS-0359]] [Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:39', '2015-10-20 16:27:14'),
('fa756243-2989-46c8-9db0-e94f6e978a3a', '[TSP-SyRTC-0110]', '"test case description":"[TSP-SyRTC-0110]Check if TSP shall not make hazard output even if MPS-N and MPS-R are switched in the unsynchronized condition.[Source:[TSP-SyRS-0184]] [Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:37', '2015-10-20 16:27:12'),
('fdefb61f-d1a4-4ac2-af1a-d803dc22f34a', '[TSP-SyRTC-0173]', '"test case description":"[TSP-SyRTC-0173]Check that TSP can not send/receive 1395 bytes SACEM data(336ms).[Source:[TSP-SyRS-0095]] [Source:[TSP-External-SyID-0025]][Safety:Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('fe0e4a22-004f-4458-af0a-e99fafda6ace', '[TSP-SyRTC-0153]', '"test case description":"[TSP-SyRTC-0153]Check that if TSP can receive/transmit 180 messages each cycle(500ms).[Source:[TSP-SyRS-0075]] [Source:[TSP-SyRS-0076]] [Source:[ TSP-External-SyID-0001]] [Source:[ TSP-External-SyID-0004]][Safety: No][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13'),
('feef3212-dc39-44de-9e0e-64f4a5c298f5', '[TSP-SyRTC-0156]', '"test case description":"[TSP-SyRTC-0156]Check the interface of GetSysTypeAndState between TSP and the upper application.[Source:[TSP-External-SyID-0051]] [Safety: Yes][End]"', '', '', '', '', '', '', '', 0, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-10-20 16:18:38', '2015-10-20 16:27:13');

-- --------------------------------------------------------

--
-- 表的结构 `tc_step`
--

CREATE TABLE IF NOT EXISTS `tc_step` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tc_id` varchar(36) NOT NULL COMMENT '所属tc的id',
  `num` varchar(11) DEFAULT NULL COMMENT '步骤数',
  `indata` varchar(255) NOT NULL,
  `actions` text NOT NULL COMMENT '采取的行动',
  `expected result` text NOT NULL COMMENT '所希望的现象',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tc_id` (`tc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=79 ;

--
-- 转存表中的数据 `tc_step`
--

INSERT INTO `tc_step` (`id`, `tc_id`, `num`, `indata`, `actions`, `expected result`, `created_at`, `updated_at`) VALUES
(73, '742396cf-c847-4123-8a46-3b39549217e7', '', '', 'Embed the code of APP simulator of MPU1-A and MPU2-A send different FSFB2(ZC)/RSSP-II(CCS) message to MCU2-A at the cycle 200.', '', '2015-10-11 01:07:09', '2015-10-11 01:07:09'),
(74, '742396cf-c847-4123-8a46-3b39549217e7', '', '', 'Power on MPS-A and MPS-B.', 'The lamp of panel display:MPS-A works as MPS-N.MPS-B works as MPS-R.', '2015-10-11 01:07:09', '2015-10-11 01:07:09'),
(75, '742396cf-c847-4123-8a46-3b39549217e7', '', '', 'After 200 cycles, check the working status of MPS-A and MPS-B.', 'MPS-A shall shut down.MPS-B start as MPS-N.SDMS shall record the inconsistent output data.', '2015-10-11 01:07:09', '2015-10-11 01:07:09'),
(76, '742396cf-c847-4123-8a46-3b39549217e7', '', '', 'Embed the code of APP simulator of MPU1-B and MPU2-B send FSFB2(ZC)/037(CCS) message to MCU2-B at the cycle 200.', '', '2015-10-11 01:07:10', '2015-10-11 01:07:10'),
(77, '742396cf-c847-4123-8a46-3b39549217e7', '', '', 'Power on MPS-B and MPS-A.', 'The lamp of panel display:MPS-A works as MPS-R.MPS-B works as MPS-N.', '2015-10-11 01:07:10', '2015-10-11 01:07:10'),
(78, '742396cf-c847-4123-8a46-3b39549217e7', '', '', 'After 200 cycles, check the working status of MPS-A and MPS-B.', 'MPS-B shall shut down.MPS-A works as MPS-N.SDMS shall record the inconsistent output data.', '2015-10-11 01:07:10', '2015-10-11 01:07:10');

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
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `testjob`
--

INSERT INTO `testjob` (`id`, `name`, `project_id`, `build_id`, `tc_version_id`, `rs_version_id`, `created_at`, `updated_at`) VALUES
('01f1e088-490a-495c-8624-fca063d1e515', 'test', '90640116-ad10-450c-9d30-91b8a6acc607', 'af196842-d459-11e4-8857-8f88fc59c6d4', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '', '2015-09-07 09:55:41', '2015-09-07 09:55:41'),
('7f2d49ae-3b76-448a-93ae-66050ab0f670', 'test0907', '90640116-ad10-450c-9d30-91b8a6acc607', 'af196842-d459-11e4-8857-8f88fc59c6d4', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '', '2015-09-07 11:00:41', '2015-09-07 11:00:41'),
('a1b3190f-4895-4de2-a486-dbb8328b83a2', 'test', '90640116-ad10-450c-9d30-91b8a6acc607', 'af196842-d459-11e4-8857-8f88fc59c6d4', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '', '2015-09-07 09:55:43', '2015-09-07 09:55:43'),
('cb1308d5-812b-434e-a38f-f416faca143c', 'test', '90640116-ad10-450c-9d30-91b8a6acc607', 'af196842-d459-11e4-8857-8f88fc59c6d4', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '', '2015-09-07 10:14:03', '2015-09-07 10:14:03');

-- --------------------------------------------------------

--
-- 表的结构 `testjob_rs_version`
--

CREATE TABLE IF NOT EXISTS `testjob_rs_version` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `testjob_id` varchar(36) NOT NULL,
  `rs_version_id` varchar(36) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `testjob_rs_version`
--

INSERT INTO `testjob_rs_version` (`id`, `testjob_id`, `rs_version_id`) VALUES
(1, '7f2d49ae-3b76-448a-93ae-66050ab0f670', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e'),
(2, '7f2d49ae-3b76-448a-93ae-66050ab0f670', '7980a81b-8978-4ca2-a0e0-e19c231424c3');

-- --------------------------------------------------------

--
-- 表的结构 `testmethod`
--

CREATE TABLE IF NOT EXISTS `testmethod` (
  `id` varchar(32) NOT NULL,
  `name` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
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
  `account` varchar(30) NOT NULL,
  `password` varchar(32) NOT NULL,
  `jobnumber` varchar(30) DEFAULT NULL,
  `realname` varchar(30) NOT NULL,
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '职位&角色',
  `islock` int(11) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`),
  UNIQUE KEY `jobnumber_2` (`jobnumber`),
  UNIQUE KEY `jobnumber_3` (`jobnumber`),
  UNIQUE KEY `jobnumber_4` (`jobnumber`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `account`, `password`, `jobnumber`, `realname`, `role_id`, `islock`, `created_at`, `updated_at`) VALUES
('4fb60451-915f-455f-ab44-e47a23615c46', 'admin', 'd41d8cd98f00b204e9800998ecf8427e', '000', 'admin', 1, 0, '2015-06-10 17:21:13', '2015-09-19 14:17:38'),
('639bd000-52ce-4ad5-aa90-9e50f43f8853', 'test3', 'd41d8cd98f00b204e9800998ecf8427e', '1211', '1212', 0, 0, '2015-10-20 14:49:05', '2015-10-20 14:55:15'),
('8515062e-9a1a-407c-bb18-e5c77266e8d3', 'huchangwu', '202cb962ac59075b964b07152d234b70', '1245', '胡长武', 0, 0, '2015-06-10 16:07:46', '2015-09-19 15:51:36'),
('9f3a8953-08dd-4e89-a5ca-52a9c77a6406', 'test', 'd41d8cd98f00b204e9800998ecf8427e', '1233', 'tester', 0, 0, '2015-02-12 06:38:32', '2015-09-19 14:18:34'),
('a7b12e32-b0f5-11e4-abb7-c17404b78885', 'guodong', '202cb962ac59075b964b07152d234b70', '123', '郭栋', 1, 0, '2015-02-10 00:00:00', '2015-06-10 17:20:23'),
('b9e80cdd-60cc-41cf-bce3-dc3c9a34d257', 'cjd', '9e357bc33cfecf281b3e4be4dae3870f', '999', 'caeng', 2, 0, '2015-09-20 20:51:40', '2015-11-02 13:42:38');

-- --------------------------------------------------------

--
-- 表的结构 `vatstr`
--

CREATE TABLE IF NOT EXISTS `vatstr` (
  `id` varchar(36) NOT NULL,
  `name` varchar(30) NOT NULL,
  `project_id` varchar(36) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `vatstr`
--

INSERT INTO `vatstr` (`id`, `name`, `project_id`, `created_at`, `updated_at`) VALUES
('687c4397-f6ea-422b-99ba-656fbeb1640a', '2', '90640116-ad10-450c-9d30-91b8a6acc607', '2015-06-12 04:28:43', '2015-06-12 04:28:43'),
('76037616-9f91-427e-8457-4eccef1cd9bd', '23', '90640116-ad10-450c-9d30-91b8a6acc607', '2015-06-12 04:28:43', '2015-06-12 04:28:43');

-- --------------------------------------------------------

--
-- 表的结构 `version`
--

CREATE TABLE IF NOT EXISTS `version` (
  `id` varchar(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `filename` text,
  `document_id` varchar(36) NOT NULL,
  `headers` text,
  `result` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `version`
--

INSERT INTO `version` (`id`, `name`, `filename`, `document_id`, `headers`, `result`, `created_at`, `updated_at`) VALUES
('07bfe4a0-2e09-4379-94e7-8495937a1da7', 'v3', '5625b32ce3334.doc', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', 'allocation,implement,priority', '增添0条,修改49条!', '2015-10-18 23:15:52', '2015-10-20 11:21:29'),
('0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', 'v1', NULL, 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', '', '', '2015-05-03 00:00:00', '2015-05-04 00:00:00'),
('29f5e6cd-0283-41ed-b49e-69f0d86b7a32', 'v2', '562386c8bbaef.doc', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', 'source', '', '2015-10-18 18:51:20', '2015-10-18 19:47:20'),
('42232e70-ebed-11e4-bbec-081d7a0ed70e', 'tcv1', '5625fad0437fc.doc', 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'test case description', '增添0条,修改77条!', '0000-00-00 00:00:00', '2015-10-20 16:27:14'),
('4b3df65f-c2da-4e9b-a991-c64c49dad8c3', 'v4', '5625b69e57e27.doc', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', 'priority', '增添49条,修改0条!', '2015-10-20 11:35:58', '2015-10-20 11:36:11'),
('69774c53-967f-4c17-a0c8-fca815820951', 'v6', '5636d4d8eaca4.doc', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', 'priority', '增添0条,修改49条!', '2015-10-20 13:13:34', '2015-11-02 11:13:40'),
('7980a81b-8978-4ca2-a0e0-e19c231424c3', 'v1', NULL, 'c00dc5ad-c45f-410c-9f88-b27562414c59', '', '', '2015-06-14 06:27:27', '2015-06-14 06:27:27'),
('7bb99792-ec8c-11e4-bbec-081d7a0ed70e', 'tcv2', '5625b079e7d0a.doc', 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'pre-condition', '增添0条,修改77条!', '0000-00-00 00:00:00', '2015-10-20 11:10:03'),
('a27b6482-6d73-4209-8f81-d1ec6499c3bd', 'v5', '5636d721b1d36.doc', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', 'allocation,priority', '增添0条,修改49条!', '2015-10-20 11:41:56', '2015-11-02 11:23:25');

-- --------------------------------------------------------

--
-- 视图结构 `tag`
--
DROP TABLE IF EXISTS `tag`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tag` AS select `rs`.`id` AS `id`,`rs`.`tag` AS `tag`,`rs`.`version_id` AS `version_id` from `rs` union select `tc`.`id` AS `id`,`tc`.`tag` AS `tag`,`tc`.`version_id` AS `version_id` from `tc`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
