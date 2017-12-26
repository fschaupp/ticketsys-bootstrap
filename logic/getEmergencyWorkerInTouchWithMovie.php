<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 21.08.2017
 * Time: 15:23
 */

include('ifNotLoggedInRedirectToIndex.php');

$UMID = $_REQUEST['UMID'];

if(!isset($UMID) OR empty($UMID)) {
    header('Location: ../index.php?alertReason=getWorkerInTouchWithMovie_isset_UMID');
    die();
}

if(!isset($conn)) {
    include 'connectToDatabase.php';
}

foreach ($conn->query('SELECT name FROM movies WHERE UMID=' . $UMID . ';') as $item) {
    $movieName = $item[0];
}

$sql = 'UPDATE movies SET emergencyWorkerUUID=' . $_SESSION['UUID'] . ' WHERE UMID=' . $UMID . ';';
$conn->exec($sql);

header('Location: ../index.php?alertReason=getWorkerInTouchWithMovie_successful&movieName='.$movieName);
die();
