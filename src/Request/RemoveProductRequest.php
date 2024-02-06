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
}