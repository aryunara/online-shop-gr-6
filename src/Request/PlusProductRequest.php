<?php

namespace Request;

class PlusProductRequest extends Request
{
    public function getId()
    {
        return $this->body['product-id'];
    }

    public function validate(): array
    {
        $errors = [];
        $productId = $this->getId();

        if (empty($productId))
        {
            $errors['minus_product'] =  "Поле product-id не заполнено";
        }

        return $errors;
    }
}