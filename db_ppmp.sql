-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2025 at 02:00 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ppmp`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL,
  `type_id` int(11) NOT NULL,
  `partno` varchar(255) DEFAULT NULL,
  `title` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `type_id`, `partno`, `title`, `created_at`, `updated_at`) VALUES
(1, 3, 'Part I', 'Items & Specifications', '2019-05-16 19:02:22', '0000-00-00 00:00:00'),
(2, 3, 'Part II', 'Other common items', '2019-05-16 19:02:35', '0000-00-00 00:00:00'),
(4, 2, NULL, 'can', '2019-05-18 02:17:24', '0000-00-00 00:00:00'),
(6, 2, NULL, 'bottle', '2019-05-18 02:20:18', '0000-00-00 00:00:00'),
(10, 2, NULL, 'roll', '2019-05-18 02:24:27', '0000-00-00 00:00:00'),
(12, 2, NULL, 'box', '2019-05-18 02:25:46', '0000-00-00 00:00:00'),
(15, 2, NULL, 'pack', '2019-05-18 11:20:32', '0000-00-00 00:00:00'),
(23, 2, NULL, 'bundle', '2019-05-18 11:22:36', '0000-00-00 00:00:00'),
(25, 2, NULL, 'pad', '2019-05-18 11:22:50', '0000-00-00 00:00:00'),
(29, 2, NULL, 'piece', '2019-05-18 11:24:58', '0000-00-00 00:00:00'),
(31, 2, NULL, 'ream', '2019-05-18 11:25:16', '0000-00-00 00:00:00'),
(40, 2, NULL, 'book', '2019-05-18 11:26:51', '0000-00-00 00:00:00'),
(59, 2, NULL, 'unit', '2019-05-18 11:30:08', '0000-00-00 00:00:00'),
(112, 2, NULL, 'jar', '2019-05-18 14:57:46', '0000-00-00 00:00:00'),
(115, 2, NULL, 'bar', '2019-05-18 15:34:23', '0000-00-00 00:00:00'),
(128, 2, NULL, 'set', '2019-05-19 00:41:34', '0000-00-00 00:00:00'),
(155, 2, NULL, 'pair', '2019-05-19 02:26:25', '0000-00-00 00:00:00'),
(167, 2, NULL, 'cart', '2019-05-21 21:24:54', '0000-00-00 00:00:00'),
(369, 1, NULL, 'Pesticides or Pest Repellents', '2019-05-27 18:33:31', '0000-00-00 00:00:00'),
(370, 1, NULL, 'Paper Materials and Products', '2019-05-27 18:34:07', '0000-00-00 00:00:00'),
(371, 1, NULL, 'ICT', '2025-05-29 04:08:03', '0000-00-00 00:00:00'),
(372, 1, NULL, 'sample', '2025-06-03 13:48:30', '0000-00-00 00:00:00'),
(374, 1, NULL, 'Printer or Facsimile or Photocopier Supplies', NULL, NULL),
(375, 1, NULL, 'Printed Publications', NULL, NULL),
(376, 1, NULL, 'Manufacturing Components and Supplies', NULL, NULL),
(377, 1, NULL, 'Office Equipment and Accessories and Supplies', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ceiling_amounts`
--

