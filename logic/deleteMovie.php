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

$UMID = $_REQUEST['UMID'];

if(!isset($UMID) OR empty($UMID)) {
    header('Location: ../movieManagement.php?alert=inputIsNotCorrect');
    die();
}

if(!isset($conn)) {
    include "./connectToDatabase.php";
}

$conn->query('DELETE FROM movies WHERE UMID='.$UMID);

//TODO: Add Alert movieSuccessfulDeleted
header('Location: ../movieManagement.php?alert=movieSuccessfulDeleted');
die();


