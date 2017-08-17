<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 09.08.2017
 * Time: 17:18
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

if(!isset($UUID) OR empty($UUID)) {
    //TODO: Add alert inputIsNotCorrect
    header('Location: ../userManagement.php?alert=inputIsNotCorrect');
    die();
}

if(!isset($conn)) {
    include "./connectToDatabase.php";
}

$conn->query('DELETE FROM users WHERE UUID='.$UUID);

//TODO: Add Alert userSuccessfulDeleted
header('Location: ../userManagement.php?alert=userSuccessfulDeleted');
die();


