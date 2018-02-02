<?php

// Battle of Teripa II - Main file / common functions / html functions / pages
// Made by Bastiaan van der Plaat (https://bastiaan.plaatsoft.nl)

// Messure start time for debug info
$time_start = microtime(true);

// Load the config
include 'config.php';

// ====================================================================
// ============================= TOKENS ===============================
// ====================================================================

// Function to generate a token with the changes for the current POST
function generate_token ($changes) {
    if (!isset($changes['messages'])) $changes['messages'] = null;
    return base64_encode(gzdeflate(http_build_query(array_filter(array_merge($_POST, $changes)))));
}

// Decode the given token and setup the POST vars
if (isset($_POST['token'])) {
    parse_str(gzinflate(base64_decode($_POST['token'])), $token);
    foreach ($token as $key => $value) $_POST[$key] = $value;
    unset($_POST['token'], $token);
} else {
    $_POST['page'] = 'sign_in';
    $_POST['lang'] = $config['default_language'];
}

// ====================================================================
// =========================== DATABASE ===============================
// ====================================================================

// Connect to the database with config vars
$database = new PDO('mysql:host=' . $config['pdo_host'] . ';dbname=' . $config['pdo_dbname'],
    $config['pdo_username'], $config['pdo_password'], [
    // Give PHP error when SQL error
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    // Returns data always as object
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
]);

$database_query_count = 0;

// Standard function to peform a database query
function database_query ($query, $args = []) {
    global $database, $database_query_count;
    $database_query_count++;
    $query = $database->prepare($query);
    $query->execute($args);
    return $query;
}

// Function to create database tables
function database_create_tables () {
    global $config;
    database_query('DROP TABLE IF EXISTS teripa_players');
    database_query('CREATE TABLE teripa_players (
        player_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(24) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        verify TINYINT NOT NULL DEFAULT 0,
        money BIGINT NOT NULL,
        lang CHAR(2) NOT NULL
    )');

    database_query('INSERT INTO teripa_players (username, email, password, money, lang) VALUES (?, ?, ?, ?, ?)',
        [ 'bplaat', 'bastiaan.v.d.plaat@gmail.com', password_hash('amsterdam', PASSWORD_DEFAULT), $config['start_money'], 'en' ]
    );

    database_query('DROP TABLE IF EXISTS teripa_sessions');
    database_query('CREATE TABLE teripa_sessions (
        session_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        player_id INT UNSIGNED NOT NULL,
        session CHAR(32) NOT NULL,
        created_at DATETIME NOT NULL,
        expires_at DATETIME NOT NULL
    )');
}

 //database_create_tables();

// ====================================================================
// ======================= LANGUAGE AND AUTH ==========================
// ====================================================================

function authenticate () {
    global $config;
    if (isset($_POST['session'])) {
        $query = database_query('SELECT player_id, expires_at FROM teripa_sessions WHERE session = ?', [ $_POST['session'] ]);
        if ($query->rowCount() == 1) {
           $session = $query->fetch();
           if (strtotime($session->expires_at) > time()) {
                database_query('UPDATE teripa_sessions SET expires_at = ? WHERE session = ?',
                    [ date('Y-m-d H:i:s', time() + $config['session_expire_time']), $_POST['session'] ]);
                return database_query('SELECT * FROM teripa_players WHERE player_id = ?', [ $session->player_id ])->fetch();
           } else {
               return null;
           }
        } else {
            return null;
        }
    } else {
        return null;
    }
}

// Simple alias function for getting a language string and format it
function t ($key, ...$args) { global $lang; return sprintf($lang[$key], ...$args); }

// ====================================================================
// ========================= HTML COMPONETS ===========================
// ====================================================================

function html_header ($current_lang) {
    global $lang;

    // Load the language strings
    include $current_lang . '.php';

    $html = '<!DOCTYPE html>';
    $html.= '<html lang="' . $current_lang . '">';
    $html.= '<head>';
    $html.= '<meta charset="utf-8">';
    $html.= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $html.= '<title>Battle of Teripa II - ' . t('title_' . $_POST['page']) . '</title>';
    $html.= '<link rel="stylesheet" type="text/css" href="style.css">';
    $html.= '</head>';
    $html.= '<body>';
    return $html;
}

