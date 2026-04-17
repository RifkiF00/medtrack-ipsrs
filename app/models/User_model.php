<?php
// File: app/models/User_model.php

class User_model {
    private $db;

    public function __construct() {
        // Panggil class Database yang sudah Anda buat sebelumnya
        $database = new Database();
        $this->db = $database->connect();
    }

    // Fungsi untuk mencari user berdasarkan username yang diinput
    public function getUserByUsername($username) {
        // Kita juga pastikan hanya akun yang 'Aktif' yang bisa dicari
        $query = "SELECT * FROM m_user WHERE username = :username AND status = 'Aktif'";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['username' => $username]);
        
        return $stmt->fetch(); // Mengembalikan 1 baris data user jika ketemu
    }
}