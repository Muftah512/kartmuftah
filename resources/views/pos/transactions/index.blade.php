@extends('layouts.pos')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-extrabold text-gray-900">سجل المعاملات</h1>
            @if(isset($transactions) && $transactions->total())
                <span class="text-sm text-gray-700">
                    إجمالي: <strong class="text-gray-900">{{ number_format($transactions->total()) }}</strong> عملية
                </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            @if($transactions->count())
            <table class="table-report w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>النوع</th>
                        <th>المبلغ</th>
                        <th>التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        @php
                            $isCredit = $transaction->type === 'credit';
                            $typeLabel = $isCredit ? 'إيداع' : 'سحب';
                            $typeBadge = $isCredit ? 'badge-success' : 'badge-warning';
                        @endphp
                        <tr>
                            <td class="text-gray-900">{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}</td>
                            <td>
                                <span class="cell-badge {{ $typeBadge }}">
                                    <i class="fa-solid {{ $isCredit ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }}"></i>
                                    {{ $typeLabel }}
                                </span>
                            </td>
                            <td class="text-right font-semibold text-gray-900">
                                <span class="font-mono">{{ number_format($transaction->amount) }}</span>
                                <span class="text-sm text-gray-700">ريال</span>
                            </td>
                            <td class="text-gray-900">
                                {{ $transaction->created_at->format('Y-m-d H:i') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="p-8 text-center">
                    <p class="text-lg text-gray-700">لا توجد معاملات حاليًا.</p>
                    <p class="text-sm muted-strong mt-1">ستظهر العمليات هنا عند إجراء إيداع أو سحب.</p>
                </div>
            @endif
        </div>

        @if($transactions->hasPages())
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
