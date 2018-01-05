<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 20.08.2017
 * Time: 15:13
 */

include('../ifNotLoggedInRedirectToIndex.php');

$UMID = $_REQUEST['UMID'];
$inputCount = $_REQUEST['inputCount'];

if(!isset($UMID) OR empty($UMID)) {
    header('Location: /index.php?alertReason=bookCards_isset_UMID');
    die();
} else {
    if(!is_numeric($UMID)) {
        header('Location: /index.php?alertReason=bookCards_isset_UMID');
        die();
    }
}

if(!isset($inputCount) OR empty($inputCount)) {
    header('Location: /index.php?alertReason=bookCards_isset_inputCount');
    die();
} else {
    if(!is_numeric($inputCount)) {
        header('Location: /index.php?alertReason=bookCards_is_numeric_inputCount');
        die();
    }
}

$filter_options = array(
    'options' => array(
        'min_range' => 1,
        'max_range' => 20)
);

if(!(filter_var($inputCount, FILTER_VALIDATE_INT, $filter_options ) !== FALSE)) {
    header('Location: /index.php?alertReason=bookCards_between_1_20');
    die();
}

if(!isset($conn)) {
    include '../connectToDatabase.php';
}

$stmt = $conn->prepare('SELECT bookedCards, name FROM movies WHERE UMID = :UMID;');
$stmt->bindParam(':UMID', $UMID);
$stmt->execute();

while($row = $stmt->fetch()) {
    $alreadyBookedCards = $row[0];
    $movieName = $row[1];
    break;
}

if(isset($alreadyBookedCards)) {
    if (($alreadyBookedCards + $inputCount) > 20) {
        header('Location: /index.php?alertReason=bookCards_booked_more_Cards_than_available');
        die();
    }
} else {
    $alreadyBookedCards = 0;
}

$newBookedCards = $alreadyBookedCards + $inputCount;

$stmt = $conn->prepare('UPDATE movies SET bookedCards = :bookedCards WHERE UMID = :UMID;');
$stmt->bindParam(':bookedCards', $newBookedCards);
$stmt->bindParam(':UMID', $UMID);
$stmt->execute();

//Checking if user already booked cards, if yes the old will be added to the new and deleted
$oldCount = 0;

$stmt = $conn->prepare('SELECT UBID, count FROM bookings WHERE UMID = :UMID AND UUID = :UUID;');
$stmt->bindParam(':UMID', $UMID);
$stmt->bindParam(':UUID', $_SESSION['UUID']);
$stmt->execute();

while($row = $stmt->fetch()) {
    $UBID = $row[0];
    $oldCount = $row[1];
    break;
}

if(isset($UBID) AND $UBID != null) {
    $inputCount += $oldCount;

    $stmt = $conn->prepare('DELETE FROM bookings WHERE UBID = :UBID;');
    $stmt->bindParam(':UBID', $UBID);
    $stmt->execute();
}

$stmt = $conn->prepare('INSERT INTO bookings (UMID, UUID, count) VALUE (:UMID, :UUID, :count);');
$stmt->bindParam(':UMID', $UMID);
$stmt->bindParam(':UUID', $_SESSION['UUID']);
$stmt->bindParam(':count', $inputCount);
$stmt->execute();

header('Location: /index.php?alertReason=bookCards_successful&bookedCards='.$inputCount.'&movieName='.$movieName);
die();