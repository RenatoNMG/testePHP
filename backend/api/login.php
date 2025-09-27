<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

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
$password = $data['password'] ?? '';

// Valida campos
if (empty($email) || empty($password)) {
    echo json_encode([
        "success" => false,
        "message" => "Email e senha são obrigatórios"
    ]);
    exit;
}

// Verifica no banco
$CandidatosDAO = new CandidatoDAO();
$Candidato = $CandidatosDAO->getByEmail($email);

if (!$Candidato) {
    echo json_encode([
        "success" => false,
        "message" => "Usuário ou senha incorretos"
    ]);
    exit;
}

// Verifica senha (assumindo que está armazenada com password_hash)
if (!password_verify($password, $Candidato['password'])) {
    echo json_encode([
        "success" => false,
        "message" => "Usuário ou senha incorretos"
    ]);
    exit;
}

// Se estiver correto, gera token (exemplo simples com base64)
$token = base64_encode($Candidato['id'] . ':' . bin2hex(random_bytes(16)));

echo json_encode([
    "success" => true,
    "token" => $token
]);
