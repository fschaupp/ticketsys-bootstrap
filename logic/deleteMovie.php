<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 09.08.2017
 * Time: 17:18
 */

include('ifNotLoggedInRedirectToIndex.php');
include('ifNotEnoughPermissionRedirectToIndex.php');

$UMID = $_REQUEST['UMID'];

if(!isset($UMID) OR empty($UMID)) {
    header('Location: ../movieManagement.php?alert=errorWhichIsImpossible');
    die();
}

if(!isset($conn)) {
    include "./connectToDatabase.php";
}

foreach ($conn->query('SELECT name FROM movies WHERE UMID=' . $UMID . ';') as $item) {
    $movieName = $item[0];
}

$conn->query('DELETE FROM movies WHERE UMID='.$UMID);

header('Location: ../movieManagement.php?alert=successfulDeletedMovie&movieName='.$movieName);
die();


