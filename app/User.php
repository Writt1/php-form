<?php

namespace app;

class User
{
    private string $table = 'users';

    public function __construct(
        private Database $db
    )
    {
    }

    public function existsByEmail(string $email): bool
    {
        $user = $this->db->findOne($this->table, ['email' => $email]);
        if ($user) {
            return true;
        }
        return false;
    }

    public function existsByPhone(string $phone): bool
    {
        $user = $this->db->findOne($this->table, ['phone' => $phone]);
        if ($user) {
            return true;
        }
        return false;
    }

    public function create(string $name, string $phone, string $email, string $password): int
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $this->db->insert($this->table, [
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'password' => $hash,
        ]);
    }
}