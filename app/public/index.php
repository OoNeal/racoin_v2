<?php
declare(strict_types=1);
error_reporting(E_ALL ^ E_DEPRECATED);

require_once __DIR__ . '/../vendor/autoload.php';

/* application boostrap */
$app = include_once __DIR__ . '/../config/bootstrap.php';

/* chargement des routes */
(require_once __DIR__ . '/../config/routes.php')($app);

$app->run();

