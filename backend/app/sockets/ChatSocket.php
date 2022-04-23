<?php
namespace App\sockets;

use App\sockets\base\baseSocket;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;



class ChatSocket extends baseSocket {
    protected $clients;//Соеднинения клиентов

    public function __construct() {
        $this->clients = [];
        echo "Сервер успешно запущен!\n";
    }




    //Открытие сокета
    public function onOpen(ConnectionInterface $conn) {
        $this->clients[$conn->resourceId] = $conn;
        echo " Новое подключение: ({$conn->resourceId})\n";
        $authtext = json_encode(['message'=>'sendauthtoken']);
        //Авторизация
        $conn->send($authtext);
    }

    //Сообщение
    public function onMessage(ConnectionInterface $from, $msg) {
        echo $from->resourceId . ' : ' . $msg;
    }
    //Закрытие сокета
    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        unset($this->clients[$conn->resourceId]);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    //Ошибка
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
