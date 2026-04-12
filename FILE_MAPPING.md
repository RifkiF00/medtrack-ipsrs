# 📋 FILE MAPPING - COPY PASTE GUIDE

Berikut adalah panduan copy-paste file dari output ke folder project kamu.

---

## 🗂️ FOLDER ROOT (medtrack-ipsrs/)

| File Output | Tujuan | Instruksi |
|------------|--------|-----------|
| `.gitignore` | `/medtrack-ipsrs/.gitignore` | Copy & paste langsung ke root |
| `README.md` | `/medtrack-ipsrs/README.md` | Copy & paste langsung ke root |
| `SETUP.md` | `/medtrack-ipsrs/SETUP.md` | Copy & paste langsung ke root |

---

## ⚙️ CONFIG FOLDER (config/)

| File Output | Tujuan | Instruksi |
|------------|--------|-----------|
| `config_database.php` | `/medtrack-ipsrs/config/database.php` | Copy, rename extension dari .php |
| `config_constants.php` | `/medtrack-ipsrs/config/constants.php` | Copy, rename extension |
| `config_security.php` | `/medtrack-ipsrs/config/security.php` | Copy, rename extension |

**Cara Copy di Windows:**
```
1. Klik kanan file di Explorer
2. Copy
3. Buka folder medtrack-ipsrs/config/
4. Klik kanan → Paste
5. Rename file (hapus prefix "config_")
```

**Cara Copy di Mac/Linux:**
```bash
cp config_database.php medtrack-ipsrs/config/database.php
cp config_constants.php medtrack-ipsrs/config/constants.php
cp config_security.php medtrack-ipsrs/config/security.php
```

---

## 📁 FOLDER STRUCTURE YANG SUDAH DIBUAT

Gunakan informasi ini jika belum membuat folder:

```
medtrack-ipsrs/
├── 📂 config/
│   ├── database.php          ← dari config_database.php
│   ├── constants.php         ← dari config_constants.php
│   └── security.php          ← dari config_security.php
│
├── 📂 app/
│   ├── 📂 controllers/       ← Kosong dulu
│   ├── 📂 models/            ← Kosong dulu
│   └── 📂 views/
│       ├── 📂 admin/
│       ├── 📂 auth/
│       ├── 📂 errors/
│       ├── 📂 layouts/
│       ├── 📂 maintenance/
│       ├── 📂 sirkulasi/
│       └── 📂 tracking/
│
├── 📂 public/
│   ├── index.php             ← Belum dibuat (akan buatkan hari 1)
│   ├── .htaccess             ← Belum dibuat
│   ├── 📂 css/
│   ├── 📂 js/
│   ├── 📂 libs/
│   └── 📂 uploads/
│       ├── 📂 qr/
│       └── 📂 documents/
│
├── 📂 database/
│   ├── schema.sql            ← Belum dibuat (di database folder output)
│   ├── dummy-data.sql        ← Belum dibuat
│   ├── 📂 migrations/
│   └── 📂 backups/
│
├── .gitignore                ← dari outputs
├── README.md                 ← dari outputs
└── SETUP.md                  ← dari outputs
```

---

## 📄 DOKUMENTASI FILES YANG SUDAH DISIAPKAN

Di folder outputs (`/mnt/user-data/outputs/`), kamu sudah punya:

✅ **Dokumentasi & Plan:**
- `IPSRS_Project_Plan.html` - Project plan dengan Rich Picture & Use Case
- `FOLDER_STRUCTURE.md` - Penjelasan detail setiap folder & file
- `QUICK_START.sh` - Bash script auto-create folder (untuk Linux/Mac)

✅ **Root Files:**
- `.gitignore` - Git ignore config
- `README.md` - Project documentation
- `SETUP.md` - Installation guide step-by-step

✅ **Config Files:**
- `config_database.php` - Database connection
- `config_constants.php` - Global constants
- `config_security.php` - Security functions

✅ **Database Files** (akan dibuat nanti):
- `01_ipsrs_database.sql` - Database schema (file ini sudah di `/outputs`)

---

## 🚀 LANGKAH QUICK START (Hari 1)

### Step 1: Copy Root Files
```bash
# Copy ke root project
cp .gitignore medtrack-ipsrs/
cp README.md medtrack-ipsrs/
cp SETUP.md medtrack-ipsrs/
```

### Step 2: Copy Config Files
```bash
# Copy ke config folder
cp config_database.php medtrack-ipsrs/config/database.php
cp config_constants.php medtrack-ipsrs/config/constants.php
cp config_security.php medtrack-ipsrs/config/security.php
```

### Step 3: Create Folder Structure
```bash
# Automatic (Linux/Mac)
bash QUICK_START.sh

# Manual (Windows) - buat folder via VS Code Explorer
```

### Step 4: Setup Database
```bash
# Import SQL schema ke MySQL
mysql -u root medtrack_ipsrs < 01_ipsrs_database.sql
```

### Step 5: Test Connection
```
http://localhost/medtrack-ipsrs/public
# Harusnya error / blank dulu (index.php belum dibuat)
```

---

## 📊 FILE CHECKLIST

Setelah copy semua files, pastikan struktur folder seperti ini:

```
medtrack-ipsrs/
├── .gitignore ✅
├── README.md ✅
├── SETUP.md ✅
├── config/
│   ├── database.php ✅
│   ├── constants.php ✅
│   └── security.php ✅
├── app/
│   ├── controllers/ (empty)
│   ├── models/ (empty)
│   └── views/ (folder structure ada)
├── public/
│   ├── (index.php belum)
│   ├── css/
│   ├── js/
│   ├── libs/
│   └── uploads/ (qr/ & documents/)
└── database/
    ├── schema.sql ✅
    ├── dummy-data.sql ✅
    ├── migrations/
    └── backups/
```

---

## ⚠️ YANG BELUM DIBUAT (Tinggal Coding):

❌ **Controllers** - Buatkan Hari 3-4
- AuthController.php
- AsetController.php
- TrackingController.php
- dll

❌ **Models** - Buatkan Hari 3-4
- Database.php (base class)
- User.php
- Aset.php
- Tracking.php
- dll

❌ **Views** - Buatkan Hari 3-4
- auth/login.php
- layouts/header.php
- layouts/footer.php
- admin/dashboard.php
- admin/aset-list.php
- dll

❌ **Public** - Buatkan Hari 1-2
- index.php (router)
- .htaccess
- css/style.css
- js/script.js

---

## 💡 TIPS

### Jika pakai Git:
```bash
git add .
git commit -m "initial: setup project structure & config files"
git push origin main
```

### Verify Database Import:
```bash
mysql -u root medtrack_ipsrs -e "SHOW TABLES;"
```

Output harusnya:
```
+-----------------------+
| Tables_in_medtrack_ipsrs |
+-----------------------+
| m_aset                 |
| m_ruangan              |
| m_user                 |
| m_vendor               |
| t_maintenance          |
| t_sirkulasi            |
| t_tracking             |
+-----------------------+
```

### Jika ada error di setup:
1. Baca `SETUP.md` bagian Troubleshooting
2. Check `config/database.php` - pastikan DB credentials benar
3. Check MySQL running
4. Check database `medtrack_ipsrs` sudah dibuat

---

## 📞 READY FOR NEXT?

Setelah semua files tercopy & database imported, siap untuk:

✅ Day 1-2: Create core classes (Database.php, User.php model)
✅ Day 3-4: Create AuthController & Login View
✅ Day 5: Create AsetController & CRUD views
✅ Day 6: QR Code generation & GPS tracking

**Good luck! 🚀**
