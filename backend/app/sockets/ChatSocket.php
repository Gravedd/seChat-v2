<?php
namespace App\sockets;
use App\sockets\base\baseSocket;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


class ChatSocket extends baseSocket {
    protected $clients;//Соеднинения клиентов

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    //Открытие сокета
    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    //Сообщение
    public function onMessage(ConnectionInterface $from, $msg) {
        $sent = json_encode($from->resourceId);//resourceId - индификатор пользователя у ws
        $from->send($sent);
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }
    //Закрытие сокета
    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    //Ошибка
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
