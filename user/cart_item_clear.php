<?php
 session_start();

 if(isset($_GET['pid'])){
    $id = $_GET['pid'];

    unset($_SESSION['cart']['id'.$id]);

    header("location: index.php");
 }

?>