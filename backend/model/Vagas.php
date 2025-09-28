<?php
class Vaga implements JsonSerializable
{
    private ?int $id;
    private string $titulo;
    private ?string $descricao;
    private string $tipo;
    private string $status;

    public function __construct(
        ?int $id = null,
        string $titulo = '',
        ?string $descricao = null,
        string $tipo = 'CLT',
        string $status = 'active'
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->tipo = $tipo;
        $this->status = $status;
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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'descricao' => $this->descricao,
            'tipo' => $this->tipo,
            'status' => $this->status
        ];
    }
}
