<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 24.08.2017
 * Time: 15:58
 */

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['email'])) {
    header('Location: ./index.php?alert=loginFirst');
    die();
}

if (!isset($conn)) {
    include "./logic/connectToDatabase.php";
}

$isWorker = false;
foreach ($conn->query('SELECT UMID FROM movies WHERE workerUUID=' . $_SESSION['UUID']) as $item) {
    $isWorker = true;
    break;
}
if (!$isWorker) {
    //TODO: Add alert youAreNotAWorker
    header('Location: ./index.php?alert=youAreNotAWorker');
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Tickets - Kinodienst</title>

    <?php include 'header.php'; ?>
</head>
<body>

<?php include 'navbar.php'; ?>
<div class="container">
    <h1>Kinodienst</h1>
    <p>Hier findest du alle Reservierungen in welchen du Kinodienst hast.</p>

    <?php

    foreach ($conn->query('SELECT UMID, name, date FROM movies WHERE workerUUID=' . $_SESSION['UUID'] . ' ORDER BY date;') as $movies) {
        $UMID = $movies[0];
        $movieName = $movies[1];
        $movieDate = $movies[2];

        $date_minus_eins = date('Y-m-d H:i:s', strtotime($date_minus_eins . ' -1 day'));
        $del = "";
        $delend = "";
        if ($date_minus_eins > $movieDate) {
            $del = "<del>";
            $delend = "</del>";
        }

        $bookingsExists = false;
        foreach ($conn->query('SELECT users.firstname FROM users, bookings WHERE bookings.UMID=' . $UMID . ' 
                                            AND bookings.UUID=users.UUID;') as $bookings) {
            $bookingsExists = true;
        }

        echo '<div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="font-size: 20px">' . $del . $movieName . $delend . '</div>
                        <div class="panel-body">
            ';

        if ($bookingsExists) {
            echo '
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Kartenanzahl</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                ';

            foreach ($conn->query('SELECT users.firstname, users.surname, bookings.count FROM users, bookings WHERE bookings.UMID=' . $movies[0] . ' AND bookings.UUID=users.UUID;') as $bookings) {
                $userName = $bookings[0] . ' ' . $bookings[1];
                $bookedCards = $bookings[2];

                echo '
                                        <tr>
                                            <td>' . $userName . '</td>
                                            <td>' . $bookedCards . '</td>
                                        </tr>
                    ';
            }

            echo '
                                    </tbody>
                                </table>
                            </div>
                ';
        } else {
            echo 'Ohh... Derzeit exestieren noch keine Reservierung für deine Filme! :(';
        }

        echo '
                        </div>
                    </div>
                </div>
              </div>';
    }


    ?>

</div>
<?php include 'endScripts.php'; ?>

<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
</body>
</html>


