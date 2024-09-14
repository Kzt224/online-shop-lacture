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
    
      $current_date = date("Y-m-d");
      $toDate = date("Y-m-d",strtotime($current_date . '-1 month'));

      $fromDate = date("Y-m-d",strtotime($current_date . '+1 day'));

   
     $statement = $pdo->prepare("SELECT * FROM sale_orders WHERE ordered_date<:from_date AND ordered_date>=:to_date ORDER BY id DESC");
     $statement->execute([ ':to_date' => $toDate, ':from_date' => $fromDate]);
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
                <h3 class="card-title">Monthly Reporting</h3>
              </div><br>
                
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered" id="report">
                  <thead>
                    <tr>
                      <th style="width: 10px">id</th>
                      <th>User Name</th>
                      <th>Total Ammount</th>
                      <th>Order Date</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php  
                        if($result){
                           $i=1;
                          foreach ($result as  $value) {
                             ?> 
                          <?php 
                           $userstmt = $pdo->prepare("SELECT * FROM user WHERE id=".$value['user_id']);
                           $userstmt->execute();
                           $userresult = $userstmt->fetchAll();
                          ?>
                    <tr>
                      <td><?= $i ?></td>
                      <td><?= escape($userresult[0]['name']) ?></td>
                      <td>$<?= escape($value['total_prices']) ?></td>
                      <td><?= escape(date('Y-m-d',strtotime($value['ordered_date']))) ?></td>
                     
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
