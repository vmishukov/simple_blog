<?php
session_start();
include_once("db.php");

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    return;
}

if(!isset($_GET['pid'])){
    $post = $_GET['post'];
    header("Location:view_post.php?pid=$post");
} else {
    $pid = $_GET['pid'];
    $post = $_GET['post'];
    $sql = "DELETE FROM comments WHERE id=$pid";
    mysqli_query($db,$sql);
    header("Location: view_post.php?pid=$post");
}

