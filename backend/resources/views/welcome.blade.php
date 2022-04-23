<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<script>
    let connection = new WebSocket('ws://sechat.loc:6001?id=1');
    connection.onopen = function (e) {
        console.log(connection);
        console.log('Подключенно');
    }
    connection.onmessage = function (e) {
        let response = JSON.parse(e.data);
        console.log(response);
        if (response.message === 'sendauthtoken') {
            return console.log(response);
        }
    }
    function send() {
        let data = 'Данные для отправки' + Math.random();
        connection.send(data);
        console.log('Отправленно' + data);
    }



</script>
</html>
