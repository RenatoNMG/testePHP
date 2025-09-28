<?php

header("Content-Type: application/json");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../database/database.php';
require_once __DIR__ . '/../model/Vagas.php';
require_once __DIR__ . '/../dao/VagasDAO.php';

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 20;
$tipo = $_GET['tipo'] ?? null;

// Normaliza o filtro de tipo
if ($tipo === "" || (is_string($tipo) && strtolower($tipo) === "todos")) {
    $tipo = null;
}

$dao = new VagaDAO();

// Pega todas as vagas da página usando funções existentes
if ($tipo && in_array($tipo, ['CLT', 'PJ', 'Freelancer'])) {
    $vagas = $dao->getPaginatedByTipo($tipo, $page, $limit);
    $total = $dao->getTotalByTipo($tipo);
} else {
    $vagas = $dao->getPaginated($page, $limit);
    $total = $dao->getTotal();
}

// Filtra apenas vagas ativas antes de enviar
$vagasAtivas = array_filter($vagas, fn($vaga) => $vaga->getStatus() === 'active');

echo json_encode([
    'success' => true,
    'page' => $page,
    'limit' => $limit,
    'total' => count($vagasAtivas),
    'pages' => ceil(count($vagasAtivas) / $limit),
    'data' => array_values($vagasAtivas) // reindexa o array
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
