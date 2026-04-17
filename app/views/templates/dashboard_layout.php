<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['judul'] ?? 'MedTrack IPSRS'; ?></title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/dashboard.css">
</head>
<body>

<?php
$role = $_SESSION['role'] ?? '';
$namaUser = $_SESSION['nama_lengkap'] ?? 'User';
$contentView = $data['content_view'] ?? '';
$hidePageHeader = $data['hide_page_header'] ?? false;
?>

<div class="dashboard-wrapper">

    <aside class="sidebar-left">
        <div class="brand">
            <div class="brand-icon"><i class="bi bi-hospital"></i></div>
            <span>MedTrack</span>
        </div>

        <ul class="nav-menu">
            <li class="<?= $contentView === 'dashboard/index' ? 'active' : ''; ?>">
                <a href="<?= BASEURL; ?>/dashboard"><i class="bi bi-grid-1x2"></i> Dasbor Utama</a>
            </li>

            <?php if ($role == 'Staf IPSRS' || $role == 'Staf Logistik'): ?>
                <li class="<?= strpos($contentView, 'aset/') === 0 ? 'active' : ''; ?>">
                    <a href="<?= BASEURL; ?>/aset"><i class="bi bi-box-seam"></i> Master Alkes & Sarpras</a>
                </li>
                <li><a href="#"><i class="bi bi-arrow-left-right"></i> Mutasi Ruangan</a></li>
            <?php endif; ?>

            <?php if ($role == 'Unit Pengguna'): ?>
                <li><a href="#"><i class="bi bi-box2-heart"></i> Inventaris Instalasi</a></li>
            <?php endif; ?>

            <?php if ($role == 'Staf IPSRS' || $role == 'Unit Pengguna'): ?>
                <li><a href="#"><i class="bi bi-ticket-detailed"></i> E-Work Order (WO)</a></li>
            <?php endif; ?>

            <?php if ($role == 'Staf IPSRS'): ?>
                <li><a href="#"><i class="bi bi-tools"></i> Preventive Maintenance</a></li>
                <li><a href="#"><i class="bi bi-building"></i> Direktori Unit & SDM</a></li>
            <?php endif; ?>

            <?php if ($role == 'Staf IPSRS' || $role == 'Staf Logistik'): ?>
                <li><a href="#"><i class="bi bi-file-earmark-medical"></i> Dokumen Mutu</a></li>
            <?php endif; ?>

            <li style="margin-top: 30px;">
                <a href="<?= BASEURL; ?>/auth/logout" style="color: #ffbaba;" onclick="return confirm('Apakah Anda yakin ingin keluar?')">
                    <i class="bi bi-box-arrow-left"></i> Keluar Sistem
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <p>&copy; MedTrack 2026</p>
            <span>RS Hasna Medika Kuningan</span>
        </div>
    </aside>

    <main class="main-content">
        <?php if (!$hidePageHeader): ?>
            <div class="header-mid">
                <div class="header-titles">
                    <h1><?= $data['page_heading'] ?? 'Dashboard'; ?></h1>
                    <p><?= $data['page_subheading'] ?? 'Sistem Manajemen Aset Medis'; ?></p>
                </div>

                <button id="toggleRight" class="toggle-btn" title="Tutup/Buka Sidebar Kontrol">
                    <i class="bi bi-layout-sidebar-reverse"></i>
                </button>
            </div>
        <?php endif; ?>

        <?php
        if (!empty($contentView)) {
            require_once '../app/views/' . $contentView . '.php';
        }
        ?>
    </main>

    <aside class="sidebar-right">
        <div class="top-right">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Cari Kode Label / SN...">
            </div>
            <div class="icon-btn"><i class="bi bi-bell"></i><span class="badge">3</span></div>
            <img src="<?= BASEURL; ?>/uploads/assets/default-avatar.png" class="profile-pic" alt="User Profile">
        </div>

        <?php if ($role == 'Staf IPSRS' || $role == 'Staf Logistik'): ?>
        <div class="schedule-section">
            <div class="section-title">
                <h3><?= ($role == 'Staf Logistik') ? 'Jadwal Pengadaan' : 'Agenda Kalibrasi'; ?></h3>
                <i class="bi bi-three-dots"></i>
            </div>

            <div class="calendar-strip">
                <i class="bi bi-chevron-left"></i>
                <span>April 2026</span>
                <i class="bi bi-chevron-right"></i>
            </div>
            <div class="dates">
                <div class="date-item"><span>S</span> 14</div>
                <div class="date-item active"><span>R</span> 15</div>
                <div class="date-item"><span>K</span> 16</div>
                <div class="date-item"><span>J</span> 17</div>
            </div>
        </div>
        <?php endif; ?>

        <div class="task-card">
            <?php if ($role == 'Staf IPSRS'): ?>
                <div class="task-img"></div>
                <h4>Kalibrasi EKG Monitor</h4>
                <div class="task-details">
                    <div class="detail-row"><i class="bi bi-calendar"></i> Kamis, 16 April 2026</div>
                    <div class="detail-row"><i class="bi bi-geo-alt"></i> Instalasi Gawat Darurat</div>
                </div>
                <div class="task-actions">
                    <button class="btn-outline">Reschedule</button>
                    <button class="btn-solid">Eksekusi WO <i class="bi bi-arrow-right"></i></button>
                </div>

            <?php elseif ($role == 'Unit Pengguna'): ?>
                <div class="task-img" style="background-image: url('<?= BASEURL; ?>/img/qr-scan-bg.jpg');"></div>
                <h4>Lapor Malfungsi Alat</h4>
                <p style="color: rgba(255,255,255,0.8); font-size: 12px; margin-bottom: 20px;">
                    Gunakan pemindai untuk respon cepat teknisi IPSRS.
                </p>
                <div class="task-actions" style="flex-direction: column;">
                    <button class="btn-solid" style="width: 100%; margin-bottom: 10px;">
                        <i class="bi bi-qr-code-scan"></i> Pindai QR Aset
                    </button>
                    <button class="btn-outline" style="width: 100%;">Input Manual</button>
                </div>

            <?php elseif ($role == 'Staf Logistik'): ?>
                <div class="task-img" style="background-image: url('<?= BASEURL; ?>/img/box-bg.jpg');"></div>
                <h4>Verifikasi Sparepart</h4>
                <div class="task-details">
                    <div class="detail-row"><i class="bi bi-box-seam"></i> Oxygen Sensor Kit</div>
                    <div class="detail-row"><i class="bi bi-person"></i> Req: Teknisi Budi</div>
                </div>
                <div class="task-actions">
                    <button class="btn-outline">Reject</button>
                    <button class="btn-solid">Approve <i class="bi bi-check2"></i></button>
                </div>
            <?php endif; ?>
        </div>
    </aside>
</div>

<script src="<?= BASEURL; ?>/js/dashboard.js"></script>
</body>
</html>