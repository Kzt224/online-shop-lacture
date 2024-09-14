<?php  
 
 session_start();
 require("../config/config.php");
 require("../config/common.php");

 
  if(!$_SESSION['logged_in'] && !$_SESSION['id']){
    header("location: login.php");
  }

  if($_SESSION['role_id'] != 1){
     header("location: login.php");
  }
    
    

   
     $statement = $pdo->prepare("SELECT * FROM sale_orders_detail GROUP BY product_id HAVING  SUM(quantity) > 4 ORDER BY id DESC");
     $statement->execute();
     $result = $statement->fetchAll();

    
     
?>
  <?php 
    include("header.php");

  
  ?>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Royal Customer Listing</h3>
              </div><br>
                
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered" id="report">
                  <thead>
                    <tr>
                      <th style="width: 10px">id</th>
                      <th>Product_name Name</th>
                      <th>Item Image</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php  
                        if($result){
                           $i=1;
                          foreach ($result as  $value) {
                             
                            
                             ?> 
                          <?php 
                           $productstmt = $pdo->prepare("SELECT * FROM products WHERE id=".$value['product_id']);
                           $productstmt->execute();
                           $productres = $productstmt->fetchAll();

                           
                          ?>
                    <tr>
                      <td><?= $i ?></td>
                      <td><?= escape($productres[0]['name']) ?></td>
                      <td>
                        <img src="images/<?= $productres[0]['image'] ?>" alt="" width="100px" >
                      </td>
                     
                    </tr>                         
                          <?php
                            $i++;
                          }
                        }
                      ?>
                  </tbody>
               </table><br>
              <!-- php code here -->
              
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
 <script>
    	
   new DataTable('#report');
 </script>
