<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - كرت المفتاح</title>
    {{-- Tailwind CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center py-12">
    <div class="w-full max-w-md bg-white bg-opacity-90 rounded-lg shadow-lg p-8">
        
        <h2 class="text-2xl font-bold text-center mb-6">تسجيل الدخول - كرت المفتاح</h2>

        {{-- عرض رسائل الأخطاء --}}
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            {{-- البريد الإلكتروني --}}
            <div>
                <label for="email" class="block mb-1 font-medium">البريد الإلكتروني</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            {{-- كلمة المرور --}}
            <div>
                <label for="password" class="block mb-1 font-medium">كلمة المرور</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
            </div>

            {{-- تذكّرني ونسيت كلمة المرور --}}
            <div class="flex items-center justify-between">
                <label class="inline-flex items-center">
                    <input
                        type="checkbox"
                        name="remember"
                        class="form-checkbox h-4 w-4 text-blue-600"
                    >
                    <span class="ml-2 text-gray-700">تذكّرني</span>
                </label>
                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="text-sm text-blue-600 hover:underline"
                    >
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>

            {{-- زر الدخول --}}
            <button
                type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-3 px-4 rounded-lg hover:from-blue-700 hover:to-blue-800 transition duration-300"
            >
                تسجيل الدخول
            </button>
        </form>

        {{-- الجزء الأسفل: حقوق ونسخة --}}
        <div class="mt-6 text-center text-gray-600 text-sm">
            &copy; {{ date('Y') }} جميع الحقوق محفوظة – كرت المفتاح<br>
            الإصدار 1.0.0
        </div>
    </div>
</body>
</html>
