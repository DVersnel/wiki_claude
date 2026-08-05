<?php

namespace MDJ\View\Forms\FormFields;

class EmailField extends Textfield
{
    protected string $placeholder;
    protected string $value;

    public function __construct(string $name, string $label, string $placeholder = 'Enter your email address', string $value = '')
    {
        parent::__construct($name, $label);
        $this->placeholder = $placeholder;
        $this->value = $value;
    }

    public function show()
    {
        echo '<div class="mb-3">
                <label for="' . $this->label . '" class="form-label d-block text-start">' . $this->name . '</label>
                <input type="email" class="form-control" id="' . $this->label . '" name="' . $this->label . '" placeholder="' . $this->placeholder . '" value="' . $this->value . '">
                </div>';        
    }
}