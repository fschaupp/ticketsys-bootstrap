<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 06.08.17
 * Time: 18:00
 */

include('ifNotLoggedInRedirectToIndex.php');
include('ifNotEnoughPermissionRedirectToIndex.php');

$UUID = $_REQUEST['UUID'];
$inputPassword = $_REQUEST['inputePassword'];
$inputPassword_again = $_REQUEST['inputePassword_again'];

if(!isset($UUID) OR empty($UUID)) {
    header('Location: ../userManagement.php?alert=errorWhichIsImpossible');
    die();
}
if(!isset($inputPassword) OR empty($inputPassword)) {
    header('Location: ../userManagement.php?alert=errorWhichIsImpossible');
    die();
}
if(!isset($inputPassword_again) OR empty($inputPassword_again)) {
    header('Location: ../userManagement.php?alert=errorWhichIsImpossible');
    die();
}

if($inputPassword != $inputPassword_again) {
    header('Location: ../userManagement.php?alert=passwordsAreNotEqual');
    die();
}

if(strlen($inputPassword) > 512) {
    header('Location: ../userManagement.php?alert=passwordOnly512Characters');
    die();
}

$hashed_password = hash('sha512', $inputPassword);

if(!isset($conn)) {
    include "./connectToDatabase.php";
}

foreach ($conn->query('SELECT firstname, lastname FROM users WHERE UUID=' . $UUID . ';') as $item) {
    $userName = $item[0] . ' ' . $item[1];
}

$sql = 'UPDATE users SET password="'.$hashed_password.'" WHERE UUID='.$UUID.';';

$conn->exec($sql);

header('Location: ../userManagement.php?alert=successfulEditedUserPassword&userName='.$userName);
die();



