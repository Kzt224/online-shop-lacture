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

    if(!empty($_GET['pageno'])){
      $pageno = $_GET['pageno'];
    }else{
      $pageno = 1;
    }

    $numberOfrecs = 1;
    $offSet = ($pageno -1) * $numberOfrecs ;


      //  if no search the category run this code 
  if(empty($_POST['search']) && empty($_COOKIE['search'])){
    //code for table
       $statement = $pdo->prepare("SELECT * FROM products ORDER BY id DESC");
       $statement->execute();
       $rawresult = $statement->fetchAll();
       $total_pages = ceil(count($rawresult) / $numberOfrecs);

       $statement = $pdo->prepare("SELECT * FROM products ORDER BY id DESC LIMIT $offSet,$numberOfrecs");
       $statement->execute();
       $result = $statement->fetchAll();
  }else{
     
    if(isset($_POST['search'])){
      $searchKey = $_POST['search'];
    }else{
      $searchKey = $_COOKIE['search'];
    }

    //  if  search the category run this code 
    $statement = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%'  ORDER BY id DESC ");
    $statement->execute();
    $rawresult = $statement->fetchAll();
    $total_pages = ceil(count($rawresult) / $numberOfrecs);

   // if test the code and error remove comment

     $statement = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%'  ORDER BY id DESC LIMIT $offSet,$numberOfrecs ");
     $statement->execute();
     $result = $statement->fetchAll();
     
 
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
                   <a href="productadd.php" type="button" class="btn btn-success">Create New Product</a>
                </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">id</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Category</th>
                      <th>In Stock</th>
                      <th>Price</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php  
                        if($result){
                           $i=1;
                          foreach ($result as  $value) {
                             ?> 
                          <?php 
                           $catstatement = $pdo->prepare("SELECT * FROM categories WHERE id=".$value['category_id']);
                           $catstatement->execute();
                           $cactresult = $catstatement->fetchAll();
                          ?>
                    <tr>
                      <td><?= $i ?></td>
                      <td><?= escape($value['name']) ?></td>
                      <td><?= escape(substr($value['description'],0,30) ) ?> </td>
                      <td><?= escape($cactresult[0]['name']) ?></td>
                      <td><?= escape($value['quantity']) ?></td>
                      <td>$<?= escape($value['price']) ?></td>
                      <td>
                        <div class="btn-group">
                          <div class="container">
                             <a href="productedit.php?id=<?= $value['id'] ?>" type="button" class="btn btn-warning">Edit</a>
                          </div>
                          <div class="container">
                            <a href="productdelete.php?id=<?= $value['id'] ?>" type="button" class="btn btn-danger" onclick=" return confirm('Are you sure you want to delete this item?');">Delete</a>
                         </div>
                        </div>
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
