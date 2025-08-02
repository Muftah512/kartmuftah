@extends('layouts.pos')

@section('title', 'تقارير المبيعات')

@section('breadcrumb')
    <li class="inline-flex items-center">
        <span class="text-gray-600">/</span>
    </li>
    <li class="inline-flex items-center">
        <a href="{{ route('pos.sales') }}" class="text-gray-700 hover:text-blue-600">تقارير المبيعات</a>
    </li>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">تقارير المبيعات</h1>
        </div>

        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4">تصفية التقارير</h2>
                <form method="GET" action="{{ route('pos.sales') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-gray-700 mb-2">من تاريخ</label>
                        <input type="date" name="start_date" class="w-full px-4 py-2 border rounded-lg" value="{{ request('start_date') }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">إلى تاريخ</label>
                        <input type="date" name="end_date" class="w-full px-4 py-2 border rounded-lg" value="{{ request('end_date') }}">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">نوع الباقة</label>
                        <select name="package_id" class="w-full px-4 py-2 border rounded-lg">
                            <option value="">جميع الباقات</option>
                            @foreach($packages as $package)
                            <option value="{{ $package->id }}" {{ request('package_id') == $package->id ? 'selected' : '' }}>
                                {{ $package->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full">
                            تطبيق التصفية
                        </button>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الكرت</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الباقة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم العميل</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($sales as $sale)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-mono">{{ $sale->username }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $sale->package->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $sale->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-green-600 font-bold">{{ number_format($sale->package->price) }} ريال</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $sale->customer_phone ? '+967' . $sale->customer_phone : '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                لا توجد مبيعات في الفترة المحددة
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-50 font-bold">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right">الإجمالي:</td>
                            <td class="px-6 py-4 text-green-600">{{ number_format($totalSales) }} ريال</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-6">
                {{ $sales->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
