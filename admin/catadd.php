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

// check method and insert database from form

if($_POST){

   if(empty($_POST['name']) || empty($_POST['description'])){

    if(empty($_POST['name'])){
      $nameError = "Category name cannot be null";
    }
    if(empty($_POST['description'])){
     $descriptionError = "description  cannot be null";
   }
   
   }else{
    $name = $_POST['name'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO categories(name,description) VALUES(:name,:description)");
    $res = $stmt->execute(
      array(':name' => $name, ':description' => $description )
    );

    if($res){
      header("location: category.php?". "success=1");
   }
}
}   
 ?>

<?php include("header.php"); ?>

<div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <form action="" method="post">
                 <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                <div class="form-group">
                <label for="title">Name</label>
                <p style="color:red"><?php echo empty($nameError) ? '' : $nameError; ?></p>
                <input type="text" name="name" class="form-control" >
              </div>
              <div class="form-group">
                 <label for="content" >Description</label>
                 <p style="color:red"><?php echo empty($descriptionError) ? '' : $descriptionError; ?></p>
                <textarea name="description" class="form-control" rows="8" columns="80" ></textarea>
              </div>
              <div class="form-group">
                 <input type="submit" value="submit" class="btn btn-success">
                 <a href="category.php" class="btn btn-warning" type="button">Back</a>
              </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

<?php include("footer.php"); ?>