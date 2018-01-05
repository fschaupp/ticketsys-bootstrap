<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 21.08.2017
 * Time: 16:09
 */

include('../ifNotLoggedInRedirectToIndex.php');

$UMID = $_REQUEST['UMID'];

if(!isset($UMID) OR empty($UMID)) {
    header('Location: /index.php?alertReason=cancelWorkerGetInTouchWithMovie_isset_UMID');
    die();
} else {
    if(!is_numeric($UMID)) {
        header('Location: /index.php?alertReason=cancelWorkerGetInTouchWithMovie_isset_UMID');
        die();
    }
}

if(!isset($conn)) {
    include '../connectToDatabase.php';
}

$stmt = $conn->prepare('SELECT name FROM movies WHERE UMID = :UMID;');
$stmt->bindParam(':UMID', $UMID);
$stmt->execute();

while($row = $stmt->fetch()) {
    $movieName = $row[0];
    break;
}

$stmt = $conn->prepare('UPDATE movies SET emergencyWorkerUUID = NULL WHERE UMID = :UMID AND emergencyWorkerUUID = :UUID;');
$stmt->bindParam(':UMID', $UMID);
$stmt->bindParam(':UUID', $_SESSION['UUID']);
$stmt->execute();

header('Location: /index.php?alertReason=cancelWorkerGetInTouchWithMovie_successful&movieName='.$movieName);
die();