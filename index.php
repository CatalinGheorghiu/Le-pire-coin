<?php
include "db.php";
$dbh = new PDO($dsn, $user, $pass, $options);
session_start();



//Select Posts
$query = 'SELECT *,
                                    Posts.id as postId,
                                    Users.id as userId,
                                    Posts.created_at as postCreated,
                                    Users.created_at as userCreated
                                    FROM Posts
                                    INNER JOIN Users
                                    ON Posts.user_id = Users.id
                                    ORDER BY Posts.created_at DESC';
$stmt = $dbh->prepare($query);
$stmt->execute();
$posts = $stmt->fetchAll();
// var_dump($posts);

include "index.phtml";
