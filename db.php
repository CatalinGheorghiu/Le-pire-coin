<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "Le_pire_coin";

//Set DSN
$dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;
$options = array(
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);


