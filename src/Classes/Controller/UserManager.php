<?php

namespace MDJ\Controller;
use MDJ\Model\Objects\User;
use MDJ\Model\Repositories\UserRepository;
class UserManager
{
    public function loginUser(string $email, string $password): array
    {
        try
        {
            $userrepo = new UserRepository();
            $user_info = $userrepo -> verifyUser($email, $password);
            if (!$user_info)
            {
                throw new \Exception('Error: Invalid email or password');
            }
            $user = $userrepo->getUserById((int)$user_info['id']);
            $_SESSION['logged_in'] = true;
            $_SESSION['user'] = $user;
        }

        catch (\Exception $e)
        {
            return ['success' => false, 'message' => $e->getMessage()];
        }
        return ['success' => true, 'message' => 'Login successful', 'user' => $user];
    }

    public function registerUser(User $user): array
    {
        try 
        {
            $userrepo = new UserRepository();
            $user_info = $userrepo -> getUserByEmail($user->getEmail());
            if ($user_info)
            {
                throw new \Exception('Error: User already exists');
            }
            
            $result = $userrepo -> createUser($user);
            if (!$result) 
            {
                throw new \Exception('Error: Writing user to database failed');
            }
        } 
        catch (\Exception $e) 
        {
        return ['success' => false, 'message' => $e->getMessage()];
        }
    return ['success' => true, 'message' => 'Registration successful', $result];
    }

    public function logoutUser(): array
    {
        session_unset();
        session_destroy();

        return ['success' => true, 'message' => 'Logout successful'];
    }
}