<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Methods: POST, OPTIONS');


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../dao/CandidatosDAO.php';

$id = null;

$rawData = file_get_contents("php://input");
if (!empty($rawData)) {
    $data = json_decode($rawData, true);
    if (isset($data['id_candidato'])) {
        $id = (int)$data['id_candidato'];
    }
}

// Se for form-data ou x-www-form-urlencoded
if (isset($_POST['id_candidato'])) {
    $id = (int)$_POST['id_candidato'];
}

// Agora tenta apagar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($id !== null) {
        try {
            $dao = new CandidatoDAO();
            if ($dao->delete($id)) {
                echo json_encode(["success" => true, "message" => "Conta deletada com sucesso."]);
            } else {
                echo json_encode(["success" => false, "message" => "Conta não encontrada."]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Erro interno do servidor."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Conta não encontrada."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método inválido. Use POST."]);
}

