<?php

$autoload_folders = [ ROOT . '/core', ROOT . '/models', ROOT . '/controllers' ];

function search_folder (string $folder) {
    global $autoload_folders;
    $files = glob($folder . '/*');
    foreach ($files as $file) {
        if (is_dir($file)) {
            $autoload_folders[] = $file;
            search_folder($file);
        }
    }
}

foreach ($autoload_folders as $folder) {
    search_folder($folder);
}

spl_autoload_register(function (string $class) {
    global $autoload_folders;
    foreach ($autoload_folders as $folder) {
        $path = $folder . '/' . $class . '.php';
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

require ROOT . '/vendor/autoload.php';

require_once ROOT . '/config.php';

if (APP_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

Session::start(SESSION_SHORT_COOKIE_NAME);

require_once ROOT . '/core/utils.php';

Database::connect(DATABASE_DSN, DATABASE_USER, DATABASE_PASSWORD);

require_once ROOT . '/routes/web.php';
