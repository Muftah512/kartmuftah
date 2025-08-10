<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($package) ? 'تعديل الباقة' : 'إنشاء باقة جديدة' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4361ee',
                        secondary: '#3f37c9',
                        success: '#4cc9f0',
                        light: '#f8f9fa',
                        dark: '#212529',
                    },
                    fontFamily: {
                        'tajawal': ['Tajawal', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        * {
            font-family: 'Tajawal', sans-serif;
        }
        
        .form-section {
            background: linear-gradient(to right, #f0f4ff, #f8f9ff);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #4361ee;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        .form-title {
            position: relative;
            padding-right: 15px;
            margin-bottom: 20px;
            color: #2d3748;
            font-weight: 600;
        }
        
        .form-title:after {
            content: "";
            position: absolute;
            right: 0;
            bottom: -5px;
            width: 50px;
            height: 3px;
            background: #4361ee;
            border-radius: 3px;
        }
        
        .feature-badge {
            background-color: #e0e7ff;
            color: #4f46e5;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            margin: 0 5px 5px 0;
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
    </style>
</head>
<body class="bg-gray-50 font-tajawal">
    <div class="min-h-screen">
        <!-- شريط التنقل العلوي -->
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <div class="text-primary text-xl font-bold">
                            <i class="fas fa-wifi mr-2"></i>نظام إدارة الباقات
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="ml-3 relative">
                            <div class="flex items-center space-x-3">
                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-10 h-10"></div>
                                <div>
                                    <div class="font-medium text-gray-800">المشرف</div>
                                    <div class="text-sm text-gray-500">مدير النظام</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- المحتوى الرئيسي -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <!-- العنوان والمسارات -->
                <div class="px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">
                            <i class="fas fa-box-open mr-2 text-primary"></i>
                            {{ isset($package) ? 'تعديل الباقة: ' . $package->name : 'إنشاء باقة جديدة' }}
                        </h2>
                        <nav class="flex mt-2" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-1">
                                <li>
                                    <div class="flex items-center">
                                        <a href="#" class="text-sm font-medium text-gray-500 hover:text-gray-700">لوحة التحكم</a>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <a href="{{ route('admin.packages.index') }}" class="mr-2 text-sm font-medium text-gray-500 hover:text-gray-700">الباقات</a>
                                    </div>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="mr-2 text-sm font-medium text-primary">
                                            {{ isset($package) ? 'تعديل' : 'إنشاء' }}
                                        </span>
                                    </div>
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left ml-2"></i>
                            العودة للقائمة
                        </a>
                    </div>
                </div>

                <!-- المحتوى -->
                <div class="px-6 py-8">
                    <!-- عرض الأخطاء -->
                    @if($errors->any())
                        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="mr-3">
                                    <h3 class="text-sm font-medium">حدث خطأ!</h3>
                                    <div class="mt-2 text-sm">
                                        <ul class="list-disc mr-5 space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- الفورم -->
                    <form action="{{ isset($package) ? route('admin.packages.update', $package->id) : route('admin.packages.store') }}" method="POST" class="space-y-6">
                        @csrf
                        @if(isset($package))
                            @method('PUT')
                        @endif

                        <!-- قسم المعلومات الأساسية -->
                        <div class="form-section">
                            <h3 class="form-title">المعلومات الأساسية</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- اسم الباقة -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">اسم الباقة</label>
                                    <div class="relative">
                                        <div class="input-icon">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <input 
                                            type="text" 
                                            id="name" 
                                            name="name" 
                                            value="{{ old('name', $package->name ?? '') }}" 
                                            required
                                            class="w-full pr-3 pl-10 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                            placeholder="مثال: باقة الإنترنت الفضية">
                                    </div>
                                </div>
                                
                                <!-- السعر -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">السعر (ريال سعودي)</label>
                                    <div class="relative">
                                        <div class="input-icon">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <input 
                                            type="number" 
                                            id="price" 
                                            name="price" 
                                            value="{{ old('price', $package->price ?? '') }}" 
                                            step="0.01"
                                            required
                                            class="w-full pr-3 pl-10 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                            placeholder="مثال: 99.99">
                                    </div>
                                </div>
                                
                                <!-- حجم التحميل -->
                                <div>
                                    <label for="size_mb" class="block text-sm font-medium text-gray-700 mb-1">حجم التحميل (ميجابايت)</label>
                                    <div class="relative">
                                        <div class="input-icon">
                                            <i class="fas fa-database"></i>
                                        </div>
                                        <input 
                                            type="number" 
                                            id="size_mb" 
                                            name="size_mb" 
                                            value="{{ old('size_mb', $package->size_mb ?? '') }}" 
                                            required
                                            class="w-full pr-3 pl-10 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                            placeholder="مثال: 1024">
                                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">MB</div>
                                    </div>
                                </div>
                                
                                <!-- الصلاحية -->
                                <div>
                                    <label for="validity_days" class="block text-sm font-medium text-gray-700 mb-1">مدة الصلاحية (أيام)</label>
                                    <div class="relative">
                                        <div class="input-icon">
                                            <i class="fas fa-calendar-day"></i>
                                        </div>
                                        <input 
                                            type="number" 
                                            id="validity_days" 
                                            name="validity_days" 
                                            value="{{ old('validity_days', $package->validity_days ?? '') }}" 
                                            required
                                            class="w-full pr-3 pl-10 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                            placeholder="مثال: 30">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- قسم إعدادات MikroTik -->
                        <div class="form-section">
                            <h3 class="form-title">إعدادات MikroTik</h3>
                            <div class="grid grid-cols-1 gap-6">
                                <!-- ملف التعريف -->
                                <div>
                                    <label for="mikrotik_profile" class="block text-sm font-medium text-gray-700 mb-1">ملف التعريف</label>
                                    <div class="relative">
                                        <div class="input-icon">
                                            <i class="fas fa-user-shield"></i>
                                        </div>
                                        {{--
                                            When profiles are available from the MikroTik router, show them in a
                                            dropdown.  Otherwise display a warning and allow manual entry.
                                        --}}
                                        @if(isset($mikrotikProfiles) && count($mikrotikProfiles) > 0)
                                            <select
                                                id="mikrotik_profile"
                                                name="mikrotik_profile"
                                                required
                                                class="w-full pr-3 pl-10 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                                <option value="">-- اختر ملف تعريف --</option>
                                                @foreach($mikrotikProfiles as $profile)
                                                    <option value="{{ $profile['name'] }}"
                                                        {{ old('mikrotik_profile', $package->mikrotik_profile ?? '') == $profile['name'] ? 'selected' : '' }}>
                                                        {{ $profile['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4 warning-box">
                                                <div class="flex">
                                                    <div class="flex-shrink-0">
                                                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div class="mr-3">
                                                        <p class="text-sm text-yellow-700">
                                                            لا يمكن الاتصال بخادم MikroTik. يرجى التحقق من الإعدادات.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            <input
                                                type="text"
                                                id="mikrotik_profile"
                                                name="mikrotik_profile"
                                                value="{{ old('mikrotik_profile', $package->mikrotik_profile ?? '') }}"
                                                required
                                                class="w-full pr-3 pl-10 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                                placeholder="اسم ملف التعريف في MikroTik">
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- الميزات الإضافية -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">ميزات إضافية</label>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="features[]" value="unlimited_peak" class="rounded border-gray-300 text-primary focus:ring-primary">
                                            <span class="mr-2 text-sm text-gray-700">سرعة غير محدودة في أوقات الذروة</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="checkbox" name="features[]" value="free_setup" class="rounded border-gray-300 text-primary focus:ring-primary">
                                            <span class="mr-2 text-sm text-gray-700">تركيب مجاني</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="checkbox" name="features[]" value="priority_support" class="rounded border-gray-300 text-primary focus:ring-primary">
                                            <span class="mr-2 text-sm text-gray-700">دعم فني مميز</span>
                                        </label>
                                        
                                        <label class="flex items-center">
                                            <input type="checkbox" name="features[]" value="wifi_router" class="rounded border-gray-300 text-primary focus:ring-primary">
                                            <span class="mr-2 text-sm text-gray-700">راوتر WiFi مجاني</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- قسم الإعدادات المتقدمة -->
                        <div class="form-section">
                            <h3 class="form-title">الإعدادات المتقدمة</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- حالة الباقة -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">حالة الباقة</label>
                                    <select id="status" name="status" class="w-full py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                        <option value="active" {{ (old('status', $package->status ?? '') == 'active' ? 'selected' : '') }}>نشطة</option>
                                        <option value="inactive" {{ (old('status', $package->status ?? '') == 'inactive' ? 'selected' : '') }}>غير نشطة</option>
                                    </select>
                                </div>
                                
                                <!-- سرعة التحميل -->
                                <div>
                                    <label for="download_speed" class="block text-sm font-medium text-gray-700 mb-1">سرعة التحميل (Mbps)</label>
                                    <div class="relative">
                                        <div class="input-icon">
                                            <i class="fas fa-download"></i>
                                        </div>
                                        <input 
                                            type="number" 
                                            id="download_speed" 
                                            name="download_speed" 
                                            value="{{ old('download_speed', $package->download_speed ?? '10') }}" 
                                            class="w-full pr-3 pl-10 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                            placeholder="مثال: 10">
                                    </div>
                                </div>
                                
                                <!-- سرعة الرفع -->
                                <div>
                                    <label for="upload_speed" class="block text-sm font-medium text-gray-700 mb-1">سرعة الرفع (Mbps)</label>
                                    <div class="relative">
                                        <div class="input-icon">
                                            <i class="fas fa-upload"></i>
                                        </div>
                                        <input 
                                            type="number" 
                                            id="upload_speed" 
                                            name="upload_speed" 
                                            value="{{ old('upload_speed', $package->upload_speed ?? '5') }}" 
                                            class="w-full pr-3 pl-10 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                            placeholder="مثال: 5">
                                    </div>
                                </div>
                                
                                <!-- عدد الأجهزة -->
                                <div>
                                    <label for="device_limit" class="block text-sm font-medium text-gray-700 mb-1">حد الأجهزة المتصلة</label>
                                    <div class="relative">
                                        <div class="input-icon">
                                            <i class="fas fa-laptop"></i>
                                        </div>
                                        <input 
                                            type="number" 
                                            id="device_limit" 
                                            name="device_limit" 
                                            value="{{ old('device_limit', $package->device_limit ?? '5') }}" 
                                            class="w-full pr-3 pl-10 py-2.5 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                            placeholder="مثال: 5">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- معاينة الباقة -->
                        <div class="form-section">
                            <h3 class="form-title">معاينة الباقة</h3>
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                                <div class="flex flex-col md:flex-row justify-between">
                                    <div class="mb-4 md:mb-0">
                                        <h4 class="text-xl font-bold text-gray-800" id="preview-name">
                                            {{ old('name', $package->name ?? 'اسم الباقة') }}
                                        </h4>
                                        <div class="flex items-center mt-1">
                                            <span class="text-3xl font-bold text-primary" id="preview-price">
                                                {{ old('price', $package->price ?? '0') }}
                                            </span>
                                            <span class="text-gray-600 mr-2">ريال/شهر</span>
                                        </div>
                                    </div>
                                    <div>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800" id="preview-status">
                                            {{ (old('status', $package->status ?? 'active') == 'active' ? 'نشطة' : 'غير نشطة') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mt-6 grid grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <div class="bg-blue-100 p-2 rounded-lg">
                                            <i class="fas fa-database text-blue-600"></i>
                                        </div>
                                        <div class="mr-3">
                                            <div class="text-sm text-gray-500">حجم التحميل</div>
                                            <div class="font-medium" id="preview-size">{{ old('size_mb', $package->size_mb ?? '0') }} ميجابايت</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <div class="bg-indigo-100 p-2 rounded-lg">
                                            <i class="fas fa-calendar-day text-indigo-600"></i>
                                        </div>
                                        <div class="mr-3">
                                            <div class="text-sm text-gray-500">مدة الصلاحية</div>
                                            <div class="font-medium" id="preview-validity">{{ old('validity_days', $package->validity_days ?? '0') }} يوم</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <div class="bg-purple-100 p-2 rounded-lg">
                                            <i class="fas fa-download text-purple-600"></i>
                                        </div>
                                        <div class="mr-3">
                                            <div class="text-sm text-gray-500">سرعة التحميل</div>
                                            <div class="font-medium" id="preview-download">{{ old('download_speed', $package->download_speed ?? '10') }} Mbps</div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <div class="bg-pink-100 p-2 rounded-lg">
                                            <i class="fas fa-upload text-pink-600"></i>
                                        </div>
                                        <div class="mr-3">
                                            <div class="text-sm text-gray-500">سرعة الرفع</div>
                                            <div class="font-medium" id="preview-upload">{{ old('upload_speed', $package->upload_speed ?? '5') }} Mbps</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-6">
                                    <div class="text-sm font-medium text-gray-700 mb-2">ملف التعريف:</div>
                                    <div class="bg-gray-100 px-4 py-2 rounded-lg inline-block">
                                        <i class="fas fa-user-shield text-gray-600 mr-2"></i>
                                        <span id="preview-profile">{{ old('mikrotik_profile', $package->mikrotik_profile ?? '') }}</span>
                                    </div>
                                </div>
                                
                                <div class="mt-6">
                                    <div class="text-sm font-medium text-gray-700 mb-2">الميزات الإضافية:</div>
                                    <div class="flex flex-wrap" id="preview-features">
                                        <span class="feature-badge">
                                            <i class="fas fa-bolt mr-1"></i> سرعة غير محدودة في أوقات الذروة
                                        </span>
                                        <span class="feature-badge">
                                            <i class="fas fa-cog mr-1"></i> تركيب مجاني
                                        </span>
                                        <span class="feature-badge">
                                            <i class="fas fa-headset mr-1"></i> دعم فني مميز
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- أزرار التحكم -->
                        <div class="flex justify-between pt-6 border-t border-gray-200">
                            <div>
                                <button type="reset" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium">
                                    <i class="fas fa-redo mr-2"></i> إعادة تعيين
                                </button>
                            </div>
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.packages.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium">
                                    <i class="fas fa-times mr-2"></i> إلغاء
                                </a>
                                <button type="submit" class="px-6 py-2.5 bg-primary hover:bg-secondary text-white rounded-lg font-medium transition duration-300">
                                    @if(isset($package))
                                        <i class="fas fa-sync mr-2"></i> تحديث الباقة
                                    @else
                                        <i class="fas fa-save mr-2"></i> حفظ الباقة الجديدة
                                    @endif
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // تحديث المعاينة في الوقت الحقيقي
        document.addEventListener('DOMContentLoaded', function() {
            // الحقول التي يتم تحديث معاينتها
            const fields = [
                {id: 'name', preview: 'preview-name'},
                {id: 'price', preview: 'preview-price'},
                {id: 'size_mb', preview: 'preview-size', format: val => val + ' ميجابايت'},
                {id: 'validity_days', preview: 'preview-validity', format: val => val + ' يوم'},
                {id: 'mikrotik_profile', preview: 'preview-profile'},
                {id: 'download_speed', preview: 'preview-download', format: val => val + ' Mbps'},
                {id: 'upload_speed', preview: 'preview-upload', format: val => val + ' Mbps'},
                {id: 'status', preview: 'preview-status', format: val => val === 'active' ? 'نشطة' : 'غير نشطة'}
            ];
            
            // إضافة مستمعات الأحداث
            fields.forEach(field => {
                const input = document.getElementById(field.id);
                const preview = document.getElementById(field.preview);
                
                if (input && preview) {
                    input.addEventListener('input', function() {
                        let value = this.value;
                        
                        // تطبيق التنسيق إذا كان موجوداً
                        if (field.format) {
                            value = field.format(value);
                        }
                        
                        preview.textContent = value;
                        
                        // تغيير لون حالة الباقة
                        if (field.id === 'status') {
                            preview.className = this.value === 'active' ? 
                                'px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800' :
                                'px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800';
                        }
                    });
                }
            });
            
            // تحديث الميزات الإضافية
            document.querySelectorAll('input[name="features[]"]').forEach(checkbox => {
                checkbox.addEventListener('change', updateFeaturesPreview);
            });
            
            function updateFeaturesPreview() {
                const container = document.getElementById('preview-features');
                container.innerHTML = '';
                
                document.querySelectorAll('input[name="features[]"]:checked').forEach(checkbox => {
                    let featureText = '';
                    
                    switch(checkbox.value) {
                        case 'unlimited_peak':
                            featureText = '<i class="fas fa-bolt mr-1"></i> سرعة غير محدودة في أوقات الذروة';
                            break;
                        case 'free_setup':
                            featureText = '<i class="fas fa-cog mr-1"></i> تركيب مجاني';
                            break;
                        case 'priority_support':
                            featureText = '<i class="fas fa-headset mr-1"></i> دعم فني مميز';
                            break;
                        case 'wifi_router':
                            featureText = '<i class="fas fa-wifi mr-1"></i> راوتر WiFi مجاني';
                            break;
                    }
                    
                    if (featureText) {
                        const badge = document.createElement('span');
                        badge.className = 'feature-badge';
                        badge.innerHTML = featureText;
                        container.appendChild(badge);
                    }
                });
                
                if (container.children.length === 0) {
                    const empty = document.createElement('span');
                    empty.className = 'text-gray-500 italic';
                    empty.textContent = 'لم يتم تحديد ميزات إضافية';
                    container.appendChild(empty);
                }
            }
            
            // تحديث أولي للمعاينة
            updateFeaturesPreview();
        });
    </script>
</body>
</html>