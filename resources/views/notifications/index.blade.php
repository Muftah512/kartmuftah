@extends('layouts.admin')

@section('title', 'الإشعارات')
@section('page-title', 'الإشعارات')
@section('current-page', 'الإشعارات')

@section('content')
<div class="bg-white rounded-xl shadow p-4">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold">
            الإشعارات
            <span class="text-sm text-gray-500">({{ $unreadCount }} غير مقروء)</span>
        </h2>

        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                تعليم الكل كمقروء
            </button>
        </form>
    </div>

    @if (session('status'))
        <div class="mb-3 p-3 rounded bg-green-50 text-green-700 text-sm">
            {{ session('status') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-right">
                    <th class="px-4 py-3">العنوان</th>
                    <th class="px-4 py-3">التفاصيل</th>
                    <th class="px-4 py-3">التاريخ</th>
                    <th class="px-4 py-3">الحالة</th>
                    <th class="px-4 py-3">فتح</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($notifications as $n)
                    @php $data = $n->data; @endphp
                    <tr class="{{ $n->read_at ? '' : 'bg-amber-50' }}">
                        <td class="px-4 py-3 font-medium">{{ $data['title'] ?? 'إشعار' }}</td>

                        <td class="px-4 py-3 text-gray-700">
                            @switch($data['type'] ?? null)
                                @case('topup')
                                    نقطة: {{ $data['pos_name'] ?? $data['pos_id'] }} — مبلغ: {{ number_format($data['amount'] ?? 0, 2) }}
                                    @break

                                @case('card')
                                    مستخدم: {{ $data['username'] ?? '' }} — باقة: {{ $data['package'] ?? '' }} — سعر: {{ number_format($data['price'] ?? 0, 2) }}
                                    @break

                                @case('pos_low_balance')
                                    {{ $data['pos_name'] ?? 'نقطة' }} — الرصيد: {{ number_format($data['balance'] ?? 0, 2) }}
                                    (الحد: {{ number_format($data['threshold'] ?? 0, 0) }})
                                    @break

                                @default
                                    {{ \Illuminate\Support\Str::limit(json_encode($data, JSON_UNESCAPED_UNICODE), 100) }}
                            @endswitch
                        </td>

                        <td class="px-4 py-3 text-gray-500">{{ optional($n->created_at)->format('Y-m-d H:i') }}</td>

                        <td class="px-4 py-3">
                            @if($n->read_at)
                                <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs">مقروء</span>
                            @else
                                <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs">غير مقروء</span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                                @csrf
                                <button class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs">
                                    فتح/تعليم كمقروء
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">لا توجد إشعارات</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
