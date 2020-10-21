<?php
    if(isset($_SESSION['id'])){
        header("Location: index.php");
    }

    if(isset($_POST['register'])){
        include_once ("db.php");

        $username = strip_tags(($_POST['username']));
        $password = strip_tags(($_POST['password']));
        $password_confirm = strip_tags(($_POST['password_confirm']));
        $email = strip_tags(($_POST['email']));


        $username = stripslashes($username);
        $password = stripslashes($password);
        $password_confirm = stripslashes($password_confirm);
        $email = stripslashes($email);

        $username = mysqli_real_escape_string($db,$username);
        $password = mysqli_real_escape_string($db,$password);
        $password_confirm = mysqli_real_escape_string($db,$password_confirm);
        $email = mysqli_real_escape_string($db,$email);

        $password =md5($password);
        $password_confirm =md5($password_confirm);

        $sql_store = "INSERT into users (username,password,email) VALUES ('$username','$password','$email')";
        $sql_fetch_username = "SELECT username FROM users WHERE username = '$username'";
        $sql_fetch_email = "SELECT email FROM users WHERE email = '$email'";

        $query_username = mysqli_query($db,$sql_fetch_username);
        $query_email = mysqli_query($db,$sql_fetch_email);
        if(mysqli_num_rows($query_username)){
            echo"There is already user with that name!";
            return;
        }
        if($username ==""){
            echo "Please insert a username";
            return;
        }
        if ($password == "" || $password_confirm == ""){
            echo "Please insert your password";
            return;
        }
        if($password != $password_confirm){
            echo  "The passwords do not match!";
            return;
        }
        if(!filter_var($email,FILTER_VALIDATE_EMAIL) || $email ==""){
            echo  "This email is not valid";
            return;
        }

        mysqli_query($db,$sql_store);

        header("Location: login.php");

    }

?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <meta charset="utf-8">
    <title>Register</title>
</head>

<body>

<form action="register.php" method="POST">
    <div class="container fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Registration</div>
                    <div class="card-body">
                        <form action="" method="">
                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">Username</label>
                                <div class="col-md-6">
                                    <input type="text"  class="form-control" name="username" required autofocus>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">E-MAIL</label>
                                <div class="col-md-6">
                                    <input type="email"  class="form-control" name="email" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input type="password"  class="form-control" name="password" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                <div class="col-md-6">
                                    <input type="password"  class="form-control" name="password_confirm" required>
                                </div>
                            </div>
                            <div class="col-md-6 offset-md-4">
                                <input name="register" type="submit" class="btn-primary f-s-12 rounded-corner" value="Create account" />
                                <a href="login.php" class="btn btn-link ">
                                    Log in
                                </a>
                                <a href="index.php" class="btn btn-link">
                                    Home page
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</form>


</body>

