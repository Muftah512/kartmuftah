<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة نقاط البيع</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 25px;
        }
        
        .card-header {
            background: linear-gradient(120deg, #4361ee, #3a0ca3);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 20px 25px;
            font-weight: 600;
            border: none;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: #2ecc71;
            border-color: #2ecc71;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
        }
        
        .btn-info {
            background-color: var(--info);
            border-color: var(--info);
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
        }
        
        .table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        
        .table th {
            background-color: #f1f5fd;
            color: var(--primary);
            font-weight: 700;
            padding: 15px;
            border-top: 1px solid #eaeef7;
        }
        
        .table td {
            padding: 15px;
            vertical-align: middle;
            border-top: 1px solid #eaeef7;
        }
        
        .table tr:hover td {
            background-color: #f8fbff;
        }
        
        .badge {
            padding: 8px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .badge.bg-success {
            background-color: #2ecc71 !important;
        }
        
        .badge.bg-danger {
            background-color: #e74c3c !important;
        }
        
        .status-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }
        
        .active-status {
            background-color: #2ecc71;
        }
        
        .inactive-status {
            background-color: #e74c3c;
        }
        
        .action-btn {
            border-radius: 50px;
            padding: 8px 15px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .pagination {
            justify-content: center;
            margin-top: 25px;
        }
        
        .page-link {
            border-radius: 50px !important;
            margin: 0 5px;
            border: none;
            color: var(--primary);
        }
        
        .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .stat-card {
            text-align: center;
            padding: 25px 15px;
            border-radius: 15px;
            margin-bottom: 25px;
            color: white;
        }
        
        .stat-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .stat-card .number {
            font-size: 2rem;
            font-weight: 700;
            margin: 10px 0;
        }
        
        .stat-card .label {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .search-box {
            position: relative;
            max-width: 400px;
        }
        
        .search-box input {
            border-radius: 50px;
            padding-left: 45px;
        }
        
        .search-box i {
            position: absolute;
            left: 20px;
            top: 12px;
            color: #6c757d;
        }
        
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        
        .filter-section h5 {
            color: var(--primary);
            margin-bottom: 20px;
            font-weight: 700;
        }
        
        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .header-section .btn-group {
                margin-top: 15px;
                width: 100%;
            }
            
            .header-section .btn {
                width: 100%;
                margin-bottom: 10px;
            }
        }
        
        .avatar {
            width: 40px; 
            height: 40px; 
            background: #4361ee; 
            color: white; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <!-- Header Section -->
        <div class="header-section">
            <div>
                <h1 class="h2 mb-3" style="color: #3a0ca3; font-weight: 700;">
                    <i class="fas fa-cash-register me-3"></i>إدارة نقاط البيع
                </h1>
                <p class="text-muted">إدارة جميع نقاط البيع التابعة لك في مكان واحد</p>
            </div>
            <div>
                <a href="{{ route('accountant.pos.create') }}" class="btn btn-primary mb-2">
                    <i class="fas fa-plus me-2"></i>إنشاء نقطة بيع جديدة
                </a>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="row mb-4">
            @php
                $activeCount = $points->where('is_active', 1)->count();
                $inactiveCount = $points->where('is_active', 0)->count();
                $totalBalance = $points->sum('balance');
            @endphp
            
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(120deg, #4361ee, #4895ef);">
                    <i class="fas fa-store"></i>
                    <div class="number">{{ $points->total() }}</div>
                    <div class="label">نقاط البيع</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(120deg, #2ecc71, #27ae60);">
                    <i class="fas fa-check-circle"></i>
                    <div class="number">{{ $activeCount }}</div>
                    <div class="label">نشطة</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(120deg, #e74c3c, #c0392b);">
                    <i class="fas fa-times-circle"></i>
                    <div class="number">{{ $inactiveCount }}</div>
                    <div class="label">غير نشطة</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(120deg, #f39c12, #e67e22);">
                    <i class="fas fa-coins"></i>
                    <div class="number">{{ number_format($totalBalance, 2) }} ر.ي</div>
                    <div class="label">إجمالي الرصيد</div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-filter me-2"></i>تصفية النتائج</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">حالة نقطة البيع</label>
                            <select class="form-select">
                                <option>جميع الحالات</option>
                                <option selected>نشطة فقط</option>
                                <option>غير نشطة</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ترتيب حسب</label>
                            <select class="form-select">
                                <option>أحدث الإضافات</option>
                                <option>الاسم من أ إلى ي</option>
                                <option>الاسم من ي إلى أ</option>
                                <option>أعلى رصيد</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5><i class="fas fa-search me-2"></i>بحث متقدم</h5>
                    <div class="search-box mb-3">
                        <i class="fas fa-search"></i>
                        <input type="text" class="form-control" placeholder="ابحث باسم نقطة البيع أو الموقع...">
                    </div>
                    <button class="btn btn-outline-primary me-2">
                        <i class="fas fa-sync-alt me-2"></i>إعادة تعيين
                    </button>
                    <button class="btn btn-primary">
                        <i class="fas fa-filter me-2"></i>تطبيق الفلتر
                    </button>
                </div>
            </div>
        </div>

        <!-- Export Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="text-muted">عرض {{ $points->firstItem() }} - {{ $points->lastItem() }} من {{ $points->total() }} نتيجة</span>
            </div>
            <div>
                <a href="{{ route('accountant.reports.pos') }}" class="btn btn-success me-2">
                    <i class="fas fa-file-pdf me-2"></i>تصدير PDF
                </a>
                <a href="{{ route('accountant.reports.transactions') }}" class="btn btn-info">
                    <i class="fas fa-file-excel me-2"></i>تصدير المعاملات
                </a>
            </div>
        </div>

        <!-- POS Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-list me-2"></i>قائمة نقاط البيع
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
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
                            @foreach($points as $index => $pos)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3" style="background: {{ $pos->is_active ? '#4361ee' : '#e74c3c' }}">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <strong>{{ $pos->name }}</strong>
                                            <div class="text-muted small">{{ $pos->email ?? '--' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $pos->location ?? '--' }}</td>
                                <td>
                                    <span class="fw-bold" style="color: {{ $pos->balance > 0 ? '#2ecc71' : '#e74c3c' }};">
                                        {{ number_format($pos->balance, 2) }} ر.ي
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $pos->is_active ? 'bg-success' : 'bg-danger' }}">
                                        <span class="status-indicator {{ $pos->is_active ? 'active-status' : 'inactive-status' }}"></span>
                                        {{ $pos->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>{{ $pos->creator->name }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="#" class="btn btn-sm btn-primary action-btn me-2">
                                            <i class="fas fa-edit me-1"></i> تعديل
                                        </a>
                                        <a href="{{ route('accountant.pos.show', $pos->id) }}" class="btn btn-sm btn-info action-btn">
                                            <i class="fas fa-eye me-1"></i> تفاصيل
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        {{ $points->links() }}
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>