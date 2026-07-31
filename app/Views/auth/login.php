<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Perpustakaan Kampus</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-brand">
                Perpus<span>Kampus</span>
            </div>
            
            <p class="text-center text-muted" style="margin-bottom:2rem; font-size:0.9375rem;">
                Silakan masuk menggunakan akun Anda untuk mengakses sistem perpustakaan.
            </p>

            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" style="justify-content:center; padding:0.75rem;">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?url=auth/processLogin" method="POST">
                <div class="form-group">
                    <label class="form-label" style="font-weight:600;">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus style="padding:0.75rem 1rem;">
                </div>
                
                <div class="form-group" style="margin-top:1.25rem;">
                    <label class="form-label" style="font-weight:600;">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required style="padding:0.75rem 1rem;">
                </div>
                
                <button type="submit" class="btn btn-primary btn-lg" style="width:100%; margin-top:2rem; padding:0.875rem; font-size:1rem; border-radius:12px;">Log In</button>
            </form>
            
            <div class="text-center mt-4">
                <a href="index.php?url=home/index" style="font-size:0.875rem; font-weight:500; color:var(--text-muted);">← Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>
