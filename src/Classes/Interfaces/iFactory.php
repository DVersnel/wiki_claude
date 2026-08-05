<?php
namespace MDJ\Interfaces;

interface iFactory
{
    public function createItem(array $data):iView;
}