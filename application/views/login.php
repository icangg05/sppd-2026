<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="google" content="notranslate">
    <meta name="robots" content="noindex, nofollow">
    <title>SPPD ELEKTRONIK KOTA KENDARI</title>
    <link rel="icon" href="<?php echo base_url();?>assets2/dist/img/user.png">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url();?>assets2/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets2/dist/css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets2/plugins/iCheck/css/blue.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link href="https://fonts.googleapis.com/css?family=Anton|Permanent+Marker|Quicksand" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            overflow: hidden;
            margin: 0;
        }

        #video-background {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -100;
            object-fit: cover;
        }

        .login-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(41, 128, 185, 0.6) 0%, rgba(109, 213, 250, 0.6) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 900px;
            padding: 15px;
        }

        .login-card {
            background: transparent;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            display: flex;
            flex-wrap: wrap;
        }

        .login-left {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            min-width: 300px;
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
        }

        .login-right {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-width: 300px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .brand-logos {
            margin-bottom: 20px;
        }

        .brand-logos img {
            margin: 0 10px;
            transition: transform 0.3s ease;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }
        
        .brand-logos img:hover {
            transform: scale(1.05);
        }

        .app-title {
            font-weight: 700;
            font-size: 24px;
            color: #000000;
            margin-top: 10px;
            line-height: 1.3;
            letter-spacing: 0.5px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }

        .app-subtitle {
            font-size: 14px;
            color: #000000;
            margin-top: 8px;
            font-weight: 400;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .login-header {
            margin-bottom: 35px;
            text-align: center;
        }

        .login-header h3 {
            font-weight: 600;
            color: #2c3e50;
            margin: 0 0 10px 0;
            font-size: 22px;
        }

        .login-header p {
            color: #000000;
            font-size: 14px;
            margin: 0;
        }

        .hero-img {
            max-width: 80%;
            margin-bottom: 30px;
            border-radius: 10px;
        }

        /* Kalimat sambutan: versi mobile menyebut nama sistem karena panel kiri disembunyikan */
        .login-header .teks-mobile {
            display: none;
        }

        /* Floating Label Styles */
        .form-group.floating-group {
            position: relative;
            margin-bottom: 25px;
        }

        .form-control.floating-control {
            height: 52px;
            padding: 25px 15px 10px 15px;
            border: 2px solid #eef2f7;
            border-radius: 12px;
            font-size: 15px;
            background: #fdfdfd;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: none;
            color: #34495e;
        }

        .form-control.floating-control:focus {
            border-color: #3c8dbc;
            background: #fff;
            outline: none;
            box-shadow: 0 0 0 4px rgba(60, 141, 188, 0.1);
        }

        .floating-label {
            position: absolute;
            top: 15px;
            left: 17px;
            font-size: 15px;
            color: #95a5a6;
            pointer-events: none;
            transition: all 0.2s ease;
            background: transparent;
            font-weight: 400;
        }

        .form-control.floating-control:focus ~ .floating-label,
        .form-control.floating-control:not(:placeholder-shown) ~ .floating-label {
            top: 8px;
            left: 15px;
            font-size: 11px;
            font-weight: 600;
            color: #3c8dbc;
            transform: translateY(0);
        }

        /* Tombol lihat/sembunyikan password */
        .floating-group .floating-control.has-toggle {
            padding-right: 50px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
            width: 36px;
            height: 36px;
            padding: 0;
            border: none;
            border-radius: 8px;
            background: transparent;
            color: #95a5a6;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s, background 0.2s;
        }

        .toggle-password:hover,
        .toggle-password:focus-visible {
            color: #3c8dbc;
            background: rgba(60, 141, 188, 0.08);
            outline: none;
        }

        .toggle-password svg {
            width: 20px;
            height: 20px;
        }

        /* Captcha */
        .captcha-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }

        .captcha-gambar {
            display: flex;
            gap: 6px;
            flex: 0 0 auto;
        }

        .captcha-gambar img {
            display: block;
            width: 160px;
            height: 52px;
            border: 2px solid #eef2f7;
            border-radius: 12px;
            background: #fff;
            user-select: none;
        }

        .captcha-refresh {
            width: 42px;
            height: 52px;
            padding: 0;
            border: 2px solid #eef2f7;
            border-radius: 12px;
            background: #fdfdfd;
            color: #95a5a6;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s, border-color 0.2s, background 0.2s;
        }

        .captcha-refresh:hover,
        .captcha-refresh:focus-visible {
            color: #3c8dbc;
            border-color: #3c8dbc;
            background: rgba(60, 141, 188, 0.06);
            outline: none;
        }

        .captcha-refresh svg {
            width: 20px;
            height: 20px;
        }

        .form-group.floating-group.captcha-input {
            flex: 1 1 130px;
            min-width: 130px;
            margin-bottom: 0;
        }

        .captcha-input .floating-control {
            text-transform: uppercase;
            letter-spacing: 3px;
        }

        .login-alert {
            border-radius: 10px;
            font-size: 14px;
            padding: 12px;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        #hitung-mundur {
            display: inline-block;
            min-width: 2ch;
            font-size: 16px;
            font-variant-numeric: tabular-nums;
        }

        .btn-modern[disabled] {
            opacity: 0.6;
            cursor: not-allowed;
            box-shadow: none;
        }

        .btn-modern {
            height: 52px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 1px;
            text-transform: uppercase;
            background: linear-gradient(135deg, #3c8dbc 0%, #2980b9 100%);
            border: none;
            box-shadow: 0 10px 20px rgba(60, 141, 188, 0.3);
            transition: all 0.3s;
            margin-top: 10px;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(60, 141, 188, 0.4);
            background: linear-gradient(135deg, #4fa3d4 0%, #3498db 100%);
        }
        
        .btn-modern:active {
            transform: translateY(0);
            box-shadow: 0 5px 15px rgba(60, 141, 188, 0.3);
        }

        .copyright {
            margin-top: 40px;
            font-size: 12px;
            color: #000000;
            text-align: center;
        }
        
        .copyright a {
            color: #3c8dbc;
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .copyright a:hover {
            color: #3c8dbc;
        }
        
        @media (max-width: 768px) {
            body {
                height: auto;
                overflow-y: auto;
            }

            .login-overlay {
                position: relative;
                height: auto;
                min-height: 100vh;
                padding: 24px 16px;
                align-items: center;
                display: flex;
            }

            .login-container {
                padding: 0;
            }

            .login-card {
                flex-direction: column;
                width: 100%;
                max-width: 440px;
                margin: 0 auto;
            }

            /* Panel kiri (foto walikota, logo, judul & subjudul aplikasi) disembunyikan di mobile:
               langsung tampil form login */
            .login-left {
                display: none;
            }

            .login-right {
                padding: 28px 20px 24px;
                min-width: 0;
            }

            .login-header {
                margin-bottom: 28px;
            }

            .login-header .teks-desktop {
                display: none;
            }

            .login-header .teks-mobile {
                display: block;
            }

            /* Video latar: animasi sirkuitnya ada di sisi kiri video. Di desktop video ditempel ke kanan bawah
               dengan ukuran asli, sehingga di layar sempit yang terlihat hanya bagian kanan yang polos.
               Di mobile: penuhi layar dan geser bidikan ke area sirkuit. */
            #video-background {
                top: 0;
                left: 0;
                right: auto;
                bottom: auto;
                width: 100%;
                height: 100%;
                min-width: 0;
                min-height: 0;
                object-fit: cover;
                object-position: 22% center;
            }
        }

        @media (max-width: 360px) {
            .login-overlay {
                padding: 16px 12px;
            }

            .login-left,
            .login-right {
                padding-left: 16px;
                padding-right: 16px;
            }
        }
    </style>
</head>
<body class="hold-transition">
    <video autoplay muted loop playsinline webkit-playsinline id="video-background">
        <source src="<?php echo base_url();?>assets2/dist/img/background.webm" type="video/webm">
        Your browser does not support HTML5 video.
    </video>
    <div class="login-overlay">
        <div class="login-container">
            <div class="login-card">
                <div class="login-left">
                    <img src="<?php echo base_url();?>assets/img/HERO.webp" alt="Hero Image" class="hero-img">
                    <div class="brand-logos">
                        <img src="<?php echo base_url();?>assets/img/logokdi.png" alt="Logo Kendari" width="50">
                        <img src="<?php echo base_url();?>assets/img/BSE.png" alt="Logo BSE" width="120">
                    </div>
                    <div class="app-title">SPPD ELEKTRONIK<br>KOTA KENDARI</div>
                    <div class="app-subtitle">Sistem Informasi Perjalanan Dinas</div>
                </div>
                
                <div class="login-right">
                    <div class="login-header">
                        <h3>Selamat Datang</h3>
                        <p class="teks-desktop">Silahkan login untuk memulai sesi anda</p>
                        <p class="teks-mobile">Silahkan login ke SPPD Elektronik Kota Kendari</p>
                    </div>

                    <?php
                    $message = $this->session->flashdata('notif');
                    $identity = (string) $this->session->flashdata('isi_identity');
                    if($message){
                        echo '<div class="alert alert-danger text-center" style="border-radius: 10px; font-size: 14px; padding: 10px; margin-bottom: 25px;">'.$message .'</div>';
                    }
                    $status_login = isset($status_login) ? $status_login : array('jenis' => '');
                    // super admin tidak pernah diblokir, hanya dikunci 60 detik tiap 3 kali gagal
                    $kebal = !empty($status_login['kebal']);
                    $sebelum = (!$kebal && isset($status_login['tahap']) && $status_login['tahap'] == 2) ? 'akun diblokir' : 'login dikunci 60 detik';
                    ?>

                    <?php if ($this->session->flashdata('captcha_salah')) { ?>
                        <div class="alert alert-danger text-center login-alert" role="alert">
                            <strong>Kode captcha salah atau sudah kedaluwarsa.</strong><br>
                            <small>Masukkan password dan kode captcha yang baru.</small>
                        </div>
                    <?php } ?>

                    <?php if ($status_login['jenis'] === 'salah') { ?>
                        <div class="alert alert-danger text-center login-alert" role="alert">
                            <strong>Nama user atau password salah!</strong><br>
                            <small>Sisa <b><?php echo (int) $status_login['sisa_percobaan']; ?></b> percobaan sebelum <?php echo $sebelum; ?>.</small>
                        </div>
                    <?php } elseif ($status_login['jenis'] === 'kunci') { ?>
                        <div class="alert alert-warning text-center login-alert" role="alert" id="alert-kunci" data-sisa="<?php echo (int) $status_login['sisa_detik']; ?>">
                            <span id="teks-kunci"><strong>Terlalu banyak percobaan login gagal.</strong><br>Coba lagi dalam <b id="hitung-mundur"><?php echo (int) $status_login['sisa_detik']; ?></b> detik.</span>
                            <span id="teks-selesai" style="display:none">Silakan coba login kembali.<br><small>Sisa <b>3</b> percobaan lagi sebelum <?php echo $kebal ? 'login dikunci 60 detik lagi' : 'akun diblokir'; ?>.</small></span>
                        </div>
                    <?php } elseif ($status_login['jenis'] === 'blokir') { ?>
                        <div class="alert alert-danger text-center login-alert" role="alert">
                            <strong>Akun diblokir.</strong><br>
                            <small>Terlalu banyak percobaan login gagal. Hubungi Super Admin untuk membuka blokir.</small>
                        </div>
                    <?php } ?>

                    <form action="<?php echo base_url();?>login" method="post" accept-charset="utf-8">
                        <div class="form-group floating-group">
                            <input type="text" class="form-control floating-control" name="identity" id="identity" placeholder=" " value="<?php echo html_escape($identity); ?>" required>
                            <label for="identity" class="floating-label">User Name</label>
                        </div>

                        <div class="form-group floating-group">
                            <input type="password" class="form-control floating-control has-toggle" name="password" id="password" placeholder=" " autocomplete="current-password" required<?php echo $identity !== '' ? ' autofocus' : ''; ?>>
                            <label for="password" class="floating-label">Password</label>
                            <button type="button" class="toggle-password" id="toggle-password" aria-label="Tampilkan password" aria-pressed="false" title="Tampilkan password">
                                <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                            </button>
                        </div>

                        <div class="captcha-row">
                            <div class="captcha-gambar">
                                <img src="<?php echo base_url('login/captcha'); ?>?t=<?php echo time(); ?>" id="captcha-img" alt="Kode captcha" width="160" height="52" draggable="false">
                                <button type="button" class="captcha-refresh" id="captcha-refresh" aria-label="Ganti kode captcha" title="Ganti kode captcha">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="23 4 23 10 17 10"/><polyline points="1 20 1 14 7 14"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                                </button>
                            </div>
                            <div class="form-group floating-group captcha-input">
                                <input type="text" class="form-control floating-control" name="captcha" id="captcha" placeholder=" " autocomplete="off" autocapitalize="characters" autocorrect="off" spellcheck="false" maxlength="5" required>
                                <label for="captcha" class="floating-label">Kode Captcha</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <button type="submit" class="btn btn-primary btn-block btn-modern" id="btn-login">LOGIN</button>
                            </div>
                        </div>
                    </form>
                    
                    <div class="copyright">
                        V 3.0 | &copy; <?php echo date('Y')?> Pemerintah Kota Kendari
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery 2.2.3 -->
    <script src="<?php echo base_url();?>assets2/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <!-- Bootstrap 3.3.6 -->
    <script src="<?php echo base_url();?>assets2/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets2/plugins/iCheck/js/icheck.min.js"></script>
    <script> 
        $(function(){ 
            // Optional: Keep iCheck if needed for other inputs, but currently no checkboxes in new form
            $('input[type="checkbox"]').iCheck({ checkboxClass: 'icheckbox_square-blue', radioClass: 'iradio_square-blue', increaseArea: '20%' });
        });

        // Hitung mundur saat login dikunci sementara
        (function () {
            var alertKunci = document.getElementById('alert-kunci');
            if (!alertKunci) return;
            var btn = document.getElementById('btn-login');
            var angka = document.getElementById('hitung-mundur');
            var selesai = Date.now() + parseInt(alertKunci.getAttribute('data-sisa'), 10) * 1000;
            var teksAsli = btn.textContent;

            function perbarui() {
                var sisa = Math.max(0, Math.ceil((selesai - Date.now()) / 1000));
                angka.textContent = sisa;
                if (sisa > 0) {
                    btn.disabled = true;
                    btn.textContent = 'TUNGGU ' + sisa + ' DETIK';
                    return;
                }
                clearInterval(timer);
                btn.disabled = false;
                btn.textContent = teksAsli;
                document.getElementById('teks-kunci').style.display = 'none';
                document.getElementById('teks-selesai').style.display = '';
                alertKunci.className = alertKunci.className.replace('alert-warning', 'alert-info');
            }
            var timer = setInterval(perbarui, 250);
            perbarui();
        })();

        // Lihat / sembunyikan password
        (function () {
            var input = document.getElementById('password');
            var btn = document.getElementById('toggle-password');
            if (!input || !btn) return;
            var eye = btn.querySelector('.icon-eye');
            var eyeOff = btn.querySelector('.icon-eye-off');
            btn.addEventListener('click', function () {
                var tampil = input.type === 'password';
                input.type = tampil ? 'text' : 'password';
                eye.style.display = tampil ? 'none' : '';
                eyeOff.style.display = tampil ? '' : 'none';
                var label = tampil ? 'Sembunyikan password' : 'Tampilkan password';
                btn.setAttribute('aria-label', label);
                btn.setAttribute('title', label);
                btn.setAttribute('aria-pressed', tampil ? 'true' : 'false');
                input.focus();
            });
        })();

        // Ganti gambar captcha (kode baru dibuat & disimpan di server setiap gambar dimuat)
        (function () {
            var img = document.getElementById('captcha-img');
            var btn = document.getElementById('captcha-refresh');
            var input = document.getElementById('captcha');
            if (!img || !btn || !input) return;
            var alamat = img.src.split('?')[0];
            function gantiCaptcha(fokus) {
                img.src = alamat + '?t=' + Date.now();
                input.value = '';
                if (fokus) input.focus();
            }
            btn.addEventListener('click', function () { gantiCaptcha(true); });
            // halaman yang dipulihkan dari cache (tombol Back) memakai gambar lama yang kodenya sudah tidak berlaku
            window.addEventListener('pageshow', function (e) { if (e.persisted) gantiCaptcha(false); });
        })();
    </script>
</body>
</html>