function html_footer () {
    global $time_start, $database_query_count;
    $html = '<pre class="row center">' .  round((microtime(true) - $time_start) * 1000, 1)   . ' ms - ' . $database_query_count . ' queries</pre>';
    $html.= '<div class="row center">' . t('footer_made_by', '<a href="https://bastiaan.plaatsoft.nl/" target="_blank">Bastiaan van der Plaat</a>') . '</div>';
    $html.= '</body>';
    $html.= '</html>';
    return $html;
}

function html_link ($key, $changes, $active = false) {
    return '<form class="inline" method="post"><button class="link' . ($active ? ' active' : '') .
        '" name="token" value="' . generate_token($changes) . '">' . t('link_' . $key) . '</button></form>';
}

function html_text_input ($name) {
    return '<input type="text" name="' . $name .'"' . (isset($_POST[$name ]) ? ' value="' . $_POST[$name ] . '"' : '') . '>';
}

function html_account_header () {
    global $config;
    $html = '<div class="row center title">' . t('title') . '</div>';
    $html.= '<div class="row center slogan">~ ' . t('subtitle') . ' ~</div>';
    $html.= '<div class="row center">';
    $html.= html_link('sign_in', ['page' => 'sign_in'], $_POST['page'] == 'sign_in') . ' &nbsp;';
    $html.= html_link('sign_up', ['page' => 'sign_up'], $_POST['page'] == 'sign_up') . ' &nbsp;';
    $html.= html_link('recovery', ['page' => 'recovery'], $_POST['page'] == 'recovery') . ' &nbsp; ';
    $html.= '<form class="inline" method="post">';
    $html.= '<input type="hidden" name="token" value="' . generate_token(['messages' => isset($_POST['messages']) ? $_POST['messages'] : null, 'lang' => null]) . '">';
    $html.= '<select name="lang" onchange="this.form.submit()">';
    foreach ($config['languages'] as $key => $value) {
        $html.= '<option value="' . $key . '"' . ($key == $_POST['lang'] ? ' selected' : '') . '>' . $value . '</option>';
    }
    $html.= '</select>';
    $html.= '</form>';
    $html.= '</div>';
    $html.= html_account_messages();
    return $html;
}

function html_account_messages () {
    $html = '';
    if (isset($_POST['messages'])) {
        foreach ($_POST['messages'] as $message) {
            $type = explode('_', $message)[0];
            $html .= '<div class="box row">';
            $html .= '<table>';
            $html .= '<tr><th class="' . $type . '">' . t($type) . '</th><td>' . t($message) . '</td></tr>';
            $html .= '</table>';
            $html .= '</div>';
        }
    }
    return $html;
}

function html_account_footer () {
    $player_count = database_query('SELECT player_id FROM teripa_players')->rowCount();
    $players_online = database_query('SELECT session_id FROM teripa_sessions WHERE expires_at >= ?', [date('Y-m-d H:i:s')])->rowCount();
    return '<div class="row center">' . t('footer_account_stats', $player_count, $players_online) . '</div>';
}

function html_game_header () {
    $html = '<div class="row center">';
    $html.= html_link('home', ['page' => 'home'], $_POST['page'] == 'home') . ' &nbsp;';
    $html.= html_link('settings', ['page' => 'settings'], $_POST['page'] == 'settings') . ' &nbsp;';
    $html.= html_link('logout', ['page' => 'logout']);
    $html.= '</div>';
    return $html;
}

// ===================================================================
// ======================== ACCOUNT PAGES ============================
// ===================================================================

// Sign in page
function page_sign_in () {
    $html = html_header($_POST['lang']);
    $html.= html_account_header();
    $html.= '<form class="box row" method="post">';
    $html.= '<input type="hidden" name="token" value="' . generate_token(['page' => 'do_sign_in', 'username' => null]) . '">';
    $html.= '<table>';
    $html.= '<tr><th class="subtitle" colspan="2">' . t('title_sign_in')  . '</th></tr>';
    $html.= '<tr><td>' . t('label_username') . '</td><td>' . html_text_input('username') . '</td></tr>';
    $html.= '<tr><td>' . t('label_password') . '</td><td><input type="password" name="password"></td></tr>';
    $html.= '<tr><td></td><td><input type="submit" value="' . t('link_sign_in') . '"></td></tr>';
    $html.= '</table>';
    $html.= '</form>';
    $html.= html_account_footer();
    $html.= html_footer();
    return $html;
}

