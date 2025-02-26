-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2024 at 04:54 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
  `role` enum('admin','staff gudang') NOT NULL,
  `otp` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`email`, `password`, `no_WA`, `nama_pengguna`, `role`, `otp`) VALUES
('juan@gmail.com', 'Juan123', NULL, 'Juan', 'staff gudang', NULL),
('megah@gmail.com', 'Megah123', '08123456789', 'Megah', 'admin', NULL),
('megahnandasp@gmail.com', 'Megah1234', '0812345679', 'Nanda', 'admin', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_bahan_baku`
--

CREATE TABLE `t_bahan_baku` (
  `kode_barcode` varchar(12) NOT NULL,
  `nama_bahan_baku` varchar(255) NOT NULL,
  `kuantitas` int(4) NOT NULL,
  `unit` enum('PCS') NOT NULL,
  `id_kategori` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_bahan_masuk/keluar`
--

CREATE TABLE `t_bahan_masuk/keluar` (
  `id_bahan_baku` int(6) NOT NULL,
  `kode_barcode` varchar(12) NOT NULL,
  `kuantitas` int(4) NOT NULL,
  `tanggal` date NOT NULL,
  `id_kategori` varchar(4) NOT NULL,
  `aksi` enum('Bahan Baku Masuk','Bahan Baku Keluar') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_kategori`
--

CREATE TABLE `t_kategori` (
  `id_kategori` varchar(4) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `nama_bahan_baku` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_kategori`
--

INSERT INTO `t_kategori` (`id_kategori`, `nama_kategori`, `nama_bahan_baku`) VALUES
('K002', 'Minuman', '');

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
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `no_WA` (`no_WA`);

--
-- Indexes for table `t_bahan_baku`
--
ALTER TABLE `t_bahan_baku`
  ADD PRIMARY KEY (`kode_barcode`),
  ADD UNIQUE KEY `id_kategori` (`id_kategori`);

--
-- Indexes for table `t_bahan_masuk/keluar`
--
ALTER TABLE `t_bahan_masuk/keluar`
  ADD PRIMARY KEY (`id_bahan_baku`),
  ADD UNIQUE KEY `kode_barcode` (`kode_barcode`),
  ADD UNIQUE KEY `kuantitas` (`kuantitas`),
  ADD UNIQUE KEY `id_kategori` (`id_kategori`);

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
  ADD CONSTRAINT `t_bahan_baku_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `t_kategori` (`id_kategori`);

--
-- Constraints for table `t_bahan_masuk/keluar`
--
ALTER TABLE `t_bahan_masuk/keluar`
  ADD CONSTRAINT `t_bahan_masuk/keluar_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `t_kategori` (`id_kategori`),
  ADD CONSTRAINT `t_bahan_masuk/keluar_ibfk_2` FOREIGN KEY (`kuantitas`) REFERENCES `t_notifikasi` (`kuantitas`),
  ADD CONSTRAINT `t_bahan_masuk/keluar_ibfk_3` FOREIGN KEY (`kode_barcode`) REFERENCES `t_bahan_baku` (`kode_barcode`);

--
-- Constraints for table `t_notifikasi`
--
ALTER TABLE `t_notifikasi`
  ADD CONSTRAINT `t_notifikasi_ibfk_1` FOREIGN KEY (`no_WA`) REFERENCES `pengguna` (`no_WA`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
