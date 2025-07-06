<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatCard extends Component
{
    /**
     * عنوان البطاقة
     *
     * @var string
     */
    public $title;
    
    /**
     * قيمة البطاقة
     *
     * @var string|int
     */
    public $value;
    
    /**
     * أيقونة البطاقة
     *
     * @var string
     */
    public $icon;
    
    /**
     * لون خلفية البطاقة
     *
     * @var string
     */
    public $color;
    
    /**
     * اتجاه الاتجاه (زيادة/نقصان)
     *
     * @var string|null
     */
    public $trend;
    
    /**
     * قيمة الاتجاه (نسبة مئوية)
     *
     * @var string|null
     */
    public $trendValue;
    
    /**
     * رابط التفاصيل
     *
     * @var string|null
     */
    public $url;
    
    /**
     * نص رابط التفاصيل
     *
     * @var string|null
     */
    public $urlText;
    
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
        'blue' => 'blue',
        'green' => 'green',
        'red' => 'red',
        'yellow' => 'yellow',
        'purple' => 'purple',
        'indigo' => 'indigo',
        'pink' => 'pink',
        'gray' => 'gray',
    ];
    
    /**
     * اتجاهات الاتجاه المتاحة
     */
    const TRENDS = [
        'up' => 'up',
        'down' => 'down',
        'neutral' => 'neutral',
    ];

    /**
     * إنشاء نسخة جديدة من المكون
     *
     * @param  string  $title
     * @param  string|int  $value
     * @param  string  $icon
     * @param  string  $color
     * @param  string|null  $trend
     * @param  string|null  $trendValue
     * @param  string|null  $url
     * @param  string|null  $urlText
     * @param  string  $class
     * @return void
     */
    public function __construct(
        $title,
        $value,
        $icon = 'chart-bar',
        $color = 'blue',
        $trend = null,
        $trendValue = null,
        $url = null,
        $urlText = 'عرض التفاصيل',
        $class = ''
    ) {
        $this->title = $title;
        $this->value = $value;
        $this->icon = $icon;
        $this->color = $this->validateColor($color);
        $this->trend = $this->validateTrend($trend);
        $this->trendValue = $trendValue;
        $this->url = $url;
        $this->urlText = $urlText;
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
        return array_key_exists($color, self::COLORS) ? $color : 'blue';
    }
    
    /**
     * التحقق من صحة اتجاه الاتجاه
     *
     * @param  string|null  $trend
     * @return string|null
     */
    protected function validateTrend($trend)
    {
        if ($trend && array_key_exists($trend, self::TRENDS)) {
            return $trend;
        }
        return null;
    }
    
    /**
     * الحصول على فئات CSS حسب اللون
     *
     * @return array
     */
    public function colorClasses()
    {
        $classes = [
            'blue' => [
                'bg' => 'bg-blue-100',
                'text' => 'text-blue-800',
                'icon' => 'text-blue-500',
                'trendUp' => 'text-green-500',
                'trendDown' => 'text-red-500',
            ],
            'green' => [
                'bg' => 'bg-green-100',
                'text' => 'text-green-800',
                'icon' => 'text-green-500',
                'trendUp' => 'text-green-600',
                'trendDown' => 'text-red-600',
            ],
            'red' => [
                'bg' => 'bg-red-100',
                'text' => 'text-red-800',
                'icon' => 'text-red-500',
                'trendUp' => 'text-green-500',
                'trendDown' => 'text-red-500',
            ],
            'yellow' => [
                'bg' => 'bg-yellow-100',
                'text' => 'text-yellow-800',
                'icon' => 'text-yellow-500',
                'trendUp' => 'text-green-500',
                'trendDown' => 'text-red-500',
            ],
            'purple' => [
                'bg' => 'bg-purple-100',
                'text' => 'text-purple-800',
                'icon' => 'text-purple-500',
                'trendUp' => 'text-green-500',
                'trendDown' => 'text-red-500',
            ],
            'indigo' => [
                'bg' => 'bg-indigo-100',
                'text' => 'text-indigo-800',
                'icon' => 'text-indigo-500',
                'trendUp' => 'text-green-500',
                'trendDown' => 'text-red-500',
            ],
            'pink' => [
                'bg' => 'bg-pink-100',
                'text' => 'text-pink-800',
                'icon' => 'text-pink-500',
                'trendUp' => 'text-green-500',
                'trendDown' => 'text-red-500',
            ],
            'gray' => [
                'bg' => 'bg-gray-100',
                'text' => 'text-gray-800',
                'icon' => 'text-gray-500',
                'trendUp' => 'text-green-500',
                'trendDown' => 'text-red-500',
            ],
        ];
        
        return $classes[$this->color] ?? $classes['blue'];
    }
    
    /**
     * الحصول على أيقونة الاتجاه
     *
     * @return string
     */
    public function trendIcon()
    {
        if ($this->trend === 'up') {
            return 'fas fa-arrow-up';
        } elseif ($this->trend === 'down') {
            return 'fas fa-arrow-down';
        }
        return 'fas fa-minus';
    }
    
    /**
     * الحصول على لون نص الاتجاه
     *
     * @return string
     */
    public function trendTextColor()
    {
        if ($this->trend === 'up') {
            return $this->colorClasses()['trendUp'];
        } elseif ($this->trend === 'down') {
            return $this->colorClasses()['trendDown'];
        }
        return 'text-gray-500';
    }

    /**
     * الحصول على عرض المكون
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.stat-card');
    }
}