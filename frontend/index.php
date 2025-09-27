<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="auth/cadastro.html">Cadastrar</a>

    
</body>
</html>

<?php

$url = "http://localhost:8080/backend/api/login.php"; // ajuste o caminho real
$data = [
    "email" => "login_teste@example.com",
    "senha" => "senha123"
];

$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n",
        "method"  => "POST",
        "content" => json_encode($data)
    ]
];

$context  = stream_context_create($options);
$result = @file_get_contents($url, false, $context);

if ($result === FALSE) {
    echo "Erro ao acessar API.<br>";
} else {
    $response = json_decode($result, true);
    if (is_array($response) && isset($response['success'])) {
        if ($response['success']) {
            echo "[LOGIN] Sucesso! Token: {$response['token']} | Nome: {$response['nome']}<br><br>";
        } else {
            echo "[LOGIN] Falha: {$response['message']}<br><br>";
        }
    } else {
        echo "[LOGIN] Resposta inválida da API.<br><br>";
    }
}
