<?php

require_once __DIR__ . '/../../../src/bootstrap.php';
require_once __DIR__ . '/../../../src/auth/auth.php';

exigirLogin();

$pageTitle = APP_NAME . " | Editar Projeto";

$erro = '';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: ' . BASE_URL . '/painel/projetos/index.php');
    exit;
}


/*
 * Busca os dados do projeto
 */
$sql = "SELECT ID, titulo, descricao, objetivo, curso_id, area_id,
               periodo, status, orientador_id, link_projeto
        FROM projeto
        WHERE ID = :id
        LIMIT 1";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id' => $id
]);

$projeto = $stmt->fetch();

if (!$projeto) {
    http_response_code(404);
    die('Projeto não encontrado.');
}


/*
 * Busca cursos
 */
$sql = "SELECT ID, nome
        FROM curso
        WHERE status = 'Sim'
        ORDER BY nome";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$cursos = $stmt->fetchAll();


/*
 * Busca áreas
 */
$sql = "SELECT ID, nome
        FROM area
        WHERE status = 'Sim'
        ORDER BY nome";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$areas = $stmt->fetchAll();


/*
 * Busca orientadores
 */
$sql = "SELECT ID, nome_completo
        FROM user
        WHERE tipo_usuario = 'orientador'
        AND status = 'Sim'
        ORDER BY nome_completo";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$orientadores = $stmt->fetchAll();


/*
 * Processa a alteração
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!csrfTokenValido($csrfToken)) {

        $erro = 'Requisição inválida.';

    } else {

        $titulo = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $objetivo = trim($_POST['objetivo'] ?? '');
        $cursoId = (int) ($_POST['curso_id'] ?? 0);
        $areaId = (int) ($_POST['area_id'] ?? 0);
        $periodo = trim($_POST['periodo'] ?? '');
        $status = trim($_POST['status'] ?? '');
        $orientadorId = (int) ($_POST['orientador_id'] ?? 0);
        $linkProjeto = trim($_POST['link_projeto'] ?? '');

        /*
         * Validação
         */
        if ($titulo === '') {

            $erro = 'Informe o título do projeto.';

        } elseif ($descricao === '') {

            $erro = 'Informe a descrição do projeto.';

        } elseif ($cursoId <= 0) {

            $erro = 'Selecione um curso.';

        } elseif ($areaId <= 0) {

            $erro = 'Selecione uma área.';

        } elseif ($orientadorId <= 0) {

            $erro = 'Selecione um orientador.';

        } elseif (!in_array($status, ['Planejamento', 'Em andamento', 'Concluído'], true)) {

            $erro = 'Selecione um status válido.';

        } elseif ($linkProjeto !== '' && !filter_var($linkProjeto, FILTER_VALIDATE_URL)) {

            $erro = 'Informe uma URL válida para o projeto.';

        } else {

            try {

                $sql = "UPDATE projeto
                        SET
                            titulo = :titulo,
                            descricao = :descricao,
                            objetivo = :objetivo,
                            curso_id = :curso_id,
                            area_id = :area_id,
                            periodo = :periodo,
                            status = :status,
                            orientador_id = :orientador_id,
                            link_projeto = :link_projeto
                        WHERE ID = :id";

                $stmt = $pdo->prepare($sql);

                $stmt->execute([
                    ':titulo' => $titulo,
                    ':descricao' => $descricao,
                    ':objetivo' => $objetivo,
                    ':curso_id' => $cursoId,
                    ':area_id' => $areaId,
                    ':periodo' => $periodo,
                    ':status' => $status,
                    ':orientador_id' => $orientadorId,
                    ':link_projeto' => $linkProjeto,
                    ':id' => $id
                ]);

                redirecionar(BASE_URL . '/painel/projetos/visualizar.php?id=' . $id);

            } catch (PDOException $e) {

                $erro = 'Não foi possível atualizar o projeto.';
            }
        }

        /*
         * Mantém os valores digitados caso ocorra erro
         */
        if (!empty($erro)) {

            $projeto['titulo'] = $titulo;
            $projeto['descricao'] = $descricao;
            $projeto['objetivo'] = $objetivo;
            $projeto['curso_id'] = $cursoId;
            $projeto['area_id'] = $areaId;
            $projeto['periodo'] = $periodo;
            $projeto['status'] = $status;
            $projeto['orientador_id'] = $orientadorId;
            $projeto['link_projeto'] = $linkProjeto;
        }
    }
}


require_once __DIR__ . '/../../../src/includes/head.php';

