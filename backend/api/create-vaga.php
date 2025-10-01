<?php
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');

require_once __DIR__ . "/../model/Vagas.php";
require_once __DIR__ . "/../dao/VagasDAO.php";
require_once __DIR__ . "/../database/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);

    $titulo = $data['titulo'] ?? null;
    $descricao = $data['descricao'] ?? null;
    $tipo = $data['tipo'] ?? 'CLT';
    $status = $data['status'] ?? 'active';
    $criado_por = $data['criado_por'] ?? null;

    if (
        !$titulo || trim($titulo) === '' ||
        !$descricao || trim($descricao) === '' ||
        !in_array($tipo, ['CLT', 'PJ', 'Freelancer']) ||
        !in_array($status, ['active', 'paused', 'closed'])
    ) {
        http_response_code(400);
        echo json_encode(['error' => 'Dados inválidos. Verifique título, descrição, tipo e status.']);
        exit;
    }

    // Cria a vaga com o ID do candidato que criou
    $vaga = new Vaga(null, $titulo, $descricao, $tipo, $status, $criado_por);


    try {
        $dao = new VagaDAO();
        $success = $dao->create($vaga);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Vaga criada com sucesso.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro ao criar vaga.']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erro interno do servidor.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido. Use POST.']);
}
