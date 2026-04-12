-- ============================================================================
-- SISTEM TRACKING, PENGELOLAAN ASET, DAN MAINTENANCE PERALATAN MEDIS
-- Unit IPSRS & Logistik Medis - Rumah Sakit
-- ============================================================================

-- Buat database
CREATE DATABASE IF NOT EXISTS medtrack_ipsrs_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE medtrack_ipsrs_db;

-- ============================================================================
-- 1. MASTER DATA TABLES
-- ============================================================================

-- Tabel Master Ruangan (IGD, ICCU, OK, dll)
CREATE TABLE m_ruangan (
  id_ruang INT PRIMARY KEY AUTO_INCREMENT,
  nama_ruang VARCHAR(100) NOT NULL UNIQUE,
  kategori ENUM('IGD', 'ICCU', 'OK', 'Rawat_Inap', 'Laboratorium', 'Apotek', 'Gudang_Logistik') NOT NULL,
  lokasi_gedung VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_kategori (kategori)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Master User (Admin, Staf Logistik, Staf Unit)
CREATE TABLE m_user (
  id_user INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(100) NOT NULL,
  role ENUM('Admin_IPSRS', 'Staf_Logistik', 'Staf_Unit', 'Kepala_IPSRS') NOT NULL,
  id_ruang INT,
  nip VARCHAR(20),
  no_hp VARCHAR(15),
  status ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif',
  last_login DATETIME,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_ruang) REFERENCES m_ruangan(id_ruang) ON DELETE SET NULL,
  INDEX idx_role (role),
  INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Master Aset (Peralatan Medis)
CREATE TABLE m_aset (
  id_aset INT PRIMARY KEY AUTO_INCREMENT,
  kode_label VARCHAR(50) NOT NULL UNIQUE,
  nama_alat VARCHAR(150) NOT NULL,
  merk VARCHAR(80),
  model VARCHAR(80),
  serial_number VARCHAR(100),
  no_sertifikat VARCHAR(50),
  tgl_pengadaan DATE,
  tgl_kadaluarsa_sertif DATE,
  harga_perolehan DECIMAL(15, 2),
  status_kondisi ENUM('Baik', 'Rusak_Ringan', 'Rusak_Berat', 'Maintenance', 'Pensiun') DEFAULT 'Baik',
  id_ruang_saat_ini INT,
  lokasi_fisik VARCHAR(255),
  keterangan TEXT,
  file_qr_code VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (id_ruang_saat_ini) REFERENCES m_ruangan(id_ruang) ON DELETE SET NULL,
  INDEX idx_kode_label (kode_label),
  INDEX idx_status (status_kondisi),
  INDEX idx_ruang (id_ruang_saat_ini)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Master Vendor/Supplier
CREATE TABLE m_vendor (
  id_vendor INT PRIMARY KEY AUTO_INCREMENT,
  nama_vendor VARCHAR(120) NOT NULL,
  kontak_person VARCHAR(100),
  no_telp VARCHAR(15),
  email VARCHAR(100),
  alamat TEXT,
  kota VARCHAR(50),
  provinsi VARCHAR(50),
  status ENUM('Aktif', 'Nonaktif') DEFAULT 'Aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- 2. TRANSACTIONAL TABLES
-- ============================================================================

-- Tabel Tracking GPS (Log setiap scan QR + lokasi)
CREATE TABLE t_tracking (
  id_track INT PRIMARY KEY AUTO_INCREMENT,
  id_aset INT NOT NULL,
  id_user INT,
  id_ruang INT,
  latitude DECIMAL(10, 8),
  longitude DECIMAL(11, 8),
  akurasi_gps INT COMMENT 'meter',
  tgl_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  keterangan VARCHAR(255),
  FOREIGN KEY (id_aset) REFERENCES m_aset(id_aset) ON DELETE CASCADE,
  FOREIGN KEY (id_user) REFERENCES m_user(id_user) ON DELETE SET NULL,
  FOREIGN KEY (id_ruang) REFERENCES m_ruangan(id_ruang) ON DELETE SET NULL,
  INDEX idx_aset (id_aset),
  INDEX idx_tgl (tgl_update),
  INDEX idx_lokasi (latitude, longitude)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Lanjutan Tabel Sirkulasi/Peminjaman Aset
CREATE TABLE t_sirkulasi (
  id_pinjam INT PRIMARY KEY AUTO_INCREMENT,
  id_aset INT NOT NULL,
  id_user_peminjam INT NOT NULL,
  ruang_asal INT NOT NULL,
  ruang_tujuan INT NOT NULL,
  tgl_pinjam DATETIME DEFAULT CURRENT_TIMESTAMP,
  tgl_kembali_rencana DATE,
  tgl_kembali_aktual DATETIME,
  status_pinjam ENUM('Booking', 'Dipinjam', 'Kembali', 'Terlambat') DEFAULT 'Dipinjam',
  keperluan VARCHAR(255),
  kondisi_awal VARCHAR(100) DEFAULT 'Baik',
  kondisi_akhir VARCHAR(100),
  catatan TEXT,
  FOREIGN KEY (id_aset) REFERENCES m_aset(id_aset) ON DELETE CASCADE,
  FOREIGN KEY (id_user_peminjam) REFERENCES m_user(id_user),
  FOREIGN KEY (ruang_asal) REFERENCES m_ruangan(id_ruang),
  FOREIGN KEY (ruang_tujuan) REFERENCES m_ruangan(id_ruang),
  INDEX idx_status_pinjam (status_pinjam)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Maintenance & Kalibrasi (Penting untuk IPSRS)
CREATE TABLE t_maintenance (
  id_main INT PRIMARY KEY AUTO_INCREMENT,
  id_aset INT NOT NULL,
  id_user_teknisi INT NOT NULL,
  id_vendor INT, -- Jika diservis pihak luar
  jenis_tindakan ENUM('Pemeliharaan_Rutin', 'Perbaikan', 'Kalibrasi', 'Uji_Fungsi') NOT NULL,
  tgl_mulai DATETIME DEFAULT CURRENT_TIMESTAMP,
  tgl_selesai DATETIME,
  deskripsi_kendala TEXT,
  tindakan_diambil TEXT,
  biaya DECIMAL(15, 2) DEFAULT 0.00,
  status_perbaikan ENUM('Proses', 'Selesai', 'Pending_Part', 'Gagal') DEFAULT 'Proses',
  file_laporan VARCHAR(255) COMMENT 'Link ke PDF hasil kalibrasi/servis',
  FOREIGN KEY (id_aset) REFERENCES m_aset(id_aset) ON DELETE CASCADE,
  FOREIGN KEY (id_user_teknisi) REFERENCES m_user(id_user),
  FOREIGN KEY (id_vendor) REFERENCES m_vendor(id_vendor)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel Laporan Kerusakan (Troubleshoot Request dari Unit)
CREATE TABLE t_troubleshoot (
  id_ticket INT PRIMARY KEY AUTO_INCREMENT,
  id_aset INT NOT NULL,
  id_user_pelapor INT NOT NULL,
  tgl_lapor TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  tingkat_urgensi ENUM('Rendah', 'Sedang', 'Tinggi', 'Darurat') DEFAULT 'Sedang',
  deskripsi_kerusakan TEXT NOT NULL,
  foto_kerusakan VARCHAR(255),
  status_ticket ENUM('Open', 'Pengecekan', 'Dikerjakan', 'Closed') DEFAULT 'Open',
  FOREIGN KEY (id_aset) REFERENCES m_aset(id_aset),
  FOREIGN KEY (id_user_pelapor) REFERENCES m_user(id_user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER //
CREATE TRIGGER tr_update_status_aset_pinjam
AFTER INSERT ON t_sirkulasi
FOR EACH ROW
BEGIN
    UPDATE m_aset SET status_kondisi = 'Maintenance' 
    WHERE id_aset = NEW.id_aset AND NEW.status_pinjam = 'Dipinjam';
END; //
DELIMITER ;