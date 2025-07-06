@extends('layouts.accountant')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-indigo-700 px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold text-white">ÝÇÊæÑÉ ÔÍä ÑÕíÏ</h1>
                <div class="flex space-x-3">
                    <button onclick="window.print()" class="bg-white text-purple-700 px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-print mr-2"></i> ØÈÇÚÉ
                    </button>
                    <a href="{{ route('accountant.invoices.download', $invoice) }}" class="bg-white text-purple-700 px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-download mr-2"></i> ÊÍãíá PDF
                    </a>
                </div>
            </div>
        </div>
        
        <div class="p-8">
            <!-- ãÚáæãÇÊ ÇáÝÇÊæÑÉ -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">ãÚáæãÇÊ ÇáÝÇÊæÑÉ</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">ÑÞã ÇáÝÇÊæÑÉ:</span> INV-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</p>
                        <p><span class="font-medium">ÊÇÑíÎ ÇáÅÕÏÇÑ:</span> {{ $invoice->created_at->format('d/m/Y') }}</p>
                        <p><span class="font-medium">ÇáÍÇáÉ:</span> 
                            <span class="px-2 py-1 rounded-full text-sm font-medium 
                                {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $invoice->status == 'paid' ? 'ãÏÝæÚÉ' : 'ÛíÑ ãÏÝæÚÉ' }}
                            </span>
                        </p>
                    </div>
                </div>
                
                <div>
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">ãÚáæãÇÊ äÞØÉ ÇáÈíÚ</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">ÇÓã ÇáäÞØÉ:</span> {{ $invoice->pointOfSale->name }}</p>
                        <p><span class="font-medium">ÇáãæÞÚ:</span> {{ $invoice->pointOfSale->location }}</p>
                        <p><span class="font-medium">ÇáãÔÑÝ ÇáãÓÄæá:</span> {{ $invoice->pointOfSale->supervisor->name }}</p>
                    </div>
                </div>
            </div>
            
            <!-- ÊÝÇÕíá ÇáÝÇÊæÑÉ -->
            <div class="border-t border-gray-200 pt-6 mb-8">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">ÊÝÇÕíá ÇáÝÇÊæÑÉ</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ÇáæÕÝ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ÇáãÈáÛ</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">ÇáÊÇÑíÎ</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($invoice->transactions as $transaction)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $transaction->description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($transaction->amount) }} ÑíÇá
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $transaction->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                    ÇáÅÌãÇáí
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ number_format($invoice->amount) }} ÑíÇá
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- ãáÇÍÙÇÊ -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            ÓíÊã ÅÖÇÝÉ åÐÇ ÇáãÈáÛ Åáì ÑÕíÏ äÞØÉ ÇáÈíÚ ÝæÑ ÊÃßíÏ ÇáÏÝÚ. íÑÌì ÇáÇÍÊÝÇÙ ÈäÓÎÉ ãä åÐå ÇáÝÇÊæÑÉ ßÓÌá ãÇáí.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- ÊæÞíÚ -->
            <div class="mt-8 text-center border-t pt-6">
                <p class="text-gray-600">ÔßÑÇð áÊÚÇãáßã ãÚ äÙÇã ßÑÊ ÇáãÝÊÇÍ</p>
                <p class="text-gray-500 text-sm mt-2">ÌãíÚ ÇáÍÞæÞ ãÍÝæÙÉ &copy; {{ date('Y') }}</p>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        .print-content, .print-content * {
            visibility: visible;
        }
        .print-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .no-print {
            display: none;
        }
    }
</style>
@endsection