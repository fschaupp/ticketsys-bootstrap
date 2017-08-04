<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 04.08.17
 * Time: 16:20
 */

session_start();

if(isset($_SESSION['UUID'])) {
    //TODO: Add alert alreadyLoggedIn
    header('Location: ../index.php?alert=alreadyLoggedIn');
}

$inputFirstname = $_REQUEST['inputFirstname'];
$inputSurname = $_REQUEST['inputSurname'];
$inputEmail = $_REQUEST['inputEmail'];
$inputPassword = $_REQUEST['inputPassword'];
$inputPassword_again = $_REQUEST['inputPassword_again'];

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
if(!isset($inputPassword) OR empty($inputPassword)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
    die();
}
if(!isset($inputPassword_again) OR empty($inputPassword_again)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
    die();
}

if($inputPassword != $inputPassword_again) {
    //TODO: Add alert passwordsAreNotEqual
    header('Location: ../index.php?alert=passwordsAreNotEqual');
    die();
}

$hashed_password = hash('sha512', $inputPassword);

include 'connectToDatabase.php';

$conn->query('
  INSERT INTO users(firstname, surname, password, email, rank, isActivated)
    VALUES ("' . $inputFirstname . '" , "' . $inputSurname . '" , "' . $hashed_password . '", "' . $inputEmail . '", "user", 1)
');

//TODO: Add alert registrationSuccessful
header('Location: ../index.php?alert=registrationSuccessful');
die();