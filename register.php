<?php
include "header.phtml";

include "db.php";
$error = "";
$name_err = "";
$email_err = "";
$user_err = "";
$password_err = "";

//Check if form is submitted
if (!empty($_POST)) {
    //Create PDO instance

    $dbh = new PDO($dsn, $user, $pass, $options);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password1 = $_POST['confirm_password'];



    //Password match
    if ($password !== $password1) {
        $password_err = "Passwords don't match!";
    }

    //Password length
    if (strlen($password) < 6) {
        $password_err = "Password must be at least 6 characters!";
    }

    //Email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email!";
    }

    //Verify Empty fields
    if (empty($name) || empty($email) || empty($password || empty($password1))) {
        $error = 'Please complete all fields!';
    }


    //See if user EXISTS
    $query = 'SELECT * FROM Users WHERE email =:email';
    $stmt = $dbh->prepare($query);
    $stmt->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $stmt->execute();

    //If user exists in DB...
    if ($stmt->rowCount() > 0) {
        $user_err = 'This email is already used!';
    } else {

        //Add user
        $query = 'INSERT INTO Users (name, email, password) VALUES (:name, :email, :password)';
        $stmt = $dbh->prepare($query);
        $stmt->bindValue(':name', trim($_POST['name'], PDO::PARAM_STR));
        $stmt->bindValue(':email', trim($_POST['email'], PDO::PARAM_STR));
        $stmt->bindValue(':password', password_hash(trim($_POST['password']), PASSWORD_BCRYPT), PDO::PARAM_STR);
        $stmt->execute();

        //Redirect
        header('Location: login.php?register=success');
        exit;
    }
}

include "register.phtml";
include "footer.phtml";
