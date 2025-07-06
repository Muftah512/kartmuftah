<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DialogModal extends Component
{
    /**
     * عنوان المودال
     *
     * @var string
     */
    public $title;
    
    /**
     * معرف فريد للمودال
     *
     * @var string
     */
    public $id;
    
    /**
     * حجم المودال
     *
     * @var string
     */
    public $size;
    
    /**
     * هل يحتوي على زر إغلاق؟
     *
     * @var bool
     */
    public $closeable;
    
    /**
     * فئة CSS إضافية
     *
     * @var string
     */
    public $class;
    
    /**
     * محاذاة المحتوى
     *
     * @var string
     */
    public $align;
    
    /**
     * نوع الخلفية
     *
     * @var string
     */
    public $background;
    
    /**
     * هل يمنع الإغلاق بالنقر خارج المودال؟
     *
     * @var bool
     */
    public $persistent;

    /**
     * قائمة الأحجام المتاحة
     */
    const SIZES = [
        'sm' => 'sm',
        'md' => 'md',
        'lg' => 'lg',
        'xl' => 'xl',
        'full' => 'full',
    ];
    
    /**
     * قائمة المحاذاة المتاحة
     */
    const ALIGNS = [
        'center' => 'center',
        'top' => 'top',
        'bottom' => 'bottom',
    ];
    
    /**
     * قائمة أنواع الخلفيات المتاحة
     */
    const BACKGROUNDS = [
        'blur' => 'blur',
        'dark' => 'dark',
        'light' => 'light',
        'none' => 'none',
    ];

    /**
     * إنشاء نسخة جديدة من المكون
     *
     * @param  string  $title
     * @param  string  $id
     * @param  string  $size
     * @param  bool  $closeable
     * @param  string  $class
     * @param  string  $align
     * @param  string  $background
     * @param  bool  $persistent
     * @return void
     */
    public function __construct(
        $title = '',
        $id = 'dialog-modal',
        $size = 'md',
        $closeable = true,
        $class = '',
        $align = 'center',
        $background = 'blur',
        $persistent = false
    ) {
        $this->title = $title;
        $this->id = $id;
        $this->size = $this->validateSize($size);
        $this->closeable = (bool) $closeable;
        $this->class = $class;
        $this->align = $this->validateAlign($align);
        $this->background = $this->validateBackground($background);
        $this->persistent = (bool) $persistent;
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
     * التحقق من صحة المحاذاة
     *
     * @param  string  $align
     * @return string
     */
    protected function validateAlign($align)
    {
        return array_key_exists($align, self::ALIGNS) ? $align : 'center';
    }
    
    /**
     * التحقق من صحة الخلفية
     *
     * @param  string  $background
     * @return string
     */
    protected function validateBackground($background)
    {
        return array_key_exists($background, self::BACKGROUNDS) ? $background : 'blur';
    }
    
    /**
     * الحصول على فئات CSS للحجم
     *
     * @return string
     */
    public function sizeClasses()
    {
        return [
            'sm' => 'max-w-sm',
            'md' => 'max-w-md',
            'lg' => 'max-w-lg',
            'xl' => 'max-w-xl',
            'full' => 'max-w-full',
        ][$this->size];
    }
    
    /**
     * الحصول على فئات CSS للمحاذاة
     *
     * @return string
     */
    public function alignClasses()
    {
        return [
            'center' => 'items-center',
            'top' => 'items-start',
            'bottom' => 'items-end',
        ][$this->align];
    }
    
    /**
     * الحصول على فئات CSS للخلفية
     *
     * @return string
     */
    public function backgroundClasses()
    {
        return [
            'blur' => 'bg-black bg-opacity-30 backdrop-blur-sm',
            'dark' => 'bg-black bg-opacity-70',
            'light' => 'bg-white bg-opacity-70',
            'none' => 'bg-transparent',
        ][$this->background];
    }

    /**
     * الحصول على عرض المكون
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dialog-modal');
    }
}