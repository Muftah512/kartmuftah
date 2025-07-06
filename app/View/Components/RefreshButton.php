<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RefreshButton extends Component
{
    /**
     * تأثير دوران الأيقونة
     *
     * @var bool
     */
    public $spin;
    
    /**
     * نص الزر
     *
     * @var string|null
     */
    public $label;
    
    /**
     * حجم الزر
     *
     * @var string
     */
    public $size;
    
    /**
     * لون الزر
     *
     * @var string
     */
    public $color;
    
    /**
     * نص التلميح
     *
     * @var string
     */
    public $tooltip;
    
    /**
     * موضع التلميح
     *
     * @var string
     */
    public $position;
    
    /**
     * فئات CSS إضافية
     *
     * @var string
     */
    public $class;
    
    /**
     * قائمة الأحجام المتاحة
     */
    const SIZES = [
        'xs' => 'xs',
        'sm' => 'sm',
        'md' => 'md',
        'lg' => 'lg'
    ];
    
    /**
     * قائمة الألوان المتاحة
     */
    const COLORS = [
        'default' => 'default',
        'primary' => 'primary',
        'success' => 'success',
        'danger' => 'danger',
        'light' => 'light',
        'dark' => 'dark'
    ];
    
    /**
     * قائمة مواضع التلميح المتاحة
     */
    const POSITIONS = [
        'top' => 'top',
        'bottom' => 'bottom',
        'left' => 'left',
        'right' => 'right'
    ];

    /**
     * إنشاء نسخة جديدة من المكون
     *
     * @param  bool  $spin
     * @param  string|null  $label
     * @param  string  $size
     * @param  string  $color
     * @param  string  $tooltip
     * @param  string  $position
     * @param  string  $class
     * @return void
     */
    public function __construct(
        $spin = false,
        $label = null,
        $size = 'md',
        $color = 'default',
        $tooltip = 'تحديث الصفحة',
        $position = 'bottom',
        $class = ''
    ) {
        $this->spin = (bool) $spin;
        $this->label = $label;
        $this->size = $this->validateSize($size);
        $this->color = $this->validateColor($color);
        $this->tooltip = $tooltip;
        $this->position = $this->validatePosition($position);
        $this->class = $class;
    }

    /**
     * التحقق من صحة حجم الزر
     *
     * @param  string  $size
     * @return string
     */
    protected function validateSize($size)
    {
        return array_key_exists($size, self::SIZES) ? $size : 'md';
    }
    
    /**
     * التحقق من صحة لون الزر
     *
     * @param  string  $color
     * @return string
     */
    protected function validateColor($color)
    {
        return array_key_exists($color, self::COLORS) ? $color : 'default';
    }
    
    /**
     * التحقق من صحة موضع التلميح
     *
     * @param  string  $position
     * @return string
     */
    protected function validatePosition($position)
    {
        return array_key_exists($position, self::POSITIONS) ? $position : 'bottom';
    }
    
    /**
     * الحصول على فئات CSS للزر حسب الحجم واللون
     *
     * @return string
     */
    public function buttonClasses()
    {
        $classes = 'inline-flex items-center justify-center transition-all duration-200 ease-in-out rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 ';
        
        // أحجام الأزرار
        $sizeClasses = [
            'xs' => 'px-2 py-1 text-xs',
            'sm' => 'px-3 py-1.5 text-sm',
            'md' => 'px-4 py-2 text-base',
            'lg' => 'px-5 py-2.5 text-lg'
        ];
        
        // ألوان الأزرار
        $colorClasses = [
            'default' => 'bg-gray-100 hover:bg-gray-200 text-gray-700 focus:ring-gray-300',
            'primary' => 'bg-indigo-600 hover:bg-indigo-700 text-white focus:ring-indigo-400',
            'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-400',
            'danger'  => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-400',
            'light'   => 'bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 focus:ring-gray-200',
            'dark'    => 'bg-gray-800 hover:bg-gray-900 text-white focus:ring-gray-500'
        ];
        
        // إضافة فئات الحجم واللون
        $classes .= $sizeClasses[$this->size] . ' ' . $colorClasses[$this->color];
        
        // إضافة الفئات الإضافية إذا وجدت
        if (!empty($this->class)) {
            $classes .= ' ' . $this->class;
        }
        
        return $classes;
    }
    
    /**
     * الحصول على حجم SVG حسب حجم الزر
     *
     * @return string
     */
    public function svgSize()
    {
        $sizes = [
            'xs' => '0.875rem',
            'sm' => '1rem',
            'md' => '1.25rem',
            'lg' => '1.5rem'
        ];
        
        return $sizes[$this->size] ?? '1.25rem';
    }

    /**
     * الحصول على عرض المكون
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.refresh-button');
    }
}