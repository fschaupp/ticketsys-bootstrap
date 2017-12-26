<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 06.08.17
 * Time: 18:00
 */

include('ifNotLoggedInRedirectToIndex.php');
include('ifNotEnoughPermissionRedirectToIndex.php');

$UUID = $_REQUEST['UUID'];
$inputRank = $_REQUEST['inputeRank'];

if(!isset($UUID) OR empty($UUID)) {
    header('Location: ../userManagement.php?alert=errorWhichIsImpossible');
    die();
}
if(!isset($inputRank) OR empty($inputRank)) {
    header('Location: ../userManagement.php?alert=errorWhichIsImpossible');
    die();
}

if(!isset($conn)) {
    include "./connectToDatabase.php";
}

foreach ($conn->query('SELECT firstname, lastname FROM users WHERE UUID=' . $UUID . ';') as $item) {
    $userName = $item[0] . ' ' . $item[1];
}

$sql = 'UPDATE users SET rank="'.$inputRank.'" WHERE UUID='.$UUID.';';

$conn->exec($sql);

header('Location: ../userManagement.php?alert=successfulEditedRank&userName='.$userName);
die();



