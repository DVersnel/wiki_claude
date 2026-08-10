<?php

namespace MDJ\View\Forms\FormFields;

class TextArea extends Textfield
{
    public function __construct($name, $label, $placeholder, $value = '')
    {
        parent::__construct($name, $label, $placeholder, $value);
    }

    public function show()
    {
        echo '<div class="mb-3">
                <label for="' . $this->label . '" class="form-label d-block text-start">' . htmlspecialchars($this->name) . '</label>
                <textarea class="form-control" rows="4" id="' . $this->label . '" name="' . $this->label . '" placeholder="' . htmlspecialchars($this->placeholder) . '">' . htmlspecialchars($this->value) . '</textarea>
                </div>';
    }
}