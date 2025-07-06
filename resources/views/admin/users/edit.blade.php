<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المستخدم - لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --admin-color: #3a0ca3;
            --accountant-color: #4cc9f0;
            --pos-color: #06d6a0;
            --light-bg: #f8f9fa;
            --dark-text: #2b2d42;
            --border-color: #dee2e6;
        }
        
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #f5f7fb;
            color: #333;
            padding: 20px;
            direction: rtl;
        }
        
        .admin-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .admin-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .page-title {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary-color);
        }
        
        .page-title i {
            margin-left: 15px;
            font-size: 1.5rem;
        }
        
        .back-btn {
            background: #f1f5f9;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s;
        }
        
        .back-btn:hover {
            background: #e2e8f0;
            transform: translateX(-5px);
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #334155;
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
        }
        
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 25px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .section-title {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .section-title i {
            margin-left: 10px;
            font-size: 1.1rem;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            margin-top: 30px;
            gap: 15px;
        }
        
        .btn-save {
            background: linear-gradient(135deg, var(--primary-color), var(--admin-color));
            border: none;
            padding: 12px 30px;
            border-radius: 12px;
            font-weight: 600;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }
        
        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(67, 97, 238, 0.35);
        }
        
        .btn-cancel {
            background: #f1f5f9;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            color: #64748b;
            transition: all 0.3s;
        }
        
        .btn-cancel:hover {
            background: #e2e8f0;
            color: #475569;
        }
        
        .user-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin: 0 auto 20px;
            display: block;
        }
        
        .role-indicator {
            display: inline-flex;
            align-items: center;
            padding: 4px 15px;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .role-admin { 
            background-color: rgba(58, 12, 163, 0.15);
            color: var(--admin-color);
            border: 2px solid var(--admin-color);
        }
        
        .role-accountant { 
            background-color: rgba(76, 201, 240, 0.15);
            color: var(--accountant-color);
            border: 2px solid var(--accountant-color);
        }
        
        .role-pos { 
            background-color: rgba(6, 214, 160, 0.15);
            color: var(--pos-color);
            border: 2px solid var(--pos-color);
        }
        
        .role-selector {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .role-card {
            flex: 1;
            min-width: 200px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .role-card.selected {
            border-color: var(--primary-color);
            background-color: rgba(67, 97, 238, 0.05);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }
        
        .role-card.admin.selected {
            border-color: var(--admin-color);
            background-color: rgba(58, 12, 163, 0.05);
            box-shadow: 0 0 0 3px rgba(58, 12, 163, 0.2);
        }
        
        .role-card.accountant.selected {
            border-color: var(--accountant-color);
            background-color: rgba(76, 201, 240, 0.05);
            box-shadow: 0 0 0 3px rgba(76, 201, 240, 0.2);
        }
        
        .role-card.pos.selected {
            border-color: var(--pos-color);
            background-color: rgba(6, 214, 160, 0.05);
            box-shadow: 0 0 0 3px rgba(6, 214, 160, 0.2);
        }
        
        .role-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }
        
        .admin .role-icon { color: var(--admin-color); }
        .accountant .role-icon { color: var(--accountant-color); }
        .pos .role-icon { color: var(--pos-color); }
        
        .role-title {
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .role-description {
            font-size: 0.9rem;
            color: #64748b;
        }
        
        .pos-field {
            background-color: rgba(6, 214, 160, 0.05);
            border-radius: 12px;
            padding: 20px;
            border: 1px dashed var(--pos-color);
            margin-top: 20px;
        }
        
        @media (max-width: 768px) {
            .role-card {
                min-width: 100%;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn-save, .btn-cancel {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-card">
            <div class="page-header">
                <div class="page-title">
                    <i class="fas fa-user-edit"></i>
                    <span>تعديل المستخدم</span>
                </div>
                <a href="#" class="back-btn">
                    <i class="fas fa-arrow-right me-2"></i>العودة إلى القائمة
                </a>
            </div>
            
            <div class="text-center">
                <img src="https://ui-avatars.com/api/?name=سالم+علي&background=4361ee&color=fff" alt="صورة المستخدم" class="user-avatar">
                
                <h3 class="mb-1">سالم علي</h3>
                <div class="d-flex justify-content-center">
                    <span class="role-indicator role-accountant">
                        <i class="fas fa-calculator me-2"></i>محاسب
                    </span>
                </div>
                <p class="text-muted">sami.ali@company.com | آخر تحديث: 2023-11-15</p>
            </div>
            
            <form>
                <!-- اختيار الدور -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-user-tag"></i>
                        <span>دور المستخدم</span>
                    </div>
                    
                    <div class="role-selector">
                        <!-- دور المدير -->
                        <div class="role-card admin" data-role="admin">
                            <div class="role-icon">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="role-title">مدير النظام</div>
                            <div class="role-description">
                                صلاحية كاملة للوصول إلى جميع أجزاء النظام وإدارة المستخدمين
                            </div>
                        </div>
                        
                        <!-- دور المحاسب -->
                        <div class="role-card accountant selected" data-role="accountant">
                            <div class="role-icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <div class="role-title">محاسب</div>
                            <div class="role-description">
                                إدارة الفواتير، التقارير المالية، والحسابات
                            </div>
                        </div>
                        
                        <!-- دور نقطة البيع -->
                        <div class="role-card pos" data-role="pos">
                            <div class="role-icon">
                                <i class="fas fa-cash-register"></i>
                            </div>
                            <div class="role-title">نقطة البيع</div>
                            <div class="role-description">
                                إدارة عمليات البيع في نقطة بيع محددة
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="role" id="selected-role" value="accountant">
                </div>
                
                <!-- معلومات المستخدم -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-user-circle"></i>
                        <span>معلومات المستخدم</span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">الاسم الكامل</label>
                            <input type="text" class="form-control" value="سالم علي">
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" value="sami.ali@company.com">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control" placeholder="اتركه فارغاً للحفاظ على نفس كلمة المرور">
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label class="form-label">تأكيد كلمة المرور</label>
                            <input type="password" class="form-control">
                        </div>
                    </div>
                </div>
                
                <!-- نقطة البيع (تظهر فقط عند اختيار دور نقطة البيع) -->
                <div class="form-section" id="pos-section" style="display: none;">
                    <div class="section-title">
                        <i class="fas fa-store"></i>
                        <span>نقطة البيع المخصصة</span>
                    </div>
                    
                    <div class="pos-field">
                        <div class="mb-3">
                            <label class="form-label">اختر نقطة البيع</label>
                            <select class="form-select">
                                <option value="">اختر نقطة البيع</option>
                                <option value="1">الفرع الرئيسي</option>
                                <option value="2">فرع الشمال</option>
                                <option value="3">فرع الجنوب</option>
                                <option value="4">فرع المركز التجاري</option>
                            </select>
                        </div>
                        
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-info-circle me-2"></i>
                            سيتم تقييد المستخدم للعمل فقط على نقطة البيع المحددة
                        </div>
                    </div>
                </div>
                
                <!-- الصلاحيات الإضافية -->
                <div class="form-section">
                    <div class="section-title">
                        <i class="fas fa-key"></i>
                        <span>صلاحيات إضافية</span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="reportsPermission" checked>
                                <label class="form-check-label" for="reportsPermission">الوصول إلى التقارير</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="exportsPermission">
                                <label class="form-check-label" for="exportsPermission">تصدير البيانات</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="clientsPermission" checked>
                                <label class="form-check-label" for="clientsPermission">إدارة العملاء</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="productsPermission">
                                <label class="form-check-label" for="productsPermission">إدارة المنتجات</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- أزرار الحفظ والإلغاء -->
                <div class="form-actions">
                    <button type="button" class="btn-cancel">
                        <i class="fas fa-times me-2"></i>إلغاء
                    </button>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save me-1"></i>حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // اختيار الدور
        document.querySelectorAll('.role-card').forEach(card => {
            card.addEventListener('click', function() {
                // إزالة التحديد من جميع البطاقات
                document.querySelectorAll('.role-card').forEach(c => {
                    c.classList.remove('selected');
                });
                
                // إضافة التحديد للبطاقة المختارة
                this.classList.add('selected');
                
                // تحديث القيمة المخفية
                const role = this.getAttribute('data-role');
                document.getElementById('selected-role').value = role;
                
                // إظهار/إخفاء قسم نقطة البيع
                const posSection = document.getElementById('pos-section');
                if (role === 'pos') {
                    posSection.style.display = 'block';
                } else {
                    posSection.style.display = 'none';
                }
                
                // تحديث لون زر الحفظ حسب الدور
                const saveBtn = document.querySelector('.btn-save');
                saveBtn.classList.remove('admin', 'accountant', 'pos');
                
                if (role === 'admin') {
                    saveBtn.style.background = 'linear-gradient(135deg, var(--primary-color), var(--admin-color))';
                } else if (role === 'accountant') {
                    saveBtn.style.background = 'linear-gradient(135deg, var(--primary-color), var(--accountant-color))';
                } else if (role === 'pos') {
                    saveBtn.style.background = 'linear-gradient(135deg, var(--primary-color), var(--pos-color))';
                }
            });
        });
        
        // محاكاة عملية الحفظ
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // محاكاة التأخير
            const saveBtn = document.querySelector('.btn-save');
            const originalHtml = saveBtn.innerHTML;
            
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...';
            saveBtn.disabled = true;
            
            setTimeout(() => {
                saveBtn.innerHTML = originalHtml;
                saveBtn.disabled = false;
                
                // عرض رسالة نجاح
                const role = document.getElementById('selected-role').value;
                let roleName = '';
                
                if (role === 'admin') roleName = 'مدير النظام';
                else if (role === 'accountant') roleName = 'محاسب';
                else if (role === 'pos') roleName = 'نقطة البيع';
                
                alert(`تم تحديث بيانات المستخدم بنجاح!\nالدور الجديد: ${roleName}`);
            }, 1500);
        });
        
        // إظهار قسم نقطة البيع إذا كان الدور الحالي هو نقطة البيع
        const currentRole = document.querySelector('.role-card.selected').getAttribute('data-role');
        if (currentRole === 'pos') {
            document.getElementById('pos-section').style.display = 'block';
        }
    </script>
</body>
</html>