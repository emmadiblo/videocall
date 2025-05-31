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

// Trouver l'ID de la salle
$stmt = $pdo->prepare('SELECT id FROM rooms WHERE code = ?');
$stmt->execute([$roomCode]);
$room = $stmt->fetch();

if (!$room) {
    echo json_encode(['success' => false, 'message' => 'Salle introuvable']);
    exit;
}

// Supprimer l'utilisateur des participants
try {
    $stmt = $pdo->prepare('DELETE FROM room_participants WHERE room_id = ? AND user_id = ?');
    $stmt->execute([$room['id'], $_SESSION['user_id']]);
    
    // Vérifier s'il reste des participants
    $stmt = $pdo->prepare('SELECT COUNT(*) as count FROM room_participants WHERE room_id = ?');
    $stmt->execute([$room['id']]);
    $count = $stmt->fetch()['count'];
    
    // Fermer la salle si plus personne
    if ($count == 0) {
        $stmt = $pdo->prepare('UPDATE rooms SET status = "closed" WHERE id = ?');
        $stmt->execute([$room['id']]);
    }
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
}