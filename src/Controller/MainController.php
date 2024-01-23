<?php

class MainController
{

    public function getCatalog()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /get_login.php');
        } else {
            echo "Hello, {$_SESSION['user_name']}!";
            require_once './../View/catalog.php';
        }
    }
}