<?php
session_start();

require 'Classes/Auth.php';

$auth = new Auth('db.xml');

$html_login = file_get_contents("login.html");
$html_logged_in = file_get_contents("logged_in.html");

if (isset($_SESSION['user'])) {
    echo str_replace('{NAME}', $_SESSION['user'], $html_logged_in);
} elseif (isset($_COOKIE['token'])) {
    $message = $auth->cookieLogin($_COOKIE['token']);
    if ($message === 'logged in') {
        echo str_replace('{NAME}', $_SESSION['user'], $html_logged_in);
    } else {
        echo $html_login;
    }
} else {
    echo $html_login;
}





