<?php
namespace MDJ\Model\Objects;

class Article
{
    public int $id;
    public string $name;
    public string $description;
    public string $text;
    public string $code;
    public int $user_id;
    public string $last_edit;
}