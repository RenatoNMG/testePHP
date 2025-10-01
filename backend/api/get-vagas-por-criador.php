<?php
header("Content-Type: application/json");
header('Access-Control-Allow-Origin: http://localhost:3000');
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/../database/database.php';
require_once __DIR__ . '/../model/Vagas.php';
require_once __DIR__ . '/../dao/VagasDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Verifica o id
$idCriador = isset($_GET['id_criador']) ? (int)$_GET['id_criador'] : null;

if (!$idCriador) {
    http_response_code(400);
    echo json_encode(['error' => 'ID do criador é obrigatório.']);
    exit;
}

try {
    $dao = new VagaDAO();
    $vagas = $dao->getByCriadoPor($idCriador);

    echo json_encode([
        'success' => true,
        'total' => count($vagas),
        'data' => array_values($vagas)
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno do servidor.'
    ]);
}
