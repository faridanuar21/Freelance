<?php

session_start();

// initialize variables

$username = "";
$email = "";

$error = array();

// connect to db

$db = mysqli_connect('localhost', 'root', '', 'farid') or die("Could not connect to database")

// register users
$username = mysqli_real_escape_string($db,$POST['username']);
$email = mysqli_real_escape_string($db,$POST['email']);
$password_1 = mysqli_real_escape_string($db,$POST['password_1']);
$password_2 = mysqli_real_escape_string($db,$POST['password_2']);

// form validation

if (empty($username)) {array_push($errors, "Username is required!")};
if (empty($email)) {array_push($errors, "Email is required!")};
if (empty($password_1)) {array_push($errors, "Password is required!")};
if ($password_1 != $password_2){array_push($errors, "Passwords do not match")}

// check DB for existing user with same username

$user_check_query = "SELECT * FROM user WHERE username = '$username' or '$email' LIMIT 1";

$result = mysqli_query($db, $user_check_query);
$user = mysqli_fetch_assoc($result);

if ($user) {
    if($user['username'] === $username){array_push($errors, "Username already exists!");
    if($user['email'] === $email){array_push($errors, "This email id already has registered username!");}

}


// Register user

if (count($errors)===0){

    $password = md5($password_1); //this will encrypt the password
    $query = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$password')";

    mysqli_query($db, $query);
    $_SESSION['username'] = $username;
    $_SESSION['success'] = "You are now logged in";

    header('location: index.php');
}

// LOGIN USER

if(isset($_POST['login_user'])){
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password_1']);

    if(empty($username)){
        array_push($errors, "Username is required");
    }

    if(empty($password)){
        array_push($errors, "Password is required");
    }

    if(count($errors) == 0 ){
        $password = md5($password;)

        $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
        $results = mysqli_query($db, $query);

        if(mysqli_num_results($results)) {
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "Logged in successfully";
            header('location: index.php');
        }else{
            array_push($errors, "Wrong username/passwords combination. Please try again.");
        }
    }

}

?>