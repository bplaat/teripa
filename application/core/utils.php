<?php

function view ($name, $data = null) {
    if (!is_null($data)) extract($data);
    require APP . '/views/' . $name . '.php';
}

function cut ($string, $length) {
    return strlen($string) > $length ? substr($string, 0, $length) . '...' : $string;
}

function startsWith ($string, $startString) {
    return substr($string, 0, strlen($startString)) == $startString;
}

function findById ($items, $id) {
    foreach ($items as $item) {
        if ($item->id == $id) {
            return $item;
        }
    }
}

function slug ($string) {
    return str_replace(' ', '_', strtolower($string));
}
