<?php

declare(strict_types=1);

class Projeto
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function cadastrar(array $dados): int
    {
        $sql = "INSERT INTO projeto (
                    titulo,
                    descricao,
                    objetivo,
                    curso_id,
                    area_id,
                    periodo,
                    status,
                    orientador_id,
                    link_projeto
                )
                VALUES (
                    :titulo,
                    :descricao,
                    :objetivo,
                    :curso_id,
                    :area_id,
                    :periodo,
                    :status,
                    :orientador_id,
                    :link_projeto
                )";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'],
            ':objetivo' => $dados['objetivo'],
            ':curso_id' => $dados['curso_id'],
            ':area_id' => $dados['area_id'],
            ':periodo' => $dados['periodo'],
            ':status' => $dados['status'],
            ':orientador_id' => $dados['orientador_id'],
            ':link_projeto' => $dados['link_projeto']
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
