<?php
   session_start();
   include("../config/config.php");
   include("../config/common.php");

   if(isset($_POST['id'])){
     $id = $_POST['id'];
     $qty = $_POST['qty'];
     
     $stmt = $pdo->prepare("SELECT * FROM products WHERE id=$id");
	   $stmt->execute();
	   $res = $stmt->fetch(PDO::FETCH_ASSOC);

     if($qty > $res['quantity']){
        echo "<script>alert('not enought item');window.location.href='product_detail.php?id=$id'</script>";
     }else{

        if(isset($_SESSION['cart']['id'.$id])){
            $_SESSION['cart']['id'.$id] += $qty;
          }else{
            $_SESSION['cart']['id'.$id] = $qty;
          }
       
          header("location: product_detail.php?id=".$id);
     }

   }

   if(isset($_GET['id'])){
    $id = $_GET['id'];
    $qty = 1;

    if(isset($_SESSION['cart']['id'.$id])){
      $_SESSION['cart']['id'.$id] += $qty;
    }else{
      $_SESSION['cart']['id'.$id] = $qty;
    }

    echo "<script>alert('order add successfully');window.location.href='cart.php';</script>";
   }
?>