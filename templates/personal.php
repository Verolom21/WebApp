<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    .hide {
        display: none;
    }
</style>
<body>


<h1>Привет <span class="userName"><?=$login;?></span>!</h1>

<br>
<div class="takeGiftBlock hide">
    <p>Нажмите на кнопку, чтобы получить случайный приз!</p>
    <button class="receiveGift">ПОЛУЧИТЬ ПРИЗ</button>
</div>
<div class="personalInfo" style="">
    <div class="giftMoneyBlock hide">
        <p>Вы выйграли денежный приз в размере <span class="howMoney"> рублей!</span>!</p>
        <p>Можете перечислить деньги на ваш счет в банке, нажав на кнопку:</p>
        <button class="sendInBank">Отправить</button>
        <p>ИЛИ</p>
        <p>Ковертировать денежный приз в баллы лояльности нажав на кнопку ниже!</p>
        <button class="convertInPoints">Конвертировать</button>
    </div>
    <div class="moneyBankBlock hide">
        <p>Ваши деньги переведены в банк!</p>
        <p>Ваш счет в банке: <span class="moneyBank"></span></p>
    </div>
    <div class="bonusPointsBlock hide">
        <p>Вы получили бонусные баллы: <span class="bonusPoints"></span></p>
    </div>
    <div class="objectBlock hide">
        <p>Вы получили подарок: <span class="howObject"></span></p>
        <p>Нажмите кнопку если хотите, чтобы вам отправили подарок по почте</p>
        <button class="sendObject">ОТПРАВИТЬ</button>
        <p>ИЛИ</p>
        <p>Вы можете отказаться от подарка:</p>
        <button class="rejectObject">Отказаться</button>

    </div>
    <div class="moneyInPointsBlock hide">
        <p>Вы конверитровали денежный приз в баллы! </p>
        <p>У вас на счету <span class="convertPoints"></span> баллов!</p>
    </div>
    <div class="sendObjectBlock hide">
        <p>Ваш подарок(<span class="object"></span>) отправлен по почте!</p>
    </div>
    <div class="rejectGiftBlock hide">
        <p>Вы отказались от подарка!</p>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="../main.js"></script>
</body>
</html>

<?php
