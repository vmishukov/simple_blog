<?php
    session_start();
    include_once ("db.php");
    if(!isset($_GET['pid'])) {
        header("Location: index.php");
    }
    if (isset($_POST['comment'])) {
        $content = strip_tags($_POST['content']);
        $content = mysqli_real_escape_string($db, $content);
        $date = date('l jS \of F Y h:i:s A');
        $user_id = $_SESSION['id'];
        $post_id = $_GET['pid'];
        $sql = "INSERT INTO `comments` (`content`, `date`, `post`, `user`) VALUES ('$content', '$date', '$post_id', '$user_id')";
        if ($content == "") {
            echo "Please complete your post!";
            return;
        }
        mysqli_query($db, $sql);
        //header("Refresh:0");
    }
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <meta charset="utf-8">
    <title>Blog</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php
        if(!isset($_SESSION['username'])){
            echo "<a class='navbar-brand' href='login.php'>Log in!</a>
                    <ul class='navbar-nav mr-auto'>                      
                        <li class='nav-item active'>
                            <a class='nav-link' class='nav-link' href='index.php'>Home</a>
                        </li>                      
                    </ul>

";
        }else{
            $user_id = $_SESSION['id'];
            $user_name = mysqli_fetch_assoc(mysqli_query($db,"SELECT username FROM users WHERE id='$user_id'"))['username'] or die(mysqli_error($db));
            echo "<a class='navbar-brand' >Welcome, $user_name!</a>";
            echo "<div class='collapse navbar-collapse' id='navbarSupportedContent'>
                    <ul class='navbar-nav mr-auto'>                         
                        <li class='nav-item active'>
                            <a class='nav-link' class='nav-link' href='index.php'>Home</a>
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
        $pid = $_GET['pid'];
        $sql = "SELECT * FROM posts WHERE id=$pid LIMIT 1";
        $res = mysqli_query($db,$sql) or die(mysqli_error($db));
        if (mysqli_num_rows($res)>0){
            while ($row = mysqli_fetch_assoc($res)){
                $id = $row['id'];
                $title = $row['title'];
                $date = $row['date'];
                $content = $row['content'];

                $user_id =$row['user'];
                $user_name = mysqli_fetch_assoc(mysqli_query($db,"SELECT username FROM users WHERE id='$user_id'"))['username'] or die(mysqli_error($db));

                if (isset($_SESSION['admin']) && $_SESSION['admin']==1||($user_id ==$_SESSION['id'])){
                    $admin ="<div><a class='btn btn-primary f-s-12 rounded-corner'href='del_post.php?pid=$id'>Delete</a>&nbsp;<a href='edit_post.php?pid=$id' class='btn btn-primary f-s-12 rounded-corner'>Edit</a></div>";
                } else{
                    $admin="";
                }
                echo
                "                                                                                    
                <div class='card' >
                    <div class='card-body'>
                        <h5 class='card-title'>$title By $user_name</h5>
                            <h6 class='card-subtitle mb-2 text-muted'>$date</h6>
                            <p class='card-text'>$content</p>
                            <a href=''#' class='card-link'>$admin</a>                                   
                     </div>
                </div> ";
            }
        } else{
            echo "<p>There no posts to display</p>";
        } ?>

    <form method="post" action="view_post.php?pid=<?php echo $pid; ?>">
        <div class='container'>
            <div class='row'>
                <div class="input-group">
                    <input type="text" class="form-control rounded-corner" name="content"placeholder="Write a comment...">
                    <span class="input-group-btn p-l-10">
                          <?php
                          if(isset($_SESSION['id'])){
                              echo "<input name='comment' class='btn btn-primary f-s-12 rounded-corner' type='submit' value='Add comment' />";
                          }else{
                              echo "<input name='comment' class='btn btn-primary f-s-12 rounded-corner' type='submit' value='Log in to comment' disabled/>";
                          }


                          ?>

                        </span>
                </div>
            </div>
        </div>
    </form>

    <?php
        $sql = "SELECT * FROM comments WHERE post=$pid";
        $res = mysqli_query($db,$sql) or die(mysqli_error($db));
        $comments ="";
        if (mysqli_num_rows($res)>0) {
            while ($row = mysqli_fetch_assoc($res)) {
                $id = $row['id'];
                $date = $row['date'];
                $content = $row['content'];
                $user_id =$row['user'];
                $user_name = mysqli_fetch_assoc(mysqli_query($db,"SELECT username FROM users WHERE id='$user_id'"))['username'] or die(mysqli_error($db));
                if ((isset($_SESSION['admin']) && $_SESSION['admin']==1) ||($user_id ==$_SESSION['id']) ){
                    $admin ="<div><a class='btn btn-primary f-s-12 rounded-corner'href='del_comm.php?pid=$id&post=$pid'>Delete</a>&nbsp;";
                } else{
                    $admin="";
                }
                $comments .=
                            "
                            </div>
                                <div class='card' >
                                  <div class='card-body'>
                                    <h5 class='card-title'>$user_name</h5>
                                    <p class='card-text'>$content</p>  
                                    <h7 class='card-subtitle mb-2 text-muted'>$date</h7>                                      
                                    <a href=''#' class='card-link'>$admin</a>                                                                    
                                  </div>
                                </div>                            
                            ";
            }
            echo $comments;
        }
    ?>
</body>
</html>
