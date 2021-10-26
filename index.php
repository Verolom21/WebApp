<?php

if (!isset($_COOKIE['auth'])) {
    require_once 'templates/auth.php';
} else {
    $login = $_COOKIE['login'];
    require_once 'templates/personal.php';
}








