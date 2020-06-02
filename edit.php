<?php
include "db.php";
$dbh = new PDO($dsn, $user, $pass, $options);
session_start();

//Select Posts
$query = 'SELECT   
                                Posts.id as postId, 
                                Posts.user_id as postUserId, 
                                Posts.title,
                                Posts.body, 
                                Posts.created_at,
                                Users.id,
                                Users.name
                                FROM Posts 
                                INNER JOIN Users ON Users.id = Posts.user_id
                                WHERE Posts.id=:id';
$sth = $dbh->prepare($query);
$sth->execute([':id' => trim($_GET['post'])]);
$post = $sth->fetch();
// var_dump($post);





if (isset($_POST['edit-post'])) {
    //Sanitize data
    $title = htmlspecialchars(trim($_POST['title']));
    $body = htmlspecialchars(trim($_POST['content']));
    //Update POST
    $query = '  UPDATE 
                    Posts 
                SET 
                    title = :title, 
                    body = :body
                WHERE id = :id';

    $stmt = $dbh->prepare($query);
    $stmt->bindValue(':id', $post['postId'], PDO::PARAM_INT);
    $stmt->bindValue(':title', $title, PDO::PARAM_STR);
    $stmt->bindValue(':body', $body, PDO::PARAM_STR);
    $stmt->execute();

    //Redirect
    header('Location: dashboard.php');
    exit;
}

include "edit.phtml";
