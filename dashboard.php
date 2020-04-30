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

//Select User Name
$query = 'SELECT name FROM Users WHERE id= :id';
$stmt = $dbh->prepare($query);
$stmt->bindValue(':id', trim($_SESSION['logged']), PDO::PARAM_STR);
$stmt->execute();
$usernameSession = $stmt->fetch();
// var_dump($usernameSession);


// //Select Posts
$query = 'SELECT *  FROM Posts WHERE user_id =:id  ORDER BY Posts.created_at DESC';
$stmt = $dbh->prepare($query);
$stmt->bindValue(':id', trim($_SESSION['logged']), PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();
// var_dump($posts);


$query = 'SELECT Posts.id, Posts.title, Posts.body, Posts.created_at ,Images.img_url
                FROM Posts
                INNER JOIN Images ON Posts.id = Images.post_id 
                GROUP BY Posts.id';
$stmt = $dbh->prepare($query);
// $stmt->bindValue(':id', trim($_SESSION['logged']), PDO::PARAM_INT);
$stmt->execute();
$images = $stmt->fetchAll();
// var_dump($images);





$query = 'SELECT * FROM Images WHERE post_id IN (SELECT id FROM Posts WHERE user_id = :id)';
$stmt = $dbh->prepare($query);
$stmt->bindValue(':id', trim($_SESSION['logged']), PDO::PARAM_INT);
$stmt->execute();
$imgs = $stmt->fetchAll();

// var_dump($imgs);







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
