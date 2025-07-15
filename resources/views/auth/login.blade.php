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
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(8px);
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            transition: transform .3s, box-shadow .3s;
        }
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }
        .input-field {
            border: 2px solid #e2e8f0;
            padding: .75rem 1rem;
            transition: border-color .3s, box-shadow .3s;
        }
        .input-field:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(76,124,243,0.2);
            outline: none;
        }
        .btn-login {
            background: linear-gradient(135deg, var(--accent) 0%, #3a66d9 100%);
            color: #fff;
            font-weight: bold;
            transition: transform .2s, box-shadow .2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(76,124,243,0.3);
        }
        .logo-circle {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            border-radius: 9999px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            margin: 0 auto 1rem;
        }
        .input-icon { position: absolute; top: 50%; transform: translateY(-50%); color: #a0aec0; }
        .input-icon.right { right: 1rem; }
        .input-icon.left  { left: 1rem; cursor: pointer; }
    </style>
</head>
<body class="flex items-center justify-center px-4 py-12">
    <div class="login-card w-full max-w-md p-8">
        <div class="logo-circle mb-4">
            <i class="fas fa-key"></i>
        </div>
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-1">تسجيل الدخول</h2>
        <p class="text-center text-gray-600 mb-6">كرت المفتاح - نظام إدارة البطاقات الذكية</p>

        {{-- عرض الأخطاء العامة --}}
        @if($errors->any())
            <div class="mb-4 p-4 bg-red-50 text-red-700 rounded-lg border border-red-200 animate-fadeIn">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle mt-1 mr-2"></i>
                    <div>
                        <p class="font-medium mb-2">حدثت الأخطاء التالية:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            {{-- البريد الإلكتروني --}}
            <div class="relative">
                <label for="email" class="block mb-1 font-medium text-gray-700">البريد الإلكتروني</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    placeholder="مثال: user@example.com"
                    class="input-field w-full rounded-lg pr-12"
                >
                <i class="fas fa-envelope input-icon right"></i>
            </div>

            {{-- كلمة المرور --}}
            <div class="relative">
                <label for="password" class="block mb-1 font-medium text-gray-700">كلمة المرور</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    placeholder="••••••••"
                    class="input-field w-full rounded-lg pr-12"
                >
                <i class="fas fa-lock input-icon right"></i>
                <span class="input-icon left" onclick="togglePassword()">
                    <i class="fas fa-eye"></i>
                </span>
            </div>

            {{-- تذكّرني --}}
            <div class="flex items-center justify-between">
                <label class="inline-flex items-center">
                    <input
                        id="remember"
                        name="remember"
                        type="checkbox"
                        {{ old('remember') ? 'checked' : '' }}
                        class="form-checkbox h-4 w-4 text-blue-600"
                    >
                    <span class="mr-2 text-gray-700">تذكّرني</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:underline">نسيت كلمة المرور؟</a>
            </div>

            {{-- زر الدخول --}}
            <button type="submit" class="btn-login w-full py-3 rounded-lg flex justify-center items-center">
                <i class="fas fa-sign-in-alt ml-2"></i> تسجيل الدخول
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-gray-200 text-center text-gray-600 text-sm">
            <p class="mb-2">&copy; {{ date('Y') }} كرت المفتاح. جميع الحقوق محفوظة.</p>
            <div class="flex justify-center space-x-4 text-xl">
                <a href="#" class="hover:text-blue-600"><i class="fab fa-facebook"></i></a>
                <a href="#" class="hover:text-blue-400"><i class="fab fa-twitter"></i></a>
                <a href="#" class="hover:text-red-600"><i class="fab fa-instagram"></i></a>
            </div>
            <p class="mt-2">الإصدار 1.0.0</p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const pw = document.getElementById('password');
            const icon = document.querySelector('.input-icon.left i');
            if (pw.type === 'password') {
                pw.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pw.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
        document.addEventListener('DOMContentLoaded',()=> {
            document.querySelectorAll('.input-field').forEach(input=>{
                input.addEventListener('focus',()=> input.classList.add('ring-2','ring-blue-300'));
                input.addEventListener('blur', ()=> input.classList.remove('ring-2','ring-blue-300'));
            });
        });
    </script>
</body>
</html>
