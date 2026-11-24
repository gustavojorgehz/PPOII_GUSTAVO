<?php

require_once __DIR__ . '/../../../src/bootstrap.php';
require_once __DIR__ . '/../../../src/auth/auth.php';

exigirLogin();

$pageTitle = APP_NAME . " | Projetos";

$busca = trim($_GET['busca'] ?? '');
$cursoId = $_GET['curso_id'] ?? '';
$areaId = $_GET['area_id'] ?? '';
$periodo = trim($_GET['periodo'] ?? '');
$status = $_GET['status'] ?? '';

$where = [];
$params = [];

if ($busca !== '') {
    $where[] = "projeto.titulo LIKE :busca";
    $params[':busca'] = '%' . $busca . '%';
}

if ($cursoId !== '') {
    $where[] = "projeto.curso_id = :curso_id";
    $params[':curso_id'] = $cursoId;
}

if ($areaId !== '') {
    $where[] = "projeto.area_id = :area_id";
    $params[':area_id'] = $areaId;
}

if ($periodo !== '') {
    $where[] = "projeto.periodo LIKE :periodo";
    $params[':periodo'] = '%' . $periodo . '%';
}

if ($status !== '') {
    $where[] = "projeto.status = :status";
    $params[':status'] = $status;
}

$cursos = $pdo->query(
    "SELECT ID, nome FROM curso WHERE status = 'Sim' ORDER BY nome"
)->fetchAll();
$areas = $pdo->query(
    "SELECT ID, nome FROM area WHERE status = 'Sim' ORDER BY nome"
)->fetchAll();

$sql = "SELECT 
            projeto.*,
            curso.nome AS curso,
            area.nome AS area,
            user.nome_completo AS orientador
        FROM projeto
        INNER JOIN curso ON projeto.curso_id = curso.ID
        INNER JOIN area ON projeto.area_id = area.ID
        INNER JOIN user ON projeto.orientador_id = user.ID";

if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}

$sql .= " ORDER BY projeto.data_cadastro DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$projetos = $stmt->fetchAll();

require_once __DIR__ . '/../../../src/includes/head.php';
?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1>
                <i class="bi bi-folder"></i>
                Projetos
            </h1>

            <p class="text-muted mb-0">
                Projetos aplicados cadastrados no RePA.
            </p>
        </div>

        <a
            href="<?= BASE_URL ?>/painel/projetos/cadastrar.php"
            class="btn btn-success"
        >
            <i class="bi bi-plus-lg"></i>
            Novo projeto
        </a>

    </div>

    <?php if (empty($projetos)): ?>

        <div class="alert alert-info">
            Nenhum projeto foi cadastrado ainda.
        </div>

    <?php else: ?>

        <form method="GET" class="row g-3 mb-4">

    <div class="col-md-4">
        <label class="form-label">Buscar projeto</label>
        <input 
            type="text"
            name="busca"
            class="form-control"
            placeholder="Digite o título..."
            value="<?= htmlspecialchars($busca) ?>"
        >
    </div>

    <div class="col-md-2">
        <label class="form-label">Curso</label>
        <select name="curso_id" class="form-select">
            <option value="">Todos</option>

            <?php foreach ($cursos as $curso): ?>
                <option 
                    value="<?= $curso['ID'] ?>"
                    <?= $cursoId == $curso['ID'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($curso['nome']) ?>
                </option>
            <?php endforeach; ?>

        </select>
    </div>

    <div class="col-md-2">
        <label class="form-label">Área</label>
        <select name="area_id" class="form-select">
            <option value="">Todas</option>

            <?php foreach ($areas as $area): ?>
                <option 
                    value="<?= $area['ID'] ?>"
                    <?= $areaId == $area['ID'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($area['nome']) ?>
                </option>
            <?php endforeach; ?>

        </select>
    </div>

    <div class="col-md-2">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="">Todos</option>
            <option value="Planejamento" <?= $status === 'Planejamento' ? 'selected' : '' ?>>
                Planejamento
            </option>
            <option value="Em andamento" <?= $status === 'Em andamento' ? 'selected' : '' ?>>
                Em andamento
            </option>
            <option value="Concluído" <?= $status === 'Concluído' ? 'selected' : '' ?>>
                Concluído
            </option>
        </select>
    </div>

    <div class="col-md-2 d-flex align-items-end">
        <button type="submit" class="btn btn-success w-100">
            <i class="bi bi-search"></i>
            Buscar
        </button>
    </div>

    </form>

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-dark">
                    <tr>
                        <th>Título</th>
                        <th>Curso</th>
                        <th>Área</th>
                        <th>Orientador</th>
                        <th>Período</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>

                    <?php foreach ($projetos as $projeto): ?>

                        <tr>

                            <td>
                                <strong>
                                    <?= htmlspecialchars($projeto['titulo']) ?>
                                </strong>
                            </td>

                            <td>
                                <?= htmlspecialchars($projeto['curso']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($projeto['area']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($projeto['orientador']) ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($projeto['periodo'] ?? '-') ?>
                            </td>

                            <td>
                                <span class="badge text-bg-secondary">
                                    <?= htmlspecialchars($projeto['status']) ?>
                                </span>
                            </td>

                            <td>
                                <a
                                    href="<?= BASE_URL ?>/painel/projetos/visualizar.php?id=<?= $projeto['ID'] ?>"
                                    class="btn btn-sm btn-primary"
                                    title="Visualizar"
                                >
                                    <i class="bi bi-eye"></i>
                                </a>

                                <a
                                    href="<?= BASE_URL ?>/painel/projetos/editar.php?id=<?= $projeto['ID'] ?>"
                                    class="btn btn-sm btn-warning"
                                    title="Editar"
                                >
                                    <i class="bi bi-pencil"></i>
                                </a>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

    <?php endif; ?>

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
