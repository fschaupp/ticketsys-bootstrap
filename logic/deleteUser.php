<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 09.08.2017
 * Time: 17:18
 */

include('ifNotLoggedInRedirectToIndex.php');
include('ifNotEnoughPermissionRedirectToIndex.php');

$UUID = $_REQUEST['UUID'];

if(!isset($UUID) OR empty($UUID)) {
    header('Location: ../userManagement.php?alert=errorWhichIsImpossible');
    die();
}

if(!isset($conn)) {
    include "./connectToDatabase.php";
}

foreach ($conn->query('SELECT firstname, lastname FROM users WHERE UUID=' . $UUID . ';') as $item) {
    $userName = $item[0] . ' ' . $item[1];
}

$conn->query('DELETE FROM users WHERE UUID='.$UUID);

header('Location: ../userManagement.php?alert=successfulDeletedUser&userName='.$userName);
die();


