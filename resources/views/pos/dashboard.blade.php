@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">لوحة تحكم نقطة البيع</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">الرصيد الحالي</h2>
            <p class="text-3xl font-bold text-green-600">{{ number_format(auth()->user()->pointOfSale->balance) }} ريال</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">إنشاء كرت جديد</h2>
            <form id="generate-card-form">
                @csrf
                <div class="mb-4">
                    <select name="package_id" class="w-full p-2 border rounded">
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->name }} - {{ $package->price }} ريال</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">توليد</button>
            </form>
        </div>
    </div>
    
    <div id="card-result" class="mt-8 hidden">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">الكارت المولد</h2>
            <div class="flex items-center">
                <div class="bg-gray-100 p-4 rounded mr-4">
                    <span id="generated-username" class="text-2xl font-mono"></span>
                </div>
                <button id="print-card" class="bg-green-500 text-white px-4 py-2 rounded">
                    طباعة
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('generate-card-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const response = await fetch('/pos/cards', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if(data.success) {
            document.getElementById('generated-username').textContent = data.username;
            document.getElementById('card-result').classList.remove('hidden');
        }
    });
</script>
@endsection@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">لوحة تحكم نقطة البيع</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">الرصيد الحالي</h2>
            <p class="text-3xl font-bold text-green-600">{{ number_format(auth()->user()->pointOfSale->balance) }} ريال</p>
        </div>
        
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">إنشاء كرت جديد</h2>
            <form id="generate-card-form">
                @csrf
                <div class="mb-4">
                    <select name="package_id" class="w-full p-2 border rounded">
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->name }} - {{ $package->price }} ريال</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">توليد</button>
            </form>
        </div>
    </div>
    
    <div id="card-result" class="mt-8 hidden">
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">الكارت المولد</h2>
            <div class="flex items-center">
                <div class="bg-gray-100 p-4 rounded mr-4">
                    <span id="generated-username" class="text-2xl font-mono"></span>
                </div>
                <button id="print-card" class="bg-green-500 text-white px-4 py-2 rounded">
                    طباعة
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('generate-card-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const response = await fetch('/pos/cards', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if(data.success) {
            document.getElementById('generated-username').textContent = data.username;
            document.getElementById('card-result').classList.remove('hidden');
        }
    });
</script>
@endsection