<?php

require_once __DIR__ . '/../../../src/bootstrap.php';
require_once __DIR__ . '/../../../src/auth/auth.php';

exigirLogin();

$pageTitle = APP_NAME . " | Projeto";

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: ' . BASE_URL . '/painel/projetos/index.php');
    exit;
}


/*
 * Busca o projeto
 */
$sql = "SELECT
            p.ID,
            p.titulo,
            p.descricao,
            p.objetivo,
            p.periodo,
            p.status,
            p.link_projeto,
            p.data_cadastro,
            p.data_atualizacao,
            c.nome AS curso,
            a.nome AS area,
            u.nome_completo AS orientador
        FROM projeto p

        INNER JOIN curso c
            ON c.ID = p.curso_id

        INNER JOIN area a
            ON a.ID = p.area_id

        INNER JOIN user u
            ON u.ID = p.orientador_id

        WHERE p.ID = :id

        LIMIT 1";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id' => $id
]);

$projeto = $stmt->fetch();


/*
 * Projeto não encontrado
 */
if (!$projeto) {

    http_response_code(404);

    die('Projeto não encontrado.');
}


require_once __DIR__ . '/../../../src/includes/head.php';

?>

<div class="container py-5">

    <!-- Cabeçalho -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1>
                <i class="bi bi-folder2-open"></i>

                <?= htmlspecialchars($projeto['titulo']) ?>
            </h1>

            <p class="text-muted mb-0">
                Detalhes do projeto aplicado
            </p>

        </div>

        <a
            href="<?= BASE_URL ?>/painel/projetos/index.php"
            class="btn btn-secondary"
        >
            <i class="bi bi-arrow-left"></i>

            Voltar
        </a>

    </div>


    <!-- Informações principais -->

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row g-4">

                <!-- Curso -->

                <div class="col-md-4">

                    <div class="text-muted">
                        Curso
                    </div>

                    <strong>
                        <?= htmlspecialchars($projeto['curso']) ?>
                    </strong>

                </div>


                <!-- Área -->

                <div class="col-md-4">

                    <div class="text-muted">
                        Área
                    </div>

                    <strong>
                        <?= htmlspecialchars($projeto['area']) ?>
                    </strong>

                </div>


                <!-- Período -->

                <div class="col-md-4">

                    <div class="text-muted">
                        Período
                    </div>

                    <strong>
                        <?= htmlspecialchars($projeto['periodo'] ?: '-') ?>
                    </strong>

                </div>


                <!-- Orientador -->

                <div class="col-md-4">

                    <div class="text-muted">
                        Orientador
                    </div>

                    <strong>
                        <?= htmlspecialchars($projeto['orientador']) ?>
                    </strong>

                </div>


                <!-- Status -->

                <div class="col-md-4">

                    <div class="text-muted">
                        Status
                    </div>

                    <span class="badge text-bg-secondary">

                        <?= htmlspecialchars($projeto['status']) ?>

                    </span>

                </div>


                <!-- Cadastro -->

                <div class="col-md-4">

                    <div class="text-muted">
                        Data de cadastro
                    </div>

                    <strong>

                        <?= date(
                            'd/m/Y H:i',
                            strtotime($projeto['data_cadastro'])
                        ) ?>

                    </strong>

                </div>

            </div>

        </div>

    </div>


    <!-- Descrição -->

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <h3>
                <i class="bi bi-card-text"></i>
                Descrição
            </h3>

            <p class="mt-3 mb-0">

                <?= nl2br(
                    htmlspecialchars($projeto['descricao'])
                ) ?>

            </p>

        </div>

    </div>


    <!-- Objetivo -->

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <h3>
                <i class="bi bi-bullseye"></i>
                Objetivo
            </h3>

            <?php if (!empty($projeto['objetivo'])): ?>

                <p class="mt-3 mb-0">

                    <?= nl2br(
                        htmlspecialchars($projeto['objetivo'])
                    ) ?>

                </p>

            <?php else: ?>

                <p class="text-muted mt-3 mb-0">
                    Nenhum objetivo informado.
                </p>

            <?php endif; ?>

        </div>

    </div>


    <!-- Link -->

    <?php if (!empty($projeto['link_projeto'])): ?>

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <h3>
                    <i class="bi bi-link-45deg"></i>
                    Projeto
                </h3>

                <a
                    href="<?= htmlspecialchars($projeto['link_projeto']) ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="btn btn-primary mt-2"
                >
                    <i class="bi bi-box-arrow-up-right"></i>

                    Acessar projeto
                </a>

            </div>

        </div>

    <?php endif; ?>


    <!-- Ações -->

    <div class="d-flex gap-2">

    <a
        href="<?= BASE_URL ?>/painel/projetos/editar.php?id=<?= $projeto['ID'] ?>"
        class="btn btn-warning"
    >
        <i class="bi bi-pencil"></i>
        Editar
    </a>


    <form
        method="POST"
        action="<?= BASE_URL ?>/painel/projetos/excluir.php"
        onsubmit="return confirm('Tem certeza que deseja excluir este projeto?');"
    >

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
        >

        <input
            type="hidden"
            name="id"
            value="<?= $projeto['ID'] ?>"
        >

        <button
            type="submit"
            class="btn btn-danger"
        >
            <i class="bi bi-trash"></i>
            Excluir
        </button>

    </form>


    <a
        href="<?= BASE_URL ?>/painel/projetos/index.php"
        class="btn btn-secondary"
    >
        Voltar
    </a>

</div>

</div>

<footer class="mt-auto text-center bg-dark text-light py-4">

    <div class="container">

        <small>
                © <?= date('Y') ?> <?= APP_NAME ?> • <?= APP_SUBTITLE ?>
        </small>

    </div>

</footer>
    <?php require_once __DIR__ . "/../../../src/includes/scripts.php"; ?>
</body>
</html>