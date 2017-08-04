<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Tickets</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.min.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">BKE Ticketsystem</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <?php
                session_start();

                if(isset($_SESSION['UUID'])) {
                    if($_SESSION['rank'] == "administrator") {
                        echo '
                            <ul class="nav navbar-nav">
                                <li><a href="#">Filmverwaltung</a></li>
                                <li><a href="#">Userverwaltung</a></li>
                            </ul>
                        ';
                    }
                }
            ?>
            <div class="navbar-form navbar-left">
                <button  class="btn btn-info" data-toggle="modal" data-target="#modal_cardsinfo">Karten abholen!?</button>
            </div>
            <form class="navbar-form navbar-left">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Film suchen">
                </div>
                <button type="submit" class="btn btn-default">Suchen</button>
            </form>
            <div class="navbar-form navbar-right">
                <?php
                    session_start();

                    if(!isset($_SESSION['UUID']) OR empty($_SESSION['UUID'])) {
                        echo '
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal_login">Anmelden</button>
                            <button class="btn btn-info" data-toggle="modal" data-target="#modal_registration">Registrieren</button>
                        ';
                    } else {
                        echo '
                            Hallo, '.$_SESSION['firstname'].'
                            <a href="./logic/logout.php">
                                <button type="submit" class="btn btn-danger">Abmelden</button>
                            </a>
                        ';
                    }
                ?>
            </div>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<!-- Modal -->
<div class="modal fade" id="modal_login" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Melde dich bei dem BKE Tickersystem an</h4>
            </div>
            <form action="./logic/login.php" method="GET">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputEmail_l">Email-Adresse:</label>
                        <input required type="email" class="form-control" id="inputEmail_l" name="inputEmail">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword_l">Passwort:</label>
                        <input required type="password" class="form-control" id="inputPassword_l" name="inputPassword">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-success">Anmelden</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_registration" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Registriere dich bei dem BKE Tickersystem</h4>
            </div>
            <form action="./logic/register.php" method="GET">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputFirstname">Vorname:</label>
                        <input required type="text" class="form-control" id="inputFirstname" name="inputFirstname">
                    </div>
                    <div class="form-group">
                        <label for="inputSurname">Nachname:</label>
                        <input required type="text" class="form-control" id="inputSurname" name="inputSurname">
                    </div>
                    <div class="form-group">
                        <label for="inputEmail">Email-Adresse:</label>
                        <input required type="email" class="form-control" id="inputEmail" name="inputEmail">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Passwort:</label>
                        <input required type="password" class="form-control" id="inputPassword" name="inputPassword">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword_again">Passwort wiederholen:</label>
                        <input required type="password" class="form-control" id="inputPassword_again" name="inputPassword_again">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-success">Regestrieren</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_cardsinfo" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Karten abholen? Wann und wo?</h4>
            </div>

            <div class="modal-body">
                <div class="panel panel-primary">
                    <div class="panel-heading">Wann hole ich meine frisch bestellten Karten?</div>
                    <div class="panel-body">Sobald du Karten bestellt hast und der große Tag gekommen ist holst
                        du die Karten einfach um 20:45 (UTC+01:00) ab.
                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">Und wo hole ich die Karten wenn ich schon pünktlich sein muss?</div>
                    <div class="panel-body">Ganz einfach, warte einfach vor dem Eisentor der Kanzlerwiese.
                        Damit du auch ganz sicher hinfindest, hier die Koordinaten:
                        <a target="_blank" href="https://www.google.at/maps/place/48%C2%B038'38.1%22N+15%C2%B048'52.8%22E/@48.643908,15.8124843,17z/data=!3m1!4b1!4m5!3m4!1s0x0:0x0!8m2!3d48.643908!4d15.814673">
                            48.643908, 15.814673
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="http://lmgtfy.com/?q=Lesen+lernen" target="_blank">
                    <button class="btn btn-warning">Ich kann nicht lesen</button>
                </a>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Verstanden oder zumindest durchgelesen</button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
</body>
</html>