?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h1>
                <i class="bi bi-pencil-square"></i>
                Editar projeto
            </h1>

            <p class="text-muted mb-0">
                Altere as informações do projeto.
            </p>

        </div>

        <a
            href="<?= BASE_URL ?>/painel/projetos/visualizar.php?id=<?= $id ?>"
            class="btn btn-secondary"
        >
            <i class="bi bi-arrow-left"></i>
            Voltar
        </a>

    </div>


    <?php if (!empty($erro)): ?>

        <div class="alert alert-danger">
            <?= htmlspecialchars($erro) ?>
        </div>

    <?php endif; ?>


    <div class="card shadow-sm">

        <div class="card-body">

            <form method="POST">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
                >


                <!-- Título -->

                <div class="mb-3">

                    <label
                        for="titulo"
                        class="form-label"
                    >
                        Título do projeto
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="titulo"
                        name="titulo"
                        maxlength="200"
                        required
                        value="<?= htmlspecialchars($projeto['titulo']) ?>"
                    >

                </div>


                <!-- Descrição -->

                <div class="mb-3">

                    <label
                        for="descricao"
                        class="form-label"
                    >
                        Descrição
                    </label>

                    <textarea
                        class="form-control"
                        id="descricao"
                        name="descricao"
                        rows="5"
                        required
                    ><?= htmlspecialchars($projeto['descricao']) ?></textarea>

                </div>


                <!-- Objetivo -->

                <div class="mb-3">

                    <label
                        for="objetivo"
                        class="form-label"
                    >
                        Objetivo
                    </label>

                    <textarea
                        class="form-control"
                        id="objetivo"
                        name="objetivo"
                        rows="4"
                    ><?= htmlspecialchars($projeto['objetivo'] ?? '') ?></textarea>

                </div>


                <div class="row">

                    <!-- Curso -->

                    <div class="col-md-6 mb-3">

                        <label
                            for="curso_id"
                            class="form-label"
                        >
                            Curso
                        </label>

                        <select
                            class="form-select"
                            id="curso_id"
                            name="curso_id"
                            required
                        >

                            <option value="">
                                Selecione o curso
                            </option>

                            <?php foreach ($cursos as $curso): ?>

                                <option
                                    value="<?= $curso['ID'] ?>"
                                    <?= $projeto['curso_id'] == $curso['ID']
                                        ? 'selected'
                                        : '' ?>
                                >
                                    <?= htmlspecialchars($curso['nome']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <!-- Área -->

                    <div class="col-md-6 mb-3">

                        <label
                            for="area_id"
                            class="form-label"
                        >
                            Área
                        </label>

                        <select
                            class="form-select"
                            id="area_id"
                            name="area_id"
                            required
                        >

                            <option value="">
                                Selecione a área
                            </option>

                            <?php foreach ($areas as $area): ?>

                                <option
                                    value="<?= $area['ID'] ?>"
                                    <?= $projeto['area_id'] == $area['ID']
                                        ? 'selected'
                                        : '' ?>
                                >
                                    <?= htmlspecialchars($area['nome']) ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                </div>


                <div class="row">

                    <!-- Período -->

                    <div class="col-md-6 mb-3">

                        <label
                            for="periodo"
                            class="form-label"
                        >
                            Período
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="periodo"
                            name="periodo"
                            maxlength="50"
                            value="<?= htmlspecialchars($projeto['periodo'] ?? '') ?>"
                        >

                    </div>


                    <!-- Status -->

                    <div class="col-md-6 mb-3">

                        <label
                            for="status"
                            class="form-label"
                        >
                            Status
                        </label>

                        <select
                            class="form-select"
                            id="status"
                            name="status"
                        >

                            <option
                                value="Planejamento"
                                <?= $projeto['status'] === 'Planejamento'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Planejamento
                            </option>

                            <option
                                value="Em andamento"
                                <?= $projeto['status'] === 'Em andamento'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Em andamento
                            </option>

                            <option
                                value="Concluído"
                                <?= $projeto['status'] === 'Concluído'
                                    ? 'selected'
                                    : '' ?>
                            >
                                Concluído
                            </option>

                        </select>

                    </div>

                </div>


                <!-- Orientador -->

                <div class="mb-3">

                    <label
                        for="orientador_id"
                        class="form-label"
                    >
                        Orientador
                    </label>

                    <select
                        class="form-select"
                        id="orientador_id"
                        name="orientador_id"
                        required
                    >

                        <option value="">
                            Selecione o orientador
                        </option>

                        <?php foreach ($orientadores as $orientador): ?>

                            <option
                                value="<?= $orientador['ID'] ?>"
                                <?= $projeto['orientador_id'] == $orientador['ID']
                                    ? 'selected'
                                    : '' ?>
                            >
                                <?= htmlspecialchars($orientador['nome_completo']) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>


                <!-- Link -->

                <div class="mb-4">

                    <label
                        for="link_projeto"
                        class="form-label"
                    >
                        Link do projeto
                    </label>

                    <input
                        type="url"
                        class="form-control"
                        id="link_projeto"
                        name="link_projeto"
                        maxlength="255"
                        value="<?= htmlspecialchars($projeto['link_projeto'] ?? '') ?>"
                    >

                </div>


                <!-- Botões -->

                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >
                        <i class="bi bi-check-lg"></i>
                        Salvar alterações
                    </button>

                    <a
                        href="<?= BASE_URL ?>/painel/projetos/visualizar.php?id=<?= $id ?>"
                        class="btn btn-secondary"
                    >
                        Cancelar
                    </a>

                </div>

            </form>

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
    <?php require_once __DIR__ . "/../../../src/includes/scripts.php"; ?>
</body>
</html>
