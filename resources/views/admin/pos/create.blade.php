<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة نقطة بيع جديدة - لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --light-bg: #f8f9fa;
            --dark-text: #2b2d42;
            --border-color: #dee2e6;
            --success-color: #4cc9f0;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f5f7fb;
            color: #333;
            padding-top: 20px;
        }
        
        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.15);
        }
        
        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--border-color);
            padding: 15px 20px;
            font-weight: 700;
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            transition: all 0.3s;
            font-family: 'Tajawal', sans-serif;
        }
        
        .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            outline: none;
        }
        
        .form-group {
            margin-bottom: 20px;
            position: relative;
        }
        
        .form-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        
        .form-input.has-icon {
            padding-right: 45px;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            color: white;
            font-size: 1.1rem;
            cursor: pointer;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
        }
        
        .back-btn {
            background: white;
            border: 1px solid var(--border-color);
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            color: var(--dark-text);
            text-decoration: none;
            margin-left: 15px;
        }
        
        .back-btn:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            color: var(--primary-color);
        }
        
        .error-message {
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 5px;
            display: block;
        }
        
        .form-section {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
        }
        
        .status-toggle {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        
        input:checked + .slider:before {
            transform: translateX(30px);
        }
        
        .status-label {
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .btn-container {
                flex-direction: column;
                gap: 15px;
            }
            
            .back-btn, .submit-btn {
                width: 100%;
                justify-content: center;
            }
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
        }
        
        .password-strength {
            height: 5px;
            margin-top: 5px;
            border-radius: 5px;
            background: #e9ecef;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: width 0.3s;
        }
        
        .strength-0 { width: 20%; background: #dc3545; }
        .strength-1 { width: 40%; background: #fd7e14; }
        .strength-2 { width: 60%; background: #ffc107; }
        .strength-3 { width: 80%; background: #20c997; }
        .strength-4 { width: 100%; background: #198754; }
        
        .password-requirements {
            margin-top: 10px;
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .requirement {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 5px;
        }
        
        .requirement i {
            font-size: 0.7rem;
        }
        
        .requirement.valid {
            color: #198754;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- رأس الصفحة -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0"><i class="fas fa-cash-register me-3"></i>إضافة نقطة بيع جديدة</h1>
                    <p class="mb-0 mt-2">أضف نقطة بيع جديدة إلى النظام وأكمل معلوماتها الأساسية</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('admin.pos.index') }}" class="back-btn">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- بطاقة النموذج -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.pos.store') }}" method="POST" id="posForm">
                    @csrf
                    
                    <!-- معلومات أساسية -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-info-circle me-2"></i>المعلومات الأساسية
                        </h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">اسم نقطة البيع</label>
                                    <div class="position-relative">
                                        <input type="text" name="name" value="{{ old('name') }}" required
                                            class="form-input form-input has-icon">
                                        <i class="fas fa-store form-icon"></i>
                                    </div>
                                    @error('name')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">الموقع</label>
                                    <div class="position-relative">
                                        <input type="text" name="location" value="{{ old('location') }}" required
                                            class="form-input form-input has-icon">
                                        <i class="fas fa-location-dot form-icon"></i>
                                    </div>
                                    @error('location')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">المحاسب المسؤول</label>
                                    <div class="position-relative">
                                        <select name="accountant_id" class="form-input form-input has-icon">
                                            <option value="">— اختر المحاسب —</option>
                                            @foreach($accountants as $acc)
                                                <option value="{{ $acc->id }}"
                                                    @selected(old('accountant_id') == $acc->id)>
                                                    {{ $acc->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="fas fa-user-tie form-icon"></i>
                                    </div>
                                    @error('accountant_id')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <div class="position-relative">
                                        <input type="email" name="email" value="{{ old('email') }}" required
                                            class="form-input form-input has-icon">
                                        <i class="fas fa-envelope form-icon"></i>
                                    </div>
                                    @error('email')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">حالة نقطة البيع</label>
                                    <div class="status-toggle">
                                        <label class="switch">
                                            <input type="checkbox" name="is_active" value="1" checked>
                                            <span class="slider"></span>
                                        </label>
                                        <span class="status-label">نشطة</span>
                                    </div>
                                    <p class="text-muted mt-2 mb-0">تفعيل نقطة البيع للاستخدام الفوري</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- معلومات تسجيل الدخول -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-lock me-2"></i>معلومات تسجيل الدخول
                        </h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">كلمة المرور</label>
                                    <div class="position-relative">
                                        <input type="password" name="password" id="password" required
                                            class="form-input form-input has-icon">
                                        <i class="fas fa-key form-icon"></i>
                                        <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                                    </div>
                                    <div class="password-strength">
                                        <div class="password-strength-bar" id="password-strength-bar"></div>
                                    </div>
                                    <div class="password-requirements">
                                        <div class="requirement" id="length-req">
                                            <i class="fas fa-circle"></i>
                                            <span>8 أحرف على الأقل</span>
                                        </div>
                                        <div class="requirement" id="uppercase-req">
                                            <i class="fas fa-circle"></i>
                                            <span>حرف كبير واحد على الأقل</span>
                                        </div>
                                        <div class="requirement" id="number-req">
                                            <i class="fas fa-circle"></i>
                                            <span>رقم واحد على الأقل</span>
                                        </div>
                                        <div class="requirement" id="special-req">
                                            <i class="fas fa-circle"></i>
                                            <span>رمز خاص واحد على الأقل</span>
                                        </div>
                                    </div>
                                    @error('password')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">تأكيد كلمة المرور</label>
                                    <div class="position-relative">
                                        <input type="password" name="password_confirmation" id="confirm_password" required
                                            class="form-input form-input has-icon">
                                        <i class="fas fa-key form-icon"></i>
                                        <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
                                    </div>
                                    <div id="password-match" class="mt-2"></div>
                                    @error('password_confirmation')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- معلومات إضافية -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-plus-circle me-2"></i>معلومات إضافية (اختياري)
                        </h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">رقم الهاتف</label>
                                    <div class="position-relative">
                                        <input type="tel" name="phone" value="{{ old('phone') }}"
                                            class="form-input form-input has-icon">
                                        <i class="fas fa-phone form-icon"></i>
                                    </div>
                                    @error('phone')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">ملاحظات إضافية</label>
                            <textarea name="notes" class="form-input" rows="3">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    
                    <!-- أزرار التحكم -->
                    <div class="d-flex justify-content-between align-items-center mt-5 btn-container">
                        <a href="{{ route('admin.pos.index') }}" class="back-btn">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                        <button type="submit" class="submit-btn">
                            <i class="fas fa-save me-2"></i>حفظ نقطة البيع
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // تبديل حالة نقطة البيع
        document.querySelector('input[name="is_active"]').addEventListener('change', function() {
            const statusLabel = document.querySelector('.status-label');
            statusLabel.textContent = this.checked ? 'نشطة' : 'غير نشطة';
        });
        
        // إظهار/إخفاء كلمة المرور
        const togglePassword = document.querySelector('#togglePassword');
        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const password = document.querySelector('#password');
        const confirmPassword = document.querySelector('#confirm_password');
        
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
        
        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
        
        // التحقق من قوة كلمة المرور
        password.addEventListener('input', function() {
            const value = password.value;
            const strengthBar = document.getElementById('password-strength-bar');
            const requirements = {
                length: value.length >= 8,
                uppercase: /[A-Z]/.test(value),
                number: /[0-9]/.test(value),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(value)
            };
            
            // تحديث شريط القوة
            const strength = Object.values(requirements).filter(Boolean).length;
            strengthBar.className = 'password-strength-bar';
            strengthBar.classList.add(`strength-${strength}`);
            
            // تحديث متطلبات كلمة المرور
            document.getElementById('length-req').classList.toggle('valid', requirements.length);
            document.getElementById('uppercase-req').classList.toggle('valid', requirements.uppercase);
            document.getElementById('number-req').classList.toggle('valid', requirements.number);
            document.getElementById('special-req').classList.toggle('valid', requirements.special);
        });
        
        // التحقق من تطابق كلمتي المرور
        function checkPasswordMatch() {
            const passwordValue = password.value;
            const confirmValue = confirmPassword.value;
            const matchElement = document.getElementById('password-match');
            
            if (confirmValue === '') {
                matchElement.innerHTML = '';
                return;
            }
            
            if (passwordValue === confirmValue) {
                matchElement.innerHTML = '<span style="color:#198754;"><i class="fas fa-check-circle me-2"></i>كلمة المرور متطابقة</span>';
            } else {
                matchElement.innerHTML = '<span style="color:#dc3545;"><i class="fas fa-times-circle me-2"></i>كلمة المرور غير متطابقة</span>';
            }
        }
        
        password.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);
        
        // التحقق من النموذج قبل الإرسال
        document.getElementById('posForm').addEventListener('submit', function(e) {
            const passwordValue = password.value;
            const confirmValue = confirmPassword.value;
            
            // التأكد من تطابق كلمتي المرور
            if (passwordValue !== confirmValue) {
                e.preventDefault();
                alert('كلمة المرور غير متطابقة. يرجى التأكد من تطابق كلمتي المرور.');
                return false;
            }
            
            // التأكد من قوة كلمة المرور
            const requirements = {
                length: passwordValue.length >= 8,
                uppercase: /[A-Z]/.test(passwordValue),
                number: /[0-9]/.test(passwordValue),
                special: /[!@#$%^&*(),.?":{}|<>]/.test(passwordValue)
            };
            
            const strength = Object.values(requirements).filter(Boolean).length;
            if (strength < 3) {
                e.preventDefault();
                alert('كلمة المرور ضعيفة. يرجى اختيار كلمة مرور أقوى.');
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>