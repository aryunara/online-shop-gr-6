<?php

namespace Model;

class User extends Model implements \JsonSerializable
{
    private int $id;
    private string $name;
    private string $email;
    private string $password;

    public function __construct(int $id, string $name, string $email, string $password)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public static function create(string $name, string $email, string $hash) : ?User
    {
        $stmt = self::getPdo()->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :hash) RETURNING id");
        $stmt->execute(['name' => $name, 'email' => $email, 'hash' => $hash]);

        $result = $stmt->fetch();
        if (!$result) {
            return null;
        }

        return new self($result['id'], $name, $email, $hash);

    }

    public static function getOneByEmail(string $email): ?User
    {
        $stmt = self::getPdo()->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $data = $stmt->fetch();

        if (empty($data)) {
            return null;
        }

        return new User($data['id'], $data['name'], $data['email'], $data['password']);
    }

    public static function getOneById(int $userId): ?User
    {
        $stmt = self::getPdo()->prepare('SELECT * FROM users WHERE id = :userId');
        $stmt->execute(['userId' => $userId]);
        $data = $stmt->fetch();

        if (empty($data)) {
            return null;
        }

        return new User($data['id'], $data['name'], $data['email'], $data['password']);
    }

    public static function all(): ?array
    {
        $stmt = self::getPdo()->query('SELECT * FROM users');
        $data = $stmt->fetchAll();

        if (empty($data)) {
            return null;
        }

        return static::hydrateAll($data);
    }

    private static function hydrateAll(array $data) : array
    {
        $result = [];
        foreach ($data as $user) {
            $result[] = new User($user['id'], $user['name'], $user['email'], $user['password']);
        }

        return $result;
    }

    public function jsonSerialize() : array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password
        ];
    }
}