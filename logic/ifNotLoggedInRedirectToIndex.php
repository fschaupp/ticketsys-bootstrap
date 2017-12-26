<?php
/**
 * Created by PhpStorm.
 * User: webcat
 * Date: 25.12.17
 * Time: 23:14
 */

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['email'])) {
    header('Location: ../index.php?alert=loginFirst');
    die();
}