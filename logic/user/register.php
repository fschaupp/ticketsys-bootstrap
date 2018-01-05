<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 04.08.17
 * Time: 16:20
 */

if(!isset($_SESSION)) {
    session_start();
}

if(isset($_SESSION['email'])) {
    header('Location: /index.php?alert=alreadyLoggedIn');
    die();
}

$inputFirstname = $_REQUEST['inputFirstname'];
$inputSurname = $_REQUEST['inputSurname'];
$inputEmail = $_REQUEST['inputEmail'];
$inputPassword = $_REQUEST['inputPassword'];
$inputPassword_again = $_REQUEST['inputPassword_again'];

if(!isset($inputFirstname) OR empty($inputFirstname)) {
    header('Location: /index.php?alertReason=register_isset_firstname');
    die();
}
if(strlen($inputFirstname) > 128) {
    header('Location: /index.php?alertReason=register_firstname_over_128_characters');
    die();
}

if(!isset($inputSurname) OR empty($inputSurname)) {
    header('Location: /index.php?alertReason=register_isset_surname');
    die();
}
if(strlen($inputSurname) > 128) {
    header('Location: /index.php?alertReason=register_surname_over_128_characters');
    die();
}

if(!isset($inputEmail) OR empty($inputEmail)) {
    header('Location: /index.php?alertReason=register_isset_email');
    die();
}
if(strlen($inputEmail) > 128) {
    header('Location: /index.php?alertReason=register_email_over_128_characters');
    die();
}

if(!isset($inputPassword) OR empty($inputPassword)) {
    header('Location: /index.php?alertReason=register_isset_password');
    die();
}
if(strlen($inputPassword) > 512) {
    header('Location: /index.php?alertReason=register_password_over_512_characters');
    die();
}

if(!isset($inputPassword_again) OR empty($inputPassword_again)) {
    header('Location: /index.php?alertReason=register_isset_password');
    die();
}

if($inputPassword != $inputPassword_again) {
    header('Location: /index.php?alertReason=register_passwords_are_not_equal');
    die();
}

$hashed_password = hash('sha512', $inputPassword);

if(!isset($conn)) {
    include "../connectToDatabase.php";
}

$stmt = $conn->prepare('SELECT email FROM users WHERE email = :email;');
$stmt->bindParam(':email', $inputEmail);
$stmt->execute();
$isAlreadyUsed = $stmt->rowCount();

if($isAlreadyUsed > 0) {
    header('Location: /index.php?alertReason=register_account_already_exist');
    die();
}

$stmt = $conn->prepare('INSERT INTO users(firstname, surname, password, email, rank) VALUE (:firstname, :surname, :password, :email, "rank");');
$stmt->bindParam(':firstname', $inputFirstname);
$stmt->bindParam(':surname', $inputSurname);
$stmt->bindParam(':password', $hashed_password);
$stmt->bindParam(':email', $inputEmail);
$stmt->execute();

header('Location: /index.php?alertReason=register_successful');
die();