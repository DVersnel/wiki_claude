<?php
namespace MDJ\View;

use MDJ\Interfaces\iView;


class Form implements iView
{
    private array $fields;

    public function __construct(array $fields) 
    {
        $this->fields = $fields;
    }

    public function show()
    {
        $this->openForm();
        $this->showFields();
        $this->closeForm();
    }

    protected function openForm()
    {
        $page = $_REQUEST['page'] ?? $_GET['page'] ?? '';
        echo '<form class="col-5 mx-auto" method="post" action="'.\Config::BASEURL.'">';
        echo '<input type="hidden" name="page" value="'.$page.'">';
    }

    protected function showFields()
    {
        foreach ($this->fields as $field) 
        {
            $field->show();
        }
    }

    protected function closeForm()
    {
        echo '<div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>';
        echo '</form>';
    }
}