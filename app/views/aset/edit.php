<div class="container" style="max-width: 900px; margin: 30px auto; padding: 0 20px;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; gap:15px; flex-wrap:wrap;">
        <div>
            <h2 style="margin:0;">Edit Aset</h2>
            <p style="margin:8px 0 0; color:#666;">Perbarui data aset.</p>
        </div>
        <a href="<?= BASEURL; ?>/aset" style="padding:10px 16px; background:#6c757d; color:#fff; text-decoration:none; border-radius:8px;">
            Kembali
        </a>
    </div>

    <?php if (!empty($data['errors'])): ?>
        <div style="margin-bottom:20px; padding:14px 16px; border-radius:8px; background:#fdecec; color:#b42318; border:1px solid #f5c2c7;">
            <strong>Periksa input berikut:</strong>
            <ul style="margin:10px 0 0 18px;">
                <?php foreach ($data['errors'] as $error): ?>
                    <li><?= escape($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= BASEURL; ?>/aset/update/<?= $data['aset']['id_aset']; ?>" method="POST" style="background:#fff; border:1px solid #ddd; border-radius:12px; padding:24px;">
        <input type="hidden" name="csrf_token" value="<?= getCSRFToken(); ?>">

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

            <div>
                <label>Kode Label</label>
                <input type="text" name="kode_label" value="<?= escape($data['aset']['kode_label'] ?? ''); ?>" style="width:100%; padding:10px;">
            </div>

            <div>
                <label>Nama Alat</label>
                <input type="text" name="nama_alat" value="<?= escape($data['aset']['nama_alat'] ?? ''); ?>" style="width:100%; padding:10px;">
            </div>

            <div>
                <label>Kategori Aset</label>
                <select name="kategori_aset" style="width:100%; padding:10px;">
                    <option value="Medis" <?= $data['aset']['kategori_aset']=='Medis'?'selected':'' ?>>Medis</option>
                    <option value="Sarpras" <?= $data['aset']['kategori_aset']=='Sarpras'?'selected':'' ?>>Sarpras</option>
                    <option value="IT" <?= $data['aset']['kategori_aset']=='IT'?'selected':'' ?>>IT</option>
                </select>
            </div>

            <div>
                <label>Jumlah Unit</label>
                <input type="number" name="jumlah_unit" min="1" value="<?= escape($data['aset']['jumlah_unit'] ?? 1); ?>" style="width:100%; padding:10px;">
            </div>

            <div>
                <label>Merk</label>
                <input type="text" name="merk" value="<?= escape($data['aset']['merk'] ?? ''); ?>" style="width:100%; padding:10px;">
            </div>

            <div>
                <label>Model</label>
                <input type="text" name="model" value="<?= escape($data['aset']['model'] ?? ''); ?>" style="width:100%; padding:10px;">
            </div>

            <div>
                <label>Serial Number</label>
                <input type="text" name="serial_number" value="<?= escape($data['aset']['serial_number'] ?? ''); ?>" style="width:100%; padding:10px;">
            </div>

            <div>
                <label>No. Sertifikat</label>
                <input type="text" name="no_sertifikat" value="<?= escape($data['aset']['no_sertifikat'] ?? ''); ?>" style="width:100%; padding:10px;">
            </div>

            <div>
                <label>Tanggal Pengadaan</label>
                <input type="date" name="tgl_pengadaan" value="<?= $data['aset']['tgl_pengadaan'] ?? ''; ?>" style="width:100%; padding:10px;">
            </div>

            <div>
                <label>Tgl Kalibrasi Terakhir</label>
                <input type="date" name="tgl_kalibrasi_terakhir" value="<?= $data['aset']['tgl_kalibrasi_terakhir'] ?? ''; ?>" style="width:100%; padding:10px;">
            </div>

            <div>
                <label>Harga Perolehan</label>
                <input type="number" step="0.01" name="harga_perolehan" value="<?= escape($data['aset']['harga_perolehan'] ?? ''); ?>" style="width:100%; padding:10px;">
            </div>

            <div>
                <label>Status Kondisi</label>
                <select name="status_kondisi" style="width:100%; padding:10px;">
                    <?php
                    $kondisiList = ['Gudang','Baik','Rusak Ringan','Rusak Berat','Maintenance','Pensiun'];
                    foreach ($kondisiList as $kondisi):
                    ?>
                        <option value="<?= $kondisi; ?>" <?= (($data['aset']['status_kondisi'] ?? '') === $kondisi) ? 'selected' : ''; ?>>
                            <?= $kondisi; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="grid-column:1 / -1;">
                <label>Ruangan Saat Ini</label>
                <select name="id_ruang_saat_ini" style="width:100%; padding:10px;">
                    <?php foreach ($data['ruangan'] as $ruang): ?>
                        <option value="<?= $ruang['id_ruang']; ?>"
                            <?= (($data['aset']['id_ruang_saat_ini'] ?? '') == $ruang['id_ruang']) ? 'selected' : ''; ?>>
                            <?= escape($ruang['nama_ruang']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="grid-column:1 / -1;">
                <label>Lokasi Fisik</label>
                <input type="text" name="lokasi_fisik" value="<?= escape($data['aset']['lokasi_fisik'] ?? ''); ?>" style="width:100%; padding:10px;">
            </div>

            <div style="grid-column:1 / -1;">
                <label>Gambar Aset</label>
                <input type="text" name="gambar_aset" value="<?= escape($data['aset']['gambar_aset'] ?? ''); ?>" style="width:100%; padding:10px;">
            </div>

            <div style="grid-column:1 / -1;">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="4" style="width:100%; padding:10px;"><?= escape($data['aset']['keterangan'] ?? ''); ?></textarea>
            </div>

        </div>

        <div style="margin-top:20px; display:flex; justify-content:flex-end; gap:10px;">
            <a href="<?= BASEURL; ?>/aset" style="padding:10px 16px; background:#6c757d; color:#fff; border-radius:8px;">Batal</a>
            <button type="submit" style="padding:10px 16px; background:#0d6efd; color:#fff; border:none; border-radius:8px;">
                Update Aset
            </button>
        </div>
    </form>
</div>