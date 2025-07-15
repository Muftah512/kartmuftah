<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة نقطة بيع جديدة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        .form-container {
            max-width: 800px;
            margin: 40px auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }
        
        .form-header {
            background: linear-gradient(120deg, #4361ee, #3a0ca3);
            color: white;
            padding: 30px 40px;
            text-align: center;
            position: relative;
        }
        
        .form-header h1 {
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 1.8rem;
        }
        
        .form-header p {
            opacity: 0.9;
            margin-bottom: 0;
            font-size: 1.1rem;
        }
        
        .form-header i {
            position: absolute;
            top: 30px;
            left: 40px;
            font-size: 2.5rem;
            opacity: 0.2;
        }
        
        .form-body {
            padding: 40px;
        }
        
        .section-title {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 25px;
            color: var(--primary);
            font-weight: 600;
            font-size: 1.3rem;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), #3a0ca3);
            border-radius: 3px;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #444;
        }
        
        .form-control, .form-select {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid #e0e6ed;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }
        
        .input-group-text {
            background-color: #eef2ff;
            border: 2px solid #e0e6ed;
            border-radius: 12px 0 0 12px !important;
        }
        
        .password-toggle {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .password-toggle:hover {
            color: var(--primary);
        }
        
        .form-check-input {
            width: 1.3em;
            height: 1.3em;
            margin-top: 0.15em;
            border: 2px solid #e0e6ed;
        }
        
        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }
        
        .form-check-label {
            font-weight: 500;
            color: #555;
        }
        
        .btn-primary {
            background: linear-gradient(120deg, #4361ee, #3a0ca3);
            border: none;
            border-radius: 12px;
            padding: 14px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
            max-width: 300px;
            display: block;
            margin: 30px auto 0;
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        }
        
        .alert {
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 25px;
        }
        
        .alert-success {
            background: linear-gradient(120deg, #4ade80, #22c55e);
            color: white;
            border: none;
        }
        
        .alert-danger {
            background: linear-gradient(120deg, #f87171, #ef4444);
            color: white;
            border: none;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .form-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        
        .password-container {
            position: relative;
        }
        
        .password-strength {
            height: 5px;
            background: #e2e8f0;
            border-radius: 3px;
            margin-top: 8px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            border-radius: 3px;
            transition: width 0.4s ease;
        }
        
        .password-hints {
            margin-top: 8px;
            font-size: 0.85rem;
            color: #64748b;
        }
        
        .password-hint {
            display: flex;
            align-items: center;
            margin-bottom: 5px;
        }
        
        .password-hint i {
            margin-left: 5px;
            font-size: 0.7rem;
        }
        
        .password-hint.valid {
            color: #22c55e;
        }
        
        @media (max-width: 768px) {
            .form-body {
                padding: 25px;
            }
            
            .form-header {
                padding: 25px 20px;
            }
            
            .form-header i {
                left: 20px;
                top: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <i class="fas fa-cash-register"></i>
            <h1>إضافة نقطة بيع جديدة</h1>
            <p>املأ النموذج أدناه لإضافة نقطة بيع جديدة إلى النظام</p>
        </div>
        
        <div class="form-body">
            <!-- Success Message -->
            <div class="alert alert-success" style="display: none;">
                <i class="fas fa-check-circle me-2"></i>تم إضافة نقطة البيع بنجاح!
            </div>
            
            <!-- Error Message -->
            <div class="alert alert-danger" style="display: none;">
                <i class="fas fa-exclamation-circle me-2"></i>حدث خطأ أثناء الإضافة. يرجى مراجعة البيانات المدخلة.
            </div>
            
            <form id="posForm">
                <!-- Account Information Section -->
                <h3 class="section-title">
                    <i class="fas fa-user-circle me-2"></i>معلومات الحساب
                </h3>
                
                <div class="form-grid">
                    <div class="mb-4">
                        <label for="name" class="form-label">
                            <i class="fas fa-store me-2"></i>اسم نقطة البيع
                        </label>
                        <div class="position-relative">
                            <input type="text" name="name" id="name" class="form-control" placeholder="أدخل اسم نقطة البيع" required>
                            <span class="form-icon"><i class="fas fa-store"></i></span>
                        </div>
                        <div class="invalid-feedback">يرجى إدخال اسم نقطة البيع</div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>البريد الإلكتروني
                        </label>
                        <div class="position-relative">
                            <input type="email" name="email" id="email" class="form-control" placeholder="أدخل البريد الإلكتروني" required>
                            <span class="form-icon"><i class="fas fa-envelope"></i></span>
                        </div>
                        <div class="invalid-feedback">يرجى إدخال بريد إلكتروني صحيح</div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone me-2"></i>رقم الهاتف
                        </label>
                        <div class="position-relative">
                            <input type="tel" name="phone" id="phone" class="form-control" placeholder="أدخل رقم الهاتف" required>
                            <span class="form-icon"><i class="fas fa-phone"></i></span>
                        </div>
                        <div class="invalid-feedback">يرجى إدخال رقم هاتف صحيح</div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="location" class="form-label">
                            <i class="fas fa-location-dot me-2"></i>الموقع
                        </label>
                        <div class="position-relative">
                            <input type="text" name="location" id="location" class="form-control" placeholder="أدخل موقع نقطة البيع" required>
                            <span class="form-icon"><i class="fas fa-location-dot"></i></span>
                        </div>
                        <div class="invalid-feedback">يرجى إدخال موقع نقطة البيع</div>
                    </div>
                </div>
                
                <!-- Password Section -->
                <h3 class="section-title">
                    <i class="fas fa-lock me-2"></i>كلمة المرور
                </h3>
                
                <div class="form-grid">
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-key me-2"></i>كلمة المرور
                        </label>
                        <div class="password-container position-relative">
                            <input type="password" name="password" id="password" class="form-control" placeholder="أدخل كلمة المرور" required>
                            <span class="form-icon"><i class="fas fa-key"></i></span>
                            <span class="password-toggle position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%);">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        
                        <div class="password-strength mt-2">
                            <div class="password-strength-bar" id="password-strength-bar"></div>
                        </div>
                        
                        <div class="password-hints">
                            <div class="password-hint" id="length-hint">
                                <i class="fas fa-circle"></i> 8 أحرف على الأقل
                            </div>
                            <div class="password-hint" id="number-hint">
                                <i class="fas fa-circle"></i> تحتوي على رقم
                            </div>
                            <div class="password-hint" id="special-hint">
                                <i class="fas fa-circle"></i> تحتوي على رمز خاص
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-key me-2"></i>تأكيد كلمة المرور
                        </label>
                        <div class="password-container position-relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="أعد إدخال كلمة المرور" required>
                            <span class="form-icon"><i class="fas fa-key"></i></span>
                            <span class="password-toggle position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%);">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        <div class="invalid-feedback">كلمتا المرور غير متطابقتين</div>
                    </div>
                </div>
                
                <!-- Status Section -->
                <h3 class="section-title">
                    <i class="fas fa-toggle-on me-2"></i>حالة نقطة البيع
                </h3>
                
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" checked>
                    <label class="form-check-label" for="is_active">
                        <i class="fas fa-power-off me-2"></i>نشط
                    </label>
                    <p class="text-muted mt-2">عند تفعيل هذه الخانة، ستتمكن نقطة البيع من تسجيل الدخول والبدء في العمل فوراً</p>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>حفظ نقطة البيع
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const passwordToggles = document.querySelectorAll('.password-toggle');
            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
            
            // Password strength meter
            const passwordInput = document.getElementById('password');
            const strengthBar = document.getElementById('password-strength-bar');
            const lengthHint = document.getElementById('length-hint');
            const numberHint = document.getElementById('number-hint');
            const specialHint = document.getElementById('special-hint');
            
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                
                // Check password length
                if (password.length >= 8) {
                    strength += 33;
                    lengthHint.classList.add('valid');
                } else {
                    lengthHint.classList.remove('valid');
                }
                
                // Check for numbers
                if (/\d/.test(password)) {
                    strength += 33;
                    numberHint.classList.add('valid');
                } else {
                    numberHint.classList.remove('valid');
                }
                
                // Check for special characters
                if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                    strength += 34;
                    specialHint.classList.add('valid');
                } else {
                    specialHint.classList.remove('valid');
                }
                
                // Update strength bar
                strengthBar.style.width = `${strength}%`;
                
                // Update bar color
                if (strength < 33) {
                    strengthBar.style.backgroundColor = '#ef4444';
                } else if (strength < 66) {
                    strengthBar.style.backgroundColor = '#f59e0b';
                } else {
                    strengthBar.style.backgroundColor = '#22c55e';
                }
            });
            
            // Form submission simulation
            const form = document.getElementById('posForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show success message
                document.querySelector('.alert-success').style.display = 'block';
                document.querySelector('.alert-danger').style.display = 'none';
                
                // Reset form after 2 seconds (simulation)
                setTimeout(() => {
                    form.reset();
                    document.querySelector('.alert-success').style.display = 'none';
                    strengthBar.style.width = '0';
                    
                    // Reset hints
                    [lengthHint, numberHint, specialHint].forEach(hint => {
                        hint.classList.remove('valid');
                    });
                }, 2000);
            });
        });
    </script>
</body>
</html>