CREATE TABLE `ceiling_amounts` (
  `id` int(11) NOT NULL,
  `cppmt_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `create_ppmps`
--

CREATE TABLE `create_ppmps` (
  `id` int(11) NOT NULL,
  `year` varchar(255) NOT NULL,
  `start` varchar(255) NOT NULL,
  `end` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `create_ppmps`
--

INSERT INTO `create_ppmps` (`id`, `year`, `start`, `end`, `status`, `created_at`, `updated_at`) VALUES
(1, '2025', '2025-01-01', '2025-07-31', 2, '2025-06-06 23:30:55', '2025-06-08 00:17:02'),
(2, '2026', '2025-06-01', '2025-06-30', 1, '2025-06-07 22:18:43', '2025-06-08 00:17:02');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(10) UNSIGNED NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `item_spec` longtext NOT NULL,
  `amount` varchar(255) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `measure` varchar(255) NOT NULL,
  `partno` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `item_code`, `item_spec`, `amount`, `category`, `measure`, `partno`, `created_at`, `updated_at`) VALUES
(1, '10191509-IN-A01', ' INSECTICIDE, aerosol type, net content: 600ml min\r\n', '139.36', '369', '4', '1', '2019-05-18 02:19:06', '2025-04-11 19:04:27'),
(2, '12191601-AL-E01', ' INSECTICIDE, aerosol type, net content: 600ml min\r\n\r\n', '43.99', '369', '6', '1', '2019-05-18 02:21:43', '2019-05-24 19:18:08'),
(3, '12171703-SI-P01', 'STAMP PAD INK, purple or violet, water based ink, 30ml\r\n', '24.63', '369', '6', '1', '2019-05-18 02:24:01', '2019-05-24 19:18:43'),
(4, '13111203-AC-F01', 'ACETATE, thickness: 0.075mm min (gauge #3)\r\n', '737.64', '369', '10', '1', '2019-05-18 02:25:25', '2019-05-24 19:19:11'),
(5, '13111201-CF-P02', 'CARBON FILM, PE, black, size 210mm x 297mm\r\n', '208.52', '369', '12', '1', '2019-05-18 02:26:23', '2019-05-24 19:19:39'),
(6, '13111201-CF-P02', 'CARBON FILM, PE, black, size 216mm x 330mm\r\n', '208.52', '369', '12', '1', '2019-05-18 02:27:27', '2019-05-24 19:20:07'),
(7, '14111525-CA-A01', 'CARTOLINA, assorted colors\r\n', '72.78', '370', '15', '1', '2019-05-18 11:55:45', '2019-05-24 19:21:40'),
(8, '14111506-CF-L11', 'CONTINUOUS FORM, 1 PLY, 280 x 378mm\r\n', '682.24', '370', '12', '1', '2019-05-18 11:57:12', '2019-05-24 19:34:21'),
(9, '14111506-CF-L12', 'CONTINUOUS FORM, 1 PLY, 280 x 378mm\r\n', '1029.6', '370', '12', '1', '2019-05-18 11:58:22', '2019-05-24 19:37:09'),
(10, '14111506-CF-L22', 'CONTINUOUS FORM, 2 ply, 280 x 378mm, carbonless', '1300', '370', '12', '1', '2019-05-18 11:59:26', '2019-05-24 19:37:42'),
(11, '14111506-CF-L21', 'CONTINUOUS FORM, 2 ply, 280mm x 241mm, carbonless\r\n', '765.44', '370', '12', '1', '2019-05-18 12:01:29', '2019-05-24 19:38:26'),
(12, '14111506-CF-L31', 'CONTINUOUS FORM, SHORT,  3 PLY, 280 x 241mm, carbonless\r\n', '596.8', '370', '12', '1', '2019-05-18 12:02:40', '2019-05-24 19:39:26'),
(13, '14111506-CF-L32', 'CONTINUOUS FORM, 3 PLY, 280 x 378mm, carbonless\r\n', '1034.8', '370', '12', '1', '2019-05-18 12:39:47', '2019-05-24 19:40:09'),
(14, '14111609-LL-C01', 'LOOSELEAF COVER, made of chipboard, for legal\r\n', '670.7', '370', '23', '1', '2019-05-18 12:41:02', '2019-05-24 23:03:07'),
(15, '14111514-NP-S02', 'NOTE PAD, stick on, 50mm x 76mm (2\" x 3\") min\r\n', '32.22', '370', '25', '1', '2019-05-18 12:42:06', '2019-05-24 23:03:56'),
(16, '14111514-NP-S04', 'NOTE PAD, stick on, 76mm x 100mm (3\" x 4\") min\r\n', '56.06', '370', '25', '1', '2019-05-18 12:43:00', '2019-05-24 23:04:49'),
(17, '14111514-NP-S03', 'NOTE PAD, stick on, 76mm x 76mm (3\" x 3\") min\r\n', '41.50', '370', '25', '1', '2019-05-18 12:44:50', '2019-05-24 23:05:38'),
(18, '14111514-NB-S01', 'NOTEBOOK, STENOGRAPHER, spiral, 40 leaves\r\n', '12.04', '370', '31', '1', '2019-05-18 14:26:11', '2019-05-24 23:06:28'),
(19, '14111507-PP-M01', 'PAPER, MULTICOPY, 80gsm, size: 210mm x 297mm\r\n', '132.37', '370', '31', '1', '2019-05-18 14:29:50', '2019-05-24 23:07:13'),
(20, '14111507-PP-M02', 'PAPER, MULTICOPY, 80gsm, size: 216mm x 330mm\r\nucts\r\n', '154.57', '370', '31', '1', '2019-05-18 14:32:53', '2019-05-25 00:30:17'),
(21, '14111507-PP-C01', 'PAPER, Multi-Purpose (COPY) A4, 70 gsm\r\n', '114.51', '370', '31', '1', '2019-05-18 14:41:47', '2019-05-25 00:31:50'),
(22, '14111507-PP-C02', 'PAPER, Multi-Purpose (COPY) Legal, 70 gsm\r\n', '129.98', '370', '31', '1', '2019-05-18 14:47:21', '2019-05-25 00:32:38'),
(24, '14111503-PA-P01', 'PAPER, PARCHMENT, size: 210 x 297mm, multi-purpose\r\n', '96.20', '370', '31', '1', '2019-05-18 14:49:22', '2019-05-25 00:34:25'),
(25, '14111818-TH-P02', 'PAPER, THERMAL, 55gsm, size: 216mm±1mm x 30m-0.3m\r\n', '48.78', '370', '10', '1', '2019-05-18 14:50:09', '2019-05-25 00:42:36'),
(26, '14111531-RE-B01', 'RECORD BOOK, 300 PAGES, size: 214mm x 278mm min\r\n', '70.72', '370', '40', '1', '2019-05-18 14:51:12', '2019-05-25 00:47:11'),
(27, '14111531-RE-B02', 'RECORD BOOK, 500 PAGES, size: 214mm x 278mm min\r\n', '101.92', '370', '40', '1', '2019-05-18 14:51:50', '2019-05-25 00:47:44'),
(28, '14111704-TT-P01', 'TOILET TISSUE PAPER 2-plys sheets, 150 pulls\r\n', '65.42', '370', '15', '1', '2019-05-18 14:52:58', '2019-05-25 00:48:30'),
(29, '26111702-BT-A01', 'BATTERY, dry cell, AA, 2 pieces per blister pack\r\n', '19.73', '370', '15', '1', '2019-05-18 14:55:09', '2019-05-25 00:49:27'),
(30, '26111702-BT-A02', 'BATTERY, dry cell, AAA, 2 pieces per blister pack\r\n', '19.5', '370', '15', '1', '2019-05-18 14:55:49', '2019-05-25 00:50:05'),
(31, '26111702-BT-A03', 'BATTERY, dry cell, D, 1.5 volts, alkaline\r\n', '88.4', 'Batteries and Cells and Accessories', '15', '1', '2019-05-18 14:56:43', '2019-05-25 00:51:04'),
(32, ' 31201610-GL-J01', 'GLUE, all purpose, gross weight: 200 grams min\r\n', '47.82', 'Manufacturing Components and Supplies', '112', '1', '2019-05-18 14:58:28', '2019-05-25 00:51:48'),
(33, '31151804-SW-H01', 'STAPLE WIRE, for heavy duty staplers, (23/13)\r\n', '20.68', 'Manufacturing Components and Supplies', '12', '1', '2019-05-18 14:59:34', '2019-05-25 00:52:32'),
(34, '31151804-SW-S01', 'STAPLE WIRE, STANDARD, (26/6)\r\n', '20.05', 'Manufacturing Components and Supplies', '12', '1', '2019-05-18 15:00:57', '2019-05-25 00:54:02'),
(35, '31201502-TA-E01', 'TAPE, ELECTRICAL, 18mm x 16M min\r\n', '18.2', 'Manufacturing Components and Supplies', '10', '1', '2019-05-18 15:04:37', '2019-05-25 00:54:34'),
(36, '31201503-TA-M01', 'TAPE, MASKING, width: 24mm (±1mm)\r\n', '55.12', 'Manufacturing Components and Supplies', '10', '1', '2019-05-18 15:05:23', '2019-05-25 00:55:14'),
(37, '31201503-TA-M02', 'TAPE, MASKING, width: 48mm (±1mm)\r\n', '106.6', 'Manufacturing Components and Supplies', '10', '1', '2019-05-18 15:06:18', '2019-05-25 00:55:57'),
(38, '31201517-TA-P01', 'TAPE, PACKAGING, width: 48mm (±1mm)\r\n', '18.2', 'Manufacturing Components and Supplies', '10', '1', '2019-05-18 15:08:28', '2019-05-25 00:56:41'),
(39, '31201512-TA-T01', 'TAPE, TRANSPARENT, width: 24mm (±1mm)\r\n', '9.1', 'Manufacturing Components and Supplies', '6', '1', '2019-05-18 15:09:31', '2019-05-25 00:57:10'),
(40, '31201512-TA-T02', 'TAPE, TRANSPARENT, width: 48mm (±1mm)\r\n', '18.2', 'Manufacturing Components and Supplies', '10', '1', '2019-05-18 15:10:38', '2019-05-25 00:57:56'),
(41, '31151507-TW-P01', 'TWINE, plastic, one (1) kilo per roll\r\n', '50.96', 'Manufacturing Components and Supplies', '10', '1', '2019-05-18 15:11:48', '2019-05-25 01:04:23'),
(42, '40101604-EF-G01', 'ELECTRIC FAN, INDUSTRIAL, ground type, metal blade\r\n', '974.48', 'Heating and Ventilation and Air Circulation', '59', '1', '2019-05-18 15:18:26', '2019-05-25 01:05:15'),
(43, '40101604-EF-C01', 'ELECTRIC FAN, ORBIT type, ceiling,  metal blade\r\n', '1192.88', 'Heating and Ventilation and Air Circulation', '59', '1', '2019-05-18 15:21:18', '2019-05-25 01:05:58'),
(44, '40101604-EF-S01', 'ELECTRIC FAN, STAND type, plastic blade\r\n', '1006.39', 'Heating and Ventilation and Air Circulation', '59', '1', '2019-05-18 15:22:54', '2019-05-25 01:06:47'),
(45, '40101604-EF-W01', 'ELECTRIC FAN, WALL type, plastic blade\r\n', '669.66', 'Heating and Ventilation and Air Circulation', '59', '1', '2019-05-18 15:24:32', '2019-05-25 01:07:37'),
(46, '39101605-FL-T01', 'FLUORESCENT LAMP,  18 WATTS, linear tubular (T8)\r\n', '40.56', 'Lighting and Fixtures and Accessories', '29', '1', '2019-05-18 15:25:40', '2019-05-25 01:08:32'),
(47, '39101628-LB-L01', 'Ligth Bulb, LED, 7 watts 1 pc in individual box\r\n', '72.49', 'Lighting and Fixtures and Accessories', '29', '1', '2019-05-18 15:26:22', '2019-05-25 01:09:09'),
(48, '41111604-RU-P02', 'RULER, plastic, 450mm (18\"), width: 38mm min\r\n', '15.48', 'Measuring and Observing and Testing Equipment', '29', '1', '2019-05-18 15:27:06', '2019-05-25 01:09:54'),
(49, '47131812-AF-A01', 'AIR FRESHENER, aerosol, 280ml/150g min\r\n', '86.06', 'Cleaning Equipment and Supplies', '4', '1', '2019-05-18 15:28:09', '2019-05-25 01:10:32'),
(50, '47131604-BR-S01', 'BROOM, soft (tambo)\r\n', '130', 'Cleaning Equipment and Supplies', '29', '1', '2019-05-18 15:30:10', '2019-05-25 01:11:17'),
(51, '47131604-BR-T01', 'BROOM, STICK (TING-TING), usable length: 760mm min\r\n', '30.58', 'Cleaning Equipment and Supplies', '29', '1', '2019-05-18 15:31:02', '2019-05-25 01:11:57'),
(52, '47131829-TB-C01', 'CLEANER,TOILET BOWL AND URINAL, 900ml-1000ml cap\r\n', '41.60', 'Cleaning Equipment and Supplies', '6', '1', '2019-05-18 15:32:01', '2019-05-25 01:12:47'),
(53, '47131805-CL-P01', 'CLEANSER, SCOURING POWDER, 350g min./can\r\n', '23.92', 'Cleaning Equipment and Supplies', '4', '1', '2019-05-18 15:33:26', '2019-05-25 01:13:23'),
(54, '47131811-DE-B02', 'DETERGENT BAR, 140 grams as packed\r\n', '8.01', 'Cleaning Equipment and Supplies', '115', '1', '2019-05-18 15:36:51', '2019-05-25 01:14:34'),
(55, '47131811-DE-P02', 'DETERGENT POWDER, all purpose, 1kg\r\n', '37.43', 'Cleaning Equipment and Supplies', '15', '1', '2019-05-18 15:38:03', '2019-05-25 01:15:19'),
(56, '47131803-DS-A01', 'DISINFECTANT SPRAY, aerosol type, 400-550 grams\r\n', '122.98', 'Cleaning Equipment and Supplies', '4', '1', '2019-05-18 15:43:49', '2019-05-25 01:16:08'),
(57, '47131601-DU-P01', 'DUST PAN, non-rigid plastic, w/ detachable handle\r\n', '24.84', 'Cleaning Equipment and Supplies', '29', '1', '2019-05-18 15:44:31', '2019-05-25 01:16:48'),
(58, '47131802-FW-P02', 'FLOOR WAX, PASTE, RED\r\n', '269.36', 'Cleaning Equipment and Supplies', '4', '1', '2019-05-18 15:45:41', '2019-05-25 01:17:22'),
(59, '47131830-FC-A01', 'FURNITURE CLEANER, aerosol type, 300ml min per can\r\n', '87.36', 'Cleaning Equipment and Supplies', '4', '1', '2019-05-18 15:46:47', '2019-05-25 01:18:21'),
(60, '47121804-MP-B01', 'MOP BUCKET, heavy duty, hard plastic\r\n', '1911', 'Cleaning Equipment and Supplies', '59', '1', '2019-05-18 15:48:26', '2019-05-25 01:21:04'),
(61, '47131613-MP-H02', 'MOPHANDLE, heavy duty, aluminum, screw type\r\n', '145.6', 'Cleaning Equipment and Supplies', '29', '1', '2019-05-18 15:49:46', '2019-05-25 01:22:06'),
(62, '47131619-MP-R01', 'MOPHEAD, made of rayon, weight: 400 grams min\r\n', '110.24', 'Cleaning Equipment and Supplies', '29', '1', '2019-05-18 15:50:41', '2019-05-25 01:23:28'),
(63, '47131501-RG-C01', 'RAGS, all cotton, 32 pieces per kilogram min\r\n', '49.69', 'Cleaning Equipment and Supplies', '23', '1', '2019-05-18 15:51:46', '2019-05-25 01:25:26'),
(64, '47131602-SC-N01', 'SCOURING PAD, made of synthetic nylon, 140 x 220mm\r\n', '102.96', 'Cleaning Equipment and Supplies', '15', '1', '2019-05-18 15:53:17', '2019-05-25 12:21:06'),
(65, '47121701-TB-P02', 'TRASHBAG, plastic, transparent\r\n', '139.88', 'Cleaning Equipment and Supplies', '10', '1', '2019-05-18 15:54:01', '2019-05-25 12:22:29'),
(66, '47121702-WB-P01', 'WASTEBASKET, non-rigid plastic\r\n', '23.59', 'Cleaning Equipment and Supplies', '29', '1', '2019-05-18 15:54:42', '2019-05-25 12:23:21'),
(67, '43211507-DCT-03', 'Desktop Computer, branded\r\n', '39208', 'Information and Communication Technology (ICT) Equipment and Devices and Accessories', '59', '1', '2019-05-18 16:00:23', '2019-05-25 12:37:20'),
(68, '43202003-DV-W01', 'DVD REWRITABLE, speed: 4x min, 4.7GB capacity min\r\n', '21.79', 'Information and Communication Technology (ICT) Equipment and Devices and Accessories', '29', '1', '2019-05-18 16:01:14', '2019-05-25 12:40:40'),
(69, '43201827-HD-X02', 'EXTERNAL HARD DRIVE, 1TB, 2.5\"HDD, USB 3.0\r\n', '2724.8', 'Information and Communication Technology (ICT) Equipment and Devices and Accessories', '29', '1', '2019-05-18 16:02:48', '2019-05-25 12:44:20'),
(70, '43202010-FD-U01', 'FLASH DRIVE, 16 GB capacity\r\n', '276.64', 'Information and Communication Technology (ICT) Equipment and Devices and Accessories', '29', '1', '2019-05-18 16:03:45', '2019-05-25 12:45:16'),
(71, '43211503-LCT-02', 'Laptop Computer, branded\r\n', '35916.4', 'Information and Communication Technology (ICT) Equipment and Devices and Accessories', '59', '1', '2019-05-18 16:04:44', '2019-05-25 12:46:07'),
(72, '43211503-LCT-02', 'MOUSE, optical, USB connection type\r\n', '134.99', 'Information and Communication Technology (ICT) Equipment and Devices and Accessories', '59', '1', '2019-05-18 16:05:57', '2019-05-25 12:47:37'),
(73, '43212102-PR-D02', 'PRINTER, IMPACT DOT MATRIX, 24 pins, 136 column\r\n', '33131.28', 'Information and Communication Technology (ICT) Equipment and Devices and Accessories', '59', '1', '2019-05-18 16:12:52', '2019-05-25 12:51:37'),
(74, '43212102-PR-D01', 'PRINTER, IMPACT DOT MATRIX, 9 pins, 80 columns\r\n', '7995.52', 'Information and Communication Technology (ICT) Equipment and Devices and Accessories', '59', '1', '2019-05-18 16:13:59', '2019-05-25 12:52:19'),
(75, '43212105-PR-L01', 'PRINTER, LASER, monochrome, network-ready\r\n', '724.88', 'Information and Communication Technology (ICT) Equipment and Devices and Accessories', '59', '1', '2019-05-18 16:15:27', '2019-05-25 12:53:16'),
(76, '44121710-CH-W01', 'CHALK, molded, white, dustless, length: 78mm min\r\n', '25.68', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 00:21:35', '2019-05-25 12:54:08'),
(77, '44122105-BF-C01', 'CLIP, BACKFOLD, all metal, clamping: 19mm (-1mm)\r\n', '7.57', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 00:22:24', '2019-05-25 12:54:39'),
(78, '44122105-BF-C02', 'CLIP, BACKFOLD, all metal, clamping: 25mm (-1mm)\r\n', '13.4', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 00:23:14', '2019-05-25 12:55:36'),
(79, '44122105-BF-C03', 'CLIP, BACKFOLD, all metal, clamping: 32mm (-1mm)\r\n', '20.55', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 00:24:03', '2019-05-25 12:56:34'),
(80, '44122105-BF-C04', 'CLIP, BACKFOLD, all metal, clamping: 50mm (-1mm)\r\n', '39.52', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 00:25:04', '2019-05-25 12:57:05'),
(81, '44121801-CT-R01', 'CORRECTION TAPE, film base type, UL 6m min\r\n', '17.56', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 00:25:44', '2019-05-25 12:59:01'),
(82, '44111515-DF-B01', 'DATA FILE BOX, made of chipboard, with closed ends\r\n', '69.78', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 00:26:52', '2019-05-25 12:59:38'),
(83, '44122011-DF-F01', 'DATA FOLDER, made of chipboard, taglia lock\r\n', '68.64', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 00:28:48', '2019-05-25 13:00:13'),
(84, '44121506-EN-D01', 'ENVELOPE, DOCUMENTARY, for A4 size document\r\n', '408.14', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 00:31:02', '2019-05-25 13:00:44'),
(85, '44121506-EN-D02', 'ENVELOPE, DOCUMENTARY, for legal size document\r\n', '518.08', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 00:31:53', '2019-05-25 13:01:20'),
(86, '44121506-EN-X01', 'ENVELOPE, EXPANDING, KRAFTBOARD,for legal size doc\r\n', '738.4', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 00:32:45', '2019-05-25 13:01:54'),
(87, '44121506-EN-X02', 'ENVELOPE, EXPANDING, PLASTIC, 0.50mm thickness min\r\n', '30.49', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 00:35:00', '2019-05-25 13:03:01'),
(88, '44121506-EN-M01', 'ENVELOPE, MAILING,white, 80gsm (-5%)\r\n', '328.64', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 00:35:55', '2019-05-25 13:03:55'),
(89, '44121504-EN-W01', 'ENVELOPE, mailing, white, with window\r\n', '410.80', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 00:36:52', '2019-05-25 13:04:35'),
(90, '44111912-ER-B01', 'ERASER, FELT, for blackboard/whiteboard\r\n', '11.11', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 00:38:33', '2019-05-25 13:05:11'),
(91, '44122118-FA-P01', 'FASTENER, METAL, 70mm between prongs\r\n', '78.92', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 00:40:12', '2019-05-25 13:05:53'),
(92, '44111515-FO-X01', 'FILE ORGANIZER, expanding, plastic, 12 pockets\r\n', '70.61', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 00:40:52', '2019-05-25 13:06:41'),
(93, '44122018-FT-D01', 'FILE TAB DIVIDER, bristol board, for A4\r\n', '12.48', 'Office Equipment and Accessories and Supplies', '128', '1', '2019-05-19 00:42:21', '2019-05-25 13:07:24'),
(94, '44122018-FT-D02', 'FILE TAB DIVIDER, bristol board, for legal\r\n', '16.64', 'Office Equipment and Accessories and Supplies', '128', '1', '2019-05-19 00:43:30', '2019-05-25 13:07:49'),
(95, '44122011-FO-F01', 'FOLDER, FANCY, for A4 size documents\r\n', '253.29', 'Office Equipment and Accessories and Supplies', '23', '1', '2019-05-19 00:44:53', '2019-05-25 13:08:23'),
(96, '44122011-FO-F02', 'FOLDER, FANCY, for legal size documents\r\n', '291.2', 'Office Equipment and Accessories and Supplies', '23', '1', '2019-05-19 01:12:51', '2019-05-25 13:09:16'),
(97, '44122027-FO-P01', ' Office Equipment and Accessories aFOLDER, PRESSBOARD, size: 240mm x 370mm (-5mm)\r\nand Supplies\r\n', '746.72', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 01:14:11', '2019-05-25 13:13:30'),
(98, '44122011-FO-T03', 'FOLDER, TAGBOARD, for A4 size documents\r\n', '217.36', 'Office Equipment and Accessories and Supplies', '15', '1', '2019-05-19 01:15:03', '2019-05-25 13:14:13'),
(99, '44122011-FO-T04', 'FOLDER, TAGBOARD, for legal size documents\r\n', '279.64', 'Office Equipment and Accessories and Supplies', '15', '1', '2019-05-19 01:16:02', '2019-05-25 13:14:45'),
(100, '44122008-IT-T01', 'INDEX TAB, self-adhesive, transparent\r\n', '51.88', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 01:17:47', '2019-05-25 13:15:16'),
(101, '44111515-MF-B02', 'MAGAZINE FILE BOX, LARGE size, made of chipboard\r\n', '41.6', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 01:18:55', '2019-05-25 13:15:50'),
(102, '44121716-MA-F01', 'MARKER, FLUORESCENT, 3 assorted colors per set\r\n', '37.23', 'Office Equipment and Accessories and Supplies', '128', '1', '2019-05-19 01:20:11', '2019-05-25 13:16:21'),
(103, '44121708-MW-B01', 'MARKER, whiteboard, black, felt tip, bullet type\r\n', '10.31', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 01:21:02', '2019-05-25 13:17:16'),
(104, '44121708-MW-B02', 'MARKER, whiteboard, black, felt tip, bullet type\r\n', '10.31', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 01:22:28', '2019-05-25 21:59:01'),
(105, '44121708-MW-B03', 'MARKER, whiteboard, blue, felt tip, bullet type\r\n', '10.31', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 01:23:56', '2019-05-25 22:01:20'),
(106, '44121708-MP-B01', 'MARKER, PERMANENT, bullet type, black\r\n', '9.65', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 01:25:52', '2019-05-25 22:03:10'),
(107, '44121708-MP-B02', 'MARKER, PERMANENT, bullet type, blue\r\n', '9.65', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 01:27:11', '2019-05-25 22:03:58'),
(108, '44122104-PC-G01', 'PAPER CLIP, vinyl/plastic coat, length: 32mm min\r\n', '5.98', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 01:30:58', '2019-05-25 22:05:22'),
(109, '44122104-PC-J02', 'PAPER CLIP, vinyl/plastic coat, length: 48mm min\r\n', '12.74', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 01:33:46', '2019-05-25 22:06:01'),
(110, '44121706-PE-L01', 'PENCIL, lead, w/ eraser, wood cased, hardness: HB\r\n', '20.79', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 01:35:06', '2019-05-25 22:06:42'),
(111, '44122037-RB-P10', 'RING BINDER, 80 rings, plastic, 32mm x 1.12m\r\n', '201.64', 'Office Equipment and Accessories and Supplies', '23', '1', '2019-05-19 01:36:18', '2019-05-25 22:07:24'),
(112, '44122101-RU-B01', 'STAMP PAD, FELT, bed dimension: 60mm x 100mm min\r\n', '96.72', 'Office Equipment and Accessories and Supplies', '12', '1', '2019-05-19 01:37:20', '2019-05-25 22:08:29'),
(113, '44121905-SP-F01', 'RUBBER BAND, 70mm min lay flat length (#18)\r\nSTAMP PAD, FELT, bed dimension: 60mm x 100mm min\r\n', '27.66', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 01:38:51', '2019-05-25 22:11:23'),
(114, '44121612-BL-H01', 'CUTTER BLADE, for heavy duty cutter\r\n', '11.77', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 01:40:11', '2019-05-25 22:17:26'),
(115, '44121612-CU-H01', 'CUTTER KNIFE, for general purpose\r\n', '27.4', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 01:41:00', '2019-05-25 22:19:00'),
(116, '44103202-DS-M01', 'DATING AND STAMPING MACHINE, heavy duty\r\n', '478.38', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 02:24:08', '2019-05-25 22:19:31'),
(117, '44121619-PS-M01', 'PENCIL SHARPENER, manual, single cutter head\r\n', '187.2', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 02:25:10', '2019-05-25 22:21:31'),
(118, '44101602-PU-P01', 'PUNCHER, paper, heavy duty, with two hole guide\r\n', '131.96', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 02:25:53', '2019-05-25 22:22:02'),
(119, '44121618-SS-S01', 'SCISSORS, symmetrical, blade length: 65mm min\r\n', '15.6', 'Office Equipment and Accessories and Supplies', '155', '1', '2019-05-19 02:27:09', '2019-05-25 22:22:37'),
(120, '44121615-ST-S01', 'STAPLER, STANDARD TYPE, load cap: 200 staples min\r\n', '82.16', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 02:28:04', '2019-05-25 22:23:24'),
(121, '44121615-ST-B01', 'STAPLER, BINDER TYPE, heavy duty, desktop\r\n', '878.8', 'Office Equipment and Accessories and Supplies', '59', '1', '2019-05-19 02:29:22', '2019-05-25 22:23:59'),
(122, '44121613-SR-P01	', 'STAPLE REMOVER, PLIER-TYPE\r\n', '18.18', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 02:30:19', '2019-05-25 22:24:42'),
(123, '44121605-TD-T01', 'TAPE DISPENSER, TABLE TOP, for 24mm width tape\r\n', '55.83', 'Office Equipment and Accessories and Supplies', '29', '1', '2019-05-19 02:31:28', '2019-05-25 22:25:17'),
(124, '44101602-PB-M01', 'BINDING AND PUNCHING MACHINE, binding cap: 50mm\r\n', '10400', 'Office Equipment and Accessories and Supplies', '59', '1', '2019-05-19 02:32:35', '2019-05-25 22:37:50'),
(125, '44101807-CA-C01', 'CALCULATOR, compact, 12 digits\r\n', '135.2', 'Office Equipment and Accessories and Supplies', '59', '1', '2019-05-19 02:33:29', '2019-05-25 22:38:35'),
(126, '44101714-FX-M01', 'FACSIMILE MACHINE, uses thermal paper\r\n', '4711.2', 'Office Equipment and Accessories and Supplies', '59', '1', '2019-05-19 02:34:16', '2019-05-25 22:39:09'),
(127, '44101601-PT-M01', 'PAPER TRIMMER/CUTTING MACHINE, max paper size: B4\r\n', '8088.08', 'Office Equipment and Accessories and Supplies', '59', '1', '2019-05-19 02:35:15', '2019-05-25 22:39:39'),
(128, '44101603-PS-M01', 'PAPER SHREDDER, cutting width: 3mm-4mm (Entry Level)\r\n', '5699.2', 'Office Equipment and Accessories and Supplies', '59', '1', '2019-05-19 02:36:34', '2019-05-25 22:40:09'),
(129, '44103109-BR-D05', 'DRUM CART, BROTHER DR-3455 \r\n', '6864', '374', '167', '1', '2019-05-21 21:25:51', '2019-05-25 22:45:15'),
(130, '44103109-BR-D05', 'INK CART, CANON CL-741, Col.\r\n', '1001.52', '374', '167', '1', '2019-05-21 21:26:48', '2019-05-25 22:50:42'),
(131, '44103105-CA-C02', 'INK CART, CANON CL-811, Colored\r\n', '1029.6', '374', '167', '1', '2019-05-21 21:27:43', '2019-05-25 22:52:11'),
(132, '44103105-CA-B04', 'INK CART, CANON PG-740, Black \r\n', '754', '374', '167', '1', '2019-05-21 21:29:07', '2019-05-25 22:53:21'),
(133, '44103105-EP-C17', 'INK CART, EPSON C13T664200 (T6642), Cyan\r\n', '254.8', '374', '167', '1', '2019-05-22 01:08:13', '2019-05-25 22:54:53'),
(134, '44103105-EP-M17', 'INK CART, EPSON C13T664300 (T6643), Magenta\r\n', '254.8', '374', '167', '1', '2019-05-22 01:09:54', '2019-05-25 22:56:05'),
(135, '44103105-EP-Y17', 'INK CART, EPSON C13T664400 (T6644), Yellow\r\n', '254.8', '374', '167', '1', '2019-05-22 01:15:00', '2019-05-25 22:57:23'),
(136, '44103105-HP-B40', 'INK CART, HP C2P04AA (HP62) Black \r\n', '254.8', '374', '167', '1', '2019-05-22 01:16:39', '2019-05-25 23:00:13'),
(137, '44103105-HP-T40', 'INK CART, HP C2P06AA (HP62) Tri-color \r\n', '254.8', '374', '167', '1', '2019-05-22 01:18:56', '2019-05-25 23:00:48'),
(138, '44103105-HP-B09', 'INK CART, HP C9351AA, (HP21), Black\r\n', '658.32', '374', '167', '1', '2019-05-22 02:27:15', '2019-05-25 23:01:57'),
(139, '44103105-HP-T10', 'INK CART, HP C9352AA, (HP22), Tri-color\r\n', '745.68', '374', '167', '1', '2019-05-22 02:28:27', '2019-05-25 23:02:48'),
(140, '44103105-HP-T30', 'INK CART, HP C9363WA, (HP97), Tri-color\r\n', '1492.4', '374', '167', '1', '2019-05-22 02:29:38', '2019-05-25 23:03:35'),
(141, '44103105-HP-P48', 'INK CART, HP C9397A (HP72) 69ml Photo Black\r\n', '1996.8', '374', '167', '1', '2019-05-22 02:31:17', '2019-05-25 23:04:14'),
(142, '44103105-HP-C48', 'INK CART, HP C9398A (HP72) 69ml Cyan\r\n', '1996.8', '374', '167', '1', '2019-05-22 02:32:16', '2019-05-25 23:06:18'),
(143, '44103105-HP-M48', 'INK CART, HP C9399A (HP72) 69ml Magenta\r\n', '1996.8', '374', '167', '1', '2019-05-22 02:33:17', '2019-05-25 23:05:36'),
(144, '44103105-HP-Y48', 'INK CART, HP C9400A (HP72) 69ml Yellow \r\n', '1996.8', '374', '167', '1', '2019-05-22 02:34:16', '2019-05-25 23:07:13'),
(145, '44103105-HP-G48', 'INK CART, HP C9401A (HP72) 69ml Gray \r\n', '1996.8', '374', '167', '1', '2019-05-22 02:35:26', '2019-05-25 23:07:50'),
(146, '44103105-HP-B48', 'INK CART, HP C9403A (HP72) 130ml Matte Black\r\n', '3016', '374', '167', '1', '2019-05-22 02:41:39', '2019-05-25 23:09:00'),
(147, '44103105-HP-B17', 'INK CART, HP CC640WA, (HP60),  Black\r\n', '650', '374', '167', '1', '2019-05-22 02:42:54', '2019-05-25 23:10:57'),
(148, '44103105-HP-T17', 'INK CART, HP CC643WA, (HP60), Tri-color\r\n', '766.48', '374', '167', '1', '2019-05-22 02:43:58', '2019-05-25 23:11:35'),
(149, '44103105-HP-B35', 'INK CART, HP CD887AA, (HP703), Black\r\n', '339.04', '374', '167', '1', '2019-05-22 02:44:50', '2019-05-25 23:12:34'),
(150, '44103105-HP-T35', 'INK CART, HP CD888AA, (HP703), Tri-color\r\n', '339.04', '374', '167', '1', '2019-05-22 02:45:39', '2019-05-25 23:13:39'),
(151, '44103105-HX-C40', 'INK CART, HP CD972AA, (HP 920XL), Cyan\r\n', '629.2', '374', '167', '1', '2019-05-22 02:46:32', '2019-05-27 11:50:54'),
(152, '44103105-HX-M40', 'INK CART, HP CD973AA, (HP 920XL), Magenta\r\n', '629.2', '374', '167', '1', '2019-05-22 02:47:21', '2019-05-27 11:51:52'),
(153, '44103105-HX-Y40', 'INK CART, HP CD974AA, (HP 920XL), Yellow\r\n', '629.2', '374', '167', '1', '2019-05-22 02:48:10', '2019-05-27 11:52:28'),
(154, '44103105-HX-B40', 'INK CART, HP CD975AA, (HP 920XL), Black\r\n', '1242.8', '374', '167', '1', '2019-05-22 02:48:56', '2019-05-27 11:53:09'),
(155, '44103105-HP-B20', 'INK CART, HP CH561WA, (HP61), Black\r\n', '644.8', '374', '167', '1', '2019-05-22 02:49:42', '2019-05-27 11:53:42'),
(156, '44103105-HP-B49', 'INK CART, HP CH565A (HP82) Black \r\n', '1872', '374', '167', '1', '2019-05-22 02:51:31', '2019-05-27 11:56:26'),
(157, '44103105-HP-C49', 'INK CART, HP CH566A (HP82) Cyan \r\n', '1300', '374', '167', '1', '2019-05-22 02:52:26', '2019-05-27 11:56:57'),
(158, '44103105-HP-M49', 'INK CART, HP CH567A (HP82) Magenta \r\n', '1300', '374', '167', '1', '2019-05-22 02:53:23', '2019-05-27 11:57:33'),
(159, '44103105-HP-Y49', 'INK CART, HP CH568A (HP82) Yellow \r\n', '1300', '374', '167', '1', '2019-05-22 02:54:10', '2019-05-27 11:58:00'),
(160, '44103105-HX-B43', 'INK CART, HP CN045AA, (HP950XL), Black\r\n', '1554.8', '374', '167', '1', '2019-05-22 02:55:02', '2019-05-27 11:58:34'),
(161, '44103105-HX-C43', 'INK CART, HP CN046AA, (HP951XL), Cyan\r\n', '1175.2', '374', '167', '1', '2019-05-22 02:56:01', '2019-05-27 11:59:21'),
(162, '44103105-HX-M43', 'INK CART, HP CN047AA, (HP951XL), Magenta\r\n', '1180.4', '374', '167', '1', '2019-05-22 02:57:33', '2019-05-27 12:00:19'),
(163, '44103105-HX-Y43', 'INK CART, HP CN048AA, (HP951XL). Yellow\r\n', '1180.4', '374', '167', '1', '2019-05-22 02:58:21', '2019-05-27 12:00:59'),
(164, '44103105-HP-B36', 'INK CART, HP CN692AA, (HP704), Black\r\n', '339.04', '374', '167', '1', '2019-05-22 02:59:33', '2019-05-27 12:01:32'),
(165, '44103105-HP-T20', 'INK CART, HP CH562WA, (HP61), Tricolor\r\n', '826.8', '374', '167', '1', '2019-05-22 02:59:33', '2019-05-27 12:03:07'),
(166, '44103105-HP-T36', 'INK CART, HP CN693AA, (HP704), Tri-color\r\n', '339.04', '374', '167', '1', '2019-05-22 03:10:50', '2019-05-27 12:24:50'),
(167, '44103105-HP-B33', 'INK CART, HP CZ107AA, (HP678), Black\r\n', '339.04', '374', '167', '1', '2019-05-22 03:11:52', '2019-05-27 12:25:45'),
(168, '44103105-HP-T33', 'INK CART, HP CZ108AA, (HP678), Tricolor\r\n', '339.04', '374', '167', '1', '2019-05-22 03:13:03', '2019-05-27 12:26:33'),
(169, '44103105-HP-B42', 'INK CART, HP CZ121A (HP685A), Black\r\n', '366.08', '374', '167', '1', '2019-05-22 03:14:09', '2019-05-27 12:27:11'),
(170, '44103105-HP-C33', 'INK CART, HP CZ122A (HP685A), Cyan\r\n', '249.6', '374', '167', '1', '2019-05-22 03:16:04', '2019-05-27 12:27:48'),
(171, '44103105-HP-M33', 'INK CART, HP CZ123A (HP685A), Magenta\r\n', '249.6', '374', '167', '1', '2019-05-22 03:16:46', '2019-05-27 12:28:36'),
(172, '44103105-HP-Y33', 'INK CART, HP CZ124A (HP685A), Yellow\r\n', '249.6', '374', '167', '1', '2019-05-22 03:17:58', '2019-05-27 12:29:13'),
(173, '44103105-HP-T43', 'INK CART, HP F6V26AA (HP680) Tri-color\r\n', '403.83', '374', '167', '1', '2019-05-22 03:18:46', '2019-05-27 12:29:47'),
(174, '44103105-HP-B43', 'INK CART, HP F6V27AA (HP680) Black \r\n', '403.83', '374', '167', '1', '2019-05-22 03:19:53', '2019-05-27 12:30:20'),
(175, '44103105-HP-C50', 'INK CART, HP L0S51AA (HP955) Cyan Original \r\n', '403.83', '374', '167', '1', '2019-05-22 03:20:43', '2019-05-27 12:31:00'),
(176, '44103105-HP-M50', 'INK CART, HP L0S54AA (HP955) Magenta Original\r\n', '403.83', '374', '167', '1', '2019-05-22 12:07:10', '2019-05-27 12:32:31'),
(177, '44103105-HP-Y50', 'INK CART, HP L0S57AA (HP955) Yellow Original \r\n', '403.83', '374', '167', '1', '2019-05-22 12:12:59', '2019-05-27 12:31:43'),
(178, '44103105-HP-B50', 'INK CART, HP L0S60AA (HP955) Black Original \r\n', '403.83', '374', '167', '1', '2019-05-22 12:14:14', '2019-05-27 12:33:08'),
(179, '44103105-HX-C48', 'INK CART, HP L0S63AA (HP955XL) Cyan Original\r\n', '1277.76', '374', '167', '1', '2019-05-22 12:15:40', '2019-05-27 12:37:15'),
(180, '44103105-HX-M48', 'INK CART, HP L0S66AA (HP955XL) Magenta Original \r\n', '1277.76', '374', '167', '1', '2019-05-22 12:18:27', '2019-05-27 12:37:54'),
(181, '44103105-HX-Y48', 'INK CART, HP L0S69AA (HP955XL) Yellow Original \r\n', '1277.76', '374', '167', '1', '2019-05-22 12:20:26', '2019-05-27 12:38:39'),
(182, '44103105-HX-B48', 'INK CART, HP L0S72AA (HP955XL) Black Original\r\n', '1737.02', '374', '167', '1', '2019-05-22 12:22:20', '2019-05-27 12:39:43'),
(183, '44103105-HP-C51', 'INK CART, HP T6L89AA (HP905) Cyan Original \r\n', '453.62', '374', '167', '1', '2019-05-22 12:24:48', '2019-05-27 12:40:31'),
(184, '44103105-HP-M51', 'INK CART, HP T6L93AA (HP905) Magenta Original\r\n', '453.62', '374', '167', '1', '2019-05-22 12:26:09', '2019-05-27 12:41:01'),
(185, '44103105-HP-Y51', 'INK CART, HP T6L97AA (HP905) Yellow Original\r\n', '453.62', '374', '167', '1', '2019-05-22 12:29:47', '2019-05-27 12:41:39'),
(186, '44103105-HX-B49', 'INK CART, HP T6M01AA (HP905) Black Original\r\n', '741.28', '374', '167', '1', '2019-05-22 12:31:35', '2019-05-27 12:42:08'),
(187, '44103112-EP-R05', 'RIBBON CART, EPSON C13S015516 (#8750), Black\r\n', '76.75', '374', '167', '1', '2019-05-22 12:33:20', '2019-05-27 12:43:14'),
(188, '44103112-EP-R07', 'RIBBON CART, EPSON C13S015531 (S015086), Black\r\n', '724.88', '374', '167', '1', '2019-05-22 12:34:59', '2019-05-27 12:44:02'),
(189, '44103112-EP-R13', 'RIBBON CART, EPSON C13S015632, Black, forLX-310\r\n', '75.92', '374', '167', '1', '2019-05-22 12:36:40', '2019-05-27 12:44:32'),
(190, '44103103-BR-B03', 'TONER CART,  BROTHER TN-2025, Black\r\n', '2556.32', '374', '167', '1', '2019-05-22 12:37:37', '2019-05-27 12:45:07'),
(191, '44103103-BR-B04', 'TONER CART,  BROTHER TN-2130, Black\r\n', '1820', '374', '167', '1', '2019-05-22 12:41:23', '2019-05-27 12:47:26'),
(192, '44103103-BR-B05', 'TONER CART,  BROTHER TN-2150, Black\r\n', '2615.6', '374', '167', '1', '2019-05-22 14:26:48', '2019-05-27 12:48:14'),
(193, '44103103-BR-B09', 'TONER CART,  BROTHER TN-3320, Black\r\n', '2941.95', '374', '167', '1', '2019-05-22 14:28:29', '2019-05-27 12:48:57'),
(194, '44103103-BR-B11', 'TONER CART,  BROTHER TN-3350, Black, for HL5450DN (CU Printer)\r\n', '4288.54', '374', '167', '1', '2019-05-22 14:29:57', '2019-05-27 12:49:34'),
(195, '44103103-HP-B12', 'TONER CART, HP CB435A, Black\r\n', '2857.92', '374', '167', '1', '2019-05-22 14:29:57', '2019-05-27 12:52:52'),
(196, '44103103-HP-B12', 'TONER CART, HP CB435A, Black\r\n', '2857.92', '374', '167', '1', '2019-05-22 14:31:14', '2019-05-27 15:15:08'),
(197, '44103103-HP-B14', 'TONER CART, HP CB540A, Black\r\n', '3312.4', '374', '167', '1', '2019-05-22 14:32:11', '2019-05-27 15:15:49'),
(198, '44103103-HP-B18', 'TONER CART, HP CE255A, Black\r\n', '6791.2', '374', '167', '1', '2019-05-22 14:34:10', '2019-05-27 15:16:45'),
(199, '44103103-HP-B21', 'TONER CART, HP CE278A, Black\r\n', '3179.28', '374', '167', '1', '2019-05-22 14:35:20', '2019-05-27 15:17:25'),
(200, '44103103-HP-B22', 'TONER CART, HP CE285A (HP85A), Black\r\n', '2953.6', '374', '167', '1', '2019-05-22 14:36:30', '2019-05-27 15:17:52'),
(201, '44103103-HP-B23', 'TONER CART, HP CE310A, Black\r\n', '2386.8', '374', '167', '1', '2019-05-22 14:37:26', '2019-05-27 15:18:25'),
(202, '44103103-HP-C23', 'TONER CART, HP CE311A, Cyan\r\n', '2490.8', '374', '167', '1', '2019-05-22 14:38:50', '2019-05-27 15:19:03'),
(203, '44103103-HP-Y23', 'TONER CART, HP CE312A, Yellow\r\n', '2490.8', '374', '167', '1', '2019-05-22 14:39:54', '2019-05-27 15:19:46'),
(204, '44103103-HP-M23', 'TONER CART, HP CE313A, Magenta\r\n', '2490.8', '374', '167', '1', '2019-05-22 14:41:01', '2019-05-27 15:20:32'),
(205, '44103103-HP-B24', 'TONER CART, HP CE320A, Black\r\n', '2854.2', '374', '167', '1', '2019-05-22 14:41:57', '2019-05-27 15:21:00'),
(206, '44103103-HP-C24', 'TONER CART, HP CE321A, Cyan\r\n', '3010.8', '374', '167', '1', '2019-05-22 14:43:11', '2019-05-27 15:21:38'),
(207, '44103103-HP-Y24', 'TONER CART, HP CE322A, Yellow\r\n', '3010.8', '374', '167', '1', '2019-05-22 14:45:44', '2019-05-27 15:22:03'),
(208, '44103103-HP-M24', 'TONER CART, HP CE323A, Magenta\r\n', '3010.8', '374', '167', '1', '2019-05-22 14:46:39', '2019-05-27 15:22:38'),
(209, '44103103-HP-B25', 'TONER CART, HP CE390A, Black\r\n', '7690.8', '374', '167', '1', '2019-05-22 15:30:26', '2019-05-27 15:23:29'),
(210, '44103103-HP-B26', 'TONER CART, HP CE400A, Black\r\n', '6754.8', '374', '167', '1', '2019-05-22 15:31:23', '2019-05-27 15:24:03'),
(211, '44103103-HP-C26', 'TONER CART, HP CE401A, Cyan\r\n', '9978.8', '374', '167', '1', '2019-05-22 15:32:40', '2019-05-27 15:24:38'),
(212, '44103103-HP-Y26', 'TONER CART, HP CE402A, Yellow\r\n', '9978.8', '374', '167', '1', '2019-05-22 15:33:51', '2019-05-27 15:25:19'),
(213, '44103103-HP-M26', 'TONER CART, HP CE403A, Magenta\r\n', '9978.8', '374', 'Select measure', '1', '2019-05-22 15:34:46', '2019-05-27 15:25:50'),
(214, '44103103-HP-B27', 'TONER CART, HP CE410A, (HP305), Black\r\n', '3868.8', '374', '167', '1', '2019-05-22 15:37:30', '2019-05-27 15:26:28'),
(215, '44103103-HP-C27', 'TONER CART, HP CE411A, (HP305), Cyan\r\n', '5512', '374', 'Select measure', '1', '2019-05-22 15:38:54', '2019-05-27 15:27:07'),
(216, '44103103-HP-Y27', 'TONER CART, HP CE412A, (HP305), Yellow\r\n', '5512', '374', '167', '1', '2019-05-22 15:39:44', '2019-05-27 15:27:33'),
(217, '44103103-HP-M27', 'TONER CART, HP CE413A, (HP305), Magenta\r\n', '5512', '374', '167', '1', '2019-05-22 15:40:46', '2019-05-27 15:28:06'),
(218, '44103103-HP-B28', 'TONER CART, HP CE505A, Black\r\n', '4079.92', '374', 'Select measure', '1', '2019-05-22 15:46:23', '2019-05-27 15:29:45'),
(219, '44103103-HX-B28', 'TONER CART, HP CE505X, Black, high cap\r\n', '7213.44', '374', '167', '1', '2019-05-22 15:48:12', '2019-05-27 15:30:29'),
(220, '44103103-HP-B52', 'TONER CART, HP CF217A (HP17A) Black LaserJet \r\n', '2932.8', '374', '167', '1', '2019-05-22 15:53:10', '2019-05-27 15:31:17'),
(221, '44103103-HP-B57', 'TONER CART, HP CF283A (HP83A) LaserJet  Black \r\n', '3241.7', '374', '167', '1', '2019-05-22 15:54:28', '2019-05-27 15:32:45'),
(222, '44103103-HX-B50', 'TONER CART, HP CF226XC (HP26XC) Black LaserJet \r\n', '2932.8', '374', '167', '1', '2019-05-22 15:56:55', '2019-05-27 15:34:50'),
(223, '44103103-HP-B55', 'TONER CART, HP CF280A, LaserJet Pro M401/M425 2.7K Black \r\n', '2932.8', '374', '167', '1', '2019-05-22 15:58:25', '2019-05-27 15:35:46'),
(224, '44103103-HP-B51', 'TONER CART, HP CF280XC \r\n', '6962.8', '374', '167', '1', '2019-05-22 15:59:34', '2019-05-27 15:37:01'),
(225, '44103103-HP-B56', 'TONER CART, HP CF281A (HP81A) Black LaserJet \r\n', '8640.85', '374', '167', '1', '2019-05-22 16:04:52', '2019-05-27 15:37:43'),
(226, '44103103-HP-B57', 'TONER CART, HP CF283A (HP83A) LaserJet  Black \r\n', '3241.7', '374', '167', '1', '2019-05-22 16:05:45', '2019-05-27 15:38:36'),
(227, '44103103-HX-B51', 'TONER CART, HP CF283XC (HP83X) Blk Contract LJ \r\n', '3946.8', '374', '167', '1', '2019-05-22 16:07:03', '2019-05-27 15:39:21'),
(228, '44103103-HP-B58', 'TONER CART, HP CF287A (HP87) black\r\n', '10051.6', '374', '167', '1', '2019-05-22 16:07:54', '2019-05-27 15:40:17'),
(229, '44103103-HP-B59', 'TONER CART, HP CF310AC (HP826) black\r\n', '10051.6', '374', '167', '1', '2019-05-22 16:10:20', '2019-05-27 15:42:05'),
(230, '44103103-HP-C59', 'TONER CART, HP CF311AC (HP826) cyan\r\n', '10051.6', '374', '167', '1', '2019-05-22 16:11:13', '2019-05-27 15:42:32'),
(231, '44103103-HP-Y59', 'TONER CART, HP CF312AC (HP826) yellow\r\n', '10051.6', '374', '167', '1', '2019-05-22 16:12:05', '2019-05-27 15:43:44'),
(232, '44103103-HP-M59', 'TONER CART, HP CF313AC (HP826) magenta \r\n', '10051.6', '374', '167', '1', '2019-05-22 16:14:08', '2019-05-27 15:44:34'),
(233, '44103103-HX-B52', 'TONER CART, HP CF325XC (HP25X) Black LaserJet \r\n', '13156', '374', '167', '1', '2019-05-22 16:15:24', '2019-05-27 15:45:16'),
(234, '44103103-HP-B60', 'TONER CART, HP CF350A Black LJ \r\n', '2901.6', '374', '167', '1', '2019-05-22 16:16:54', '2019-05-27 15:45:46'),
(235, '44103103-HP-C60', 'TONER CART, HP CF351A Cyan LJ \r\n', '2943.2', '374', '167', '1', '2019-05-22 18:02:02', '2019-05-27 15:46:16'),
(236, '44103103-HP-Y60', 'TONER CART, HP CF352A Yellow LJ \r\n', '2943.2', '374', '167', '1', '2019-05-22 18:03:02', '2019-05-27 16:07:30'),
(237, '44103103-HP-M60', 'TONER CART, HP CF353A Magenta LJ \r\n', '2943.2', '374', '167', '1', '2019-05-22 18:03:56', '2019-05-27 16:08:11'),
(238, '44103103-HP-B61', 'TONER CART, HP CF360A (HP508A) Black LaserJet \r\n', '7389.2', '374', '167', '1', '2019-05-22 18:04:42', '2019-05-27 16:08:46'),
(239, '44103103-HX-B53', 'TONER CART, HP CF360XC (HP508X) Black Contract LJ \r\n', '7389.2', '374', '167', '1', '2019-05-22 18:05:52', '2019-05-27 16:09:34'),
(240, '44103103-HP-C61', 'TONER CART, HP CF361A (HP508A) Cyan LaserJet \r\n', '9474.4', '374', '167', '1', '2019-05-22 18:07:51', '2019-05-27 16:10:58'),
(241, '44103103-HX-C53', 'TONER CART, HP CF361XC (HP508X) Cyan Contract LJ \r\n', '9474.2', '374', '167', '1', '2019-05-22 18:10:39', '2019-05-27 16:12:00'),
(242, '44103103-HP-Y61', 'TONER CART, HP CF362A (HP508A) Yellow LaserJet \r\n', '9474.2', '374', '167', '1', '2019-05-22 18:11:48', '2019-05-27 16:13:52'),
(243, '44103103-HX-Y53', 'TONER CART, HP CF362XC (HP508X) Yellow Contract LJ \r\n', '9474.4', '374', '167', '1', '2019-05-22 18:12:36', '2019-05-27 16:17:42'),
(244, '44103103-HX-Y53', 'TONER CART, HP CF362XC (HP508X) Yellow Contract LJ \r\n', '9474.4', '374', '167', '1', '2019-05-22 18:12:36', '2019-05-27 16:17:08'),
(245, '44103103-HP-M61', 'TONER CART, HP CF363A (HP508A) Magenta LaserJet \r\n', '9474.4', '374', '167', '1', '2019-05-22 18:14:41', '2019-05-27 16:18:40'),
(246, '44103103-HX-M53', 'TONER CART, HP CF363XC (HP508X) Magenta Contract LJ\r\n', '9474.4', '374', '167', '1', '2019-05-22 18:16:14', '2019-05-27 16:19:22'),
(247, '44103103-HP-B62', 'TONER CART, HP CF400A (HP201A) Black LaserJet \r\n', '9474.4', '374', '167', '1', '2019-05-22 18:17:35', '2019-05-27 16:20:11'),
(248, '44103103-HP-C62', 'TONER CART, HP CF401A (HP201A) Cyan LaserJet \r\n', '9474.4', '374', '167', '1', '2019-05-22 18:20:28', '2019-05-27 16:20:55'),
(249, '44103103-HP-Y62', 'TONER CART, HP CF402A (HP201A) Yellow LaserJet \r\n', '9474.4', '374', '167', '1', '2019-05-22 18:21:27', '2019-05-27 16:21:30'),
(250, '44103103-HP-M62', 'TONER CART, HP CF403A (HP201A) Magenta LaserJet \r\n', '9474.4', '374', '167', '1', '2019-05-22 18:22:44', '2019-05-27 16:23:22'),
(251, '44103103-HP-B63', 'TONER CART, HP CF410A (HP410A) black\r\n', '4440.8', '374', '167', '1', '2019-05-22 18:24:30', '2019-05-27 16:24:52'),
(252, '44103103-HX-B54', 'TONER CART, HP CF410XC (HP410XC) black \r\n', '7441.2', '374', '167', '1', '2019-05-22 18:25:49', '2019-05-27 16:25:38'),
(253, '44103103-HP-C63', 'TONER CART, HP CF411A (HP410A) cyan\r\n', '5049.2', '374', '167', '1', '2019-05-22 18:26:56', '2019-05-27 16:27:09'),
(254, '44103103-HX-C54', 'TONER CART, HP CF411XC (HP410XC) cyan \r\n', '7472.4', '374', '167', '1', '2019-05-22 18:30:41', '2019-05-27 18:24:44'),
(255, '44103103-HP-Y63', 'TONER CART, HP CF412A (HP410A) yellow\r\n', '5049.2', '374', '167', '1', '2019-05-22 18:31:39', '2019-05-27 18:25:23'),
(256, '44103103-HX-Y54', 'TONER CART, HP CF412XC (HP410XC) yellow \r\n', '7472.4', '374', '167', '1', '2019-05-22 18:32:42', '2019-05-27 18:26:04'),
(257, '44103103-HP-M63', 'TONER CART, HP CF413A (HP410A) magenta\r\n', '5049.2', '374', '167', '1', '2019-05-22 18:33:57', '2019-05-27 18:27:57'),
(258, '44103103-HX-M54', 'TONER CART, HP CF413XC (HP410XC) magenta \r\n', '7472.2', '374', '167', '1', '2019-05-22 18:34:55', '2019-05-27 18:29:25'),
(259, '44103103-HX-M54', 'TONER CART, HP CF413XC (HP410XC) magenta \r\n', '7472.4', '374', '167', '1', '2019-05-22 18:36:54', '2019-05-27 18:30:26'),
(260, '44103103-HP-B34', 'TONER CART, HP Q2612A, Black\r\n', '3164.72', '374', '167', '1', '2019-05-22 18:38:18', '2019-05-27 18:31:06'),
(261, '44103103-HP-B39', 'TONER CART, HP Q5942A, Black\r\n', '7482.8', '374', '167', '1', '2019-05-22 18:40:44', '2019-05-27 18:34:09'),
(262, '44103103-HP-B48', 'TONER CART, HP Q7553A, Black\r\n', '3972.8', '374', '167', '1', '2019-05-22 18:42:00', '2019-05-27 18:35:24'),
(263, '44103103-LX-B03', 'TONER CART, LEXMARK E360H11P, Black\r\n', '8874.32', '374', '167', '1', '2019-05-22 18:42:47', '2019-05-27 18:37:06'),
(264, '44103103-LX-B05', 'TONER CART, LEXMARK T650A11P, Black\r\n', '9630.4', '374', '167', '1', '2019-05-22 18:43:46', '2019-05-27 18:37:44'),
(265, '44103103-LX-B05', 'TONER CART, SAMSUNG MLT-D101S, Black\r\n', '9630.4', '374', '167', '1', '2019-05-22 18:47:16', '2019-05-27 18:38:35'),
(266, '44103103-SA-B06', 'TONER CART, SAMSUNG MLT-D103S, Black\r\n', '2641.6', '374', '167', '1', '2019-05-22 18:48:26', '2019-05-27 18:39:16'),
(267, '44103103-SA-B07', 'TONER CART, SAMSUNG MLT-D103S, Black\r\n', '2912', '374', '167', '1', '2019-05-22 18:51:06', '2019-05-27 18:40:08'),
(268, '44103103-SA-B08', 'TONER CART, SAMSUNG MLT-D104S, Black\r\n', '2444', '374', '167', '1', '2019-05-22 18:52:01', '2019-05-27 18:40:45'),
(269, '44103103-SA-B09', 'TONER CART, SAMSUNG MLT-D105L, Black\r\n', '2787.2', '374', '167', '1', '2019-05-22 18:54:08', '2019-05-27 18:41:22'),
(270, '44103103-SA-B14', 'TONER CART, SAMSUNG MLT-D108S, Black\r\n', '2745.6', '374', '167', '1', '2019-05-22 18:54:52', '2019-05-27 18:42:06'),
(271, '44103103-SA-B21', 'TONER CART, SAMSUNG MLT-D203E, Black \r\n', '7124', '374', '167', '1', '2019-05-22 18:56:02', '2019-05-27 18:42:49'),
(272, '44103103-SA-B18', 'TONER CART, SAMSUNG MLT-D203L, Black\r\n', '4617.6', '374', '167', '1', '2019-05-22 18:56:58', '2019-05-27 18:43:24'),
(273, '44103103-SA-B20', 'TONER CART, SAMSUNG MLT-D203U, black\r\n', '9464', '374', '167', '1', '2019-05-22 18:58:08', '2019-05-27 18:44:08'),
(274, '44103103-SA-B12', 'TONER CART, SAMSUNG MLT-D205E, Black\r\n', '9204', '374', '167', '1', '2019-05-22 19:10:07', '2019-05-27 18:44:48'),
(275, '44103103-SA-B05', 'TONER CART, SAMSUNG MLT-D205L, Black\r\n', '5064.8', '374', '167', '1', '2019-05-22 19:11:11', '2019-05-27 18:45:19'),
(276, '44103103-SA-B10', 'TONER CART, SAMSUNG SCX-D6555A, Black\r\n', '4357.6', '374', '167', '1', '2019-05-22 19:12:38', '2019-05-27 18:46:09'),
(277, '44103103-BR-B15', 'TONER CARTRIDGE, BROTHER TN-3478, Blackf, for printer HL-6400DW (12,000 pages)\r\n', '6069.44', '374', '167', '1', '2019-05-22 19:14:00', '2019-05-27 18:46:45'),
(278, '44103103-CA-B00', 'TONER CARTRIDGE, CANON 324 II, for  printer LBP6780x\r\n', '13399.36', '374', '167', '1', '2019-05-22 19:14:54', '2019-05-27 19:07:44'),
(279, '45121517-DO-C01', 'DOCUMENT CAMERA, 3.2M pixels\r\n', '28860', 'Audio and Visual Equipment and Supplies', '167', '1', '2019-05-22 19:16:56', '2019-05-27 19:10:43'),
(280, '45111609-MM-P01', 'MULTIMEDIA PROJECTOR, 4000 min ANSI Lumens\r\n', '18616', 'Audio and Visual Equipment and Supplies', '59', '1', '2019-05-22 19:17:56', '2019-05-27 19:11:37'),
(281, '55121905-PH-F01', 'PHILIPPINE NATIONAL FLAG, 100% polyester\r\n', '319.28', 'Flag or Accessories', '29', '1', '2019-05-22 19:19:21', '2019-05-27 19:12:50'),
(282, '55101524-RA-H01', 'HANDBOOK (RA 9184), 7th Edition\r\n', '46.28', 'Printed Publications', '40', '1', '2019-05-22 19:22:23', '2019-05-27 19:14:34'),
(283, '46191601-FE-M01', 'FIRE EXTINGUISHER, DRY CHEMICAL, 4.5kgs\r\n', '1144', 'Fire Fighting Equipment', '59', '1', '2019-05-22 19:24:43', '2019-05-27 19:16:31'),
(284, '46191601-FE-H01', ' Fire Fighting Equipment\r\n', '4992', 'FIRE EXTINGUISHER, PURE HCFC 123, 4.5kgs', '59', '1', '2019-05-22 19:26:10', '0000-00-00 00:00:00'),
(285, '52161535-DV-R01', 'DIGITAL VOICE RECORDER, memory: 4GB (expandable)\r\n', '6828.14', 'Consumer Electronics', '59', '1', '2019-05-22 19:27:21', '2019-05-27 19:17:40'),
(286, '56101504-CM-B01', 'CHAIR, monobloc, beige, with backrest, w/o armrest\r\n', '262.6', 'Furniture and Furnishings', '29', '1', '2019-05-22 19:28:34', '2019-05-27 19:19:02'),
(287, '56101504-CM-W01', 'CHAIR,monobloc, white, with backrest, w/o armrest\r\n', '262.6', 'Furniture and Furnishings', '29', '1', '2019-05-22 19:29:39', '2019-05-27 19:20:09'),
(288, '56101519-TM-S01', 'TABLE, MONOBLOC, WHITE, 889 x 889mm (35\" x 35\")min\r\n', '1326', 'Furniture and Furnishings', '59', '1', '2019-05-22 19:30:33', '2019-05-27 19:20:46'),
(289, '56101519-TM-S02', 'TABLE, MONOBLOC, BEIGE, 889 x 889mm (35\" x 35\")min\r\n', '1326', 'Furniture and Furnishings', '59', '1', '2019-05-22 19:31:25', '2019-05-27 19:22:07'),
(290, '60121413-CB-P01', 'CLEARBOOK, 20 transparent pockets, for A4 size\r\n', '39.78', 'Arts and Crafts Equipment and Accessories and Supplies', '29', '1', '2019-05-22 19:32:45', '2019-05-27 19:23:56'),
(291, '60121413-CB-P02', 'CLEARBOOK, 20 transparent pockets, for LEGAL size\r\n', '42.38', 'Arts and Crafts Equipment and Accessories and Supplies', '29', '1', '2019-05-22 19:34:37', '2019-05-27 19:24:52'),
(292, '60121534-ER-P01', 'ERASER, PLASTIC/RUBBER, for pencil draft/writing\r\nSIGN PEN, BLACK, liquid/gel ink, 0.5mm needle tip\r\n', '4.42', 'Arts and Crafts Equipment and Accessories and Supplies', '29', '1', '2019-05-22 19:35:22', '2019-05-27 19:25:39'),
(293, '60121524-SP-G01', 'SIGN PEN, BLACK, liquid/gel ink, 0.5mm needle tip\r\n', '34.61', 'Arts and Crafts Equipment and Accessories and Supplies', '29', '1', '2019-05-22 19:36:20', '2019-05-27 19:26:14'),
(294, '60121524-SP-G02', 'SIGN PEN, BLUE, liquid/gel ink, 0.5mm needle tip\r\n', '34.61', 'Arts and Crafts Equipment and Accessories and Supplies', '29', '1', '2019-05-22 19:37:28', '2019-05-27 19:26:49'),
(295, '60121524-SP-G03', 'SIGN PEN, RED, liquid/gel ink, 0.5mm needle tip\r\n', '34.61', 'Arts and Crafts Equipment and Accessories and Supplies', '29', '1', '2019-05-22 19:38:48', '2019-05-27 19:27:14'),
(296, '60121124-WR-P01', 'WRAPPING PAPER, kraft, 65gsm (-5%)\r\n', '129.67', 'Arts and Crafts Equipment and Accessories and Supplies', '15', '1', '2019-05-22 19:39:54', '2019-05-27 19:27:44'),
(297, '14111531-PP-R01', 'PAPER, PAD, ruled, size: 216mm x 330mm (± 2mm)\r\n', '17.35', '370', '25', '1', '2019-05-24 18:44:13', '2019-05-27 19:29:57'),
(298, '0001', ' Mouse', '500', '371', '29', '2', '2025-05-29 04:11:14', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` int(11) NOT NULL,
  `transaction` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ppmp`
--

CREATE TABLE `ppmp` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `cppmt_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `item_spec` longtext NOT NULL,
  `measure` varchar(255) NOT NULL,
  `jan` float NOT NULL DEFAULT 0,
  `feb` float NOT NULL DEFAULT 0,
  `mar` float NOT NULL DEFAULT 0,
  `q1` varchar(255) NOT NULL,
  `q1_amount` varchar(255) NOT NULL,
  `apr` float NOT NULL DEFAULT 0,
  `may` float NOT NULL DEFAULT 0,
  `jun` float NOT NULL DEFAULT 0,
  `q2` varchar(255) NOT NULL,
  `q2_amount` varchar(255) NOT NULL,
  `jul` float NOT NULL DEFAULT 0,
  `aug` float NOT NULL DEFAULT 0,
  `sep` float NOT NULL DEFAULT 0,
  `q3` varchar(255) NOT NULL,
  `q3_amount` varchar(255) NOT NULL,
  `oct` float NOT NULL DEFAULT 0,
  `nov` float NOT NULL DEFAULT 0,
  `decem` float NOT NULL DEFAULT 0,
  `q4` varchar(255) NOT NULL,
  `q4_amount` varchar(255) NOT NULL,
  `total_qty` varchar(255) NOT NULL,
  `cataloque` varchar(255) NOT NULL,
  `total_amt` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `tier` int(11) NOT NULL DEFAULT 1,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `type`
--

CREATE TABLE `type` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `type`
--

INSERT INTO `type` (`id`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Category', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Measure', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Part Number', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `fname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `ceiling_amount` text DEFAULT NULL,
  `position` varchar(255) NOT NULL,
  `office` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `isAdmin` enum('0','1','2') NOT NULL DEFAULT '0',
  `status` int(11) NOT NULL DEFAULT 1,
  `remember_token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `mname`, `ceiling_amount`, `position`, `office`, `username`, `password`, `isAdmin`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Cecile', 'Albay', 'Vingno', NULL, '', '', 'Cecile', '$2a$12$Sz6wXd7Mb80feBVu6q1mTu.0wBc.DJ4E2rQwPq9VH8jEzFMJU1FFy', '0', 1, 'AtsugogEjFAMsSAgcEf64hvmvnRzSHOx48mGA157M8NbYX8q3FHcnHtDkkY7', '2025-06-05 23:56:04', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ceiling_amounts`
--
ALTER TABLE `ceiling_amounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `create_ppmps`
--
ALTER TABLE `create_ppmps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ppmp`
--
ALTER TABLE `ppmp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `type`
--
ALTER TABLE `type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=378;

--
-- AUTO_INCREMENT for table `ceiling_amounts`
--
ALTER TABLE `ceiling_amounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `create_ppmps`
--
ALTER TABLE `create_ppmps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=302;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ppmp`
--
ALTER TABLE `ppmp`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `type`
--
ALTER TABLE `type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
