<?php
include "db.php";
$dbh = new PDO($dsn, $user, $pass, $options);
//Start session
session_start();

if (!array_key_exists('logged', $_SESSION)) {
    //	Redirect to home page
    header('Location: ./');
    exit;
}
include "header.phtml";

//Select User Name
$query = 'SELECT name,id FROM Users WHERE id= :id';
$stmt = $dbh->prepare($query);
$stmt->bindValue(':id', trim($_SESSION['logged']), PDO::PARAM_STR);
$stmt->execute();
$usernameSession = $stmt->fetch();
// var_dump($usernameSession);


//Select Posts
$query = 'SELECT *  FROM Posts WHERE user_id =:id ORDER BY Posts.created_at DESC';
$stmt = $dbh->prepare($query);
$stmt->bindValue('id', trim($_SESSION['logged']), PDO::PARAM_STR);
$stmt->execute();
$posts = $stmt->fetchAll();
// var_dump($posts);


$userLogged = trim($_SESSION['logged']);
if (isset($_GET['del'])) {
    $query = 'DELETE FROM Posts WHERE id = :id AND user_id = :user_id';
    $sth = $dbh->prepare($query);
    $sth->bindParam(':id', $_GET['del'], PDO::PARAM_STR);
    $sth->bindParam(':user_id', $userLogged, PDO::PARAM_STR);
    $sth->execute();
    //Redirect
    header("Location: dashboard.php");
    exit();
}

include "dashboard.phtml";
include "footer.phtml";
