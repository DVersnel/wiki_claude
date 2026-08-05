<?php
namespace MDJ\Model\Objects;

class User
{
    private ?int $id = null;
    private string $name;
    private string $password;
    private string $email;

    public function __construct(?int $id = null, string $name, string $password, string $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
