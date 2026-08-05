<?php
namespace MDJ\Interfaces;

use MDJ\Model\Objects\User;

interface iUsersRepository
{
    public function getUserById(int $id): User|false;
    public function getUsernameById(int $id): string|false;
    public function getUserByEmail(string $email): User|false;
    public function getAuthorNames(): array|false;
    public function createUser(User $user): int|false;
    public function updateUser(User $user): int|false;
    public function deleteUser(int $id): int|false;
}