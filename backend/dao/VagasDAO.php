<?php
require_once __DIR__ . "/../model/Vagas.php";
require_once __DIR__ . "/../database/database.php";

class VagaDAO
{
      private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }
    
    private function mapVagaRow(array $row): Vaga
    {
        return new Vaga(
            $row['id'],
            $row['titulo'],
            $row['descricao'],
            $row['tipo'],
            $row['status']
        );
    }

    // Criar nova vaga
    public function create(Vaga $vaga): bool
    {
        $sql = "INSERT INTO vagas (titulo, descricao, tipo, status) VALUES (:titulo, :descricao, :tipo, :status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':titulo' => $vaga->getTitulo(),
            ':descricao' => $vaga->getDescricao(),
            ':tipo' => $vaga->getTipo(),
            ':status' => $vaga->getStatus()
        ]);
    }

    // Atualizar vaga existente
    public function update(Vaga $vaga): bool
    {
        $sql = "UPDATE vagas SET titulo = :titulo, descricao = :descricao, tipo = :tipo, status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':titulo' => $vaga->getTitulo(),
            ':descricao' => $vaga->getDescricao(),
            ':tipo' => $vaga->getTipo(),
            ':status' => $vaga->getStatus(),
            ':id' => $vaga->getId()
        ]);
    }

    // Excluir vaga
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM vagas WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Listar todas as vagas
    public function getAll(): array
    {
        $stmt = $this->conn->query("SELECT * FROM vagas ORDER BY titulo ASC");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapVagaRow($row);
        }
        return $result;
    }

    // Buscar vaga por ID
    public function getById(int $id): ?Vaga
    {
        $stmt = $this->conn->prepare("SELECT * FROM vagas WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapVagaRow($row) : null;
    }
}
