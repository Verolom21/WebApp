<?php

//$conn = classes\Database::connect(
//    $settings['host'],
//    $settings['database'],
//    $settings['login'],
//    $settings['password']
//);


// id - айди,
// idUser - айди в таблице,
// giftIsRecieved - был ли получен подарок (1 - да, 0 - нет),
// money - сколько денег на счету в банке,
// object - айди предмета,
// bonusPoints - бонусные баллы,
// bonusPointСonverted - были ли конвертированы баллы лояльности (1 - да, 0 - нет),
// moneyInBank - перечислен ли на счет пользователя в банке (1 - да, 0 - нет),
// objectIsSend - был ли отправлен подарок по почте,
// refusedGift - отказались ли от подарка

?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>
    <body>
    <script>

        document.addEventListener('DOMContentLoaded', async () =>
        {
            function getCookie(name) {
                var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
                return matches ? decodeURIComponent(matches[1]) : undefined;
            }
            var login = getCookie('login');
            let response = await fetch('/templates/ajax.php?login=' + login);
            let result = await response.json();
            console.log(result)
        }, false);

    </script>

    <h1>Привет <span class="userName"></span>!</h1>
    <br>
    <div class="personalInfo">

        <div class="bonusPoints">

            <!-- если получены бонусные баллы -->
            <p>Бонусные баллы: <span class="bonusPoints"></span></p>
            <p>Вы можете зачислить баллы на счет лояльности в приложении, нажав на кнопку:</p>
            <button class="sendInBank">Зачислить</button>

            <!-- если конвертированы баллы лояльности -->
            <p>Ваши баллы лояльности в приложении: <span class="bonusPoints"></span></p>

        </div>

        <div class="object">

            <!-- если получен подарок -->
            <p>Вы получили подарок: <span class="object"></span></p>
            <p>Подарок будет отправлен в ближайшее время!</p>

            <!-- если подарок отправлен по почте-->
            <p>Ваш подарок(object) по почте отправлен! <span class="object"></span></p>

            <!-- если отказ от подарка-->
            <p>Вы отказались от подарка!<span class="object"></span></p>

        </div>

        <div class="money">

            <!-- если просто выйграл деньги -->
            <p>Вы выйграли денежный приз в размере <span class="money"></span>!</p>
            <p>Можете перечислить деньги на ваш счет в банке, нажав на кнопку:</p>
            <button class="sendInBank">Отправить</button>

            <!-- если деньги переведены в банк-->
            <p>Ваши деньги переведены в банк!</p>
            <p>Ваш счет в банке: <span class="money"></span></p>

        </div>



    </div>
    <div class="receiveGift">
        <p>Нажмите на кнопку, чтобы получить случайный приз!</p>
        <button id="receiveGift">ПОЛУЧИТЬ ПРИЗ</button>
    </div>


    </body>
    </html>

<?php
