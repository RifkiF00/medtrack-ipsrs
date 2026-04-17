<?php
// Password yang ingin kita buatkan hash-nya
$passwordInput = "hasna123";

// Generate Hash
$hashBaru = password_hash($passwordInput, PASSWORD_DEFAULT);

echo "<h2>Pengecekan Hash MedTrack</h2>";
echo "Password Asli: <b>" . $passwordInput . "</b><br>";
echo "Copy Kode Hash di bawah ini ke Database:<br>";
echo "<textarea rows='3' cols='70' style='margin-top:10px; padding:10px; background:#f4f4f4; border:1px solid #ccc; font-family:monospace;'>" . $hashBaru . "</textarea>";

echo "<br><br>--- Tes Verifikasi ---<br>";
if (password_verify($passwordInput, $hashBaru)) {
    echo "<span style='color:green;'><b>BERHASIL:</b> Password cocok dengan Hash tersebut!</span>";
} else {
    echo "<span style='color:red;'><b>GAGAL:</b> Password tidak cocok.</span>";
}
?>