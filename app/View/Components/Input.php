<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    /**
     * Define all the public props here.
     */
    public string $name;
    public string $type;
    public ?string $label;
    public ?string $placeholder;
    public $value;
    public bool $required;
    public ?string $icon;
    public ?string $rightIcon;
    public ?string $helpText;
    public string $size;
    public string $color;
    public ?string $error;
    public string $class;

    /**
     * Create a new component instance.
     * All arguments are optional except name.
     */
    public function __construct(
        string $name,
        string $type = 'text',
        string $label = null,
        string $placeholder = null,
        $value = null,
        bool $required = false,
        string $icon = null,
        string $rightIcon = null,
        string $helpText = null,
        string $size = 'md',
        string $color = 'primary',
        string $error = null,
        string $class = ''
    ) {
        $this->name        = $name;
        $this->type        = in_array($type, ['text','email','password','number','date','tel','url','search','time'])
                              ? $type
                              : 'text';
        $this->label       = $label;
        $this->placeholder = $placeholder ?: ($label ? 'أدخل ' . $label : null);
        $this->value       = old($name, $value);
        $this->required    = $required;
        $this->icon        = $icon;
        $this->rightIcon   = $rightIcon;
        $this->helpText    = $helpText;
        $this->size        = in_array($size, ['sm','md','lg']) ? $size : 'md';
        $this->color       = in_array($color, ['primary','success','danger','warning','info']) 
                              ? $color 
                              : 'primary';
        $this->error       = $error;
        $this->class       = $class;
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('components.input');
    }
}