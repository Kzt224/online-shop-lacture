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

     $statement = $pdo->prepare("SELECT * FROM user WHERE id=".$_GET['id']);
     $statement->execute();
     $result = $statement->fetch(PDO::FETCH_ASSOC);
     
?>
<?php 
   if($_POST){

    $id = $_POST['id'];
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

    if(empty($_POST['role'])){
        $role = 0;
     }else{
        $role = 1;
     }

     if(empty($_POST['username']) || empty($_POST['email'])){

      if(empty($_POST['username'])){
        $usernameError = "*Username cannnot be null";
      }
      if(empty($_POST['email'])){
       $emailError = "*email cannnot be null";
      }
  }elseif(!empty($_POST['password']) && strlen($_POST['password']) < 4){
    $passwordError = "*password should be 4 character at least";
  }else{
       $statement = $pdo->prepare("SELECT * FROM user WHERE email=:email AND id!=:id");
       $statement->execute(
           array(':email' => $email, ':id' => $id)
       );
       $user = $statement->fetch(PDO::FETCH_ASSOC);
        
       if($user){
           echo "<script>alert('This email was already exit try another one');</script>";
       }else{
         if($password != null){
          $statement = $pdo->prepare("UPDATE user SET name=:name,password=:password, email=:email, role=:role WHERE id=$id");
          $res = $statement->execute(
            array(':name' => $name,':password' =>$password,':email'=> $email,':role' => $role),
          );
  
          if($res){
              echo "<script>alert('user updated successfully');window.location.href='user.php';</script>";
          }
         }else{
          $statement = $pdo->prepare("UPDATE user SET name=:name, email=:email, role_id=:role_id WHERE id=$id");
          $res = $statement->execute(
            array(':name' => $name,':email'=> $email, ':role_id' => $role),
          );
          if($res){
              echo "<script>alert('user updated successfully');window.location.href='user.php';</script>";
          }
         }
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
                <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                  <input type="hidden" value="<?= $result['id']?>" name="id">
                <div class="form-group">
                <label for="username">Username</label>
                <p style="color:red"><?php echo empty($usernameError) ? '' : $usernameError; ?></p>
                <input type="text" name="username" class="form-control"  value="<?= escape($result['name']) ?>">
              </div>
              <div class="form-group">
                 <label for="email" >email</label>
                 <p style="color:red"><?php echo empty($emailError) ? '' : $emailError; ?></p>
                  <input type="email" name="email" class="form-control" value="<?= escape($result['email']) ?>">
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <p style="color:red"><?php echo empty($passwordError) ? '' : $passwordError; ?></p>
                <span style= "font-size: 10px">this user has an password</span>
                <input type="password" name="password" class="form-control">
              </div>
               <div class="form-group">
                 <label for="Admin">Admin</label>
                 <input type="checkbox" name="role" <?php if($result['role'] == 1){echo 'checked';}else{echo 'unchecked';} ?>>
               </div>
              <div class="form-group">
                 <input type="submit" value="submit" class="btn btn-success">
                 <a href="user.php" class="btn btn-warning" type="button">Back</a>
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