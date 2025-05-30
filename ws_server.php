<?php
// Serveur WebSocket simple avec Ratchet
require_once __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\WebSocket\WsServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;

class SignalingServer implements MessageComponentInterface {
    protected $clients;
    protected $rooms;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->rooms = [];
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "Nouvelle connexion! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        
        if (isset($data['roomCode'])) {
            $roomCode = $data['roomCode'];
            
            // Ajouter le client à la salle
            if (!isset($this->rooms[$roomCode])) {
                $this->rooms[$roomCode] = [];
            }
            $this->rooms[$roomCode][] = $from;
            
            // Diffuser le message aux autres clients de la salle
            foreach ($this->rooms[$roomCode] as $client) {
                if ($client !== $from) {
                    $client->send($msg);
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        
        // Retirer de toutes les salles
        foreach ($this->rooms as $roomCode => $clients) {
            $key = array_search($conn, $clients);
            if ($key !== false) {
                unset($this->rooms[$roomCode][$key]);
                if (empty($this->rooms[$roomCode])) {
                    unset($this->rooms[$roomCode]);
                }
            }
        }
        
        echo "Connexion {$conn->resourceId} fermée\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Erreur: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new SignalingServer()
        )
    ),
    8080
);

$server->run();