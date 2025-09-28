<?php
class Vaga implements JsonSerializable
{
    private ?int $id;
    private string $titulo;
    private ?string $descricao;
    private string $tipo;
    private string $status;
    private ?int $criado_por;

    public function __construct(
        ?int $id = null,
        string $titulo = '',
        ?string $descricao = null,
        string $tipo = 'CLT',
        string $status = 'active',
        ?int $criado_por = null
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->tipo = $tipo;
        $this->status = $status;
        $this->criado_por = $criado_por;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitulo(): string
    {
        return $this->titulo;
    }
    public function getDescricao(): ?string
    {
        return $this->descricao;
    }
    public function getTipo(): string
    {
        return $this->tipo;
    }
    public function getStatus(): string
    {
        return $this->status;
    }
    public function getCriadoPor(): ?int
    {
        return $this->criado_por;
    }

    // Setters
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }
    public function setDescricao(?string $descricao): void
    {
        $this->descricao = $descricao;
    }
    public function setTipo(string $tipo): void
    {
        $this->tipo = $tipo;
    }
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
    public function setCriadoPor(?int $criado_por): void
    {
        $this->criado_por = $criado_por;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'tipo' => $this->tipo,
            'status' => $this->status,
            'criado_por' => $this->criado_por
        ];
    }
}
