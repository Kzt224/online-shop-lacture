<?php
  
  
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(!hash_equals($_SESSION['_token'], $_POST['_token'])){
        die();
    }else{
        unset($_SESSION['_token']);
    }
  }

  if(empty($_SESSION['_token'])){
    $_SESSION['_token'] = bin2hex(random_bytes(32));
  }




//   for xxs attack

 function escape($html){
    return htmlspecialchars($html,ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
 }
?>