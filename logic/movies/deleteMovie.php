<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 09.08.2017
 * Time: 17:18
 */

include('../ifNotLoggedInRedirectToIndex.php');
include('../ifNotEnoughPermissionRedirectToIndex.php');

$UMID = $_REQUEST['UMID'];

if(!isset($UMID) OR empty($UMID)) {
    header('Location: /movieManagement.php?alertReason=deleteMovie_isset_UMID');
    die();
} else {
    if(!is_numeric($UMID)) {
        header('Location: /index.php?alertReason=deleteMovie_isset_UMID');
        die();
    }
}

if(!isset($conn)) {
    include "../connectToDatabase.php";
}

$stmt = $conn->prepare('SELECT name FROM movies WHERE UMID = :UMID;');
$stmt->bindParam(':UMID', $UMID);
$stmt->execute();

while($row = $stmt->fetch()) {
    $movieName = $row[0];
    break;
}

$stmt = $conn->prepare('DELETE FROM movies WHERE UMID = :UMID;');
$stmt->bindParam(':UMID', $UMID);
$stmt->execute();

header('Location: /movieManagement.php?alertReason=deleteMovie_successful&movieName='.$movieName);
die();