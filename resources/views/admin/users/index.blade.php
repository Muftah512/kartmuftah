@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">إدارة المستخدمين</h1>
            <p class="text-gray-600 mt-1">عرض وتعديل جميع مستخدمي النظام</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
            <a href="{{ route('admin.users.create') }}" class="bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white px-4 py-3 rounded-lg flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i>
                إضافة مستخدم جديد
            </a>
        </div>
    </div>

    <!-- بطاقات الإحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-700 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm opacity-80">إجمالي المستخدمين</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $totalUsers }}</h3>
                </div>
                <i class="fas fa-users text-3xl opacity-70"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-700 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm opacity-80">المستخدمين النشطين</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $activeUsers }}</h3>
                </div>
                <i class="fas fa-user-check text-3xl opacity-70"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-700 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm opacity-80">مديري النظام</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $adminUsers }}</h3>
                </div>
                <i class="fas fa-crown text-3xl opacity-70"></i>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-amber-500 to-amber-700 rounded-xl shadow-lg p-6 text-white">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm opacity-80">موظفي نقاط البيع</p>
                    <h3 class="text-2xl font-bold mt-1">{{ $posUsers }}</h3>
                </div>
                <i class="fas fa-cash-register text-3xl opacity-70"></i>
            </div>
        </div>
    </div>

    <!-- أدوات البحث والتصفية -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">بحث بالاسم أو البريد</label>
                <input type="text" id="searchInput" placeholder="ابحث هنا..." 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">حالة المستخدم</label>
                <select id="statusFilter" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">جميع الحالات</option>
                    <option value="active">نشط فقط</option>
                    <option value="inactive">معطل فقط</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الدور</label>
                <select id="roleFilter" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">جميع الأدوار</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}">{{ $role }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                <button id="resetFilters" class="bg-gray-200 hover:bg-gray-300 text-gray-800 w-full py-2 rounded-lg flex items-center justify-center">
                    <i class="fas fa-redo mr-2"></i> إعادة التعيين
                </button>
            </div>
        </div>
    </div>

    <!-- جدول المستخدمين -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-800 to-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-right text-sm font-semibold text-white uppercase tracking-wider">المستخدم</th>
                        <th scope="col" class="px-6 py-4 text-right text-sm font-semibold text-white uppercase tracking-wider">معلومات الاتصال</th>
                        <th scope="col" class="px-6 py-4 text-right text-sm font-semibold text-white uppercase tracking-wider">الدور</th>
                        <th scope="col" class="px-6 py-4 text-right text-sm font-semibold text-white uppercase tracking-wider">نقطة البيع</th>
                        <th scope="col" class="px-6 py-4 text-right text-sm font-semibold text-white uppercase tracking-wider">الحالة</th>
                        <th scope="col" class="px-6 py-4 text-right text-sm font-semibold text-white uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="usersTable">
                    @foreach ($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="bg-gray-200 border-2 border-dashed rounded-xl w-10 h-10 flex items-center justify-center text-gray-500 mr-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $user->email }}</div>
                            <div class="text-sm text-gray-500">{{ $user->phone ?? 'لا يوجد' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                @foreach ($user->roles as $role)
                                    <span class="px-2 py-1 rounded-full text-xs font-medium 
                                        {{ $role->name == 'admin' ? 'bg-purple-100 text-purple-800' : 
                                           ($role->name == 'pos' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4">
                          @forelse($user->pointOfSale as $pos)
                            <span class="badge">{{ $pos->name }}</span>
                                 @empty
                                    N/A
                                 @endforelse
                                   </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'نشط' : 'معطل' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center justify-end space-x-3">
                                <button onclick="toggleStatus({{ $user->id }})" class="p-2 rounded-lg 
                                    {{ $user->is_active ? 'bg-green-100 hover:bg-green-200 text-green-700' : 'bg-red-100 hover:bg-red-200 text-red-700' }}"
                                    title="{{ $user->is_active ? 'تعطيل المستخدم' : 'تفعيل المستخدم' }}">
                                    <i class="fas {{ $user->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                                
                                <a href="{{ route('admin.users.edit', $user->id) }}" 
                                   class="p-2 rounded-lg bg-blue-100 hover:bg-blue-200 text-blue-700"
                                   title="تعديل المستخدم">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                @if (!$user->hasRole('المدير العام'))
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 rounded-lg bg-red-100 hover:bg-red-200 text-red-700"
                                            title="حذف المستخدم"
                                            onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- التصفح بين الصفحات -->
        @if($users->hasPages())
        <div class="bg-white px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
    
    <!-- حالة عدم وجود مستخدمين -->
    @if($users->isEmpty())
    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
        <i class="fas fa-users-slash text-5xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">لا يوجد مستخدمين</h3>
        <p class="text-gray-500 mb-6">لم يتم إضافة أي مستخدمين بعد. ابدأ بإضافة مستخدم جديد.</p>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-flex items-center">
            <i class="fas fa-plus mr-2"></i> إضافة مستخدم جديد
        </a>
    </div>
    @endif
</div>

<!-- نموذج تبديل الحالة المخفي -->
<form id="toggleStatusForm" method="POST" action="">
    @csrf
    @method('PATCH')
</form>

<script>
    // تبديل حالة المستخدم
    function toggleStatus(userId) {
        if (confirm('هل أنت متأكد من تغيير حالة المستخدم؟')) {
            const form = document.getElementById('toggleStatusForm');
            form.action = `/admin/users/${userId}/toggle-status`;
            form.submit();
        }
    }

    // البحث والتصفية
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const roleFilter = document.getElementById('roleFilter');
        const resetBtn = document.getElementById('resetFilters');
        const rows = document.querySelectorAll('#usersTable tr');
        
        function filterUsers() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value;
            const roleValue = roleFilter.value;
            
            rows.forEach(row => {
                const name = row.querySelector('.text-gray-900:first-child').textContent.toLowerCase();
                const email = row.querySelector('.text-gray-900:nth-child(2)').textContent.toLowerCase();
                const role = row.querySelector('.text-sm').textContent.toLowerCase();
                const status = row.querySelector('.font-semibold').textContent;
                
                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesStatus = statusValue === 'all' || 
                                     (statusValue === 'active' && status === 'نشط') || 
                                     (statusValue === 'inactive' && status === 'معطل');
                const matchesRole = roleValue === 'all' || role.includes(roleValue.toLowerCase());
                
                if (matchesSearch && matchesStatus && matchesRole) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
        
        searchInput.addEventListener('input', filterUsers);
        statusFilter.addEventListener('change', filterUsers);
        roleFilter.addEventListener('change', filterUsers);
        
        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = 'all';
            roleFilter.value = 'all';
            filterUsers();
        });
    });
</script>

<style>
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 20px 0 0 0;
    }
    
    .pagination li {
        margin: 0 4px;
    }
    
    .pagination li a, 
    .pagination li span {
        display: block;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        color: #4B5563;
        border: 1px solid #E5E7EB;
        transition: all 0.3s;
    }
    
    .pagination li a:hover {
        background-color: #F3F4F6;
    }
    
    .pagination .active span {
        background: linear-gradient(to right, #3B82F6, #6366F1);
        color: white;
        border-color: #3B82F6;
    }
    
    .pagination .disabled span {
        color: #9CA3AF;
        cursor: not-allowed;
    }
</style>
@endsection
