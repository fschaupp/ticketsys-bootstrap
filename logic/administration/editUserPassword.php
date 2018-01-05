<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 06.08.17
 * Time: 18:00
 */

include('../ifNotLoggedInRedirectToIndex.php');
include('../ifNotEnoughPermissionRedirectToIndex.php');

$UUID = $_REQUEST['UUID'];
$inputPassword = $_REQUEST['inputePassword'];
$inputPassword_again = $_REQUEST['inputePassword_again'];

if(!isset($UUID) OR empty($UUID)) {
    header('Location: /userManagement.php?alertReason=editUserPassword_isset_UUID');
    die();
} else {
    if(!is_numeric($UUID)) {
        header('Location: /index.php?alertReason=editUserPassword_isset_UUID');
        die();
    }
}

if(!isset($inputPassword) OR empty($inputPassword)) {
    header('Location: /userManagement.php?alertReason=editUserPassword_isset_password');
    die();
}
if(!isset($inputPassword_again) OR empty($inputPassword_again)) {
    header('Location: /userManagement.php?alertReason=editUserPassword_isset_password_again');
    die();
}

if($inputPassword != $inputPassword_again) {
    header('Location: /userManagement.php?alertReason=editUserPassword_passwords_are_not_equal');
    die();
}

if(strlen($inputPassword) > 512) {
    header('Location: /userManagement.php?alertReason=editUserPassword_passwords_only_512_characters');
    die();
}

$hashed_password = hash('sha512', $inputPassword);

if(!isset($conn)) {
    include "../connectToDatabase.php";
}

$stmt = $conn->prepare('SELECT firstname, lastname FROM users WHERE UUID = :UUID;');
$stmt->bindParam(':UUID', $UUID);
$stmt->execute();

while($row = $stmt->fetch()) {
    $userName = $row[0] . ' ' . $row[1];
    break;
}

$stmt = $conn->prepare('UPDATE users SET password = :password WHERE UUID = :UUID;');
$stmt->bindParam(':UUID', $UUID);
$stmt->bindParam(':password', $hashed_password);
$stmt->execute();

header('Location: /userManagement.php?alertReason=editUserPassword_successful&userName='.$userName);
die();