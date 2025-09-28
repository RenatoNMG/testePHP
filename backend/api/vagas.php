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

$dao = new VagaDAO();
$vagas = $dao->getPaginated($page, $limit);
$total = $dao->getTotal();

echo json_encode([
    'success' => true,
    'page' => $page,
    'limit' => $limit,
    'total' => $total,
    'pages' => ceil($total / $limit),
    'data' => $vagas
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
