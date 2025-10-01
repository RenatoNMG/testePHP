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
            $row['status'],
            $row['criado_por'] ?? null // adiciona o ID do criador
        );
    }


    // buscar vagas por criador, e candidatos
    public function getByCriadoPor(int $candidatoId): array
    {
        $stmt = $this->conn->prepare("
        SELECT 
            vagas.id AS vaga_id,
            vagas.titulo,
            vagas.descricao,
            vagas.tipo,
            vagas.status,
            vagas.criado_por,
            inscricoes.id AS inscricao_id,
            candidatos.id AS candidato_id,
            candidatos.nome AS candidato_nome,
            candidatos.email AS candidato_email
        FROM vagas
        LEFT JOIN inscricoes ON vagas.id = inscricoes.vaga_id
        LEFT JOIN candidatos ON inscricoes.candidato_id = candidatos.id
        WHERE vagas.criado_por = :candidatoId
    ");
        $stmt->bindValue(':candidatoId', $candidatoId, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $vagaId = $row['vaga_id'];

            if (!isset($result[$vagaId])) {
                $result[$vagaId] = [
                    'id' => $row['vaga_id'],
                    'titulo' => $row['titulo'],
                    'descricao' => $row['descricao'],
                    'tipo' => $row['tipo'],
                    'status' => $row['status'],
                    'criado_por' => $row['criado_por'],
                    'inscricoes' => []
                ];
            }


            if ($row['inscricao_id']) {
                $result[$vagaId]['inscricoes'][] = [
                    'id' => $row['inscricao_id'],
                    'candidato_id' => $row['candidato_id'],
                    'nome' => $row['candidato_nome'],
                    'email' => $row['candidato_email']
                ];
            }
        }

        // Retorna array reindexado
        return array_values($result);
    }


    // Criar nova vaga
    public function create(Vaga $vaga): bool
    {
        $sql = "INSERT INTO vagas (titulo, descricao, tipo, status, criado_por) 
                VALUES (:titulo, :descricao, :tipo, :status, :criado_por)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':titulo' => $vaga->getTitulo(),
            ':descricao' => $vaga->getDescricao(),
            ':tipo' => $vaga->getTipo(),
            ':status' => $vaga->getStatus(),
            ':criado_por' => $vaga->getCriadoPor() // salva o ID do candidato
        ]);
    }

    // Atualizar vaga existente
    public function update(Vaga $vaga): bool
    {
        $sql = "UPDATE vagas 
                SET titulo = :titulo, descricao = :descricao, tipo = :tipo, status = :status, criado_por = :criado_por
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':titulo' => $vaga->getTitulo(),
            ':descricao' => $vaga->getDescricao(),
            ':tipo' => $vaga->getTipo(),
            ':status' => $vaga->getStatus(),
            ':criado_por' => $vaga->getCriadoPor(),
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

    // paginas por tipo
    public function getPaginated(int $page = 1, int $limit = 20, string $orderBy = "titulo ASC"): array
    {
        $offset = ($page - 1) * $limit;
        $stmt = $this->conn->prepare("SELECT * FROM vagas ORDER BY $orderBy LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];
        

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['status'] === "active") {
                $result[] = $this->mapVagaRow($row);
            }
        }
        return $result;
    }


    // pegar total
    public function getTotal(): int
    {
        $stmt = $this->conn->query("SELECT COUNT(*) as total FROM vagas");
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    // paginação por tipo
    public function getPaginatedByTipo(string $tipo, int $page, int $limit, string $orderBy = "titulo ASC"): array
    {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM vagas WHERE tipo = :tipo ORDER BY $orderBy LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $result = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['status'] === "active") {
                $result[] = $this->mapVagaRow($row);
            }
        }
        return $result;
    }


    // pegar o total de pagina
    public function getTotalByTipo(string $tipo): int
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM vagas WHERE tipo = :tipo");
        $stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }
}
