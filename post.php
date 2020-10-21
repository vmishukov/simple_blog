<?php
session_start();
include_once ("db.php");

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    return;
}
if (isset($_POST['myActionName'])) {

    $title = strip_tags($_POST['title']);
    $content = strip_tags($_POST['content']);

    $title = mysqli_real_escape_string($db, $title);
    $content = mysqli_real_escape_string($db, $content);

    $date = date('l jS \of F Y h:i:s A');
    $id = $_SESSION['id'];
    $sql = "INSERT into posts (title,content,date,user) VALUES ('$title','$content','$date','$id')";

    if ($title == "" || $content == "") {
        echo "Please complete your post!";
        return;
    }
    mysqli_query($db, $sql);
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Blog - Post</title>
</head>
<body>

    <form action="post.php" method="POST">
        <div class="container fluid">
            <div class="form-group">
                <label for="exampleFormControlInput1">Title</label>
                <input type="text" name="title" class="form-control"  placeholder="">
            </div>
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Content</label>
                <textarea placeholder="Content" name="content" class="form-control"  rows="20"></textarea>

                <input name="myActionName" type="submit" class="btn-primary"value="Post!" />
                <a href="index.php" class="btn btn-link">
                    Home page
                </a>
            </div>
        </div>
    </form>
</body>

</html>
