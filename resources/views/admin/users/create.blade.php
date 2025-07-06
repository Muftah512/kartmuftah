<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء مستخدم جديد - لوحة التحكم</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Tajawal', sans-serif; }
        body { background-color: #f5f7fb; }
        .input-icon { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
        .role-card { border: 2px solid #e2e8f0; border-radius: 10px; transition: all 0.3s ease; cursor: pointer; }
        .role-card:hover { border-color: #4361ee; transform: translateY(-3px); }
        .role-card.selected { border-color: #4361ee; background-color: #f0f7ff; box-shadow: 0 4px 10px rgba(67, 97, 238, 0.15); }
        .password-toggle { position: absolute; left: 50px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8; }
        .password-toggle:hover { color: #4361ee; }
        .role-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-left: 10px; }
        .toast { position: fixed; top: 20px; right: 20px; padding: 15px 25px; border-radius: 8px; color: white; font-weight: 500; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 1000; opacity: 0; transform: translateY(-20px); transition: all 0.4s ease; }
        .toast.show { opacity: 1; transform: translateY(0); }
        .toast.success { background: linear-gradient(to right, #10b981, #059669); }
        .toast.error { background: linear-gradient(to right, #ef4444, #dc2626); }
        .debug-info { position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.7); color: white; padding: 10px; border-radius: 5px; font-size: 12px; z-index: 1000; }
    </style>
</head>
<body class="bg-gray-50 font-tajawal">
    <!-- Toast Messages -->
    <div id="success-toast" class="toast success hidden">
        <i class="fas fa-check-circle mr-2"></i>تم إنشاء المستخدم بنجاح!
    </div>
    <div id="error-toast" class="toast error hidden">
        <i class="fas fa-exclamation-circle mr-2"></i>حدث خطأ أثناء إنشاء المستخدم!
    </div>

    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="text-primary text-xl font-bold flex items-center">
                    <i class="fas fa-users mr-2 text-blue-600"></i>
                    <span>نظام إدارة المستخدمين</span>
                </div>
                <div class="flex items-center text-gray-600 text-sm">
                    <i class="fas fa-user-shield mr-2"></i>
                    <span>المشرف / مدير النظام</span>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row sm:justify-between sm:items-center">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-user-plus mr-2 text-blue-600"></i>إنشاء مستخدم جديد
                </h2>
                <a href="#" class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors duration-200">
                    <i class="fas fa-arrow-left ml-2"></i>العودة للقائمة
                </a>
            </div>

            <!-- General Errors Container -->
            <div id="general-errors" class="px-6 py-4 bg-red-50 text-red-700 hidden">
                <ul class="list-disc list-inside">
                    <!-- Errors will be populated here -->
                </ul>
            </div>

            <div class="px-6 py-8">
                <form id="user-create-form" action="#" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">الاسم الكامل</label>
                            <div class="relative">
                                <div class="input-icon"><i class="fas fa-user"></i></div>
                                <input type="text" id="name" name="name" required placeholder="مثال: أحمد محمد" class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <p id="name-error" class="text-red-600 text-sm mt-1 hidden">الرجاء إدخال اسم صحيح</p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                            <div class="relative">
                                <div class="input-icon"><i class="fas fa-envelope"></i></div>
                                <input type="email" id="email" name="email" required placeholder="example@domain.com" class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            <p id="email-error" class="text-red-600 text-sm mt-1 hidden">الرجاء إدخال بريد إلكتروني صحيح</p>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">كلمة المرور</label>
                            <div class="relative">
                                <div class="input-icon"><i class="fas fa-lock"></i></div>
                                <input type="password" id="password" name="password" required placeholder="كلمة مرور قوية (8 أحرف على الأقل)" class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <div class="password-toggle" id="toggle-password">
                                    <i class="far fa-eye"></i>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div class="flex items-center mb-1">
                                    <div class="h-1 w-1/3 bg-gray-300 rounded mr-2"></div>
                                    <div class="h-1 w-1/3 bg-gray-300 rounded mr-2"></div>
                                    <div class="h-1 w-1/3 bg-gray-300 rounded"></div>
                                </div>
                                <p class="text-xs text-gray-500">استخدم 8 أحرف على الأقل مع مزيج من الأرقام والرموز</p>
                            </div>
                            <p id="password-error" class="text-red-600 text-sm mt-1 hidden">كلمة المرور يجب أن تكون 8 أحرف على الأقل</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">تأكيد كلمة المرور</label>
                            <div class="relative">
                                <div class="input-icon"><i class="fas fa-lock"></i></div>
                                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="أعد إدخال كلمة المرور" class="w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <div class="password-toggle" id="toggle-confirm-password">
                                    <i class="far fa-eye"></i>
                                </div>
                            </div>
                            <p id="password-confirm-error" class="text-red-600 text-sm mt-1 hidden">كلمتا المرور غير متطابقتين</p>
                        </div>
                    </div>

                    <!-- User Role -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-2">اختر دور المستخدم</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-4">
                            <div class="role-card p-5 flex items-center justify-between" data-role="admin">
                                <div class="flex items-center">
                                    <div class="role-icon bg-blue-100 text-blue-600">
                                        <i class="fas fa-crown"></i>
                                    </div>
                                    <span>مدير</span>
                                </div>
                                <input type="radio" name="role" value="admin" class="hidden">
                            </div>
                            
                            <div class="role-card p-5 flex items-center justify-between" data-role="accountant">
                                <div class="flex items-center">
                                    <div class="role-icon bg-green-100 text-green-600">
                                        <i class="fas fa-calculator"></i>
                                    </div>
                                    <span>محاسب</span>
                                </div>
                                <input type="radio" name="role" value="accountant" class="hidden">
                            </div>
                            
                            <div class="role-card p-5 flex items-center justify-between" data-role="pos">
                                <div class="flex items-center">
                                    <div class="role-icon bg-purple-100 text-purple-600">
                                        <i class="fas fa-cash-register"></i>
                                    </div>
                                    <span>نقطة بيع</span>
                                </div>
                                <input type="radio" name="role" value="pos" class="hidden">
                            </div>
                        </div>
                        <p id="role-error" class="text-red-600 text-sm mt-1 hidden">الرجاء اختيار دور للمستخدم</p>
                    </div>

                    <!-- POS Selection -->
                    <div id="pos-field" class="hidden">
                        <label for="point_of_sale_id" class="block text-sm font-medium text-gray-700 mb-1">نقطة البيع المرتبطة</label>
                        <div class="relative">
                            <div class="input-icon"><i class="fas fa-store"></i></div>
                            <select id="point_of_sale_id" name="point_of_sale_id" class="w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <option value="">اختر نقطة البيع</option>
                                <option value="1">فرع الرياض - المركز الرئيسي</option>
                                <option value="2">فرع جدة - الواجهة البحرية</option>
                                <option value="3">فرع الدمام - السوق التجاري</option>
                                <option value="4">فرع مكة - الحرم</option>
                            </select>
                        </div>
                        <p id="pos-error" class="text-red-600 text-sm mt-1 hidden">الرجاء اختيار نقطة بيع</p>
                    </div>

                    <!-- Form Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <button type="reset" id="reset-button" class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 font-medium transition-colors duration-200">
                            <i class="fas fa-redo mr-2"></i>إعادة تعيين
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-300 shadow-md hover:shadow-lg">
                            <i class="fas fa-save mr-2"></i>إنشاء المستخدم
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Debug Info Panel -->
    <div class="debug-info hidden" id="debug-info">
        <div class="flex items-center justify-between mb-2">
            <h3 class="font-bold">معلومات التصحيح</h3>
            <button id="close-debug" class="ml-4 text-xs px-2 py-1 bg-gray-800 rounded">إغلاق</button>
        </div>
        <div id="debug-content"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Debug mode toggle
            const debugInfo = document.getElementById('debug-info');
            const debugContent = document.getElementById('debug-content');
            const closeDebug = document.getElementById('close-debug');
            
            // User role selection - FIXED ISSUE HERE
            const roleCards = document.querySelectorAll('.role-card');
            const posField = document.getElementById('pos-field');
            const roleInputs = document.querySelectorAll('input[name="role"]');
            
            roleCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Remove selection from all
                    roleCards.forEach(c => c.classList.remove('selected'));
                    // Select this card
                    this.classList.add('selected');
                    
                    // FIX: Ensure radio button is checked
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;
                    
                    // Show/hide POS field
                    const role = this.getAttribute('data-role');
                    posField.classList.toggle('hidden', role !== 'pos');
                    
                    // Clear role error when selecting a role
                    document.getElementById('role-error').classList.add('hidden');
                    
                    // Update debug info
                    updateDebugInfo('role', `تم اختيار الدور: ${role}`);
                });
            });
            
            // Password toggle visibility
            function setupPasswordToggle(inputId, toggleId) {
                const passwordInput = document.getElementById(inputId);
                const toggleButton = document.getElementById(toggleId);
                
                toggleButton.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }
            
            setupPasswordToggle('password', 'toggle-password');
            setupPasswordToggle('password_confirmation', 'toggle-confirm-password');
            
            // Password strength indicator
            const passwordInput = document.getElementById('password');
            passwordInput.addEventListener('input', function() {
                const strengthBars = this.parentElement.nextElementSibling.querySelectorAll('div > div');
                const password = this.value;
                
                // Reset indicator
                strengthBars.forEach(bar => {
                    bar.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-green-500');
                    bar.classList.add('bg-gray-300');
                });
                
                if (password.length === 0) return;
                
                // Calculate password strength
                let strength = 0;
                if (password.length >= 8) strength += 1;
                if (/[A-Z]/.test(password)) strength += 1;
                if (/[0-9]/.test(password)) strength += 1;
                if (/[^A-Za-z0-9]/.test(password)) strength += 1;
                
                // Update indicator
                for (let i = 0; i < strength; i++) {
                    if (i < strengthBars.length) {
                        strengthBars[i].classList.remove('bg-gray-300');
                        if (strength <= 2) {
                            strengthBars[i].classList.add('bg-red-500');
                        } else if (strength === 3) {
                            strengthBars[i].classList.add('bg-yellow-500');
                        } else {
                            strengthBars[i].classList.add('bg-green-500');
                        }
                    }
                }
            });
            
            // Show toast message
            function showToast(type, message = '') {
                const toast = document.getElementById(`${type}-toast`);
                if (message) {
                    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>${message}`;
                }
                
                toast.classList.remove('hidden');
                toast.classList.add('show');
                
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.classList.add('hidden'), 400);
                }, 3000);
            }
            
            // Update debug information
            function updateDebugInfo(field, message) {
                if (!debugInfo.classList.contains('hidden')) {
                    const now = new Date();
                    const time = `${now.getHours()}:${now.getMinutes()}:${now.getSeconds()}`;
                    debugContent.innerHTML += `<div class="mb-1"><span class="text-blue-400">[${time}]</span> ${field}: ${message}</div>`;
                    debugContent.scrollTop = debugContent.scrollHeight;
                }
            }
            
            // Form validation
            function validateForm() {
                let isValid = true;
                const name = document.getElementById('name').value.trim();
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;
                const roleSelected = document.querySelector('input[name="role"]:checked');
                const posSelected = document.getElementById('point_of_sale_id').value;
                
                // Reset errors
                document.querySelectorAll('[id$="-error"]').forEach(el => {
                    el.classList.add('hidden');
                });
                document.getElementById('general-errors').classList.add('hidden');
                
                // Validate name
                if (!name || name.length < 3) {
                    document.getElementById('name-error').classList.remove('hidden');
                    isValid = false;
                    updateDebugInfo('name', 'الاسم غير صالح');
                }
                
                // Validate email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!email || !emailRegex.test(email)) {
                    document.getElementById('email-error').classList.remove('hidden');
                    isValid = false;
                    updateDebugInfo('email', 'البريد الإلكتروني غير صالح');
                }
                
                // Validate password
                if (password.length < 8) {
                    document.getElementById('password-error').classList.remove('hidden');
                    isValid = false;
                    updateDebugInfo('password', 'كلمة المرور قصيرة جدًا');
                }
                
                // Validate password confirmation
                if (password !== confirmPassword) {
                    document.getElementById('password-confirm-error').classList.remove('hidden');
                    isValid = false;
                    updateDebugInfo('password', 'كلمتا المرور غير متطابقتين');
                }
                
                // Validate role - FIXED ISSUE HERE
                if (!roleSelected) {
                    document.getElementById('role-error').classList.remove('hidden');
                    isValid = false;
                    updateDebugInfo('role', 'لم يتم اختيار أي دور');
                } else {
                    updateDebugInfo('role', `الدور المختار: ${roleSelected.value}`);
                }
                
                // Validate POS if role is POS
                if (roleSelected && roleSelected.value === 'pos' && !posSelected) {
                    document.getElementById('pos-error').classList.remove('hidden');
                    isValid = false;
                    updateDebugInfo('pos', 'لم يتم اختيار نقطة بيع');
                }
                
                return isValid;
            }
            
            // Form submission
            document.getElementById('user-create-form').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                if (!validateForm()) {
                    showToast('error', 'الرجاء تصحيح الأخطاء في النموذج');
                    return;
                }
                
                // Get form data
                const formData = {
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    role: document.querySelector('input[name="role"]:checked').value,
                    point_of_sale_id: document.getElementById('point_of_sale_id').value || null
                };
                
                // Log form data
                updateDebugInfo('form', `بيانات النموذج: ${JSON.stringify(formData)}`);
                
                try {
                    // In a real application, you would use fetch or axios to send data
                    // const response = await fetch('/api/users', {
                    //     method: 'POST',
                    //     headers: { 'Content-Type': 'application/json' },
                    //     body: JSON.stringify(formData)
                    // });
                    
                    // Simulate API delay
                    updateDebugInfo('system', 'جاري معالجة الطلب...');
                    await new Promise(resolve => setTimeout(resolve, 1500));
                    
                    // Simulate successful response
                    // const data = await response.json();
                    const data = { 
                        success: true, 
                        message: 'تم إنشاء المستخدم بنجاح',
                        user: {
                            id: Math.floor(Math.random() * 1000),
                            ...formData
                        }
                    };
                    
                    if (data.success) {
                        showToast('success', data.message);
                        updateDebugInfo('system', `تم إنشاء المستخدم بنجاح! الرقم التعريفي: ${data.user.id}`);
                        
                        // Reset form on successful creation
                        document.getElementById('reset-button').click();
                        
                        // Clear selections
                        roleCards.forEach(c => c.classList.remove('selected'));
                        roleInputs.forEach(input => input.checked = false);
                        posField.classList.add('hidden');
                    } else {
                        // Show server validation errors
                        const errorContainer = document.getElementById('general-errors');
                        errorContainer.classList.remove('hidden');
                        errorContainer.innerHTML = '<ul class="list-disc list-inside">';
                        
                        if (data.errors) {
                            data.errors.forEach(error => {
                                errorContainer.innerHTML += `<li>${error}</li>`;
                                updateDebugInfo('error', error);
                            });
                        } else {
                            errorContainer.innerHTML += `<li>${data.message || 'حدث خطأ غير متوقع'}</li>`;
                            updateDebugInfo('error', data.message || 'حدث خطأ غير متوقع');
                        }
                        
                        errorContainer.innerHTML += '</ul>';
                        showToast('error', 'فشل إنشاء المستخدم');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('error', 'فشل الاتصال بالخادم');
                    updateDebugInfo('error', `فشل الاتصال: ${error.message}`);
                }
            });
            
            // Reset form
            document.getElementById('reset-button').addEventListener('click', function() {
                // Clear selections
                roleCards.forEach(c => c.classList.remove('selected'));
                roleInputs.forEach(input => input.checked = false);
                posField.classList.add('hidden');
                
                // Hide errors
                document.querySelectorAll('[id$="-error"]').forEach(el => {
                    el.classList.add('hidden');
                });
                document.getElementById('general-errors').classList.add('hidden');
                
                updateDebugInfo('system', 'تم إعادة تعيين النموذج');
            });
            
            // Enable debug mode with triple click
            let clickCount = 0;
            document.body.addEventListener('click', function() {
                clickCount++;
                if (clickCount === 3) {
                    debugInfo.classList.remove('hidden');
                    updateDebugInfo('system', 'تم تفعيل وضع التصحيح');
                    clickCount = 0;
                }
                
                setTimeout(() => clickCount = 0, 1000);
            });
            
            // Close debug panel
            closeDebug.addEventListener('click', function() {
                debugInfo.classList.add('hidden');
            });
        });
    </script>
</body>
</html>