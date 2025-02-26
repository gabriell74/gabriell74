-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 10, 2024 at 08:39 AM
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
-- Database: `kost_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_WA` varchar(15) DEFAULT NULL,
  `nama_pengguna` varchar(255) NOT NULL,
  `role` enum('Admin','Staff Gudang') NOT NULL,
  `otp` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`email`, `password`, `no_WA`, `nama_pengguna`, `role`, `otp`) VALUES
('a1@gmail.com', '123', '13', 'Yudhis', 'Admin', NULL),
('a2@gmail.com', '123', '12313', 'Yudhis', 'Admin', NULL),
('a3@gmail.com', '123456', '131', 'Gabriel', 'Admin', NULL),
('a@gmail.com', '123', '123', 'Yudhis', 'Admin', NULL),
('gabriel@gmail.com', '123', '085742140994', 'Gabriel', 'Admin', NULL),
('gabrielvivaldi2006@gmail.com', '123', '123', 'Gabriel', 'Admin', NULL),
('gabrielvivaldi200@gmail.com', '123456', '1232', 'Yudhis', 'Admin', NULL),
('gabrielvivaldi7@gmail.com', '123', '', 'Gabriel', 'Staff Gudang', NULL),
('guest2@gmail.com', '123', '123', 'Gabriel', 'Admin', NULL),
('guest@gmail.com', '123', '', 'Gabriel', 'Staff Gudang', NULL),
('yudhis@gmail.com', '234', '', 'Yudhis', 'Staff Gudang', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_bahan_baku`
--

CREATE TABLE `t_bahan_baku` (
  `id_bahan_baku` varchar(6) NOT NULL,
  `kode_barcode` varchar(50) NOT NULL,
  `nama_bahan_baku` varchar(255) NOT NULL,
  `unit` varchar(10) NOT NULL,
  `id_kategori` varchar(4) DEFAULT NULL,
  `kuantitas` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_bahan_baku`
--

INSERT INTO `t_bahan_baku` (`id_bahan_baku`, `kode_barcode`, `nama_bahan_baku`, `unit`, `id_kategori`, `kuantitas`) VALUES
('S001', 'BB-S00100', 'Spritee', 'PCS', NULL, 623),
('S002', 'BB-S00200', 'Roti', 'PCS', 'K001', 950);

-- --------------------------------------------------------

--
-- Table structure for table `t_bahan_masuk_keluar`
--

CREATE TABLE `t_bahan_masuk_keluar` (
  `id_transaksi` int(6) NOT NULL,
  `id_bahan_baku` varchar(6) NOT NULL,
  `nama_bahan_baku` varchar(255) NOT NULL,
  `id_stok_masuk` varchar(6) DEFAULT NULL,
  `id_stok_keluar` varchar(6) DEFAULT NULL,
  `kode_barcode` varchar(25) NOT NULL,
  `kuantitas` int(5) NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_kategori` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_bahan_masuk_keluar`
--

INSERT INTO `t_bahan_masuk_keluar` (`id_transaksi`, `id_bahan_baku`, `nama_bahan_baku`, `id_stok_masuk`, `id_stok_keluar`, `kode_barcode`, `kuantitas`, `tanggal`, `id_kategori`) VALUES
(56, 'S001', 'Spritee', NULL, 'K0001', 'BB-S00100', 50, '2024-12-09 14:08:12', 'K002'),
(58, 'S002', 'Roti', 'M0003', NULL, 'BB-S00200', 500, '2024-12-09 14:57:01', 'K001'),
(59, 'S002', 'Roti', 'M0004', NULL, 'BB-S00200', 700, '2024-12-18 20:07:11', 'K001'),
(61, 'S001', 'Spritee', NULL, 'K0002', 'BB-S00100', 100, '2024-12-25 20:10:16', 'K002'),
(63, 'S001', 'Spritee', 'M0006', NULL, 'BB-S00100', 123, '2024-12-10 11:18:17', 'K002');

-- --------------------------------------------------------

--
-- Table structure for table `t_kategori`
--

CREATE TABLE `t_kategori` (
  `id_kategori` varchar(4) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_kategori`
--

INSERT INTO `t_kategori` (`id_kategori`, `nama_kategori`) VALUES
('K001', 'Makanan1');

-- --------------------------------------------------------

--
-- Table structure for table `t_notifikasi`
--

CREATE TABLE `t_notifikasi` (
  `id_notifikasi` int(13) NOT NULL,
  `no_WA` varchar(15) NOT NULL,
  `kuantitas` int(4) NOT NULL,
  `tanggal` date NOT NULL,
  `pesan_WA` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `t_bahan_baku`
--
ALTER TABLE `t_bahan_baku`
  ADD PRIMARY KEY (`id_bahan_baku`),
  ADD KEY `fk_id_kategori` (`id_kategori`);

--
-- Indexes for table `t_bahan_masuk_keluar`
--
ALTER TABLE `t_bahan_masuk_keluar`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indexes for table `t_kategori`
--
ALTER TABLE `t_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `t_notifikasi`
--
ALTER TABLE `t_notifikasi`
  ADD PRIMARY KEY (`id_notifikasi`),
  ADD UNIQUE KEY `no_WA` (`no_WA`),
  ADD UNIQUE KEY `kuantitas` (`kuantitas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_bahan_masuk_keluar`
--
ALTER TABLE `t_bahan_masuk_keluar`
  MODIFY `id_transaksi` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `t_notifikasi`
--
ALTER TABLE `t_notifikasi`
  MODIFY `id_notifikasi` int(13) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_bahan_baku`
--
ALTER TABLE `t_bahan_baku`
  ADD CONSTRAINT `fk_id_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `t_kategori` (`id_kategori`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `t_notifikasi`
--
ALTER TABLE `t_notifikasi`
  ADD CONSTRAINT `t_notifikasi_ibfk_1` FOREIGN KEY (`no_WA`) REFERENCES `pengguna` (`no_WA`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
