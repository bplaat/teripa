<?php

// Battle of Teripa II - Main file / common functions / html functions / pages
// Made by Bastiaan van der Plaat (https://bastiaan.plaatsoft.nl)

// Messure start time for debug info
$time_start = microtime(true);

// Include the config
require 'config.php';

// ====================================================================
// ============================= TOKENS ===============================
// ====================================================================

// Function to generate a token with the changes for the current POST
function generate_token ($changes) {
    if (!isset($changes['warnings'])) $changes['warnings'] = null;
    return base64_encode(gzdeflate(http_build_query(array_filter(array_merge($_POST, $changes)))));
}

// Decode the given token and setup the POST vars
if (isset($_POST['token'])) {
    parse_str(gzinflate(base64_decode($_POST['token'])), $token);
    foreach ($token as $key => $value) $_POST[$key] = $value;
    unset($_POST['token'], $token);
} else {
    $_POST['page'] = 'sign_in';
    $_POST['session'] = uniqid();
    $_POST['lang'] = 'en';
}

// ====================================================================
// =========================== LANGUAGE ===============================
// ====================================================================

// Include the language strings
include $_POST['lang'] . '.php';

// Simple alias function for getting a language string and format it
function t ($key, ...$args) { global $lang; return sprintf($lang[$key], ...$args); }

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


// ====================================================================
// ========================= HTML COMPONETS ===========================
// ====================================================================

function html_header () {
    $html = '<!DOCTYPE html>';
    $html.= '<html lang="' . $_POST['lang'] . '">';
    $html.= '<head>';
    $html.= '<meta charset="utf-8">';
    $html.= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    $html.= '<title>Battle of Teripa II</title>';
    $html.= '<link rel="stylesheet" type="text/css" href="style.css">';
    $html.= '</head>';
    $html.= '<body>';
    return $html;
}

function html_footer () {
    $html = '<div class="row center">' . t('footer_made_by', '<a href="https://bastiaan.plaatsoft.nl/" target="_blank">Bastiaan van der Plaat</a>') . '</div>';
    $html.= '<pre>' . print_r($_POST, true) . '</pre>';
    $html.= '</body>';
    $html.= '</html>';
    return $html;
}

function html_lang_select () {
    global $config;
    $html = '<form style="display:inline" method="post">';
    $html.= '<input type="hidden" name="token" value="' . generate_token(['warnings' => isset($_POST['warnings']) ? $_POST['warnings'] : null, 'lang' => null]) . '">';
    $html.= '<select name="lang" onchange="this.form.submit()">';
    foreach ($config['languages'] as $key => $value) {
        $html.= '<option value="' . $key . '"' . ($key == $_POST['lang'] ? ' selected' : '') . '>' . $value . '</option>';
    }
    $html.= '</select>';
    $html.= '</form>';
    return $html;
}

function html_link ($key, $changes, $active = false) {
    return '<form style="display:inline" method="post"><button class="link' . ($active ? ' active' : '') .
        '" name="token" value="' . generate_token($changes) . '">' . t('link_' . $key) . '</button></form>';
}

function html_text_input ($name) {
    return '<input type="text" name="' . $name .'"' . (isset($_POST[$name ]) ? ' value="' . $_POST[$name ] . '"' : '') . '>';
}

function html_account_header () {
    $html = '<div class="row center title">' . t('title') . '</div>';
    $html.= '<div class="row center slogan">~ ' . t('subtitle') . ' ~</div>';
    $html.= '<div class="row center">';
    $html.= html_link('sign_in', ['page' => 'sign_in'], $_POST['page'] == 'sign_in') . ' &nbsp;';
    $html.= html_link('sign_up', ['page' => 'sign_up'], $_POST['page'] == 'sign_up') . ' &nbsp;';
    $html.= html_link('forgot_password', ['page' => 'forgot_password'], $_POST['page'] == 'forgot_password') . ' &nbsp; &nbsp;';
    $html.= html_lang_select();
    $html.= '</div>';
    $html.= html_account_warnings();
    return $html;
}