// Sign in page database logic
function page_do_sign_in () {
    global $config, $database;
    $query = database_query('SELECT * FROM teripa_players WHERE username = ?', [$_POST['username']]);
    if ($query->rowCount() == 1) {
        $player = $query->fetch();
        if (password_verify($_POST['password'], $player->password)) {
            database_query('INSERT INTO teripa_sessions (player_id, created_at, expires_at) VALUES (?, ?, ?)', [
                $player->player_id,
                date('Y-m-d H:i:s'),
                date('Y-m-d H:i:s', time() + $config['session_expire_time'])
            ]);

            $session_id = $database->lastInsertId();
            $_POST['session'] = md5($session_id);
            database_query('UPDATE teripa_sessions SET session = ? WHERE session_id = ?', [ $_POST['session'], $session_id ]);
            
            unset($_POST['username'], $_POST['password'], $_POST['lang']);
            $_POST['page'] = 'home';
            return page_home();
        } else {
            $_POST['messages'] = ['warning_login_wrong'];
            unset($_POST['password']);
            $_POST['page'] = 'sign_in';
            return page_sign_in();
        }
    } else {
        $_POST['messages'] = ['warning_login_wrong'];
        unset($_POST['password']);
        $_POST['page'] = 'sign_in';
        return page_sign_in();
    }
}

// Sign up page
function page_sign_up () {
    $html = html_header($_POST['lang']);
    $html.= html_account_header();
    $html.= '<form class="box" method="post">';
    $html.= '<input type="hidden" name="token" value="' . generate_token(['page' => 'do_sign_up', 'username' => null, 'email' => null]) . '">';
    $html.= '<table>';
    $html.= '<tr><th class="subtitle" colspan="2">' . t('title_sign_up')  . '</th></tr>';
    $html.= '<tr><td>' . t('label_username') . '</td><td>' . html_text_input('username') . '</td></tr>';
    $html.= '<tr><td>' . t('label_email') . '</td><td>' . html_text_input('email') . '</td></tr>';
    $html.= '<tr><td>' . t('label_password') . '</td><td><input type="password" name="password"></td></tr>';
    $html.= '<tr><td>' . t('label_repeat') . '</td><td><input type="password" name="repeat_password"></td></tr>';
    $html.= '<tr><td></td><td><input type="submit" value="' . t('link_sign_up') . '"></td></tr>';
    $html.= '</table>';
    $html.= '</form>';
    $html.= html_account_footer();
    $html.= html_footer();
    return $html;
}

// Sign up page database logic
function page_do_sign_up () {
    unset($_POST['password'], $_POST['repeat_password']);
    $_POST['page'] = 'sign_up';
    return page_sign_up();
}

// Password recovery page
function page_recovery () {
    $html = html_header($_POST['lang']);
    $html.= html_account_header();
    $html.= '<form class="box" method="post">';
    $html.= '<input type="hidden" name="token" value="' . generate_token(['page' => 'do_recovery', 'email' => null]) . '">';
    $html.= '<table>';
    $html.= '<tr><th class="subtitle" colspan="2">' . t('title_recovery')  . '</th></tr>';
    $html.= '<tr><td>' . t('label_email') . '</td><td>' . html_text_input('email') . '</td></tr>';
    $html.= '<tr><td></td><td><input type="submit" value="' . t('link_recovery') . '"></td></tr>';
    $html.= '</table>';
    $html.= '</form>';
    $html.= html_account_footer();
    $html.= html_footer();
    return $html;
}

// Password recovery page database logic
function page_do_recovery () {
    $_POST['page'] = 'recovery';
    return page_recovery();
}

// ===================================================================
// ========================= GAME PAGES ==============================
// ===================================================================

function page_home () {
    $player = authenticate();
    if ($player) {
        $html = html_header($player->lang);
        $html.= html_game_header();
        $html.= $player->username;
        $html.= html_footer();
        return $html;
    } else {
        return 'logout';
    }
}

function page_settings () {
    $player = authenticate();
    if ($player) {
        $html = html_header($player->lang);
        $html.= html_game_header();
        $html.= html_footer();
        return $html;
    } else {
        return 'logout';
    }
}

function page_logout () {
    global $config;
    unset($_POST['session']);
    $_POST['messages'] = ['info_logout'];
    $_POST['page'] = 'sign_in';
    $_POST['lang'] = $config['default_language'];
    return page_sign_in();
}

// Start the current page
echo call_user_func('page_' . $_POST['page']);

echo '<pre>' . print_r($_POST, true) . '</pre>';