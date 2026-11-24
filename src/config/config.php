<?php

declare(strict_types=1);

define('APP_NAME', 'RePA');
define('APP_SUBTITLE', 'Repositório de Projetos Aplicados');
define('BASE_URL', '/PPOII_GUSTAVO/public');
define('BASE_PATH', '/PPOII_GUSTAVO/public');
define('DEFAULT_USER_EMAIL', 'visitante@repa.com');
define('DEFAULT_USER_PASSWORD', '123');
define('MODO_TESTE', true);
define('IS_HTTPS', !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

date_default_timezone_set('America/Sao_Paulo');
