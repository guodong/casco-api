-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-09-20 14:57:11
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
  `doc_noedit` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- 转存表中的数据 `project_user`
--

INSERT INTO `project_user` (`id`, `project_id`, `user_id`, `doc_noedit`) VALUES
(1, '90640116-ad10-450c-9d30-91b8a6acc607', 'a7b12e32-b0f5-11e4-abb7-c17404b78885', 'af31405d-3dbc-4349-95d2-d75ed03c5993,c00dc5ad-c45f-410c-9f88-b27562414c59,d6889236-ad21-11e4-aa9b-cf2d72b432dc,dc14a208-ad21-11e4-aa9b-cf2d72b432dc,e1c83444-ad21-11e4-aa9b-cf2d72b432dc,e7146468-ad21-11e4-aa9b-cf2d72b432dc,eca32cc0-ad21-11e4-aa9b-cf2d72b432dc'),
(2, '90640116-ad10-450c-9d30-91b8a6acc607', '75672e85-327f-46c4-90b2-aa68826f5889', '5c144c2c-f983-4d30-936b-0a17e00f8bfc, af31405d-3dbc-4349-95d2-d75ed03c5993, c00dc5ad-c45f-410c-9f88-b27562414c59, d6889236-ad21-11e4-aa9b-cf2d72b432dc, dc14a208-ad21-11e4-aa9b-cf2d72b432dc, e1c83444-ad21-11e4-aa9b-cf2d72b432dc, e7146468-ad21-11e4-aa9b-cf2d72b432dc, eca32cc0-ad21-11e4-aa9b-cf2d72b432dc'),
(4, '90640116-ad10-450c-9d30-91b8a6acc607', '8515062e-9a1a-407c-bb18-e5c77266e8d3', 'af31405d-3dbc-4349-95d2-d75ed03c5993,c00dc5ad-c45f-410c-9f88-b27562414c59,d6889236-ad21-11e4-aa9b-cf2d72b432dc,dc14a208-ad21-11e4-aa9b-cf2d72b432dc,e1c83444-ad21-11e4-aa9b-cf2d72b432dc,e7146468-ad21-11e4-aa9b-cf2d72b432dc,eca32cc0-ad21-11e4-aa9b-cf2d72b432dc'),
(5, 'd942b32c-3002-403b-a29d-90f11e30061b', '8515062e-9a1a-407c-bb18-e5c77266e8d3', ''),
(9, 'd942b32c-3002-403b-a29d-90f11e30061b', '57f64694-4b12-45b7-934f-4b3465dfc3d2', ''),
(10, '90640116-ad10-450c-9d30-91b8a6acc607', '57f64694-4b12-45b7-934f-4b3465dfc3d2', ''),
(11, '7', '57f64694-4b12-45b7-934f-4b3465dfc3d2', ''),
(12, '7', 'b9e80cdd-60cc-41cf-bce3-dc3c9a34d257', ''),
(13, '90640116-ad10-450c-9d30-91b8a6acc607', 'b9e80cdd-60cc-41cf-bce3-dc3c9a34d257', ''),
(14, 'd942b32c-3002-403b-a29d-90f11e30061b', 'b9e80cdd-60cc-41cf-bce3-dc3c9a34d257', '');

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
(1, 'Manager', '系统管理员');

-- --------------------------------------------------------

--
-- 表的结构 `rs`
--

