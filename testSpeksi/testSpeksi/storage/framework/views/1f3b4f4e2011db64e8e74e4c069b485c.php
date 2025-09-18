<!DOCTYPE html>
<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Log Masuk - Sistem Perkhidmatan JKSP</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <!-- Import CSS & JS melalui Vite -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/login.css', 'resources/js/login.js']); ?>
</head>

<body>
    <div class="login-card">
        <form method="POST" action="<?php echo e(route('login')); ?>">
            <?php echo csrf_field(); ?>

            <div class="logo-container">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/26/Coat_of_arms_of_Malaysia.svg" alt="Jata Malaysia">
                <img src="https://www.moh.gov.my/index.php/file_manager/dl_item/634756755a33567964584e686269394d543064505830744c545638784c6a633154554a664c6e42755a773d3d" alt="Logo KKM">
            </div>

            <h1>
                <span class="title-main">Log Masuk</span><br>
                <span class="title-sub">
                    Sistem Perkhidmatan JKSP<br>
                    Hospital Enche' Besar Hajjah Khalsom, Kluang
                </span>
            </h1>

            <?php if($errors->any()): ?>
                <div class="error-message">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p>• <?php echo e($error === 'These credentials do not match our records.' ? 'Butiran log masuk ini tidak sepadan dengan rekod kami.' : $error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="email">ID Pengguna</label>
                <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>"
                    placeholder="nama@example.com" title="Sila masukkan alamat email yang sah" required autofocus>
                <div class="helper-text">Sila masukkan alamat email anda</div>
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
                <a href="<?php echo e(route('password.request')); ?>">Lupa Kata Laluan?</a>
            </div>

            <div class="copyright">
                © Bahagian Kewangan, KKM @ 2020
            </div>
        </form>
    </div>
</body>

</html>
<?php /**PATH C:\laragon\www\testSpeksi\resources\views/auth/login-custom.blade.php ENDPATH**/ ?>