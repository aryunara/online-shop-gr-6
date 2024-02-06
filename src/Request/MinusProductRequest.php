<?php

namespace Request;

class MinusProductRequest extends Request
{
    public function getId()
    {
        return $this->body['product-id'];
    }
}