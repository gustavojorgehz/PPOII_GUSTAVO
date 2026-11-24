<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$host = getenv('REPA_DB_HOST') ?: 'localhost';
$dbname = getenv('REPA_DB_NAME') ?: 'repa';
$user = getenv('REPA_DB_USER') ?: 'root';
$password = getenv('REPA_DB_PASSWORD') ?: '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

    $configExiste = (bool) $pdo->query('SELECT ID FROM config LIMIT 1')->fetchColumn();
    if (!$configExiste) {
        $pdo->prepare(
            'INSERT INTO config (nome_sistema, telefone_sistema, email_sistema)
             VALUES (:nome, :telefone, :email)'
        )->execute([
            ':nome' => APP_NAME,
            ':telefone' => '(47) 9 9999-9999',
            ':email' => 'repa@mail.com'
        ]);
    }

    if (MODO_TESTE) {
        $usuarioExiste = $pdo->prepare('SELECT ID FROM user WHERE email = :email LIMIT 1');
        $usuarioExiste->execute([':email' => DEFAULT_USER_EMAIL]);

        if (!$usuarioExiste->fetchColumn()) {
            $pdo->prepare(
                'INSERT INTO user
                    (nome_completo, email, telefone, tipo_usuario, status, senha)
                 VALUES
                    (:nome, :email, :telefone, :tipo, :status, :senha)'
            )->execute([
                ':nome' => 'Usuário Visitante',
                ':email' => DEFAULT_USER_EMAIL,
                ':telefone' => null,
                ':tipo' => 'visitante',
                ':status' => 'Sim',
                ':senha' => password_hash(DEFAULT_USER_PASSWORD, PASSWORD_DEFAULT)
            ]);
        }
    }
} catch (PDOException $exception) {
    error_log($exception->getMessage());
    http_response_code(500);
    exit('Não foi possível conectar ao banco de dados.');
}
