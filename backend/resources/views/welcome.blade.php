<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<script>
    let connection = new WebSocket('ws://sechat.loc:8080');
    connection.onopen = function (e) {
        console.log('Подключенно');
        console.log(connection);
    }
    connection.onmessage = function (e) {
        console.log('Полученно:' + e.data );
    }
    function send() {
        let data = 'Данные для отправки' + Math.random();
        connection.send(data);
        console.log('Отправленно' + data);
    }



</script>
</html>
