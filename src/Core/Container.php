<?php

namespace Core;

use PDO;

class Container
{
    private array $services;
    private array $instances;

    public function set(string $class, callable $callback): void
    {
        $this->services[$class] = $callback;
    }

    public function get(string $class) : object
    {
        if (isset($this->instances[$class])) {
            return $this->instances[$class];
        }

        if (isset($this->services[$class])) {
            $callback = $this->services[$class];

            $instance = $callback($this);

            $this->instances[$class] = $instance;

            return $instance;
        }

        $instance = new $class();

        $this->instances[$class] = $instance;

        return new $instance;
    }
}