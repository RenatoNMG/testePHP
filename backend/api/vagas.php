<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

require_once __DIR__ . "/../database/database.php";
require_once __DIR__ . "/../model/Vagas.php";
require_once __DIR__ . "/../dao/VagasDAO.php";

function sendResponse(int $code, bool $success, string $message, array $data = []) {
    http_response_code($code);
    echo json_encode([
        "success" => $success,
        "message" => $message,
        "data" => $data
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

//validar JSON
$input = json_decode(file_get_contents("php://input"), true);
if ($input === null && json_last_error() !== JSON_ERROR_NONE) {
    sendResponse(400, false, "JSON inválido.");
}


$tipo  = $input["tipo"]  ?? null;
$order = $input["order"] ?? "title-asc";
$limit = (int) ($input["limit"] ?? 10);
$page  = (int) ($input["page"]  ?? 1);


$limit = max(1, min($limit, 100));
$page  = max(1, $page);

$orderMap = [
    "title-asc" => "titulo ASC",
    "title-desc"=> "titulo DESC",
    "id-asc"   => "id ASC",
    "id-desc"  => "id DESC"
];
$orderBy = $orderMap[$order] ?? "titulo ASC";

try {
    $vagaDAO = new VagaDAO();

    if ($tipo) {
        $vagas = $vagaDAO->getPaginatedByTipo($tipo, $page, $limit, $orderBy) ?? [];
        $total = $vagaDAO->getTotalByTipo($tipo);
    } else {
        $vagas = $vagaDAO->getPaginated($page, $limit, $orderBy) ?? [];
        $total = $vagaDAO->getTotal();
    }

    $response = [
        "success" => true,
        "pagination" => [
            "page" => $page,
            "limit" => $limit,
            "total" => $total,
            "pages" => ceil($total / $limit)
        ],
        "data" => array_map(function($vaga) {
            return [
                "id" => $vaga->getId(),
                "titulo" => $vaga->getTitulo(),
                "descricao" => $vaga->getDescricao(),
                "tipo" => $vaga->getTipo(),
                "status" => $vaga->getStatus(),
                "criado_por" => $vaga->getCriadoPor()
            ];
        }, $vagas)
    ];

    echo json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {

    sendResponse(500, false, "Erro interno ao buscar vagas.");
}
