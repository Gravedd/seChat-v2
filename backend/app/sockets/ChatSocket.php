<?php
namespace App\sockets;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DialoguesController;
use App\Http\Controllers\MessagesController;
use App\Models\SocketUser;
use App\sockets\base\baseSocket;
use Psy\Util\Json;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;



class ChatSocket extends baseSocket {
    protected $clients;//Соеднинения клиентов

    public function __construct() {
        $this->clients = [];
        SocketUser::truncate();//Сброс бд
        echo "Сервер успешно запущен!\n";
    }

    //Открытие сокета
    public function onOpen(ConnectionInterface $conn) {
        $this->clients[$conn->resourceId] = $conn;
        $this->clients[$conn->resourceId]->auth = false;
        echo " Новое подключение: ($conn->resourceId)\n";
        $this->sendAuthRequest($conn);
    }

    //Сообщение
    public function onMessage(ConnectionInterface $from, $msg) {
        echo $from->resourceId . ' : ' . $msg;
        $msg = json_decode($msg, true);

        //Определяем тип сообщения
        switch ($msg['type']) {
            //Если тип - авторизация
            case 'auth':
                $this->Authorization($from, $msg['token']);
                break;
            case 'getdialogues':
                $dialogues = DialoguesController::getDialogues($this->clients[$from->resourceId]->id);
                $this->clients[$from->resourceId]->send(json_encode(['type'=>'dialogueslist', 'dialogues'=>$dialogues]));
                break;
            case 'getmessages':
                $messages = MessagesController::getUserMessagesInDialogWS($this->clients[$from->resourceId]->id, $msg['userid']);
                $this->clients[$from->resourceId]->send(json_encode(['type'=>'getmessages', 'user_id' => $msg['userid'], 'messages'=>$messages]));
                break;
            case 'message':
                $this->sendMessagesToUser($this->clients[$from->resourceId]->id, $msg['to'], $msg['messagetext']);
                break;

            default:
                $from->send(json_encode(['message'=> 'не корректный тип сообщения']));
                break;
        }

    }
    //Закрытие сокета
    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        if (isset($this->clients[$conn->resourceId]->auth)) {
            $this->removeUserInDB($conn->resourceId);
        }
        unset($this->clients[$conn->resourceId]);
        echo "Пользователь ({$conn->resourceId}) отключился\n";
    }

    //Ошибка
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }



    /** Методы отправки сообщений */

    protected function sendMessagesToUser ($uid, $receiver_id, $message) {
        $jsontosend = json_encode([
            'type' => 'newmessage',
            'sender_id' => $uid,
            'receiver_id' => $receiver_id,
            'message' => $message,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
            'readed' => 0,
            'id' => '1',
        ]);
        foreach ($this->clients as $client) {
            if ($client->id == $receiver_id) {
                $client->send($jsontosend);
            }
        }
        if (!MessagesController::SaveMessageInDB($uid, $receiver_id, $message)) {
            $log = date('Y-m-d H:i:s').'->'."Сообщение от $uid для $receiver_id ($message) не было сохранно в БД";
            file_put_contents(app_path() . '/logs/log.txt', $log . PHP_EOL, FILE_APPEND);
        }
    }

    protected function sendMessagesToUserWithoutSender () {

    }


    /** МЕТОДЫ АВТОРИЗАЦИИ */

    protected function sendAuthRequest($user){
        $text = json_encode(['type'=>'sendauthtoken']);
        $user->send($text);
    }
    protected function Authorization($requser, $token){
        //Получаем объект пользователя по токену
        $user = $this->AuthorizationCheckToken($token);
        if (isset($user)) {
            //Добавляем инф в БД
            $this->successAuthenticated($user->id, $requser->resourceId);
            //Записываем, что пользователь авторизован
            $this->clients[$requser->resourceId]->auth = true;
            $this->clients[$requser->resourceId]->id = $user->id;
            echo "Пользователь $user->name ($requser->resourceId) c айди $user->id успешно авторизован\n";
        } else {
            $requser->send(json_encode(['message'=> 'Не верный токен']));
        }
    }
    protected function AuthorizationCheckToken($token){
        //Проверка, есть ли пользователь с таким токеном
        $usertoken = AuthController::checkWsAuth($token);
            if (!isset($usertoken)) {
                return false;
            }
        //Получаем объект пользователя
        $user = $usertoken->tokenable;
            if (!isset($user)) {
                return false;
            }
        return $user;
    }
    protected function successAuthenticated($uid, $resid) {
        $socketUser = new SocketUser;
            $socketUser->user_id = $uid;
            $socketUser->resource_id = $resid;
        $socketUser->save();
    }
    protected function removeUserInDB($resid){
        $socketUser = SocketUser::Where('resource_id', $resid)->first();
        if (isset($socketUser)) {
            $socketUser->delete();
        }
    }
    /** КОНЕЦ МЕТОДЫ АВТОРИЗАЦИИ */
}
