<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 09.08.2017
 * Time: 17:18
 */

include('../ifNotLoggedInRedirectToIndex.php');
include('../ifNotEnoughPermissionRedirectToIndex.php');

$UUID = $_REQUEST['UUID'];

if(!isset($UUID) OR empty($UUID)) {
    header('Location: /userManagement.php?alertReason=deleteUser_isset_UUID');
    die();
} else {
    if(!is_numeric($UUID)) {
        header('Location: /index.php?alertReason=deleteUser_isset_UUID');
        die();
    }
}

if(!isset($conn)) {
    include "../connectToDatabase.php";
}


$stmt = $conn->prepare('SELECT firstname, lastname FROM users WHERE UUID = :UUID;');
$stmt->bindParam(':UUID', $_SESSION['UUID']);
$stmt->execute();

while($row = $stmt->fetch()) {
    $userName = $row[0] . ' ' . $row[1];
    break;
}

$stmt = $conn->prepare('DELETE FROM users WHERE UUID = :UUID;');
$stmt->bindParam(':UUID', $_SESSION['UUID']);
$stmt->execute();

header('Location: /userManagement.php?alertReason=deleteUser_successful&userName='.$userName);
die();