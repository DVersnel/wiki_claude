<?php
namespace MDJ\Factories;


use MDJ\Interfaces\iFactory;

class Collection
{
    private iFactory $factory;
    private array $items;

    public function __construct(array $data, iFactory $factory)
    {
        $this->factory = $factory;
        $this->createItems($data);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    private function createItems(array $data)
    {
        foreach ($data as $datum)
        {
            $this->items[] = $this->factory->createItem($datum);
        }
    }
}