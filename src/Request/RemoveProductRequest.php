<?php

namespace Request;

class RemoveProductRequest extends Request
{

    public function getUserId()
    {
        return $this->body['user-id'];
    }

    public function getProductId()
    {
        return $this->body['product-id'];
    }

    public function validate(): array
    {
        $errors = [];
        $productId = $this->getProductId();
        $userId = $this->getUserId();

        if (empty($productId))
        {
            $errors['product-id'] =  "Поле product-id не заполнено";
        }

        if (empty($userId)) {
            $errors['user-id'] = "Поле user-id не заполнено";
        }

        return $errors;
    }
}