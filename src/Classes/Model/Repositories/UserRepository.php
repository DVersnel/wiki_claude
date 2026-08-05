<?php
namespace MDJ\Model\Repositories;

use MDJ\Interfaces\iUsersRepository;
use MDJ\Model\Objects\User;
use ManKind\tools\pdo\QueryParams;

class UserRepository extends BaseRepository implements iUsersRepository
{

    // Get all usernames that have uploaded at least one article
    // Output: Array of username strings
    public function getAuthorNames(): array|false
    {
        $result = $this->db->selectAsPairs(
            "
            SELECT u.id, u.name
            FROM users u
            JOIN articles a
                ON u.id = a.user_id
            GROUP BY a.user_id
            ORDER BY u.name
            "
        );
        
        return count($result) > 0 ? $result : false; 
    }

    // Get a username by its associated user ID
    // Input: $id; User ID
    // Output: Username string
    public function getUsernameById(int $id): string|false
    {
        $sql = 
        "
        SELECT name 
        FROM users 
        WHERE id= :id
        ";

        $params = (new QueryParams())->add("id", $id, true);

        $result = $this->db->selectOne($sql, $params);
        
        if (!$result) 
        {
            return false;
        }

        return $result['name'];
    }

    // Get a User object by its associated email address
    // Input: $email; User's email address
    // Output: User object, or false if not found
    public function getUserByEmail(string $email): User|false
    {
        $sql = 
        "
        SELECT * 
        FROM users 
        WHERE email= :email
        ";

        $params = (new QueryParams())->add("email", $email, false);

        $result = $this->db->selectOne($sql, $params);
        
        if (!$result) 
        {
            return false;
        }

        return $this-> mapToUser($result);    
    }

    // Get a User object by its ID
    // Input: $id; User ID
    // Output: User object, or false if not found
    public function getUserById(int $id): User|false
    {
        $sql = 
        "
        SELECT * 
        FROM users 
        WHERE id= :id
        ";

        $params = (new QueryParams())->add("id", $id, true);

        $result = $this->db->selectOne($sql, $params);
        
        if (!$result) 
        {
            return false;
        }

        return $this-> mapToUser($result);    
    }
    

    // Save a new user to the database
    // Input: $user; User object with name, password, and email
    public function createUser(User $user): int|false
    {
        $params = (new QueryParams())
        ->add("name", $user->getName(), false)
        ->add("password", $user->getPassword(), false)
        ->add("email", $user->getEmail(), false);

        return $this->db->doInsert(
            "
            INSERT INTO users
                (name, password, email)
            VALUES (:name, :password, :email)
            ",
            $params
        );
    }

    public function checkUserExists(string $email): bool
    {
        $sql = 
        "
        SELECT COUNT(*) as count
        FROM users
        WHERE email = :email
        ";

        $params = (new QueryParams())->add("email", $email, false);

        $result = $this->db->selectOne($sql, $params);
        
        return $result && $result['count'] > 0;
    }

    public function verifyUser(string $email, string $password): array|false
    {
        $sql = 
        "
        SELECT id, name
        FROM users
        WHERE email = :email
        and password = :password
        ";

        $params = (new QueryParams())->add("email", $email, false)
        ->add("password", $password, false);

        $result = $this->db->selectOne($sql, $params);
        
        return $result;
    }

    // Update an existing user's data
    // Input: $user; User object with updated data
    public function updateUser(User $user): int|false
    {
        $params = (new QueryParams())
        ->add("id", $user->getId(), true)
        ->add("name", $user->getName(), false)
        ->add("password", $user->getPassword(), false)
        ->add("email", $user->getEmail(), false);

        return $this->db->doUpdate(
            "
            UPDATE users
            SET 
                name = :name,
                password = :password,
                email = :email
            WHERE id = :id
            ",
           $params
        );
    }

    // Delete a user by their ID
    // Input: $id; User ID
    public function deleteUser(int $id): int|false
    {
        $params = (new QueryParams())
        ->add("id", $id, true);

        return $this->db->doDelete(
            "
            DELETE 
            FROM users 
            WHERE id = :id
            ",
            $params
        );
    }

    // Helper function to map an associative array from the database to a User object
    // Input: $row; Array of data from the database
    // Output: User object with data
    private function mapToUser(array $row): User
    {
        return new User
        (
            $row['id'],
            $row['name'],
            $row['password'],
            $row['email']
        );
    }
}
