<?php

use classes\Database;

require $_SERVER['DOCUMENT_ROOT'].'/classes/Autoloader.php';
$settings = require $_SERVER['DOCUMENT_ROOT'].'/settings.php';
Autoloader::register();

$conn = Database::connect(
    $settings['host'],
    $settings['database'],
    $settings['login'],
    $settings['password']
);


if (isset($_POST['login']) && isset($_POST['token'])) {
    $login = $_POST['login'];
    $res = Database::transferMoneyUser($conn, $login);

    echo json_encode($res);
}

if (isset($_POST['allUsers'])) {

}