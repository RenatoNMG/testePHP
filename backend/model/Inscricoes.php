<?php
class Inscricao
{
    private ?int $id;
    private int $vaga_id;
    private int $candidato_id;

    public function __construct(
        ?int $id = null,
        int $vaga_id = 0,
        int $candidato_id = 0
    ) {
        $this->id = $id;
        $this->vaga_id = $vaga_id;
        $this->candidato_id = $candidato_id;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getVagaId(): int
    {
        return $this->vaga_id;
    }
    public function getCandidatoId(): int
    {
        return $this->candidato_id;
    }

    // Setters
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function setVagaId(int $vaga_id): void
    {
        $this->vaga_id = $vaga_id;
    }
    public function setCandidatoId(int $candidato_id): void
    {
        $this->candidato_id = $candidato_id;
    }
}
