# 🔧 SETUP GUIDE - MEDTRACK-IPSRS

Panduan lengkap setup project dari awal sampai bisa dijalankan.

---

## 📋 Prerequisites

Pastikan sudah install:

- [ ] **XAMPP** (Apache + MySQL + PHP)
  - Download: https://www.apachefriends.org/
  - Minimum PHP 7.4
  - MySQL 5.7+

- [ ] **Git** (untuk version control)
  - Download: https://git-scm.com/

- [ ] **VS Code** (code editor)
  - Download: https://code.visualstudio.com/
  - Extension: PHP Intelephense, MySQL

- [ ] **Browser** (Chrome/Firefox untuk testing)

---

## ⚙️ Step 1: Start XAMPP

### Windows
1. Buka XAMPP Control Panel
2. Klik **Start** untuk Apache & MySQL
3. Pastikan keduanya berwarna green ✅

### Mac
```bash
sudo /Applications/XAMPP/xamppfiles/bin/mysqld_safe
sudo /Applications/XAMPP/xamppfiles/bin/apachectl start
```

### Linux
```bash
sudo service apache2 start
sudo service mysql start
```

---

## 📁 Step 2: Create Project Folder

### Windows (XAMPP)
```bash
cd C:\xampp\htdocs
git clone https://github.com/yourusername/medtrack-ipsrs.git
cd medtrack-ipsrs
```

### Mac (XAMPP)
```bash
cd /Applications/XAMPP/xamppfiles/htdocs
git clone https://github.com/yourusername/medtrack-ipsrs.git
cd medtrack-ipsrs
```

### Linux (LAMP)
```bash
cd /var/www/html
git clone https://github.com/yourusername/medtrack-ipsrs.git
cd medtrack-ipsrs
```

---

## 📂 Step 3: Create Folder Structure

### Option A: Automatic (Linux/Mac)
```bash
bash QUICK_START.sh
```

### Option B: Manual (Windows/All OS)

Di VS Code:
1. Klik Explorer (di kiri)
2. Expand folder medtrack-ipsrs
3. Buat folder baru:

```
medtrack-ipsrs/
├── config/
├── app/
│   ├── controllers/
│   ├── models/
│   └── views/
│       ├── admin/
│       ├── auth/
│       ├── errors/
│       ├── layouts/
│       ├── maintenance/
│       ├── sirkulasi/
│       └── tracking/
├── public/
│   ├── css/
│   ├── js/
│   ├── libs/
│   └── uploads/
│       ├── documents/
│       └── qr/
├── database/
│   ├── backups/
│   └── migrations/
└── tests/
```

---

## 🗄️ Step 4: Setup Database

### Open phpMyAdmin

Akses browser:
```
http://localhost/phpmyadmin
```

Login:
- Username: `root`
- Password: (kosong, tekan enter)

### Create Database

1. Klik tab **Databases**
2. Input name: `medtrack_ipsrs`
3. Charset: `utf8mb4_unicode_ci`
4. Klik **Create**

### Import SQL Schema

1. Klik database `medtrack_ipsrs`
2. Klik tab **Import**
3. Choose file: `database/schema.sql`
4. Klik **Import**
5. Klik tab **Import** lagi
6. Choose file: `database/dummy-data.sql`
7. Klik **Import**

✅ Database siap dengan 7 tabel + dummy data

---

## 📝 Step 5: Configure Database Connection

Buka file: `config/database.php`

```php
<?php
class Database {
    private $host = 'localhost';      // ← Jangan diubah (local)
    private $db = 'medtrack_ipsrs';   // ← Sesuai nama DB
    private $user = 'root';            // ← Default XAMPP
    private $pass = '';                // ← Kosong di local
    
    public function connect() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db;
        $pdo = new PDO($dsn, $this->user, $this->pass);
        return $pdo;
    }
}
?>
```

**Jika pakai password MySQL:**
```php
private $pass = 'your_mysql_password';
```

---

## 📄 Step 6: Create Core Files

### config/constants.php
```php
<?php
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', BASE_PATH . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('BASE_URL', 'http://localhost/medtrack-ipsrs');
define('APP_NAME', 'MEDTRACK-IPSRS');
define('TIMEZONE', 'Asia/Jakarta');

date_default_timezone_set(TIMEZONE);
?>
```

### config/security.php
```php
<?php
// CSRF Token
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token ?? '');
}

// Password Hash
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

// Sanitize Input
function sanitizeInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
?>
```

### public/index.php
```php
<?php
session_start();

// Include configs
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/config/constants.php';
require_once dirname(__DIR__) . '/config/security.php';

// Autoload classes
spl_autoload_register(function($class) {
    $paths = [
        APP_PATH . '/models/',
        APP_PATH . '/controllers/'
    ];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

// Initialize database
$database = new Database();
$db = $database->connect();

// Simple routing
$path = $_GET['path'] ?? 'auth';
$parts = explode('/', trim($path, '/'));

$controllerName = ucfirst($parts[0]) . 'Controller';
$action = $parts[1] ?? 'index';
$param = $parts[2] ?? null;

// Check if authenticated (except login)
if ($path !== 'auth' && !isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/public?path=auth');
    exit;
}

// Load & execute controller
$controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';
if (file_exists($controllerFile)) {
    require $controllerFile;
    
    if (class_exists($controllerName)) {
        $controller = new $controllerName($db);
        
        if (method_exists($controller, $action)) {
            if ($param) {
                $controller->$action($param);
            } else {
                $controller->$action();
            }
        } else {
            http_response_code(404);
            echo "Action not found: $action";
        }
    }
} else {
    http_response_code(404);
    echo "Controller not found: $controllerName";
}
?>
```

