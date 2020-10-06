<?php

function request (string $key, $default = ''): string {
    return isset($_REQUEST[$key]) ? $_REQUEST[$key] : $default;
}

foreach ($_REQUEST as $key => $value) {
    Session::flash('old_' . $key, $value);
}

function old (string $key, $default = ''): string {
    return Session::get('old_' . $key, $default);
}

function csrf_token(): void {
    echo '<input type="hidden" name="csrf_token" value="' . Session::get('csrf_token') . '">';
}
