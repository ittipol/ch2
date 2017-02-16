-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2017 at 05:59 AM
-- Server version: 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ch`
--

-- --------------------------------------------------------

--
-- Table structure for table `item_categories`
--

CREATE TABLE `item_categories` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `top` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `item_categories`
--

INSERT INTO `item_categories` (`id`, `parent_id`, `name`, `description`, `top`, `active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'เสื้อผ้าและเครื่องแต่งกายสุภาพบุรุษ', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(2, NULL, 'เสื้อผ้าและเครื่องแต่งกายสุภาพสตรี', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(3, NULL, 'นาฬิกาและเครื่องประดับ', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(4, NULL, 'อุปกรณ์กีฬาและอุปกรณ์ช่วยออกกำลังกาย', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(5, NULL, 'สุขภาพและความงาม', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(6, NULL, 'แม่และเด็ก', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(7, NULL, 'ยานพาหนะ', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(8, NULL, 'ชุดและอุปกรณ์สำหรับขับขี่ยานพาหนะ', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(9, NULL, 'อะไหล่รถและประดับยนต์', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(10, NULL, 'คอมพิวเตอร์และโน๊ตบุค', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(11, NULL, 'โทรศัพท์มือถือและแท็บเล็ต', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(12, NULL, 'ทีวีและมอนิเตอร์', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(13, NULL, 'เกมและเครื่องเกม', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(14, NULL, 'กล้องและอุปกรณ์เสริม', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(15, NULL, 'เครื่องดนตรีและอุปกรณ์เสริม', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(16, NULL, 'เครื่องเสียงและชุดหูฟัง', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(17, NULL, 'เฟอร์นิเจอร์และของใช้ภายในบ้าน', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(18, NULL, 'เครื่องใช้ไฟฟ้า', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(19, NULL, 'สวนและเฟอร์นิเจอร์ตกแต่งบ้าน', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(20, NULL, 'เครื่องมือและอุปกรณ์ทำสวน', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(21, NULL, 'เครื่องมือและอุปกรณ์ช่าง', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(22, NULL, 'หนังสือ', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(23, NULL, 'สัตว์เลี้ยงและอุปกรณ์สำหรับสัตว์เลี้ยง', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(24, NULL, 'กระเป๋าและอุปกรณ์สำหรับการเดินทาง', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(25, NULL, 'ของเล่นและของสะสม ', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(26, NULL, 'เครื่องเขียนและอุปกรณ์จัดในสำนักงาน', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(27, NULL, 'อาหารและขนมขบเคี้ยว', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(28, NULL, 'เครื่องดื่ม', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20'),
(29, NULL, 'ขนมนำเข้าจากต่างประเทศ', NULL, 0, 1, '2017-01-20 06:15:20', '2017-01-20 06:15:20');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `item_categories`
--
ALTER TABLE `item_categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `item_categories`
--
ALTER TABLE `item_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
