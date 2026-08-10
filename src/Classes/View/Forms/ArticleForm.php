<?php
namespace MDJ\View\Forms;

use MDJ\View\Form;

class ArticleForm extends Form
{
    private int $article_id;
    private string $existing_image;

    public function __construct(int $article_id, array $fields, string $existing_image = '')
    {
        $this->article_id = $article_id;
        $this->existing_image = $existing_image;
        parent::__construct($fields);
    }

    protected function openForm()
    {
        echo '<form class="col-5 mx-auto" method="post" enctype="multipart/form-data" action="' . \Config::BASEURL . '">';
        echo '<input type="hidden" name="page" value="edit">';
        echo '<input type="hidden" name="id" value="' . $this->article_id . '">';
    }

    protected function showFields()
    {
        if ($this->existing_image !== '')
        {
            echo '<div class="mb-3 text-center">
                    <img src="' . htmlspecialchars($this->existing_image) . '" class="img-fluid rounded" style="max-height: 200px;">
                  </div>';
        }
        parent::showFields();
    }
}
