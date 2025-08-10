@extends('layouts.admin') 

@section('title', 'إدارة الباقات')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">قائمة الباقات</h1>

    <a href="{{ route('admin.packages.create') }}"
       class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">
       إضافة باقة جديدة
    </a>

    {{-- Display success or error messages --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <table class="min-w-full bg-white">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">الاسم</th>
                <th class="px-4 py-2">السعر</th>
                <th class="px-4 py-2">الصلاحية (أيام)</th>
                <th class="px-4 py-2">بروفايل MikroTik</th>
                <th class="px-4 py-2">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
        @foreach($packages as $package)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                <td class="px-4 py-2">{{ $package->name }}</td>
                <td class="px-4 py-2">{{ number_format($package->price) }} ريال</td>
                <td class="px-4 py-2">{{ $package->validity_days }}</td>
                <td class="px-4 py-2">
                    @php
                        // Determine if the profile exists on the MikroTik router
                        $profileFound = false;
                        if (isset($mikrotikProfiles) && is_array($mikrotikProfiles)) {
                            foreach ($mikrotikProfiles as $profile) {
                                if (isset($profile['name']) && $profile['name'] === $package->mikrotik_profile) {
                                    $profileFound = true;
                                    break;
                                }
                            }
                        }
                    @endphp
                    @if($package->mikrotik_profile)
                        <span class="px-2 py-1 rounded-full text-xs {{ $profileFound ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                            {{ $package->mikrotik_profile }}
                        </span>
                    @else
                        <span class="px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600">غير محدد</span>
                    @endif
                </td>
                <td class="px-4 py-2">
                    <a href="{{ route('admin.packages.edit', $package) }}"
                       class="text-blue-600 hover:underline mr-2">تعديل</a>
                    <form action="{{ route('admin.packages.destroy', $package) }}"
                          method="POST" class="inline-block"
                          onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">حذف</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
