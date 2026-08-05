<?php
namespace MDJ\Model\Repositories;

use ManKind\tools\pdo\Crud;

class BaseRepository 
{
    protected $db;

    // Constructor for the database connection
    // Input: $db; Database object
    public function __construct()
    {
        $this->db = Crud::getInstance();
        if ($this->db->isConnected() == false)
        {
            throw new \Exception('oops no db conn');
        }
    }
}