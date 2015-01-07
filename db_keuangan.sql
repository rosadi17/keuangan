-- phpMyAdmin SQL Dump
-- version 4.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2014 at 11:45 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `db_keuangan`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_uraian`
--

CREATE TABLE IF NOT EXISTS `detail_uraian` (
  `id` int(11) NOT NULL,
  `id_uraian` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `data_kuat_org` varchar(100) NOT NULL,
  `sg_orang` int(11) NOT NULL,
  `sg_hari_bulan` int(11) NOT NULL,
  `harga_satuan` double NOT NULL,
  `jml_biaya` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `jurnal`
--

CREATE TABLE IF NOT EXISTS `jurnal` (
`id` int(11) NOT NULL,
  `kode_nota` varchar(14) NOT NULL,
  `tanggal` datetime NOT NULL,
  `id_rekening` int(11) NOT NULL,
  `id_uraian` int(11) DEFAULT NULL,
  `perwabku` varchar(20) NOT NULL,
  `debet` double NOT NULL,
  `kredit` double NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `jurnal`
--

INSERT INTO `jurnal` (`id`, `kode_nota`, `tanggal`, `id_rekening`, `id_uraian`, `perwabku`, `debet`, `kredit`, `keterangan`) VALUES
(1, '', '2014-02-21 00:00:00', 111303, 1, 'Default', 19200000, 0, ''),
(2, '', '2014-02-21 00:00:00', 212110, 4, 'Default', 0, 16000000, ''),
(3, 'BKM14080001', '2014-09-05 10:59:37', 111120, NULL, '', 0, 25000000, ''),
(4, 'BKM14080001', '2014-09-05 10:59:37', 111120, NULL, '', 25000000, 0, ''),
(5, 'BKM14080002', '2014-09-05 11:04:40', 111302, NULL, '', 0, 20000000, 'Uraian'),
(6, 'BKM14080002', '2014-09-05 11:04:40', 111302, NULL, '', 20000000, 0, 'Uraian');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE IF NOT EXISTS `kegiatan` (
`id` int(11) NOT NULL,
  `kode` int(11) NOT NULL,
  `id_program` int(11) NOT NULL,
  `nama_kegiatan` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`id`, `kode`, `id_program`, `nama_kegiatan`) VALUES
(1, 11000, 1, 'PEMELIHARAAN PERSONIL'),
(2, 31300, 2, 'Pengabdian Masyarakat'),
(3, 13000, 1, 'PENGEMBANGAN KEMAMPUAN PERSONIL'),
(4, 13000, 7, 'Pengembangan Kemampuan Personil (Bangkuat Pers)'),
(5, 11000, 4, 'Pemelilharaan Personil'),
(6, 21000, 5, 'Pembangunan Materiil dan Fasilitas (Pembangunan Matfas)'),
(7, 22000, 5, 'PEMBERDAYAAN SARANA DAN PRASARANA'),
(8, 21000, 8, 'Pembangunan Materiil dan Fasilitas (Pembangunan Matfas)'),
(9, 33000, 9, 'dukungan umum'),
(10, 13000, 10, 'Pengembangan Kemampuan Personil (Bangkuat Pers)'),
(11, 21300, 11, 'Pengadaan Materiil'),
(12, 11000, 12, 'Pemeliharaan Personil');

-- --------------------------------------------------------

--
-- Table structure for table `module`
--

CREATE TABLE IF NOT EXISTS `module` (
`id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `module`
--

INSERT INTO `module` (`id`, `nama`, `keterangan`) VALUES
(1, 'Master Data', 'Pengelolaan referensi data keuangan'),
(2, 'Transaksi', 'Entri Data Transaksi Keuangan'),
(3, 'Report', 'Laporan data transaksi keuangan');

-- --------------------------------------------------------

--
-- Table structure for table `pagu_anggaran`
--

