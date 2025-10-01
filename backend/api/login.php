<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
require_once __DIR__ . '/../database/database.php';
require_once __DIR__ . '/../dao/CandidatosDAO.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Método não permitido"
    ]);
    exit;
}

// Recebe os dados do POST
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'] ?? '';
$senha = $data['senha'] ?? '';


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Email inválido"
    ]);
    exit;
}

// Valida campos
if (empty($email) || empty($senha)) {
    echo json_encode([
        "success" => false,
        "message" => "Email e senha são obrigatórios"
    ]);
    exit;
}

// Verifica no banco
try {
    $CandidatosDAO = new CandidatoDAO();
    $Candidato = $CandidatosDAO->getByEmail($email);

    if (!$Candidato || !password_verify($senha, $Candidato->getSenha())) {
        http_response_code(401);
        echo json_encode([
            "success" => false,
            "message" => "Usuário ou senha incorretos"
        ]);
        exit;
    }

    $token = base64_encode($Candidato->getId() . ':' . bin2hex(random_bytes(16)));

    echo json_encode([
        "success" => true,
        "token" => $token,
        "id" => $Candidato->getId()
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erro interno do servidor"
    ]);
}
