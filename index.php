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

include ('./languages/german.php');
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $string_title_first . $string_title_home; ?></title>

    <?php include 'header.php'; ?>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="mycontainer">
    <?php
    include ('./logic/alertSwitch.php');

    $row_count = 0;

    foreach ($conn->query('SELECT UMID, name, date, trailerLink, workerUUID, bookedCards, emergencyWorkerUUID FROM movies ORDER BY date') as $item) {
        $UMID = $item[0];
        $movieName = $item[1];
        $movieDate = $item[2];
        $movieTrailerLink = $item[3];
        $workerUUID = $item[4];
        $bookedCards = $item[5];
        $emergencyWorkerUUID = $item[6];

        $workerName = null;
        $movieHasWorker = false;
        $emergencyWorkerName = null;
        $movieHasEmergencyWorker = false;

        $movieAlreadyShowed = false;

        $availableCards = 20 - $bookedCards;

        $currentDate = DateTime::createFromFormat('Y-m-d H:i:s', $movieDate);
        $formattedDate = $currentDate->format('D, d M Y');

        $currentDate_minus_eins = date('Y-m-d H:i:s', strtotime($currentDate_minus_eins . ' -1 day'));
        $del = "";
        $delend = "";

        if (isset($workerUUID)) {
            foreach ($conn->query('SELECT firstname, surname, UUID FROM users WHERE UUID=' . $workerUUID) as $users) {
                $workerName = $users[0] . ' ' . $users[1];
                $movieHasWorker = true;
                break;
            }
        }

        if (isset($emergencyWorkerUUID)) {
            foreach ($conn->query('SELECT firstname, surname, UUID FROM users WHERE UUID=' . $emergencyWorkerUUID) as $users) {
                $emergencyWorkerName = $users[0] . ' ' . $users[1];
                $movieHasEmergencyWorker = true;
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
                                            <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">'.$string_home_date.'</div>
                                            <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">' . $formattedDate . '</div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">'.$string_home_trailer.'</div>
                                            <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">
                                                <a href="' . $movieTrailerLink . '" target="_blank" 
                                                    data-toggle="tooltip" data-placement="top" title="'.$string_home_trailer_button_tooltip.'">
                                                    <button type="button" class="btn btn-primary">
                                                        <span class="glyphicon glyphicon-new-window"></span>
                                                    </button>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">'.$string_home_cardService.'</div>
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
                                    title="'.$string_home_cardService_button_tooltip_already_showed.'">'.$string_home_cardService_button_text.'</button>';
                } else {
                    echo '<a href="#modal_getNormalWorkerInTouch" data-toggle="tooltip" data-placement="bottom" 
                                title="'.$string_home_cardService_button_tooltip.'">
                            <button class="btn btn-info" data-toggle="modal" data-target="#modal_getNormalWorkerInTouch"
                                data-movie-id="' . $item[0] . '" data-movie-name="' . $item[1] . '">'.$string_home_cardService_button_text.'</button></a>';
                }
            } else {
                echo $string_home_cardService_no_one_assigned;
            }
            echo '
                                            </div>
                    ';
            //If movie has worker the name is displayed and if you are logged in and the worker is you you can cancel it
        } else {
            echo '
                                            <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">'
                . $workerName;

            if ($loggedIn) {
                if ($workerUUID == $_SESSION['UUID']) {
                    echo '
                                                <a href="#modal_cancelNormalWorkerGetInTouchWithMovie" 
                                                    data-toggle="tooltip" data-placement="right" title="'.$string_home_cardService_remove_button_tooltip.'">
                                                    <button class="btn btn-default" data-toggle="modal" 
                                                        data-target="#modal_cancelNormalWorkerGetInTouchWithMovie" 
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
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">'.$string_home_emergencyCardService.'</div>
                ';

        //If movie has NO emergency Worker, you should be possible to get in touch and if you aren't logged in you see that there is no worker
        if (!$movieHasEmergencyWorker) {
            echo '
                                            <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">
                    ';

            if ($loggedIn) {
                if ($movieAlreadyShowed) {
                    echo '
                                <button class="btn btn-info disabled" data-toggle="tooltip" data-placement="bottom"
                                    title="'.$string_home_cardService_button_tooltip_already_showed.'">'.$string_home_cardService_button_text.'</button>';
                } else {
                    echo '<a href="#modal_getEmergencyWorkerInTouch" data-toggle="tooltip" data-placement="bottom" 
                                title="'.$string_home_emergencyCardService_button_tooltip.'">
                            <button class="btn btn-info" data-toggle="modal" data-target="#modal_getEmergencyWorkerInTouch"
                                data-movie-id="' . $item[0] . '" data-movie-name="' . $item[1] . '">'.$string_home_cardService_button_text.'
                            </button>
                          </a>';
                }
            } else {
                echo $string_home_cardService_no_one_assigned;
            }
            echo '
                                            </div>
                    ';
            //If movie has worker the is displayed and if you are logged in and the worker is you you will cancel it
        } else {
            echo '
                                            <div class="col-xs-6 col-sm-6 col-md-7 col-lg-7">'
                . $emergencyWorkerName;

            if ($loggedIn) {
                if ($emergencyWorkerUUID == $_SESSION['UUID']) {
                    echo '
                                                <a href="#modal_cancelEmergencyWorkerGetInTouchWithMovie" 
                                                        data-toggle="tooltip" data-placement="right" 
                                                        title="'.$string_home_cardService_remove_button_tooltip.'">
                                                    <button class="btn btn-default" data-toggle="modal" 
                                                        data-target="#modal_cancelEmergencyWorkerGetInTouchWithMovie" 
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
                                            <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">'.$string_home_available_cards.'</div>
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
                                            <div class="col-xs-6 col-sm-6 col-md-5 col-lg-5">'.$string_home_booked_cards.'</div>
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
                echo '<button class="btn btn-danger disabled" data-toggle="tooltip" data-placement="bottom"
                                    title="'.$string_home_booking_fully_booked_tooltip.'">'.$string_home_booking_fully_booked.'</button>';
            } else if ($movieAlreadyShowed) {
                echo '<button class="btn btn-danger disabled" data-toggle="tooltip" data-placement="bottom"
                                    title="'.$string_home_booking_already_been_shown_tooltip.'">'.$string_home_booking_already_been_shown.'</button>';
            } else {
                echo '<a href="#modal_bookCards"  class="btn btn-success" data-toggle="modal" data-target="#modal_bookCards" 
                        data-movie-id="' . $item[0] . '" data-availablecards="' . $availableCards . '">'.$string_home_booking.'</a>';
            }

            if (isset($userHasBookedForThisMovie)) {
                if ($userHasBookedForThisMovie) {
                    echo '<a href="#modal_cancelBookedCards" class="btn btn-warning" data-toggle="modal" 
                                data-target="#modal_cancelBookedCards" data-movie-id="' . $item[0] . '" 
                                data-movie-name="' . $item[1] . '" style="float: right">'.$string_home_cancel_booking.'</a>';
                    $UBID = null;
                }
            }

        } else {
            echo '<a href="#" data-toggle="tooltip" data-placement="bottom" title="'.$string_home_booking_tooltip.'">
                    <button type="button" class="btn btn-success" data-toggle="modal" 
                        data-target="#modal_login">'.$string_home_booking.'</button>
                  </a>';
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
            <form action="./logic/booking/bookCards.php" method="POST">
                <div class="modal-body">
                    <input type="text" name="UMID" hidden value=""/>
                    <div class="alert alert-info">
                        <?php echo $string_home_modal_book_cards_alert; ?>
                    </div>
                    <div class="form-group">
                        <label for="inputCount"><?php echo $string_home_modal_book_cards_number_of_cards; ?></label>
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
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $string_cancel; ?></button>
                    <button type="submit" class="btn btn-success" autofocus><?php echo $string_home_modal_book_cards_button_success; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_getNormalWorkerInTouch">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal_getNormalWorkerInTouch_movieNameHEAD"></h4>
            </div>
            <form method="POST" action="./logic/worker/getNormalWorkerInTouchWithMovie.php">
                <div class="modal-body">
                    <input type="text" name="UMID" hidden value=""/>
                    <div class="alert alert-info">
                        <?php echo $string_home_modal_get_in_touch_alert; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger" data-dismiss="modal"><?php echo $string_cancel; ?></button>
                    <button type="submit" class="btn btn-success"><?php echo $string_home_modal_get_in_touch_button_success; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_getEmergencyWorkerInTouch">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal_getEmergencyWorkerInTouch_movieNameHEAD"></h4>
            </div>
            <form method="POST" action="./logic/worker/getEmergencyWorkerInTouchWithMovie.php">
                <div class="modal-body">
                    <input type="text" name="UMID" hidden value=""/>
                    <div class="alert alert-info">
                        <?php echo $string_home_modal_get_in_touch_alert; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger" data-dismiss="modal"><?php echo $string_cancel; ?></button>
                    <button type="submit" class="btn btn-success"><?php echo $string_home_modal_get_in_touch_button_success; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_cancelNormalWorkerGetInTouchWithMovie">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal_cancelNormalWorkerGetInTouch_movieNameHEAD"></h4>
            </div>
            <form method="POST" action="./logic/worker/cancelNormalWorkerGetInTouchWithMovie.php">
                <input type="text" name="UMID" hidden value=""/>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-success" data-dismiss="modal"><?php echo $string_cancel; ?></button>
                    <button type="submit" class="btn btn-danger"><?php echo $string_home_modal_cancel_get_in_touch_button_success; ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_cancelEmergencyWorkerGetInTouchWithMovie">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal_cancelEmergencyWorkerGetInTouch_movieNameHEAD"></h4>
            </div>
            <form method="POST" action="./logic/worker/cancelEmergencyWorkerGetInTouchWithMovie.php">
                <input type="text" name="UMID" hidden value=""/>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-success" data-dismiss="modal"><?php echo $string_cancel; ?></button>
                    <button type="submit" class="btn btn-danger"><?php echo $string_home_modal_cancel_get_in_touch_button_success; ?></button>
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
            <form method="POST" action="./logic/booking/cancelBookedCards.php">
                <div class="modal-body">
                    <input type="text" name="UMID" hidden value="">
                    <div class="alert alert-info">
                        <?php echo $string_home_modal_cancel_booked_cards_alert; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-success" data-dismiss="modal"><?php echo $string_cancel; ?></button>
                    <button type="submit" class="btn btn-danger"><?php echo $string_home_modal_cancel_booked_cards_button_success; ?></button>
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

        document.getElementById('modal_bookCards_availableCardsDIV').innerText = "<?php echo $string_home_modal_book_cards_available_cards; ?>" + avcards;
        document.getElementById('modal_bookCards_availableCardsHEAD').innerText = "<?php echo $string_home_modal_book_cards_title; ?>" + avcards;

        document.getElementById('inputCount').setAttribute('max', "" + avcards);
        document.getElementById('maxinput').innerText = "" + avcards;
    });

    $('#modal_getNormalWorkerInTouch').on('show.bs.modal', function (e) {
        var movieId = $(e.relatedTarget).data('movie-id');
        $(e.currentTarget).find('input[name="UMID"]').val(movieId);

        var movieName = $(e.relatedTarget).data('movie-name');
        document.getElementById('modal_getNormalWorkerInTouch_movieNameHEAD').innerText =
            "<?php echo $string_home_modal_get_in_touch_title_1; ?>" + movieName + "<?php echo $string_home_modal_get_in_touch_title_2; ?>";
    });

    $('#modal_getEmergencyWorkerInTouch').on('show.bs.modal', function (e) {
        var movieId = $(e.relatedTarget).data('movie-id');
        $(e.currentTarget).find('input[name="UMID"]').val(movieId);

        var movieName = $(e.relatedTarget).data('movie-name');
        document.getElementById('modal_getEmergencyWorkerInTouch_movieNameHEAD').innerText =
            "<?php echo $string_home_modal_get_in_touch_title_1; ?>" + movieName + "<?php echo $string_home_modal_get_in_touch_title_2; ?>";
    });

    $('#modal_cancelNormalWorkerGetInTouchWithMovie').on('show.bs.modal', function (e) {
        var movieId = $(e.relatedTarget).data('movie-id');
        $(e.currentTarget).find('input[name="UMID"]').val(movieId);

        var movieName = $(e.relatedTarget).data('movie-name');
        document.getElementById('modal_cancelNormalWorkerGetInTouch_movieNameHEAD').innerText =
            "<?php echo $string_home_modal_cancel_get_in_touch_title_1; ?>" + movieName + "<?php echo $string_home_modal_cancel_get_in_touch_title_2; ?>";
    });

    $('#modal_cancelEmergencyWorkerGetInTouchWithMovie').on('show.bs.modal', function (e) {
        var movieId = $(e.relatedTarget).data('movie-id');
        $(e.currentTarget).find('input[name="UMID"]').val(movieId);

        var movieName = $(e.relatedTarget).data('movie-name');
        document.getElementById('modal_cancelEmergencyWorkerGetInTouch_movieNameHEAD').innerText =
            "<?php echo $string_home_modal_cancel_get_in_touch_title_1; ?>" + movieName + "<?php echo $string_home_modal_cancel_get_in_touch_title_2; ?>";
    });

    $('#modal_cancelBookedCards').on('show.bs.modal', function (e) {
        var movieId = $(e.relatedTarget).data('movie-id');
        $(e.currentTarget).find('input[name="UMID"]').val(movieId);

        var movieName = $(e.relatedTarget).data('movie-name');
        document.getElementById('modal_cancelBooking_movieNameHEAD').innerText =
            "<?php echo $string_home_modal_cancel_booked_cards_title_1; ?>" + movieName + "<?php echo $string_home_modal_cancel_booked_cards_title_2; ?>";
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