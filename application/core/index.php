<?php

spl_autoload_register(function ($className) {
    $file = APP . '/controllers/' . $className . '.php';
    if (file_exists($file)) require_once $file;
});
spl_autoload_register(function ($className) {
    $file = APP . '/core/' . $className . '.php';
    if (file_exists($file)) require_once $file;
});
spl_autoload_register(function ($className) {
    $file = APP . '/models/' . $className . '.php';
    if (file_exists($file)) require_once $file;
});
require APP . '/core/utils.php';

require APP . '/config.php';

Database::connect(DATABASE_HOST, DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);

Auth::check();

require APP . '/routes.php';
