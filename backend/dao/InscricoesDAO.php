<?php
require_once __DIR__ . "/../database/database.php";
require_once __DIR__ . "/../model/Inscricoes.php";

class InscricaoDAO
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // Mapear row para objeto Inscricao
    public function mapInscricaoRow(array $row): Inscricao
    {
        return new Inscricao(
            $row['id'],
            $row['vaga_id'],
            $row['candidato_id']
        );
    }

    // Criar nova inscrição
    public function create(Inscricao $inscricao): bool
    {
        $sql = "INSERT INTO inscricoes (vaga_id, candidato_id) VALUES (:vaga_id, :candidato_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':vaga_id' => $inscricao->getVagaId(),
            ':candidato_id' => $inscricao->getCandidatoId()
        ]);
    }

    // Atualizar inscrição
    public function update(Inscricao $inscricao): bool
    {
        $sql = "UPDATE inscricoes SET vaga_id = :vaga_id, candidato_id = :candidato_id WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':vaga_id' => $inscricao->getVagaId(),
            ':candidato_id' => $inscricao->getCandidatoId(),
            ':id' => $inscricao->getId()
        ]);
    }

    // Excluir inscrição
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM inscricoes WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Listar todas as inscrições
    public function getAll(): array
    {
        $stmt = $this->conn->query("SELECT * FROM inscricoes ORDER BY id ASC");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapInscricaoRow($row);
        }
        return $result;
    }

    // Buscar inscrição por ID
    public function getById(int $id): ?Inscricao
    {
        $sql = "SELECT * FROM inscricoes WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $this->mapInscricaoRow($row);
        }
        return null;
    }

    // Buscar inscrições por vaga
    public function getByVagaId(int $vaga_id): array
    {
        $sql = "SELECT * FROM inscricoes WHERE vaga_id = :vaga_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':vaga_id' => $vaga_id]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapInscricaoRow($row);
        }
        return $result;
    }

    // Buscar inscrições por candidato
    public function getByCandidatoId(int $candidato_id): array
    {
        $sql = "SELECT * FROM inscricoes WHERE candidato_id = :candidato_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':candidato_id' => $candidato_id]);
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapInscricaoRow($row);
        }
        return $result;
    }
}
