<?php

function money_format ($number) {
    return '<span class="money">$ ' . number_format($number) . '</span>';
}
function income_format ($number) {
    return '<span class="money">$ ' . number_format($number) . ' /s</span>';
}
function attack_format ($number) {
    return '<span class="attack">' . number_format($number) . '</span>';
}
function defence_format ($number) {
    return '<span class="defence">' . number_format($number) . '</span>';
}