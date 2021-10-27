<?php
use classes\Database;

$cookie = $_COOKIE;
require $_SERVER['DOCUMENT_ROOT'].'/classes/Autoloader.php';
$settings = require $_SERVER['DOCUMENT_ROOT'].'/settings.php';
Autoloader::register();


if (!isset($cookie['auth'])) {
    require_once 'templates/auth.php';
} else {
    $login = $cookie['login'];
    require_once 'templates/personal.php';
}








