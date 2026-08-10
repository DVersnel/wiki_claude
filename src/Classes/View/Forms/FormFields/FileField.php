<?php
namespace MDJ\View\Forms\FormFields;

use MDJ\Interfaces\iView;

class FileField implements iView
{
    protected string $name;
    protected string $label;

    public function __construct(string $name, string $label)
    {
        $this->name = $name;
        $this->label = $label;
    }

    public function show()
    {
        echo '<div class="mb-3">
                <label for="' . $this->label . '" class="form-label d-block text-start">' . htmlspecialchars($this->name) . '</label>
                <input type="file" class="form-control" id="' . $this->label . '" name="' . $this->label . '" accept="image/*">
                </div>';
    }
}
