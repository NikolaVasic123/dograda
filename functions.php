<?php

$dbhost = "localhost";
$dbname = "dograda";
$dbuser = "dgadmin";
$dbpassword = "dgadmin1";

// Objekat konekcije ka bazi
$connection = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);
if($connection->connect_error != null) {
    die($connection->connect_error);
}

// Funkcija za izvrsavanje proizvoljnih upita u bazu podataka
function queryMysql($query) {
    global $connection;
    $result = $connection->query($query);
    if(!$result) {
        die($connection->error);
    }
    return $result;
}

// Funkcija za izvrsavanje CREATE TABLE upita
function createTable($name, $query) {
    queryMysql("CREATE TABLE IF NOT EXISTS $name($query)");
    echo "Table '$name' created or already exists.<br>";
}

// Funkcija za tretiraje inputa iz forme
function sanitizeString($text)
{
    $text = strip_tags($text);
    $text = htmlentities($text);
    $text = stripslashes($text);
    global $connection;
    $text = $connection->real_escape_string($text);
    return $text;
}



?>