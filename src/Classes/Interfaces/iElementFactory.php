<?php
namespace MDJ\Interfaces;

interface iElementFactory
{
    public function createItem(array $page_info): array;
}