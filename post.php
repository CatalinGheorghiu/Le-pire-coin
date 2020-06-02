<?php
include "db.php";
$dbh = new PDO($dsn, $user, $pass, $options);
session_start();


//Select Post
$query = '  SELECT   
                Posts.id as postId, 
                Posts.user_id as postUserId, 
                Posts.title,
                Posts.body, 
                Posts.created_at,
                Users.id,
                Users.name
            FROM 
                Posts 
            INNER JOIN 
                Users 
            ON 
                Users.id = Posts.user_id
            WHERE 
                Posts.id=:id';
$sth = $dbh->prepare($query);
$sth->execute([':id' =>  $_GET['post']]);
$post = $sth->fetch();
// }
// var_dump($post);

include "post.phtml";
