<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة نقاط البيع - لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --light-bg: #f8f9fa;
            --dark-text: #2b2d42;
            --border-color: #dee2e6;
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
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 20px;
            overflow: hidden;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--border-color);
            padding: 15px 20px;
            font-weight: 700;
        }
        
        .table th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 15px 20px;
        }
        
        .table td {
            padding: 12px 20px;
            vertical-align: middle;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .status-active {
            background-color: rgba(76, 201, 240, 0.2);
            color: #1a936f;
        }
        
        .status-inactive {
            background-color: rgba(239, 71, 111, 0.2);
            color: #ef476f;
        }
        
        .action-btn {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
            margin-left: 5px;
        }
        
        .action-btn:hover {
            transform: scale(1.1);
        }
        
        .view-btn {
            background-color: rgba(76, 201, 240, 0.15);
            color: var(--primary-color);
        }
        
        .edit-btn {
            background-color: rgba(255, 193, 7, 0.15);
            color: #ffc107;
        }
        
        .delete-btn {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }
        
        .add-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            color: white;
            text-decoration: none;
        }
        
        .add-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
            color: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 5rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }
        
        .empty-state h4 {
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .pagination .page-item .page-link {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 3px;
            border: none;
            color: var(--dark-text);
        }
        
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .action-form {
            display: inline-block;
        }
        
        @media (max-width: 768px) {
            .table-responsive {
                border: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- رأس الصفحة -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0"><i class="fas fa-cash-register me-3"></i>إدارة نقاط البيع</h1>
                    <p class="mb-0 mt-2">إدارة جميع نقاط البيع في نظامك بسهولة وكفاءة</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('admin.pos.create') }}" class="add-btn">
                        <i class="fas fa-plus"></i> إضافة نقطة بيع جديدة
                    </a>
                </div>
            </div>
        </div>

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stats-card card-1">
            <i class="fas fa-store"></i>
            <h3>{{ $stats['total'] }}</h3>
            <p>نقاط البيع الكلية</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card card-2">
            <i class="fas fa-check-circle"></i>
            <h3>{{ $stats['active'] }}</h3>
            <p>نقاط البيع النشطة</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card card-3">
            <i class="fas fa-pause-circle"></i>
            <h3>{{ $stats['inactive'] }}</h3>
            <p>نقاط البيع غير النشطة</p>
        </div>
    </div>
</div>

        <!-- جدول نقاط البيع -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">قائمة نقاط البيع</h5>
                <span class="badge bg-primary">{{ $points->total() }} نقطة بيع</span>
            </div>
            <div class="card-body">
                @if($points->count())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الموقع</th>
                                    <th>المحاسب</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($points as $point)
                                <tr>
                                    <td>{{ $point->id }}</td>
                                    <td>{{ $point->name }}</td>
                                    <td>{{ $point->location }}</td>
                                    <td>{{ optional($point->accountant)->name ?? '—' }}</td>
                                    <td>
                                        @if($point->is_active)
                                            <span class="status-badge status-active">نشط</span>
                                        @else
                                            <span class="status-badge status-inactive">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>{{ $point->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.pos.edit', $point) }}" class="action-btn edit-btn" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.pos.destroy', $point) }}" method="POST" class="action-form">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="action-btn delete-btn" title="حذف" onclick="return confirm('هل أنت متأكد من رغبتك في حذف نقطة البيع هذه؟')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- التصفح بين الصفحات -->
                    <nav class="d-flex justify-content-center mt-4">
                        {{ $points->links() }}
                    </nav>
                @else
                    <div class="empty-state">
                        <i class="fas fa-cash-register"></i>
                        <h4>لا توجد نقاط بيع</h4>
                        <p class="mb-4">لم يتم إضافة أي نقاط بيع بعد. ابدأ بإضافة نقطة بيع جديدة.</p>
                        <a href="{{ route('admin.pos.create') }}" class="add-btn">
                            <i class="fas fa-plus me-2"></i>إضافة نقطة بيع جديدة
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // تفعيل التأكيد عند الحذف
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm('هل أنت متأكد من رغبتك في حذف نقطة البيع هذه؟ سيتم حذف جميع البيانات المرتبطة بها.')) {
                    e.preventDefault();
                }
            });
        });

        // تخصيص تنسيق أرقام الصفحات
        document.addEventListener('DOMContentLoaded', function() {
            const paginationItems = document.querySelectorAll('.pagination .page-item');
            
            paginationItems.forEach(item => {
                const link = item.querySelector('.page-link');
                if (link) {
                    // إضافة فئات Bootstrap لتنسيق أرقام الصفحات
                    link.classList.add('d-flex', 'align-items-center', 'justify-content-center');
                    
                    // استبدال النصوص للأسهم
                    if (link.textContent.includes('Previous')) {
                        link.innerHTML = '<i class="fas fa-chevron-right"></i>';
                    } else if (link.textContent.includes('Next')) {
                        link.innerHTML = '<i class="fas fa-chevron-left"></i>';
                    }
                    
                    // إضافة فئة النشطة
                    if (item.classList.contains('active')) {
                        link.classList.add('text-white');
                    }
                }
            });
        });
    </script>
</body>
</html>