<?php

if (!isset($conn)) {
    include "./logic/connectToDatabase.php";
}

if (!isset($_SESSION)) {
    session_start();
}

$loggedIn = false;
if (isset($_SESSION['email'])) {
    $loggedIn = true;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Tickets</title>

    <?php include 'header.php'; ?>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="mycontainer">
    <?php
    $row_count = 0;

    foreach ($conn->query('SELECT UMID, name, date, trailerLink, workerUUID, bookedCards FROM movies ORDER BY date') as $item) {
        $UMID = $item[0];
        $movieName = $item[1];
        $movieDate = $item[2];
        $movieTrailerLink = $item[3];
        $workerUUID = $item[4];
        $bookedCards = $item[5];

        $workerName = null;
        $movieHasWorker = false;

        $movieAlreadyShowed = false;

        $availableCards = 20 - $bookedCards;

        $currentDate = DateTime::createFromFormat('Y-m-d H:i:s', $movieDate);
        $formattedDate = $currentDate->format('D, d M Y');

        $currentDate_minus_eins = date('Y-m-d H:i:s', strtotime($currentDate_minus_eins . ' -1 day'));
        $del = "";
        $delend = "";

        if (isset($item[4])) {
            foreach ($conn->query('SELECT firstname, surname, UUID FROM users WHERE UUID=' . $workerUUID) as $users) {
                $workerName = $users[0] . ' ' . $users[1];
                $movieHasWorker = true;
                break;
            }
        }

        if ($currentDate_minus_eins > $item['date']) {
            $del = "<del>";
            $delend = "</del>";

            $movieAlreadyShowed = true;
        }

        if ($row_count == 0) {
            echo '<div class="row">';
        }

        echo '
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <div class="panel panel-default">
                            <div class="panel-heading" style="font-size: 20px">' . $del . $movieName . $delend . '</div>
                            <div class="panel-body">
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">Datum:</div>
                                            <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">' . $formattedDate . '</div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">Trailer:</div>
                                            <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">
                                                <a href="' . $movieTrailerLink . '" target="_blank" 
                                                    data-toggle="tooltip" data-placement="top" title="Link zum Trailer">
                                                    <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-new-window" aria-label="Öffnen"></span></button>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">Kinodienst:</div>
                ';

        //If movie has NO worker, you should be possible to get in touch and if you aren't logged in you see that there is no worker
        if (!$movieHasWorker) {
            echo '
                                            <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">
                    ';

            if ($loggedIn) {
                if ($movieAlreadyShowed) {
                    echo '
                                <button class="btn btn-info disabled" data-toggle="tooltip" data-placement="bottom"
                                    title="Der Film wurde bereits gezeigt!">Melden</button>';
                } else {
                    echo '<a href="#modal_getInTouch" data-toggle="tooltip" data-placement="bottom" title="Melde dich für den Kinodienst">
                                <button class="btn btn-info" data-toggle="modal" data-target="#modal_getInTouch" data-movie-id="' . $item[0] . '" data-movie-name="' . $item[1] . '"
                                >Melden</button></a>';
                }
            } else {
                echo 'Nicht eingeteilt';
            }
            echo '
                                            </div>
                    ';
            //If movie has worker the is displayed and if you are logged in and the worker is you you will cancel it
        } else {
            echo '
                                            <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">'
                . $workerName;

            if ($loggedIn) {
                if ($workerUUID == $_SESSION['UUID']) {
                    echo '
                                                <a href="#modal_cancelGetInTouch" 
                                                    data-toggle="tooltip" data-placement="right" title="Entferne dich vom Kinodienst">
                                                    <button class="btn btn-default" 
                                                        data-toggle="modal" data-target="#modal_cancelGetInTouch" 
                                                        data-movie-id="' . $UMID . '" data-movie-name="' . $movieName . '">
                                                            
                                                        <span class="glyphicon glyphicon-remove"></span>
                                                    </button>
                                                </a>';
                }

            }
            echo '
                                            </div>
                    ';
        }

        echo '
                                        </div>
                                    </li>
                ';
        if (!$movieAlreadyShowed) {
            echo '
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">Freie Karten:</div>
                                            <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">' . $availableCards . '</div>
                                        </div>
                                    </li>
                    ';
        }

        if ($loggedIn) {
            $userHasBookedForThisMovie = false;
            foreach ($conn->query('SELECT UBID, count FROM bookings WHERE UUID=' . $_SESSION['UUID'] . ' AND UMID=' . $item[0] . ';') as $bookings) {
                $UBID = $bookings[0];
                $userBookedCardsCount = $bookings[1];
                $userHasBookedForThisMovie = true;
                break;
            }

            if ($userHasBookedForThisMovie && isset($userBookedCardsCount) && !$movieAlreadyShowed)
                echo '
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">Geb. Karten:</div>
                                            <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">' . $userBookedCardsCount . '</div>
                                        </div>
                                    </li>        
                        ';
        }


        echo '
                                </ul>
                            </div>
                            <div class="panel-footer">
                ';

        if ($loggedIn) {
            if ($availableCards == 0) {
                echo '<button class="btn btn-success disabled" data-toggle="tooltip" data-placement="bottom"
                                    title="Es sind keine freien Karten zur Reservierung verfügbar">Ausgebucht</button>';
            } else if ($movieAlreadyShowed) {
                echo '<button class="btn btn-success disabled" data-toggle="tooltip" data-placement="bottom"
                                    title="Der Film wurde bereits gezeigt!">Nicht reservierbar</button>';
            } else {
                echo '<a href="#modal_bookCards"  class="btn btn-success" data-toggle="modal" data-target="#modal_bookCards" 
                        data-movie-id="' . $item[0] . '" data-availablecards="' . $availableCards . '">Reservieren</a>';
            }

            if (isset($userHasBookedForThisMovie)) {
                if ($userHasBookedForThisMovie) {
                    echo '<a href="#modal_cancelBookedCards" class="btn btn-warning" data-toggle="modal" data-target="#modal_cancelBookedCards" 
                                    data-movie-id="' . $item[0] . '" data-movie-name="' . $item[1] . '" style="float: right">Buchung stornieren</a>';
                    $UBID = null;
                }
            }

        } else {
            echo '<a href="#" data-toggle="tooltip" data-placement="bottom" title="Du bist noch nicht angemeldet">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal_login">Reservieren</button></a>';
        }

        echo ' 
                            </div>
                        </div>
                    </div>
                ';

        if ($row_count == 3) {
            echo '</div>';
            $row_count = 0;
        } else {
            $row_count++;
        }
    }

    //If there aren't enough movies for a row, the foreach would be interrupted and the last div wouldn't be ended
    if ($row_count != 3) {
        echo '</div>';
    }

    ?>
</div>

<div class="modal fade" id="modal_bookCards" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal_bookCards_availableCardsHEAD"></h4>
            </div>
            <form action="./logic/bookCards.php" method="GET">
                <div class="modal-body">
                    <input type="text" name="UMID" hidden value=""/>
                    <div class="alert alert-info">
                        <strong>Info!</strong> Karten sind in erster Linie für Vereinsmitglieder. Sollten kurz vor
                        Kinobeginn noch Karten frei sein, können diese jederzeit an Freunde
                        oder Familienmitglieder weitergeben werden!
                    </div>
                    <div class="form-group">
                        <label for="inputCount">Kartenanzahl:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-play"></i></span>
                            <input required type="text" class="form-control" id="inputCount" name="inputCount"
                                   placeholder="1" min="0" max="20"
                                   onchange="checkValue()" autofocus>
                        </div>
                    </div>

                    <div id="maxinput" hidden></div>

                    <b id="modal_bookCards_availableCardsDIV"></b>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-success" autofocus>Reservieren</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_getInTouch">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal_getInTouch_movieNameHEAD"></h4>
            </div>
            <form method="GET" action="./logic/getInTouchWithMovie.php">
                <div class="modal-body">
                    <input type="text" name="UMID" hidden value=""/>
                    <div class="alert alert-info">
                        <strong>Info!</strong> Du kannst diesen Schritt jederzeit rückgängig machen!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-success">Melden</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_cancelGetInTouch">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal_cancelGetInTouch_movieNameHEAD"></h4>
            </div>
            <form method="GET" action="./logic/cancelGetInTouchWithMovie.php">
                <input type="text" name="UMID" hidden value=""/>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-success" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-danger">Abmelden</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_cancelBookedCards">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal_cancelBooking_movieNameHEAD"></h4>
            </div>
            <form method="GET" action="./logic/cancelBookedCards.php">
                <div class="modal-body">
                    <input type="text" name="UMID" hidden value="">
                    <div class="alert alert-info">
                        <strong>Info!</strong> Wenn du mehrere Karten reserviert hast, werden diese auch storniert!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-success" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-danger">Stornieren</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'endScripts.php'; ?>

<script>
    $('#modal_bookCards').on('show.bs.modal', function (e) {
        var movieId = $(e.relatedTarget).data('movie-id');
        $(e.currentTarget).find('input[name="UMID"]').val(movieId);

        var avcards = $(e.relatedTarget).data('availablecards');

        document.getElementById('modal_bookCards_availableCardsDIV').innerText = "Freie Karten: " + avcards;
        document.getElementById('modal_bookCards_availableCardsHEAD').innerText = "Reserviere Karten - Freie Karten: " + avcards;

        document.getElementById('inputCount').setAttribute('max', "" + avcards);
        document.getElementById('maxinput').innerText = "" + avcards;
    });

    $('#modal_getInTouch').on('show.bs.modal', function (e) {
        var movieId = $(e.relatedTarget).data('movie-id');
        $(e.currentTarget).find('input[name="UMID"]').val(movieId);

        var movieName = $(e.relatedTarget).data('movie-name');
        document.getElementById('modal_getInTouch_movieNameHEAD').innerText = "Willst du dich wirklich für " + movieName + " melden?";
    });

    $('#modal_cancelGetInTouch').on('show.bs.modal', function (e) {
        var movieId = $(e.relatedTarget).data('movie-id');
        $(e.currentTarget).find('input[name="UMID"]').val(movieId);

        var movieName = $(e.relatedTarget).data('movie-name');
        document.getElementById('modal_cancelGetInTouch_movieNameHEAD').innerText = "Willst du dich wirklich für " + movieName + " abmelden?";
    });

    $('#modal_cancelBookedCards').on('show.bs.modal', function (e) {
        var movieId = $(e.relatedTarget).data('movie-id');
        $(e.currentTarget).find('input[name="UMID"]').val(movieId);

        var movieName = $(e.relatedTarget).data('movie-name');
        document.getElementById('modal_cancelBooking_movieNameHEAD').innerText = "Willst du die Reservierungen für " + movieName + " stornieren?";
    });

    function checkValue() {
        var max = parseInt(document.getElementById('maxinput').innerText);
        var value = document.getElementById("inputCount").value;
        if (value > max) {
            document.getElementById("inputCount").value = max;
        }
        if (value < 1) {
            document.getElementById("inputCount").value = 1;
        }
    }
</script>
</body>
</html>