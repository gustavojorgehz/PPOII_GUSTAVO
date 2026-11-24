<?php

require_once __DIR__ . '/../../src/bootstrap.php';
require_once __DIR__ . '/../../src/auth/auth.php';

exigirLogin();

$usuario = usuarioLogado();

$pageTitle = APP_NAME . " | Painel";

require_once __DIR__ . '/../../src/includes/head.php';
?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1>Painel</h1>

            <p class="text-muted mb-0">
                Bem-vindo, <?= htmlspecialchars($usuario['nome']) ?>!
            </p>
        </div>

        <a href="<?= BASE_URL ?>/logout.php" class="btn btn-danger">
            <i class="bi bi-box-arrow-right"></i>
            Sair
        </a>

    </div>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">

                <div class="card-body">
                    <i class="bi bi-folder fs-1"></i>

                    <h3 class="card-title mt-3">
                        Projetos
                    </h3>

                    <p class="card-text">
                        Cadastre, consulte e gerencie os projetos aplicados.
                    </p>

                    <a
                        href="<?= BASE_URL ?>/painel/projetos/index.php"
                        class="btn btn-success"
                    >
                        Acessar projetos
                    </a>
                </div>

            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">

                <div class="card-body">
                    <i class="bi bi-person fs-1"></i>

                    <h3 class="card-title mt-3">
                        Meu perfil
                    </h3>

                    <p class="card-text">
                        Visualize suas informações de usuário.
                    </p>

                    <button class="btn btn-secondary" disabled>
                        Em breve
                    </button>
                </div>

            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 shadow-sm">

                <div class="card-body">
                    <i class="bi bi-bar-chart fs-1"></i>

                    <h3 class="card-title mt-3">
                        Estatísticas
                    </h3>

                    <p class="card-text">
                        Consulte informações sobre os projetos cadastrados.
                    </p>

                    <button class="btn btn-secondary" disabled>
                        Em breve
                    </button>
                </div>

            </div>
        </div>

    </div>

</div>

<footer class="mt-auto text-center bg-dark text-light py-4">

    <div class="container">

        <small>
                © <?= date('Y') ?> <?= APP_NAME ?> • <?= APP_SUBTITLE ?>
        </small>

    </div>

</footer>
    <?php require_once __DIR__ . "/../../src/includes/scripts.php"; ?>
</body>
</html>