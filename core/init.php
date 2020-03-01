<?php
session_start();
ob_start();

$errors = array();

$start_date = mktime(15, 30, 0, 2, 27, 2020);
$end_date = mktime(16, 30, 0, 2, 27, 2021);

define('SITE_ROOT', '/vote');

if (strpos($_SERVER['PHP_SELF'], 'admin') === false) {
    include_once 'functions/func.php';
    include_once 'credentials/secure.php';
} elseif (strpos($_SERVER['PHP_SELF'], 'admin') !== false) {
    include_once '../functions/func.php';
    include_once '../credentials/secure.php';
}

spl_autoload_register('Autoloader');

if (isset($_SESSION['id'])) {
    $user = User::instance()->user_data($_SESSION['id']);
    $positions = array('presidents', 'governors', 'house_of_representatives', 'senators', 'state_assemblies');
}