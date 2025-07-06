<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Select extends Component
{
    /**
     * اسم الحقل
     *
     * @var string
     */
    public $name;
    
    /**
     * تسمية الحقل
     *
     * @var string|null
     */
    public $label;
    
    /**
     * خيارات القائمة
     *
     * @var array
     */
    public $options;
    
    /**
     * القيمة المحددة
     *
     * @var string|array|null
     */
    public $selected;
    
    /**
     * النص الموضعي
     *
     * @var string|null
     */
    public $placeholder;
    
    /**
     * هل الحقل مطلوب؟
     *
     * @var bool
     */
    public $required;
    
    /**
     * أيقونة قبل الحقل
     *
     * @var string|null
     */
    public $icon;
    
    /**
     * نص المساعدة
     *
     * @var string|null
     */
    public $helpText;
    
    /**
     * حجم الحقل
     *
     * @var string
     */
    public $size;
    
    /**
     * لون الحقل
     *
     * @var string
     */
    public $color;
    
    /**
     * هل متعدد الاختيارات؟
     *
     * @var bool
     */
    public $multiple;
    
    /**
     * عدد الخيارات الظاهرة
     *
     * @var int|null
     */
    public $visibleOptions;
    
    /**
     * رسالة الخطأ
     *
     * @var string|null
     */
    public $error;
    
    /**
     * فئة CSS إضافية
     *
     * @var string
     */
    public $class;

    /**
     * قائمة الأحجام المتاحة
     */
    const SIZES = [
        'sm' => 'sm',
        'md' => 'md',
        'lg' => 'lg',
    ];
    
    /**
     * قائمة الألوان المتاحة
     */
    const COLORS = [
        'primary' => 'primary',
        'success' => 'success',
        'danger' => 'danger',
        'warning' => 'warning',
        'info' => 'info',
    ];

    /**
     * إنشاء نسخة جديدة من المكون
     *
     * @param  string  $name
     * @param  array  $options
     * @param  string|null  $label
     * @param  string|array|null  $selected
     * @param  string|null  $placeholder
     * @param  bool  $required
     * @param  string|null  $icon
     * @param  string|null  $helpText
     * @param  string  $size
     * @param  string  $color
     * @param  bool  $multiple
     * @param  int|null  $visibleOptions
     * @param  string|null  $error
     * @param  string  $class
     * @return void
     */
    public function __construct(
        $name,
        $options = [],
        $label = null,
        $selected = null,
        $placeholder = null,
        $required = false,
        $icon = null,
        $helpText = null,
        $size = 'md',
        $color = 'primary',
        $multiple = false,
        $visibleOptions = null,
        $error = null,
        $class = ''
    ) {
        $this->name = $name;
        $this->options = $options;
        $this->label = $label;
        $this->selected = old($name, $selected);
        $this->placeholder = $placeholder ?? ($label ? 'اختر ' . $label : 'اختر من القائمة');
        $this->required = (bool) $required;
        $this->icon = $icon;
        $this->helpText = $helpText;
        $this->size = $this->validateSize($size);
        $this->color = $this->validateColor($color);
        $this->multiple = (bool) $multiple;
        $this->visibleOptions = $visibleOptions;
        $this->error = $error;
        $this->class = $class;
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
     * الحصول على فئات CSS حسب الحجم
     *
     * @return array
     */
    public function sizeClasses()
    {
        return [
            'sm' => 'py-1.5 px-3 text-sm',
            'md' => 'py-2 px-4 text-base',
            'lg' => 'py-3 px-5 text-lg',
        ][$this->size];
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
                'border' => 'border-gray-300',
                'focus' => 'focus:border-blue-500 focus:ring-blue-500',
                'error' => 'border-red-500 focus:border-red-500 focus:ring-red-500',
            ],
            'success' => [
                'border' => 'border-green-300',
                'focus' => 'focus:border-green-500 focus:ring-green-500',
                'error' => 'border-red-500 focus:border-red-500 focus:ring-red-500',
            ],
            'danger' => [
                'border' => 'border-red-300',
                'focus' => 'focus:border-red-500 focus:ring-red-500',
                'error' => 'border-red-500 focus:border-red-500 focus:ring-red-500',
            ],
            'warning' => [
                'border' => 'border-yellow-300',
                'focus' => 'focus:border-yellow-500 focus:ring-yellow-500',
                'error' => 'border-red-500 focus:border-red-500 focus:ring-red-500',
            ],
            'info' => [
                'border' => 'border-cyan-300',
                'focus' => 'focus:border-cyan-500 focus:ring-cyan-500',
                'error' => 'border-red-500 focus:border-red-500 focus:ring-red-500',
            ],
        ][$this->color];
    }
    
    /**
     * الحصول على فئات CSS للحقل
     *
     * @return string
     */
    public function selectClasses()
    {
        $classes = [
            'block w-full rounded-md shadow-sm transition duration-200 appearance-none',
            $this->sizeClasses(),
            $this->error ? $this->colorClasses()['error'] : $this->colorClasses()['border'],
            $this->error ? $this->colorClasses()['error'] : $this->colorClasses()['focus'],
            $this->icon ? 'pl-10' : '',
            $this->multiple ? 'pr-10' : '',
        ];
        
        return implode(' ', $classes);
    }
    
    /**
     * التحقق مما إذا كانت القيمة محددة
     *
     * @param  string|int  $value
     * @return bool
     */
    public function isSelected($value)
    {
        if ($this->multiple) {
            return in_array($value, (array) $this->selected);
        }
        
        return $value == $this->selected;
    }

    /**
     * الحصول على عرض المكون
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.select');
    }
}