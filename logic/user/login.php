<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 04.08.17
 * Time: 15:44
 */

$email = $_REQUEST['inputEmail'];
$password = $_REQUEST['inputPassword'];

if(!isset($_SESSION)) {
    session_start();
}

if(isset($_SESSION['email'])) {
    header('Location: /index.php?alertReason=login_alreadyLoggedIn');
    die();
}

if(!isset($email) OR empty($email)) {
    header('Location: /index.php?alertReason=login_isset_email');
    die();
}

if(!isset($password) OR empty($password)) {
    header('Location: /index.php?alertReason=login_isset_password');
    die();
}

$hashed_password = hash('sha512', $password);

if(!isset($conn)) {
    include "../connectToDatabase.php";
}

$login_was_successful = false;

foreach ($conn->query('SELECT UUID, email, password, rank, firstname FROM users;') as $item) {
    if($item[1] == $email && $item[2] == $hashed_password) {
        //Session registrieren
        $_SESSION['UUID'] = $item[0];
        $_SESSION['email'] = $item[1];
        $_SESSION['rank'] = $item[3];
        $_SESSION['firstname'] = $item[4];

        $login_was_successful = true;
        break;
    }
}

if(!$login_was_successful) {
    header('Location: /index.php?alertReason=login_credentials_wrong');
} else {
    header('Location: /index.php?alertReason=login_successful');
}