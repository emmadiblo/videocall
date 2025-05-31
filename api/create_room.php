<?php
// api/create_room.php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non connecté']);
    exit;
}

// Configuration de la base de données
$host = 'localhost';
$dbname = 'videocall_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de base de données']);
    exit;
}

// Générer un code de salle unique
$roomCode = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));

try {
    $stmt = $pdo->prepare("INSERT INTO rooms (code, creator_id, created_at, expires_at) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR))");
    $stmt->execute([$roomCode, $_SESSION['user_id']]);
    
    echo json_encode(['success' => true, 'roomCode' => $roomCode]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la création']);
}
?>