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

if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];

    $userInfo = Database::checkUser($conn, $login);
    $res = 0;
    if (!$userInfo) {
        $message = 'Пользователя с таким логином не существует';
    } elseif ($userInfo['password']==$password) {
        $message = 'Вы авторизованы!';
        setcookie('auth', 'success', strtotime( '+30 days'),'/');
        setcookie('login', $login, strtotime( '+30 days'),'/');
        $res = 1;
    } else {
        $message = 'Неверный пароль';
    }
    echo json_encode([
        'res'=>$res,
        'message'=>$message
    ]);
}

if (isset($_GET['login'])) {
    $personalInfo = Database::getPersonalInfo($conn, $_GET['login']);
    echo json_encode($personalInfo);
}


