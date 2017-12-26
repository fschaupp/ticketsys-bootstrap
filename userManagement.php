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

if (!isset($conn)) {
    include "./logic/connectToDatabase.php";
}

include ('./alertSwitch.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Tickets - Benutzerverwaltung</title>

    <?php include 'header.php'; ?>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="mycontainer">
    <?php
    if(isset($alertText) && isset($alertType)) {

        echo '
        <div class="alert alert-'.$alertType.'" role="alert">'.$alertText.'</div>
        ';
    }
    ?>
    <h1>Alle Benutzer</h1>
    <p>Hier findest du alle gespeicherten Benutzer</p>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th>Name</th>
                <th>Email-Adresse</th>
                <th>Rang</th>
                <th>Aktionen</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($conn->query('SELECT UUID, firstname, surname, email, rank FROM users ORDER BY surname') as $item) {
                echo '<tr>';

                echo '<td>'. $item[1] . ' ' . $item[2] .'</td>';
                echo '<td><a href="mailto:'.$item[3].'">'. $item[3] .'</a></td>';
                echo '<td>'. $item[4] .
                        '<a href="#editRank" data-toggle="modal" data-target="#modal_editRank" class="btn btn-default btn-md" data-user-id="'.$item[0].'" 
                            style="margin-left: 5px;">
                            <span class="glyphicon glyphicon-pencil"></span></a>
                      </td>';
                echo '<td>
                        <a href="#editUserPassword" data-toggle="modal" data-target="#modal_editUserPassword" class="btn btn-success btn-md" data-user-id="'.$item[0].'">
                            <span class="glyphicon glyphicon-pencil"></span> Passwort ändern
                        </a>
                                        
                        <a href="#deleteUser" data-toggle="modal" data-target="#modal_deleteUser" class="btn btn-danger btn-md" data-user-id="' . $item[0] . '">
                            <span class="glyphicon glyphicon-trash"></span> Löschen</a>
                      </td>';

                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modal_editUserPassword" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Passwort ändern:</h4>
            </div>
            <form action="./logic/editUserPassword.php" method="GET">
                <div class="modal-body">
                    <input type="text" name="UUID" hidden value=""/>

                    <div class="form-group">
                        <label for="inputePassword">Passwort:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input required type="password" class="form-control" id="inputePassword" name="inputePassword" placeholder="Passwort"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputePassword_again">Passwort bestätigen:</label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input required type="password" class="form-control" id="inputePassword_again" name="inputePassword_again" placeholder="Passwort wiederholen"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-success">Ändern</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_editRank" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Rang ändern:</h4>
            </div>
            <form action="./logic/editRank.php" method="GET">
                <div class="modal-body">
                    <input type="text" name="UUID" hidden value=""/>

                    <div class="form-group">
                        <label for="inputeRank">Rang:</label>
                        <select class="form-control" id="inputeRank" name="inputeRank">
                            <option>user</option>
                            <option>administrator</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-success">Ändern</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_deleteUser">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Willst du diesen Benutzer wirklich löschen?</h4>
            </div>
            <form method="GET" action="./logic/deleteUser.php">
                <input type="text" name="UUID" hidden value=""/>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger" data-dismiss="modal">Abbrechen</button>
                    <button type="submit" class="btn btn-success">Löschen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'endScripts.php'; ?>
<script>
    $('#modal_deleteUser').on('show.bs.modal', function (e) {
        var userId = $(e.relatedTarget).data('user-id');
        $(e.currentTarget).find('input[name="UUID"]').val(userId);
    });
    $('#modal_editRank').on('show.bs.modal', function (e) {
        var userId = $(e.relatedTarget).data('user-id');
        $(e.currentTarget).find('input[name="UUID"]').val(userId);
    });
    $('#modal_editUserPassword').on('show.bs.modal', function (e) {
        var userId = $(e.relatedTarget).data('user-id');
        $(e.currentTarget).find('input[name="UUID"]').val(userId);
    });
</script>
</body>
</html>