<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/projeto.php';

class ProjetoController
{
    private Projeto $projeto;

    public function __construct(PDO $pdo)
    {
        $this->projeto = new Projeto($pdo);
    }

    public function cadastrar(array $dados): int
    {
        return $this->projeto->cadastrar($dados);
    }
}
