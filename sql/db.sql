
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `user_password` varchar(40) NOT NULL,
  `password_salt` varchar(8) NOT NULL,
  `user_email` varchar(127) NOT NULL DEFAULT '',
  `user_fullname` varchar(255) NOT NULL DEFAULT '',
  `user_role` enum('administrator','user') NOT NULL DEFAULT 'user',
  `register_datetime` datetime DEFAULT NULL,
  `register_ip` bigint(20) NOT NULL DEFAULT 0,
  `last_login_datetime` datetime DEFAULT NULL,
  `last_login_ip` bigint(20) NOT NULL DEFAULT 0,
  `last_update_datetime` datetime DEFAULT NULL,
  `last_update_ip` bigint(20) NOT NULL DEFAULT 0,
  `admin_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `username` (`username`),
  KEY `user_role` (`user_role`),
  KEY `admin_id` (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `user_password`, `password_salt`, `user_email`, `user_fullname`, `user_role`) VALUES
('admin', sha('34231122admin123'), '34231122', 'admin@here.org', 'Administrator', 'administrator');

DROP TABLE IF EXISTS `barang`;
CREATE TABLE IF NOT EXISTS `barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `kode_barang` varchar(20) NOT NULL DEFAULT '',
  `nama_barang` varchar(50) NOT NULL DEFAULT '',
  `spesifikasi_barang` TEXT,
  `satuan_barang` varchar(20) NOT NULL DEFAULT '',
  `tanggal_input` datetime NOT NULL,
  `tanggal_update` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `transaksi`;
CREATE TABLE IF NOT EXISTS `pembelian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `barang_id` int(11) NOT NULL DEFAULT 0,
  `jenis_transaksi` ENUM('barang_masuk','barang_keluar') NOT NULL,
  `tanggal_transaksi` datetime NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 0,
  `harga_satuan` decimal(15,2) NOT NULL DEFAULT 0.0,
  `diskon` decimal(15,2) NOT NULL DEFAULT 0.0,
  `tanggal_input` datetime NOT NULL,
  `tanggal_update` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY (`user_id`),
  KEY (`barang_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
