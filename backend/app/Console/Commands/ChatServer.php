<?php

namespace App\Console\Commands;

use App\sockets\ChatSocket;
use Illuminate\Console\Command;

//Для запуска сервера
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class ChatServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sockets:serve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sockets:serve - запуск сервера';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('     Сервер запускается...');
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new ChatSocket()
                )
            ),
            6001
        );
        $server->run();
    }
}
