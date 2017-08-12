<!DOCTYPE html>
<html lang="en">
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

    <?php
    if(!isset($conn)) {
        include "./logic/connectToDatabase.php";
    }

    foreach ($conn->query('SELECT UUID, email, firstname, surname FROM users') as $item) {

    }
    ?>

    <?php include 'endScripts.php'; ?>
</body>
</html>