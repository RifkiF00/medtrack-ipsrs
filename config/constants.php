<?php
/**
 * constants.php
 * Global Application Constants
 */

// Path Constants
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('CONFIG_PATH', BASE_PATH . '/config');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// URL Constants
define('BASE_URL', 'http://localhost/medtrack-ipsrs');
define('PUBLIC_URL', BASE_URL . '/public');

// App Constants
define('APP_NAME', 'MEDTRACK-IPSRS');
define('APP_VERSION', '1.0.0');
define('TIMEZONE', 'Asia/Jakarta');

// Database Constants
define('DB_HOST', 'localhost');
define('DB_NAME', 'medtrack_ipsrs');
define('DB_USER', 'root');
define('DB_PASS', '');

// Security Constants
define('PASSWORD_MIN_LENGTH', 6);
define('SESSION_TIMEOUT', 1800); // 30 minutes
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB

// Status Constants
define('STATUS_ASET_BAIK', 'Baik');
define('STATUS_ASET_RUSAK_RINGAN', 'Rusak_Ringan');
define('STATUS_ASET_RUSAK_BERAT', 'Rusak_Berat');
define('STATUS_ASET_MAINTENANCE', 'Maintenance');
define('STATUS_ASET_PENSIUN', 'Pensiun');

// Role Constants
define('ROLE_ADMIN', 'Admin_IPSRS');
define('ROLE_LOGISTIK', 'Staf_Logistik');
define('ROLE_UNIT', 'Staf_Unit');

// Set Timezone
date_default_timezone_set(TIMEZONE);
?>
