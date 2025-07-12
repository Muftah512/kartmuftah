<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - كرت المفتاح</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #1e3c72;
            --secondary: #2a5298;
            --accent: #4c7cf3;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            font-family: 'Tajawal', sans-serif;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            transform: translateY(0);
        }
        
        .login-card:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            transform: translateY(-5px);
        }
        
        .input-field {
            transition: all 0.3s ease;
            border: 2px solid #e2e8f0;
            padding: 12px 15px;
            padding-right: 45px;
        }
        
        .input-field:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(76, 124, 243, 0.2);
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--accent) 0%, #3a66d9 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(76, 124, 243, 0.3);
        }
        
        .btn-login:active {
            transform: translateY(1px);
        }
        
        .btn-login::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(30deg) translate(0, -150%);
            transition: all 0.8s ease;
        }
        
        .btn-login:hover::after {
            transform: rotate(30deg) translate(0, 150%);
        }
        
        .error-box {
            animation: fadeIn 0.4s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .logo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .input-icon {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            color: #a0aec0;
        }
        
        .toggle-password {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
        }
        
        .toggle-password:hover {
            color: var(--accent);
        }
    </style>
</head>
<body class="flex items-center justify-center py-6 sm:py-12 px-4">
    <div class="login-card w-full max-w-md p-8">
        <!-- شعار التطبيق -->
        <div class="logo-container">
            <div class="logo">
                <i class="fas fa-key"></i>
            </div>
        </div>
        
        <h2 class="text-2xl font-bold text-center mb-1 text-gray-800">تسجيل الدخول</h2>
        <p class="text-center text-gray-600 mb-6">كرت المفتاح - نظام إدارة البطاقات الذكية</p>

        <!-- رسائل الأخطاء -->
        <div class="error-box mb-4 p-4 bg-red-50 text-red-700 rounded-lg border border-red-200 hidden">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle mt-1 mr-2"></i>
                <div>
                    <div class="font-medium">حدث خطأ!</div>
                    <ul class="mt-1 list-disc list-inside errors-list">
                        <!-- سيتم إضافة الأخطاء هنا ديناميكيًا -->
                    </ul>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- حقل البريد الإلكتروني -->
            <div class="relative">
                <label for="email" class="block mb-2 font-medium text-gray-700">البريد الإلكتروني</label>
                <div class="relative">
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="user@example.com"
                        required
                        autofocus
                        class="input-field w-full rounded-lg focus:outline-none"
                        placeholder="ادخل بريدك الإلكتروني"
                    >
                    <i class="fas fa-envelope input-icon"></i>
                </div>
            </div>

            <!-- حقل كلمة المرور -->
            <div class="relative">
                <label for="password" class="block mb-2 font-medium text-gray-700">كلمة المرور</label>
                <div class="relative">
                    <input
                        id="password"
                        name="password"
                        type="password"
                        value="password123"
                        required
                        class="input-field w-full rounded-lg focus:outline-none"
                        placeholder="ادخل كلمة المرور"
                    >
                    <i class="fas fa-lock input-icon"></i>
                    <span class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>

            <!-- تذكّرني ونسيت كلمة المرور -->
            <div class="flex items-center justify-between">
                <label class="inline-flex items-center">
                    <input
                        type="checkbox"
                        name="remember"
                        class="form-checkbox h-4 w-4 text-blue-600"
                        checked
                    >
                    <span class="ml-2 text-gray-700">تذكّرني</span>
                </label>
                <a
                    href="#"
                    class="text-sm text-blue-600 hover:text-blue-800 hover:underline transition"
                >
                    نسيت كلمة المرور؟
                </a>
            </div>

            <!-- زر الدخول -->
            <button
                type="submit"
                class="btn-login w-full text-white font-bold py-3 px-4 rounded-lg transition"
            >
                <i class="fas fa-sign-in-alt mr-2"></i> تسجيل الدخول
            </button>
        </form>

        <!-- الجزء الأسفل: حقوق ونسخة -->
        <div class="mt-8 pt-5 border-t border-gray-200 text-center text-gray-600 text-sm">
            <div class="mb-2">جميع الحقوق محفوظة &copy; {{ date('Y') }} - كرت المفتاح</div>
            <div class="flex justify-center space-x-3">
                <a href="#" class="text-gray-500 hover:text-blue-600 transition"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-gray-500 hover:text-blue-400 transition"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-gray-500 hover:text-red-600 transition"><i class="fab fa-instagram"></i></a>
            </div>
            <div class="mt-2">الإصدار 1.0.0</div>
        </div>
    </div>

    <script>
        // محاكاة رسائل الخطأ
        const errors = [
            "البريد الإلكتروني غير صحيح",
            "كلمة المرور يجب أن تحتوي على 8 أحرف على الأقل"
        ];
        
        // عرض رسائل الخطأ إذا كانت موجودة
        if (errors.length > 0) {
            const errorBox = document.querySelector('.error-box');
            const errorsList = document.querySelector('.errors-list');
            
            errorBox.classList.remove('hidden');
            
            errors.forEach(error => {
                const li = document.createElement('li');
                li.textContent = error;
                errorsList.appendChild(li);
            });
        }
        
        // تبديل عرض كلمة المرور
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // تأثيرات عند التحميل
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-blue-300');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-blue-300');
                });
            });
        });
    </script>
</body>
</html>