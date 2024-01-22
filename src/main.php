<?php

session_start();
if (!isset($_SESSION['user_id'])) {
   header('Location: /get_login.php');
} else {
    require_once './catalog.php';
}