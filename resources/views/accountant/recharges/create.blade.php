@extends('layouts.accountant')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-teal-700 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">ÔÍä ÑÕíÏ äÞØÉ ÈíÚ</h1>
        </div>
        
        <div class="p-6">
            <form action="{{ route('accountant.recharges.store') }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="pos_id" class="block text-gray-700 font-medium mb-2">äÞØÉ ÇáÈíÚ *</label>
                    <select id="pos_id" name="pos_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        <option value="">ÇÎÊÑ äÞØÉ ÇáÈíÚ</option>
                        @foreach($points as $point)
                        <option value="{{ $point->id }}" data-balance="{{ $point->balance }}">
                            {{ $point->name }} ({{ $point->location }})
                        </option>
                        @endforeach
                    </select>
                    
                    <div id="current-balance" class="mt-2 text-sm text-gray-500 hidden">
                        ÇáÑÕíÏ ÇáÍÇáí: <span id="balance-value" class="font-semibold"></span> ÑíÇá
                    </div>
                </div>
                
                <div class="mb-6">
                    <label for="amount" class="block text-gray-700 font-medium mb-2">ãÈáÛ ÇáÔÍä (ÑíÇá íãäí) *</label>
                    <input type="number" id="amount" name="amount" min="1000" step="1000"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                           placeholder="ÃÏÎá ÇáãÈáÛ" required>
                    <p class="text-sm text-gray-500 mt-1">ÇáÍÏ ÇáÃÏäì ááÔÍä: 1,000 ÑíÇá</p>
                </div>
                
                <div class="mb-6">
                    <label for="payment_method" class="block text-gray-700 font-medium mb-2">ØÑíÞÉ ÇáÏÝÚ *</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="cash" class="form-radio text-green-600" required>
                                <span class="mr-2">äÞÏí</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="bank_transfer" class="form-radio text-green-600">
                                <span class="mr-2">ÊÍæíá Èäßí</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                        ÔÍä ÇáÑÕíÏ æÅäÔÇÁ ÇáÝÇÊæÑÉ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('pos_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const balance = selectedOption.getAttribute('data-balance');
        
        if (balance) {
            document.getElementById('current-balance').classList.remove('hidden');
            document.getElementById('balance-value').textContent = 
                new Intl.NumberFormat().format(balance);
        } else {
            document.getElementById('current-balance').classList.add('hidden');
        }
    });
</script>
@endsection