<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 20.08.2017
 * Time: 15:13
 */

include('ifNotLoggedInRedirectToIndex.php');

$UMID = $_REQUEST['UMID'];
$inputCount = $_REQUEST['inputCount'];

if(!isset($UMID) OR empty($UMID)) {
    header('Location: ../index.php?alert=errorWhichIsImpossible');
    die();
}

if(!isset($inputCount) OR empty($inputCount)) {
    header('Location: ../index.php?alert=errorWhichIsImpossible');
    die();
}

if(!is_numeric ($inputCount)) {
    header('Location: ../index.php?alert=errorWhichIsImpossible');
    die();
}

$filter_options = array(
    'options' => array(
        'min_range' => 1,
        'max_range' => 20)
);

if(!(filter_var($inputCount, FILTER_VALIDATE_INT, $filter_options ) !== FALSE)) {
    header('Location: ../index.php?alert=pleaseEnterAValidNumber');
    die();
}

if(!isset($conn)) {
    include 'connectToDatabase.php';
}

foreach ($conn->query('SELECT bookedCards, name FROM movies WHERE UMID='.$UMID.';') as $item) {
    $alreadyBookedCards = $item[0];
    $movieName = $item[1];
    break;
}

if(isset($alreadyBookedCards)) {
    if (($alreadyBookedCards + $inputCount) > 20) {
        header('Location: ../index.php?alert=bookedTooManyCards');
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

header('Location: ../index.php?alert=successfulBookedCards&count='.$inputCount.'&movieName='.$movieName);
die();


