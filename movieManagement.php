<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['email'])) {
    header('Location: ../index.php?alert=loginFirst');
    die();
} else {
    if ($_SESSION['rank'] != "administrator") {
        header('Location: ../index.php?alert=permissionDenied');
        die();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Tickets - Movie management</title>

    <?php include 'header.php'; ?>
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container">
    <h1>Alle Filme</h1>
    <button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#modal_createMovie"
            style="margin-top: -30px">Film anlegen
    </button>
    <p>Hier findest du alle gespeicherten Filme</p>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Filmname</th>
                <th>Datum</th>
                <th>Trailer</th>
                <th>Arbeiter</th>
                <th>Aktion</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if (!isset($conn)) {
                include "./logic/connectToDatabase.php";
            }

            foreach ($conn->query('SELECT UMID, name, date, trailerLink, workerID FROM movies ORDER BY date') as $item) {
                $workerName = "Nicht eingeteilt";
                if (isset($item[4])) {
                    foreach ($conn->query('SELECT firstname, surname, UUID FROM users WHERE UUID=' . $item[4]) as $users) {
                        $workerName = $users[0] . ' ' . $users[1];
                        break;
                    }
                }

                $del = "";
                $delend = "";
                if (date("Y-m-d H:i:s") > $item[2]) {
                    $del = "<del>";
                    $delend = "</del>";
                }

                $date = DateTime::createFromFormat('Y-m-d H:i:s', $item[2]);
                $formattedDate = $date->format('D, d M Y');

                echo '<tr>';

                echo '<td>' . $del . $item[1] . $delend . '</td>';
                echo '<td>' . $del . $formattedDate . $delend . '</td>';
                echo '<td><a href="'. $item[3] . '" target="_blank" style="margin-right: 5px;"><button type="button" class="btn btn-primary">Trailer</button></a>' . $del . $item[3] . $delend . '</td>';
                echo '<td>' . $del . $workerName . $delend . '</td>';
                echo '<td>
                        <a href="#editMovie" data-toggle="modal" data-target="#modal_editMovie" class="btn btn-success btn-md" data-movie-id="'.$item[0].'" data-movie-name="'.$item[1].'"
                                data-movie-trailer="'.$item[3].'">
                            <span class="glyphicon glyphicon-pencil"></span> Bearbeiten
                        </a>
                                        
                        <a href="#deleteMovie" data-toggle="modal" data-target="#modal_deleteMovie" class="btn btn-danger btn-md" data-movie-id="' . $item[0] . '">
                            <span class="glyphicon glyphicon-trash"></span> Löschen</a>
                      </td>';

                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal_createMovie" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Lege einen neuen Film an:</h4>
            </div>
            <form action="./logic/createMovie.php" method="GET">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="inputcMoviename">Filmname:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-film"></i></span>
                            <input required type="text" class="form-control" id="inputcMoviename" name="inputcMoviename" placeholder="Filmname">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputcDate">Datum:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input required type="date" class="form-control" id="inputcDate" name="inputcDate">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputcTrailerlink">Trailer:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
                            <input required type="url" class="form-control" id="inputcTrailerlink" name="inputcTrailerlink" placeholder="https://www.youtube.com/watch?v=C0Mx4vKMGx4">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-success">Anlegen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_editMovie" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Film bearbeiten:</h4>
            </div>
            <form action="./logic/editMovie.php" method="GET">
                <div class="modal-body">
                    <input type="text" name="UMID" hidden value=""/>

                    <div class="form-group">
                        <label for="inputeMoviename">Filmname:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-film"></i></span>
                            <input required type="text" class="form-control" id="inputeMoviename" name="inputeMoviename" value=""/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputeDate">Datum:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            <input required type="date" class="form-control" id="inputeDate" name="inputeDate"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputeTrailerlink">Trailer:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-link"></i></span>
                            <input required type="url" class="form-control" id="inputeTrailerlink" name="inputeTrailerlink" value=""/>
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

<div class="modal fade" id="modal_deleteMovie">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Willst du den Film wirklich löschen?</h4>
            </div>
            <form method="GET" action="./logic/deleteMovie.php">
                <input type="text" name="UMID" hidden value=""/>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger" data-dismiss="modal">Abbrechen</button>
                    <button type="delete" class="btn btn-success" name="edit">Löschen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'endScripts.php'; ?>
<script>
    $('#modal_deleteMovie').on('show.bs.modal', function (e) {
        var movieId = $(e.relatedTarget).data('movie-id');
        $(e.currentTarget).find('input[name="UMID"]').val(movieId);
    });
    $('#modal_editMovie').on('show.bs.modal', function (e) {
        var movieId = $(e.relatedTarget).data('movie-id');
        $(e.currentTarget).find('input[name="UMID"]').val(movieId);

        var name = $(e.relatedTarget).data('movie-name');
        $(e.currentTarget).find('input[name="inputeMoviename"]').val(name);

        var trailer = $(e.relatedTarget).data('movie-trailer');
        $(e.currentTarget).find('input[name="inputeTrailerlink"]').val(trailer);
    });
</script>
</body>
</html>