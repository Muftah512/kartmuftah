@extends('layouts.admin')

@section('title', 'تعديل المستخدم: ' . $user->name)

@section('content')
<div class="container-fluid">
  <h1 class="mb-4">تعديل مستخدم</h1>

  {{-- عرض الأخطاء العامة --}}
  @if($errors->any())
    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded">
      <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card">
    <div class="card-body">
      <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row g-3">
          {{-- الاسم الكامل --}}
          <div class="col-md-6">
            <label for="name" class="form-label">الاسم الكامل</label>
            <input type="text"
                   name="name"
                   id="name"
                   class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $user->name) }}"
                   required
                   autofocus>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- البريد الإلكتروني --}}
          <div class="col-md-6">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email"
                   name="email"
                   id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email', $user->email) }}"
                   required>
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <hr class="my-4">

        {{-- اختيار الدور --}}
        <div class="mb-3">
          <label for="role" class="form-label">دور المستخدم</label>
          <select name="role"
                  id="role"
                  class="form-select @error('role') is-invalid @enderror"
                  required>
            <option value="">— اختر دوراً —</option>
            @foreach($roles as $role)
              <option value="{{ $role }}" @selected(old('role', $user->role) == $role)>{{ ucfirst($role) }}</option>
            @endforeach
          </select>
          @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- حقل نقطة البيع (يظهر فقط عند اختيار دور pos) --}}
        <div class="mb-3" id="pos-field" style="display: {{ old('role', $user->role) === 'pos' ? 'block' : 'none' }};">
          <label for="point_of_sale_id" class="form-label">نقطة البيع المرتبطة</label>
          <select name="point_of_sale_id"
                  id="point_of_sale_id"
                  class="form-select @error('point_of_sale_id') is-invalid @enderror">
            <option value="">— اختر نقطة البيع —</option>
            @foreach($pointsOfSale as $pos)
              <option value="{{ $pos->id }}" @selected(old('point_of_sale_id', $user->point_of_sale_id) == $pos->id)>{{ $pos->name }}</option>
            @endforeach
          </select>
          @error('point_of_sale_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <hr class="my-4">

        {{-- تغيير كلمة المرور (اختياري) --}}
        <div class="mb-2 form-check form-switch">
          <input class="form-check-input" type="checkbox" id="toggle-change-password">
          <label class="form-check-label" for="toggle-change-password">تغيير كلمة المرور</label>
        </div>

        <div id="password-fields" style="display: none;">
          <div class="row g-3">
            {{-- كلمة المرور الجديدة --}}
            <div class="col-md-6">
              <label for="password" class="form-label">كلمة المرور الجديدة</label>
              <input type="password"
                     name="password"
                     id="password"
                     class="form-control @error('password') is-invalid @enderror"
                     autocomplete="new-password"
                     placeholder="اتركه فارغًا إذا لا تريد التغيير">
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">اترك الحقل فارغًا إن لم ترغب بتغيير كلمة المرور.</div>
            </div>

            {{-- تأكيد كلمة المرور --}}
            <div class="col-md-6">
              <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
              <input type="password"
                     name="password_confirmation"
                     id="password_confirmation"
                     class="form-control @error('password_confirmation') is-invalid @enderror"
                     autocomplete="new-password">
              @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">رجوع</a>
          <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const posField   = document.getElementById('pos-field');
    const posSelect  = document.getElementById('point_of_sale_id');

    const pwToggle   = document.getElementById('toggle-change-password');
    const pwFields   = document.getElementById('password-fields');
    const pwInput    = document.getElementById('password');
    const pwConfirm  = document.getElementById('password_confirmation');

    // إظهار/إخفاء حقل نقطة البيع حسب الدور
    function togglePosField() {
      const isPos = roleSelect.value === 'pos';
      posField.style.display = isPos ? 'block' : 'none';
      if (!isPos && posSelect) posSelect.value = '';
    }
    roleSelect.addEventListener('change', togglePosField);
    togglePosField();

    // فتح حقول كلمة المرور تلقائياً إذا وُجدت أخطاء أو قيم قديمة
    const hadPasswordError = {{ $errors->has('password') || $errors->has('password_confirmation') ? 'true' : 'false' }};
    const hadOldPassword   = {{ old('password') ? 'true' : 'false' }};
    if (hadPasswordError || hadOldPassword) {
      pwToggle.checked = true;
    }

    function togglePasswordFields() {
      const on = pwToggle.checked;
      pwFields.style.display = on ? 'block' : 'none';
      if (!on) {
        if (pwInput) pwInput.value = '';
        if (pwConfirm) pwConfirm.value = '';
      }
    }
    pwToggle.addEventListener('change', togglePasswordFields);
    togglePasswordFields();
  });
</script>
@endpush
@endsection
