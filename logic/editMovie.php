<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 06.08.17
 * Time: 18:00
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
$inputMoviename = $_REQUEST['inputeMoviename'];
$inputDate = $_REQUEST['inputeDate'];
$inputTrailerlink = $_REQUEST['inputeTrailerlink'];

if(!isset($UMID) OR empty($UMID)) {
    header('Location: ../movieManagement.php?alert=inputIsNotCorrect');
    die();
}
if(!isset($inputMoviename) OR empty($inputMoviename)) {
    header('Location: ../movieManagement.php?alert=inputIsNotCorrect');
    die();
}
if(!isset($inputDate) OR empty($inputDate)) {
    header('Location: ../movieManagement.php?alert=inputIsNotCorrect');
    die();
}
if(!isset($inputTrailerlink) OR empty($inputTrailerlink)) {
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

$sql = 'UPDATE movies SET name="'.$inputMoviename.'", date="'.$inputDate.'", trailerLink="'.$inputTrailerlink.'" WHERE UMID='. $UMID.';';

$conn->exec($sql);

//TODO: Add Alert movieSuccessfulEdited
header('Location: ../movieManagement.php?alert=movieSuccessfulEdited');
die();



