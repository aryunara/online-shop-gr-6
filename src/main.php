<?php

session_start();
if (!isset($_SESSION['user_id'])) {
   header('Location: /get_login.php');
} else {
    echo "Hello, user!";
    require_once './catalog.php';
}