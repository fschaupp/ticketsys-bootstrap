<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 22.08.2017
 * Time: 11:33
 */

include('../ifNotLoggedInRedirectToIndex.php');

$UMID = $_REQUEST['UMID'];

if(!isset($UMID) OR empty($UMID)) {
    header('Location: /index.php?alertReason=cancelBookedCards_isset_UMID');
    die();
} else {
    if(!is_numeric($UMID)) {
        header('Location: /index.php?alertReason=cancelBookedCards_isset_UMID');
        die();
    }
}

if(!isset($conn)) {
    include '../connectToDatabase.php';
}

$stmt = $conn->prepare('SELECT UBID, count FROM bookings WHERE UMID = :UMID AND UUID = :UUID;');
$stmt->bindParam(':UMID', $UMID);
$stmt->bindParam(':UUID', $_SESSION['UUID']);
$stmt->execute();

while($row = $stmt->fetch()) {
    $UBID = $row[0];
    $count = $row[1];
    break;
}

if(isset($UBID) AND isset($count)) {
    if($UBID != null) {
        $stmt = $conn->prepare('DELETE FROM bookings WHERE UBID = :UBID;');
        $stmt->bindParam(':UBID', $UBID);
        $stmt->execute();

        $stmt = $conn->prepare('SELECT bookedCards, name FROM movies WHERE UMID = :UMID;');
        $stmt->bindParam(':UMID', $UMID);
        $stmt->execute();


        while($row = $stmt->fetch()) {
            $bookedCards = $row[0];
            $movieName = $row[1];
            break;
        }

        if(isset($bookedCards) AND isset($movieName)) {
            $newBookedCards = $bookedCards - $count;

            $stmt = $conn->prepare('UPDATE movies SET bookedCards = :bookedCards WHERE UMID = :UMID;');
            $stmt->bindParam(':bookedCards', $newBookedCards);
            $stmt->bindParam(':UMID', $UMID);
            $stmt->execute();

            header('Location: /index.php?alertReason=cancelBookedCards_successful&movieName='.$movieName);
        }
    } else {
        header('Location: /index.php?alertReason=cancelBookedCards_booking_does_not_exist');
    }
} else {
    header('Location: /index.php?alertReason=cancelBookedCards_booking_does_not_exist');
}

die();