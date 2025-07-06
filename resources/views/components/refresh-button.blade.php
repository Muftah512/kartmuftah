@props([
    'spin' => false, // تأثير دوران الأيقونة
    'label' => null, // نص اختياري
    'size' => 'md', // الأحجام: xs, sm, md, lg
    'color' => 'default', // الألوان: default, primary, success, danger
    'tooltip' => 'تحديث الصفحة', // نص تلميح
    'position' => 'bottom', // موضع التلميح: top, bottom, left, right
])

@php
    // تعريف كلاسات الأزرار داخلياً
    $sizes = [
        'xs' => 'px-2 py-1 text-xs size-xs',
        'sm' => 'px-3 py-1.5 text-sm size-sm',
        'md' => 'px-4 py-2 text-base size-md',
        'lg' => 'px-5 py-2.5 text-lg size-lg',
    ];
    
    $colors = [
        'default' => 'bg-gray-100 hover:bg-gray-200 text-gray-700 focus:ring-gray-300',
        'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-400',
        'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-400',
        'danger' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-400',
        'light' => 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 focus:ring-gray-200',
        'dark' => 'bg-gray-800 hover:bg-gray-900 text-white focus:ring-gray-500',
    ];
    
    $baseClasses = "inline-flex items-center justify-center transition-all duration-200 ease-in-out rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2";
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $colorClass = $colors[$color] ?? $colors['default'];
    $buttonClasses = "{$baseClasses} {$sizeClass} {$colorClass}";
@endphp

<button
    type="button"
    onclick="refreshPage(this)"
    {{ $attributes->merge(['class' => $buttonClasses]) }}
    title="{{ $tooltip }}"
    data-tooltip-pos="{{ $position }}"
    aria-label="تحديث الصفحة"
>
    {{-- الأيقونة مع تأثير الدوران --}}
    <span class="refresh-icon {{ $spin ? 'animate-spin' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" class="refresh-svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
        </svg>
    </span>
    
    {{-- النص إذا كان موجودًا --}}
    @if($label)
        <span class="refresh-label ml-2">{{ $label }}</span>
    @endif
</button>

@once
@push('styles')
<style>
    /* تأثيرات الدوران */
    .animate-spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        100% { transform: rotate(360deg); }
    }
    
    /* أنماط الزر الأساسية */
    .refresh-button {
        transition: transform 0.3s, background-color 0.3s;
        cursor: pointer;
    }
    
    .refresh-button:hover {
        transform: scale(1.05);
    }
    
    .refresh-button:active {
        transform: scale(0.95);
    }
    
    /* تأثير النقر */
    .refresh-button:active .refresh-svg {
        transform: scale(0.9);
    }
    
    /* أحجام الأيقونة */
    .refresh-svg {
        transition: transform 0.2s;
    }
    
    .size-xs .refresh-svg {
        width: 0.875rem;
        height: 0.875rem;
    }
    
    .size-sm .refresh-svg {
        width: 1rem;
        height: 1rem;
    }
    
    .size-md .refresh-svg {
        width: 1.25rem;
        height: 1.25rem;
    }
    
    .size-lg .refresh-svg {
        width: 1.5rem;
        height: 1.5rem;
    }
    
    /* تلميحات الأدوات */
    [data-tooltip-pos] {
        position: relative;
    }
    
    [data-tooltip-pos]::after {
        content: attr(title);
        position: absolute;
        white-space: nowrap;
        background: rgba(0, 0, 0, 0.75);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s, visibility 0.2s;
        z-index: 100;
    }
    
    [data-tooltip-pos="top"]::after {
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        margin-bottom: 6px;
    }
    
    [data-tooltip-pos="bottom"]::after {
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        margin-top: 6px;
    }
    
    [data-tooltip-pos="left"]::after {
        right: 100%;
        top: 50%;
        transform: translateY(-50%);
        margin-right: 6px;
    }
    
    [data-tooltip-pos="right"]::after {
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        margin-left: 6px;
    }
    
    [data-tooltip-pos]:hover::after {
        opacity: 1;
        visibility: visible;
    }
</style>
@endpush

@push('scripts')
<script>
    function refreshPage(button) {
        // إظهار تأثير التحميل
        const icon = button.querySelector('.refresh-icon');
        icon.classList.add('animate-spin');
        
        // منع النقر المتكرر
        button.disabled = true;
        
        // تحديث الصفحة بعد تأخير بسيط لإظهار التأثير
        setTimeout(() => {
            window.location.reload();
        }, 300);
    }
</script>
@endpush
@endonce