<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Log Masuk - Sistem Perkhidmatan JKSP</title>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@400;600&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: "Poppins", sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            gap: 25px;
            margin-bottom: 20px;
        }

        .logo-container img {
            height: 60px;
        }

        .title-main {
            font-family: "Merriweather", serif;
            /* Elegan & tebal */
            font-size: 34px;
            font-weight: 700;
            color: #2c3e50;
            display: block;
            margin-bottom: 15px;
            /* jarakkan ke bawah dari sub-title */
        }

        .title-sub {
            font-family: "Poppins", sans-serif;
            /* ringkas & moden */
            font-size: 20px;
            font-weight: 400;
            color: #555;
            line-height: 1.6;
            display: block;
        }

        .form-group {
            text-align: left;
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 6px;
            color: #555;
        }

        .show-password {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            /* jarak antara input dan checkbox */
            font-size: 13px;
            color: #555;
        }

        .show-password input {
            margin: 0;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 14px;
            outline: none;
            transition: border 0.3s;
        }

        input:focus {
            border-color: #2980b9;
        }

        .checkbox-group {
            margin: 10px 0 20px 0;
            font-size: 14px;
            color: #555;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: 0.3s;
        }

        .btn-login {
            background: #2980b9;
            color: white;
            margin-bottom: 12px;
        }

        .btn-login:hover {
            background: #2573a7;
        }

        .btn-reset {
            background: #f44336;
            color: white;
        }

        .btn-reset:hover {
            background: #d32f2f;
        }

        .captcha-box {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        #captchaCanvas {
            border: 1px solid #ddd;
            border-radius: 6px;
            background: #f9f9f9;
        }

        .forgot-password {
            margin-top: 15px;
            font-size: 14px;
        }

        .forgot-password a {
            color: #2980b9;
            text-decoration: none;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .copyright {
            margin-top: 25px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="logo-container">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/26/Coat_of_arms_of_Malaysia.svg"
                    alt="Jata Malaysia">
                <img src="https://www.moh.gov.my/index.php/file_manager/dl_item/634756755a33567964584e686269394d543064505830744c545638784c6a633154554a664c6e42755a773d3d"
                    alt="Logo KKM">
            </div>

            <h1>
                <span class="title-main">Log Masuk</span><br>
                <span class="title-sub">
                    Sistem Perkhidmatan JKSP<br>
                    Hospital Enche' Besar Hajjah Khalsom, Kluang
                </span>
            </h1>

            @if($errors->any())
                <div style="color: red; margin-bottom: 15px; font-size: 14px; text-align:left;">
                    @foreach($errors->all() as $error)
                        <p>• {{ $error === 'These credentials do not match our records.' ? 'Butiran log masuk ini tidak sepadan dengan rekod kami.' : $error }}
                        </p>
                    @endforeach
                </div>
            @endif

            <div class="form-group">
                <label for="username">ID Pengguna</label>
                <input type="email" id="username" name="username" value="{{ old('username') }}"
                    placeholder="nama@example.com" title="Sila masukkan alamat email yang sah" required autofocus>
                <div style="font-size: 12px; color: #666; margin-top: 5px;">
                    Sila masukkan alamat email anda
                </div>
            </div>

            <div class="form-group">
                <label for="password">Kata Laluan</label>
                <input type="password" id="password" name="password" required>
                <div class="show-password">
                    <input type="checkbox" id="show-password">
                    <label for="show-password">Papar Katalaluan</label>
                </div>
            </div>

            <div class="form-group">
                <label for="captcha-input">Captcha</label>
                <div class="captcha-box">
                    <canvas id="captchaCanvas" width="120" height="40"></canvas>
                    <button type="button" onclick="generateCaptcha()">↻</button>
                </div>
                <input type="text" id="captcha-input" name="captcha_input" placeholder="Masukkan captcha" required>
                <input type="hidden" id="captcha-session" name="captcha_session">
            </div>

            <button type="submit" class="btn btn-login">Log Masuk</button>
            <button type="button" class="btn btn-reset" onclick="resetForm()">Semula</button>

            <div class="forgot-password">
                <a href="{{ route('password.request') }}">Lupa Kata Laluan?</a>
            </div>

            <div class="copyright">
                © Bahagian Kewangan, KKM @ 2020
            </div>
        </form>
    </div>

    <script>
        let captchaCode = "";

        function generateCaptcha() {
            const canvas = document.getElementById("captchaCanvas");
            const ctx = canvas.getContext("2d");
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
            captchaCode = "";
            for (let i = 0; i < 5; i++) {
                captchaCode += chars.charAt(Math.floor(Math.random() * chars.length));
            }

            ctx.font = "bold 20px Poppins";
            ctx.fillStyle = "#2c3e50";
            ctx.textBaseline = "middle";
            ctx.fillText(captchaCode, 15, 20);

            document.getElementById('captcha-session').value = captchaCode;
        }

        function resetForm() {
            document.getElementById('username').value = "";
            document.getElementById('password').value = "";
            document.getElementById('captcha-input').value = "";
            generateCaptcha();
        }

        document.addEventListener('DOMContentLoaded', function () {
            generateCaptcha();
            const showPasswordCheckbox = document.getElementById('show-password');
            if (showPasswordCheckbox) {
                showPasswordCheckbox.addEventListener('change', function () {
                    const passwordInput = document.getElementById('password');
                    passwordInput.type = this.checked ? 'text' : 'password';
                });
            }
        });
    </script>
</body>

</html>