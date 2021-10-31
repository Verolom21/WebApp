function getCookie(name) {
    var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function showBlock(e) {
    e.classList.remove('hide');
}

let login = getCookie('login')
let url = '/templates/ajax.php?login=' + login;
let objects = {
    2: 'Открытка',
    3: 'Часы',
    4: 'Бонусная карта',
}
let userInfo;

$.ajax({
    url: url + '&getInfo=1',
    method: 'get',
    dataType: 'json',
    async: false,
    success: function (data) {
        userInfo = data;
    }
})

console.log(userInfo)
switch (Number(userInfo['status'])) {
    // получить подарок
    case 0:
        let takeGiftBlock = document.querySelector('.takeGiftBlock');
        showBlock(takeGiftBlock);

        let butReceiveGift = document.querySelector('.receiveGift');
        butReceiveGift.addEventListener('click', function () {
            $.ajax({
                url: url + '&receiveGift',
                method: 'get',
                dataType: 'json',
                async: false,
                success: function (data) {
                    userInfo = data;
                }
            })
            window.location.reload();
        });
        break;

    // 1 Получен денежный приз
    case 1:
        let giftMoney = document.querySelector('.giftMoneyBlock');
        showBlock(giftMoney);

        let howMoney = document.querySelector('.howMoney');
        howMoney.textContent = userInfo['money'];

        let sendInBank = document.querySelector('.sendInBank');
        sendInBank.addEventListener('click', function () {
            $.ajax({
                url: url + '&sendInBank',
                method: 'get',
                dataType: 'json',
                async: false
            });
            window.location.reload();
        });

        let convertInPoints = document.querySelector('.convertInPoints');
        convertInPoints.addEventListener('click', function () {
            $.ajax({
                url: url + '&convertInPoints',
                method: 'get',
                dataType: 'json',
                async: false
            });
            window.location.reload();
        });


        break;

    // 2 Получены бонусные баллы
    case 2:

        let bonusPointsBlock = document.querySelector('.bonusPointsBlock');
        showBlock(bonusPointsBlock);
        let bonusPoints = document.querySelector('.bonusPoints');
        bonusPoints.textContent = userInfo['bonusPoints'];
        break;

    // 3 Получен физический предмет
    case 3:

        let objectBlock = document.querySelector('.objectBlock');
        showBlock(objectBlock);

        let howObject = document.querySelector('.howObject');
        howObject.textContent = objects[Number(userInfo['idObject'])];

        let sendObject = document.querySelector('.sendObject');
        sendObject.addEventListener('click', function () {
            $.ajax({
                url: url + '&sendObject',
                method: 'get',
                dataType: 'json',
                async: false
            });
            window.location.reload();
        });

        let rejectObject = document.querySelector('.rejectObject');
        rejectObject.addEventListener('click', function () {
            $.ajax({
                url: url + '&rejectObject',
                method: 'get',
                dataType: 'json',
                async: false
            });
            window.location.reload();
        });


        break;

    // 4 Денежный приз перечислен в банк
    case 4:
        let moneyBankBlock = document.querySelector('.moneyBankBlock');
        showBlock(moneyBankBlock);
        let moneyBank = document.querySelector('.moneyBank');
        moneyBank.textContent = userInfo['moneyInBank']
        break;

    // 5 Денежный приз конвертирован в баллы лояльности
    case 5:
        let moneyInPointsBlock = document.querySelector('.moneyInPointsBlock');
        showBlock(moneyInPointsBlock);

        let convertPoints = document.querySelector('.convertPoints');
        convertPoints.textContent = userInfo['bonusPoints'];
        break;

    // 6 Предмет отправлен по почте
    case 6:
        let sendObjectBlock = document.querySelector('.sendObjectBlock');
        showBlock(sendObjectBlock);

        let object = document.querySelector('.object');
        object.textContent = objects[Number(userInfo['idObject'])];
        break;

    // 7 Отказ от подарка
    case 7:
        let rejectGiftBlock = document.querySelector('.rejectGiftBlock');
        showBlock(rejectGiftBlock);
        rejectGiftBlock.classList.remove('hide');
        break;
}
