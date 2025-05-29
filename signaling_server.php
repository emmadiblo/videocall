<?php
// signaling_server.php - Serveur de signalisation WebSocket avec ReactPHP

require_once 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\App;
use Ratchet\RFC6455\Messaging\MessageInterface;

class SignalingServer implements MessageComponentInterface {
    protected $clients;
    protected $rooms;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->rooms = [];
        echo "Serveur de signalisation démarré\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        $conn->roomId = null;
        $conn->userName = null;
        
        echo "Nouvelle connexion: {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        
        if (!$data || !isset($data['type'])) {
            return;
        }

        switch ($data['type']) {
            case 'join-room':
                $this->handleJoinRoom($from, $data);
                break;
                
            case 'offer':
                $this->handleOffer($from, $data);
                break;
                
            case 'answer':
                $this->handleAnswer($from, $data);
                break;
                
            case 'ice-candidate':
                $this->handleIceCandidate($from, $data);
                break;
                
            case 'leave-room':
                $this->handleLeaveRoom($from, $data);
                break;
        }
    }

    private function handleJoinRoom(ConnectionInterface $conn, $data) {
        $roomId = $data['roomId'] ?? '';
        $userName = $data['userName'] ?? '';
        
        if (empty($roomId) || empty($userName)) {
            $this->sendError($conn, 'Nom de salle et nom d\'utilisateur requis');
            return;
        }

        // Quitter la salle précédente si nécessaire
        if ($conn->roomId) {
            $this->leaveRoom($conn);
        }

        // Rejoindre la nouvelle salle
        $conn->roomId = $roomId;
        $conn->userName = $userName;

        if (!isset($this->rooms[$roomId])) {
            $this->rooms[$roomId] = [];
        }

        $this->rooms[$roomId][$conn->resourceId] = $conn;

        // Informer les autres participants
        $this->broadcastToRoom($roomId, [
            'type' => 'user-joined',
            'userName' => $userName
        ], $conn);

        // Confirmer la connexion
        $this->sendMessage($conn, [
            'type' => 'joined-room',
            'roomId' => $roomId,
            'participants' => $this->getRoomParticipants($roomId)
        ]);

        echo "Utilisateur {$userName} a rejoint la salle {$roomId}\n";
    }

    private function handleOffer(ConnectionInterface $from, $data) {
        $this->broadcastToRoom($from->roomId, [
            'type' => 'offer',
            'offer' => $data['offer'],
            'from' => $from->userName
        ], $from);
    }

    private function handleAnswer(ConnectionInterface $from, $data) {
        $this->broadcastToRoom($from->roomId, [
            'type' => 'answer',
            'answer' => $data['answer'],
            'from' => $from->userName
        ], $from);
    }

    private function handleIceCandidate(ConnectionInterface $from, $data) {
        $this->broadcastToRoom($from->roomId, [
            'type' => 'ice-candidate',
            'candidate' => $data['candidate'],
            'from' => $from->userName
        ], $from);
    }

    private function handleLeaveRoom(ConnectionInterface $conn, $data) {
        $this->leaveRoom($conn);
    }

    private function leaveRoom(ConnectionInterface $conn) {
        if ($conn->roomId && isset($this->rooms[$conn->roomId])) {
            unset($this->rooms[$conn->roomId][$conn->resourceId]);
            
            // Informer les autres participants
            $this->broadcastToRoom($conn->roomId, [
                'type' => 'user-left',
                'userName' => $conn->userName
            ]);

            // Nettoyer les salles vides
            if (empty($this->rooms[$conn->roomId])) {
                unset($this->rooms[$conn->roomId]);
            }

            echo "Utilisateur {$conn->userName} a quitté la salle {$conn->roomId}\n";
            
            $conn->roomId = null;
            $conn->userName = null;
        }
    }

    private function broadcastToRoom($roomId, $message, ConnectionInterface $except = null) {
        if (!isset($this->rooms[$roomId])) {
            return;
        }

        foreach ($this->rooms[$roomId] as $client) {
            if ($client !== $except) {
                $this->sendMessage($client, $message);
            }
        }
    }

    private function sendMessage(ConnectionInterface $conn, $message) {
        $conn->send(json_encode($message));
    }

    private function sendError(ConnectionInterface $conn, $error) {
        $this->sendMessage($conn, [
            'type' => 'error',
            'message' => $error
        ]);
    }

    private function getRoomParticipants($roomId) {
        if (!isset($this->rooms[$roomId])) {
            return [];
        }

        $participants = [];
        foreach ($this->rooms[$roomId] as $client) {
            if ($client->userName) {
                $participants[] = $client->userName;
            }
        }
        return $participants;
    }

    public function onClose(ConnectionInterface $conn) {
        $this->leaveRoom($conn);
        $this->clients->detach($conn);
        echo "Connexion fermée: {$conn->resourceId}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Erreur: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Configuration et démarrage du serveur
$app = new App('localhost', 8080);
$app->route('/signaling', new SignalingServer, ['*']);
$app->run();