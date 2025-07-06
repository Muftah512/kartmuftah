<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ExportButtons extends Component
{
    /**
     * رابط التصدير الأساسي
     *
     * @var string
     */
    public $url;
    
    /**
     * نص الزر الرئيسي
     *
     * @var string
     */
    public $label;
    
    /**
     * التنسيقات المتاحة للتصدير
     *
     * @var array
     */
    public $formats;
    
    /**
     * موقع القائمة المنسدلة
     *
     * @var string
     */
    public $dropdownPosition;
    
    /**
     * أيقونة الزر الرئيسي
     *
     * @var string
     */
    public $icon;
    
    /**
     * إنشاء نسخة جديدة من المكون
     *
     * @param  string  $url
     * @param  string  $label
     * @param  array  $formats
     * @param  string  $dropdownPosition
     * @param  string  $icon
     * @return void
     */
    public function __construct(
        $url = '#',
        $label = 'تصدير',
        $formats = ['pdf', 'excel', 'csv', 'print'],
        $dropdownPosition = 'right',
        $icon = 'file-export'
    ) {
        $this->url = $url;
        $this->label = $label;
        $this->formats = $formats;
        $this->dropdownPosition = $dropdownPosition;
        $this->icon = $icon;
        
        // التحقق من صحة التنسيقات المدخلة
        $this->validateFormats();
        
        // التحقق من صحة موضع القائمة
        $this->validatePosition();
    }

    /**
     * التحقق من صحة التنسيقات المدخلة
     */
    private function validateFormats()
    {
        $validFormats = ['pdf', 'excel', 'csv', 'print'];
        $this->formats = array_filter($this->formats, function ($format) use ($validFormats) {
            return in_array($format, $validFormats);
        });
        
        // إذا لم يكن هناك تنسيقات صالحة، نستخدم التنسيقات الافتراضية
        if (empty($this->formats)) {
            $this->formats = ['pdf', 'excel', 'csv', 'print'];
        }
    }
    
    /**
     * التحقق من صحة موضع القائمة
     */
    private function validatePosition()
    {
        if (!in_array($this->dropdownPosition, ['left', 'right'])) {
            $this->dropdownPosition = 'right';
        }
    }
    
    /**
     * الحصول على ألوان الأيقونات حسب التنسيق
     *
     * @param  string  $format
     * @return string
     */
    public function iconColor($format)
    {
        $colors = [
            'pdf' => 'text-red-500',
            'excel' => 'text-green-600',
            'csv' => 'text-blue-500',
            'print' => 'text-gray-600',
        ];
        
        return $colors[$format] ?? 'text-gray-500';
    }
    
    /**
     * الحصول على أسماء التنسيقات
     *
     * @param  string  $format
     * @return string
     */
    public function formatName($format)
    {
        $names = [
            'pdf' => 'PDF',
            'excel' => 'Excel',
            'csv' => 'CSV',
            'print' => 'طباعة مباشرة',
        ];
        
        return $names[$format] ?? ucfirst($format);
    }
    
    /**
     * الحصول على وصف التنسيقات
     *
     * @param  string  $format
     * @return string
     */
    public function formatDescription($format)
    {
        $descriptions = [
            'pdf' => 'تنسيق ثابت للطباعة',
            'excel' => 'تنسيق جدولي قابل للتعديل',
            'csv' => 'ملف نصي مفصول بفواصل',
            'print' => 'إرسال إلى الطابعة',
        ];
        
        return $descriptions[$format] ?? 'تصدير البيانات';
    }
    
    /**
     * الحصول على أيقونات التنسيقات
     *
     * @param  string  $format
     * @return string
     */
    public function formatIcon($format)
    {
        $icons = [
            'pdf' => 'file-pdf',
            'excel' => 'file-excel',
            'csv' => 'file-csv',
            'print' => 'print',
        ];
        
        return $icons[$format] ?? 'file-export';
    }

    /**
     * الحصول على عرض المكون
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.export-buttons');
    }
}