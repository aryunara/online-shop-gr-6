<?php

namespace Model;
class UserProduct extends Model
{
    private int $id;
    private int $userId;
    private int $productId;
    private int $quantity;

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function __construct(int $id, int $userId, int $productId, int $quantity)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public static function getCart(int $userId): ?array
    {
        $stmt = self::getPdo()->prepare('SELECT * FROM user_products WHERE user_id = :userId');
        $stmt->execute(['userId' => $userId]);
        $data = $stmt->fetchAll();

        if (empty($data)) {
            return null;
        }

        return static::hydrateAll($data);
    }

    public static function getUserProduct(int $productId, int $userId): ?UserProduct
    {
        $stmt = self::getPdo()->prepare('SELECT * FROM user_products WHERE product_id = :productId AND user_id = :userId');
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $data = $stmt->fetch();

        if (empty($data)) {
            return null;
        }

        return new UserProduct($data['id'], $data['user_id'], $data['product_id'], $data['quantity']);
    }

    public static function getUserProductQuantity(int $productId, int $userId) : ?int
    {
        $stmt = self::getPdo()->prepare('SELECT quantity FROM user_products WHERE product_id = :productId AND user_id = :userId');
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
        $data = $stmt->fetch();

        if (empty($data)) {
            return null;
        }

        return $data['quantity'];
    }

    public static function create(int $userId, int $productId, int $quantity) : void
    {
        $stmt = self::getPdo()->prepare("INSERT INTO user_products (user_id, product_id, quantity) VALUES (:userId, :productId, :quantity)");
        $stmt->execute(['userId' => $userId, 'productId' => $productId, 'quantity' => $quantity]);
    }

    public static function deleteProduct(int $productId, int $userId): void
    {
        $stmt = self::getPdo()->prepare('DELETE FROM user_products WHERE product_id = :productId AND user_id = :userId');
        $stmt->execute(['productId' => $productId, 'userId' => $userId]);
    }

    public function save(): void
    {
        $sql = 'UPDATE user_products SET quantity = :quantity WHERE product_id = :productId AND user_id = :userId';
        $stmt = static::getPdo()->prepare($sql);
        $stmt->execute(['quantity' => $this->quantity, 'productId' => $this->productId, 'userId' => $this->userId]);
    }

    public function destroy() : void
    {
        $stmt = self::getPdo()->prepare('DELETE FROM user_products WHERE id = :id');
        $stmt->execute(['id' => $this->id]);
    }

    public static function getCount(int $userId) : int
    {
        $stmt = self::getPdo()->prepare('SELECT SUM(user_products.quantity) FROM user_products WHERE user_products.user_id = :userId');
        $stmt->execute(['userId' => $userId]);
        $result = $stmt->fetch();

        return $result['sum'] ?? 0;
    }

    private static function hydrateAll(array $data) : array
    {
        $result = [];
        foreach ($data as $userProduct) {
            $result[] = new UserProduct($userProduct['id'], $userProduct['user_id'], $userProduct['product_id'], $userProduct['quantity']);
        }

        return $result;
    }

}