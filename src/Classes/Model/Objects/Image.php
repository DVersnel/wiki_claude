<?php
namespace MDJ\Model\Objects;

class Image
{
    public int $id;
    public string $description;
    public string $last_edit;
    public string $path;
    public int $article_id;
    public int $display_order;
}
