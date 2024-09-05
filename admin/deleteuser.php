<?php
  require("../config/config.php");

  if(!$_SESSION['logged_in'] && !$_SESSION['user_id']){
    header("location: login.php");
 }
 if($_SESSION['role_id'] != 1){
  header("location: login.php");
}
   

if($_GET['id']){

  $statement = $pdo->prepare("DELETE  FROM user WHERE id=".$_GET['id']);
    $result= $statement->execute();

    if($result){
      echo "<script>alert('user  delete unsuccessfully')</script>";
      header("location: user.php");

    }
}

?>