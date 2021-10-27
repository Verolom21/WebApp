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

<form action="" id="form">
    <label for="login">Введите логин</label>
    <input type="text" name="login" id="login">
    <label for="login">Введите пароль</label>
    <input type="password" name="password" id="login">
    <input type="submit">
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
    form.onsubmit = async (e) => {
        e.preventDefault();
        let response = await fetch('/templates/ajax.php', {
            method: 'POST',
            body: new FormData(form)
        });
        let result = await response.json();
        alert(result.message);
        if (result['res']===1) {
            location.reload();
        }
    };
</script>
</body>
</html>