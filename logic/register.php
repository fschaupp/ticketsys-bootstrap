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
    //TODO: Add alert alreadyLoggedIn
    header('Location: ../index.php?alert=alreadyLoggedIn');
    die();
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
if(strlen($inputFirstname) > 128) {
    //TODO: Add Alert firstnameOnly128Characters
    header('Location: ../index.php?alert=firstnameOnly128Characters');
    die();
}

if(!isset($inputSurname) OR empty($inputSurname)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
    die();
}
if(strlen($inputSurname) > 128) {
    //TODO: Add Alert surnameOnly128Characters
    header('Location: ../index.php?alert=surnameOnly128Characters');
    die();
}

if(!isset($inputEmail) OR empty($inputEmail)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
    die();
}
if(strlen($inputEmail) > 128) {
    //TODO: Add Alert emailOnly128Characters
    header('Location: ../index.php?alert=emailOnly128Characters');
    die();
}

if(!isset($inputPassword) OR empty($inputPassword)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
    die();
}
if(strlen($inputPassword) > 512) {
    //TODO: Add Alert passwordOnly128Characters
    header('Location: ../index.php?alert=passwordOnly512Characters');
    die();
}

if(!isset($inputPassword_again) OR empty($inputPassword_again)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
    die();
}

$inputFirstname = preg_replace("/[^a-zA-Z0-9]/", "", $inputFirstname);
$inputSurname = preg_replace("/[^a-zA-Z0-9]/", "", $inputSurname);

if($inputPassword != $inputPassword_again) {
    //TODO: Add alert passwordsAreNotEqual
    header('Location: ../index.php?alert=passwordsAreNotEqual');
    die();
}

$hashed_password = hash('sha512', $inputPassword);

if(!isset($conn)) {
    include "./connectToDatabase.php";
}

foreach ($conn->query('SELECT email FROM users WHERE email="'.$inputEmail.'";') as $item) {
    if($item[0] == $inputEmail) {
        //TODO: Add alert accountAlreadyExists
        header('Location: ../index.php?alert=accountAlreadyExists');
        die();
    }
    break;
}

$conn->query('
  INSERT INTO users(firstname, surname, password, email, rank)
    VALUES ("' . $inputFirstname . '" , "' . $inputSurname . '" , "' . $hashed_password . '", "' . $inputEmail . '", "user")
');

//TODO: Add alert registrationSuccessful
header('Location: ../index.php?alert=registrationSuccessful');
die();