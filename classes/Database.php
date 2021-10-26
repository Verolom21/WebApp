<?php

namespace classes;

use PDO;

class Database
{
    public static $gifts = [
      '1' => 'money', //  денежный (случайная сумма в интервале)
      '2' => 'bonusPoints', // бонусные баллы (случайная сумма в интервале)
      '3' => 'object', // физический предмет (случайный предмет из списка)
    ];

    public static function connect($host, $database, $login, $password)
    {
        $conn = new PDO(
            "mysql:host=$host;dbname=$database",
            $login,
            $password
        );
        return $conn;
    }

    public static function checkUser($conn, $login)
    {
        $query = "SELECT `userName`, `password` FROM users where `userName` = ?";
        $sth = $conn->prepare($query);
        $sth->execute([$login]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if (count($result)>0) {
            return $result;
        }
        return false;
    }

    public static function getGift() {

    }

    public static function getPersonalInfo($conn, $login) {
        $query = "SELECT personal.* FROM personal INNER JOIN users ON personal.idUser=users.id AND users.userName=?";
        $sth = $conn->prepare($query);
        $sth->execute([$login]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if (count($result)>0) {
            return $result;
        }
        return false;
    }

    public static function transferMoneyUser($conn, $login) {
        $res = 0;
        $query = "SELECT personal.money, personal.idUser FROM personal INNER JOIN users ON personal.idUser=users.id AND users.userName=?";
        $sth = $conn->prepare($query);
        $sth->execute([$login]);
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        if ($result['money']>0) {
            $query = "UPDATE `personal` SET `money`=?, `bank`=? WHERE `idUser`=?;";
            $sth = $conn->prepare($query);
            $result = $sth->execute([ '0', $result['money'], $result['idUser']]);
            if ($result) {
                $res = 1;
                $message = 'Деньги переведены!';
            } else {
                $message = 'Что-то пошло не так!';
            }

        } else {
            $message = 'Не достаточно денег для перевода в банк!';
        }
        return [
            'res' => $res,
            'message' => $message
        ];
    }




}