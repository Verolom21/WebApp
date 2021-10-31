<?php

use classes\Personal;
use classes\DB;

require $_SERVER['DOCUMENT_ROOT'].'/classes/Autoloader.php';
$settings = require $_SERVER['DOCUMENT_ROOT'].'/settings.php';
Autoloader::register();

if (isset($_POST['login'])) {
    $login = $_POST['login'];
}

if (isset($_GET['login'])) {
    $login = $_GET['login'];
}

$user = new Personal($login);

if (isset($_POST['login']) && isset($_POST['password'])) {
    $password = $_POST['password'];

    $userInfo = $user->checkUser();
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
    return;
}

if (isset($_GET['login']) and isset($_GET['getInfo'])) {
    $personalInfo = $user->getPersonalInfo();
    echo json_encode($personalInfo);
    return;
}

if (isset($_GET['login']) and isset($_GET['receiveGift'])) {
    $personalInfo = $user->receiveGift();
    echo json_encode($personalInfo);
    return;
}

if (isset($_GET['login']) and isset($_GET['sendObject'])) {
    $res = $user->sendObject();
    echo json_encode($res);
    return;
}

if (isset($_GET['login']) and isset($_GET['sendInBank'])) {
    $res = $user->transferInBank();
    echo json_encode($res);
    return;
}

if (isset($_GET['login']) and isset($_GET['convertInPoints'])) {
    $res = $user->convertMoneyInPoints();
    echo json_encode($res);
    return;
}

if (isset($_GET['login']) and isset($_GET['rejectObject'])) {
    $res = $user->refusedGift();
    echo json_encode($res);
    return;
}


