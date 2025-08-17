@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">تقارير شحن الرصيد — لكل محاسب</h1>

        <a href="{{ route('admin.accountants.topups.export', request()->query()) }}"
           class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-black">
            تصدير CSV
        </a>
    </div>

    {{-- فلاتر --}}
    <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-6 bg-white p-4 rounded-xl shadow">
        <div>
            <label class="block text-sm font-medium mb-1">من تاريخ</label>
            <input type="date" name="date_from" value="{{ $filters['date_from'] }}" class="w-full border rounded-md p-2">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">إلى تاريخ</label>
            <input type="date" name="date_to" value="{{ $filters['date_to'] }}" class="w-full border rounded-md p-2">
        </div>
        <div class="md:col-span-2">
            <label class="block text-sm font-medium mb-1">المحاسب (اختياري لعرض التفصيل)</label>
            <select name="accountant_id" class="w-full border rounded-md p-2">
                <option value="">الكل</option>
                @foreach($accountants as $a)
                    <option value="{{ $a->id }}" @selected($filters['accountant_id']==$a->id)>{{ $a->name }} ({{ $a->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 w-full">تطبيق</button>
        </div>
    </form>

    {{-- ملخص لكل محاسب --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto mb-8">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-right">
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">المحاسب</th>
                    <th class="px-4 py-3">البريد</th>
                    <th class="px-4 py-3">نقاط البيع (إجمالي)</th>
                    <th class="px-4 py-3">نقاط أُنشئت خلال الفترة</th>
                    <th class="px-4 py-3">عدد عمليات الشحن (credit)</th>
                    <th class="px-4 py-3">إجمالي الشحن (credit)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($summaryRows as $i => $r)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $i+1 }}</td>
                        <td class="px-4 py-2 font-medium">{{ $r['accountant_name'] }}</td>
                        <td class="px-4 py-2">{{ $r['accountant_email'] }}</td>
                        <td class="px-4 py-2">{{ $r['pos_total'] }}</td>
                        <td class="px-4 py-2">{{ $r['pos_created_in_range'] }}</td>
                        <td class="px-4 py-2">{{ $r['topup_ops'] }}</td>
                        <td class="px-4 py-2">{{ number_format($r['topup_total'], 2) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- تفصيل حسب نقاط البيع عندما يتم اختيار محاسب --}}
    @if($filters['accountant_id'])
        <h2 class="text-xl font-semibold mb-3">
            التفصيل لنقاط بيع المحاسب المحدد (credit)
        </h2>

        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr class="text-right">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">نقطة البيع</th>
                        <th class="px-4 py-3">البريد</th>
                        <th class="px-4 py-3">الهاتف</th>
                        <th class="px-4 py-3">تاريخ الإنشاء</th>
                        <th class="px-4 py-3">عمليات الشحن</th>
                        <th class="px-4 py-3">إجمالي الشحن</th>
                        <th class="px-4 py-3">آخر شحن</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($perPos as $i => $p)
                        <tr class="border-b">
                            <td class="px-4 py-2 align-top">{{ $i+1 }}</td>
                            <td class="px-4 py-2 align-top font-medium">{{ $p->name }}</td>
                            <td class="px-4 py-2 align-top">{{ $p->email }}</td>
                            <td class="px-4 py-2 align-top">{{ $p->phone }}</td>
                            <td class="px-4 py-2 align-top">{{ \Carbon\Carbon::parse($p->created_at)->format('Y-m-d') }}</td>
                            <td class="px-4 py-2 align-top">{{ $p->topup_ops }}</td>
                            <td class="px-4 py-2 align-top">{{ number_format($p->topup_total, 2) }}</td>
                            <td class="px-4 py-2 align-top">{{ $p->last_topup_at ? \Carbon\Carbon::parse($p->last_topup_at)->format('Y-m-d H:i') : '-' }}</td>
                        </tr>

                        {{-- جدول فرعي لسجلات الشحن لهذه النقطة خلال الفترة --}}
                        @if(isset($itemsByPos[$p->id]) && count($itemsByPos[$p->id]))
                            <tr class="bg-gray-50">
                                <td colspan="8" class="px-4 py-2">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full text-xs">
                                            <thead>
                                                <tr class="text-right">
                                                    <th class="px-2 py-2">التاريخ</th>
                                                    <th class="px-2 py-2">المبلغ</th>
                                                    <th class="px-2 py-2">النوع</th>
                                                    <th class="px-2 py-2">ملاحظات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($itemsByPos[$p->id] as $it)
                                                    <tr class="border-b">
                                                        <td class="px-2 py-1">{{ \Carbon\Carbon::parse($it->created_at)->format('Y-m-d H:i') }}</td>
                                                        <td class="px-2 py-1">{{ number_format($it->amount, 2) }}</td>
                                                        <td class="px-2 py-1">{{ $it->type }}</td>
                                                        <td class="px-2 py-1">{{ $it->note ?? '' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr><td colspan="8" class="px-4 py-6 text-center text-gray-500">لا توجد نقاط بيع لهذا المحاسب أو لا توجد شحنات خلال الفترة.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
