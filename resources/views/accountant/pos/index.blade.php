@extends('layouts.app')

@section('content')
<div class="container">
    <h1>إدارة نقاط البيع</h1>
    <a href="{{ route('accountant.pos.create') }}" class="btn btn-primary mb-3">إنشاء نقطة بيع جديدة</a>
    
    <div class="card">
        <div class="card-body">
{{-- ... داخل الـ card-body --}}
<div class="d-flex justify-content-between mb-3">
    <div>
        <a href="{{ route('accountant.pos.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إنشاء نقطة بيع جديدة
        </a>
    </div>
    <div>
        <a href="{{ route('accountant.reports.pos') }}" class="btn btn-success me-2">
            <i class="fas fa-file-pdf"></i> تصدير PDF
        </a>
        <a href="{{ route('accountant.reports.transactions') }}" class="btn btn-info">
            <i class="fas fa-file-excel"></i> تصدير المعاملات
        </a>
    </div>
</div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الموقع</th>
                        <th>الرصيد</th>
                        <th>الحالة</th>
                        <th>أنشئت بواسطة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($points as $pos)
                    <tr>
                        <td>{{ $pos->name }}</td>
                        <td>{{ $pos->location ?? '--' }}</td>
                        <td>{{ number_format($pos->balance, 2) }} ر.ي</td>
                        <td>
                            <span class="badge {{ $pos->is_active ? 'bg-success' : 'bg-danger' }}">
                                {{ $pos->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td>{{ $pos->creator->name }}</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-warning">تعديل</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
