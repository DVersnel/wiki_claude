<?php
namespace MDJ\View\Forms\FormFields;

use MDJ\Interfaces\iView;

class Textfield implements iView 
{
    protected string $type = 'text';          
    protected string $name;
    protected string  $label;
    protected string $placeholder;

    public function __construct(string $name = '', string $label, string $placeholder = '')
    {
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
    }

    public function show()
    {
        echo '<div class="mb-3">
                <label for="' . $this->label . '" class="form-label">' . $this->name . '</label>
                <input type="'.$this->type.'" class="form-control" id="' . $this->label . '" placeholder="' . $this->placeholder .'">
                </div>';
    }
}