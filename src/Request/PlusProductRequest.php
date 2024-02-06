<?php

namespace Request;

class PlusProductRequest extends Request
{
    public function getName()
    {
        return $this->body['name'];
    }

    public function getId()
    {
        return $this->body['product-id'];
    }
}