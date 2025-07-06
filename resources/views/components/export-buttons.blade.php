@props([
    'url' => '#',
    'label' => 'تصدير',
    'formats' => ['pdf', 'excel', 'csv', 'print'],
    'dropdownPosition' => 'right'
])

<div class="relative inline-block text-left" x-data="{ open: false }">
    <div>
        <button 
            type="button" 
            @click="open = !open"
            class="inline-flex items-center justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
            id="export-menu-button" 
            aria-expanded="true" 
            aria-haspopup="true"
        >
            <i class="fas fa-file-export mr-2 text-indigo-600"></i>
            {{ $label }}
            <svg class="-mr-1 ml-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div 
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="origin-top-right absolute mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10"
        :class="{
            'right-0': '{{ $dropdownPosition }}' === 'right',
            'left-0': '{{ $dropdownPosition }}' === 'left'
        }"
        role="menu" 
        aria-orientation="vertical" 
        aria-labelledby="export-menu-button" 
        tabindex="-1"
    >
        <div class="py-1" role="none">
            @if(in_array('pdf', $formats))
            <a 
                href="{{ $url }}?export=pdf" 
                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-b border-gray-100"
                role="menuitem" 
                tabindex="-1" 
                id="export-menu-item-0"
            >
                <i class="fas fa-file-pdf text-red-500 mr-3 w-5 text-center"></i>
                <div>
                    <div class="font-medium">PDF</div>
                    <div class="text-xs text-gray-500">تنسيق ثابت للطباعة</div>
                </div>
            </a>
            @endif
            
            @if(in_array('excel', $formats))
            <a 
                href="{{ $url }}?export=excel" 
                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-b border-gray-100"
                role="menuitem" 
                tabindex="-1" 
                id="export-menu-item-1"
            >
                <i class="fas fa-file-excel text-green-600 mr-3 w-5 text-center"></i>
                <div>
                    <div class="font-medium">Excel</div>
                    <div class="text-xs text-gray-500">تنسيق جدولي قابل للتعديل</div>
                </div>
            </a>
            @endif
            
            @if(in_array('csv', $formats))
            <a 
                href="{{ $url }}?export=csv" 
                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 border-b border-gray-100"
                role="menuitem" 
                tabindex="-1" 
                id="export-menu-item-2"
            >
                <i class="fas fa-file-csv text-blue-500 mr-3 w-5 text-center"></i>
                <div>
                    <div class="font-medium">CSV</div>
                    <div class="text-xs text-gray-500">ملف نصي مفصول بفواصل</div>
                </div>
            </a>
            @endif
            
            @if(in_array('print', $formats))
            <a 
                href="#" 
                onclick="window.print(); return false;"
                class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                role="menuitem" 
                tabindex="-1" 
                id="export-menu-item-3"
            >
                <i class="fas fa-print text-gray-600 mr-3 w-5 text-center"></i>
                <div>
                    <div class="font-medium">طباعة مباشرة</div>
                    <div class="text-xs text-gray-500">إرسال إلى الطابعة</div>
                </div>
            </a>
            @endif
            
            <!-- زر إغلاق القائمة -->
            <button 
                @click="open = false" 
                class="w-full text-center text-xs text-gray-500 py-2 hover:bg-gray-50 border-t border-gray-100 mt-1"
            >
                <i class="fas fa-times mr-1"></i> إغلاق
            </button>
        </div>
    </div>
    
    <!-- مؤشر التحميل -->
    <div 
        x-show="isExporting" 
        class="absolute inset-0 bg-white bg-opacity-80 flex items-center justify-center rounded-md"
        style="display: none;"
    >
        <div class="flex flex-col items-center">
            <div class="w-8 h-8 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin mb-2"></div>
            <span class="text-xs text-indigo-600">جاري التصدير...</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:initialized', () => {
    // إضافة وظيفة التصدير مع مؤشر التحميل
    const exportButtons = document.querySelectorAll('[href*="?export="]');
    
    exportButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const component = this.closest('[x-data]');
            Alpine.nextTick(() => {
                Alpine.bind(component, { 
                    isExporting: true,
                    init() {
                        // إخفاء المؤشر بعد 3 ثواني (محاكاة لانتهاء التصدير)
                        setTimeout(() => {
                            this.isExporting = false;
                            this.open = false;
                        }, 3000);
                    }
                });
            });
        });
    });
});
</script>
@endpush

<style>
/* رسوم متحركة للمؤشر */
@keyframes spin {
    to { transform: rotate(360deg); }
}
.animate-spin {
    animation: spin 1s linear infinite;
}

/* تحسينات للعرض على الأجهزة المحمولة */
@media (max-width: 640px) {
    [x-data] .absolute {
        position: fixed;
        width: 100%;
        left: 0 !important;
        right: 0 !important;
        max-width: 100%;
        margin-top: 0.5rem;
        border-radius: 0.5rem;
    }
}
</style>