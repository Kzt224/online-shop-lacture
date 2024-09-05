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

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Product Listing</h3>
              </div><br>
                <div class="container-fluid">
                   <a href="add.php" type="button" class="btn btn-success">Create New</a>
                </div>
              <!-- /.card-header -->
           <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">id</th>
                      <th>name</th>
                      <th>description</th>
                      <th>category_id</th>
                      <th>quantity</th>
                      <th>price</th>
                      <th>image</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>1</td>
                      <td>sportshoe</td>
                      <td>for race</td>
                      <td>4</td>
                      <td>255</td>
                      <td>$25</td>
                      <td></td>
                      <td>
                        <div class="btn-group">
                          <div class="container">
                             <a href="" type="button" class="btn btn-warning">Edit</a>
                          </div>
                          <div class="container">
                            <a href="" type="button" class="btn btn-danger" onclick=" return confirm('Are you sure you want to delete this item?');">Delete</a>
                         </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
               </table><br>
          </div>
              <!-- php code here -->
              <nav aria-label="Page navigation example" style="float: right">

               </nav>
              
              
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
