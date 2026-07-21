<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Keuangan App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #1e1b4b 0%, #4361ee 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }
        .register-card {
            width: 100%;
            max-width: 450px;
            border: none;
            border-radius: 18px;
            box-shadow: 0 20px 45px rgba(0,0,0,.25);
        }
        .register-icon {
            width: 64px; height: 64px;
            border-radius: 16px;
            background: linear-gradient(135deg,#4361ee,#3730a3);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.7rem;
            margin: 0 auto 14px;
        }
    </style>
</head>
<body>
    <div class="card register-card p-4">
        <div class="card-body">
            <div class="register-icon"><i class="fa-solid fa-user-plus"></i></div>
            <h4 class="text-center fw-bold mb-1">Buat Akun Baru</h4>
            <div class="d-flex align-items-center my-3">
                <hr class="flex-grow-1 my-0 opacity-25">
                <span class="px-2 text-muted small" style="font-size: 0.8rem;">Develop by Rabin Van Mora &copy; <?= date('Y'); ?> </span>
                <hr class="flex-grow-1 my-0 opacity-25">
            </div>
            <p class="text-center text-muted mb-4">Mulai kelola keuanganmu sekarang</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2"><small><?= $error ?></small></div>
            <?php endif; ?>

            <?= form_open('auth/register') ?>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="<?= set_value('name') ?>" placeholder="John Doe" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= set_value('username') ?>" placeholder="johndoe" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= set_value('email') ?>" placeholder="nama@email.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Konfirmasi Password</label>
                    <input type="password" name="passconf" class="form-control" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-semibold mt-2">
                    <i class="fa-solid fa-user-check"></i> Daftar Akun
                </button>
            <?= form_close() ?>

            <!-- Separator "Atau" -->
            <div class="d-flex align-items-center my-3">
                <hr class="flex-grow-1 my-0 opacity-25">
                <span class="px-2 text-muted small">atau</span>
                <hr class="flex-grow-1 my-0 opacity-25">
            </div>

            <!-- Tombol Google Login -->
            <a href="<?= site_url('auth/google_login') ?>" class="btn btn-outline-light w-100 fw-medium d-flex align-items-center justify-content-center gap-2 py-2 text-dark bg-white border">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 48 48">
                    <path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C12.955 4 4 12.955 4 24s8.955 20 20 20s20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z"/>
                    <path fill="#FF3D00" d="m6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C16.318 4 9.656 8.337 6.306 14.691z"/>
                    <path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/>
                    <path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/>
                </svg>
                Daftar / Masuk dengan Google
            </a>
            <p class="text-center text-muted mt-3 mb-0" style="font-size:.85rem;">
                Sudah punya akun? <a href="<?= site_url('login') ?>" class="text-decoration-none fw-bold">Masuk di sini</a>
            </p>

        </div>
    </div>
</body>
</html>