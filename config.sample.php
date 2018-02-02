<?php

// Battle of Teripa II - Sample config and constants
// Made by Bastiaan van der Plaat (https://bastiaan.plaatsoft.nl)

// PDO database connection
$config['pdo_host'] = '';
$config['pdo_username'] = '';
$config['pdo_password'] = '';
$config['pdo_dbname'] = '';

// Language files
$config['languages']['en'] = 'English';
$config['languages']['nl'] = 'Nederlands';

// Default language
$config['default_language'] = 'en';

// Session expire time (in seconds)
$config['session_expire_time'] = 2 * 60;

// Sign up start money
$config['start_money'] = 5000;