<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "Le_pire_coin";

//Set DSN
$dsn = 'mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8';
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
);
