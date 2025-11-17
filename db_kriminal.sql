
DROP TABLE IF EXISTS `data_kriminal`;


CREATE TABLE `data_kriminal` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `lokasi` VARCHAR(255) NOT NULL,
  `latitude` DECIMAL(10, 8) NOT NULL,
  `longitude` DECIMAL(11, 8) NOT NULL,
  `jumlah_kejadian` INT NOT NULL,
  `jenis_kejahatan_dominan` VARCHAR(255),
  `rata_rata_kerugian_juta` DECIMAL(10, 2),
  `jumlah_penduduk` INT,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `data_kriminal` 
(`lokasi`, `latitude`, `longitude`, `jumlah_kejadian`, `jenis_kejahatan_dominan`, `rata_rata_kerugian_juta`, `jumlah_penduduk`) 
VALUES
('Abeli', -4.01514600, 122.62000000, 24, 'Pencurian', 2.5, 17353),
('Baruga', -4.00889000, 122.50201000, 46, 'Pencurian', 1.8, 34241),
('Kadia', -3.99370000, 122.52800000, 22, 'Perampokan', 5.9, 36956),
('Kambu', -4.02010000, 122.48620000, 32, 'Penganiayaan', 4.3, 24898),
('Kendari', -3.97150000, 122.53560000, 10, 'Narkoba', 1.5, 28814),
('Kendari Barat', -3.97340000, 122.49880000, 8, 'Pembunuhan', 1.2, 42530),
('Mandonga', -3.98770000, 122.50850000, 134, 'Pencurian', 7.1, 37582),
('Poasia', -4.03210000, 122.57140000, 23, 'Perampokan', 2.8, 41769),
('Puuwatu', -4.00190000, 122.45930000, 54, 'Pencurian, Penganiayaan', 4.3, 40853),
('Wua-Wua', -4.00110000, 122.50970000, 60, 'Pembunuhan, Penganiayaan', 3.1, 33996);