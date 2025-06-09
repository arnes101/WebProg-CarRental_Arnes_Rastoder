<?php
require __DIR__ . '/vendor/autoload.php';


use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;
    protected $nicknames;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->nicknames = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $this->nicknames[$conn->resourceId] = "Anonymous";

        echo "New connection! ({$conn->resourceId})\n";
        $this->sendUserCount();
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        if (isset($data['type']) && $data['type'] === 'set_nickname') {
            $this->nicknames[$from->resourceId] = htmlspecialchars($data['nickname']);
            return;
        }

        $nickname = $this->nicknames[$from->resourceId] ?? 'Anonymous';
        $outMsg = [
            'nickname' => $nickname,
            'message' => htmlspecialchars($data['message']),
            'timestamp' => date('H:i')
        ];

        foreach ($this->clients as $client) {
            $client->send(json_encode($outMsg));
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        unset($this->nicknames[$conn->resourceId]);
        echo "Connection {$conn->resourceId} has disconnected\n";
        $this->sendUserCount();
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    private function sendUserCount() {
        $countMsg = [
            'type' => 'user_count',
            'count' => count($this->clients)
        ];
        foreach ($this->clients as $client) {
            $client->send(json_encode($countMsg));
        }
    }
}

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(new WsServer(new Chat())),
    8080
);

echo "WebSocket server started on port 8080\n";
$server->run();
