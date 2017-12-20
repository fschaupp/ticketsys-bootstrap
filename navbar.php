<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 06.08.17
 * Time: 18:25
 */

if(!isset($conn)) {
    include './logic/connectToDatabase.php';
}

if(!isset($_SESSION)) {
    session_start();
}

$loggedIn = false;
if(isset($_SESSION['email'])) {
    $loggedIn = true;
}
?>

<nav class="navbar navbar-default navbar-fixed-top" style="margin-right: 0px;">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">BKE Ticketsystem</a>
            <a class="navbar-brand" href="#" data-toggle="tooltip" data-placement="bottom" title="Es kann zu Bugs, Datenverlusten und Offline-Zeiten kommen!">
                <span class="label label-warning">BETA</span>
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <?php
                if($loggedIn) {
                    echo '
                        <ul class="nav navbar-nav">
                    ';

                    foreach ($conn->query('SELECT UMID FROM movies WHERE workerUUID='.$_SESSION['UUID']) as $item) {
                        echo '<li><a href="./cardService.php">Kinodienst</a></li>';
                        break;
                    }

                    echo '</ul>';

                    if($_SESSION['rank'] == "administrator") {
                        echo '
                            <ul class="nav navbar-nav">
                                <li class="dropdown">
                                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administration 
                                    <span class="caret"></span></a>
                                  <ul class="dropdown-menu">
                                    <li><a href="./movieManagement.php">Filmverwaltung</a></li>
                                    <li><a href="./userManagement.php">Benutzerverwaltung</a></li>
                                  </ul>
                                </li>   
                            </ul>
                        ';
                    }
                }
            ?>
            <div class="navbar-form navbar-left">
                <button  class="btn btn-info" data-toggle="modal" data-target="#modal_cardsinfo">Karten abholen!?</button>
            </div>
            <div class="navbar-form navbar-right">
                <?php
                    if($loggedIn) {
                        echo '
                            Hallo, '.$_SESSION['firstname'].'
                            <a href="#" data-toggle="tooltip" data-placement="bottom" title="Angaben editieren">
                            <button class="btn btn-default" data-toggle="modal" data-target="#modal_editAccount">
                                <span class="glyphicon glyphicon-pencil"></span>
                            </button>
                            </a>
                            <a href="./logic/logout.php">
                                <button type="submit" class="btn btn-danger" data-toggle="tooltip" data-placement="bottom" title="Abmelden"><span class="glyphicon glyphicon-log-out"></span></button>
                            </a>
                        ';
                    } else {
                        echo '
                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal_login">Anmelden</button>
                            <button class="btn btn-success" data-toggle="modal" data-target="#modal_registration">Registrieren</button>
                        ';
                    }
                ?>
            </div>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>

<div class="navbar navbar-default navbar-fixed-bottom">
    <div class="container">
        <p class="navbar-text pull-left">© Dominik Dafert 2017</p>

        <p class="navbar-text pull-right"><a href="#" data-toggle="modal" data-target="#modal_help" >Hilfe</a></p>
    </div>
</div>


