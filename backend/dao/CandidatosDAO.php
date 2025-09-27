<?php
require_once __DIR__ . "/../database/database.php";

class CandidatoDAO
{

    private $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }


    public function mapCandidatoRow(array $row): Candidato
    {
        return new Candidato(
            $row['id'],
            $row['nome'],
            $row['email'],
            $row['senha'],
            $row['token']
        );
    }


    // Criar novo candidato
    public function create(Candidato $candidato): bool
    {
        $sql = "INSERT INTO candidatos (nome, email, senha, token) VALUES (:nome, :email, :senha, :token)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $candidato->getNome(),
            ':email' => $candidato->getEmail(),
            ':senha' => $candidato->getSenha(),
            ':token' => $candidato->getToken()
        ]);
    }

    // Atualizar candidato
    public function update(Candidato $candidato): bool
    {
        $sql = "UPDATE candidatos SET nome = :nome, email = :email, senha = :senha, token = :token WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nome' => $candidato->getNome(),
            ':email' => $candidato->getEmail(),
            ':senha' => $candidato->getSenha(),
            ':token' => $candidato->getToken(),
            ':id' => $candidato->getId()
        ]);
    }

    // Excluir candidato
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM candidatos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // Listar todos os candidatos
    public function getAll(): array
    {
        $stmt = $this->conn->query("SELECT * FROM candidatos ORDER BY nome ASC");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = $this->mapCandidatoRow($row);
        }
        return $result;
    }

    // Buscar por ID
    public function getById(int $id): ?Candidato
    {
        $sql = "SELECT * FROM candidatos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return $this->mapCandidatoRow($row);
        }
        return null;
    }

    public function getByEmail(string $email): ?Candidato{
        $sql = 'SELECT * FROM candidatos WHERE email = :email';
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'email'=> $email,
            ]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $this->mapCandidatoRow($row);
            }
            return null;
    }
}
