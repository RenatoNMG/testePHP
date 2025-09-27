<?php
class Candidato
{
    private ?int $id;
    private string $nome;
    private string $email;
    private string $senha;
    private ?string $token;

    public function __construct(
        ?int $id = null,
        string $nome = '',
        string $email = '',
        string $senha = '',
        ?string $token = null
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->senha = $senha;
        $this->token = $token;
    }

    // Getters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNome(): string
    {
        return $this->nome;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getSenha(): string
    {
        return $this->senha;
    }
    public function getToken(): ?string
    {
        return $this->token;
    }

    // Setters
    public function setId(?int $id): void
    {
        $this->id = $id;
    }
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setSenha(string $senha): void
    {
        $this->senha = $senha;
    }
    public function setToken(?string $token): void
    {
        $this->token = $token;
    }
}
