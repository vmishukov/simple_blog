<?php
    session_start();
    include_once ("db.php");


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Blog</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <?php
    if(!isset($_SESSION['username'])){
        echo "<a class='navbar-brand' href='login.php'>Log in!</a>";
    }else{
        $user_id = $_SESSION['id'];
        $user_name = mysqli_fetch_assoc(mysqli_query($db,"SELECT username FROM users WHERE id='$user_id'"))['username'] or die(mysqli_error($db));
        echo "<a class='navbar-brand' >Welcome, $user_name!</a>";

        echo "<div class='collapse navbar-collapse' id='navbarSupportedContent'>
                    <ul class='navbar-nav'>                       
                        <li class='nav-item active '>
                            <a class='nav-link' class='nav-link' href='post.php'>Post!</a>
                        </li>
                         <li class='nav-item'>              
                            <a class='nav-link' href='logout.php'>Log out</a>
                        </li>
                    </ul>
                </div>";
    }

    ?>
</nav>
<hr/>
<hr/>

    <?php
        $sql = "SELECT * FROM posts ORDER BY id DESC";

        $res = mysqli_query($db,$sql) or die(mysqli_error($db));
        $posts ="";
        if (mysqli_num_rows($res) > 0){
            while($row = mysqli_fetch_assoc($res)){
                $id = $row['id'];
                $title = $row['title'];
                $content = $row['content'];
                $date = $row['date'];
                $user_id =$row['user'];
                $user_name = mysqli_fetch_assoc(mysqli_query($db,"SELECT username FROM users WHERE id='$user_id'"))['username'] or die(mysqli_error($db));
                if (isset($_SESSION['admin']) && $_SESSION['admin']==1||($user_id ==$_SESSION['id'])){
                    $admin ="<div><a class='btn btn-primary f-s-12 rounded-corner'href='del_post.php?pid=$id'>Delete</a>&nbsp;<a href='edit_post.php?pid=$id' class='btn btn-primary f-s-12 rounded-corner'>Edit</a></div>";
                }else{
                    $admin ="";
                }
                $posts .= "
                <div class='card fluid'>
                    <div class='card-body col-5'>
                        <h5 class='card-title'><a href='view_post.php?pid=$id' target='_blank'>$title By $user_name</a></h5>
                            
                            <p class='card-text  text-truncate' >$content</p>
                            <h7 class='card-subtitle mb-2 text-muted'>$date</h7>
                            <a href=''#' class='card-link'>$admin</a>                                   
                     </div>
                </div> 
";
            }
            echo $posts;
            } else{
            echo "There are no post to display!";
        }
    ?>
</body>
</html>