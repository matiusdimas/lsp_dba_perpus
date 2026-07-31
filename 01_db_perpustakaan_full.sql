-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: db_perpustakaan_kampus
-- ------------------------------------------------------
-- Server version	8.4.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `anggota`
--

DROP TABLE IF EXISTS `anggota`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `anggota` (
  `id_anggota` varchar(15) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text,
  PRIMARY KEY (`id_anggota`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `anggota`
--

LOCK TABLES `anggota` WRITE;
/*!40000 ALTER TABLE `anggota` DISABLE KEYS */;
INSERT INTO `anggota` VALUES ('AG-001','Dimas Prasetia','dimas@gmail.com','081234567890','Jakarta'),('AG-002','Anugrah Akbar','akbar@gmail.com','082198765432','Bekasi'),('AG-003','Yana Fitri','yana@gmail.com','083811223344','Depok'),('AG-004','yahud','yahud@gmail.com','12392132','bekasi'),('AG-005','Andi Saputra','andi@example.com','81234567890','Jl. Merdeka No. 1'),('AG-006','Budi Santosos','budi@example.com','81298765432','Jl. Sudirman No. 2');
/*!40000 ALTER TABLE `anggota` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `buku`
--

DROP TABLE IF EXISTS `buku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buku` (
  `id_buku` varchar(15) NOT NULL,
  `id_kategori` int NOT NULL,
  `judul` varchar(150) NOT NULL,
  `penulis` varchar(100) NOT NULL,
  `penerbit` varchar(100) NOT NULL,
  `stok` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_buku`),
  KEY `fk_buku_kategori` (`id_kategori`),
  CONSTRAINT `fk_buku_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chk_stok` CHECK ((`stok` >= 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buku`
--

LOCK TABLES `buku` WRITE;
/*!40000 ALTER TABLE `buku` DISABLE KEYS */;
INSERT INTO `buku` VALUES ('BK-001',1,'Pemrograman Web dengan Laravel','Andi Prasetyo','Informatika',6),('BK-002',2,'Analisis dan Perancangan Sistem','Siti Rahma','Bina Media',2),('BK-003',1,'Basis Data Relasional & SQL','Budi Santoso','Informatika',1),('BK-004',3,'Manajemen Organisasi Modern','Dedi Wijaya','Ghalia Indonesia',4),('BK-005',2,'xzc','zx','zx',10);
/*!40000 ALTER TABLE `buku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dokumen`
--

DROP TABLE IF EXISTS `dokumen`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dokumen` (
  `id_dokumen` int NOT NULL AUTO_INCREMENT,
  `judul_dokumen` varchar(150) NOT NULL,
  `nama_file` varchar(100) NOT NULL,
  `jenis_file` varchar(10) NOT NULL,
  `lokasi_file` varchar(255) NOT NULL,
  `versi` varchar(10) DEFAULT '1.0',
  `tgl_unggah` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokumen`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dokumen`
--

LOCK TABLES `dokumen` WRITE;
/*!40000 ALTER TABLE `dokumen` DISABLE KEYS */;
INSERT INTO `dokumen` VALUES (1,'Panduan Tata Tertib Perpustakaan 2026','tatib_2026.pdf','pdf','/uploads/docs/tatib_2026.pdf','1.0','2026-08-01 01:29:16'),(2,'Formulir Pendaftaran Anggota Baru','form_anggota.docx','docx','/uploads/docs/form_anggota.docx','2.1','2026-08-01 01:29:16'),(3,'SOP Denda dan Pengembalian','sop_denda.pdf','pdf','/uploads/docs/sop_denda.pdf','1.2','2026-08-01 01:29:16');
/*!40000 ALTER TABLE `dokumen` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori`
--

DROP TABLE IF EXISTS `kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(50) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori`
--

LOCK TABLES `kategori` WRITE;
/*!40000 ALTER TABLE `kategori` DISABLE KEYS */;
INSERT INTO `kategori` VALUES (1,'Informatika'),(2,'Sistem Informasi'),(3,'Manajemen');
/*!40000 ALTER TABLE `kategori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peminjaman`
--

DROP TABLE IF EXISTS `peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peminjaman` (
  `id_peminjaman` varchar(20) NOT NULL,
  `id_anggota` varchar(15) NOT NULL,
  `id_buku` varchar(15) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_jatuh_tempo` date NOT NULL,
  `status` enum('Dipinjam','Selesai') DEFAULT 'Dipinjam',
  PRIMARY KEY (`id_peminjaman`),
  KEY `fk_peminjaman_anggota` (`id_anggota`),
  KEY `fk_peminjaman_buku` (`id_buku`),
  CONSTRAINT `fk_peminjaman_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_peminjaman_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peminjaman`
--

LOCK TABLES `peminjaman` WRITE;
/*!40000 ALTER TABLE `peminjaman` DISABLE KEYS */;
INSERT INTO `peminjaman` VALUES ('PJ-2026-001','AG-001','BK-001','2026-08-01','2026-08-08','Selesai'),('PJ-2026-002','AG-002','BK-002','2026-07-20','2026-07-27','Selesai'),('PJ-2026-003','AG-004','BK-003','2026-07-31','2026-08-07','Selesai'),('PJ-2026-004','AG-004','BK-002','2026-07-31','2026-08-07','Selesai'),('PJ-2026-005','AG-001','BK-003','2026-07-31','2026-08-07','Dipinjam'),('PJ-2026-006','AG-001','BK-004','2026-07-31','2026-08-15','Selesai'),('PJ-2026-007','AG-004','BK-003','2026-07-31','2026-08-07','Selesai'),('PJ-2026-008','AG-003','BK-002','2026-07-31','2026-08-07','Dipinjam');
/*!40000 ALTER TABLE `peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengembalian`
--

DROP TABLE IF EXISTS `pengembalian`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengembalian` (
  `id_pengembalian` varchar(20) NOT NULL,
  `id_peminjaman` varchar(20) NOT NULL,
  `id_buku` varchar(15) NOT NULL,
  `tgl_kembali` date NOT NULL,
  `denda` decimal(10,2) DEFAULT '0.00',
  PRIMARY KEY (`id_pengembalian`),
  KEY `fk_pengembalian_peminjaman` (`id_peminjaman`),
  KEY `fk_pengembalian_buku` (`id_buku`),
  CONSTRAINT `fk_pengembalian_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pengembalian_peminjaman` FOREIGN KEY (`id_peminjaman`) REFERENCES `peminjaman` (`id_peminjaman`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengembalian`
--

LOCK TABLES `pengembalian` WRITE;
/*!40000 ALTER TABLE `pengembalian` DISABLE KEYS */;
INSERT INTO `pengembalian` VALUES ('KB-2026-001','PJ-2026-002','BK-002','2026-07-28',2000.00),('KB-2026-002','PJ-2026-003','BK-003','2026-07-31',0.00),('KB-2026-003','PJ-2026-001','BK-001','2026-08-08',0.00),('KB-2026-004','PJ-2026-004','BK-002','2026-08-13',12000.00),('KB-2026-005','PJ-2026-006','BK-004','2026-07-31',0.00),('KB-2026-006','PJ-2026-007','BK-003','2026-07-31',0.00);
/*!40000 ALTER TABLE `pengembalian` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('Administrator','Petugas','Anggota') DEFAULT 'Anggota',
  `id_anggota` varchar(15) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `id_anggota` (`id_anggota`),
  CONSTRAINT `fk_users_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$BWhChl0bBfmuuUW650lfRur.gIMjSiNWD95SB2MoU.XVRmtn9QHy6','Administrator Sistem','Administrator',NULL,1,'2026-08-01 01:29:16'),(2,'petugas','$2y$10$thLfmcA0AmIycofrtwsOr.KYsKDiOJccnovs8p62KIgh0lr.oqg1u','Petugas Perpustakaan','Petugas',NULL,1,'2026-08-01 01:29:16'),(3,'anggota','$2y$10$qOQHrI7AghD8BO3gnW5cIuPkp3FJXdUGG.SYPs5KgLdvqAQm9AAo.','Dimas Prasetia','Anggota','AG-001',1,'2026-08-01 01:29:16'),(4,'yahud','$2y$10$I1sKaUgQbIuwD1MIOTSt..IhPuk.LfCN5jgIPBFc9unElzmCtpFQm','yahud','Anggota','AG-004',1,'2026-08-01 01:30:53');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'db_perpustakaan_kampus'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-01  3:03:59
