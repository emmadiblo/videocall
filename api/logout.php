<?php
header('Content-Type: application/json');
session_start();

// Détruire la session
session_destroy();

echo json_encode(['success' => true]);