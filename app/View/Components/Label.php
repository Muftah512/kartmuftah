<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Label extends Component
{
    /**
     * نص التسمية
     *
     * @var string
     */
    public $value;
    
    /**
     * لون التسمية
     *
     * @var string
     */
    public $color;
    
    /**
     * حجم التسمية
     *
     * @var string
     */
    public $size;
    
    /**
     * أيقونة قبل النص
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
     * هل التسمية دائرية
     *
     * @var bool
     */
    public $rounded;
    
    /**
     * هل تحتوي على حدود
     *
     * @var bool
     */
    public $withBorder;
    
    /**
     * هل التسمية قابلة للإغلاق
     *
     * @var bool
     */
    public $dismissible;
    
    /**
     * فئة CSS إضافية
     *
     * @var string
     */
    public $class;

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
        'gray' => 'gray',
        'indigo' => 'indigo',
        'purple' => 'purple',
        'pink' => 'pink',
        'yellow' => 'yellow',
        'teal' => 'teal',
        'cyan' => 'cyan',
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
     * @param  string  $value
     * @param  string  $color
     * @param  string  $size
     * @param  string|null  $icon
     * @param  string|null  $rightIcon
     * @param  bool  $rounded
     * @param  bool  $withBorder
     * @param  bool  $dismissible
     * @param  string  $class
     * @return void
     */
    public function __construct(
        $value = '',
        $color = 'primary',
        $size = 'md',
        $icon = null,
        $rightIcon = null,
        $rounded = true,
        $withBorder = false,
        $dismissible = false,
        $class = ''
    ) {
        $this->value = $value;
        $this->color = $this->validateColor($color);
        $this->size = $this->validateSize($size);
        $this->icon = $icon;
        $this->rightIcon = $rightIcon;
        $this->rounded = (bool) $rounded;
        $this->withBorder = (bool) $withBorder;
        $this->dismissible = (bool) $dismissible;
        $this->class = $class;
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
     * الحصول على فئات CSS حسب اللون
     *
     * @return array
     */
    public function colorClasses()
    {
        $classes = [
            'primary' => [
                'bg' => 'bg-blue-100',
                'text' => 'text-blue-800',
                'border' => 'border-blue-300',
                'hover' => 'hover:bg-blue-200',
            ],
            'secondary' => [
                'bg' => 'bg-gray-100',
                'text' => 'text-gray-800',
                'border' => 'border-gray-300',
                'hover' => 'hover:bg-gray-200',
            ],
            'success' => [
                'bg' => 'bg-green-100',
                'text' => 'text-green-800',
                'border' => 'border-green-300',
                'hover' => 'hover:bg-green-200',
            ],
            'danger' => [
                'bg' => 'bg-red-100',
                'text' => 'text-red-800',
                'border' => 'border-red-300',
                'hover' => 'hover:bg-red-200',
            ],
            'warning' => [
                'bg' => 'bg-yellow-100',
                'text' => 'text-yellow-800',
                'border' => 'border-yellow-300',
                'hover' => 'hover:bg-yellow-200',
            ],
            'info' => [
                'bg' => 'bg-cyan-100',
                'text' => 'text-cyan-800',
                'border' => 'border-cyan-300',
                'hover' => 'hover:bg-cyan-200',
            ],
            'light' => [
                'bg' => 'bg-gray-50',
                'text' => 'text-gray-600',
                'border' => 'border-gray-200',
                'hover' => 'hover:bg-gray-100',
            ],
            'dark' => [
                'bg' => 'bg-gray-800',
                'text' => 'text-gray-100',
                'border' => 'border-gray-700',
                'hover' => 'hover:bg-gray-700',
            ],
            'gray' => [
                'bg' => 'bg-gray-200',
                'text' => 'text-gray-700',
                'border' => 'border-gray-300',
                'hover' => 'hover:bg-gray-300',
            ],
            'indigo' => [
                'bg' => 'bg-indigo-100',
                'text' => 'text-indigo-800',
                'border' => 'border-indigo-300',
                'hover' => 'hover:bg-indigo-200',
            ],
            'purple' => [
                'bg' => 'bg-purple-100',
                'text' => 'text-purple-800',
                'border' => 'border-purple-300',
                'hover' => 'hover:bg-purple-200',
            ],
            'pink' => [
                'bg' => 'bg-pink-100',
                'text' => 'text-pink-800',
                'border' => 'border-pink-300',
                'hover' => 'hover:bg-pink-200',
            ],
            'yellow' => [
                'bg' => 'bg-yellow-100',
                'text' => 'text-yellow-800',
                'border' => 'border-yellow-300',
                'hover' => 'hover:bg-yellow-200',
            ],
            'teal' => [
                'bg' => 'bg-teal-100',
                'text' => 'text-teal-800',
                'border' => 'border-teal-300',
                'hover' => 'hover:bg-teal-200',
            ],
            'cyan' => [
                'bg' => 'bg-cyan-100',
                'text' => 'text-cyan-800',
                'border' => 'border-cyan-300',
                'hover' => 'hover:bg-cyan-200',
            ],
        ];
        
        return $classes[$this->color] ?? $classes['primary'];
    }
    
    /**
     * الحصول على فئات الحجم
     *
     * @return array
     */
    public function sizeClasses()
    {
        $classes = [
            'xs' => [
                'text' => 'text-xs',
                'px' => 'px-1.5',
                'py' => 'py-0.5',
                'icon' => 'text-xs',
            ],
            'sm' => [
                'text' => 'text-sm',
                'px' => 'px-2',
                'py' => 'py-0.5',
                'icon' => 'text-sm',
            ],
            'md' => [
                'text' => 'text-base',
                'px' => 'px-3',
                'py' => 'py-1',
                'icon' => 'text-base',
            ],
            'lg' => [
                'text' => 'text-lg',
                'px' => 'px-4',
                'py' => 'py-1.5',
                'icon' => 'text-lg',
            ],
            'xl' => [
                'text' => 'text-xl',
                'px' => 'px-5',
                'py' => 'py-2',
                'icon' => 'text-xl',
            ],
        ];
        
        return $classes[$this->size] ?? $classes['md'];
    }
    
    /**
     * الحصول على فئات CSS للعنصر
     *
     * @return string
     */
    public function elementClasses()
    {
        $colors = $this->colorClasses();
        $sizes = $this->sizeClasses();
        
        $classes = [
            'inline-flex items-center',
            $colors['bg'],
            $colors['text'],
            $sizes['text'],
            $sizes['px'],
            $sizes['py'],
            $this->rounded ? 'rounded-full' : 'rounded-md',
            $this->withBorder ? 'border ' . $colors['border'] : '',
            $this->dismissible ? 'pr-2' : '',
            $this->class,
        ];
        
        return implode(' ', $classes);
    }

    /**
     * الحصول على عرض المكون
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.label');
    }
}