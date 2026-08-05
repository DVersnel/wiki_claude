<?php
namespace MDJ\Model;

class Db
{

    private $db;

    // Constructor that makes a new database connection
    public function __construct()
    {
        $this->db = new \PDO(
            'mysql:host=localhost:8080;
            dbname=wiki',
            'dbuser',
            'dbpass'
        );
    }

    // Disconnect from the database
    public function disconnect()
    {
        $this->db = null;
    }

    // Fetch a single row for a query and its parameters
    // Input: $query; SQL query $params; The parameters of the SQL query
    // Output: Associative array from database
    public function fetch($query, $params): array
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Fetch all rows for a query and its parameters
    // Input: $query; SQL query $params; The parameters of the SQL query
    // Output: Associative array from database
    public function fetchAll($query, $params = []): array
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Execute a query onto the database with its parameters
    // Input: $query; SQL query $params; The parameters of the SQL query
    public function execute($query, $params): void
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
    }

    // Gets the ID of the last inserted entry of the database
    // Output: Last inserted ID
    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }
}