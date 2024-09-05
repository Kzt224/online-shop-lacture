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
 if($_POST){
     $username = $_POST['username'];
     $email = $_POST['email'];
     $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
     
     if(empty($_POST['role'])){
      $role = 0;
      }else{
          $role = 1;
      }

     if(empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 4){

      if(empty($_POST['username'])){
        $usernameError = "*Username cannnot be null";
      }
      if(empty($_POST['email'])){
       $emailError = "*email cannnot be null";
     }
     if(empty($_POST['password'])){
       $passwordError = "*password cannnot be null";
     }
     if(strlen($_POST['password']) < 4){
      $passwordError = "*password should be 4 character at least";
     }
    }else{
     $stamnt = $pdo->prepare("INSERT INTO user (name,password,email,role) VALUES(:name,:password,:email,:role)");
        $res = $stamnt->execute(
         array(':name' => $username, ':password' => $password,':email' => $email, ':role' => $role)
        );
        if($res){
           echo "<script>alert('user added successfully');window.location.href='user.php';</script>";
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
                <label for="username">User name</label>
                <p style="color:red"><?php echo empty($usernameError) ? '' : $usernameError; ?></p>
                <input type="text" name="username" class="form-control" >
              </div>
              <div class="form-group">
                 <label for="email" >email</label>
                <p style="color:red"><?php echo empty($emailError) ? '' : $emailError; ?></p>
                 <input type="email" class="form-control" name="email" >
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <p style="color:red"><?php echo empty($passwordError) ? '' : $passwordError; ?></p>
                <input type="password" name="password" class="form-control" >
              </div>
              <div class="form-group">
                 <label for="admin">Admin</label><br>
                 <input type="checkbox" name="role" value="1">
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