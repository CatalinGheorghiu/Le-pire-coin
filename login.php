<?php
include "header.phtml";

include "db.php";

$user_err = '';
$password_err = '';

if (!empty($_POST)) {
    //Create PDO instance

    $dbh = new PDO($dsn, $user, $pass, $options);

    //Add user
    $query = 'SELECT id, password FROM Users WHERE email = :email';
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(':email', trim($_POST['email'], PDO::PARAM_STR));
    $stmt->execute();
    $user = $stmt->fetch();

    //If user exists...
    if ($user !== false and password_verify(trim($_POST['password']), $user['password'])) {
        //Start session
        session_start();
        $_SESSION['logged'] = intval($user['id']);

        //Redirect to dashboard
        header('Location: dashboard.php');
        exit;
    } else {
        if ($user == false) {
            $user_err = "Incorrect user!";
        } elseif (!password_verify(trim($_POST['password']), $user['password'])) {
            $password_err = "Incorrect password!";
        }
        // //Redirect to login again
        // header('Location: login.php');
        // exit;
    }
}
include "login.phtml";
include "footer.phtml";
