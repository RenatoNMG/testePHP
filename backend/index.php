<?php
require_once __DIR__ . "/model/Candidatos.php";
require_once __DIR__ . "/dao/CandidatosDAO.php";
require_once __DIR__ . "/model/Vagas.php";
require_once __DIR__ . "/dao/VagasDAO.php";
require_once __DIR__ . "/model/Inscricoes.php";
require_once __DIR__ . "/dao/InscricoesDAO.php";
require_once __DIR__ . "/database/database.php";

function printTitle(string $title) {
    echo "<br>====================<br>";
    echo "$title<br>";
    echo "====================<br><br>";
}

function testCandidatoDAO() {
    printTitle("Teste CandidatoDAO");

    $dao = new CandidatoDAO();

    // Criar
    $c = new Candidato(null, "Teste Usuario", "teste@example.com", password_hash("123456", PASSWORD_DEFAULT));
    $dao->create($c);
    echo "[CRIAR] Candidato criado.<br><br>";

    // Listar
    echo "[LISTAR] Lista de candidatos:<br>";
    $candidatos = $dao->getAll();
    foreach ($candidatos as $c) {
        echo "ID: {$c->getId()} | Nome: {$c->getNome()} | Email: {$c->getEmail()}<br>";
    }
    echo "<br>";

    // Atualizar
    $candidatos[0]->setNome("Nome Atualizado");
    $dao->update($candidatos[0]);
    echo "[ATUALIZAR] Candidato atualizado.<br><br>";

    // Buscar por ID
    $c = $dao->getById($candidatos[0]->getId());
    echo "[BUSCAR ID] ID {$c->getId()} | Nome: {$c->getNome()} | Email: {$c->getEmail()}<br><br>";

    // Excluir
    $dao->delete($candidatos[0]->getId());
    echo "[EXCLUIR] Candidato excluído.<br><br>";
}

function testVagaDAO() {
    printTitle("Teste VagaDAO");

    $dao = new VagaDAO();

    // Criar
    $v = new Vaga(null, "Desenvolvedor PHP", "Desenvolvimento de API em PHP", "CLT");
    $dao->create($v);
    echo "[CRIAR] Vaga criada.<br><br>";

    // Listar
    echo "[LISTAR] Lista de vagas:<br>";
    $vagas = $dao->getAll();
    foreach ($vagas as $v) {
        echo "ID: {$v->getId()} | Título: {$v->getTitulo()} | Tipo: {$v->getTipo()} | Status: {$v->getStatus()}<br>";
    }
    echo "<br>";

    // Atualizar
    $vagas[0]->setTitulo("Desenvolvedor PHP Atualizado");
    $dao->update($vagas[0]);
    echo "[ATUALIZAR] Vaga atualizada.<br><br>";

    // Buscar por ID
    $v = $dao->getById($vagas[0]->getId());
    echo "[BUSCAR ID] ID {$v->getId()} | Título: {$v->getTitulo()} | Tipo: {$v->getTipo()} | Status: {$v->getStatus()}<br><br>";

    // Excluir
    $dao->delete($vagas[0]->getId());
    echo "[EXCLUIR] Vaga excluída.<br><br>";
}

function testInscricaoDAO() {
    printTitle("Teste InscricaoDAO");

    $cDAO = new CandidatoDAO();
    $vDAO = new VagaDAO();
    $iDAO = new InscricaoDAO();

    // Criar candidato e vaga para teste
    $c = new Candidato(null, "Candidato Inscricao", "inscricao@example.com", password_hash("123", PASSWORD_DEFAULT));
    $v = new Vaga(null, "Vaga Inscricao", "Descrição da vaga", "CLT");
    $cDAO->create($c);
    $vDAO->create($v);

    $candidatos = $cDAO->getAll();
    $vagas = $vDAO->getAll();

    $inscricao = new Inscricao(null, $vagas[0]->getId(), $candidatos[0]->getId());
    $iDAO->create($inscricao);
    echo "[CRIAR] Inscrição criada.<br><br>";

    // Listar
    echo "[LISTAR] Lista de inscrições:<br>";
    $inscricoes = $iDAO->getAll();
    foreach ($inscricoes as $i) {
        echo "ID: {$i->getId()} | Vaga ID: {$i->getVagaId()} | Candidato ID: {$i->getCandidatoId()}<br>";
    }
    echo "<br>";

    // Atualizar (trocar vaga ou candidato)
    $inscricoes[0]->setVagaId($vagas[0]->getId());
    $iDAO->update($inscricoes[0]);
    echo "[ATUALIZAR] Inscrição atualizada.<br><br>";

    // Buscar por ID
    $i = $iDAO->getById($inscricoes[0]->getId());
    echo "[BUSCAR ID] ID {$i->getId()} | Vaga ID: {$i->getVagaId()} | Candidato ID: {$i->getCandidatoId()}<br><br>";

    // Excluir
    $iDAO->delete($inscricoes[0]->getId());
    echo "[EXCLUIR] Inscrição excluída.<br><br>";

    // Limpar dados de teste
    $cDAO->delete($candidatos[0]->getId());
    $vDAO->delete($vagas[0]->getId());
}

// Rodar todos os testes
testCandidatoDAO();
testVagaDAO();
testInscricaoDAO();

echo "=== FIM DOS TESTES ===<br><br>";
