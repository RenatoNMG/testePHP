<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');

// Responde a preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../database/database.php';
require_once __DIR__ . '/../dao/VagasDAO.php';
require_once __DIR__ . '/../model/Vagas.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Método não permitido. Use POST."
    ]);
    exit;
}

// Recebe o JSON do corpo da requisição
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;

// Valida o ID
if (!$id || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "ID invalido."
    ]);
    exit;
}

try {
    $dao = new VagaDAO();
    $deleted = $dao->delete($id);

    if ($deleted) {
        echo json_encode([
            "success" => true,
            "message" => "Vaga deletada com sucesso."
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            "success" => false,
            "message" => "Vaga não encontrada."
        ]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erro interno do servidor."
    ]);
}

