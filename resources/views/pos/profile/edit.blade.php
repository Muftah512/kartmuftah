@extends($layout)

@section('title', 'إعدادات الحساب')

@section('breadcrumb')
  <li class="inline-flex items-center">
    <i class="fas fa-chevron-left mx-2 text-gray-400"></i>
    <span class="text-sm font-medium text-gray-500">الإعدادات</span>
  </li>
@endsection

@section('content')
<div class="container mx-auto px-2 sm:px-0">

    {{-- تنبيهات الجلسة --}}
    @if(session('success'))
      <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        {{ session('error') }}
      </div>
    @endif

    {{-- أخطاء التحقق --}}
    @if ($errors->any())
      <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $error)
            <li class="mb-1">{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

      {{-- البطاقة 1: البيانات العامة --}}
      <div class="md:col-span-2 bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-1">البيانات العامة</h2>
        <p class="text-sm text-gray-500 mb-4">حدّث اسم الحساب، وبشكل اختياري اسم نقطة البيع إن وُجدت.</p>

        <form action="{{ route($prefix.'.profile.update') }}" method="POST" class="space-y-4" novalidate>
          @csrf
          @method('PUT')

          <div>
            <label class="block mb-1 font-medium">اسم الحساب (المستخدم)</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   autocomplete="name"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring">
          </div>


          <div class="pt-2">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg">
              حفظ التغييرات
            </button>
          </div>
        </form>
      </div>

      {{-- البطاقة 2: الصورة الشخصية (خاصة بالمستخدم فقط) --}}
      <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-1">الصورة الشخصية</h2>
        <p class="text-sm text-gray-500 mb-4">تُخزَّن الصورة بشكل خاص على الخادم ولا يراها إلا صاحب الحساب. إن لم تُرفع صورة سنعرض أفاتارًا بحروف الاسم تلقائيًا.</p>

        <div class="flex items-center gap-4">
          <img src="{{ route($prefix.'.profile.avatar.show') }}?t={{ now()->timestamp }}"
               alt="صورة {{ $user->name }}"
               class="w-20 h-20 rounded-full object-cover border" loading="lazy">

          <form action="{{ route($prefix.'.profile.avatar') }}" method="POST" enctype="multipart/form-data" class="flex-1">
            @csrf

            <input type="file" name="avatar" accept="image/png,image/jpeg,image/webp"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring mb-3" required>

            <div class="flex gap-2">
              <button type="submit"
                      class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded-lg">
                تحديث الصورة
              </button>

              <button form="deleteAvatarForm" type="submit"
                      class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                حذف الصورة
              </button>
            </div>
          </form>

          <form id="deleteAvatarForm" action="{{ route($prefix.'.profile.avatar.delete') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
          </form>
        </div>

        <p class="text-xs text-gray-500 mt-3">
          الحد الأقصى 2MB — الصيغ المسموحة: JPG, JPEG, PNG, WEBP.
        </p>
      </div>

      {{-- البطاقة 3: تغيير كلمة المرور --}}
      <div class="md:col-span-3 bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-1">تغيير كلمة المرور</h2>
        <p class="text-sm text-gray-500 mb-4">لأمانك، نتحقق من كلمة المرور الحالية قبل قبول كلمة جديدة.</p>

        <form action="{{ route($prefix.'.profile.password') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4" novalidate>
          @csrf
          @method('PUT')

          <div>
            <label class="block mb-1 font-medium">كلمة المرور الحالية</label>
            <input type="password" name="current_password" required
                   autocomplete="current-password"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring">
          </div>

          <div>
            <label class="block mb-1 font-medium">كلمة المرور الجديدة</label>
            <input type="password" name="password" required
                   autocomplete="new-password"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring">
          </div>

          <div>
            <label class="block mb-1 font-medium">تأكيد كلمة المرور الجديدة</label>
            <input type="password" name="password_confirmation" required
                   autocomplete="new-password"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring">
          </div>

          <div class="md:col-span-3 pt-2">
            <button type="submit"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg">
              تحديث كلمة المرور
            </button>
          </div>
        </form>
      </div>

    </div>
</div>
@endsection
