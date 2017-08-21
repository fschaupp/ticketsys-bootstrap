<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 21.08.2017
 * Time: 16:09
 */

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['email'])) {
    header('Location: ../index.php?alert=loginFirst');
    die();
}

$UMID = $_REQUEST['UMID'];

if(!isset($UMID) OR empty($UMID)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
    die();
}

if(!isset($conn)) {
    include 'connectToDatabase.php';
}

$sql='UPDATE movies SET workerID=NULL WHERE UMID='.$UMID.';';
$conn->exec($sql);

//TODO: Add Alert successfulCancelGetInTouch
header('Location: ../index.php?alert=successfulCancelGetInTouch');
die();

