@extends('layouts.pos')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-6">سجل المعاملات</h1>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">#</th>
                        <th class="py-2 px-4 border-b">النوع</th>
                        <th class="py-2 px-4 border-b">المبلغ</th>
                        <th class="py-2 px-4 border-b">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $loop->iteration }}</td>
                        <td class="py-2 px-4 border-b">{{ $transaction->type == 'credit' ? 'إيداع' : 'سحب' }}</td>
                        <td class="py-2 px-4 border-b">{{ number_format($transaction->amount) }}</td>
                        <td class="py-2 px-4 border-b">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection