<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 20.08.2017
 * Time: 15:13
 */

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['email'])) {
    header('Location: ../index.php?alert=loginFirst');
    die();
}

$UMID = $_REQUEST['UMID'];
$inputCount = $_REQUEST['inputCount'];

if(!isset($UMID) OR empty($UMID)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
    die();
}

if(!isset($inputCount) OR empty($inputCount)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
    die();
}

if(!isset($conn)) {
    include 'connectToDatabase.php';
}

foreach ($conn->query('SELECT bookedCards FROM movies WHERE UMID='.$UMID.';') as $item) {
    $alreadyBookedCards = $item[0];
    break;
}

if(isset($alreadyBookedCards)) {
    if (($alreadyBookedCards + $inputCount) > 20) {
        //TODO: Add Alert bookedToManyCards
        header('Location: ../index.php?alert=bookedToManyCards');
        die();
    }
} else {
    $alreadyBookedCards = 0;
}

$newBookedCards = $alreadyBookedCards + $inputCount;

$sql = 'UPDATE movies SET bookedCards='.$newBookedCards.' WHERE UMID='.$UMID.';';
$conn->exec($sql);

//Checking if user already booked cards, if yes the old will be added to the new and deleted
$oldCount = 0;
foreach ($conn->query('SELECT UBID, count FROM bookings WHERE UMID='.$UMID.' AND UUID='.$_SESSION['UUID'].';') as $item) {
    $UBID = $item[0];
    $oldCount = $item[1];
    break;
}

if(isset($UBID) AND $UBID != null) {
    $inputCount += $oldCount;
    $sql = 'DELETE FROM bookings WHERE UBID='.$UBID.';';
    $conn->exec($sql);
}

$sql = 'INSERT INTO bookings (UMID, UUID, count) VALUE ('.$UMID.', '.$_SESSION['UUID'].', '.$inputCount.');';
$conn->exec($sql);

//TODO: Add Alert successfulBookedCards UND count
header('Location: ../index.php?alert=successfulBookedCards?count='.$inputCount);
die();


