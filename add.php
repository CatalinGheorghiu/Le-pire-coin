<?php
session_start();
if (!array_key_exists('logged', $_SESSION)) {
    //	Redirect to home page
    header('Location: ./');
    exit;
}

if (!empty($_POST)) {
    include "db.php";
    $dbh = new PDO($dsn, $user, $pass, $options);






    $query = '  INSERT INTO 
                    Posts(user_id, title, body) 
                VALUES 
                    (:user_id, :title, :body)';
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(':user_id', $_SESSION['logged'], PDO::PARAM_INT);
    $stmt->bindValue(':title', trim($_POST['title']), PDO::PARAM_STR);
    $stmt->bindValue(':body', trim($_POST['content']), PDO::PARAM_STR);
    $stmt->execute();
    $postId = $dbh->lastInsertId();


    for ($i = 0; $i < count($_FILES["file"]["name"]); $i++) {

        $fileName = $_FILES['file']['name'][$i];
        $fileTmpName = $_FILES['file']['tmp_name'][$i];
        $fileSize = $_FILES['file']['size'][$i];
        $fileError = $_FILES['file']['error'][$i];
        $fileType = $_FILES['file']['type'][$i];
        // var_dump($fileType);


        if ($fileError  == 0) {
            if (in_array(mime_content_type($fileTmpName), ['image/png', 'image/jpeg'])) {
                if ($fileSize <= 3000000) {

                    $urlImg = uniqid() . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                    var_dump($urlImg);
                    move_uploaded_file($fileTmpName, 'uploads/' . $urlImg);

                    $query = 'INSERT INTO Images (post_id, img_url) VALUES (:post_id, :img_url)';
                    $stmt = $dbh->prepare($query);
                    $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
                    $stmt->bindValue(':img_url', $urlImg, PDO::PARAM_STR);
                    $stmt->execute();
                } else {
                    echo "File to big";
                }
            } else {
                echo "There was an error uploading your file";
            }
        } else {
            echo "Error type file!";
        }
    }

    //Redirect if success
    header('Location: dashboard.php');
}

include "add.phtml";
