<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Set Semula Kata Laluan - Sistem Perkhidmatan Kerja Sosial Perubatan</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .reset-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            padding: 30px;
        }

        .reset-header {
            text-align: center;
            margin-bottom: 25px;
        }

        .reset-logo {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .logo {
            height: 50px;
            width: auto;
        }

        .national-emblem {
            height: 60px;
            width: auto;
        }

        .reset-title {
            color: #2c3e50;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .reset-subtitle {
            color: #7f8c8d;
            font-size: 16px;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="email"]:focus {
            border-color: #3498db;
            outline: none;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }

        .back-to-login a {
            color: #3498db;
            text-decoration: none;
        }

        .back-to-login a:hover {
            text-decoration: underline;
        }

        /* Error messages */
        .invalid-feedback {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .reset-container {
                padding: 20px;
            }
            
            .reset-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-header">
            <div class="reset-logo">
                <img src="https://upload.wikimedia.org/wikipedia/commons/2/26/Coat_of_arms_of_Malaysia.svg"
                    alt="Jata Malaysia" class="national-emblem">
                <img src="https://www.moh.gov.my/index.php/file_manager/dl_item/634756755a33567964584e686269394d543064505830744c545638784c6a633154554a664c6e42755a773d3d"
                    alt="Logo KKM" class="logo">
            </div>
            
            <h1 class="reset-title">Set Semula Kata Laluan</h1>
            <p class="reset-subtitle">Sistem Perkhidmatan Jabatan Kerja Sosial Perubatan</p>
        </div>

        <?php if(session('status')): ?>
            <div class="alert-success">
                <?php echo e(session('status') === 'We have emailed your password reset link.' ? 'Kami telah menghantar pautan set semula kata laluan melalui e-mel.' : session('status')); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.email')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group">
                <label for="email">Alamat E-mel</label>
                <input id="email" type="email" class="<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                       name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus
                       placeholder="Masukkan alamat e-mel anda">

                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-feedback" role="alert">
                        <strong><?php echo e($message === "We can't find a user with that email address." ? 'Kami tidak dapat mencari pengguna dengan alamat e-mel tersebut.' : $message); ?></strong>
                    </span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary">
                    Hantar Pautan Set Semula Kata Laluan
                </button>
            </div>
        </form>

        <div class="back-to-login">
            <a href="<?php echo e(route('login.custom')); ?>">← Kembali ke Log Masuk</a>
        </div>
    </div>
</body>
</html><?php /**PATH C:\laragon\www\testSpeksi\resources\views/auth/passwords/email.blade.php ENDPATH**/ ?>