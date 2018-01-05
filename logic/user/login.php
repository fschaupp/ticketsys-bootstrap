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

$stmt = $conn->prepare('SELECT UUID, email, firstname, password, rank FROM users WHERE email = :email AND password = :password;');
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $hashed_password);
$stmt->execute();

while($row = $stmt->fetch()) {
    $_SESSION['UUID'] = $row[0];
    $_SESSION['email'] = $row[1];
    $_SESSION['firstname'] = $row[2];
    $_SESSION['rank'] = $row[4];

    $login_was_successful = true;
    break;
}

if(!$login_was_successful) {
    header('Location: /index.php?alertReason=login_credentials_wrong');
} else {
    header('Location: /index.php?alertReason=login_successful');
}