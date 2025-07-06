<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📊 تقرير بطاقات الإنترنت
            </h2>
            <div class="flex space-x-2">
                <x-export-buttons :url="route('admin.reports.export.cards')" />
                <x-refresh-button />
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- إحصائيات سريعة -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-stat-card title="البطاقات اليوم"    :value="$todayCards"   icon="📅" color="bg-blue-100 text-blue-800" />
            <x-stat-card title="البطاقات النشطة"  :value="$activeCards"  icon="✅" color="bg-green-100 text-green-800" />
            <x-stat-card title="البطاقات المستخدمة":value="$usedCards"    icon="🔑" color="bg-purple-100 text-purple-800" />
            <x-stat-card title="متوسط الإنشاء/يوم" :value="$averageDaily" icon="📈" color="bg-amber-100 text-amber-800" />
        </div>

        <!-- فلتر متقدم -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div>
                    <x-label for="start_date" value="من تاريخ" />
                    <x-input name="start_date" id="start_date" type="date" value="{{ request('start_date') }}" />
                </div>
                <div>
                    <x-label for="end_date" value="إلى تاريخ" />
                    <x-input name="end_date"   id="end_date"   type="date" value="{{ request('end_date') }}" />
                </div>
                <div>
                    <x-label for="pos_id" value="نقطة البيع" />
                    <x-select name="pos_id" id="pos_id">
                        <option value="">الكل</option>
                        @foreach($pointsOfSale as $pos)
                            <option value="{{ $pos->id }}" @selected(request('pos_id') == $pos->id)>
                                {{ $pos->name }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
                <div>
                    <x-label for="package_id" value="الباقة" />
                    <x-select name="package_id" id="package_id">
                        <option value="">الكل</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" @selected(request('package_id') == $package->id)>
                                {{ $package->name }}
                            </option>
                        @endforeach
                    </x-select>
                </div>
                <div class="flex space-x-2">
                    <x-button type="submit" class="bg-indigo-600 hover:bg-indigo-700 w-full">
                        <i class="fas fa-filter mr-2"></i> تصفية
                    </x-button>
                    <a href="{{ route('admin.reports.cards') }}" class="bg-gray-500 hover:bg-gray-600 text-white w-full flex items-center justify-center rounded">
                        <i class="fas fa-sync-alt mr-2"></i> إعادة تعيين
                    </a>
                </div>
            </form>
        </div>

        <!-- الجدول -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="flex justify-between items-center p-4 border-b">
                <div class="relative">
                    <x-input
                        name="search"
                        type="text"
                        placeholder="بحث سريع..."
                        class="pl-10"
                        wire:model.debounce.300ms="search"
                    />
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    عرض <span class="font-bold">{{ $cards->count() }}</span> من أصل <span class="font-bold">{{ $cards->total() }}</span> بطاقة
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <!-- رؤوس الأعمدة -->
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($cards as $card)
                            <tr class="hover:bg-gray-50 transition">
                                <!-- بيانات كل بطاقة -->
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    لا توجد بطاقات لعرضها
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- هذه السطر هو التعديل -->
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 sm:px-6">
                {{ $cards->links() }}
            </div>
        </div>
    </div>
</x-app-layout>