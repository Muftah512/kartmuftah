
{{-- Blade view: admin/pos/create.blade.php --}}
{{-- تم تعديل الحقول لتفادي أخطاء قاعدة البيانات وضمان إرسال كل الحقول المطلوبة --}}

@extends('layouts.admin')

@section('title', 'إضافة نقطة بيع جديدة')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">إضافة نقطة بيع جديدة</h2>

    <form action="{{ route('admin.pos.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">اسم نقطة البيع</label>
            <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">الموقع</label>
            <input type="text" name="location" id="location" class="form-control" required value="{{ old('location') }}">
            @error('location') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="accountant_id" class="form-label">المحاسب المسؤول</label>
            <select name="accountant_id" id="accountant_id" class="form-select">
                <option value="">— اختر المحاسب —</option>
                @foreach($accountants as $acc)
                    <option value="{{ $acc->id }}" @selected(old('accountant_id') == $acc->id)>
                        {{ $acc->name }}
                    </option>
                @endforeach
            </select>
            @error('accountant_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">البريد الإلكتروني</label>
            <input type="email" name="email" id="email" class="form-control" required value="{{ old('email') }}">
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">كلمة المرور</label>
            <input type="password" name="password" id="password" class="form-control" required>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">رقم الهاتف (اختياري)</label>
            <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
        </div>

        <input type="hidden" name="is_active" value="0">
        <div class="form-check form-switch mb-3">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" checked>
            <label class="form-check-label" for="is_active">نشطة</label>
        </div>

        <button type="submit" class="btn btn-primary">حفظ</button>
        <a href="{{ route('admin.pos.index') }}" class="btn btn-secondary">إلغاء</a>
    </form>
</div>
@endsection
