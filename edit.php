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
                                Posts.img,
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





if (isset($_POST['add-post'])) {

    /* File Upload */
    $file = $_POST['file'];
    // var_dump($_FILES['file']);
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileError = $_FILES['file']['error'];
    $fileType = $_FILES['file']['type'];

    if ($fileError  == 0) {
        if (in_array(mime_content_type($fileTmpName), ['image/png', 'image/jpeg'])) {
            if ($fileSize <= 3000000000) {

                $urlImg = uniqid() . '.' . pathinfo($fileName, PATHINFO_EXTENSION);

                unlink('uploads/' . $post['img']);

                move_uploaded_file($fileTmpName, 'uploads/' . $urlImg);
            } else {
                echo "File to big";
            }
        } else {
            echo "There was an error uploading your file";
        }
    } else {
        echo "Error type file!";
    }


    //Update POST
    $query = 'UPDATE Posts SET title = :title, body = :body, img = :img WHERE id = :id';
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(':id', $post['postId'], PDO::PARAM_STR);
    $stmt->bindValue(':title', trim($_POST['title']), PDO::PARAM_STR);
    $stmt->bindValue(':body', trim($_POST['content']), PDO::PARAM_STR);
    if (!empty($urlImg)) {
        $stmt->bindValue(':img', $urlImg, PDO::PARAM_STR);
    } else {
        $urlImg = $post['img'];
        $stmt->bindValue(':img', $urlImg, PDO::PARAM_STR);
    }
    $stmt->execute();

    //Redirect
    header('Location: dashboard.php');
    exit;
}

include "edit.phtml";
