<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 06.08.17
 * Time: 18:00
 */

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['email'])) {
    //TODO: Add Alert loginFirst
    header('Location: ../index.php?alert=loginFirst');
    die();
}

$UUID = $_REQUEST['UUID'];
$inputFirstname = $_REQUEST['inputFirstname'];
$inputSurname = $_REQUEST['inputSurname'];
$inputEmail = $_REQUEST['inputEmail'];
$inputPassword_current = $_REQUEST['inputPassword_current'];
$inputPassword = $_REQUEST['inputPassword'];
$inputPassword_again = $_REQUEST['inputPassword_again'];

if(!isset($UUID) OR empty($UUID)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
    die();
}
if(!isset($inputFirstname) OR empty($inputFirstname)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
    die();
}
if(!isset($inputSurname) OR empty($inputSurname)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
    die();
}
if(!isset($inputEmail) OR empty($inputEmail)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
    die();
}
if(!isset($inputPassword_current) OR empty($inputPassword_current)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
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
        header('Location: ../index.php?alert=inputIsNotCorrect');
        die();
    }
}

$hashed_password_current = hash('sha512', $inputPassword_current);

if(!isset($conn)) {
    include "./connectToDatabase.php";
}

foreach ($conn->query('SELECT UUID, password FROM users WHERE UUID="'.$UUID.'";') as $item) {
    if($item[1] != $hashed_password_current) {
        //TODO: Add Alert passwordIsWrong
        header('Location: ../index.php?alert=passwordIsWrong');
        die();
    }
    break;
}

if($change_password == true) {
    if ($inputPassword != $inputPassword_again) {
        header('Location: ../index.php?alert=passwordsAreNotEqual');
        die();
    }

    $hashed_password = hash('sha512', $inputPassword);

    $sql = 'UPDATE users SET password="'.$hashed_password.'" WHERE UUID='. $UUID.';';

    $conn->exec($sql);
}

$sql = 'UPDATE users SET firstname="'.$inputFirstname.'", surname="'.$inputSurname.'", email="'.$inputEmail.'" WHERE UUID='. $UUID.';';

$conn->exec($sql);

$_SESSION['email'] = $inputEmail;
$_SESSION['firstname'] = $inputFirstname;

header('Location: ../index.php?alert=editAccountSuccessful');
die();



