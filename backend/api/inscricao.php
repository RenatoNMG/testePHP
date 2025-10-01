<?php
// Permitir qualquer origem
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}



require_once __DIR__ . "/../database/database.php";
require_once __DIR__ . "/../model/Inscricoes.php";
require_once __DIR__ . "/../dao/InscricoesDAO.php";


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método inválido']);
    exit;
}

// Recebe os dados JSON
$data = json_decode(file_get_contents('php://input'), true);

if (empty($data['idVaga']) || empty($data['idCandidato'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

$dao = new InscricaoDAO(); 

// veirifcação se o usuario ja é incrito
if ($dao->getByCandidatoEVaga($data['idCandidato'], $data['idVaga'])) {
    echo json_encode(['success'=> false,'message'=> 'Voce jà é incrito nessa vaga']);
    exit;
}


// Cria instância do modelo
$inscricao = new Inscricao(
    null,
    intval($data['idVaga']),
    intval($data['idCandidato'])
);

// Chama o DAO para salvar

if ($dao->create($inscricao)) {
    echo json_encode(['success' => true, 'message' => 'Inscrição realizada com sucesso']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar inscrição']);
}
