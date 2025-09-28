<?php
header('Content-Type: application/json');

// Permite requisições do front-end
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
require_once __DIR__ . '/../database/database.php';
require_once __DIR__ . '/../dao/CandidatosDAO.php';
require_once __DIR__ . '/../model/Candidatos.php';

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
$nome = $data['nome'] ?? '';
$email = $data['email'] ?? '';
$senha = $data['senha'] ?? '';

// Valida campos
if (empty($nome) || empty($email) || empty($senha)) {
    echo json_encode([
        "success" => false,
        "message" => "Nome, email e senha são obrigatórios"
    ]);
    exit;
}

// Cria token aleatório
$token = base64_encode(bin2hex(random_bytes(16)));

// Criptografa a senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Cria objeto Candidato
$candidato = new Candidato();
$candidato->setNome($nome);
$candidato->setEmail($email);
$candidato->setSenha($senhaHash);
$candidato->setToken($token);

// Salva no banco
$CandidatosDAO = new CandidatoDAO();
if ($CandidatosDAO->create($candidato)) {
    echo json_encode([
        "success" => true,
        "token" => $token
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Erro ao cadastrar. Email já existe?"
    ]);
}
