-- phpMyAdmin SQL Dump
-- version 4.2.8.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-03-17 17:09:38
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
-- 表的结构 `ad`
--

CREATE TABLE IF NOT EXISTS `ad` (
`id` int(11) NOT NULL COMMENT 'ad的id',
  `doc_id` int(11) NOT NULL COMMENT '所属doc的id',
  `title` varchar(20) NOT NULL COMMENT 'item名称',
  `description` text NOT NULL COMMENT '描述',
  `category` varchar(20) NOT NULL COMMENT '类别',
  `contribution` varchar(10) NOT NULL COMMENT '贡献',
  `allocation` varchar(40) NOT NULL COMMENT '分配对象',
  `source_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `document`
--

CREATE TABLE IF NOT EXISTS `document` (
  `id` varchar(36) NOT NULL COMMENT '默认索引',
  `name` varchar(100) NOT NULL COMMENT '文档名称',
  `type` enum('rs','tc','ad','tr','folder') NOT NULL COMMENT '文档类型',
  `project_id` varchar(36) NOT NULL COMMENT '所属项目',
  `build_version` varchar(10) NOT NULL COMMENT '软件测试版本',
  `test_version` varchar(10) NOT NULL COMMENT '测试软件版本',
  `fid` varchar(36) NOT NULL DEFAULT '0',
  `regex` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `document`
--

INSERT INTO `document` (`id`, `name`, `type`, `project_id`, `build_version`, `test_version`, `fid`, `regex`, `created_at`, `updated_at`) VALUES
('d6889236-ad21-11e4-aa9b-cf2d72b432dc', 'TSP-SYRS', 'rs', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', 'dc14a208-ad21-11e4-aa9b-cf2d72b432dc', '', '2014-11-23 14:05:41', '2015-02-05 01:16:30'),
('dc14a208-ad21-11e4-aa9b-cf2d72b432dc', 'sytem', 'folder', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', '0', '', '2014-12-08 22:31:02', '2014-12-08 22:31:02'),
('e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'TSP-SYTC', 'tc', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', 'dc14a208-ad21-11e4-aa9b-cf2d72b432dc', '', '2014-11-23 13:21:30', '2015-01-28 00:00:44'),
('e7146468-ad21-11e4-aa9b-cf2d72b432dc', 'subsystem', 'folder', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', '0', '', '2014-12-08 15:42:01', '2014-12-08 15:42:01'),
('eca32cc0-ad21-11e4-aa9b-cf2d72b432dc', 'test case', 'tc', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', 'e7146468-ad21-11e4-aa9b-cf2d72b432dc', '', '2014-12-08 15:58:51', '2015-01-28 00:00:51'),
('a34c4ad1-d990-4181-919d-9186dae35f3b', '2123', 'rs', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', '0', '', '2015-03-09 19:02:03', '2015-03-09 19:02:03'),
('186cbc50-f915-4e14-a81b-fcbf0044bec9', '3', 'rs', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', '0', '', '2015-03-15 19:17:30', '2015-03-15 19:17:30'),
('f0df3528-c1bc-48c5-ae4f-8bbb7a039018', '2', 'rs', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', '0', '', '2015-03-15 19:17:08', '2015-03-15 19:17:08'),
('508206a3-ffb6-4bdf-bc43-ee9766096aa3', '1', 'rs', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', '0', '', '2015-03-15 19:16:48', '2015-03-15 19:16:48'),
('c00dc5ad-c45f-410c-9f88-b27562414c59', 'adf', 'rs', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', '0', '', '2015-02-06 01:36:36', '2015-02-06 01:36:36'),
('a1ef418b-f5c8-4b3f-8e60-1b941fbf19d8', '1', 'rs', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', '0', '', '2015-03-15 19:21:05', '2015-03-15 19:21:05'),
('144c2cff-9158-4b2b-bae9-231ba370b048', '11', 'rs', '7', '', '', '0', '', '2015-03-15 19:25:36', '2015-03-15 19:25:36'),
('edff4580-ef45-4cc3-bfb2-c6db5a7129b5', '11', 'rs', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', '0', '', '2015-03-16 00:38:48', '2015-03-16 00:38:48'),
('2a809e18-3967-46a7-9275-792482d0dce0', '11', 'rs', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', '0', '', '2015-03-16 00:39:38', '2015-03-16 00:39:38'),
('24cd2fd7-82b4-40d2-9375-d072563c4788', '11', 'rs', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', '0', '{"description":"\\\\d","implement":"","priority":"","contribution":"","category":"","allocation":""}', '2015-03-16 00:40:48', '2015-03-16 00:40:48'),
('348ff1ab-64fa-4508-92df-d76d3be4a0c2', '123', 'rs', '90640116-ad10-450c-9d30-91b8a6acc607', '', '', '0', '', '2015-03-16 01:13:22', '2015-03-16 01:13:22');

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

--
-- 转存表中的数据 `project`
--

INSERT INTO `project` (`id`, `name`, `description`, `graph`, `created_at`, `updated_at`) VALUES
('90640116-ad10-450c-9d30-91b8a6acc607', 'TSP-PJG', '卡斯卡项目', '{"cells":[{"type":"basic.Rect","position":{"x":64,"y":171},"size":{"width":150,"height":30},"angle":0,"id":"d6889236-ad21-11e4-aa9b-cf2d72b432dc","z":1,"attrs":{"rect":{"fill":"#E74C3C"},"text":{"text":"TSP-SYRS","fill":"white"}}},{"type":"basic.Rect","position":{"x":387,"y":60},"size":{"width":150,"height":30},"angle":0,"id":"e1c83444-ad21-11e4-aa9b-cf2d72b432dc","z":2,"attrs":{"rect":{"fill":"#8E44AD"},"text":{"text":"TSP-SYTC","fill":"white"}}},{"type":"basic.Rect","position":{"x":399,"y":162},"size":{"width":150,"height":30},"angle":0,"id":"eca32cc0-ad21-11e4-aa9b-cf2d72b432dc","z":3,"attrs":{"rect":{"fill":"#8E44AD"},"text":{"text":"test case","fill":"white"}}},{"type":"basic.Rect","position":{"x":100,"y":70},"size":{"width":150,"height":30},"angle":0,"id":"c00dc5ad-c45f-410c-9f88-b27562414c59","z":5,"attrs":{"rect":{"fill":"#E74C3C"},"text":{"text":"adf","fill":"white"}}},{"type":"fsa.Arrow","smooth":true,"source":{"id":"c00dc5ad-c45f-410c-9f88-b27562414c59"},"target":{"id":"d6889236-ad21-11e4-aa9b-cf2d72b432dc"},"id":"4e8924ea-ef36-4d1c-b0c1-4537371842b6","z":6,"attrs":{}},{"type":"fsa.Arrow","smooth":true,"source":{"id":"d6889236-ad21-11e4-aa9b-cf2d72b432dc"},"target":{"id":"eca32cc0-ad21-11e4-aa9b-cf2d72b432dc"},"id":"624b0f68-07be-4755-b9a4-85fb364c52d3","z":7,"attrs":{}},{"type":"fsa.Arrow","smooth":true,"source":{"id":"e1c83444-ad21-11e4-aa9b-cf2d72b432dc"},"target":{"id":"d6889236-ad21-11e4-aa9b-cf2d72b432dc"},"id":"e5aa1834-4c97-4c0f-83f9-949575b5ef0f","z":8,"attrs":{}},{"type":"fsa.Arrow","smooth":true,"source":{"id":"e1c83444-ad21-11e4-aa9b-cf2d72b432dc"},"target":{"id":"c00dc5ad-c45f-410c-9f88-b27562414c59"},"id":"beb173b0-fcbc-4c31-a207-46805f848769","z":9,"attrs":{}}]}', '2014-11-24 00:00:00', '2015-02-13 05:15:45'),
('7', 'RAIL-FORWARD', '12312', '{"cells":[{"type":"basic.Rect","position":{"x":207,"y":71},"size":{"width":150,"height":30},"angle":0,"id":"144c2cff-9158-4b2b-bae9-231ba370b048","z":1,"attrs":{"rect":{"fill":"#E74C3C"},"text":{"text":"11","fill":"white"}}}]}', '2014-11-23 18:32:34', '2015-03-16 05:20:07'),
('d942b32c-3002-403b-a29d-90f11e30061b', 'test', 'dd', '', '2015-03-10 11:40:20', '2015-03-10 11:40:20');

-- --------------------------------------------------------

--
-- 表的结构 `project_user`
--

CREATE TABLE IF NOT EXISTS `project_user` (
  `project_id` varchar(36) NOT NULL COMMENT '工程id',
  `user_id` varchar(36) NOT NULL COMMENT '员工id',
  `role` varchar(10) NOT NULL COMMENT '与user_role里面的role_type是否重复？'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `project_user`
--

INSERT INTO `project_user` (`project_id`, `user_id`, `role`) VALUES
('90640116-ad10-450c-9d30-91b8a6acc607', 'a7b12e32-b0f5-11e4-abb7-c17404b78885', 'TE'),
('90640116-ad10-450c-9d30-91b8a6acc607', '75672e85-327f-46c4-90b2-aa68826f5889', ''),
('7', 'a7b12e32-b0f5-11e4-abb7-c17404b78885', '');

-- --------------------------------------------------------

--
-- 表的结构 `relation`
--

CREATE TABLE IF NOT EXISTS `relation` (
  `src` varchar(36) NOT NULL COMMENT '文档关系起点',
  `dest` varchar(36) NOT NULL COMMENT '文档关系终点',
  `src_type` varchar(10) NOT NULL COMMENT '源item所属类别',
  `dest_type` varchar(10) NOT NULL COMMENT '覆盖item所属类别',
  `src_tag` varchar(20) NOT NULL COMMENT '源tag内容',
  `dest_tag` varchar(20) NOT NULL COMMENT '目标tag内容'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `relation`
--

INSERT INTO `relation` (`src`, `dest`, `src_type`, `dest_type`, `src_tag`, `dest_tag`) VALUES
('c00dc5ad-c45f-410c-9f88-b27562414c59', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', '', '', '', ''),
('d6889236-ad21-11e4-aa9b-cf2d72b432dc', 'eca32cc0-ad21-11e4-aa9b-cf2d72b432dc', '', '', '', ''),
('c00dc5ad-c45f-410c-9f88-b27562414c59', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', '', '', '', ''),
('d6889236-ad21-11e4-aa9b-cf2d72b432dc', 'eca32cc0-ad21-11e4-aa9b-cf2d72b432dc', '', '', '', ''),
('e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', '', '', '', ''),
('c00dc5ad-c45f-410c-9f88-b27562414c59', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', '', '', '', ''),
('d6889236-ad21-11e4-aa9b-cf2d72b432dc', 'eca32cc0-ad21-11e4-aa9b-cf2d72b432dc', '', '', '', ''),
('e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', '', '', '', ''),
('e1c83444-ad21-11e4-aa9b-cf2d72b432dc', 'c00dc5ad-c45f-410c-9f88-b27562414c59', '', '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `id` varchar(36) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `role`
--

INSERT INTO `role` (`id`, `name`) VALUES
('c3f765c2-b32d-11e4-a4f2-8784007343f5', '系统管理员'),
('c3f76ee6-b32d-11e4-a4f2-8784007343f5', '项目管理员'),
('ca6e0848-b32d-11e4-a4f2-8784007343f5', '测试人员');

-- --------------------------------------------------------

--
-- 表的结构 `role_user`
--

CREATE TABLE IF NOT EXISTS `role_user` (
  `role_id` varchar(36) NOT NULL,
  `user_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `root`
--

CREATE TABLE IF NOT EXISTS `root` (
  `admin` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL DEFAULT '8888'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `root`
--

INSERT INTO `root` (`admin`, `password`) VALUES
('zhanghui', '8888');

-- --------------------------------------------------------

--
-- 表的结构 `rs`
--

CREATE TABLE IF NOT EXISTS `rs` (
  `id` varchar(36) NOT NULL COMMENT 'rsitems的主键',
  `document_id` varchar(36) NOT NULL COMMENT 'rsitems所属文献',
  `tag` varchar(20) NOT NULL COMMENT '标签名称，添加索引',
  `description` text NOT NULL COMMENT '标签描述',
  `implement` varchar(10) NOT NULL COMMENT '手段',
  `priority` varchar(20) NOT NULL COMMENT '优先级',
  `contribution` varchar(10) NOT NULL COMMENT '安全性',
  `category` varchar(20) NOT NULL COMMENT '类别',
  `allocation` varchar(200) NOT NULL COMMENT '分配对象',
  `vatstr_id` varchar(36) NOT NULL COMMENT '对应管理员分配的vat',
  `varstr_result` int(1) NOT NULL DEFAULT '0',
  `version` varchar(30) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `rs`
--

INSERT INTO `rs` (`id`, `document_id`, `tag`, `description`, `implement`, `priority`, `contribution`, `category`, `allocation`, `vatstr_id`, `varstr_result`, `version`, `created_at`, `updated_at`) VALUES
('9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', '[TSP-SyRS-0001]', 'Trackside safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n', ' 1.1.0', 'High', ' SIL0', ' Functional', ' [TSP-SyAD]\r\n', '', 0, '', '2014-12-09 03:50:31', '2015-03-10 02:57:25'),
('9123ffb2-a6c5-11e4-b3f2-2eb11a8cf52a', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', '[TSP-SyRS-0004]', 'Safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n', ' 1.1.0', 'Average', ' SIL0', ' Functional', ' [TSP-SyAD]\r\n', '6ffd5327-f853-4b05-bcad-f741536afb93', 0, '', '2014-12-09 03:50:31', '2015-03-12 07:19:11'),
('9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52a', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', '[TSP-SyRS-0002]', 'Safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n', ' 1.1.0', 'Average', ' SIL0', ' Functional', ' [TSP-SyAD]\r\n', '', 0, '', '2014-12-09 03:50:31', '2015-03-10 02:57:25'),
('9123ffb2-a6c5-11e4-b3f2-2eb1ea8cf52a', 'd6889236-ad21-11e4-aa9b-cf2d72b432dc', '[TSP-SyRS-0003]', 'Safety product shall consist of TSP and application software (APP).\r\nTrackside safety product shall consist of TSP and application software (APP).\r\n轨旁安全产品由TSP和应用软件（APP）组成。\r\n', ' 1.1.0', 'Average', ' SIL0', ' Functional', ' [TSP-SyAD]\r\n', '6ffd5327-f853-4b05-bcad-f741536afb93', 0, '', '2014-12-09 03:50:31', '2015-03-12 07:19:11');

-- --------------------------------------------------------

--
-- 表的结构 `rs_tc`
--

CREATE TABLE IF NOT EXISTS `rs_tc` (
`id` int(10) unsigned NOT NULL,
  `rs_tag` varchar(30) NOT NULL,
  `tc_tag` varchar(30) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `rs_tc`
--

INSERT INTO `rs_tc` (`id`, `rs_tag`, `tc_tag`) VALUES
(1, '1', '1'),
(2, '0', '8'),
(3, '1', '10'),
(4, '5', '10'),
(5, '11', '11'),
(6, '5', '11'),
(7, '10', '11'),
(8, '13', '11'),
(9, '16', '11'),
(10, '20', '11'),
(11, '5', '12'),
(12, '8', '12'),
(13, '10', '12'),
(14, '5', '13'),
(15, '8', '13'),
(16, '10', '13'),
(17, '0', '14'),
(18, '14', '15'),
(19, '13', '15');

-- --------------------------------------------------------

--
-- 表的结构 `rs_vat`
--

CREATE TABLE IF NOT EXISTS `rs_vat` (
  `rs_id` varchar(36) NOT NULL,
  `vat_id` varchar(36) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `rs_vat`
--

INSERT INTO `rs_vat` (`rs_id`, `vat_id`) VALUES
('28b38466-a6c0-11e4-b3f2-2eb1ec8cf52b', 'fd7bdad4-c56c-410c-95b6-910e53f1dd6b'),
('28b38466-a6c0-11e4-b3f2-2eb1ec8cf52b', '399b97e7-776f-49f3-b4fe-ffc218f0ff55'),
('28b38466-a6c0-11e4-b3f2-2eb1ec8cf52b', '01e87349-d3b1-4985-b261-2de78719a825'),
('9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b', '9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b'),
('9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b', '399b97e7-776f-49f3-b4fe-ffc218f0ff55');

-- --------------------------------------------------------

--
-- 替换视图以便查看 `tag`
--
CREATE TABLE IF NOT EXISTS `tag` (
`id` varchar(36)
,`tag` varchar(20)
,`document_id` varchar(36)
);
-- --------------------------------------------------------

--
-- 表的结构 `tc`
--

CREATE TABLE IF NOT EXISTS `tc` (
  `id` varchar(36) NOT NULL COMMENT '单个tc标签id',
  `document_id` varchar(36) NOT NULL COMMENT 'tc所属文献id',
  `tag` varchar(20) NOT NULL COMMENT 'tc名称',
  `description` text NOT NULL COMMENT 'tc描述',
  `testmethod_id` varchar(32) NOT NULL COMMENT '测试方法',
  `test_item` varchar(100) NOT NULL,
  `pre_condition` text NOT NULL COMMENT '前提条件',
  `result` tinyint(1) NOT NULL DEFAULT '0' COMMENT '结果，0未测试，1表示成功，2表示失败',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tc`
--

INSERT INTO `tc` (`id`, `document_id`, `tag`, `description`, `testmethod_id`, `test_item`, `pre_condition`, `result`, `created_at`, `updated_at`) VALUES
('399b97e7-776f-49f3-b4fe-ffc218f0ff55', 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', '[TSP-SyRTC-0117]', 'Check TSP shall be a hot-redundant 2×2oo2 system.', '34af2239-94c5-486b-98a3-b118a75f', '', 'APP installed on A MPU1 and A MPU2 is the same as B MPU1 and B MPU2. ', 0, '2015-01-28 08:22:08', '2015-03-12 05:14:23'),
('fd7bdad4-c56c-410c-95b6-910e53f1dd6b', 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', '[TSP-SyRTC-0117]', 'Check TSP shall be a hot-redundant 2×2oo2 system.', '34af2239-94c5-486b-98a3-b118a75f', '', 'APP installed on A MPU1 and A MPU2 is the same as B MPU1 and B MPU2. ', 1, '2015-01-28 08:23:47', '2015-03-12 06:46:31'),
('01e87349-d3b1-4985-b261-2de78719a825', 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', '1', '1', 'EP', '', '1', 1, '2015-01-29 05:55:57', '2015-02-03 08:59:43'),
('2e47eb3e-3582-49f8-9cf0-40a554841e7d', 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', '2', '2', '34af2239-94c5-486b-98a3-b118a75f', '', '2', 0, '2015-01-29 05:58:34', '2015-03-17 02:18:56'),
('87e9f18a-9801-4353-99c8-66090109f093', 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', '1', '1', 'EG', '', '1', 0, '2015-01-29 08:37:51', '2015-02-02 03:35:34'),
('7276c180-fd88-4d36-a9b6-724ea6efbd28', 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', '1', '1', 'EP', '', '1', 0, '2015-01-30 05:37:24', '2015-01-30 05:37:24'),
('996417bd-d5d9-4d8d-9e72-8283feb39a8f', 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', '1', '1', 'EP', '', '1', 0, '2015-01-30 05:40:31', '2015-01-30 05:40:31'),
('bf9839fe-31e2-495a-bf51-116ad2364c64', 'e1c83444-ad21-11e4-aa9b-cf2d72b432dc', '13333245', '1', 'EP', '', '132', 2, '2015-01-30 06:04:40', '2015-02-03 08:40:29');

-- --------------------------------------------------------

--
-- 表的结构 `tc_source`
--

CREATE TABLE IF NOT EXISTS `tc_source` (
`id` int(10) unsigned NOT NULL,
  `tc_id` varchar(36) NOT NULL,
  `source_id` varchar(36) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tc_source`
--

INSERT INTO `tc_source` (`id`, `tc_id`, `source_id`) VALUES
(2, 'fd7bdad4-c56c-410c-95b6-910e53f1dd6b', '9123ffb2-a6c5-11e4-b3f2-2eb11a8cf52a'),
(5, '2e47eb3e-3582-49f8-9cf0-40a554841e7d', '9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b'),
(6, '2e47eb3e-3582-49f8-9cf0-40a554841e7d', '9123ffb2-a6c5-11e4-b3f2-2eb11a8cf52a'),
(7, '399b97e7-776f-49f3-b4fe-ffc218f0ff55', '9123ffb2-a6c5-11e4-b3f2-2eb1ec8cf52b'),
(8, '399b97e7-776f-49f3-b4fe-ffc218f0ff55', '9123ffb2-a6c5-11e4-b3f2-2eb11a8cf52a');

-- --------------------------------------------------------

--
-- 表的结构 `tc_step`
--

CREATE TABLE IF NOT EXISTS `tc_step` (
  `tc_id` varchar(36) NOT NULL COMMENT '所属tc的id',
  `num` int(11) NOT NULL COMMENT '步骤数',
  `actions` text NOT NULL COMMENT '采取的行动',
  `expected_result` text NOT NULL COMMENT '所希望的现象',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tc_step`
--

INSERT INTO `tc_step` (`tc_id`, `num`, `actions`, `expected_result`, `created_at`, `updated_at`) VALUES
('87e9f18a-9801-4353-99c8-66090109f093', 3, '4', '4', '2015-02-02 03:35:34', '2015-02-02 03:35:34'),
('87e9f18a-9801-4353-99c8-66090109f093', 2, '3', '3', '2015-02-02 03:35:34', '2015-02-02 03:35:34'),
('87e9f18a-9801-4353-99c8-66090109f093', 1, '1', '1', '2015-02-02 03:35:34', '2015-02-02 03:35:34'),
('7276c180-fd88-4d36-a9b6-724ea6efbd28', 1, '1', '1', '2015-01-30 05:37:24', '2015-01-30 05:37:24'),
('7276c180-fd88-4d36-a9b6-724ea6efbd28', 2, '3', '3', '2015-01-30 05:37:24', '2015-01-30 05:37:24'),
('7276c180-fd88-4d36-a9b6-724ea6efbd28', 3, '4', '4', '2015-01-30 05:37:24', '2015-01-30 05:37:24'),
('996417bd-d5d9-4d8d-9e72-8283feb39a8f', 3, '4', '4', '2015-02-04 03:43:32', '2015-02-04 03:43:32'),
('996417bd-d5d9-4d8d-9e72-8283feb39a8f', 2, '3', '3', '2015-02-04 03:43:32', '2015-02-04 03:43:32'),
('996417bd-d5d9-4d8d-9e72-8283feb39a8f', 1, '1', '1', '2015-02-04 03:43:32', '2015-02-04 03:43:32'),
('399b97e7-776f-49f3-b4fe-ffc218f0ff55', 1, '2', '3', '2015-03-17 02:44:45', '2015-03-17 02:44:45'),
('01e87349-d3b1-4985-b261-2de78719a825', 1, '1', '11', '2015-02-03 08:59:43', '2015-02-03 08:59:43'),
('bf9839fe-31e2-495a-bf51-116ad2364c64', 2, '33', '22', '2015-02-03 08:40:29', '2015-02-03 08:40:29'),
('fd7bdad4-c56c-410c-95b6-910e53f1dd6b', 1, '4', '4', '2015-03-17 02:12:24', '2015-03-17 02:12:24'),
('399b97e7-776f-49f3-b4fe-ffc218f0ff55', 3, '123', '3213微软', '2015-03-17 02:44:45', '2015-03-17 02:44:45'),
('bf9839fe-31e2-495a-bf51-116ad2364c64', 1, '3', '3', '2015-02-03 08:40:29', '2015-02-03 08:40:29'),
('399b97e7-776f-49f3-b4fe-ffc218f0ff55', 2, '3', '3323', '2015-03-17 02:44:45', '2015-03-17 02:44:45');

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
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `jobnumber`, `password`, `account`, `realname`, `created_at`, `updated_at`) VALUES
('75672e85-327f-46c4-90b2-aa68826f5889', '1231', '202cb962ac59075b964b07152d234b70', '3123', 'tom', '2015-02-12 07:00:53', '2015-03-12 06:47:51'),
('9f3a8953-08dd-4e89-a5ca-52a9c77a6406', '12332', 'd9b1d7db4cd6e70935368a1efb10e377', 'test', 'tester', '2015-02-12 06:38:32', '2015-03-12 06:47:20'),
('a7b12e32-b0f5-11e4-abb7-c17404b78885', '123', '202cb962ac59075b964b07152d234b70', 'guodong', '郭栋', '2015-02-10 00:00:00', '2015-02-10 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `vatstr`
--

CREATE TABLE IF NOT EXISTS `vatstr` (
  `id` varchar(36) NOT NULL,
  `name` varchar(30) NOT NULL,
  `project_id` varchar(36) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `vatstr`
--

INSERT INTO `vatstr` (`id`, `name`, `project_id`, `created_at`, `updated_at`) VALUES
('6ffd5327-f853-4b05-bcad-f741536afb93', '2', '90640116-ad10-450c-9d30-91b8a6acc607', '2015-03-12 06:06:46', '2015-03-12 06:06:46');

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
-- Indexes for table `ad`
--
ALTER TABLE `ad`
 ADD PRIMARY KEY (`id`), ADD KEY `doc_id` (`doc_id`), ADD KEY `title` (`title`), ADD KEY `source_id` (`source_id`);

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
 ADD KEY `src` (`src`), ADD KEY `dest` (`dest`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `root`
--
ALTER TABLE `root`
 ADD PRIMARY KEY (`admin`);

--
-- Indexes for table `rs`
--
ALTER TABLE `rs`
 ADD PRIMARY KEY (`id`), ADD KEY `title` (`tag`), ADD FULLTEXT KEY `title_2` (`tag`);

--
-- Indexes for table `rs_tc`
--
ALTER TABLE `rs_tc`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tc`
--
ALTER TABLE `tc`
 ADD PRIMARY KEY (`id`), ADD KEY `doc_id` (`document_id`), ADD KEY `title` (`tag`);

--
-- Indexes for table `tc_source`
--
ALTER TABLE `tc_source`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tc_step`
--
ALTER TABLE `tc_step`
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
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `account` (`account`);

--
-- Indexes for table `vatstr`
--
ALTER TABLE `vatstr`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ad`
--
ALTER TABLE `ad`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ad的id';
--
-- AUTO_INCREMENT for table `document`
--
ALTER TABLE `document`
AUTO_INCREMENT=43;
--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `rs_tc`
--
ALTER TABLE `rs_tc`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `tc_source`
--
ALTER TABLE `tc_source`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
