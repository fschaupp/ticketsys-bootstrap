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
$inputRank = $_REQUEST['inputeRank'];

if(!isset($UUID) OR empty($UUID)) {
    header('Location: ../userManagement.php?alert=inputIsNotCorrect');
    die();
}
if(!isset($inputRank) OR empty($inputRank)) {
    header('Location: ../userManagement.php?alert=inputIsNotCorrect');
    die();
}

if(!isset($conn)) {
    include "./connectToDatabase.php";
}

$sql = 'UPDATE users SET rank="'.$inputRank.'" WHERE UUID='.$UUID.';';

$conn->exec($sql);

//TODO: Add Alert editRankSuccessfulEdited
header('Location: ../userManagement.php?alert=editRankSuccessful');
die();



