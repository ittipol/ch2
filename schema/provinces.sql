-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2017 at 06:00 AM
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
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `name`) VALUES
(1, 'กระบี่'),
(2, 'กรุงเทพมหานคร'),
(3, 'กาญจนบุรี'),
(4, 'กาฬสินธุ์'),
(5, 'กำแพงเพชร'),
(6, 'ขอนแก่น'),
(7, 'จันทบุรี'),
(8, 'ฉะเชิงเทรา'),
(9, 'ชลบุรี'),
(10, 'ชัยนาท'),
(11, 'ชัยภูมิ'),
(12, 'ชุมพร'),
(13, 'เชียงราย'),
(14, 'เชียงใหม่'),
(15, 'ตรัง'),
(16, 'ตราด'),
(17, 'ตาก'),
(18, 'นครนายก'),
(19, 'นครปฐม'),
(20, 'นครพนม'),
(21, 'นครราชสีมา'),
(22, 'นครศรีธรรมราช'),
(23, 'นครสวรรค์'),
(24, 'นนทบุรี'),
(25, 'นราธิวาส'),
(26, 'น่าน'),
(27, 'บึงกาฬ'),
(28, 'บุรีรัมย์'),
(29, 'ปทุมธานี'),
(30, 'ประจวบคีรีขันธ์'),
(31, 'ปราจีนบุรี'),
(32, 'ปัตตานี'),
(33, 'พระนครศรีอยุธยา'),
(34, 'พะเยา'),
(35, 'พังงา'),
(36, 'พัทลุง'),
(37, 'พิจิตร'),
(38, 'พิษณุโลก'),
(39, 'เพชรบุรี'),
(40, 'เพชรบูรณ์'),
(41, 'แพร่'),
(42, 'ภูเก็ต'),
(43, 'มหาสารคาม'),
(44, 'มุกดาหาร'),
(45, 'แม่ฮ่องสอน'),
(46, 'ยโสธร'),
(47, 'ยะลา'),
(48, 'ร้อยเอ็ด'),
(49, 'ระนอง'),
(50, 'ระยอง'),
(51, 'ราชบุรี'),
(52, 'ลพบุรี'),
(53, 'ลำปาง'),
(54, 'ลำพูน'),
(55, 'เลย'),
(56, 'ศรีสะเกษ'),
(57, 'สกลนคร'),
(58, 'สงขลา'),
(59, 'สตูล'),
(60, 'สมุทรปราการ'),
(61, 'สมุทรสงคราม'),
(62, 'สมุทรสาคร'),
(63, 'สระแก้ว'),
(64, 'สระบุรี'),
(65, 'สิงห์บุรี'),
(66, 'สุโขทัย'),
(67, 'สุพรรณบุรี'),
(68, 'สุราษฎร์ธานี'),
(69, 'สุรินทร์'),
(70, 'หนองคาย'),
(71, 'หนองบัวลำภู'),
(72, 'อ่างทอง'),
(73, 'อำนาจเจริญ'),
(74, 'อุดรธานี'),
(75, 'อุตรดิตถ์'),
(76, 'อุทัยธานี'),
(77, 'อุบลราชธานี');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
