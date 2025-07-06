@extends('layouts.accountant')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-6 py-4">
            <h1 class="text-2xl font-bold text-white">إنشاء نقطة بيع جديدة</h1>
        </div>
        
        <div class="p-6">
            <form action="{{ route('accountant.pos.store') }}" method="POST">
                @csrf
                
                <!-- معلومات نقطة البيع -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">معلومات نقطة البيع</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-2">اسم نقطة البيع *</label>
                            <input type="text" id="name" name="name" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="مثال: بقالة المدينة" required>
                        </div>
                        
                        <div>
                            <label for="location" class="block text-gray-700 font-medium mb-2">الموقع *</label>
                            <input type="text" id="location" name="location" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="مثال: صنعاء - شارع التحرير" required>
                        </div>
                        
                        <div>
                            <label for="supervisor_id" class="block text-gray-700 font-medium mb-2">المشرف المسؤول *</label>
                            <select id="supervisor_id" name="supervisor_id" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">اختر مشرف</option>
                                @foreach($supervisors as $supervisor)
                                <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- معلومات مستخدم نقطة البيع -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800">معلومات مستخدم نقطة البيع</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="pos_user_name" class="block text-gray-700 font-medium mb-2">اسم المستخدم *</label>
                            <input type="text" id="pos_user_name" name="pos_user_name" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="اسم المستخدم لنقطة البيع" required>
                        </div>
                        
                        <div>
                            <label for="pos_user_email" class="block text-gray-700 font-medium mb-2">البريد الإلكتروني *</label>
                            <input type="email" id="pos_user_email" name="pos_user_email" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="بريد المستخدم" required>
                        </div>
                        
                        <div>
                            <label for="pos_user_password" class="block text-gray-700 font-medium mb-2">كلمة المرور *</label>
                            <input type="password" id="pos_user_password" name="pos_user_password" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="كلمة مرور قوية" required>
                        </div>
                        
                        <div>
                            <label for="pos_user_password_confirmation" class="block text-gray-700 font-medium mb-2">تأكيد كلمة المرور *</label>
                            <input type="password" id="pos_user_password_confirmation" name="pos_user_password_confirmation" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   placeholder="أعد إدخال كلمة المرور" required>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                        إنشاء نقطة البيع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection