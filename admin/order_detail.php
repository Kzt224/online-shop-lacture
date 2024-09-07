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
  if(!empty($_POST['search'])){
    setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
 }else{
   if(empty($_GET['pageno'])){
     unset($_COOKIE['search']);
     setcookie('search', '', -1, "/");
   }
 }

?>
  <?php 
    include("header.php");

  
  ?>
    <!-- /.content-header -->
    <?php  

//for pagination
  if(!empty($_GET['pageno'])){
    $pageno = $_GET['pageno'];
  }else{
    $pageno = 1;
  }

  $numberOfrecs = 4;
  $offSet = ($pageno -1) * $numberOfrecs ;
       
        // id from view buttom

        $id = $_GET['id'];
       
       $statement = $pdo->prepare("SELECT * FROM sale_orders_detail WHERE id = $id ");
       $statement->execute();
       $rawresult = $statement->fetchAll();
       $total_pages = ceil(count($rawresult) / $numberOfrecs);

       $statement = $pdo->prepare("SELECT * FROM sale_orders_detail WHERE id = $id LIMIT $offSet,$numberOfrecs");
       $statement->execute();
       $result = $statement->fetchAll();


?> 
  
   <?php 
     if(isset($_GET['success'])){
        echo "<script>
        swal ({
            title: 'success!',
            text: 'category add successfully',
            icon: 'success',
            button: 'ok'
     })
        </script>";
     }
   ?>


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Orders Details </h3>
                 </div><br>
              
              <!-- /.card-header -->
           <div class="card-body">
           <a href="order_list.php" class="btn btn-primary" type="btn">Back</a>
           <br><br>
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Product</th>
                      <th>quantity</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php  
                        if($result){
                           $i=1;
                          foreach ($result as  $value) {?> 
                          <?php 
                           $pstatement = $pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                           $pstatement->execute();
                           $presult = $pstatement->fetchAll();
                          ?>
                    <tr>
                      <td><?= $i ?></td>
                      <td><?= escape($presult[0]['name'] ) ?></td>
                      <td><?= escape($value['quantity']) ?> </td>
                      <td><?= $value['ordered_date'] ?></td>
                    </tr>                         
                          <?php
                            $i++;
                          }
                        }
                      ?>
                  </tbody>
               </table><br>
          
             
              <nav aria-label="Page navigation example" style="float: right">
                <ul class="pagination">
                        <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                        <li class="page-item <?php if($pageno <= 1){echo 'disabled';} ?>">
                          <a class="page-link" href="<?php if($pageno <= 1){echo '#';}else{echo "?pageno=".($pageno -1 );} ?>">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#"><?= $pageno ?></a></li>
                        <li class="page-item <?php if($pageno >= $total_pages){echo 'disabled';} ?>">
                          <a class="page-link" href="<?php if($pageno >= $total_pages){echo "#";}else{echo "?pageno=".($pageno +1);} ?>">Next</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="?pageno=<?= $total_pages ?>">Last</a></li>
                      </ul>
               </nav>
              
                </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div> 
    <!-- /.content -->
 
 <?php 
  include("footer.php");
 ?>
