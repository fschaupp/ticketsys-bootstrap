<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 04.08.17
 * Time: 15:22
 */

$host = 'localhost';
$dbName = 'ticketsys';
$user = 'root';
$password = 'Passwort1234';
$conn = new PDO ("mysql:host=$host;dbname=$dbName;charset=utf8", $user, $password);

$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//Will be added to gitignore
