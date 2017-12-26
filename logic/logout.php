<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 04.08.17
 * Time: 17:29
 */

if(!isset($_SESSION)) {
    session_start();
}
session_destroy();

header('Location:../index.php?alert=successfulLoggedOut');
die();