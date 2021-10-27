<?php

namespace classes;

use PDO;

class Database
{
    public static $gifts = [1=>'money', //  денежный (случайная сумма в интервале)
                            2=>'bonusPoints', // бонусные баллы (случайная сумма в интервале)
                            3=>'object', // физический предмет (случайный предмет из списка)
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

    private static function exec($conn, $query, $queryParams=[], $option=PDO::FETCH_ASSOC) {
        $sth = $conn->prepare($query);
        $sth->execute($queryParams);
        $result = $sth->fetch($option);
        return $result;
    }

    private static function changeMoney($conn, $money){
        $query = "UPDATE `gifts` SET `money` = ?;";
        $queryParams = [$money];
        $result = self::exec($conn, $query, $queryParams);
        return $result;
    }

    private static function getBonusPoints($conn, $login)
    {
        // доработать
    }

    private static function changeObject($conn, $objects)
    {
        $query = "UPDATE `gifts` SET `objects` = ?;";
        $queryParams = [$objects];
        $result = self::exec($conn, $query, $queryParams);
        return $result;
    }

    public static function checkUser($conn, $login)
    {

        $query = "SELECT `userName`, `password` FROM users where `userName` = ?";
        $queryParams = [$login];
        $result = self::exec($conn, $query, $queryParams);

        if (count($result)>0) {
            return $result;
        }
        return false;
    }

    public static function receiveGift($conn, $login): void {
        $gifts = self::$gifts;
        $query = "SELECT * FROM `gifts`;";
        $result = self::exec($conn, $query, $queryParams);


        if ($result['money']==0) {
            unset($gifts[1]);
        }
        if ($result['objects']==0) {
            unset($gifts[3]);
        }

        $randomGift = array_rand(self::$gifts);

        switch ($randomGift) {

            case 1:
                if ($result['money']<1000) {
                    $randomMoney = rand(1, $result['money']<1000);
                } else {
                    $randomMoney = rand(1, 1000);
                }
                $afterMoney = $result['money']-$randomMoney;
                self::changeMoney($conn, $afterMoney);
                break;

            case 2:
                self::changeBonusPoints($conn, $login);
                break;

            case 3:

                $objects = $result['objects']-1;
                self::changeObject($conn, $objects);
                break;

        }
    }

    public static function getPersonalInfo($conn, $login) {
        $query = "SELECT personal.* FROM personal INNER JOIN users ON personal.idUser=users.id AND users.userName=?";
        $queryParams = [$login];
        $result = self::exec($conn, $query, $queryParams);

        if (count($result)>0) {
            return $result;
        }
        return false;
    }

    public static function transferMoneyUser($conn, $login) {
        $res = 0;
        $query = "SELECT personal.money, personal.idUser FROM personal INNER JOIN users ON personal.idUser=users.id AND users.userName=?";
        $queryParams = [$login];
        $result = self::exec($conn, $query, $queryParams);

        if ($result['money']>0) {
            $query = "UPDATE `personal` SET `money`=?, `bank`=? WHERE `idUser`=?;";
            $queryParams = ['0', $result['money'], $result['idUser']];
            $result = self::exec($conn, $query, $queryParams);
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