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
}

if(!isset($conn)) {
    include '../connectToDatabase.php';
}

foreach ($conn->query('SELECT UBID FROM bookings WHERE UMID='. $UMID . ' AND UUID='. $_SESSION['UUID'] .';') as $item) {
    $UBID = $item[0];
}

if(isset($UBID)) {
    if($UBID != null) {
        foreach ($conn->query('SELECT count FROM bookings WHERE UBID=' . $UBID . ';') as $item) {
            $count = $item[0];
        }

        $sql = 'DELETE FROM bookings WHERE UBID=' . $UBID . ' AND UUID=' . $_SESSION['UUID'] . ';';
        $conn->exec($sql);

        foreach ($conn->query('SELECT bookedCards, name FROM movies WHERE UMID=' . $UMID . ';') as $item) {
            $bookedCards = $item[0];
            $movieName = $item[1];
        }

        if(isset($bookedCards) AND isset($count)) {
            $newBookedCards = $bookedCards - $count;

            $sql = 'UPDATE movies SET bookedCards=' . $newBookedCards . ' WHERE UMID='.$UMID.';';
            $conn->exec($sql);

            header('Location: /index.php?alertReason=cancelBookedCards_successful&movieName='.$movieName);
        }
    } else {
        header('Location: /index.php?alertReason=cancelBookedCards_booking_does_not_exist');
    }
} else {
    header('Location: /index.php?alertReason=cancelBookedCards_booking_does_not_exist');
}

die();