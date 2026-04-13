<?php
// File: app/controllers/Home.php

class Home extends Controller {
    public function index() {
        echo "<div style='font-family: sans-serif; text-align: center; margin-top: 50px;'>";
        echo "<h1>🚀 Selamat! Mesin MVC IPSRS.core Berhasil Menyala!</h1>";
        echo "<p>Coba ketik URL sembarangan di address bar, aplikasinya tidak akan error.</p>";
        echo "</div>";
    }
}