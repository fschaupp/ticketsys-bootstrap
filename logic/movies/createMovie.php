<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 08.08.2017
 * Time: 18:08
 */

include('../ifNotLoggedInRedirectToIndex.php');
include('../ifNotEnoughPermissionRedirectToIndex.php');

$inputMoviename = $_REQUEST['inputcMoviename'];
$inputDate = $_REQUEST['inputcDate'];
$inputTrailer = $_REQUEST['inputcTrailerlink'];

if(!isset($inputMoviename) OR empty($inputMoviename)) {
    header('Location: /movieManagement.php?alertReason=createMovie_isset_moviename');
    die();
}
if(!isset($inputDate) OR empty($inputDate)) {
    header('Location: /movieManagement.php?alertReason=createMovie_isset_date');
    die();
}
if(!isset($inputTrailer) OR empty($inputTrailer)) {
    header('Location: /movieManagement.php?alertReason=createMovie_isset_trailer');
    die();
}

if(!isset($conn)) {
    include "../connectToDatabase.php";
}

$stmt = $conn->prepare('INSERT INTO movies(name, date, trailerLink, bookedCards) VALUE (:name, :date, :trailer, 0);');
$stmt->bindParam(':name', $inputMoviename);
$stmt->bindParam(':date', $inputDate);
$stmt->bindParam(':trailer', $inputTrailer);
$stmt->execute();

header('Location: /movieManagement.php?alertReason=createMovie_successful&movieName='.$inputMoviename);
die();