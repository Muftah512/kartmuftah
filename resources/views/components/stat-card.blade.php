@props([
    'title' => '',
    'value' => 0,
    'icon' => 'chart-bar',
    'color' => 'blue',
    'trend' => null, // 'up' or 'down'
    'trendValue' => null,
    'url' => null,
    'urlText' => 'عرض التفاصيل',
    'class' => '',
])

@php
    // تعريف ألوان المكون
    $colorClasses = [
        'blue' => [
            'bg' => 'bg-blue-50',
            'icon' => 'bg-blue-100 text-blue-600',
            'text' => 'text-blue-800',
            'hover' => 'hover:bg-blue-100'
        ],
        'green' => [
            'bg' => 'bg-green-50',
            'icon' => 'bg-green-100 text-green-600',
            'text' => 'text-green-800',
            'hover' => 'hover:bg-green-100'
        ],
        'red' => [
            'bg' => 'bg-red-50',
            'icon' => 'bg-red-100 text-red-600',
            'text' => 'text-red-800',
            'hover' => 'hover:bg-red-100'
        ],
        'yellow' => [
            'bg' => 'bg-yellow-50',
            'icon' => 'bg-yellow-100 text-yellow-600',
            'text' => 'text-yellow-800',
            'hover' => 'hover:bg-yellow-100'
        ],
        'purple' => [
            'bg' => 'bg-purple-50',
            'icon' => 'bg-purple-100 text-purple-600',
            'text' => 'text-purple-800',
            'hover' => 'hover:bg-purple-100'
        ],
        'indigo' => [
            'bg' => 'bg-indigo-50',
            'icon' => 'bg-indigo-100 text-indigo-600',
            'text' => 'text-indigo-800',
            'hover' => 'hover:bg-indigo-100'
        ]
    ];
    
    // تحديد ألوان المكون حسب الخاصية
    $colors = $colorClasses[$color] ?? $colorClasses['blue'];
    
    // تحديد اتجاه المؤشر
    $trendColors = [
        'up' => [
            'text' => 'text-green-600',
            'icon' => 'fas fa-arrow-up'
        ],
        'down' => [
            'text' => 'text-red-600',
            'icon' => 'fas fa-arrow-down'
        ]
    ];
    
    $trendInfo = $trend ? ($trendColors[$trend] ?? $trendColors['up']) : null;
@endphp

<div 
    {{ $attributes->merge(['class' => "stat-card rounded-xl shadow-sm overflow-hidden transition-all duration-300 hover:shadow-md {$colors['hover']} {$class}"]) }}
>
    <div class="flex items-center p-5 {{ $colors['bg'] }}">
        <div class="flex-shrink-0 stat-card-icon transition-transform duration-300">
            <div class="p-3 rounded-lg {{ $colors['icon'] }}">
                <i class="fas fa-{{ $icon }} text-xl"></i>
            </div>
        </div>
        <div class="ml-4 flex-1">
            <h3 class="text-sm font-medium text-gray-500 truncate">{{ $title }}</h3>
            <div class="flex items-baseline">
                <p class="text-2xl font-semibold {{ $colors['text'] }}">{{ $value }}</p>
                
                @if($trend && $trendValue)
                <div class="ml-2 flex items-center text-sm font-semibold {{ $trendInfo['text'] }}">
                    <i class="{{ $trendInfo['icon'] }} mr-1"></i>
                    <span>{{ $trendValue }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    @if($url)
    <div class="bg-gray-50 px-5 py-3 transition-colors duration-300 hover:bg-gray-100">
        <a href="{{ $url }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors duration-200 flex items-center justify-between">
            <span>{{ $urlText }}</span>
            <i class="fas fa-arrow-left text-xs"></i>
        </a>
    </div>
    @endif
</div>

@once
@push('styles')
<style>
    .stat-card {
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.1);
        border-color: rgba(0, 0, 0, 0.05);
    }
    
    .stat-card:hover .stat-card-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .stat-card.pulse:hover {
        animation: pulse 2s infinite;
    }
</style>
@endpush
@endonce