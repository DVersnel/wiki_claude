<?php
namespace MDJ\View\Forms\FormFields;

class Checkbox extends Textfield
{
    protected string $name;
    protected string $label;
    protected bool $checked;

    public function __construct(string $name, string $label, bool $checked = false)
    {
        parent::__construct($name, $label);
        $this->checked = $checked;
    }

    public function show()
    {
        $checkedAttr = $this->checked ? ' checked' : '';
        echo '<div class="form-check mb-3">';
        echo '<input class="form-check-input" type="checkbox" id="' . htmlspecialchars($this->label) . '" name="' . htmlspecialchars($this->label) . '" value="1"' . $checkedAttr . '>';
        echo '<label class="form-check-label d-block text-start" for="' . htmlspecialchars($this->label) . '">' . htmlspecialchars($this->name) . '</label>';
        echo '</div>';
    }
}