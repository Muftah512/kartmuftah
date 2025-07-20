@extends('layouts.pos')

@section('title', 'لوحة التحكم')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <span class="text-gray-600">/ لوحة التحكم</span>
    </li>
@endsection

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">لوحة تحكم نقطة البيع</h1>
    
    <!-- بطاقات الإحصائيات -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold mb-2">الرصيد الحالي</h2>
                    <p class="text-3xl font-bold">{{ number_format($balance) }} ريال</p>
                </div>
                <i class="fas fa-wallet text-4xl opacity-50"></i>
            </div>
            <div class="mt-4">
                <a href="#" class="text-white hover:underline">طلب زيادة رصيد</a>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-teal-600 text-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold mb-2">كروت اليوم</h2>
                    <p class="text-3xl font-bold">{{ $todayCards }}</p>
                </div>
                <i class="fas fa-sim-card text-4xl opacity-50"></i>
            </div>
            <div class="mt-4">
                <span class="text-sm">من {{ date('d/m/Y') }}</span>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-pink-600 text-white p-6 rounded-lg shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold mb-2">مبيعات اليوم</h2>
                    <p class="text-3xl font-bold">{{ number_format($todaySales) }} ريال</p>
                </div>
                <i class="fas fa-chart-line text-4xl opacity-50"></i>
            </div>
            <div class="mt-4">
                <span class="text-sm">من {{ date('d/m/Y') }}</span>
            </div>
        </div>
    </div>
    
    <!-- الرسوم البيانية والإحصائيات -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- إحصائيات الأسبوع -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">إحصائيات الأسبوع</h2>
            <p class="text-gray-600 mb-4">من {{ date('d/m/Y', strtotime($startDate)) }} إلى {{ date('d/m/Y', strtotime($endDate)) }}</p>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اليوم</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد الكروت</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قيمة المبيعات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($weeklyData as $day)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $day['day'] }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-center">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    {{ $day['cards'] }}
                                </span>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-green-600 font-medium">
                                {{ number_format($day['sales']) }} ريال
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 font-bold">
                        <tr>
                            <td class="px-4 py-2">الإجمالي</td>
                            <td class="px-4 py-2 text-center">
                                <span class="px-2 py-1 bg-blue-500 text-white rounded-full">
                                    {{ collect($weeklyData)->sum('cards') }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-green-600">
                                {{ number_format(collect($weeklyData)->sum('sales')) }} ريال
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <!-- آخر المعاملات -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">آخر المعاملات</h2>
                <a href="{{ route('pos.transactions') }}" class="text-blue-600 hover:text-blue-800 text-sm">
                    عرض الكل <i class="fas fa-arrow-left ml-1"></i>
                </a>
            </div>
            
            <div class="space-y-4">
                @forelse($recentTransactions as $transaction)
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium">{{ $transaction->description }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $transaction->created_at->format('d/m/Y H:i') }} 
                                بواسطة {{ $transaction->user->name }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="{{ $transaction->type === 'credit' ? 'text-green-600' : 'text-red-600' }} font-bold">
                                {{ $transaction->type === 'credit' ? '+' : '-' }}
                                {{ number_format($transaction->amount) }} ريال
                            </p>
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">
                                {{ $transaction->type === 'credit' ? 'إيداع' : 'سحب' }}
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-exchange-alt text-4xl mb-4"></i>
                    <p>لا توجد معاملات حديثة</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- آخر الكروت المولدة -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">آخر الكروت المولدة</h2>
            <a href="{{ route('pos.cards.generate') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-plus-circle mr-2"></i> توليد جديد
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($recentCards as $card)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-lg text-indigo-600">{{ $card->username }}</h3>
                        <p class="text-gray-600">{{ $card->package->name }}</p>
                    </div>
                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                        {{ $card->package->validity_days }} يوم
                    </span>
                </div>
                
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <div>
                        <p class="text-sm text-gray-500">تاريخ الإنشاء</p>
                        <p>{{ $card->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">تاريخ الانتهاء</p>
                        <p>{{ $card->expiration_date->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                
                <div class="mt-4 flex justify-between">
                    <span class="text-lg font-bold text-gray-800">
                        {{ number_format($card->package->price) }} ريال
                    </span>
                    <div class="flex space-x-2">
                        <button class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-print"></i>
                        </button>
                        <button class="text-green-600 hover:text-green-800">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-8 text-gray-500">
                <i class="fas fa-sim-card text-4xl mb-4"></i>
                <p>لا توجد كروت مولدة حديثاً</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- إجراءات سريعة -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <a href="{{ route('pos.cards.generate') }}" class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-6 rounded-lg shadow text-center hover:shadow-lg transition-shadow">
            <i class="fas fa-sim-card text-3xl mb-3"></i>
            <h3 class="text-lg font-semibold">إنشاء كرت جديد</h3>
        </a>
        
        <a href="{{ route('pos.cards.recharge') }}" class="bg-gradient-to-r from-green-500 to-teal-600 text-white p-6 rounded-lg shadow text-center hover:shadow-lg transition-shadow">
            <i class="fas fa-redo text-3xl mb-3"></i>
            <h3 class="text-lg font-semibold">إعادة شحن كرت</h3>
        </a>
        
        <a href="{{ route('pos.sales') }}" class="bg-gradient-to-r from-amber-500 to-orange-600 text-white p-6 rounded-lg shadow text-center hover:shadow-lg transition-shadow">
            <i class="fas fa-chart-bar text-3xl mb-3"></i>
            <h3 class="text-lg font-semibold">تقارير المبيعات</h3>
        </a>
        
        <a href="{{ route('pos.transactions') }}" class="bg-gradient-to-r from-pink-500 to-rose-600 text-white p-6 rounded-lg shadow text-center hover:shadow-lg transition-shadow">
            <i class="fas fa-exchange-alt text-3xl mb-3"></i>
            <h3 class="text-lg font-semibold">سجل المعاملات</h3>
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // يمكنك إضافة أي سكريبتات تفاعلية هنا
    document.addEventListener('DOMContentLoaded', function() {
        // مثال: تحديث البيانات كل 5 دقائق
        setInterval(() => {
            // هنا كود لتحديث البيانات دون إعادة تحميل الصفحة
            console.log('تحديث بيانات لوحة التحكم...');
        }, 300000); // 5 دقائق
    });
</script>
@endsection