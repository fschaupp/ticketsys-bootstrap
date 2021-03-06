<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 06.08.17
 * Time: 18:00
 */

include('../ifNotLoggedInRedirectToIndex.php');
include('../ifNotEnoughPermissionRedirectToIndex.php');

$UMID = $_REQUEST['UMID'];
$inputMoviename = $_REQUEST['inputeMoviename'];
$inputDate = $_REQUEST['inputeDate'];
$inputTrailerlink = $_REQUEST['inputeTrailerlink'];

if(!isset($UMID) OR empty($UMID)) {
    header('Location: /movieManagement.php?alertReason=editMovie_isset_UMID');
    die();
} else {
    if(!is_numeric($UMID)) {
        header('Location: /index.php?alertReason=editMovie_isset_UMID');
        die();
    }
}

if(!isset($inputMoviename) OR empty($inputMoviename)) {
    header('Location: /movieManagement.php?alertReason=editMovie_isset_moviename');
    die();
}
if(!isset($inputDate) OR empty($inputDate)) {
    header('Location: /movieManagement.php?alertReason=editMovie_isset_date');
    die();
}
if(!isset($inputTrailerlink) OR empty($inputTrailerlink)) {
    header('Location: /movieManagement.php?alertReason=editMovie_isset_trailer');
    die();
}

if(!isset($conn)) {
    include "../connectToDatabase.php";
}

$stmt = $conn->prepare('UPDATE movies SET name = :name, date = :date, trailerLink = :trailer WHERE UMID = :UMID;');
$stmt->bindParam(':UMID', $UMID);
$stmt->bindParam(':name', $inputMoviename);
$stmt->bindParam(':date', $inputDate);
$stmt->bindParam(':trailer', $inputTrailerlink);
$stmt->execute();

header('Location: /movieManagement.php?alertReason=editMovie_successful&movieName='.$inputMoviename);
die();