### public/.htaccess
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /medtrack-ipsrs/
    
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ public/index.php?path=$1 [QSA,L]
</IfModule>
```

---

## 👥 Step 7: Create Login Controller

### app/controllers/AuthController.php

```php
<?php
class AuthController {
    private $db;
    
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function index() {
        // Show login page
        require APP_PATH . '/views/auth/login.php';
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = sanitizeInput($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Query user
            $stmt = $this->db->prepare("
                SELECT id_user, username, password_hash, nama_lengkap, role, id_ruang
                FROM m_user
                WHERE username = ?
            ");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && verifyPassword($password, $user['password_hash'])) {
                // Set session
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['id_ruang'] = $user['id_ruang'];
                
                // Update last login
                $upd = $this->db->prepare("UPDATE m_user SET last_login = NOW() WHERE id_user = ?");
                $upd->execute([$user['id_user']]);
                
                // Redirect
                header('Location: ' . BASE_URL . '/public?path=dashboard');
                exit;
            } else {
                $_SESSION['error'] = 'Username atau password salah!';
            }
        }
        
        require APP_PATH . '/views/auth/login.php';
    }
    
    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . '/public?path=auth');
        exit;
    }
}
?>
```

---

## 🎨 Step 8: Create Login View

### app/views/auth/login.php

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MEDTRACK IPSRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            padding: 40px;
        }
        h2 {
            color: #667eea;
            margin-bottom: 30px;
            text-align: center;
        }
        .btn-login {
            background: #667eea;
            border: none;
            width: 100%;
            padding: 10px;
            font-weight: bold;
        }
        .btn-login:hover {
            background: #764ba2;
        }
        .alert {
            margin-bottom: 20px;
        }
        .demo-note {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>🏥 MEDTRACK IPSRS</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <form method="POST" action="<?= BASE_URL ?>/public?path=auth&action=login">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required autofocus>
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-login btn-primary">Login</button>
        </form>
        
        <div class="demo-note">
            <strong>Demo Credentials:</strong>
            <hr>
            <strong>Admin:</strong> admin1 / password123<br>
            <strong>Logistik:</strong> logistik1 / password123<br>
            <strong>Unit:</strong> unit1 / password123
        </div>
    </div>
</body>
</html>
```

---

## ✅ Step 9: Test Everything

### 1. Akses Login Page
```
http://localhost/medtrack-ipsrs/public
```

### 2. Login dengan:
- Username: `admin1`
- Password: `password123`

### 3. Check Database Connection
- Jika berhasil login → Database OK ✅
- Jika error → Check `config/database.php`

### 4. Check Console
- F12 → Console
- Pastikan tidak ada error merah

---

## 🐛 Troubleshooting

### Error: "No input file specified"
```
Solusi: Pastikan .htaccess sudah benar
Check: RewriteBase /medtrack-ipsrs/
```

### Error: "SQLSTATE[HY000]: General error"
```
Solusi: Database connection error
1. Pastikan MySQL running
2. Check config/database.php
3. Database medtrack_ipsrs sudah dibuat
```

### Error: "Class not found"
```
Solusi: Autoload error
1. Check spelling class name
2. Pastikan file ada di folder yg benar
3. Check file extension .php
```

### Geolocation Blocked
```
Solusi: Browser permission
1. Check browser console (F12)
2. Allow location access
3. Refresh halaman
```

---

## 📝 Next Steps

Setelah berhasil login:

1. **Day 1-2:** Lanjut membuat models (User, Aset, Tracking)
2. **Day 3-4:** Buat CRUD aset controller & view
3. **Day 5:** QR code generation
4. **Day 6:** GPS tracking & map
5. **Day 6 (sore):** Testing & polish
6. **Day 7:** Demo & dokumentasi

---

## 💡 Tips & Tricks

### Use VS Code Extensions
```
- PHP Intelephense (autocomplete)
- MySQL (database viewer)
- Thunder Client (API testing)
```

### Quick Commands
```bash
# Test PHP syntax
php -l config/database.php

# Check folder permissions
ls -la public/uploads/

# Fix permissions (Linux)
chmod 755 public/uploads/
```

### Keep It Simple
```
✓ Do: Focus on MVP features
✓ Do: Commit daily to git
✗ Don't: Add unnecessary features
✗ Don't: Skip input validation
```

---

## 📞 Need Help?

1. Check README.md
2. Check console errors (F12)
3. Check phpMyAdmin untuk data
4. Ask mentor/dosen

---

**Happy Coding! 🚀**
