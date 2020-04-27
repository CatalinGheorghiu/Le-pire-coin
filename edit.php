<?php
include "db.php";
$dbh = new PDO($dsn, $user, $pass, $options);
session_start();
include "header.phtml";

//Select Posts
$query = 'SELECT   
                                Posts.id as postId, 
                                Posts.user_id as postUserId, 
                                Posts.title,
                                Posts.body, 
                                Posts.img,
                                Posts.created_at,
                                Users.id,
                                Users.name
                                FROM Posts 
                                INNER JOIN Users ON Users.id = Posts.user_id
                                WHERE Posts.id=:id';
$sth = $dbh->prepare($query);
$sth->execute([':id' =>  $_GET['post']]);
$post = $sth->fetch();
// var_dump($post);

include "edit.phtml";



if (isset($_POST)) {
    //Update POST
    $query = 'UPDATE Posts SET title = :title, body = :body WHERE id = :id';
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(':id', $post['postId'], PDO::PARAM_STR);
    $stmt->bindValue(':title', $_POST['title'], PDO::PARAM_STR);
    $stmt->bindValue(':body', $_POST['content'], PDO::PARAM_STR);
    $stmt->execute();

    //Redirect
    header('Location: dashboard.php');
    exit;
}


include "footer.phtml";
