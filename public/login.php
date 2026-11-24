<?php

require_once __DIR__ . "/../src/bootstrap.php";
require_once __DIR__ . '/../src/auth/auth.php';

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? null;
    $email = trim((string) ($_POST['email'] ?? ''));
    $senha = (string) ($_POST['senha'] ?? '');

    if (!csrfTokenValido($csrfToken)) {
        $erro = 'Requisição inválida.';
    } elseif ($email === '' || $senha === '') {
        $erro = 'Preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Digite um e-mail válido.';
    } elseif (!login($email, $senha, isset($_POST['lembrar_me']))) {
        $erro = 'E-mail ou senha incorretos.';
    } else {
        redirecionar(BASE_URL . '/painel/index.php');
    }
}

$pageTitle = APP_NAME . " | Login";

require_once __DIR__ . "/../src/includes/head.php";

$emailPadrao = '';
$senhaPadrao = '';

if (MODO_TESTE) {
    $emailPadrao = DEFAULT_USER_EMAIL;
    $senhaPadrao = DEFAULT_USER_PASSWORD;
}

?>

    <link rel="stylesheet" href="<?= BASE_URL ?>/styles/login.css">

    <script defer src="<?= BASE_URL ?>/script/login.js"></script>

</head>

<body class="auth-page d-flex flex-column min-vh-100">

    <!-- Navbar -->
     <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container">

            <a class="navbar-brand fw-bold fs-3 text-success" href=".\index.php">
                <?= APP_NAME ?>
            </a>

            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">

                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item me-lg-3">
                        <a class="nav-link active" href=".\index.php">
                            Início
                        </a>
                    </li>

                </ul>

            </div>

        </div>
    </nav>

    <!-- Conteúdo -->
    <main class="container flex-grow-1 d-flex justify-content-center align-items-center py-5">

        <div class="col-12 col-sm-10 col-md-8 col-lg-5">

            <!-- LOGIN -->

            <div id="loginForm" class="card auth-card shadow">

                <div class="card-body p-4">

                    <h2 class="text-center mb-4">
                        Entrar
                    </h2>

                   <?php if (!empty($erro)): ?>

                        <div class="alert alert-danger">
                            <?= htmlspecialchars($erro) ?>
                        </div>

                    <?php endif; ?>

                    <div id="loginMessage"></div>

                    <form action="" method="POST">

                        <input
                            type="hidden"
                            name="csrf_token"
                            value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
                        >

                        <div class="mb-3">

                            <label for="loginEmail" class="form-label">
                                E-mail
                            </label>

                            <input
                                type="email"
                                id="loginEmail"
                                name="email"
                                class="form-control"
                                placeholder="Digite seu e-mail"
                                value="<?= htmlspecialchars($emailPadrao) ?>"
                                required>

                        </div>

                        <div class="mb-4">

                            <label for="loginPassword" class="form-label">
                                Senha
                            </label>

                            <input
                                type="password"
                                id="loginPassword"
                                name="senha"
                                class="form-control"
                                placeholder="Digite sua senha"
                                value="<?= htmlspecialchars($senhaPadrao) ?>"
                                required>

                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="form-check">

                                <input
                                    role="button"
                                    class="form-check-input"
                                    type="checkbox"
                                    id="rememberMe"
                                    name="lembrar_me"
                                    value="1"
                                    >

                                <label
                                    class="form-check-label"
                                    for="rememberMe">

                                    Lembrar-me

                                </label>

                            </div>

                        </div>
                        <button class="btn btn-success w-100" type="submit">
                            Entrar
                        </button>

                    </form>

                    <hr>

                    <p class="text-center mb-0">

                        Não possui uma conta?

                        <a href="#" onclick="showForm('register')" class="text-success fw-semibold text-decoration-none">
                            Cadastre-se
                        </a>

                    </p>

                </div>

            </div>

            <div id="registerForm" class="card auth-card shadow d-none">

                <div class="card-body p-4">

                    <h2 class="text-center mb-4">
                        Criar Conta
                    </h2>

                    <div id="registerMessage"></div>

                    <form id="registerFormElement">

                        <div class="mb-3">

                            <label class="form-label">
                                Nome completo
                            </label>

                            <input
                                type="text"
                                id="registerName"
                                class="form-control"
                                placeholder="Digite seu nome"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                E-mail
                            </label>

                            <input
                                type="email"
                                id="registerEmail"
                                class="form-control"
                                placeholder="Digite seu e-mail"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Tipo de usuário</label>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="userType"
                                    id="discente"
                                    value="discente"
                                    required
                                >
                                <label class="form-check-label" for="discente">
                                    Discente
                                </label>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="userType"
                                    id="orientador"
                                    value="orientador"
                                >
                                <label class="form-check-label" for="orientador">
                                    Orientador
                                </label>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="userType"
                                    id="visitante"
                                    value="visitante"
                                >
                                <label class="form-check-label" for="visitante">
                                    Visitante
                                </label>
                            </div>
                        </div>
                    
                        <div class="mb-3">

                            <label class="form-label">
                                Senha
                            </label>

                            <input
                                type="password"
                                id="registerPassword"
                                class="form-control"
                                placeholder="Mínimo de 6 caracteres"
                                required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">
                                Confirmar senha
                            </label>

                            <input
                                type="password"
                                id="registerConfirmPassword"
                                class="form-control"
                                placeholder="Digite novamente"
                                required>

                        </div>

                        <button class="btn btn-success w-100" type="submit">
                            Criar Conta
                        </button>

                    </form>

                    <hr>

                    <p class="text-center mb-0">

                        Já possui uma conta?

                        <a href="#" onclick="showForm('login')" class="text-success fw-semibold text-decoration-none">
                            Fazer Login
                        </a>

                    </p>

                </div>

            </div>

             <div id="userArea" class="card auth-card shadow d-none"></div>

        </div>

    </main>

    <footer class="mt-auto text-center bg-dark text-light py-4">

        <div class="container">

            <small>
                © <?= date('Y') ?> <?= APP_NAME ?> • <?= APP_SUBTITLE ?>
            </small>

        </div>

    </footer>

    <?php require_once __DIR__ . "/../src/includes/scripts.php"; ?>

</body>

</html>
