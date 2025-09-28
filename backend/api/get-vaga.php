<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: GET, OPTIONS');

// Responde a preflight (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../database/database.php';
require_once __DIR__ . '/../dao/VagasDAO.php';
require_once __DIR__ . '/../model/Vagas.php';

// Verifica se o ID foi passado
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "ID inválido ou não informado."
    ]);
    exit;
}

$dao = new VagaDAO();
$vaga = $dao->getById((int)$id); // Função que você deve ter no DAO

if ($vaga) {
    echo json_encode([
        "success" => true,
        "data" => [
            "id" => $vaga->getId(),
            "titulo" => $vaga->getTitulo(),
            "descricao" => $vaga->getDescricao(),
            "tipo" => $vaga->getTipo(),
            "status" => $vaga->getStatus(),
            "criado_por" => $vaga->getCriadoPor()
        ]
    ]);
} else {
    http_response_code(404);
    echo json_encode([
        "success" => false,
        "message" => "Vaga não encontrada."
    ]);
}
