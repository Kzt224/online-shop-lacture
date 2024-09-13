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
                <h3 class="card-title">Users Listings</h3>
              </div><br>
                <div class="container-fluid">
                   <a href="adduser.php" type="button" class="btn btn-success">Create New</a>
                </div>
              <!-- /.card-header -->

              <!-- php code here -->

                <?php  

                 //for pagination
                   if(!empty($_GET['pageno'])){
                     $pageno = $_GET['pageno'];
                   }else{
                     $pageno = 1;
                   }

                   $numberOfrecs = 4;
                   $offSet = ($pageno -1) * $numberOfrecs ;
                   
                   if(empty($_POST['search']) && empty($_COOKIE['search'])){
                    $statement = $pdo->prepare("SELECT * FROM user ORDER BY id DESC ");
                    $statement->execute();
                    $rawresult = $statement->fetchAll();

                    $total_pages = ceil(count($rawresult) / $numberOfrecs);

                    $statement = $pdo->prepare("SELECT * FROM user ORDER BY id DESC LIMIT $offSet,$numberOfrecs");
                    $statement->execute();
                    $result = $statement->fetchAll();
                   }else{
                     if(isset($_POST['search'])){
                       $searchKey = $_POST['search'];
                     }else{
                       $searchKey = $_COOKIE['search'];
                     }
                     //code for table
                      $statement = $pdo->prepare("SELECT * FROM user WHERE name LIKE '%$searchKey%' OR email LIKE '%$searchKey%'  ORDER BY id DESC");
                      $statement->execute();
                      $rawresult = $statement->fetchAll();

                      $total_pages = ceil(count($rawresult) / $numberOfrecs);

                      $statement = $pdo->prepare("SELECT * FROM user WHERE name LIKE '%$searchKey%' OR email LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offSet,$numberOfrecs");
                      $statement->execute();
                      $result = $statement->fetchAll();
                     
                   }
                   
                   

                
                ?>
              
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>email</th>
                      <th>Role</th>
                      <th style="width: 40px">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php  
                        if($result){
                           $i=1;
                          foreach ($result as  $value) {
                             ?> 
                    <tr>
                      <td><?= $i ?></td>
                      <td><?= escape($value['name']) ?></td>
                      <td><?= escape($value['email']) ?></td>
                      <td><?php if($value['role'] == 1){echo 'Admin';}else{echo 'User';} ?></td>
                      <td>
                        <div class="btn-group">
                          <div class="container">
                             <a href="edituser.php?id=<?= $value['id'] ?>" type="button" class="btn btn-warning">Edit</a>
                          </div>
                          <div class="container">
                            <a href="deleteuser.php?id=<?= $value['id'] ?>" type="button" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
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
