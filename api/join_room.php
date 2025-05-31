<?php
header('Content-Type: application/json');

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Non autorisé']);
    exit;
}

// Récupérer les données JSON
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['roomCode'])) {
    echo json_encode(['success' => false, 'message' => 'Code de salle manquant']);
    exit;
}

$roomCode = trim($input['roomCode']);

require_once '../db_connect.php';

// Vérifier si la salle existe et est active
$stmt = $pdo->prepare('SELECT id FROM rooms WHERE code = ? AND status = "active" AND expires_at > NOW()');
$stmt->execute([$roomCode]);
$room = $stmt->fetch();

if (!$room) {
    echo json_encode(['success' => false, 'message' => 'Salle introuvable ou expirée']);
    exit;
}

// Ajouter l'utilisateur comme participant s'il ne l'est pas déjà
try {
    $stmt = $pdo->prepare('INSERT IGNORE INTO room_participants (room_id, user_id) VALUES (?, ?)');
    $stmt->execute([$room['id'], $_SESSION['user_id']]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la connexion à la salle: ' . $e->getMessage()]);
}