<?php
session_start();
require_once 'db.php';

if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true) {
    header("Location: testing.php");
    exit();
}

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['email_user'] ?? '');
    $pass = trim($_POST['password'] ?? '');

    $user_data = authenticate_user($user, $pass);

    if ($user_data) {
        $_SESSION['is_logged_in'] = true;
        $_SESSION['admin_name']   = $user_data['nama'];
        $_SESSION['kecamatan']    = $user_data['kecamatan'];
        
        header("Location: testing.php");
        exit();
    } else {
        $login_error = 'Username atau Password salah!';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RekapTes Surabaya</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap");

        * { box-sizing: border-box; font-family: "DM Sans", sans-serif; }
        body { 
            margin: 0; 
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #311042 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 24px; 
        }
        .auth-card { 
            width: 100%; 
            max-width: 420px; 
            background: #ffffff; 
            border-radius: 20px; 
            padding: 40px 32px; 
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3); 
            text-align: center; 
        }
        .auth-logo-wrapper { 
            margin-bottom: 20px; 
            display: flex; 
            justify-content: center; 
            align-items: center;
        }
        .auth-logo-wrapper img {
            max-width: 90px;
            height: auto;
            display: block;
        }
        .auth-card h2 { 
            font-family: "Plus Jakarta Sans", sans-serif; 
            color: #14213d; 
            font-size: 24px; 
            font-weight: 800; 
            margin: 0 0 6px 0; 
        }
        .auth-subtitle { 
            color: #7a8191; 
            font-size: 13px; 
            margin: 0 0 28px 0; 
            line-height: 1.4;
        }
        .auth-form { 
            display: flex; 
            flex-direction: column; 
            gap: 18px; 
            text-align: left; 
        }
        .form-group label { 
            display: block; 
            font-size: 11px; 
            font-weight: 700; 
            color: #475569; 
            text-transform: uppercase; 
            letter-spacing: 0.05em; 
            margin-bottom: 6px; 
        }
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .form-group input { 
            width: 100%; 
            padding: 12px 14px; 
            border: 1px solid #e8eaf0; 
            border-radius: 10px; 
            font-size: 14px; 
            color: #1e293b; 
            outline: none; 
            transition: border-color 0.2s;
        }
        .password-wrapper input {
            padding-right: 42px;
        }
        .form-group input:focus { 
            border-color: #6546d7; 
            box-shadow: 0 0 0 3px rgba(101, 70, 215, 0.15); 
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            background: none;
            border: none;
            padding: 4px;
            color: #64748b;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }
        .toggle-password:hover {
            color: #6546d7;
        }
        .auth-button { 
            width: 100%; 
            padding: 13px; 
            background: linear-gradient(135deg, #6546d7, #4f46e5); 
            color: white; 
            border: none; 
            border-radius: 10px; 
            font-weight: 700; 
            font-size: 14px; 
            margin-top: 8px; 
            cursor: pointer; 
            transition: opacity 0.2s;
        }
        .auth-button:hover { opacity: 0.92; }
        .error-alert { 
            background: #fee2e2; 
            border: 1px solid #fca5a5; 
            color: #b91c1c; 
            padding: 10px 14px; 
            border-radius: 8px; 
            font-size: 12px; 
            margin-bottom: 16px; 
            text-align: left; 
        }
    </style>
</head>
<body>

    <div class="auth-card">
        <div class="auth-logo-wrapper">
            <img src="BKKBNLOGO.png" alt="Logo RekapTes">
        </div>

        <h2>Selamat Datang</h2>
        <p class="auth-subtitle">Masuk untuk mengelola dan memantau hasil peserta</p>

        <?php if (!empty($login_error)): ?>
            <div class="error-alert"><?= htmlspecialchars($login_error) ?></div>
        <?php endif; ?>

        <form method="POST" action="" class="auth-form">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="email_user" placeholder="Masukkan username..." required>
            </div>

            <div class="form-group">
                <label>Kata Sandi</label>
                <div class="password-wrapper">
                    <input type="password" id="passwordInput" name="password" placeholder="Masukkan kata sandi..." required>
                    <button type="button" class="toggle-password" onclick="togglePass()" title="Lihat/Sembunyikan Kata Sandi">
                        <!-- Ikon Mata Terbuka -->
                        <svg id="eyeOpen" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <!-- Ikon Mata Dicoret (Hidden default) -->
                        <svg id="eyeClosed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="auth-button">Masuk</button>
        </form>
    </div>

    <script>
        function togglePass() {
            const input = document.getElementById('passwordInput');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                input.type = 'password';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        }
    </script>

</body>
</html>