<?php
/**
 * Created by PhpStorm.
 * User: webcat
 * Date: 25.12.17
 * Time: 23:24
 */

if($_SESSION['rank'] != "administrator") {
    header('Location: ../index.php?alert=permissionDenied');
    die();
}