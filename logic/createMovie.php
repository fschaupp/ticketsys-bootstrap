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

str_replace("ß", "&szlig;", $inputMoviename);
str_replace("ä", "&auml;", $inputMoviename);
str_replace("Ä", "&Auml;", $inputMoviename);
str_replace("ü", "&uuml;", $inputMoviename);
str_replace("Ü", "&Uuml;", $inputMoviename);
str_replace("ö", "&ouml;", $inputMoviename);
str_replace("Ö", "&Ouml;", $inputMoviename);

$conn->query('
  INSERT INTO movies(name, date, trailerLink, bookedCards)
    VALUES ("' . $inputMoviename . '" , "' . $inputDate . '" , "' . $inputTrailer . '", 0)
');

//TODO: Add alert movieSuccessfulCreated
header('Location: ../movieManagement.php?alert=movieSuccessfulCreated');
die();