function html_account_warnings () {
    $html = '';
    if (isset($_POST['warnings'])) {
        foreach ($_POST['warnings'] as $warning) {
            $html .= '<div class="box row">';
            $html .= '<table>';
            $html .= '<tr><th class="warning">' . t('title_warning') . '</th><td>' . t('warning_' . $warning) . '</td></tr>';
            $html .= '</table>';
            $html .= '</div>';
        }
    }
    return $html;
}

// ====================================================================
// ============================== PAGES ===============================
// ====================================================================

// Sign in page
function page_sign_in () {
    $html = html_header();
    $html.= html_account_header();
    $html.= '<form class="box row" method="post">';
    $html.= '<input type="hidden" name="token" value="' . generate_token(['page' => 'do_sign_in', 'username' => null]) . '">';
    $html.= '<table>';
    $html.= '<tr><th class="subtitle" colspan="2">' . t('title_sign_in')  . '</th></tr>';
    $html.= '<tr><td style="width:150px">' . t('label_username') . '</td><td>' . html_text_input('username') . '</td></tr>';
    $html.= '<tr><td>' . t('label_password') . '</td><td><input type="password" name="password"></td></tr>';
    $html.= '<tr><td></td><td><input type="submit" value="' . t('link_sign_in') . '"></td></tr>';
    $html.= '</table>';
    $html.= '</form>';
    $html.= html_footer();
    return $html;
}

// Sign in page database logic
function page_do_sign_in () {
    if ($_POST['username'] === 'bastiaan') {
        if ($_POST['password'] === 'gouda') {
            echo 'Home';
        } else {
            $_POST['page'] = 'sign_in';
            $_POST['warnings'] = ['login_wrong'];
            unset($_POST['password']);
            echo page_sign_in();
        }
    } else {
        $_POST['page'] = 'sign_in';
        $_POST['warnings'] = ['login_wrong'];
        unset($_POST['password']);
        echo page_sign_in();
    }
}

// Sign up page
function page_sign_up () {
    $html = html_header();
    $html.= html_account_header();
    $html.= '<form class="box" method="post">';
    $html.= '<input type="hidden" name="token" value="' . generate_token(['page' => 'do_sign_up', 'username' => null, 'email' => null]) . '">';
    $html.= '<table>';
    $html.= '<tr><th class="subtitle" colspan="2">' . t('title_sign_up')  . '</th></tr>';
    $html.= '<tr><td style="width:150px">' . t('label_username') . '</td><td>' . html_text_input('username') . '</td></tr>';
    $html.= '<tr><td>' . t('label_email') . '</td><td>' . html_text_input('email') . '</td></tr>';
    $html.= '<tr><td>' . t('label_password') . '</td><td><input type="password" name="password"></td></tr>';
    $html.= '<tr><td>' . t('label_repeat') . '</td><td><input type="password" name="repeat_password"></td></tr>';
    $html.= '<tr><td></td><td><input type="submit" value="' . t('link_sign_up') . '"></td></tr>';
    $html.= '</table>';
    $html.= '</form>';
    $html.= html_footer();
    return $html;
}

// Sign up page database logic
function page_do_sign_up () {
    unset($_POST['password']);
    unset($_POST['repeat_password']);
    echo page_sign_up();
}

// Forgot password page
function page_forgot_password () {
    $html = html_header();
    $html.= html_account_header();
    $html.= '<form class="box" method="post">';
    $html.= '<input type="hidden" name="token" value="' . generate_token(['page' => 'do_forgot_password', 'email' => null]) . '">';
    $html.= '<table>';
    $html.= '<tr><th colspan="2">' . t('title_forgot_password')  . '</th></tr>';
    $html.= '<tr><td style="width:150px">' . t('label_email') . '</td><td>' . html_text_input('email') . '</td></tr>';
    $html.= '<tr><td></td><td><input type="submit" value="' . t('link_forgot_password') . '"></td></tr>';
    $html.= '</table>';
    $html.= '</form>';
    $html.= html_footer();
    return $html;
}

// Start the current page
echo call_user_func('page_' . $_POST['page']);