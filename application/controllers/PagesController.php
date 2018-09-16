<?php

class PagesController {
    public static function index () {
        return view('index');
    }
    public static function about () {
        return view('about');
    }
    public static function notfound ($path) {
        http_response_code(404);
        return view('notfound', [ 'path' => $path ]);
    }
}