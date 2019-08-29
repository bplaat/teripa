<?php

class AdminController {
    public static function index () {
        Router::redirect('/admin/players');
    }
}
