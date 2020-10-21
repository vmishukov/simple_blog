<?php
    session_start();
    if(isset($_SESSION['id'])){
        header("Location: index.php");
    }
    if (isset($_POST['login'])){
        include_once("db.php");
        $username = strip_tags(($_POST['username']));
        $password = strip_tags(($_POST['password']));


        $username = stripslashes($username);
        $password = stripslashes($password);

        $username = mysqli_real_escape_string($db,$username);
        $password = mysqli_real_escape_string($db,$password);

        $password =md5($password);

        $sql = "SELECT * FROM users WHERE username='$username' LIMIT 1";
        $query = mysqli_query($db,$sql);
        $row = mysqli_fetch_array($query);
        $id = $row['id'];
        $db_password = $row['password'];
        $admin = $row['admin'];

        if ($password == $db_password) {
            $_SESSION['username']=$username;
            $_SESSION['id']= $id;
            if($admin == 1){
                $_SESSION['admin']= 1;
            }
            header("Location: index.php");
        }else{
            echo "You didn`t enter the correct details";
        }
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>

<body>
<form action="login.php" method="POST">
    <div class="container fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form action="" method="">
                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">Username</label>
                                <div class="col-md-6">
                                    <input type="text"  class="form-control" name="username" required autofocus>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label  class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input type="password"  class="form-control" name="password" required>
                                </div>
                            </div>
                            <div class="col-md-6 offset-md-4">

                                <input name="login" type="submit" class="btn-primary f-s-12 rounded-corner" value="Enter" />
                                <a href="register.php" class="btn btn-link">
                                    Create account
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
