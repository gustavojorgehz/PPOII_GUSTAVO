<?php

require_once __DIR__ . '/../../../src/bootstrap.php';
require_once __DIR__ . '/../../../src/auth/auth.php';

exigirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . '/painel/projetos/index.php');
    exit;
}


/*
 * Verifica CSRF
 */
$csrfToken = $_POST['csrf_token'] ?? '';

if (!csrfTokenValido($csrfToken)) {
    http_response_code(403);
    die('Requisição inválida.');
}


/*
 * Obtém o ID do projeto
 */
$id = filter_input(
    INPUT_POST,
    'id',
    FILTER_VALIDATE_INT
);

if (!$id) {
    header('Location: ' . BASE_URL . '/painel/projetos/index.php');
    exit;
}


try {

    /*
     * Exclui o projeto
     *
     * projeto_usuario possui ON DELETE CASCADE,
     * portanto os relacionamentos também serão removidos.
     */
    $sql = "DELETE FROM projeto
            WHERE ID = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

} catch (PDOException $e) {

    http_response_code(500);

    die('Não foi possível excluir o projeto.');
}


header(
    'Location: ' .
    BASE_URL .
    '/painel/projetos/index.php'
);

exit;
