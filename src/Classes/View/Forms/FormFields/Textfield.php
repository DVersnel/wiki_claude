<?php
namespace MDJ\View\Forms\FormFields;

use MDJ\Interfaces\iView;

class Textfield implements iView 
{
    protected string $type = 'text';
    protected string $name;
    protected string  $label;
    protected string $placeholder;
    protected string $value;

    public function __construct(string $name, string $label, string $placeholder = '', string $value = '')
    {
        $this->name = $name;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->value = $value;
    }

    public function show()
    {
        echo '<div class="mb-3">
                <label for="' . $this->label . '" class="form-label">' . htmlspecialchars($this->name) . '</label>
                <input type="'.$this->type.'" class="form-control" id="' . $this->label . '" name="' . $this->label . '" placeholder="' . htmlspecialchars($this->placeholder) .'" value="' . htmlspecialchars($this->value) . '">
                </div>';
    }
}