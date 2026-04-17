<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../app/init.php';

if (function_exists('setSecurityHeaders')) {
    setSecurityHeaders();
}

$app = new App();