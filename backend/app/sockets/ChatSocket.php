<?php
namespace App\sockets;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DialoguesController;
use App\Http\Controllers\MessagesController;
use App\Models\Dialogue;
use App\Models\SocketUser;
use App\Models\User;
use App\sockets\base\baseSocket;
use Psy\Util\Json;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;



class ChatSocket extends baseSocket {
    protected $clients;//Соеднинения клиентов

    /**
     * ChatSocket constructor.
     * This method starts up websocket server
     */
    public function __construct() {
        $this->clients = [];
        echo "     Сброс БД...\n";
        SocketUser::truncate();
        echo "     Сброс онлайна...\n";
        User::where('online', 1)->update(['online'=>0]);
        echo "Сервер успешно запущен!\n";
    }

    /**
     * New connection created
     *
     * The method saves information about the user in an array and sends him a request to authorize     *
     * @param ConnectionInterface $conn
     * @return void
     */
    public function onOpen(ConnectionInterface $conn) {
        $this->clients[$conn->resourceId] = $conn;
        $this->clients[$conn->resourceId]->auth = false;
        echo " Новое подключение: ($conn->resourceId)\n";
        $this->sendAuthRequest($conn);
    }

    /**
     * Server received a message
     *
     * The method checks the message type and chooses the appropriate action
     *
     * @param ConnectionInterface $from
     * @param string $msg
     */
    public function onMessage(ConnectionInterface $from, $msg) {
        echo $from->resourceId . ' : ' . $msg."\n";
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
            case 'typing':
                $this->sendTyping($this->clients[$from->resourceId]->id, $msg['receiver_id']);
                break;
            default:
                $from->send(json_encode(['message'=> 'не корректный тип сообщения']));
                break;
        }

    }

    /**
     * Connection closed
     *
     * delete user information     *
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        if (isset($this->clients[$conn->resourceId]->auth)) {
            $this->removeUserInDB($conn->resourceId);
            $user = User::find($this->clients[$conn->resourceId]->id);
                $user->online = false;
            $user->save();
        }
        unset($this->clients[$conn->resourceId]);
        echo "Пользователь ({$conn->resourceId}) отключился\n";
    }

    /**
     * Connection error
     *
     * Write error
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Ошибка: {$e->getMessage()}\n";
        $conn->close();
    }



    /** Методы отправки сообщений */

    /**
     * Sent message to user $receiver_id from $uid
     *
     * @param $uid
     * @param $receiver_id
     * @param $message
     */
    protected function sendMessagesToUser ($uid, $receiver_id, $message) {
        $this->checkDialogues($uid, $receiver_id);
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

    /**
     * Сhecking if a dialog exists between two users or not
     *
     * Create a dialog if it doesn't exist
     *
     * @param $uid
     * @param $receiver_id
     */
    protected function checkDialogues($uid, $receiver_id) {
        $dialogue = Dialogue::Where(function ($query) use ($uid, $receiver_id){
            $query->Where('user_id', $uid)->Where('user2_id', $receiver_id);
        })->orWhere(function ($query) use ($uid, $receiver_id){
            $query->Where('user_id', $receiver_id)->Where('user2_id', $uid);
        })->first();
        if (!$dialogue) {
            DialoguesController::createDialogue($uid, $receiver_id);
        }
    }

    /**
     * Send message set status to user $receiver_id from $uid
     *
     * @param $uid
     * @param $receiver_id
     */
    protected function sendTyping ($uid, $receiver_id) {
        $jsontosend = json_encode([
            'type' => 'typing',
            'sender_id' => $uid,
        ]);
        foreach ($this->clients as $client) {
            if ($client->id == $receiver_id) {
                $client->send($jsontosend);
            }
        }
    }


    /** МЕТОДЫ АВТОРИЗАЦИИ */

    /**
     * Send auth request
     *
     * @param $user
     */
    protected function sendAuthRequest($user){
        $text = json_encode(['type'=>'sendauthtoken']);
        $user->send($text);
    }

    /**
     * Authorization in websocket
     *
     * @param $requser
     * @param $token
     */
    protected function Authorization($requser, $token){
        //Получаем объект пользователя по токену
        $user = $this->AuthorizationCheckToken($token);
        if (isset($user)) {
            //Добавляем инф в БД
            $this->successAuthenticated($user->id, $requser->resourceId);
            //Записываем, что пользователь авторизован
            $this->clients[$requser->resourceId]->auth = true;
            $this->clients[$requser->resourceId]->id = $user->id;
            $user->online = true;
            $user->save();
            echo "Пользователь $user->name ($requser->resourceId) c айди $user->id успешно авторизован\n";
        } else {
            $requser->send(json_encode(['message'=> 'Не верный токен']));
        }
    }

    /**
     * Сheck user token
     *
     * @param $token
     * @return false|mixed
     */
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

    /**
     * User successfully authorized
     *
     * User information is stored
     *
     * @param $uid
     * @param $resid
     */
    protected function successAuthenticated($uid, $resid) {
        $socketUser = new SocketUser;
            $socketUser->user_id = $uid;
            $socketUser->resource_id = $resid;
        $socketUser->save();
    }

    /**
     * Delete socket user from database
     * @param $resid
     */
    protected function removeUserInDB($resid){
        $socketUser = SocketUser::Where('resource_id', $resid)->first();
        if (isset($socketUser)) {
            $socketUser->delete();
        }
    }
    /** КОНЕЦ МЕТОДЫ АВТОРИЗАЦИИ */
}
