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
$inputRank = $_REQUEST['inputeRank'];

if(!isset($UUID) OR empty($UUID)) {
    header('Location: /userManagement.php?alertReason=editRank_isset_UUID');
    die();
} else {
    if(!is_numeric($UUID)) {
        header('Location: /index.php?alertReason=editRank_isset_UUID');
        die();
    }
}
if(!isset($inputRank) OR empty($inputRank)) {
    header('Location: /userManagement.php?alertReason=editRank_isset_rank');
    die();
}

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

$stmt = $conn->prepare('UPDATE users SET rank = :rank WHERE UUID = :UUID;');
$stmt->bindParam(':UUID', $UUID);
$stmt->bindParam(':UUID', $inputRank);
$stmt->execute();

header('Location: /userManagement.php?alertReason=editRank_successful&userName='.$userName);
die();