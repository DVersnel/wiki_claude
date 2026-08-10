<?php
namespace MDJ\View\Forms\FormFields;

use MDJ\Interfaces\iView;

class TagsSelectField implements iView
{
    protected string $name;
    protected string $label;
    protected array $options;
    protected array $selected;

    public function __construct(string $name, string $label, array $options = [], array $selected = [])
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
        $this->selected = $selected;
    }

    public function show()
    {
        echo '<div class="mb-3">
                <label for="' . $this->label . '_search" class="form-label d-block text-start">' . htmlspecialchars($this->name) . '</label>
                <input type="text" class="form-control mb-1" id="' . $this->label . '_search" placeholder="Search tags..." oninput="filterTagsSelectOptions(this)" autocomplete="off">
                <select class="form-control" id="' . $this->label . '" name="' . $this->label . '[]" multiple size="6">';

        foreach ($this->options as $id => $tag_name)
        {
            $selected_attr = in_array((int)$id, $this->selected, true) ? ' selected' : '';
            echo '<option value="' . (int)$id . '"' . $selected_attr . '>' . htmlspecialchars($tag_name) . '</option>';
        }

        echo '</select>
                <div class="form-text">Hold Ctrl (Cmd on Mac) to select multiple tags.</div>
                </div>';
    }
}