CREATE TABLE IF NOT EXISTS `rs` (
  `id` varchar(36) NOT NULL COMMENT 'rsitems的主键',
  `tag` varchar(100) NOT NULL COMMENT '标签名称，添加索引',
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

INSERT INTO `rs` (`id`, `tag`, `description`, `implement`, `priority`, `contribution`, `category`, `allocation`, `vatstr_id`, `varstr_result`, `source_json`, `vat_json`, `version_id`, `created_at`, `updated_at`) VALUES
('9123ffb2-a6c5-11e4-b3f2-2eb11a8cf52a', '[TSP-SyRS-0004]', 'Safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n', ' 1.1.0', 'Average', ' SIL0', ' Functional', ' [TSP-SyAD]\r\n', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2014-12-09 03:50:31', '2015-08-25 13:45:22'),
('9123ffb2-a6c5-11e4-b3f2-2eb1e18cf52a', '[TSP-SyRS-0013]', 'Safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n', ' 1.1.0', 'Average', ' SIL0', ' Functional', ' [TSP-SyAD]\r\n', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2014-12-09 03:50:31', '2015-08-25 13:45:22'),
('9123ffb2-a6c5-11e4-b3f2-2eb1ea8cf52a', '[TSP-SyRS-0003]', 'Safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n', ' 1.1.0', 'Average', ' SIL0', ' Functional', ' [TSP-SyAD]\r\n', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2014-12-09 03:50:31', '2015-08-25 13:45:22'),
('9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52a', '[TSP-SyRS-0002]', 'Safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n', ' 1.1.0', 'Average', ' SIL0', ' Functional', ' [TSP-SyAD]\r\n', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2014-12-09 03:50:31', '2015-08-25 13:45:22'),
('9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b', '[TSP-SyRS-0001]', 'Trackside safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n', ' 1.1.0', 'High', ' SIL0', ' Functional', ' [TSP-SyAD]\r\n', '', 0, '', '[]', '0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', '2014-12-09 03:50:31', '2015-08-25 13:45:22');

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

INSERT INTO `tc` (`id`, `tag`, `description`, `testmethod_id`, `test_item`, `pre_condition`, `input`, `exec_step`, `exp_step`, `result`, `source_json`, `version_id`, `created_at`, `updated_at`) VALUES
('01e87349-d3b1-4985-b261-2de78719a825', '[TSP-SyRTC-0116]', '1', 'casco.model.Testmethod-1', '', '1', '', '', '', 1, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-01-29 05:55:57', '2015-06-17 07:54:23'),
('2e47eb3e-3582-49f8-9cf0-40a554841e7d', '[TSP-SyRTC-0115]', '2', '34af2239-94c5-486b-98a3-b118a75f', '', '2', '', '', '', 1, '[]', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-01-29 05:58:34', '2015-06-21 05:45:46'),
('399b97e7-776f-49f3-b4fe-ffc218f0ff55', '[TSP-SyRTC-0117]', 'Check TSP shall be a hot-redundant 2×2oo2 system.', '34af2239-94c5-486b-98a3-b118a75f,405b5232-5592-4303-8d79-307c80b7,beaa1f6d-f851-4522-80df-78bd4070', '', 'APP installed on A MPU1 and A MPU2 is the same as B MPU1 and B MPU2. ', '', '', '', 0, '["123-"]', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-01-28 08:22:08', '2015-06-21 05:44:52'),
('bf9839fe-31e2-495a-bf51-116ad2364c64', '13333245', '1', '', '', '132', '', '', '', 2, '', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-01-30 06:04:40', '2015-02-03 08:40:29'),
('fd7bdad4-c56c-410c-95b6-910e53f1dd6b', '[TSP-SyRTC-0117]', 'Check TSP shall be a hot-redundant 2×2oo2 system.', 'casco.model.Testmethod-1,34af2239-94c5-486b-98a3-b118a75f,405b5232-5592-4303-8d79-307c80b7,beaa1f6d-f851-4522-80df-78bd4070', '', 'APP installed on A MPU1 and A MPU2 is the same as B MPU1 and B MPU2. ', '', '', '', 1, '[]', '42232e70-ebed-11e4-bbec-081d7a0ed70e', '2015-01-28 08:23:47', '2015-06-21 05:45:09');

-- --------------------------------------------------------

--
-- 表的结构 `tc_step`
--

CREATE TABLE IF NOT EXISTS `tc_step` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tc_id` varchar(36) NOT NULL COMMENT '所属tc的id',
  `num` int(11) NOT NULL COMMENT '步骤数',
  `indata` varchar(255) NOT NULL,
  `actions` text NOT NULL COMMENT '采取的行动',
  `expected_result` text NOT NULL COMMENT '所希望的现象',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tc_id` (`tc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- 转存表中的数据 `tc_step`
--

INSERT INTO `tc_step` (`id`, `tc_id`, `num`, `indata`, `actions`, `expected_result`, `created_at`, `updated_at`) VALUES
(1, '87e9f18a-9801-4353-99c8-66090109f093', 3, '', '4', '4', '2015-02-02 03:35:34', '2015-02-02 03:35:34'),
(2, '87e9f18a-9801-4353-99c8-66090109f093', 2, '', '3', '3', '2015-02-02 03:35:34', '2015-02-02 03:35:34'),
(3, '87e9f18a-9801-4353-99c8-66090109f093', 1, '', '1', '1', '2015-02-02 03:35:34', '2015-02-02 03:35:34'),
(4, '7276c180-fd88-4d36-a9b6-724ea6efbd28', 1, '', '1', '1', '2015-01-30 05:37:24', '2015-01-30 05:37:24'),
(5, '7276c180-fd88-4d36-a9b6-724ea6efbd28', 2, '', '3', '3', '2015-01-30 05:37:24', '2015-01-30 05:37:24'),
(6, '7276c180-fd88-4d36-a9b6-724ea6efbd28', 3, '', '4', '4', '2015-01-30 05:37:24', '2015-01-30 05:37:24'),
(7, '996417bd-d5d9-4d8d-9e72-8283feb39a8f', 3, '', '4', '4', '2015-02-04 03:43:32', '2015-02-04 03:43:32'),
(8, '996417bd-d5d9-4d8d-9e72-8283feb39a8f', 2, '', '3', '3', '2015-02-04 03:43:32', '2015-02-04 03:43:32'),
(9, '996417bd-d5d9-4d8d-9e72-8283feb39a8f', 1, '', '1', '1', '2015-02-04 03:43:32', '2015-02-04 03:43:32'),
(10, '399b97e7-776f-49f3-b4fe-ffc218f0ff55', 3, '', '123', '3213微软', '2015-06-21 06:49:41', '2015-06-21 06:49:41'),
(11, '01e87349-d3b1-4985-b261-2de78719a825', 1, '', '1', '11', '2015-06-12 04:56:47', '2015-06-12 04:56:47'),
(12, 'bf9839fe-31e2-495a-bf51-116ad2364c64', 2, '', '33', '22', '2015-02-03 08:40:29', '2015-02-03 08:40:29'),
(13, 'fd7bdad4-c56c-410c-95b6-910e53f1dd6b', 1, '', '4', '4', '2015-06-21 05:45:09', '2015-06-21 05:45:09'),
(14, 'bf9839fe-31e2-495a-bf51-116ad2364c64', 1, '', '3', '3', '2015-02-03 08:40:29', '2015-02-03 08:40:29'),
(15, '399b97e7-776f-49f3-b4fe-ffc218f0ff55', 1, '', '2', '3', '2015-06-21 06:49:41', '2015-06-21 06:49:41'),
(16, '01e87349-d3b1-4985-b261-2de78719a825', 2, '', '', '', '2015-06-12 04:56:47', '2015-06-12 04:56:47'),
(17, '01e87349-d3b1-4985-b261-2de78719a825', 3, '', '', '', '2015-06-12 04:56:47', '2015-06-12 04:56:47'),
(18, '399b97e7-776f-49f3-b4fe-ffc218f0ff55', 2, '', '3', '3323', '2015-06-21 06:49:41', '2015-06-21 06:49:41');

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
('8515062e-9a1a-407c-bb18-e5c77266e8d3', 'huchangwu', '202cb962ac59075b964b07152d234b70', '1245', '胡长武', 0, 0, '2015-06-10 16:07:46', '2015-09-19 15:51:36'),
('9f3a8953-08dd-4e89-a5ca-52a9c77a6406', 'test', 'd41d8cd98f00b204e9800998ecf8427e', '1233', 'tester', 0, 0, '2015-02-12 06:38:32', '2015-09-19 14:18:34'),
('a7b12e32-b0f5-11e4-abb7-c17404b78885', 'guodong', '202cb962ac59075b964b07152d234b70', '123', '郭栋', 1, 0, '2015-02-10 00:00:00', '2015-06-10 17:20:23'),
('b9e80cdd-60cc-41cf-bce3-dc3c9a34d257', 'cjd', '6968aff4bf350b4804334f325a400543', '999', 'caeng', 0, 0, '2015-09-20 20:51:40', '2015-09-20 20:51:40');

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
  `document_id` varchar(36) NOT NULL,
  `headers` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `version`
--

INSERT INTO `version` (`id`, `name`, `document_id`, `headers`, `created_at`, `updated_at`) VALUES
('0aa31d8e-ebed-11e4-bbec-081d7a0ed70e', 'v1', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', '', '2015-05-03 00:00:00', '2015-05-04 00:00:00'),
('42232e70-ebed-11e4-bbec-081d7a0ed70e', 'tcv1', 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
('7980a81b-8978-4ca2-a0e0-e19c231424c3', 'v1', 'c00dc5ad-c45f-410c-9f88-b27562414c59', '', '2015-06-14 06:27:27', '2015-06-14 06:27:27'),
('7bb99792-ec8c-11e4-bbec-081d7a0ed70e', 'tcv2', 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- 视图结构 `tag`
--
DROP TABLE IF EXISTS `tag`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tag` AS select `rs`.`id` AS `id`,`rs`.`tag` AS `tag`,`rs`.`version_id` AS `version_id` from `rs` union select `tc`.`id` AS `id`,`tc`.`tag` AS `tag`,`tc`.`version_id` AS `version_id` from `tc`;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
