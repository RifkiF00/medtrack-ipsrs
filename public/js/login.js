/**
 * login.js - Login Page Interactivity
 * Medtrack IPSRS - RS Hasna Medika Kuningan
 */

// === TOGGLE PASSWORD VISIBILITY ===
// Pindahkan fungsi ke dalam scope global agar bisa dipanggil dari onclick=""
window.togglePassword = function () {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.querySelector('.view-pass');

    // Cari elemen <i> di dalam tombol
    const icon = toggleBtn.querySelector('i');

    if (passwordInput.type === 'password') {
        // Ubah jadi text (lihat password)
        passwordInput.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
        // Opsional: Beri warna merah sedikit agar user sadar password terlihat
        icon.style.color = 'rgba(255, 100, 100, 0.9)';
    } else {
        // Ubah kembali jadi password (sembunyikan)
        passwordInput.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
        icon.style.color = ''; // Kembalikan ke warna asli
    }
}

// === FORM VALIDATION & REMEMBER ME ===
document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');
    const usernameInput = document.getElementById('username');

    // Remember Me logic
    if (usernameInput) {
        const savedUser = localStorage.getItem('medtrack_user');
        if (savedUser) {
            usernameInput.value = savedUser;
        }
    }

    if (loginForm) {
        loginForm.addEventListener('submit', function () {
            const submitBtn = document.querySelector('.btn-login');

            // Simpan username ke local storage
            if (usernameInput.value) {
                localStorage.setItem('medtrack_user', usernameInput.value);
            }

            // Ubah state tombol
            submitBtn.disabled = true;
            submitBtn.textContent = 'Memproses...';
            submitBtn.style.opacity = '0.7';
            submitBtn.style.cursor = 'not-allowed';
        });
    }
});

// Pesan konsol untuk debugging
console.log('MedTrack JS Loaded Successfully!');