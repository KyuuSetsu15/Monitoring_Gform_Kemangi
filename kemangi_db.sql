-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2026 at 02:50 AM
-- Server version: 11.7.2-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kemangi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `peserta_backup`
--

CREATE TABLE `peserta_backup` (
  `id` int(11) NOT NULL,
  `raw_json` longtext NOT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `peserta_backup`
--

INSERT INTO `peserta_backup` (`id`, `raw_json`, `updated_at`) VALUES
(1, '{\"Timestamp\":\"19\\/10\\/2026 20:14:28\",\"Email Address\":\"maulana.rya08@gmail.com\",\"Score\":\"20 \\/ 30\",\"NAMA LENGKAP PESERTA\":\"Arya\",\"KECAMATAN\":\"Bubutan\",\"KELURAHAN\":\"Tembok dukuh\",\"RW\":\"8\",\"TANGGAL KEGIATAN\":\"07\\/10\\/2026\",\"PERTEMUAN KE-\":\"2\",\"NAMA NARASUMBER\":\"Jokowi\",\"Column 10\":\"Opsi 1\",\"NAMA KELAS KEMANGI\":\"Jayalah \",\"Pilihan Tahapan\":\"Tahap 2\",\"PILIHAN SOAL\":\"POST TEST\",\"Usia ideal menikah untuk perempuan menurut PUP adalah …\":\"20 tahun\",\"Usia ideal menikah untuk laki-laki menurut PUP adalah …\":\"26 tahun\",\"Salah satu tujuan dari PUP adalah …\":\"Meningkatkan kesadaran remaja dalam merencanakan keluarga\"}', '2026-11-17 01:50:38'),
(2, '{\"Timestamp\":\"22\\/10\\/2026 8:58:11\",\"Email Address\":\"ademarsa10@gmail.com\",\"Score\":\"0 \\/ 30\",\"NAMA LENGKAP PESERTA\":\"Joko\",\"KECAMATAN\":\"ndi ae\",\"KELURAHAN\":\"pokok e wes\",\"RW\":\"99\",\"TANGGAL KEGIATAN\":\"31\\/12\\/2026\",\"PERTEMUAN KE-\":\"5\",\"NAMA NARASUMBER\":\"saya sendiri\",\"Column 10\":\"\",\"NAMA KELAS KEMANGI\":\"mars\",\"Pilihan Tahapan\":\"Tahap 1\",\"PILIHAN SOAL\":\"PRE TEST\",\"Usia ideal menikah untuk perempuan menurut PUP adalah …\":\"19 tahun\",\"Usia ideal menikah untuk laki-laki menurut PUP adalah …\":\"28 tahun\",\"Salah satu tujuan dari PUP adalah …\":\"Meningkatkan jumlah penduduk\"}', '2026-11-17 01:50:38'),
(3, '{\"Timestamp\":\"22\\/10\\/2026 9:01:39\",\"Email Address\":\"ademarsa10@gmail.com\",\"Score\":\"0 \\/ 30\",\"NAMA LENGKAP PESERTA\":\"sunarto\",\"KECAMATAN\":\"tegalsari\",\"KELURAHAN\":\"keputran\",\"RW\":\"1\",\"TANGGAL KEGIATAN\":\"21\\/10\\/2026\",\"PERTEMUAN KE-\":\"1\",\"NAMA NARASUMBER\":\"saya sendiri\",\"Column 10\":\"\",\"NAMA KELAS KEMANGI\":\"pluto\",\"Pilihan Tahapan\":\"Tahap 1\",\"PILIHAN SOAL\":\"PRE TEST\",\"Usia ideal menikah untuk perempuan menurut PUP adalah …\":\"18 tahun\",\"Usia ideal menikah untuk laki-laki menurut PUP adalah …\":\"23 tahun\",\"Salah satu tujuan dari PUP adalah …\":\"Meningkatkan jumlah penduduk\"}', '2026-11-17 01:50:38'),
(4, '{\"Timestamp\":\"22\\/10\\/2026 8:59:57\",\"NAMA LENGKAP PESERTA\":\"sunaryo\",\"KECAMATAN\":\"ada deh\",\"KELURAHAN\":\"kepo\",\"RW\":\"69\",\"TANGGAL KEGIATAN\":\"19\\/02\\/2026\",\"PERTEMUAN KE-\":\"4\",\"NAMA NARASUMBER\":\"saya sendiri\",\"NAMA KELAS KEMANGI\":\"pluto\",\"Pilihan Tahapan\":\"Tahap 2\",\"PILIHAN SOAL\":\"Post Test\",\"Usia ideal untuk memulai pernikahan bagi laki-laki adalah...\":\"a. 18 tahun\",\"Score\":\"0 \\/ 30\",\"Email Address\":\"ademarsa10@gmail.com\",\"Apa dampak positif menikah di usia matang?\":\"c. Emosi lebih dewasa dan kesiapan fisik memadai\",\"Kesiapan finansial dalam menikah dinilai dari...\":\"a. Jumlah teman banyak\"}', '2026-11-17 01:50:38'),
(5, '{\"Timestamp\":\"22\\/10\\/2026 9:01:01\",\"NAMA LENGKAP PESERTA\":\"joko\",\"KECAMATAN\":\"tegalsari\",\"KELURAHAN\":\"keputran\",\"RW\":\"1\",\"TANGGAL KEGIATAN\":\"20\\/10\\/2026\",\"PERTEMUAN KE-\":\"3\",\"NAMA NARASUMBER\":\"saya sendiri\",\"NAMA KELAS KEMANGI\":\"mars\",\"Pilihan Tahapan\":\"Tahap 1\",\"PILIHAN SOAL\":\"Pre Test\",\"Usia ideal untuk memulai pernikahan bagi laki-laki adalah...\":\"a. 18 tahun\",\"Score\":\"0 \\/ 30\",\"Email Address\":\"ademarsa10@gmail.com\",\"Apa dampak positif menikah di usia matang?\":\"a. Emosi belum stabil\",\"Kesiapan finansial dalam menikah dinilai dari...\":\"a. Jumlah teman banyak\"}', '2026-11-17 01:50:38'),
(6, '{\"Timestamp\":\"22\\/10\\/2026 9:02:31\",\"NAMA LENGKAP PESERTA\":\"wowo\",\"KECAMATAN\":\"tegalsari\",\"KELURAHAN\":\"keputran\",\"RW\":\"1\",\"TANGGAL KEGIATAN\":\"20\\/10\\/2026\",\"PERTEMUAN KE-\":\"1\",\"NAMA NARASUMBER\":\"kamu\",\"NAMA KELAS KEMANGI\":\"mars\",\"Pilihan Tahapan\":\"Tahap 1\",\"PILIHAN SOAL\":\"Pre Test\",\"Usia ideal untuk memulai pernikahan bagi laki-laki adalah...\":\"a. 18 tahun\",\"Score\":\"0 \\/ 30\",\"Email Address\":\"ademarsa10@gmail.com\",\"Apa dampak positif menikah di usia matang?\":\"a. Emosi belum stabil\",\"Kesiapan finansial dalam menikah dinilai dari...\":\"a. Jumlah teman banyak\"}', '2026-11-17 01:50:38'),
(7, '{\"Timestamp\":\"22\\/10\\/2026 9:03:20\",\"NAMA LENGKAP PESERTA\":\"bapak\",\"KECAMATAN\":\"tegalsari\",\"KELURAHAN\":\"keputran\",\"RW\":\"1\",\"TANGGAL KEGIATAN\":\"20\\/10\\/2026\",\"PERTEMUAN KE-\":\"1\",\"NAMA NARASUMBER\":\"kamu\",\"NAMA KELAS KEMANGI\":\"mars\",\"Pilihan Tahapan\":\"Tahap 1\",\"PILIHAN SOAL\":\"Pre Test\",\"Usia ideal untuk memulai pernikahan bagi laki-laki adalah...\":\"a. 18 tahun\",\"Score\":\"0 \\/ 30\",\"Email Address\":\"ademarsa10@gmail.com\",\"Apa dampak positif menikah di usia matang?\":\"a. Emosi belum stabil\",\"Kesiapan finansial dalam menikah dinilai dari...\":\"a. Jumlah teman banyak\"}', '2026-11-17 01:50:38'),
(8, '{\"Timestamp\":\"22\\/10\\/2026 9:03:56\",\"NAMA LENGKAP PESERTA\":\"sunandar\",\"KECAMATAN\":\"tegalsari\",\"KELURAHAN\":\"keputran\",\"RW\":\"1\",\"TANGGAL KEGIATAN\":\"20\\/10\\/2026\",\"PERTEMUAN KE-\":\"1\",\"NAMA NARASUMBER\":\"dia\",\"NAMA KELAS KEMANGI\":\"mars\",\"Pilihan Tahapan\":\"Tahap 1\",\"PILIHAN SOAL\":\"Pre Test\",\"Usia ideal untuk memulai pernikahan bagi laki-laki adalah...\":\"a. 18 tahun\",\"Score\":\"0 \\/ 30\",\"Email Address\":\"ademarsa10@gmail.com\",\"Apa dampak positif menikah di usia matang?\":\"a. Emosi belum stabil\",\"Kesiapan finansial dalam menikah dinilai dari...\":\"a. Jumlah teman banyak\"}', '2026-11-17 01:50:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_admin` varchar(100) NOT NULL,
  `kecamatan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_admin`, `kecamatan`) VALUES
(1, 'admin', '5af19b561a890b9b69988c143ee32567a6a369b7fc581c7e6c4ab0e7b46628e0', 'Super Admin Surabaya', ''),
(2, 'bbtan$13', '5af19b561a890b9b69988c143ee32567a6a369b7fc581c7e6c4ab0e7b46628e0', 'Admin Kec. Bubutan', 'Bubutan'),
(3, 'gbeng$14', '5af19b561a890b9b69988c143ee32567a6a369b7fc581c7e6c4ab0e7b46628e0', 'Admin Kec. Gubeng', 'Gubeng'),
(4, 'rngkt$15', '5af19b561a890b9b69988c143ee32567a6a369b7fc581c7e6c4ab0e7b46628e0', 'Admin Kec. Rungkut', 'Rungkut'),
(5, 'skllo$16', '5af19b561a890b9b69988c143ee32567a6a369b7fc581c7e6c4ab0e7b46628e0', 'Admin Kec. Sukolilo', 'Sukolilo'),
(6, 'wnkrm$17', '5af19b561a890b9b69988c143ee32567a6a369b7fc581c7e6c4ab0e7b46628e0', 'Admin Kec. Wonokromo', 'Wonokromo'),
(7, 'tmbks$18', '5af19b561a890b9b69988c143ee32567a6a369b7fc581c7e6c4ab0e7b46628e0', 'Admin Kec. Tambaksari', 'Tambaksari');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `peserta_backup`
--
ALTER TABLE `peserta_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `peserta_backup`
--
ALTER TABLE `peserta_backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
