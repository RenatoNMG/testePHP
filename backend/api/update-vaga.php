<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');

// Responde a preflight (CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Permite apenas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Método não permitido. Use POST."
    ]);
    exit;
}

require_once __DIR__ . '/../database/database.php';
require_once __DIR__ . '/../dao/VagasDAO.php';
require_once __DIR__ . '/../model/Vagas.php';

// Recebe o JSON do corpo da requisição
$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? null;
$titulo = $data['titulo'] ?? '';
$descricao = $data['descricao'] ?? '';
$tipo = $data['tipo'] ?? '';
$status = $data['status'] ?? '';
$criado_por = $data['criado_por'] ?? null;

// Valida campos obrigatórios
if (!$id || !is_numeric($id) || !$titulo || !$descricao || !$tipo || !$status || !$criado_por) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Dados inválidos ou incompletos."
    ]);
    exit;
}

// Cria objeto Vaga
$vaga = new Vaga($id, $titulo, $descricao, $tipo, $status, $criado_por);
// Atualiza no banco
$dao = new VagaDAO();
$updated = $dao->update($vaga);

if ($updated) {
    echo json_encode([
        "success" => true,
        "message" => "Vaga atualizada com sucesso."
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erro ao atualizar vaga."
    ]);
}
