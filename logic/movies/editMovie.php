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

str_replace("ß", "&szlig;", $inputMoviename);
str_replace("ä", "&auml;", $inputMoviename);
str_replace("Ä", "&Auml;", $inputMoviename);
str_replace("ü", "&uuml;", $inputMoviename);
str_replace("Ü", "&Uuml;", $inputMoviename);
str_replace("ö", "&ouml;", $inputMoviename);
str_replace("Ö", "&Ouml;", $inputMoviename);

$sql = 'UPDATE movies SET name="'.$inputMoviename.'", date="'.$inputDate.'", trailerLink="'.$inputTrailerlink.'" WHERE UMID='. $UMID.';';

$conn->exec($sql);

header('Location: /movieManagement.php?alertReason=editMovie_successful&movieName='.$inputMoviename);
die();



