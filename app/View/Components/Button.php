<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    /**
     * نوع الزر
     *
     * @var string
     */
    public $type;
    
    /**
     * نص الزر
     *
     * @var string|null
     */
    public $text;
    
    /**
     * أيقونة الزر
     *
     * @var string|null
     */
    public $icon;
    
    /**
     * أيقونة بعد النص
     *
     * @var string|null
     */
    public $rightIcon;
    
    /**
     * لون الزر
     *
     * @var string
     */
    public $color;
    
    /**
     * حجم الزر
     *
     * @var string
     */
    public $size;
    
    /**
     * هل الزر مملوء؟
     *
     * @var bool
     */
    public $filled;
    
    /**
     * هل الزر مستدير؟
     *
     * @var bool
     */
    public $rounded;
    
    /**
     * هل الزر محمل؟
     *
     * @var bool
     */
    public $loading;
    
    /**
     * نص التحميل
     *
     * @var string|null
     */
    public $loadingText;
    
    /**
     * هل الزر معطل؟
     *
     * @var bool
     */
    public $disabled;
    
    /**
     * فئة CSS إضافية
     *
     * @var string
     */
    public $class;
    
    /**
     * رابط (إذا كان الزر رابطًا)
     *
     * @var string|null
     */
    public $href;
    
    /**
     * هدف الرابط
     *
     * @var string|null
     */
    public $target;

    /**
     * قائمة الأنواع المتاحة
     */
    const TYPES = [
        'button', 'submit', 'reset'
    ];
    
    /**
     * قائمة الألوان المتاحة
     */
    const COLORS = [
        'primary' => 'primary',
        'secondary' => 'secondary',
        'success' => 'success',
        'danger' => 'danger',
        'warning' => 'warning',
        'info' => 'info',
        'light' => 'light',
        'dark' => 'dark',
        'link' => 'link',
    ];
    
    /**
     * قائمة الأحجام المتاحة
     */
    const SIZES = [
        'xs' => 'xs',
        'sm' => 'sm',
        'md' => 'md',
        'lg' => 'lg',
        'xl' => 'xl',
    ];

    /**
     * إنشاء نسخة جديدة من المكون
     *
     * @param  string  $type
     * @param  string|null  $text
     * @param  string|null  $icon
     * @param  string|null  $rightIcon
     * @param  string  $color
     * @param  string  $size
     * @param  bool  $filled
     * @param  bool  $rounded
     * @param  bool  $loading
     * @param  string|null  $loadingText
     * @param  bool  $disabled
     * @param  string  $class
     * @param  string|null  $href
     * @param  string|null  $target
     * @return void
     */
    public function __construct(
        $type = 'button',
        $text = null,
        $icon = null,
        $rightIcon = null,
        $color = 'primary',
        $size = 'md',
        $filled = true,
        $rounded = false,
        $loading = false,
        $loadingText = null,
        $disabled = false,
        $class = '',
        $href = null,
        $target = '_self'
    ) {
        $this->type = $this->validateType($type);
        $this->text = $text;
        $this->icon = $icon;
        $this->rightIcon = $rightIcon;
        $this->color = $this->validateColor($color);
        $this->size = $this->validateSize($size);
        $this->filled = (bool) $filled;
        $this->rounded = (bool) $rounded;
        $this->loading = (bool) $loading;
        $this->loadingText = $loadingText ?? ($text ? $text : 'جاري التحميل...');
        $this->disabled = (bool) $disabled;
        $this->class = $class;
        $this->href = $href;
        $this->target = $target;
    }

    /**
     * التحقق من صحة النوع
     *
     * @param  string  $type
     * @return string
     */
    protected function validateType($type)
    {
        return in_array($type, self::TYPES) ? $type : 'button';
    }
    
    /**
     * التحقق من صحة اللون
     *
     * @param  string  $color
     * @return string
     */
    protected function validateColor($color)
    {
        return array_key_exists($color, self::COLORS) ? $color : 'primary';
    }
    
    /**
     * التحقق من صحة الحجم
     *
     * @param  string  $size
     * @return string
     */
    protected function validateSize($size)
    {
        return array_key_exists($size, self::SIZES) ? $size : 'md';
    }
    
    /**
     * الحصول على فئات CSS حسب الحجم
     *
     * @return array
     */
    public function sizeClasses()
    {
        return [
            'xs' => 'px-2.5 py-1.5 text-xs',
            'sm' => 'px-3 py-2 text-sm',
            'md' => 'px-4 py-2 text-base',
            'lg' => 'px-5 py-2.5 text-lg',
            'xl' => 'px-6 py-3 text-xl',
        ];
    }
    
    /**
     * الحصول على فئات CSS حسب اللون
     *
     * @return array
     */
    public function colorClasses()
    {
        return [
            'primary' => [
                'filled' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
                'outline' => 'border border-blue-600 text-blue-600 hover:bg-blue-50 focus:ring-blue-500',
                'text' => 'text-blue-600 hover:bg-blue-50 focus:ring-blue-500',
            ],
            'secondary' => [
                'filled' => 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500',
                'outline' => 'border border-gray-600 text-gray-600 hover:bg-gray-50 focus:ring-gray-500',
                'text' => 'text-gray-600 hover:bg-gray-50 focus:ring-gray-500',
            ],
            'success' => [
                'filled' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
                'outline' => 'border border-green-600 text-green-600 hover:bg-green-50 focus:ring-green-500',
                'text' => 'text-green-600 hover:bg-green-50 focus:ring-green-500',
            ],
            'danger' => [
                'filled' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
                'outline' => 'border border-red-600 text-red-600 hover:bg-red-50 focus:ring-red-500',
                'text' => 'text-red-600 hover:bg-red-50 focus:ring-red-500',
            ],
            'warning' => [
                'filled' => 'bg-yellow-500 hover:bg-yellow-600 text-white focus:ring-yellow-500',
                'outline' => 'border border-yellow-500 text-yellow-500 hover:bg-yellow-50 focus:ring-yellow-500',
                'text' => 'text-yellow-500 hover:bg-yellow-50 focus:ring-yellow-500',
            ],
            'info' => [
                'filled' => 'bg-cyan-600 hover:bg-cyan-700 text-white focus:ring-cyan-500',
                'outline' => 'border border-cyan-600 text-cyan-600 hover:bg-cyan-50 focus:ring-cyan-500',
                'text' => 'text-cyan-600 hover:bg-cyan-50 focus:ring-cyan-500',
            ],
            'light' => [
                'filled' => 'bg-gray-100 hover:bg-gray-200 text-gray-800 focus:ring-gray-300',
                'outline' => 'border border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-gray-200',
                'text' => 'text-gray-700 hover:bg-gray-50 focus:ring-gray-200',
            ],
            'dark' => [
                'filled' => 'bg-gray-800 hover:bg-gray-900 text-white focus:ring-gray-500',
                'outline' => 'border border-gray-800 text-gray-800 hover:bg-gray-50 focus:ring-gray-500',
                'text' => 'text-gray-800 hover:bg-gray-50 focus:ring-gray-500',
            ],
            'link' => [
                'filled' => 'text-blue-600 hover:text-blue-800 underline',
                'outline' => 'text-blue-600 hover:text-blue-800 underline',
                'text' => 'text-blue-600 hover:text-blue-800 underline',
            ],
        ][$this->color];
    }
    
    /**
     * الحصول على فئات CSS للزر
     *
     * @return string
     */
    public function buttonClasses()
    {
        // الفئات الأساسية
        $classes = [
            'inline-flex items-center justify-center',
            'font-medium focus:outline-none focus:ring-2 focus:ring-offset-2',
            'transition-all duration-200 ease-in-out',
            $this->rounded ? 'rounded-full' : 'rounded-md',
            $this->disabled ? 'opacity-70 cursor-not-allowed' : 'cursor-pointer',
        ];
        
        // تحديد نمط الزر (filled, outline, text)
        $style = $this->filled ? 'filled' : ($this->color === 'link' ? 'text' : 'outline');
        
        // إضافة فئات اللون
        $classes[] = $this->colorClasses()[$style];
        
        // إضافة فئات الحجم
        $classes[] = $this->sizeClasses()[$this->size];
        
        // إضافة فئات إضافية
        if ($this->class) {
            $classes[] = $this->class;
        }
        
        return implode(' ', $classes);
    }

    /**
     * الحصول على عرض المكون
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button');
    }
}