<!-- Modal -->
<?php
    if($loggedIn) {
        foreach ($conn->query('SELECT UUID, email, firstname, surname FROM users WHERE UUID='.$_SESSION['UUID']) as $item) {
            $UUID = $item[0];
            $email = $item[1];
            $firstname = $item[2];
            $surname = $item[3];
        }

        echo '
            <div class="modal fade" id="modal_editAccount" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Ändere deine Angaben</h4>
                        </div>
                        <form action="./logic/editAccount.php" method="GET">
                            <div class="modal-body">
                                <div class="alert alert-info">
                                  <strong>Info!</strong> Wenn das "Neues Passwort" Feld leer ist, wird sich dein Passwort nicht ändern.
                                </div>
                                
                                <input type="hidden" id="UUID" name="UUID" value="'.$UUID.'">

                                <div class="form-group">
                                    <label for="inputFirstname">Vorname:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input required type="text" class="form-control" id="inputFirstname" name="inputFirstname" value="'.$firstname.'" autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputSurname">Nachname:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input required type="text" class="form-control" id="inputSurname" name="inputSurname" value="'.$surname.'">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail">Email-Adresse:</label>
                                     <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input required type="email" class="form-control" id="inputEmail" name="inputEmail" value="'.$email.'">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword_current">Derzeitiges Passwort:</label>
                                     <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input required type="password" class="form-control" id="inputPassword_current" name="inputPassword_current" placeholder="Derzeitiges Passwort eingeben">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword">Neues Passwort:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Neues Passwort eingeben">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword_again">Neues Passwort wiederholen:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input type="password" class="form-control" id="inputPassword_again" name="inputPassword_again" placeholder="Neues Passwort wiederholen">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Abbrechen</button>
                                <button type="submit" class="btn btn-success">Speichern</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        ';
    } else {
        echo '
            <div class="modal fade" id="modal_login" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Melde dich bei dem BKE Ticketsystem an</h4>
                        </div>
                        <form action="./logic/login.php" method="GET">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="inputEmail_l">Email-Adresse:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input required type="email" class="form-control" id="inputEmail_l" name="inputEmail" autofocus placeholder="Email-Adresse eingeben">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword_l">Passwort:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input required type="password" class="form-control" id="inputPassword_l" name="inputPassword" placeholder="Passwort eingeben">
                                    </div>
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
                            <h4 class="modal-title">Registriere dich bei dem BKE Ticketsystem</h4>
                        </div>
                        <form action="./logic/register.php" method="GET">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="inputFirstname">Vorname:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input required type="text" class="form-control" id="inputFirstname" name="inputFirstname" placeholder="Vorname eingeben" autofocus>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputSurname">Nachname:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input required type="text" class="form-control" id="inputSurname" name="inputSurname" placeholder="Nachname eingeben">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail">Email-Adresse:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input required type="email" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email-Adresse eingeben">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword">Passwort:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input required type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Passwort eingeben">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputPassword_again">Passwort wiederholen:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input required type="password" class="form-control" id="inputPassword_again" name="inputPassword_again" placeholder="Passwort wiederholen">
                                    </div>
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
        ';
    }
?>

<div class="modal fade" id="modal_cardsinfo" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Karten abholen, wann und wo?</h4>
            </div>

            <div class="modal-body">
                <div class="panel panel-primary">
                    <div class="panel-heading">Wann hole ich meine frisch bestellten Karten?</div>
                    <div class="panel-body">Sobald du Karten bestellt hast und der große Tag gekommen ist holst
                        du die Karten einfach zwischen 20:40 - 20:45 ab.
                    </div>
                </div>

                <div class="panel panel-primary">
                    <div class="panel-heading">Und wo hole ich die Karten wenn ich schon pünktlich sein muss?</div>
                    <div class="panel-body">Ganz einfach, warte vor dem Eisentor der Kanzlerwiese.
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
                <button class="btn btn-primary" data-dismiss="modal">Verstanden oder zumindest durchgelesen</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_help" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Informationen</h4>
            </div>

            <div class="modal-body">
                <div class="panel panel-default">
                    <div class="panel-heading">Mitwirkende</div>

                    <ul class="list-group">
                        <li class="list-group-item">Idee & Entwicklung der ersten Version: <a target="_blank" href="#">Florian Schaupp</a></li>
                        <li class="list-group-item">Aktueller Entwickler: <a target="_blank" href="https://dafnik.me/">Dominik Dafert</a></li>
                    </ul>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Hilfe und Verbesserungsvorschläge</div>

                    <ul class="list-group">
                        <li class="list-group-item">Per email an: <a href="mailto:ticketsys@dafnik.me">ticketsys@dafnik.me</a></li>
                        <li class="list-group-item">Natürlich kann man mir (Dominik Dafert) auch persönlich oder per Message bescheid geben.</li>
                    </ul>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Bibliotheken</div>

                    <ul class="list-group">
                        <li class="list-group-item"><a target="_blank" href="https://getbootstrap.com/">Bootstrapv3</a></li>
                        <li class="list-group-item"><a target="_blank" href="https://jquery.com/">jQuery</a></li>
                    </ul>

                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">Lizenz: GPL-3.0</div>

                    <ul class="list-group">
                        <li class="list-group-item">Version: <?php include "version.php"; echo $version; ?></li>
                        <li class="list-group-item">Source Code: <a target="_blank" href="https://github.com/Dafnik/ticketsys-bootstrap/">GitHub</a></li>
                    </ul>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal">Schließen</button>
            </div>
        </div>
    </div>
</div>