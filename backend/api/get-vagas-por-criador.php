<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

require_once __DIR__ . '/../database/database.php';
require_once __DIR__ . '/../model/Vagas.php';
require_once __DIR__ . '/../dao/VagasDAO.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Verifica se o id_criador foi passado na query string
$idCriador = isset($_GET['id_criador']) ? (int)$_GET['id_criador'] : null;

if (!$idCriador) {
    http_response_code(400);
    echo json_encode(['error' => 'ID do criador é obrigatório.']);
    exit;
}

$dao = new VagaDAO();
$vagas = $dao->getByCriadoPor($idCriador);

// Filtra apenas vagas ativas
$vagasAtivas = array_filter($vagas, fn($vaga) => $vaga->getStatus() === 'active');

echo json_encode([
    'success' => true,
    'total' => count($vagasAtivas),
    'data' => array_values($vagasAtivas)
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
