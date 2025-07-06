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
        }
        
        .add-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
        }
        
        .search-box {
            position: relative;
            max-width: 300px;
        }
        
        .search-box input {
            border-radius: 50px;
            padding-left: 45px;
            padding-right: 20px;
            border: 1px solid var(--border-color);
        }
        
        .search-box i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
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
        
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 30px;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        
        input:checked + .slider:before {
            transform: translateX(30px);
        }
        
        .stats-card {
            text-align: center;
            padding: 20px;
            border-radius: 12px;
            color: white;
            margin-bottom: 20px;
        }
        
        .stats-card i {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .stats-card h3 {
            font-weight: 700;
            font-size: 2rem;
            margin: 10px 0;
        }
        
        .stats-card p {
            margin: 0;
            font-size: 1.1rem;
        }
        
        .card-1 {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
        }
        
        .card-2 {
            background: linear-gradient(135deg, #4cc9f0, #4895ef);
        }
        
        .card-3 {
            background: linear-gradient(135deg, #f72585, #b5179e);
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
        
        @media (max-width: 768px) {
            .table-responsive {
                border: none;
            }
            
            .search-box {
                margin-top: 15px;
                max-width: 100%;
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
                    <button class="add-btn">
                        <i class="fas fa-plus"></i> إضافة نقطة بيع جديدة
                    </button>
                </div>
            </div>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card card-1">
                    <i class="fas fa-store"></i>
                    <h3>24</h3>
                    <p>نقاط البيع الكلية</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card card-2">
                    <i class="fas fa-check-circle"></i>
                    <h3>18</h3>
                    <p>نقاط البيع النشطة</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card card-3">
                    <i class="fas fa-pause-circle"></i>
                    <h3>6</h3>
                    <p>نقاط البيع غير النشطة</p>
                </div>
            </div>
        </div>

        <!-- شريط البحث والتحكم -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" class="form-control" placeholder="ابحث عن نقطة بيع...">
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="d-inline-block me-3">
                            <select class="form-select">
                                <option>الكل</option>
                                <option>نشطة فقط</option>
                                <option>غير نشطة</option>
                            </select>
                        </div>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-filter me-2"></i>تصفية النتائج
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- جدول نقاط البيع -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">قائمة نقاط البيع</h5>
                <span class="badge bg-primary">24 نقطة بيع</span>
            </div>
            <div class="card-body">
                @if(count($poses) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>الموقع</th>
                                    <th>الرقم التسلسلي</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>الفرع الرئيسي</td>
                                    <td>الرياض - حي المروج</td>
                                    <td>POS-001</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" checked>
                                            <span class="slider"></span>
                                        </label>
                                        <span class="status-badge status-active ms-2">نشط</span>
                                    </td>
                                    <td>2023-10-15</td>
                                    <td>
                                        <a href="#" class="action-btn view-btn" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="action-btn edit-btn" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="action-btn delete-btn" title="حذف">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>فرع الشمال</td>
                                    <td>الرياض - حي الصحافة</td>
                                    <td>POS-002</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" checked>
                                            <span class="slider"></span>
                                        </label>
                                        <span class="status-badge status-active ms-2">نشط</span>
                                    </td>
                                    <td>2023-11-20</td>
                                    <td>
                                        <a href="#" class="action-btn view-btn" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="action-btn edit-btn" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="action-btn delete-btn" title="حذف">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>فرع الجنوب</td>
                                    <td>الرياض - حي السلي</td>
                                    <td>POS-003</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox">
                                            <span class="slider"></span>
                                        </label>
                                        <span class="status-badge status-inactive ms-2">غير نشط</span>
                                    </td>
                                    <td>2024-01-05</td>
                                    <td>
                                        <a href="#" class="action-btn view-btn" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="action-btn edit-btn" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="action-btn delete-btn" title="حذف">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>فرع المركز التجاري</td>
                                    <td>الرياض - المركز التجاري</td>
                                    <td>POS-004</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" checked>
                                            <span class="slider"></span>
                                        </label>
                                        <span class="status-badge status-active ms-2">نشط</span>
                                    </td>
                                    <td>2024-02-18</td>
                                    <td>
                                        <a href="#" class="action-btn view-btn" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="action-btn edit-btn" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="action-btn delete-btn" title="حذف">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>فرع الجامعة</td>
                                    <td>الرياض - جامعة الملك سعود</td>
                                    <td>POS-005</td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox">
                                            <span class="slider"></span>
                                        </label>
                                        <span class="status-badge status-inactive ms-2">غير نشط</span>
                                    </td>
                                    <td>2024-03-22</td>
                                    <td>
                                        <a href="#" class="action-btn view-btn" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="#" class="action-btn edit-btn" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="#" class="action-btn delete-btn" title="حذف">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- التصفح بين الصفحات -->
                    <nav class="d-flex justify-content-center mt-4">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                @else
                    <div class="empty-state">
                        <i class="fas fa-cash-register"></i>
                        <h4>لا توجد نقاط بيع</h4>
                        <p class="mb-4">لم يتم إضافة أي نقاط بيع بعد. ابدأ بإضافة نقطة بيع جديدة.</p>
                        <button class="add-btn">
                            <i class="fas fa-plus me-2"></i>إضافة نقطة بيع جديدة
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // تفعيل تبديل الحالة
        document.querySelectorAll('.switch input').forEach(switchEl => {
            switchEl.addEventListener('change', function() {
                const statusBadge = this.closest('td').querySelector('.status-badge');
                if (this.checked) {
                    statusBadge.textContent = 'نشط';
                    statusBadge.classList.remove('status-inactive');
                    statusBadge.classList.add('status-active');
                } else {
                    statusBadge.textContent = 'غير نشط';
                    statusBadge.classList.remove('status-active');
                    statusBadge.classList.add('status-inactive');
                }
                
                // هنا سيتم إضافة كود AJAX لتحديث الحالة في قاعدة البيانات
                console.log('تم تغيير الحالة إلى: ' + (this.checked ? 'نشط' : 'غير نشط'));
            });
        });

        // تأكيد الحذف
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('هل أنت متأكد من رغبتك في حذف نقطة البيع هذه؟ سيتم حذف جميع البيانات المرتبطة بها.')) {
                    // هنا سيتم إضافة كود الحذف
                    console.log('تم تأكيد الحذف');
                    // عرض رسالة نجاح مؤقتة
                    alert('تم حذف نقطة البيع بنجاح!');
                }
            });
        });

        // البحث أثناء الكتابة
        document.querySelector('.search-box input').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            
            rows.forEach(row => {
                const name = row.children[1].textContent.toLowerCase();
                const location = row.children[2].textContent.toLowerCase();
                const serial = row.children[3].textContent.toLowerCase();
                
                if (name.includes(searchTerm) || location.includes(searchTerm) || serial.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
