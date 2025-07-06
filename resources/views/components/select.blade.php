@props([
    'options'     => [],   // مصفوفة الخيارات: ['value' => 'label', ...]
    'selected'    => null, // القيمة المحددة
    'placeholder' => null, // نص العنصر الافتراضي
    'required'    => false,// هل الحقل مطلوب؟
    'label'       => null, // تسمية خارجية (اختياري)
])

@if($label)
    <label {{ $attributes->only('for')->merge(['class' => 'block text-sm font-medium text-gray-700 mb-1']) }}>
        {{ $label }}
    </label>
@endif

<select
    {{ $attributes
        ->merge([
            'class' => 'block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md'
        ])
    }}
    @if($required) required @endif
>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif

    @foreach($options as $value => $labelOption)
        <option
            value="{{ $value }}"
            @selected((string)$value === (string)$selected)
        >
            {{ $labelOption }}
        </option>
    @endforeach
</select>