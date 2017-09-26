<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 22.08.2017
 * Time: 11:33
 */

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['email'])) {
    header('Location: ../index.php?alert=loginFirst');
    die();
}

$UMID = $_REQUEST['UMID'];

if(!isset($UMID) OR empty($UMID)) {
    header('Location: ../index.php?alert=inputIsNotCorrect');
    die();
}

if(!isset($conn)) {
    include './connectToDatabase.php';
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

        foreach ($conn->query('SELECT bookedCards FROM movies WHERE UMID=' . $UMID . ';') as $item) {
            $bookedCards = $item[0];
        }

        if(isset($bookedCards) AND isset($count)) {
            $newBookedCards = $bookedCards - $count;

            $sql = 'UPDATE movies SET bookedCards=' . $newBookedCards . ' WHERE UMID='.$UMID.';';
            $conn->exec($sql);

            //TODO: Add alert successfulCancelledBooking
            header('Location: ../index.php?alert=successfulCancelledBooking');
        }
    } else {
        header('Location: ../index.php?alert=thereIsNotBookingForThisFilm');
    }
} else {
    header('Location: ../index.php?alert=thereIsNotBookingForThisFilm');
}

die();