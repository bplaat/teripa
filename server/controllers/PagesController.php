<?php

class PagesController {
    public static function home(): string {
        return view('home');
    }

    public static function notFound(): string {
        http_response_code(404);
        return view('not_found');
    }
}
