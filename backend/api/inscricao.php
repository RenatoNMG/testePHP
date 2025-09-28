<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST');

require_once __DIR__ . "/../database/database.php";
require_once __DIR__ . "/../model/Inscricoes.php";
require_once __DIR__ . "/../dao/InscricoesDAO.php";


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
    exit;
}

// Recebe os dados JSON
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['idVaga']) || empty($data['idCandidato'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

// Cria instância do modelo
$inscricao = new Inscricao(
    null,
    intval($data['idVaga']),
    intval($data['idCandidato'])
);

// Chama o DAO para salvar
$dao = new InscricaoDAO(); // já deve conter a conexão PDO
if ($dao->create($inscricao)) {
    echo json_encode(['success' => true, 'message' => 'Inscrição realizada com sucesso']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar inscrição']);
}