CREATE TABLE IF NOT EXISTS `pagu_anggaran` (
`id` int(11) NOT NULL,
  `id_satker` int(11) NOT NULL,
  `tahun` year(4) NOT NULL,
  `pagu` double NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `pagu_anggaran`
--

INSERT INTO `pagu_anggaran` (`id`, `id_satker`, `tahun`, `pagu`, `id_user`) VALUES
(1, 11, 2014, 659174100, 1),
(2, 3, 2014, 87600000, 1),
(3, 10, 2014, 577860000, 1),
(4, 9, 2014, 708715000, 1),
(5, 8, 2014, 382992800, 1),
(7, 6, 2014, 583578000, 1),
(8, 5, 2014, 109100000, 1),
(9, 4, 2014, 37650000, 1),
(10, 1, 2014, 983347000, 1),
(11, 12, 2014, 8423962390, 1),
(14, 2, 2014, 430830000, 1),
(16, 13, 2014, 345000000, 3),
(17, 14, 2014, 1000000000, 3);

-- --------------------------------------------------------

--
-- Table structure for table `penerimaan`
--

CREATE TABLE IF NOT EXISTS `penerimaan` (
`id` int(11) NOT NULL,
  `kode` varchar(14) NOT NULL,
  `sumberdana` enum('Kas','Bank') NOT NULL,
  `tanggal` date NOT NULL,
  `id_rekening` int(11) DEFAULT NULL,
  `id_uraian` int(11) NOT NULL,
  `pemasukkan` double NOT NULL,
  `penyetor` varchar(100) NOT NULL,
  `perwabku` varchar(20) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `penerimaan`
--

INSERT INTO `penerimaan` (`id`, `kode`, `sumberdana`, `tanggal`, `id_rekening`, `id_uraian`, `pemasukkan`, `penyetor`, `perwabku`) VALUES
(6, 'BKM14080001', 'Kas', '2014-08-26', 111302, 2, 135000000, 'Arvin Nizar', 'Default'),
(7, 'BKM14080002', 'Kas', '2014-08-26', 111303, 2, 135000000, 'Suherman', 'Default');

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE IF NOT EXISTS `pengeluaran` (
`id` int(11) NOT NULL,
  `kode` varchar(15) NOT NULL,
  `sumberdana` enum('Kas','Bank') NOT NULL,
  `tanggal` date NOT NULL,
  `id_rekening` int(11) DEFAULT NULL,
  `id_uraian` int(11) NOT NULL,
  `pengeluaran` double NOT NULL,
  `penerima` varchar(100) NOT NULL,
  `perwabku` varchar(20) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id`, `kode`, `sumberdana`, `tanggal`, `id_rekening`, `id_uraian`, `pengeluaran`, `penerima`, `perwabku`) VALUES
(1, 'BKK14090001', 'Kas', '2014-09-06', 111303, 2, 135000000, 'Arjun', 'Default');

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE IF NOT EXISTS `privileges` (
`id` int(11) NOT NULL,
  `module_id` int(11) DEFAULT NULL,
  `form_nama` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `show_desktop` int(1) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`id`, `module_id`, `form_nama`, `url`, `show_desktop`) VALUES
(1, 1, 'Unit Satuan Kerja', 'masterdata/unit', 1),
(2, 1, 'Referensi Kegiatan', 'masterdata/kegiatan', 1),
(3, 2, 'Dropping', 'transaksi/dropping', 1),
(4, 2, 'Rencana Kebutuhan', 'transaksi/renbut', 1),
(6, 3, 'Lap. Realisasi', 'laporan/realisasi', 1),
(8, 1, 'User Account', 'masterdata/account', 1),
(9, 2, 'Pencairan', 'transaksi/pencairan', 1),
(10, 1, 'Kode Akun', 'masterdata/kode_akun', 1),
(11, 1, 'Entri Pagu Anggaran', 'transaksi/pagu', 1),
(13, 2, 'Pemasukkan (BKM)', 'transaksi/pemasukan', 0),
(14, 3, 'Lap. Pengeluaran Uang', 'laporan/pengeluaran', 1),
(15, 3, 'Grafik Laporan Renbut & Realisasi', 'laporan/grafik', 1),
(16, 2, 'Kasir (BKM / BKK)', 'transaksi/kasir', 1),
(17, 2, 'Kode Lawan Perkiraan', 'transaksi/kode_perkiraan', 1),
(18, 3, 'Catatan Kas & Bank', 'laporan/kasbank', 1),
(19, 1, 'Ubah Password', 'masterdata/ubahpassword', 1),
(20, 2, 'Jurnal Transaksi', 'transaksi/jurnal', 1);

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE IF NOT EXISTS `program` (
`id` int(11) NOT NULL,
  `kode` int(11) NOT NULL,
  `id_satker` int(11) NOT NULL,
  `nama_program` varchar(255) NOT NULL,
  `status` enum('SPP','NON SPP') NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`id`, `kode`, `id_satker`, `nama_program`, `status`) VALUES
(1, 10000, 2, 'PROGRAM PENGEMBANGAN SUMBER DAYA MANUSIA (SDM)', 'SPP'),
(2, 30000, 2, 'PROGRAM PEMBERDAYAAN LEMBAGA DAN PELAKSANAAN OPERASIONAL PERKULIAHAAN', 'SPP'),
(3, 30000, 2, 'KEGIATAN PROMOSI DAN PUBLIKASI PMB 2013/2014', 'NON SPP'),
(4, 10000, 1, 'PROGRAM PENGEMBANGAN SUMBER DAYA MANUSIA (SDM)', 'SPP'),
(5, 20000, 1, 'PROGRAM PENGEMBANGAN SARANA DAN PRASARANA', 'SPP'),
(6, 30000, 1, 'PROGRAM PEMBERDAYAAN LEMBAGA DAN PELAKSANAAN OPERASIONAL PERKULIAHAN', 'SPP'),
(7, 10000, 3, 'PROGRAM PENGEMBANGAN SUMBER DAYA MANUSIA (SDM)', 'SPP'),
(8, 20000, 3, 'PROGRAM PENGEMBANGAN SARANA DAN PRASARANA', 'SPP'),
(9, 30000, 3, 'PROGRAM PEMBERDAYAAN LEMBAGA DAN PELAKSANAAN  OPERASIONAL PERKULIAHAN', 'SPP'),
(10, 10000, 4, 'PROGRAM PENGEMBANGAN SUMBER DAYA MANUSIA (SDM)', 'SPP'),
(11, 20000, 4, 'PROGRAM PENGEMBANGAN SARANA DAN PRASARANA', 'SPP'),
(12, 10000, 14, 'PROGRAM PENGEMBANGAN SUMBER DAYA MANUSIA (SDM)', 'SPP');

-- --------------------------------------------------------

--
-- Table structure for table `rekening`
--

CREATE TABLE IF NOT EXISTS `rekening` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `posisi` enum('D','C') NOT NULL,
  `urut` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rekening`
--

INSERT INTO `rekening` (`id`, `nama`, `posisi`, `urut`) VALUES
(100000, 'ASET', 'D', 1),
(200000, 'KEWAJIBAN', 'C', 2),
(300000, 'ASET BERSIH', 'D', 3),
(400000, 'PENDAPATAN', 'D', 4),
(500000, 'BEBAN DAN KERUGIAN', 'C', 5);

-- --------------------------------------------------------

--
-- Table structure for table `rencana_kebutuhan`
--

CREATE TABLE IF NOT EXISTS `rencana_kebutuhan` (
`id_renbut` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `tanggal_kegiatan` date DEFAULT NULL,
  `id_uraian` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `jml_renbut` double NOT NULL,
  `cashbon` double NOT NULL,
  `nominal` double NOT NULL,
  `penerima` varchar(100) NOT NULL,
  `verificator` int(11) DEFAULT NULL,
  `status` enum('Rencana','Disetujui','Ditolak') NOT NULL,
  `date_verify` date DEFAULT NULL,
  `tanggal_cair` date DEFAULT NULL,
  `id_akun_rekening` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `rencana_kebutuhan`
--

INSERT INTO `rencana_kebutuhan` (`id_renbut`, `tanggal`, `tanggal_kegiatan`, `id_uraian`, `keterangan`, `jml_renbut`, `cashbon`, `nominal`, `penerima`, `verificator`, `status`, `date_verify`, `tanggal_cair`, `id_akun_rekening`) VALUES
(3, '2014-01-19', '2014-02-10', 2, 'Pengadaan Sarana Prasarana ( Rincian terlampir )', 12500000, 0, 135000000, 'Kirk Hammet M.Kom', 1, 'Disetujui', '2014-01-22', '2014-09-06', 111303),
(4, '2014-01-26', '2014-01-26', 3, 'Pembiayaan, Sarana Prasarana dan Sistem Informasi', 120000000, 0, 0, 'Andi Deris S. Kom', 3, 'Disetujui', '2014-01-26', '2014-08-26', 111303),
(5, '2014-01-26', '2014-01-26', 4, 'Lembur ', 17000000, 0, 17000000, 'Hari Toha Hidayat', 5, 'Disetujui', '2014-01-26', '2014-01-26', 212120),
(6, '2014-01-26', '2014-01-26', 3, 'Pembiayaan, Sarana Prasarana dan Sistem Informasi', 20000000, 20000000, 0, 'Arvin Nizar', 2, 'Disetujui', '2014-01-26', '2014-08-26', 111303),
(8, '2014-02-01', '2014-02-01', 1, 'honor mengajars', 19200000, 0, 19200000, 'James Hetfield', 3, 'Disetujui', '2014-02-01', '2014-02-21', 212110);

-- --------------------------------------------------------

--
-- Table structure for table `satker`
--

CREATE TABLE IF NOT EXISTS `satker` (
`id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kode` varchar(4) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `satker`
--

INSERT INTO `satker` (`id`, `nama`, `kode`) VALUES
(1, 'PPS', '14'),
(2, 'HUMAS', '09'),
(3, 'BPM', '04'),
(4, 'LAB. BAHASA', '10'),
(5, 'PERPUSTAKAAN', '12'),
(6, 'LPPM', '11'),
(8, 'FAK. FISIP', '07'),
(9, 'FAK. EKONOMI', '06'),
(10, 'FAK.  HUKUM', '05'),
(11, 'BAA', '0101'),
(12, 'PIMPINAN', '13'),
(13, 'BIKU', '03'),
(14, 'BAPSI', '02');

-- --------------------------------------------------------

--
-- Table structure for table `sub_kegiatan`
--

CREATE TABLE IF NOT EXISTS `sub_kegiatan` (
`id` int(11) NOT NULL,
  `kode` int(11) NOT NULL,
  `id_kegiatan` int(11) NOT NULL,
  `nama_sub_kegiatan` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `sub_kegiatan`
--

INSERT INTO `sub_kegiatan` (`id`, `kode`, `id_kegiatan`, `nama_sub_kegiatan`) VALUES
(1, 11100, 5, 'Administrasi umum'),
(3, 21300, 6, 'Pengadaan Materiil'),
(4, 13100, 4, 'Pelatihan SPMI'),
(5, 21300, 8, 'Pengadaan Materiil'),
(6, 33100, 9, 'Pembuatan Naskah / Buku lainnya'),
(7, 33600, 9, 'Biaya Pembinaan / Pengawasan / Pengendalian'),
(8, 13100, 10, 'Biaya Pendidikan & Pelatihan (Diklat)'),
(9, 11100, 12, 'Administrasi Umum');

-- --------------------------------------------------------

--
-- Table structure for table `sub_rekening`
--

CREATE TABLE IF NOT EXISTS `sub_rekening` (
  `id` int(11) NOT NULL,
  `id_rekening` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_rekening`
--

INSERT INTO `sub_rekening` (`id`, `id_rekening`, `nama`) VALUES
(110000, 100000, 'Aset Lancar'),
(120000, 100000, 'Aset Tidak Lancar'),
(210000, 200000, 'KEWAJIBAN LANCAR'),
(220000, 200000, 'Kewajiban Tidak Lancar'),
(310000, 300000, 'ASET BERSIH TIDAK TERIKAT'),
(320000, 300000, 'ASET BERSIH TERIKAT TEMPORER'),
(410000, 400000, 'PENDAPATAN TAK TERIKAT'),
(510000, 500000, 'PROGRAM PENGEMBANGAN SDM');

-- --------------------------------------------------------

--
-- Table structure for table `sub_sub_rekening`
--

CREATE TABLE IF NOT EXISTS `sub_sub_rekening` (
  `id` int(11) NOT NULL,
  `id_sub_rekening` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_sub_rekening`
--

INSERT INTO `sub_sub_rekening` (`id`, `id_sub_rekening`, `nama`) VALUES
(111000, 110000, 'Kas dan Setara Kas'),
(121000, 120000, 'Kas dan Tidak Setara Kas'),
(212000, 210000, 'Utang Pajak'),
(211000, 210000, 'Utang Usaha'),
(313000, 310000, 'AKUMULASI SELISIH PENERIMAAN'),
(312000, 310000, 'MODAL BANTUAN / HIBAH'),
(311000, 310000, 'MODAL DANA YAYASAN'),
(321000, 320000, 'ASET BERSIH TERIKAT TEMPORER TAHUN LALU');

-- --------------------------------------------------------

--
-- Table structure for table `sub_sub_sub_rekening`
--

CREATE TABLE IF NOT EXISTS `sub_sub_sub_rekening` (
`id` int(11) NOT NULL,
  `id_sub_sub_rekening` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=321101 ;

--
-- Dumping data for table `sub_sub_sub_rekening`
--

INSERT INTO `sub_sub_sub_rekening` (`id`, `id_sub_sub_rekening`, `nama`) VALUES
(111300, 111000, 'BANK'),
(111100, 111000, 'Kas'),
(212100, 212000, 'Pajak Penghasilan'),
(321100, 321000, 'Aset Bersih Terikat Temporer Tahun Lalu');

-- --------------------------------------------------------

--
-- Table structure for table `sub_sub_sub_sub_rekening`
--

CREATE TABLE IF NOT EXISTS `sub_sub_sub_sub_rekening` (
  `id` int(11) NOT NULL,
  `id_sub_sub_sub_rekening` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sub_sub_sub_sub_rekening`
--

INSERT INTO `sub_sub_sub_sub_rekening` (`id`, `id_sub_sub_sub_rekening`, `nama`) VALUES
(111110, 111100, 'Kas YBBDJ I'),
(111120, 111100, 'Kas YBBDJ II'),
(111303, 111300, 'Bank BNI (GIRO) RC 0054535137'),
(111304, 111300, 'Bank BNI (GIRO) RC 0054535148'),
(111302, 111300, 'Bank BNI (GIRO) RC 0054536651'),
(111301, 111300, 'Bank BNI (GIRO) RC 0054536673'),
(111305, 111300, 'Bank BNI (GIRO) RC 0054536684'),
(111309, 111300, 'Bank BTN (GIRO) RC 00203.01.50.003597'),
(111307, 111300, 'Bank BTN (GIRO) RC 02-01-30-000004-3'),
(111308, 111300, 'Bank BTN (GIRO) RC 02-01-30-000005-1'),
(111306, 111300, 'Bank BTN (GIRO) RC 02-01-30-000666-1'),
(111310, 111300, 'Bank BTN (GIRO) RC 02-01-55-0000027-9'),
(111311, 111300, 'Bank BTN (GIRO) RC 64-01-50-006010-2'),
(111313, 111300, 'BANK BTN (Kelas Internasional)'),
(111312, 111300, 'BANK BTN (Rekening Rektor)'),
(212110, 212100, 'Pajak PPh pasal 21'),
(212120, 212100, 'Pajak PPh pasal 22');

-- --------------------------------------------------------

--
-- Table structure for table `sub_sub_uraian`
--

CREATE TABLE IF NOT EXISTS `sub_sub_uraian` (
`id` int(11) NOT NULL,
  `kode` int(11) DEFAULT NULL,
  `id_sub_uraian` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `data_kuat_org` varchar(20) NOT NULL,
  `vol_orang` int(11) NOT NULL,
  `vol_hari_perbulan` int(11) NOT NULL,
  `harga_satuan` double NOT NULL,
  `sub_total` double NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `sub_sub_uraian`
--

INSERT INTO `sub_sub_uraian` (`id`, `kode`, `id_sub_uraian`, `keterangan`, `data_kuat_org`, `vol_orang`, `vol_hari_perbulan`, `harga_satuan`, `sub_total`) VALUES
(1, 1, 1, 'Dr. Nurdin Kaimuddin, Ir.MS (Manaj.Opsi)', 'SKS', 3, 16, 100000, 4800000),
(2, 2, 1, 'b. Dr. H.Widiawan AP,SE.MM(SIM)', 'SKS', 3, 16, 100000, 4800000),
(3, 3, 1, 'c.  Dr. Marcus Lukman, SH.MHum (Filsafat Ilmu)', 'sks', 3, 16, 100000, 4800000),
(4, 4, 1, 'd. Dr.Hj. Musriha, MSi ( Anal. Kuan.Bisnis)', 'sks', 3, 16, 100000, 4800000);

-- --------------------------------------------------------

--
-- Table structure for table `sub_uraian`
--

CREATE TABLE IF NOT EXISTS `sub_uraian` (
`id` int(11) NOT NULL,
  `kode` int(11) DEFAULT NULL,
  `id_uraian` int(11) NOT NULL,
  `keterangan` text NOT NULL,
  `data_kuat_org` varchar(20) NOT NULL,
  `vol_orang` int(11) DEFAULT NULL,
  `vol_hari_perbulan` int(11) DEFAULT NULL,
  `harga_satuan` double DEFAULT NULL,
  `sub_total` double DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `sub_uraian`
--

INSERT INTO `sub_uraian` (`id`, `kode`, `id_uraian`, `keterangan`, `data_kuat_org`, `vol_orang`, `vol_hari_perbulan`, `harga_satuan`, `sub_total`) VALUES
(1, 1, 1, '1. SM I-MM reguler XI', '', 0, 0, 0, 0),
(4, 1, 2, 'Komputer 10 Unit', 'Unit', 3, 10, 4500000, 135000000),
(5, 2, 1, '2. SM II-MM reguler XI', '', 0, 0, 0, 0),
(6, 1, 4, 'Lembur Penyusunan Analisa dan Evaluasi', 'orang', 10, 16, 100000, 16000000);

-- --------------------------------------------------------

--
-- Table structure for table `uraian`
--

CREATE TABLE IF NOT EXISTS `uraian` (
`id` int(11) NOT NULL,
  `kode` varchar(11) NOT NULL,
  `id_sub_kegiatan` int(11) NOT NULL,
  `uraian` varchar(255) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `uraian`
--

INSERT INTO `uraian` (`id`, `kode`, `id_sub_kegiatan`, `uraian`) VALUES
(1, '11112', 1, 'honor mengajars'),
(2, '21301', 3, 'Pengadaan Sarana Prasarana ( Rincian terlampir )'),
(3, '21309', 3, 'Pembiayaan, Sarana Prasarana dan Sistem Informasi'),
(4, '11111', 9, 'Lembur ');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `id_user_group` int(11) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `password`, `id_user_group`) VALUES
(1, 'Hari Toha Hidayat', 'hari', '3c2234a7ce973bc1700e0c743d6a819c', 2),
(2, 'Ferli Malmsteen S.Pd', 'ferli.aprianingrum', '81dc9bdb52d04dc20036dbd8313ed055', 3),
(3, 'Arvin Nizar S.Kom', 'arvinnizar', '81dc9bdb52d04dc20036dbd8313ed055', 1),
(5, 'Axel Fabianski', 'axel', '81dc9bdb52d04dc20036dbd8313ed055', 2),
(6, 'Ainun Najib', 'ainun', '81dc9bdb52d04dc20036dbd8313ed055', 6);

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE IF NOT EXISTS `user_group` (
`id` int(11) NOT NULL,
  `nama` varchar(30) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`id`, `nama`) VALUES
(1, 'Anggaran'),
(2, 'KA BIKU'),
(3, 'Kasir'),
(4, 'Akuntan'),
(5, 'Administrator'),
(6, 'Akademik');

-- --------------------------------------------------------

--
-- Table structure for table `user_group_privileges`
--

CREATE TABLE IF NOT EXISTS `user_group_privileges` (
`id` int(11) NOT NULL,
  `user_group_id` int(11) NOT NULL,
  `privileges_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=122 ;

--
-- Dumping data for table `user_group_privileges`
--

INSERT INTO `user_group_privileges` (`id`, `user_group_id`, `privileges_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(6, 1, 6),
(8, 1, 8),
(9, 1, 9),
(25, 1, 10),
(26, 1, 11),
(30, 1, 13),
(31, 1, 14),
(32, 1, 15),
(109, 2, 1),
(107, 2, 2),
(115, 2, 3),
(121, 2, 4),
(114, 2, 6),
(110, 2, 8),
(120, 2, 9),
(106, 2, 10),
(105, 2, 11),
(119, 2, 13),
(113, 2, 14),
(112, 2, 15),
(117, 2, 16),
(118, 2, 17),
(111, 2, 18),
(108, 2, 19),
(116, 2, 20),
(22, 3, 6),
(24, 3, 9),
(10, 4, 2),
(27, 5, 8),
(29, 6, 3),
(28, 6, 6);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_uraian`
--
ALTER TABLE `detail_uraian`
 ADD KEY `id_uraian` (`id_uraian`);

--
-- Indexes for table `jurnal`
--
ALTER TABLE `jurnal`
 ADD PRIMARY KEY (`id`), ADD KEY `id_sub_sub_rekening` (`id_rekening`), ADD KEY `waktu` (`tanggal`), ADD KEY `id_uraian` (`id_uraian`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
 ADD PRIMARY KEY (`id`), ADD KEY `id_program` (`id_program`);

--
-- Indexes for table `module`
--
ALTER TABLE `module`
 ADD PRIMARY KEY (`id`), ADD KEY `nama` (`nama`);

--
-- Indexes for table `pagu_anggaran`
--
ALTER TABLE `pagu_anggaran`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id_satker_2` (`id_satker`,`tahun`), ADD KEY `id_satker` (`id_satker`), ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `penerimaan`
--
ALTER TABLE `penerimaan`
 ADD PRIMARY KEY (`id`), ADD KEY `id_uraian` (`id_uraian`), ADD KEY `id_rekening` (`id_rekening`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
 ADD PRIMARY KEY (`id`), ADD KEY `id_uraian` (`id_uraian`), ADD KEY `id_rekening` (`id_rekening`);

--
-- Indexes for table `privileges`
--
ALTER TABLE `privileges`
 ADD PRIMARY KEY (`id`), ADD KEY `modul_id` (`module_id`), ADD KEY `form_nama` (`form_nama`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
 ADD PRIMARY KEY (`id`), ADD KEY `id_satker` (`id_satker`);

--
-- Indexes for table `rekening`
--
ALTER TABLE `rekening`
 ADD PRIMARY KEY (`id`), ADD KEY `nama` (`nama`);

--
-- Indexes for table `rencana_kebutuhan`
--
ALTER TABLE `rencana_kebutuhan`
 ADD PRIMARY KEY (`id_renbut`), ADD KEY `id_uraian` (`id_uraian`), ADD KEY `verificator` (`verificator`), ADD KEY `id_akun_rekening` (`id_akun_rekening`);

--
-- Indexes for table `satker`
--
ALTER TABLE `satker`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `nama` (`nama`);

--
-- Indexes for table `sub_kegiatan`
--
ALTER TABLE `sub_kegiatan`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id_kegiatan` (`id_kegiatan`,`kode`);

--
-- Indexes for table `sub_rekening`
--
ALTER TABLE `sub_rekening`
 ADD PRIMARY KEY (`id`), ADD KEY `id_rekening` (`id_rekening`), ADD KEY `nama` (`nama`);

--
-- Indexes for table `sub_sub_rekening`
--
ALTER TABLE `sub_sub_rekening`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id_sub_rekening_2` (`id_sub_rekening`,`nama`), ADD KEY `id_sub_rekening` (`id_sub_rekening`), ADD KEY `nama` (`nama`);

--
-- Indexes for table `sub_sub_sub_rekening`
--
ALTER TABLE `sub_sub_sub_rekening`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id_sub_sub_rekening_2` (`id_sub_sub_rekening`,`nama`), ADD KEY `id_sub_sub_rekening` (`id_sub_sub_rekening`);

--
-- Indexes for table `sub_sub_sub_sub_rekening`
--
ALTER TABLE `sub_sub_sub_sub_rekening`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `id_sub_sub_sub_rekening_2` (`id_sub_sub_sub_rekening`,`nama`), ADD KEY `id_sub_sub_sub_rekening` (`id_sub_sub_sub_rekening`);

--
-- Indexes for table `sub_sub_uraian`
--
ALTER TABLE `sub_sub_uraian`
 ADD PRIMARY KEY (`id`), ADD KEY `id_uraian` (`id_sub_uraian`);

--
-- Indexes for table `sub_uraian`
--
ALTER TABLE `sub_uraian`
 ADD PRIMARY KEY (`id`), ADD KEY `id_uraian` (`id_uraian`);

--
-- Indexes for table `uraian`
--
ALTER TABLE `uraian`
 ADD PRIMARY KEY (`id`), ADD KEY `id_sub_kegiatan` (`id_sub_kegiatan`,`kode`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`), ADD KEY `username_2` (`username`), ADD KEY `id_user_group` (`id_user_group`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_group_privileges`
--
ALTER TABLE `user_group_privileges`
 ADD PRIMARY KEY (`id`), ADD KEY `user_group_id` (`user_group_id`,`privileges_id`), ADD KEY `privileges_id` (`privileges_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jurnal`
--
ALTER TABLE `jurnal`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `module`
--
ALTER TABLE `module`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `pagu_anggaran`
--
ALTER TABLE `pagu_anggaran`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `penerimaan`
--
ALTER TABLE `penerimaan`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `privileges`
--
ALTER TABLE `privileges`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `rencana_kebutuhan`
--
ALTER TABLE `rencana_kebutuhan`
MODIFY `id_renbut` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `satker`
--
ALTER TABLE `satker`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `sub_kegiatan`
--
ALTER TABLE `sub_kegiatan`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `sub_sub_sub_rekening`
--
ALTER TABLE `sub_sub_sub_rekening`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=321101;
--
-- AUTO_INCREMENT for table `sub_sub_uraian`
--
ALTER TABLE `sub_sub_uraian`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `sub_uraian`
--
ALTER TABLE `sub_uraian`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `uraian`
--
ALTER TABLE `uraian`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `user_group_privileges`
--
ALTER TABLE `user_group_privileges`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=122;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `jurnal`
--
ALTER TABLE `jurnal`
ADD CONSTRAINT `jurnal_ibfk_1` FOREIGN KEY (`id_rekening`) REFERENCES `sub_sub_sub_sub_rekening` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `jurnal_ibfk_2` FOREIGN KEY (`id_uraian`) REFERENCES `uraian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kegiatan`
--
ALTER TABLE `kegiatan`
ADD CONSTRAINT `kegiatan_ibfk_1` FOREIGN KEY (`id_program`) REFERENCES `program` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pagu_anggaran`
--
ALTER TABLE `pagu_anggaran`
ADD CONSTRAINT `pagu_anggaran_ibfk_1` FOREIGN KEY (`id_satker`) REFERENCES `satker` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `penerimaan`
--
ALTER TABLE `penerimaan`
ADD CONSTRAINT `penerimaan_ibfk_2` FOREIGN KEY (`id_uraian`) REFERENCES `uraian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `penerimaan_ibfk_3` FOREIGN KEY (`id_rekening`) REFERENCES `sub_sub_sub_sub_rekening` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
ADD CONSTRAINT `pengeluaran_ibfk_2` FOREIGN KEY (`id_uraian`) REFERENCES `uraian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pengeluaran_ibfk_3` FOREIGN KEY (`id_rekening`) REFERENCES `sub_sub_sub_sub_rekening` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `privileges`
--
ALTER TABLE `privileges`
ADD CONSTRAINT `privileges_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `program`
--
ALTER TABLE `program`
ADD CONSTRAINT `program_ibfk_1` FOREIGN KEY (`id_satker`) REFERENCES `satker` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rencana_kebutuhan`
--
ALTER TABLE `rencana_kebutuhan`
ADD CONSTRAINT `rencana_kebutuhan_ibfk_1` FOREIGN KEY (`id_uraian`) REFERENCES `uraian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `rencana_kebutuhan_ibfk_2` FOREIGN KEY (`id_akun_rekening`) REFERENCES `sub_sub_sub_sub_rekening` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `rencana_kebutuhan_ibfk_3` FOREIGN KEY (`verificator`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sub_kegiatan`
--
ALTER TABLE `sub_kegiatan`
ADD CONSTRAINT `sub_kegiatan_ibfk_1` FOREIGN KEY (`id_kegiatan`) REFERENCES `kegiatan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_rekening`
--
ALTER TABLE `sub_rekening`
ADD CONSTRAINT `sub_rekening_ibfk_1` FOREIGN KEY (`id_rekening`) REFERENCES `rekening` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_sub_rekening`
--
ALTER TABLE `sub_sub_rekening`
ADD CONSTRAINT `sub_sub_rekening_ibfk_1` FOREIGN KEY (`id_sub_rekening`) REFERENCES `sub_rekening` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_sub_sub_rekening`
--
ALTER TABLE `sub_sub_sub_rekening`
ADD CONSTRAINT `sub_sub_sub_rekening_ibfk_1` FOREIGN KEY (`id_sub_sub_rekening`) REFERENCES `sub_sub_rekening` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_sub_sub_sub_rekening`
--
ALTER TABLE `sub_sub_sub_sub_rekening`
ADD CONSTRAINT `sub_sub_sub_sub_rekening_ibfk_1` FOREIGN KEY (`id_sub_sub_sub_rekening`) REFERENCES `sub_sub_sub_rekening` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_sub_uraian`
--
ALTER TABLE `sub_sub_uraian`
ADD CONSTRAINT `sub_sub_uraian_ibfk_1` FOREIGN KEY (`id_sub_uraian`) REFERENCES `sub_uraian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_uraian`
--
ALTER TABLE `sub_uraian`
ADD CONSTRAINT `sub_uraian_ibfk_1` FOREIGN KEY (`id_uraian`) REFERENCES `uraian` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `uraian`
--
ALTER TABLE `uraian`
ADD CONSTRAINT `uraian_ibfk_1` FOREIGN KEY (`id_sub_kegiatan`) REFERENCES `sub_kegiatan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_user_group`) REFERENCES `user_group` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_group_privileges`
--
ALTER TABLE `user_group_privileges`
ADD CONSTRAINT `user_group_privileges_ibfk_1` FOREIGN KEY (`user_group_id`) REFERENCES `user_group` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `user_group_privileges_ibfk_2` FOREIGN KEY (`privileges_id`) REFERENCES `privileges` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
