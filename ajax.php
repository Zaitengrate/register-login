<?php
session_start();

require 'Classes/Auth.php';

$auth = new Auth('db.xml');


if ((!empty($_POST['create_password']) && !empty($_POST['conf_pass'])) && ($_POST['create_password'] === $_POST['conf_pass'])) {
    if (!empty($_POST['create_login']) && !empty($_POST['name']) && !empty($_POST['email'])) {
        $message['message'] =  $auth->createAccount(htmlspecialchars($_POST['create_login']),
            htmlspecialchars($_POST['create_password']),
            htmlspecialchars($_POST['name']),
            htmlspecialchars($_POST['email']));
    } else {
        $message['message'] = "Login, name or email is empty";
    }
} else {
    $message['message'] = "Password is empty or is not the same as confirm password";
}

if (!empty($_POST['login']) && !empty($_POST['password'])) {
    $message = $auth->login(htmlspecialchars($_POST['login']), htmlspecialchars($_POST['password']));
} elseif (!isset($message)) {
    $message['message'] = "Fields are empty";
}

echo json_encode($message);