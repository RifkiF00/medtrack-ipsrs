<?php
// Nyalakan session untuk keperluan login nanti
if( !session_id() ) {
    session_start();
}

// Panggil file inisiasi dari folder app
require_once '../app/init.php';

// Nyalakan mesin Router
$app = new App();