<?php
header('Content-Type: application/json'); // importante
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . "/../database/database.php";
require_once __DIR__ . "/../model/Inscricoes.php";
require_once __DIR__ . "/../dao/InscricoesDAO.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
    exit;
}

// Recebe os dados JSON
$data = json_decode(file_get_contents('php://input'), true);

$idVaga = $data['idVaga'] ?? null;
$idCandidato = $data['idCandidato'] ?? null;

if (!$idVaga || !$idCandidato) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

try {
    $dao = new InscricaoDAO();

    if ($dao->getByCandidatoEVaga($idCandidato, $idVaga)) {
        echo json_encode(['success' => false, 'message' => 'Você já está inscrito nessa vaga']);
        exit;
    }

    $inscricao = new Inscricao(null, $idVaga, $idCandidato);

    if ($dao->create($inscricao)) {
        echo json_encode(['success' => true, 'message' => 'Inscrição realizada com sucesso']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar inscrição']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor']);
}
