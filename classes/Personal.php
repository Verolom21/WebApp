<?php

namespace classes;

use classes\DB;

class Personal
{
    private DB $conn;
    public int $userID;

    public function __construct(string $login)
    {
        $this->conn = new DB();
        $this->userID = $this->getUserID($login);
        return $this->conn;
    }

    // получить по логину userID
    public function getUserID(string $login): int
    {
        $query = "SELECT `id` FROM `users` WHERE userName = ?";
        $queryParams = [$login];
        $result = $this->conn->exec($query, $queryParams);
        return (int)$result['id'];
    }

    // получить информацию всю инфу по юзеру
    public function getPersonalInfo(): array
    {
        $query = "SELECT personal.* FROM personal INNER JOIN users ON personal.idUser=users.id AND users.id=?";
        $queryParams = [$this->userID];
        $result = $this->conn->exec($query, $queryParams);

        if (count($result) > 0) {
            return $result;
        }
        return [];
    }

    // проверить есть ли юзер в базе, если да вернуть массив с парой логин:пароль
    public function checkUser(): array
    {
        $query = "SELECT `userName`, `password` FROM users where `id` = ?";
        $queryParams = [$this->userID];
        $result = $this->conn->exec($query, $queryParams);

        if (count($result) > 0 && $result != false) {
            return $result;
        }
        return [];
    }

    // подарок получен
    public function setRecivedGift(): bool
    {
        $query = "UPDATE `personal` SET `giftIsRecived`='1' WHERE `idUser`=?;";
        $queryParams = [$this->userID];
        $result = $this->conn->exec($query, $queryParams);
        return (bool)$result;
    }

    // получить подарок
    public function receiveGift(): bool
    {

        // reciveMoney - 1 проверяем сколько денег на счету, если достаточно, добавляем в массив
        // recivePoints - 2 - начислить баллы можно всегда
        // reciveObject - 3 проверяем есть ли хоть какие-то подарки на складе, если есть добавляем в массив
        $methods = [2];

        $query = "SELECT * FROM `gifts`";
        $result = $this->conn->fetchAll($query);
        foreach ($result as $el) {
            if ($el['id'] == 1 && $el['count'] > 0) {
                array_unshift($methods, 1);
            }
            if (($el['id'] == 2 || $el['id'] == 3 || $el['id'] == 5) && $el['count'] > 0) {
                $methods[] = 3;
                break;
            }
        }

        $value = array_rand($methods);

        switch ($methods[$value]) {
            case 1:
                $res = $this->reciveMoney();
                break;
            case 2:
                $res = $this->recivePoints();
                break;
            case 3:
                $res = $this->reciveObject();
                break;
        }

        $this->setRecivedGift();


        return $res;

    }

    // перевести деньги в банк
    public function transferInBank(): bool
    {
        $query = "SELECT `money` WHERE `idUser`=?";
        $queryParams = [$this->userID];
        $result = $this->conn->exec($query, $queryParams);
        $money = $result['money'];

        $query = "UPDATE `testtask`.`personal` SET `money`='0', `bank`=?, `moneyInBank`=1 WHERE  `idUser`=?;";
        $queryParams = [$money, $this->userID];
        $result = $this->conn->exec($query, $queryParams);


        $this->setStatus(4);


        return $result;
    }

    // конвертировать деньги в бонусные баллы
    public function convertMoneyInPoints(): bool
    {
        $coff = 1.5;
        $query = "UPDATE `personal` SET `bonusPoints`=`money`* ?, `money`=0, `bonusPointСonverted` = 1 WHERE  `idUser`=?";
        $queryParams = [$coff, $this->userID];
        $result = $this->conn->exec($query, $queryParams);

        $this->setStatus(5);

        return $result;
    }

    // отказаться от подарка
    public function refusedGift(): bool
    {
        $query = "UPDATE `personal` SET `refusedGift`='1', `idObject`='0' WHERE  `idUser`=?";
        $queryParams = [$this->userID];
        $result = $this->conn->exec($query, $queryParams);


        $this->setStatus(7);


        return $result;
    }

    // отправить подарок по почте
    public function sendObject(): bool
    {
        $query = "UPDATE `personal` SET `objectIsSend`=1 WHERE `idUser`=?";
        $queryParams = [$this->userID];
        $result = $this->conn->exec($query, $queryParams);

        $this->setStatus(6);

        return $result;
    }

    // получить деньги
    private function reciveMoney(): bool
    {
        $query = "SELECT * FROM `gifts` WHERE id=1";
        $result = $this->conn->exec($query);

        $money = $result['count'];
        if ($money < 1000) {
            $randomMoney = rand(1, $result['count']);
        } else {
            $randomMoney = rand(200, 1000);
        }

        $query = "UPDATE `gifts` SET `count`=`count` - ? WHERE `id`=1; UPDATE `personal` SET `money`= ? WHERE `idUser`= ?;";
        $queryParams = [$randomMoney, $randomMoney, $this->userID];
        $result = $this->conn->exec($query, $queryParams);


        $this->setStatus(1);


        return $result;

    }

    // получить бонусные баллы
    private function recivePoints(): bool
    {

        $points = rand(200, 500);
        $query = "UPDATE `personal` SET `bonusPoints`='?' WHERE `idUser`=?;";
        $queryParams = [$points, $this->userID];
        $result = $this->conn->exec($query, $queryParams);


        $this->setStatus(1);


        return $result;
    }

    // получить случайный подарок
    private function reciveObject(): bool
    {
        $query = "SELECT * FROM `gifts` WHERE id IN (2, 3, 4)";
        $result = $this->conn->exec($query);
        $tmp = [];
        foreach ($result as $el) {
            if ($el > 0) {
                $tmp[] = $el['id'];
            }
        }
        $objID = array_rand($tmp);

        $query = "UPDATE `gifts` SET `count`=`count`-1 WHERE `id`=?; UPDATE `personal` SET `idObject`=? WHERE `idUser`=?";
        $queryParams = [$objID, $objID, $this->userID];
        $result = $this->conn->exec($query, $queryParams);


        $this->setStatus(3);


        return $result;

    }

    // задаем статус (статусы хранятся в таблице statuses)
    // 0 Подарок не получен
    // 1 Получен денежный приз
    // 2 Получены бонусные баллы
    // 3 Получен физический предмет
    // 4 Денежный приз перечислен в банк
    // 5 Денежный приз конвертирован в баллы лояльности
    // 6 Предмет отправлен по почте
    // 7 Отказ от подарка
    private function setStatus(int $status): bool
    {
        $query = "UPDATE `personal` SET `status`=? WHERE `idUser`=?";
        $queryParams = [$status, $this->userID];
        $result = $this->conn->exec($query, $queryParams);
        return $result;
    }
}