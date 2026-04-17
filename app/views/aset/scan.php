<div class="card" style="padding:24px; max-width:1100px;">

    <!-- HEADER -->
    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:16px; flex-wrap:wrap; margin-bottom:24px;">
        <div>
            <h2 style="margin:0 0 8px 0;">Scan QR Aset</h2>
            <p style="margin:0; color:#6b7280;">
                Pindai QR melalui kamera atau upload gambar QR untuk membuka detail aset otomatis.
            </p>
        </div>

        <a href="<?= BASEURL; ?>/aset"
           style="padding:10px 14px; background:#6c757d; color:#fff; text-decoration:none; border-radius:8px;">
            ← Kembali
        </a>
    </div>

    <!-- GRID -->
    <div style="display:grid; grid-template-columns:1.4fr 0.9fr; gap:20px; align-items:start;">

        <!-- KIRI -->
        <div style="display:flex; flex-direction:column; gap:20px;">

            <!-- SCAN KAMERA -->
            <div style="background:#f8fafc; border:1px solid #e5e7eb; border-radius:14px; padding:18px;">
                <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:14px;">
                    <div>
                        <h3 style="margin:0 0 4px 0;">Kamera</h3>
                        <p style="margin:0; color:#6b7280; font-size:14px;">
                            Arahkan kamera ke QR code aset.
                        </p>
                    </div>

                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                        <button id="stop-scan-btn"
                                type="button"
                                style="padding:9px 14px; background:#dc3545; color:#fff; border:none; border-radius:8px; cursor:pointer;">
                            Stop Kamera
                        </button>

                        <button id="start-scan-btn"
                                type="button"
                                style="padding:9px 14px; background:#198754; color:#fff; border:none; border-radius:8px; cursor:pointer;">
                            Start Ulang
                        </button>
                    </div>
                </div>

                <div id="qr-reader"
                     style="width:100%; background:#fff; border-radius:12px; padding:10px; min-height:340px;"></div>
            </div>

            <!-- UPLOAD QR -->
            <div style="background:#f8fafc; border:1px solid #e5e7eb; border-radius:14px; padding:18px;">
                <h3 style="margin:0 0 8px 0;">Upload Gambar QR</h3>
                <p style="margin:0 0 14px 0; color:#6b7280; font-size:14px;">
                    Gunakan ini jika kamera tidak bisa membaca QR.
                </p>

                <input id="qr-file-input"
                       type="file"
                       accept="image/*"
                       style="display:block; width:100%; padding:10px; border:1px solid #d1d5db; border-radius:10px; background:#fff;">

                <div id="upload-status"
                     style="margin-top:12px; font-size:14px; color:#6b7280;">
                    Belum ada file dipilih.
                </div>
            </div>

        </div>

        <!-- KANAN -->
        <div style="display:flex; flex-direction:column; gap:20px;">

            <div style="background:#f8fafc; border:1px solid #e5e7eb; border-radius:14px; padding:18px;">
                <h3 style="margin:0 0 12px 0;">Hasil Scan</h3>

                <div style="padding:14px; background:#fff; border:1px solid #e5e7eb; border-radius:12px;">
                    <div style="font-size:12px; color:#6b7280; margin-bottom:8px;">Status</div>
                    <div id="scan-status" style="font-weight:600; color:#111827;">
                        Menunggu scan...
                    </div>
                </div>

                <div style="margin-top:14px; padding:14px; background:#fff; border:1px solid #e5e7eb; border-radius:12px;">
                    <div style="font-size:12px; color:#6b7280; margin-bottom:8px;">Hasil QR</div>
                    <div id="scan-result" style="font-size:14px; word-break:break-word; color:#111827;">
                        Belum ada hasil.
                    </div>
                </div>
            </div>

            <div style="background:#f8fafc; border:1px solid #e5e7eb; border-radius:14px; padding:18px;">
                <h3 style="margin:0 0 10px 0;">Petunjuk</h3>
                <ol style="margin:0; padding-left:18px; line-height:1.8; color:#374151;">
                    <li>Izinkan akses kamera saat diminta browser.</li>
                    <li>Arahkan QR ke tengah frame.</li>
                    <li>Jika valid, sistem akan langsung membuka detail aset.</li>
                    <li>Jika kamera gagal, gunakan upload gambar QR.</li>
                </ol>
            </div>

        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
(function () {
    const scanResult = document.getElementById("scan-result");
    const scanStatus = document.getElementById("scan-status");
    const uploadStatus = document.getElementById("upload-status");
    const fileInput = document.getElementById("qr-file-input");
    const stopBtn = document.getElementById("stop-scan-btn");
    const startBtn = document.getElementById("start-scan-btn");

    const readerId = "qr-reader";
    let scanner = null;
    let isRedirecting = false;

    function setStatus(text, color = "#111827") {
        scanStatus.textContent = text;
        scanStatus.style.color = color;
    }

    function setResult(text) {
        scanResult.textContent = text;
    }

    function isValidInternalUrl(decodedText) {
        try {
            const url = new URL(decodedText, window.location.origin);
            return url.origin === window.location.origin;
        } catch (e) {
            return false;
        }
    }

    function handleDecoded(decodedText) {
        if (isRedirecting) return;

        setResult(decodedText);

        if (!isValidInternalUrl(decodedText)) {
            setStatus("QR terbaca, tetapi URL tidak valid untuk sistem ini.", "#dc2626");
            return;
        }

        isRedirecting = true;
        setStatus("QR valid. Membuka detail aset...", "#198754");

        setTimeout(() => {
            window.location.href = decodedText;
        }, 300);
    }

    function renderScanner() {
        scanner = new Html5QrcodeScanner(
            readerId,
            {
                fps: 10,
                qrbox: { width: 230, height: 230 },
                rememberLastUsedCamera: true,
                supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
            },
            false
        );

        scanner.render(
            (decodedText) => {
                handleDecoded(decodedText);
            },
            () => {}
        );

        setStatus("Kamera aktif. Silakan scan QR.", "#2563eb");
    }

    renderScanner();

    stopBtn.addEventListener("click", function () {
        const stopButton = document.querySelector("#qr-reader__dashboard_section_csr button");
        if (stopButton) {
            stopButton.click();
            setStatus("Kamera dihentikan.", "#dc3545");
        } else {
            setStatus("Kamera belum aktif.", "#6b7280");
        }
        isRedirecting = false;
    });

    startBtn.addEventListener("click", function () {
        const readerWrap = document.getElementById(readerId);
        readerWrap.innerHTML = "";
        isRedirecting = false;
        setResult("Belum ada hasil.");
        renderScanner();
    });

    fileInput.addEventListener("change", function (e) {
        const file = e.target.files[0];
        if (!file) return;

        uploadStatus.textContent = "Memproses gambar QR...";
        setStatus("Memproses gambar QR...", "#2563eb");
        isRedirecting = false;

        const fileScanner = new Html5Qrcode(readerId + "-file-temp");

        fileScanner.scanFile(file, true)
            .then(decodedText => {
                uploadStatus.textContent = "QR dari gambar berhasil dibaca.";
                handleDecoded(decodedText);
            })
            .catch(() => {
                uploadStatus.textContent = "QR pada gambar tidak terbaca.";
                setStatus("QR pada gambar tidak terbaca.", "#dc3545");
                setResult("Gagal membaca QR dari file.");
            });
    });
})();
</script>