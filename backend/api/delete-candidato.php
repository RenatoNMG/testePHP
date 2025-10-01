<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
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
        $dao = new CandidatoDAO();

        if ($dao->delete($id)) {
            echo json_encode([
                "status" => "sucesso",
                "mensagem" => "conta Deletada com Sucesso."
            ]);
        } else {
            echo json_encode([
                "status" => "erro",
                "mensagem" => "Conta Não encontrada."
            ]);
        }
    } else {
        echo json_encode([
            "status" => "erro",
            "mensagem" => "Conta Não encontrada."
        ]);
    }
} else {
    echo json_encode([
        "status" => "erro",
        "mensagem" => "Erro no sistima methodo invalido."
    ]);
}
