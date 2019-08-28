<?php

class PagesController {
    public static function home () {
        view('home');
    }

    public static function about () {
        view('about');
    }

    public static function notfound ($path) {
        http_response_code(404);
        view('notfound', [ 'path' => $path ]);
    }
}
