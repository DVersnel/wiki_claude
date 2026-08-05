<?php
namespace MDJ\View\Forms\FormFields;

class PasswordField extends Textfield
{
    public function __construct(string $name, string $label, string $placeholder = 'Enter your password')
    {
        parent::__construct($name, $label, $placeholder);
    }

    public function show()
    {
        echo '<div class="mb-3">
                <label for="' . $this->label . '" class="form-label d-block text-start">' . $this->name . '</label>
                <input type="password" class="form-control" id="' . $this->label . '" name="' . $this->label . '" placeholder="' . $this->placeholder . '">
                </div>';
    }
}