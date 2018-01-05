<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 06.08.17
 * Time: 18:00
 */

include('../ifNotLoggedInRedirectToIndex.php');

$inputFirstname = $_REQUEST['inputFirstname'];
$inputSurname = $_REQUEST['inputSurname'];
$inputEmail = $_REQUEST['inputEmail'];
$inputPassword_current = $_REQUEST['inputPassword_current'];
$inputPassword_new_password = $_REQUEST['inputPassword_new_password'];
$inputPassword_new_password_again = $_REQUEST['inputPassword_new_password_again'];

if(!isset($inputFirstname) OR empty($inputFirstname)) {
    header('Location: /index.php?alertReason=editAccount_isset_firstname');
    die();
}
if(!isset($inputSurname) OR empty($inputSurname)) {
    header('Location: /index.php?alertReason=editAccount_isset_surname');
    die();
}
if(!isset($inputEmail) OR empty($inputEmail)) {
    header('Location: /index.php?alertReason=editAccount_isset_email');
    die();
}
if(!isset($inputPassword_current) OR empty($inputPassword_current)) {
    header('Location: /index.php?alertReason=editAccount_isset_password_current');
    die();
}

$change_password = 1;
if(!isset($inputPassword_new_password) OR empty($inputPassword_new_password)) {
    $change_password = 0;
}
if(!isset($inputPassword_new_password_again) OR empty($inputPassword_new_password_again)) {
    $change_password = 0;
}
if($inputPassword_new_password != $inputPassword_new_password_again) {
    if($change_password == 1) {
        header('Location: /index.php?alertReason=editAccount_new_passwords_are_not_equal');
        die();
    }
}

$hashed_password_current = hash('sha512', $inputPassword_current);

if(!isset($conn)) {
    include "../connectToDatabase.php";
}

$stmt = $conn->prepare('SELECT password FROM users WHERE UUID = :UUID;');
$stmt->bindParam(':UUID', $_SESSION['UUID']);
$stmt->execute();

while($row = $stmt->fetch()) {
    if($row[0] != $hashed_password_current) {
        header('Location: /index.php?alertReason=editAccount_current_password_is_wrong');
        die();
    }
    break;
}

if($change_password == 1) {
    if ($inputPassword_new_password != $inputPassword_new_password_again) {
        header('Location: /index.php?alertReason=editAccount_new_passwords_are_not_equal');
        die();
    }

    $hashed_password_new = hash('sha512', $inputPassword_new_password);

    $stmt = $conn->prepare('UPDATE users SET password = :password WHERE UUID = :UUID;');
    $stmt->bindParam(':UUID', $_SESSION['UUID']);
    $stmt->bindParam(':password', $hashed_password_new);
    $stmt->execute();
}

$stmt = $conn->prepare('UPDATE users SET firstname = :firstname , surname = :surname , email = :email WHERE UUID = :UUID;');
$stmt->bindParam(':UUID', $_SESSION['UUID']);
$stmt->bindParam(':firstname', $inputFirstname);
$stmt->bindParam(':surname', $inputSurname);
$stmt->bindParam(':email', $inputEmail);
$stmt->execute();

$_SESSION['email'] = $inputEmail;
$_SESSION['firstname'] = $inputFirstname;

header('Location: /index.php?alertReason=editAccount_successful');
die();