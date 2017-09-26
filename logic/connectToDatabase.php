<?php
/**
 * Created by PhpStorm.
 * User: dafnik
 * Date: 04.08.17
 * Time: 15:22
 */

$host = 'localhost';
$dbName = 'ticketsys';
$user = 'ticketsys';
$password = 'ticketsys';
$conn = new PDO ("mysql:host=$host;dbname=$dbName", $user, $password);

