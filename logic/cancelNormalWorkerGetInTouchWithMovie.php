<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 21.08.2017
 * Time: 16:09
 */

include('ifNotLoggedInRedirectToIndex.php');

$UMID = $_REQUEST['UMID'];

if(!isset($UMID) OR empty($UMID)) {
    header('Location: ../index.php?alertReason=cancelWorkerGetInTouchWithMovie_isset_UMID');
    die();
}

if(!isset($conn)) {
    include 'connectToDatabase.php';
}

foreach ($conn->query('SELECT name FROM movies WHERE UMID=' . $UMID . ';') as $item) {
    $movieName = $item[0];
}

$sql='UPDATE movies SET workerUUID=NULL WHERE UMID='.$UMID.' AND workerUUID='. $_SESSION['UUID'].';';
$conn->exec($sql);

header('Location: ../index.php?alertReason=cancelWorkerGetInTouchWithMovie_successful&movieName='.$movieName);
die();

