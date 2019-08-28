<?php

class AdminController {
    public static function index () {
        Router::redirect('/admin/players');
    }

    public static function migrate () {
        foreach (glob(APP . '/models/*.php') as $filename) {
            $classname = str_replace('.php', '', basename($filename));
            call_user_func($classname . '::drop');
            call_user_func($classname . '::create');
        }
        Auth::signup('Bastiaan', 'van der Plaat', 'bplaat', 'bastiaan.v.d.plaat@gmail.com', 'gouda', true);
        Router::back();
    }
}
