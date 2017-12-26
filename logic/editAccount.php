<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 06.08.17
 * Time: 18:00
 */

include('ifNotLoggedInRedirectToIndex.php');

$inputFirstname = $_REQUEST['inputFirstname'];
$inputSurname = $_REQUEST['inputSurname'];
$inputEmail = $_REQUEST['inputEmail'];
$inputPassword_current = $_REQUEST['inputPassword_current'];
$inputPassword = $_REQUEST['inputPassword'];
$inputPassword_again = $_REQUEST['inputPassword_again'];

if(!isset($inputFirstname) OR empty($inputFirstname)) {
    header('Location: ../index.php?alertReason=editAccount_isset_firstname');
    die();
}
if(!isset($inputSurname) OR empty($inputSurname)) {
    header('Location: ../index.php?alertReason=editAccount_isset_surname');
    die();
}
if(!isset($inputEmail) OR empty($inputEmail)) {
    header('Location: ../index.php?alertReason=editAccount_isset_email');
    die();
}
if(!isset($inputPassword_current) OR empty($inputPassword_current)) {
    header('Location: ../index.php?alertReason=editAccount_isset_password_current');
    die();
}

$inputFirstname = preg_replace("/[^a-zA-Z0-9]/", "", $inputFirstname);
$inputSurname = preg_replace("/[^a-zA-Z0-9]/", "", $inputSurname);
$inputEmail = preg_replace("/[^a-zA-Z0-9]/", "", $inputEmail);


$change_password = true;
if(!isset($inputPassword) OR empty($inputPassword)) {
    $change_password = false;
}
if(!isset($inputPassword_again) OR empty($inputPassword_again)) {
    if($change_password == true) {
        header('Location: ../index.php?alertReason=editAccount_new_passwords_are_not_equal');
        die();
    }
}

$hashed_password_current = hash('sha512', $inputPassword_current);

if(!isset($conn)) {
    include "./connectToDatabase.php";
}

foreach ($conn->query('SELECT UUID, password FROM users WHERE UUID="'.$_REQUEST['UUID'].'";') as $item) {
    if($item[1] != $hashed_password_current) {
        header('Location: ../index.php?alertReason=editAccount_current_password_is_wrong');
        die();
    }
    break;
}

if($change_password == true) {
    if ($inputPassword != $inputPassword_again) {
        header('Location: ../index.php?alertReason=editAccount_new_passwords_are_not_equal');
        die();
    }

    $hashed_password = hash('sha512', $inputPassword);

    $sql = 'UPDATE users SET password="'.$hashed_password.'" WHERE UUID='. $_REQUEST['UUID'].';';

    $conn->exec($sql);
}

$sql = 'UPDATE users SET firstname="'.$inputFirstname.'", surname="'.$inputSurname.'", email="'.$inputEmail.'" WHERE UUID='. $_REQUEST['UUID'].';';

$conn->exec($sql);

$_SESSION['email'] = $inputEmail;
$_SESSION['firstname'] = $inputFirstname;

header('Location: ../index.php?alert=editAccount_successful');
die();



