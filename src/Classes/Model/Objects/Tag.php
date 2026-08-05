<?php
namespace MDJ\Model\Objects;

class Tag
{
    private int $id;
    private string $tag;

    public function __construct(int $id, string $tag)
    {
        $this->id = $id;
        $this->tag = $tag;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTag(): string
    {
        return $this->tag;
    }

    public function setTag(string $tag): void
    {
        $this->tag = $tag;
    }

}