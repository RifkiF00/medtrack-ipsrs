<div class="login-container">
    <div class="login-card">

        <div class="login-side-form">
            <h1 class="brand-title">MedTrack-IPSRS</h1>
            <p class="welcome-text">Manajemen Aset Inventaris Medis, Sarana & Prasarana Terintegrasi SIMRS</p>
            <p class="subtitle-hospital">RS Hasna Medika Kuningan</p>
            
            <?php if (!empty($data['error'])): ?>
                <div class="error-toast"><?= $data['error']; ?></div>
            <?php endif; ?>

            <form action="<?= BASEURL; ?>/auth/login" method="POST" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?= getCSRFToken(); ?>">
                <div class="input-group">
                    <div class="input-icon"><i class="bi bi-person"></i></div>
                    <input type="text" name="username" id="username" placeholder="Username" required>
                </div>
                
                <div class="input-group">
                    <div class="input-icon"><i class="bi bi-lock"></i></div>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <button type="button" class="view-pass" onclick="togglePassword()">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>

                <div class="forgot-wrapper">
                    <a href="#">Lupa Password?</a>
                </div>

                <button type="submit" name="submit" class="btn-login">Masuk ke Dasbor</button>
            </form>
        </div>

        <div class="login-side-illustration">
            <div class="wave-shape">
                <svg viewBox="0 0 100 100" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" style="height:100%;width:100%;">
                    <path d="M0,0 L100,0 L100,100 L0,100 Q50,50 0,0 Z" fill="white"/>
                </svg>
            </div>
            <img src="<?= BASEURL; ?>/uploads/assets/bg-rs.jpeg" class="illustration-img">
            <div class="plus-icon"><i class="bi bi-plus-lg"></i></div>
        </div>

    </div>
</div> <script src="<?= BASEURL; ?>/js/login.js"></script>