<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fungsi bantuan untuk memanggil file secara otomatis tanpa takut salah nama/path
function load_file($path) {
    if (file_exists($path)) {
        require_once $path;
    } else {
        die("<b>MedTrack Error:</b> File tidak ditemukan di: <br> <code>$path</code>");
    }
}

// Tentukan folder dasar app
$base = __DIR__;

// 1. Panggil Config
load_file($base . '/../config/constants.php');
load_file($base . '/../config/database.php');
load_file($base . '/../config/security.php');

// 2. Panggil Core Classes
load_file($base . '/core/App.php');
load_file($base . '/core/controller.php');