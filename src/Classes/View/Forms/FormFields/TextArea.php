<?php

namespace MDJ\View\Forms\FormFields;

class TextArea extends Textfield
{
    public function __construct($name, $label, $placeholder)
    {
        parent::__construct($name, $label, $placeholder);
    }

    public function show()
    {
        echo '<div class="mb-3">
                <label for="' . $this->label . '" class="form-label d-block text-start">' . $this->name . '</label>
                <textarea class="form-control" rows="4" id="' . $this->label . '" placeholder="' . $this->placeholder . '"></textarea>
                </div>';
    }
}