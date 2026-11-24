<?php

declare(strict_types=1);

function estaLogado(): bool
{
    return isset($_SESSION['usuario_id'], $_SESSION['usuario_tipo']);
}

function iniciarSessaoUsuario(array $usuario): void
{
    session_regenerate_id(true);
    $_SESSION['usuario_id'] = (int) $usuario['ID'];
    $_SESSION['usuario_nome'] = $usuario['nome_completo'];
    $_SESSION['usuario_email'] = $usuario['email'];
    $_SESSION['usuario_tipo'] = $usuario['tipo_usuario'];
}

function cookieLembrar(string $valor, int $expiracao): void
{
    setcookie('repa_remember', $valor, [
        'expires' => $expiracao,
        'path' => BASE_PATH,
        'secure' => IS_HTTPS,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

function login(string $email, string $senha, bool $lembrarMe = false): bool
{
    global $pdo;

    $stmt = $pdo->prepare(
        'SELECT ID, nome_completo, email, tipo_usuario, status, senha
         FROM user WHERE email = :email LIMIT 1'
    );
    $stmt->execute([':email' => $email]);
    $usuario = $stmt->fetch();

    if (!$usuario || $usuario['status'] !== 'Sim' || !password_verify($senha, $usuario['senha'])) {
        return false;
    }

    iniciarSessaoUsuario($usuario);
    $pdo->prepare('UPDATE user SET ultimo_acesso = NOW() WHERE ID = :id')
        ->execute([':id' => $usuario['ID']]);

    if ($lembrarMe) {
        $token = bin2hex(random_bytes(32));
        $pdo->prepare('UPDATE user SET token_lembrar = :token WHERE ID = :id')
            ->execute([
                ':token' => hash('sha256', $token),
                ':id' => $usuario['ID']
            ]);
        cookieLembrar($token, time() + 60 * 60 * 24 * 30);
    } else {
        $pdo->prepare('UPDATE user SET token_lembrar = NULL WHERE ID = :id')
            ->execute([':id' => $usuario['ID']]);
        cookieLembrar('', time() - 3600);
    }

    return true;
}

function loginAutomatico(): bool
{
    global $pdo;

    if (estaLogado() || empty($_COOKIE['repa_remember'])) {
        return estaLogado();
    }

    $stmt = $pdo->prepare(
        "SELECT ID, nome_completo, email, tipo_usuario
         FROM user
         WHERE token_lembrar = :token AND status = 'Sim' LIMIT 1"
    );
    $stmt->execute([':token' => hash('sha256', $_COOKIE['repa_remember'])]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        cookieLembrar('', time() - 3600);
        return false;
    }

    iniciarSessaoUsuario($usuario);
    return true;
}

function usuarioLogado(): ?array
{
    if (!estaLogado()) {
        return null;
    }

    return [
        'id' => (int) $_SESSION['usuario_id'],
        'nome' => $_SESSION['usuario_nome'],
        'email' => $_SESSION['usuario_email'],
        'tipo' => $_SESSION['usuario_tipo']
    ];
}

function tipoUsuario(string $tipo): bool
{
    return estaLogado() && $_SESSION['usuario_tipo'] === $tipo;
}

function exigirLogin(): void
{
    if (!estaLogado()) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

function exigirTipoUsuario(string $tipo): void
{
    exigirLogin();
    if (!tipoUsuario($tipo)) {
        http_response_code(403);
        exit('Acesso negado.');
    }
}

function logout(): void
{
    global $pdo;

    if (isset($_SESSION['usuario_id'])) {
        $pdo->prepare('UPDATE user SET token_lembrar = NULL WHERE ID = :id')
            ->execute([':id' => $_SESSION['usuario_id']]);
    }

    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax'
        ]);
    }
    cookieLembrar('', time() - 3600);
    session_destroy();
}
