<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 08.08.2017
 * Time: 18:08
 */

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['email'])) {
    header('Location: ../index.php?alert=loginFirst');
    die();
} else {
    if($_SESSION['rank'] != "administrator") {
        //TODO: Add alert permissionDenied
        header('Location: ../index.php?alert=permissionDenied');
        die();
    }
}

$inputMoviename = $_REQUEST['inputcMoviename'];
$inputDate = $_REQUEST['inputcDate'];
$inputTrailer = $_REQUEST['inputcTrailerlink'];

if(!isset($inputMoviename) OR empty($inputMoviename)) {
    //TODO: Add alert inputIsNotCorrect
    header('Location: ../movieManagement.php?alert=inputIsNotCorrect');
    die();
}
if(!isset($inputDate) OR empty($inputDate)) {
    header('Location: ../movieManagement.php?alert=inputIsNotCorrect');
    die();
}
if(!isset($inputTrailer) OR empty($inputTrailer)) {
    header('Location: ../movieManagement.php?alert=inputIsNotCorrect');
    die();
}

if(!isset($conn)) {
    include "./connectToDatabase.php";
}

$conn->query('
  INSERT INTO movies(name, date, trailerLink)
    VALUES ("' . $inputMoviename . '" , "' . $inputDate . '" , "' . $inputTrailer . '")
');

//TODO: Add alert movieSuccessfulCreated
header('Location: ../movieManagement.php?alert=movieSuccessfulCreated');
die();