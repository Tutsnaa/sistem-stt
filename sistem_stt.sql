-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: sistem_stt
-- ------------------------------------------------------
-- Server version	8.0.30

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
-- Table structure for table `agenda`
--

DROP TABLE IF EXISTS `agenda`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agenda` (
  `id_agenda` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` int NOT NULL,
  `masa_awal_jabatan` date DEFAULT NULL,
  `masa_akhir_jabatan` date DEFAULT NULL,
  `periode` varchar(50) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `tanggal_buka` datetime NOT NULL,
  `tanggal_tutup` datetime NOT NULL,
  `status` enum('draft','menunggu','disetujui','ditolak','dibuka','ditutup','selesai') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'ditutup',
  `tanggal_dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_agenda`),
  UNIQUE KEY `unique_judul_periode` (`judul`,`periode`),
  UNIQUE KEY `unique_masa_awal` (`masa_awal_jabatan`),
  UNIQUE KEY `unique_masa_akhir` (`masa_akhir_jabatan`),
  KEY `fk_agenda_pengguna` (`id_pengguna`),
  CONSTRAINT `fk_voting_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `check_tanggal` CHECK ((`tanggal_tutup` > `tanggal_buka`))
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agenda`
--

LOCK TABLES `agenda` WRITE;
/*!40000 ALTER TABLE `agenda` DISABLE KEYS */;
INSERT INTO `agenda` VALUES (66,41,'2026-07-02','2026-07-03','2026-2027','Pengumuman','2026-06-29 00:00:00','2026-06-29 17:59:00','selesai','2026-06-29 08:03:08','2026-06-29 09:59:02');
/*!40000 ALTER TABLE `agenda` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `calon_kandidat`
--

DROP TABLE IF EXISTS `calon_kandidat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calon_kandidat` (
  `id_calon` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` int NOT NULL,
  `id_agenda` int NOT NULL,
  `jabatan` enum('ketua','wakil','sekretaris 1','sekretaris 2','bendahara 1','bendahara 2','anggota') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `no_kandidat` int NOT NULL,
  `visi` text NOT NULL,
  `misi` text NOT NULL,
  `status` enum('menunggu','disetujui','ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'menunggu',
  `tanggal_dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_calon`),
  KEY `id_pengguna` (`id_pengguna`),
  KEY `fk_calon_kandidat_agenda` (`id_agenda`),
  CONSTRAINT `calon_kandidat_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE,
  CONSTRAINT `fk_calon_kandidat_agenda` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calon_kandidat`
--

LOCK TABLES `calon_kandidat` WRITE;
/*!40000 ALTER TABLE `calon_kandidat` DISABLE KEYS */;
INSERT INTO `calon_kandidat` VALUES (92,23,66,'ketua',1,'Mewujudkan organisasi yang solid, transparan, dan aktif dalam meningkatkan kualitas anggota serta memperkuat solidaritas antar anggota.','Meningkatkan komunikasi antar anggota melalui kegiatan rutin. Mengembangkan program kerja yang kreatif dan bermanfaat. Menciptakan transparansi dalam setiap pengambilan keputusan. Mendorong partisipasi aktif seluruh anggota dalam kegiatan organisasi.','disetujui','2026-06-29 09:54:58','2026-06-29 09:55:23'),(93,22,66,'ketua',2,'Mewujudkan organisasi yang solid, transparan, dan aktif dalam meningkatkan kualitas anggota serta memperkuat solidaritas antar anggota.','Meningkatkan komunikasi antar anggota melalui kegiatan rutin. Mengembangkan program kerja yang kreatif dan bermanfaat. Menciptakan transparansi dalam setiap pengambilan keputusan. Mendorong partisipasi aktif seluruh anggota dalam kegiatan organisasi.','disetujui','2026-06-29 09:55:15','2026-06-29 09:55:24');
/*!40000 ALTER TABLE `calon_kandidat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kepengurusan`
--

DROP TABLE IF EXISTS `kepengurusan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kepengurusan` (
  `id_kepengurusan` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` int NOT NULL,
  `id_agenda` int DEFAULT NULL,
  `masa_awal_jabatan` date NOT NULL,
  `masa_akhir_jabatan` date NOT NULL,
  `jabatan` enum('ketua','wakil','sekretaris 1','sekretaris 2','bendahara 1','bendahara 2','anggota') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tanggal_dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_kepengurusan`),
  KEY `id_pengguna` (`id_pengguna`),
  KEY `fk_kepengurusan_agenda` (`id_agenda`),
  CONSTRAINT `fk_kepengurusan_agenda` FOREIGN KEY (`id_agenda`) REFERENCES `agenda` (`id_agenda`),
  CONSTRAINT `kepengurusan_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kepengurusan`
--

LOCK TABLES `kepengurusan` WRITE;
/*!40000 ALTER TABLE `kepengurusan` DISABLE KEYS */;
INSERT INTO `kepengurusan` VALUES (25,23,66,'2026-07-02','2026-07-03','ketua','2026-06-29 09:59:39','2026-06-29 09:59:39');
/*!40000 ALTER TABLE `kepengurusan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keuangan`
--

DROP TABLE IF EXISTS `keuangan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `keuangan` (
  `id_keuangan` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` int NOT NULL,
  `jenis` enum('pengeluaran','pemasukan') NOT NULL,
  `keterangan` text NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `file_bukti` varchar(255) DEFAULT NULL,
  `status` enum('menunggu','disetujui','ditolak') NOT NULL DEFAULT 'menunggu',
  `tanggal_dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_keuangan`),
  KEY `fk_keuangan_pengguna` (`id_pengguna`),
  CONSTRAINT `fk_keuangan_pengguna` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `keuangan_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keuangan`
--

LOCK TABLES `keuangan` WRITE;
/*!40000 ALTER TABLE `keuangan` DISABLE KEYS */;
INSERT INTO `keuangan` VALUES (19,12,'pemasukan','Donasi dari sponsor kegiatan HUT STT',1500000.00,'1781158330_CONTOH INVOICE PEMASUKAN.pdf','disetujui','2026-06-11 06:12:10','2026-06-11 06:14:34'),(20,12,'pemasukan','Hasil penjualan kupon bazar',3000000.00,NULL,'disetujui','2026-06-11 06:12:38','2026-06-11 10:23:08'),(21,12,'pengeluaran','Pembelian konsumsi untuk rapat pengurus',450000.00,'1781158397_CONTOH PENGELUARAN BELANJA.png','disetujui','2026-06-11 06:13:17','2026-06-11 06:14:32'),(22,12,'pengeluaran','Pembelian alat kebersihan',350000.00,NULL,'ditolak','2026-06-11 06:13:40','2026-06-11 06:14:27'),(23,12,'pemasukan','Dana bantuan dari desa adat',5000000.00,'1781158506_CONTOH INVOICE PEMASUKAN.pdf','menunggu','2026-06-11 06:15:06','2026-06-11 06:15:06');
/*!40000 ALTER TABLE `keuangan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengguna`
--

DROP TABLE IF EXISTS `pengguna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengguna` (
  `id_pengguna` int NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `jabatan` enum('admin','ketua','wakil','sekretaris 1','sekretaris 2','bendahara 1','bendahara 2','anggota') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `nama_pengguna` varchar(50) NOT NULL,
  `kata_sandi` varchar(255) NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `tanggal_dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pengguna`),
  UNIQUE KEY `nama_lengkap` (`nama_lengkap`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `nama_pengguna` (`nama_pengguna`),
  UNIQUE KEY `nama_pengguna_2` (`nama_pengguna`),
  UNIQUE KEY `email_2` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengguna`
--

LOCK TABLES `pengguna` WRITE;
/*!40000 ALTER TABLE `pengguna` DISABLE KEYS */;
INSERT INTO `pengguna` VALUES (1,'Bagus Putra','1774233803_avatar_umum.png','ketuastt@gmail.com','081234125355','Gianyar','anggota','bagus','$2y$10$GdIaNEvP72r.KvdXCOQj0.G41UjwOLRkyG0VfSUrmBHJyb5sYFWMy','aktif','2026-03-01 14:46:40','2026-05-26 14:00:37'),(8,'Anggota6','1779810477_avatar_umum.png','Anggota6@gmail.com','081234125371','Denpasar','anggota','wakil','$2y$10$HgSIQ4sBlhdxptUu/dCt3uVVrLNlZ9uMTlbcr1xrcP594ZQvwGiZi','aktif','2026-03-08 14:22:45','2026-05-26 15:47:57'),(9,'Komang Santhi Sapitri','1774234873_AVATAR_6.jpg','sekretaris1@gmail.com','081234567222','Bali','sekretaris 1','sapitri','$2y$10$EBd5wVbS2Xyr8WDNMQyureQPHJBuCaFD0/kPxpmjnkYRVWzH966ry','aktif','2026-03-09 14:03:47','2026-05-08 10:07:24'),(12,'I Gede Russandy Sentana','1774234861_AVATAR_5.jpg','bendahara1@gmail.com','081567890123','Bali','bendahara 1','sentana','$2y$10$BP65k7HSmIrF2NrtG1/LeuSO676CwjsykXdX9j6YzZu/QRnZNk9Cu','aktif','2026-03-11 15:39:11','2026-05-18 07:16:31'),(13,'Ni Putu Mirah Manis Swari','1774234847_AVATAR_4.jpg','bendahara2@gmail.com','081234567123','Bali','bendahara 2','swari','$2y$10$aUXHYCrdxz7IFPJAo7m17.Ru/WV8OoXcXRMZNOn7reBvjP8x1c2fC','aktif','2026-03-11 16:45:06','2026-05-08 10:08:42'),(14,'I Komang Agus Suadnyana Saputra','1774234829_AVATAR_3.jpg','sekretaris2@gmail.com','081124567880','Denpasar, bali','sekretaris 2','saputra','$2y$10$Bp/2VpdeshoFiXz5Vnhfh.e9hQ91Lr6IQgB6g3B3GxVNH3JjtCdka','aktif','2026-03-11 16:54:28','2026-05-08 10:10:36'),(22,'Anggota1','1774233748_avatar_umum.png','anggota1@gmail.com','081234567890','Bali','anggota','anggota1','$2y$10$HgSIQ4sBlhdxptUu/dCt3uVVrLNlZ9uMTlbcr1xrcP594ZQvwGiZi','aktif','2026-03-13 17:49:52','2026-06-11 03:44:04'),(23,'I Gede Mahesa Surya Sentana','1774234813_AVATAR_1.jpg','ketua@gmail.com','081000000000','BaliJl. Melati Indah No. 123, Blok B2, Perumahan Griya Sejahtera, Desa Sukamaju, Kecamatan Sentosa, Kota Harmoni, Provinsi Nusantara','ketua','mahesa','$2y$10$Z.oMz.ojhROLSZWJLPRQEeuuF/.m4fTixz0jhk3KjM4B0.9vZBlYS','aktif','2026-03-19 08:05:06','2026-05-25 06:39:22'),(24,'Wayan Hendra','1774234916_avatar_umum.png','wayanhendra@gmail.com','081000000001','Jl. Melati Indah No. 123, Blok B2, Perumahan Griya Sejahtera, Desa Sukamaju, Kecamatan Sentosa, Kota Harmoni, Provinsi Nusantara, Kode Pos 12345, Dekat Taman Kota dan Sebelah Minimarket','anggota','hendra','$2y$10$9a1eoVj5CD5ASNX1Xw8.su/xW2fcDGv.WNmEi4a0mrHBSERAmvNOK','aktif','2026-03-19 08:40:27','2026-03-26 12:32:06'),(30,' I Wayan Manik Arsaguna','1774534052_avatar_umum.png','wakil@gmail.com','082147148191','Bali Indonesia','wakil','manik','$2y$10$jZK3ucIOmyqw47ZjWoW.Mev/kcsUZf3CAhg.XbYJ4gjHYlp0ttBhi','aktif','2026-03-26 14:07:32','2026-05-26 14:00:37'),(37,'Anggota2','1779689543_avatar_umum.png','anggota2@gmail.com','081234567880','Bali','anggota','anggota2','$2y$10$JlWwjkv3G2ZRL4eWoYigo.7YEuK4IO0LpicRJ/Xc9gyD/tAxl7luu','aktif','2026-05-25 06:12:23','2026-05-25 06:12:23'),(38,'Anggota3','1779689631_avatar_umum.png','anggota3@gmail.com','081234567880','Bali','anggota','anggota3','$2y$10$YRdRtllfTz38Sbv44fLsBu.N7Wk7Ba.FBHusaS/0k0Re1XCIgR9.2','aktif','2026-05-25 06:13:51','2026-05-25 06:13:51'),(39,'Anggota4','1779689670_avatar_umum.png','anggota4@gmail.com','081234567880','Bali','anggota','anggota4','$2y$10$PznZ47TYgIK.zkRQkcFFFO6WwKc65FdV717UUf7B3mKgeh2Ii/Zce','aktif','2026-05-25 06:14:30','2026-05-25 06:14:30'),(40,'Anggota5','1779805805_avatar_umum.png','anggota5@gmail.com','081234567880','Bali','anggota','anggota5','$2y$10$UdtjnA1WRvULdLULHFOhQuQ8Km7mVqoyGdMBepWlvIxIxD2TMyANS','aktif','2026-05-26 14:30:05','2026-05-26 14:30:05'),(41,'ADMIN','1779811343_LOGOSTT.png','admin@gmail.com','000000000000','ADMIN','admin','admin','$2y$10$PJIMZFkMbKf.kZWePb/lFekUGokJOuA0Fu.XlMV4lSUr64qBFNOLW','aktif','2026-05-26 16:02:23','2026-05-26 16:02:51'),(44,'anggota7','1779959741_avatar_umum.png','anggota7@gmail.com','081234567880','Bali','anggota','anggota7','$2y$10$iM0zFjyBMaGWG5J2aBMvtObT4c72RjkVWNX7vLV7vHHNmZDGw7kDW','aktif','2026-05-28 09:15:42','2026-06-11 05:56:56');
/*!40000 ALTER TABLE `pengguna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengumuman`
--

DROP TABLE IF EXISTS `pengumuman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengumuman` (
  `id_pengumuman` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` int NOT NULL,
  `judul` varchar(150) NOT NULL,
  `isi` text NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `status` enum('Menunggu','Disetujui','Ditolak') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Menunggu',
  `tanggal_dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pengumuman`),
  KEY `id_pengguna` (`id_pengguna`),
  CONSTRAINT `pengumuman_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengumuman`
--

LOCK TABLES `pengumuman` WRITE;
/*!40000 ALTER TABLE `pengumuman` DISABLE KEYS */;
INSERT INTO `pengumuman` VALUES (39,41,'Persiapan Gotong Royong Banjar','Seluruh anggota Sekaa Truna Truni diharapkan mengikuti kegiatan gotong royong pada Sabtu, 21 Juni 2026 pukul 07.00 WITA di area Balai Banjar dan lingkungan sekitar. Mohon membawa perlengkapan kebersihan masing-masing.','Pengumuman_Gotong_Royong_STT_Galuh_Mantri.pdf','Disetujui','2026-06-11 05:34:01','2026-06-11 05:41:12'),(40,41,'Jadwal Latihan Tari dan Tabuh','Dalam rangka persiapan pementasan pada acara adat desa, latihan tari dan tabuh akan dilaksanakan setiap Selasa dan Kamis pukul 19.00 WITA di Balai Banjar. Seluruh peserta dimohon hadir tepat waktu.','','Ditolak','2026-06-11 05:34:28','2026-06-11 05:41:18'),(41,9,'Pengumuman Perekrutan Anggota Baru','Sekaa Truna Truni membuka pendaftaran anggota baru bagi pemuda dan pemudi yang telah memenuhi persyaratan sesuai ketentuan organisasi. Pendaftaran dibuka hingga akhir bulan ini.','','Menunggu','2026-06-11 05:41:58','2026-06-11 05:41:58');
/*!40000 ALTER TABLE `pengumuman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suara_voting`
--

DROP TABLE IF EXISTS `suara_voting`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suara_voting` (
  `id_suara` int NOT NULL AUTO_INCREMENT,
  `id_pengguna` int NOT NULL,
  `id_calon` int NOT NULL,
  `suara` int DEFAULT '0',
  `tanggal_dibuat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_diubah` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_suara`),
  UNIQUE KEY `id_pengguna` (`id_pengguna`,`id_calon`),
  KEY `id_calon` (`id_calon`),
  CONSTRAINT `suara_voting_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id_pengguna`) ON DELETE CASCADE,
  CONSTRAINT `suara_voting_ibfk_2` FOREIGN KEY (`id_calon`) REFERENCES `calon_kandidat` (`id_calon`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suara_voting`
--

LOCK TABLES `suara_voting` WRITE;
/*!40000 ALTER TABLE `suara_voting` DISABLE KEYS */;
INSERT INTO `suara_voting` VALUES (93,22,92,1,'2026-06-29 01:56:46','2026-06-29 09:56:46'),(94,37,92,1,'2026-06-29 01:57:04','2026-06-29 09:57:04'),(95,38,93,1,'2026-06-29 01:57:29','2026-06-29 09:57:29');
/*!40000 ALTER TABLE `suara_voting` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-30 13:55:58
