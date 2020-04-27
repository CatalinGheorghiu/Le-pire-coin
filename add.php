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
include "add.phtml";

if (!empty($_POST)) {
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

    var_dump($urlImg);
    $query = 'INSERT INTO Posts (user_id, title, body, img) VALUES (:user_id, :title, :body, :img)';
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(':user_id', $_SESSION['logged'], PDO::PARAM_STR);
    $stmt->bindValue(':title', $_POST['title'], PDO::PARAM_STR);
    $stmt->bindValue(':body', $_POST['content'], PDO::PARAM_STR);
    if (!empty($urlImg)) {

        $stmt->bindValue(':img', $urlImg, PDO::PARAM_STR);
    } else {
        $urlImg = "unnamed.jpg";
        $stmt->bindValue(':img', $urlImg, PDO::PARAM_STR);
    }
    $stmt->execute();

    //Redirect if success
    header('Location: dashboard.php');
} 
// include "footer.phtml";
