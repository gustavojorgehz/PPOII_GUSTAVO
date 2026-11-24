<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>RePA | Repositório de Projetos Aplicados</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="/public/static/styles/main.css">
    <link rel="stylesheet" href="/public/static/styles/home.css">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container">

            <a class="navbar-brand fw-bold fs-3 text-success" href="#">
                RePA
            </a>

            <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">

                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item me-lg-3">
                        <a class="nav-link active" href="#">
                            Início
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="/public/pages/login.html" class="btn btn-success">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login
                        </a>
                    </li>

                </ul>

            </div>

        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-7">

                    <span class="badge bg-success mb-3">
                        Repositório Acadêmico
                    </span>

                    <h1 class="display-4 fw-bold">
                        Repositório de Projetos Aplicados
                    </h1>

                    <p class="lead mt-4">
                        Plataforma para cadastro, organização e divulgação de projetos desenvolvidos por estudantes e orientadores.
                    </p>

                    <a href="/public/pages/login.html" class="btn btn-success btn-lg mt-3">
                        Acessar Sistema
                        <small>sem um cadastro</small>
                    </a>

                </div>

                <!-- <div class="col-lg-5 text-center">
                    <i class="bi bi-journal-richtext hero-icon"></i>
                </div> -->

            </div>

        </div>

    </section>

    <!-- Informações -->
    <section class="py-5">

        <div class="container">

            <div class="row g-4">

                <div class="col-md-6">

                    <div class="card h-100 shadow-sm">

                        <div class="card-body">

                            <h3 class="card-title mb-4">
                                Sobre o Projeto
                            </h3>

                            <p>
                                O RePA foi desenvolvido para facilitar o compartilhamento do conhecimento produzido dentro da instituição, permitindo que projetos sejam consultados, organizados e reutilizados por estudantes e professores.
                            </p>

                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="card h-100 shadow-sm">

                        <div class="card-body">

                            <h3 class="card-title mb-4">
                                Informações
                            </h3>

                            <ul class="list-group list-group-flush">

                                <li class="list-group-item">
                                    <strong>Tipo:</strong> Sistema Web
                                </li>

                                <li class="list-group-item">
                                    <strong>Objetivo:</strong> Repositório acadêmico
                                </li>

                                <li class="list-group-item">
                                    <strong>Público:</strong> Discentes, Orientadores e Visitantes
                                </li>

                                <li class="list-group-item">
                                    <strong>Funcionalidades:</strong> Cadastro, Login, Upload, Pesquisa e Comentários
                                </li>

                                <li class="list-group-item">
                                    <strong>ODS:</strong> Educação de Qualidade
                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">

        <div class="container text-center">

            <p class="mb-1">
                © 2026 RePA • Repositório de Projetos Aplicados
            </p>

            <small>
                Desenvolvido para a disciplina PPO II <br>
                Discente: Gustavo Jorge .H. Scalzoni <br>
                Orientador: Rodrigo de Paula e Silva Ribeiro
            </small>

        </div>

    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>