<?php

namespace Model;

class Product extends Model
{
    private int $id;
    private string $name;
    private string $description;
    private float $price;
    private string $img_url;

    public function __construct(int $id, string $name, string $description, float $price, string $img_url)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->img_url = $img_url;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getImgUrl(): string
    {
        return $this->img_url;
    }


    public static function getAll(): ?array
    {
        $stmt = self::getPdo()->query('SELECT * FROM products');
        $data = $stmt->fetchAll();

        if (empty($data)) {
            return null;
        }

        return static::hydrateAll($data);
    }

    public static function getOneById(int $productId): ?Product
    {
        $stmt = self::getPdo()->prepare('SELECT * FROM products WHERE id = :productId');
        $stmt->execute(['productId' => $productId]);
        $data = $stmt->fetch();

        if (empty($data)) {
            return null;
        }

        return new Product ($data['id'], $data['name'], $data['description'], $data['price'], $data['img_url']);
    }

    public static function getAllByIds(array $productIds) :? array
    {
        $string = implode(", ", $productIds);
        $stmt = self::getPdo()->query("SELECT * FROM products WHERE id IN ($string)");
        $data = $stmt->fetchAll();

        if (empty($data)) {
            return null;
        }

        return static::hydrateAll($data);
    }

    private static function hydrateAll(array $data) : array
    {
        $result = [];
        foreach ($data as $product) {
            $result[$product['id']] = new Product($product['id'], $product['name'], $product['description'], $product['price'], $product['img_url']);
        }

        return $result;
    }
}