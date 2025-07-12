@extends('layouts.admin')

@section('title', 'إنشاء مستخدم جديد')

@section('content')
<div class="container-fluid">
  <h1 class="mb-4">إنشاء مستخدم جديد</h1>

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
      <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="row g-3">
          {{-- الاسم الكامل --}}
          <div class="col-md-6">
            <label for="name" class="form-label">الاسم الكامل</label>
            <input type="text" 
                   name="name" 
                   id="name" 
                   class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name') }}" 
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
                   value="{{ old('email') }}" 
                   required>
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- كلمة المرور --}}
          <div class="col-md-6">
            <label for="password" class="form-label">كلمة المرور</label>
            <input type="password" 
                   name="password" 
                   id="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   required>
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          {{-- تأكيد كلمة المرور --}}
          <div class="col-md-6">
            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
            <input type="password" 
                   name="password_confirmation" 
                   id="password_confirmation" 
                   class="form-control @error('password_confirmation') is-invalid @enderror" 
                   required>
            @error('password_confirmation')
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
              <option value="{{ $role }}" @selected(old('role') == $role)>{{ ucfirst($role) }}</option>
            @endforeach
          </select>
          @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        {{-- حقل نقطة البيع (يظهر فقط عند اختيار دور pos) --}}
        <div class="mb-3" id="pos-field" style="display: {{ old('role') === 'pos' ? 'block' : 'none' }};">
          <label for="point_of_sale_id" class="form-label">نقطة البيع المرتبطة</label>
          <select name="point_of_sale_id" 
                  id="point_of_sale_id" 
                  class="form-select @error('point_of_sale_id') is-invalid @enderror">
            <option value="">— اختر نقطة البيع —</option>
            @foreach($pointsOfSale as $pos)
              <option value="{{ $pos->id }}" @selected(old('point_of_sale_id') == $pos->id)>{{ $pos->name }}</option>
            @endforeach
          </select>
          @error('point_of_sale_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="text-end">
          <button type="submit" class="btn btn-primary">
            إنشاء المستخدم
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- سكربت لإظهار/إخفاء حقل نقطة البيع --}}
@push('scripts')
<script>
  document.getElementById('role').addEventListener('change', function() {
    const posField = document.getElementById('pos-field');
    posField.style.display = (this.value === 'pos') ? 'block' : 'none';
  });
</script>
@endpush
@endsection