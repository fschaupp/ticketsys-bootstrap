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
    header('Location: ../index.php?alert=loginFirst');
    die();
} else {
    if($_SESSION['rank'] != "administrator") {
        header('Location: ../index.php?alert=permissionDenied');
        die();
    }
}

$UUID = $_REQUEST['UUID'];
$inputPassword = $_REQUEST['inputePassword'];
$inputPassword_again = $_REQUEST['inputePassword_again'];

if(!isset($UUID) OR empty($UUID)) {
    header('Location: ../userManagement.php?alert=inputIsNotCorrect');
    die();
}
if(!isset($inputPassword) OR empty($inputPassword)) {
    header('Location: ../userManagement.php?alert=inputIsNotCorrect');
    die();
}
if(!isset($inputPassword_again) OR empty($inputPassword_again)) {
    header('Location: ../userManagement.php?alert=inputIsNotCorrect');
    die();
}

if($inputPassword != $inputPassword_again) {
    //TODO: Add alert passwordsAreNotEqual
    header('Location: ../userManagement.php?alert=passwordsAreNotEqual');
    die();
}

$hashed_password = hash('sha512', $inputPassword);

if(!isset($conn)) {
    include "./connectToDatabase.php";
}

$sql = 'UPDATE users SET password="'.$hashed_password.'" WHERE UUID='.$UUID.';';

$conn->exec($sql);

//TODO: Add Alert editUserPasswordSuccessfulEdited
header('Location: ../userManagement.php?alert=editUserPasswordSuccessfulEdited');
die();



