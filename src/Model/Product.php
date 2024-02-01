<?php

namespace Model;

class Product extends Model
{
//    private int $id;
//    private string $name;
//    private string $description;
//    private float $price;
//    private string $img_url;
//
//    public function __construct(int $id, string $name, string $description, float $price, string $img_url)
//    {
//        $this->id = $id;
//        $this->name = $name;
//        $this->description = $description;
//        $this->price = $price;
//        $this->img_url = $img_url;
//    }
//
//    public function getId(): int
//    {
//        return $this->id;
//    }
//
//    public function getName(): string
//    {
//        return $this->name;
//    }
//
//    public function getDescription(): string
//    {
//        return $this->description;
//    }
//
//    public function getPrice(): float
//    {
//        return $this->price;
//    }
//
//    public function getImgUrl(): string
//    {
//        return $this->img_url;
//    }


    public static function getAll() : array
    {
        $stmt = self::getPdo()->query('SELECT * FROM products');
        return $stmt->fetchAll();
    }

    public function getProductFromCartInfo($productInCartId)
    {
        $stmt = self::getPdo()->prepare('SELECT * FROM products WHERE id = :productInCartId');
        $stmt->execute(['productInCartId' => $productInCartId]);
        return $stmt->fetch();
//        $data = $stmt->fetch();
//
//        return new Product ($data['id'], $data['name'], $data['description'], $data['price'], $data['img_url']);
    }

}