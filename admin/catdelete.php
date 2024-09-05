<?php
  session_start();

  require("../config/config.php");
  require("../config/common.php");

 if(!$_SESSION['logged_in'] && !$_SESSION['user_id']){
    header("location: login.php");
 }
 if($_SESSION['role_id'] != 1){
  header("location: login.php");
}
  
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id=$id ");
    $res = $stmt->execute();

    if($res){
        header("location: category.php?"."delete=1");
    }
}

?>