@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">تقرير المبيعات</h1>
        
        <div class="flex flex-wrap gap-3 mt-4 md:mt-0">
            <a href="{{ route('admin.reports.export.sales') }}">تصدير إلى إكسل</a> 
               class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-file-excel mr-2"></i> تصدير Excel
            </a>
            
            <a href="{{ route('admin.reports.pdf.sales', request()->query()) }}" 
               class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-file-pdf mr-2"></i> تصدير PDF
            </a>
            
            <button onclick="window.print()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-print mr-2"></i> طباعة
            </button>
        </div>
    </div>

    <!-- فلترة التقرير -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <form action="{{ route('admin.reports.sales') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">نقطة البيع</label>
                    <select name="pos_id" class="w-full px-4 py-2 border rounded-lg">
                        <option value="">جميع النقاط</option>
                        @foreach($points as $point)
                        <option value="{{ $point->id }}" {{ $posId == $point->id ? 'selected' : '' }}>
                            {{ $point->name }} - {{ $point->location }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">من تاريخ</label>
                    <input type="date" name="start_date" class="w-full px-4 py-2 border rounded-lg" 
                           value="{{ $startDate }}">
                </div>
                
                <div>
                    <label class="block text-gray-700 mb-2">إلى تاريخ</label>
                    <input type="date" name="end_date" class="w-full px-4 py-2 border rounded-lg" 
                           value="{{ $endDate }}">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg h-[42px] w-full">
                        <i class="fas fa-filter mr-2"></i> تطبيق الفلتر
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ملخص الإحصائيات -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-700 rounded-xl shadow-lg p-6 mb-8 text-white">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
            <div>
                <p class="text-lg">عدد المعاملات</p>
                <p class="text-3xl font-bold">{{ $transactions->total() }}</p>
            </div>
            <div>
                <p class="text-lg">إجمالي المبيعات</p>
                <p class="text-3xl font-bold">{{ number_format($totalAmount) }} ريال</p>
            </div>
            <div>
                <p class="text-lg">متوسط المبيعات اليومية</p>
                <p class="text-3xl font-bold">{{ number_format($totalAmount / max(1, $transactions->count())) }} ريال</p>
            </div>
        </div>
    </div>

    <!-- جدول المعاملات -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نقطة البيع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الوصف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $transaction->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $transaction->pointOfSale->name }}</div>
                        <div class="text-sm text-gray-500">{{ $transaction->pointOfSale->location }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                        {{ number_format($transaction->amount) }} ريال
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $transaction->description }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- التقسيم الصفحي -->
    <div class="mt-6">
        {{ $transactions->appends(request()->query())->links() }}
    </div>
</div>
@endsection
