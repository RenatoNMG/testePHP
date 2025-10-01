<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

require_once __DIR__ . "/../database/database.php";
require_once __DIR__ . "/../model/Vagas.php";
require_once __DIR__ . "/../dao/VagasDAO.php"; 

// JSON do corpo
$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    echo json_encode([
        "success" => false,
        "message" => "Nenhum dado enviado"
    ]);
    exit;
}

$tipo  = $input["tipo"]  ?? null;
$order = $input["order"] ?? "title-asc";
$limit = (int) ($input["limit"] ?? 10);
$page  = (int) ($input["page"]  ?? 1);

try {
    $vagaDAO = new VagaDAO();


    $orderBy = "titulo ASC";
    if ($order === "title-desc") {
        $orderBy = "titulo DESC";
    } elseif ($order === "id-asc") {
        $orderBy = "id ASC";
    } elseif ($order === "id-desc") {
        $orderBy = "id DESC";
    }


if ($tipo) {
    $vagas  = $vagaDAO->getPaginatedByTipo($tipo, $page, $limit, $orderBy);
    $total  = $vagaDAO->getTotalByTipo($tipo);
} else {
    $vagas  = $vagaDAO->getPaginated($page, $limit, $orderBy);
    $total  = $vagaDAO->getTotal();
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

    echo json_encode($response, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erro: " . $e->getMessage()
    ]);
}
