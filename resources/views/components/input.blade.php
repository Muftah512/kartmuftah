@props([
    'name',
    'type'        => 'text',
    'label'       => null,
    'placeholder' => null,
    'value'       => null,
    'required'    => false,
    'icon'        => null,
    'rightIcon'   => null,
    'helpText'    => null,
    'size'        => 'md',
    'color'       => 'primary',
    'error'       => null,
    'class'       => '',
])

@php
    // size classes
    $sizeClasses = [
        'sm' => 'py-1.5 px-3 text-sm',
        'md' => 'py-2 px-4 text-base',
        'lg' => 'py-3 px-5 text-lg',
    ][$size];

    // color classes
    $colorMap = [
        'primary' => ['border-gray-300','focus:border-blue-500 focus:ring-blue-500','border-red-500 focus:border-red-500 focus:ring-red-500'],
        'success' => ['border-green-300','focus:border-green-500 focus:ring-green-500','border-red-500 focus:border-red-500 focus:ring-red-500'],
        'danger'  => ['border-red-300','focus:border-red-500 focus:ring-red-500','border-red-500 focus:border-red-500 focus:ring-red-500'],
        'warning' => ['border-yellow-300','focus:border-yellow-500 focus:ring-yellow-500','border-red-500 focus:border-red-500 focus:ring-red-500'],
        'info'    => ['border-cyan-300','focus:border-cyan-500 focus:ring-cyan-500','border-red-500 focus:border-red-500 focus:ring-red-500'],
    ][$color];

    $borderClass = $error ? $colorMap[2] : $colorMap[0];
    $focusClass  = $error ? $colorMap[2] : $colorMap[1];
@endphp

<div class="w-full {{ $class }}">
    @if($label)
        <label for="{{ $name }}" class="block mb-1 font-medium">{{ $label }}</label>
    @endif

    <div class="relative">
        @if($icon)
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                {!! $icon !!}
            </span>
        @endif

        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            value="{{ $value }}"
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            @if($required) required @endif
            {{ $attributes->merge([
                'class' => implode(' ', [
                    'block w-full rounded-md shadow-sm transition duration-200',
                    $sizeClasses,
                    $borderClass,
                    $focusClass,
                    $icon ? 'pl-10' : '',
                    $rightIcon ? 'pr-10' : '',
                ]),
            ]) }}
        >

        @if($rightIcon)
            <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500">
                {!! $rightIcon !!}
            </span>
        @endif
    </div>

    @if($helpText)
        <p class="mt-1 text-sm text-gray-500">{{ $helpText }}</p>
    @endif

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>