<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 08.08.2017
 * Time: 18:08
 */

include('ifNotLoggedInRedirectToIndex.php');
include('ifNotEnoughPermissionRedirectToIndex.php');

$inputMoviename = $_REQUEST['inputcMoviename'];
$inputDate = $_REQUEST['inputcDate'];
$inputTrailer = $_REQUEST['inputcTrailerlink'];

if(!isset($inputMoviename) OR empty($inputMoviename)) {
    header('Location: ../movieManagement.php?alert=errorWhichIsImpossible');
    die();
}
if(!isset($inputDate) OR empty($inputDate)) {
    header('Location: ../movieManagement.php?alert=errorWhichIsImpossible');
    die();
}
if(!isset($inputTrailer) OR empty($inputTrailer)) {
    header('Location: ../movieManagement.php?alert=errorWhichIsImpossible');
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

header('Location: ../movieManagement.php?alert=successfulCreatedMovie&movieName='.$inputMoviename);
die();