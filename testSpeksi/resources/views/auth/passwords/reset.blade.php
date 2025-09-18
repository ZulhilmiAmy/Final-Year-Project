<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            font-size: 14px;
            margin-bottom: 25px;
            text-align: center;
            line-height: 1.5;
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

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
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

        .btn-primary:disabled {
            background: #cccccc;
            cursor: not-allowed;
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

        .email-display {
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
            text-align: center;
            font-weight: 600;
            color: #2c3e50;
        }

        /* Password validation styles */
        .password-requirements {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .password-requirements ul {
            margin: 0;
            padding-left: 20px;
        }

        .requirement {
            color: #6c757d;
            margin-bottom: 5px;
            transition: color 0.3s;
        }

        .requirement.valid {
            color: #28a745;
        }

        .requirement.valid::before {
            content: "✓ ";
            font-weight: bold;
        }

        .requirement.invalid {
            color: #dc3545;
        }

        .requirement.invalid::before {
            content: "✗ ";
            font-weight: bold;
        }

        .password-strength {
            height: 5px;
            margin-top: 10px;
            border-radius: 3px;
            background: #e9ecef;
            overflow: hidden;
        }

        .strength-meter {
            height: 100%;
            border-radius: 3px;
            width: 0%;
            transition: width 0.3s, background 0.3s;
        }

        .password-match {
            margin-top: 5px;
            font-size: 14px;
            display: none;
        }

        .password-match.valid {
            color: #28a745;
            display: block;
        }

        .password-match.invalid {
            color: #dc3545;
            display: block;
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .reset-container {
                padding: 20px;
            }
            
            .reset-title {
                font-size: 20px;
            }
            
            .password-requirements {
                font-size: 12px;
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
            <p class="reset-subtitle">Sila masukkan kata laluan baharu untuk akaun anda</p>
        </div>

        <div class="email-display">
            E-mel: {{ $email }}
        </div>

        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="password-requirements">
            <strong>Keperluan Kata Laluan:</strong>
            <ul>
                <li id="req-length" class="requirement invalid">Sekurang-kurangnya 8 aksara</li>
                <li id="req-lowercase" class="requirement invalid">Satu huruf kecil (a-z)</li>
                <li id="req-uppercase" class="requirement invalid">Satu huruf besar (A-Z)</li>
                <li id="req-number" class="requirement invalid">Satu nombor (0-9)</li>
                <li id="req-symbol" class="requirement invalid">Satu simbol (!@#$%^&*()_+-=)</li>
            </ul>
            <div class="password-strength">
                <div id="strength-meter" class="strength-meter"></div>
            </div>
        </div>

        <form method="POST" action="{{ route('password.update') }}" id="passwordResetForm">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">Alamat E-mel</label>
                <input id="email" type="email" class="@error('email') is-invalid @enderror" 
                       name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                       readonly style="background-color: #f8f9fa;">

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Kata Laluan Baharu</label>
                <input id="password" type="password" class="@error('password') is-invalid @enderror" 
                       name="password" required autocomplete="new-password" 
                       placeholder="Masukkan kata laluan baharu">

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password-confirm">Sahkan Kata Laluan</label>
                <input id="password-confirm" type="password" 
                       name="password_confirmation" required autocomplete="new-password"
                       placeholder="Masukkan semula kata laluan baharu">
                <div id="password-match-message" class="password-match"></div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-primary" id="submit-button" disabled>
                    Set Semula Kata Laluan
                </button>
            </div>
        </form>

        <div class="back-to-login">
            <a href="{{ route('login.custom') }}">← Kembali ke Log Masuk</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmInput = document.getElementById('password-confirm');
            const passwordMatchMessage = document.getElementById('password-match-message');
            const strengthMeter = document.getElementById('strength-meter');
            const submitButton = document.getElementById('submit-button');
            
            const requirements = {
                length: document.getElementById('req-length'),
                lowercase: document.getElementById('req-lowercase'),
                uppercase: document.getElementById('req-uppercase'),
                number: document.getElementById('req-number'),
                symbol: document.getElementById('req-symbol')
            };
            
            function validatePassword() {
                const value = passwordInput.value;
                let isValid = true;
                let strength = 0;
                
                // Check length (min 8 characters)
                if (value.length >= 8) {
                    requirements.length.classList.add('valid');
                    requirements.length.classList.remove('invalid');
                    strength += 20;
                } else {
                    requirements.length.classList.add('invalid');
                    requirements.length.classList.remove('valid');
                    isValid = false;
                }
                
                // Check lowercase
                if (/(?=.*[a-z])/.test(value)) {
                    requirements.lowercase.classList.add('valid');
                    requirements.lowercase.classList.remove('invalid');
                    strength += 20;
                } else {
                    requirements.lowercase.classList.add('invalid');
                    requirements.lowercase.classList.remove('valid');
                    isValid = false;
                }
                
                // Check uppercase
                if (/(?=.*[A-Z])/.test(value)) {
                    requirements.uppercase.classList.add('valid');
                    requirements.uppercase.classList.remove('invalid');
                    strength += 20;
                } else {
                    requirements.uppercase.classList.add('invalid');
                    requirements.uppercase.classList.remove('valid');
                    isValid = false;
                }
                
                // Check number
                if (/(?=.*\d)/.test(value)) {
                    requirements.number.classList.add('valid');
                    requirements.number.classList.remove('invalid');
                    strength += 20;
                } else {
                    requirements.number.classList.add('invalid');
                    requirements.number.classList.remove('valid');
                    isValid = false;
                }
                
                // Check symbol (common symbols)
                if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(value)) {
                    requirements.symbol.classList.add('valid');
                    requirements.symbol.classList.remove('invalid');
                    strength += 20;
                } else {
                    requirements.symbol.classList.add('invalid');
                    requirements.symbol.classList.remove('valid');
                    isValid = false;
                }
                
                // Update strength meter
                strengthMeter.style.width = strength + '%';
                if (strength < 40) {
                    strengthMeter.style.background = '#dc3545'; // Red - Weak
                } else if (strength < 80) {
                    strengthMeter.style.background = '#ffc107'; // Yellow - Medium
                } else if (strength < 100) {
                    strengthMeter.style.background = '#28a745'; // Green - Strong
                } else {
                    strengthMeter.style.background = '#007bff'; // Blue - Very Strong
                }
                
                return isValid;
            }
            
            function validatePasswordMatch() {
                if (passwordInput.value === '') {
                    passwordMatchMessage.classList.remove('valid', 'invalid');
                    passwordMatchMessage.textContent = '';
                    return false;
                }
                
                if (passwordInput.value === confirmInput.value) {
                    passwordMatchMessage.textContent = 'Kata laluan sepadan.';
                    passwordMatchMessage.classList.add('valid');
                    passwordMatchMessage.classList.remove('invalid');
                    return true;
                } else {
                    passwordMatchMessage.textContent = 'Kata laluan tidak sepadan.';
                    passwordMatchMessage.classList.add('invalid');
                    passwordMatchMessage.classList.remove('valid');
                    return false;
                }
            }
            
            function validateForm() {
                const isPasswordValid = validatePassword();
                const isPasswordMatch = validatePasswordMatch();
                
                submitButton.disabled = !(isPasswordValid && isPasswordMatch);
            }
            
            passwordInput.addEventListener('input', function() {
                validatePassword();
                validatePasswordMatch();
                validateForm();
            });
            
            confirmInput.addEventListener('input', function() {
                validatePasswordMatch();
                validateForm();
            });
            
            // Show/hide password toggle (optional enhancement)
            function addPasswordToggle() {
                const toggleText = 'Tunjukkan Kata Laluan';
                const passwordFields = document.querySelectorAll('input[type="password"]');
                
                passwordFields.forEach(field => {
                    const wrapper = document.createElement('div');
                    wrapper.style.position = 'relative';
                    field.parentNode.insertBefore(wrapper, field);
                    wrapper.appendChild(field);
                    
                    const toggle = document.createElement('button');
                    toggle.type = 'button';
                    toggle.textContent = toggleText;
                    toggle.style.position = 'absolute';
                    toggle.style.right = '10px';
                    toggle.style.top = '50%';
                    toggle.style.transform = 'translateY(-50%)';
                    toggle.style.background = 'none';
                    toggle.style.border = 'none';
                    toggle.style.color = '#3498db';
                    toggle.style.cursor = 'pointer';
                    toggle.style.fontSize = '12px';
                    toggle.style.padding = '2px 5px';
                    
                    toggle.addEventListener('click', function() {
                        if (field.type === 'password') {
                            field.type = 'text';
                            toggle.textContent = 'Sembunyikan Kata Laluan';
                        } else {
                            field.type = 'password';
                            toggle.textContent = toggleText;
                        }
                    });
                    
                    wrapper.appendChild(toggle);
                });
            }
            
            // Uncomment the line below if you want to add show/hide password toggle
            // addPasswordToggle();
            
            // Initial validation
            validateForm();
            
            // Form submission validation
            document.getElementById('passwordResetForm').addEventListener('submit', function(e) {
                if (!validatePassword() || !validatePasswordMatch()) {
                    e.preventDefault();
                    alert('Sila pastikan semua keperluan kata laluan dipenuhi dan kata laluan sepadan.');
                }
            });
        });
    </script>
</body>
</html>