<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['email'])) {
    header('Location: ./index.php?alert=loginFirst');
    die();
} else {
    if ($_SESSION['rank'] != "administrator") {
        header('Location: ./index.php?alert=permissionDenied');
        die();
    }
}

include ('./languages/german.php');

if (!isset($conn)) {
    include "./logic/connectToDatabase.php";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $string_title_first . $string_title_userManagement; ?></title>

    <?php include 'header.php'; ?>
</head>
<body>
<?php include 'navbar.php'; ?>
<div class="mycontainer">
    <?php
    include ('./logic/alertSwitch.php');
    ?>
    <h1><?php echo $string_userManagement_header; ?></h1>
    <p><?php echo $string_userManagement_subheader; ?> </p>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th><?php echo $string_userManagement_table_header_firstname_and_surname; ?></th>
                <th><?php echo $string_userManagement_table_header_email; ?></th>
                <th><?php echo $string_userManagement_table_header_rank; ?></th>
                <th><?php echo $string_userManagement_table_header_actions; ?></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($conn->query('SELECT UUID, firstname, surname, email, rank FROM users ORDER BY surname') as $item) {
                echo '<tr>';

                echo '<td>'. $item[1] . ' ' . $item[2] .'</td>';
                echo '<td><a href="mailto:'.$item[3].'">'. $item[3] .'</a></td>';
                echo '<td>'. $item[4] .
                        '<a href="#editRank" data-toggle="modal" data-target="#modal_editRank" class="btn btn-default btn-md"
                         data-user-id="'.$item[0].'" style="margin-left: 5px;">
                            <span class="glyphicon glyphicon-pencil"></span>
                         </a>
                      </td>';
                echo '<td>
                        <a href="#editUserPassword" data-toggle="modal" data-target="#modal_editUserPassword" 
                                class="btn btn-warning btn-md" data-user-id="'.$item[0].'">
                            <span class="glyphicon glyphicon-pencil"></span> 
                            '. $string_userManagement_table_body_actions_change_password .'
                        </a>
                                        
                        <a href="#deleteUser" data-toggle="modal" data-target="#modal_deleteUser" 
                                class="btn btn-danger btn-md" data-user-id="' . $item[0] . '">
                            <span class="glyphicon glyphicon-trash"></span> 
                            ' . $string_userManagement_table_body_actions_delete . '
                        </a>
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
                <h4 class="modal-title"><?php echo $string_userManagement_modal_edit_password_title; ?></h4>
            </div>
            <form action="./logic/administration/editUserPassword.php" method="POST">
                <div class="modal-body">
                    <input type="text" name="UUID" hidden value=""/>

                    <div class="form-group">
                        <label for="inputePassword">
                            <?php echo $string_userManagement_modal_edit_password_password; ?>
                        </label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input required type="password" class="form-control" id="inputePassword" name="inputePassword" placeholder="Passwort"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputePassword_again">
                            <?php echo $string_userManagement_modal_edit_password_confirm; ?>
                        </label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input required type="password" class="form-control" id="inputePassword_again" name="inputePassword_again" placeholder="Passwort wiederholen"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $string_cancel; ?></button>
                    <button type="submit" class="btn btn-success"><?php echo $string_userManagement_modal_edit_password_success; ?></button>
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
                <h4 class="modal-title"><?php echo $string_userManagement_modal_edit_rank_title; ?></h4>
            </div>
            <form action="./logic/administration/editRank.php" method="POST">
                <div class="modal-body">
                    <input type="text" name="UUID" hidden value=""/>

                    <div class="form-group">
                        <label for="inputeRank">
                            <?php echo $string_userManagement_modal_edit_rank_rank; ?>
                        </label>
                        <select class="form-control" id="inputeRank" name="inputeRank">
                            <option>user</option>
                            <option>administrator</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $string_cancel; ?></button>
                    <button type="submit" class="btn btn-success"><?php echo $string_userManagement_modal_edit_rank_success; ?></button>
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
                <h4 class="modal-title"><?php echo $string_userManagement_modal_delete_user_title; ?></h4>
            </div>
            <form method="POST" action="./logic/administration/deleteUser.php">
                <input type="text" name="UUID" hidden value=""/>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-danger" data-dismiss="modal"><?php echo $string_cancel; ?></button>
                    <button type="submit" class="btn btn-success"><?php echo $string_userManagement_modal_delete_user_success; ?></button>
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