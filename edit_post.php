<?php
session_start();
include_once ("db.php");

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    return;
}
if(!isset($_GET['pid'])) {
    header("Location: index.php");
}

$pid = $_GET['pid'];

if (isset($_POST['update'])) {

    $title = strip_tags($_POST['title']);
    $content = strip_tags($_POST['content']);

    $title = mysqli_real_escape_string($db, $title);
    $content = mysqli_real_escape_string($db, $content);

    $date = date('1 jS \of F Y h:i:s A');

    $sql = "UPDATE posts SET title ='$title', content='$content', date ='$date' WHERE id=$pid";

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <meta charset="utf-8">
    <title>Edit post</title>
</head>
<body>
<?php

$sql_get="SELECT * FROM posts WHERE id='$pid' LIMIT 1";
$res = mysqli_query($db,$sql_get);
$content = "";
$title = "";
if (mysqli_num_rows($res) > 0){
    while($row = mysqli_fetch_assoc($res)){
        $title = $row['title'];
        $content = $row['content'];

    }
}
?>

<?php  echo "<form action='edit_post.php?pid=$pid' method='POST'>";?>
    <div class="container fluid">
        <div class="form-group">
            <label for="exampleFormControlInput1">Title</label>

            <?php  echo "<input type='text' name='title' class='form-control'  value='$title' placeholder=''>";?>
        </div>
        <div class="form-group">
            <label for="exampleFormControlTextarea1">Content</label>

            <?php echo"<textarea placeholder='Content' class='form-control' name='content' rows='20' cols='50'>$content</textarea><br />";?>
            <input name="update" type="submit" class="btn-primary"value="Update!" />
            <a href="index.php" class="btn btn-link">
                Home page
            </a>
        </div>
    </div>
</form>



</body>

</html>