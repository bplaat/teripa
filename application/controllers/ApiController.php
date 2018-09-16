<?php

class ApiController {
    public static function players () {
        return Players::select()->fetchAll();
    }
    public static function units () {
        return Units::select()->fetchAll();
    }
    public static function buildings () {
        return Buildings::select()->fetchAll();
    }
}