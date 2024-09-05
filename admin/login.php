<?php
 session_start();
 require("../config/config.php");
 require("../config/common.php");
 
if($_POST){
   $email = $_POST['email'];
   $password = $_POST['password'];

   $statement = $pdo->prepare("SELECT * FROM user WHERE email= :email");
   $statement->bindValue(':email',$email);
   $statement->execute();
   
   $user = $statement->fetch(PDO::FETCH_ASSOC);


   if($user){
    if($user['role'] === 1){
      if(password_verify($password,$user['password'])){
       $_SESSION['user_id'] = $user['id'];
       $_SESSION['user_name'] = $user['name'];
       $_SESSION['logged_in'] = time();
       $_SESSION['role_id'] = 1;
       header("location: index.php");
     }
    }else{
      echo "<script>alert('Admin only access to login');window.location.href='login.php';</script>";
      exit();
    }
  }
  echo "<script>alert('incorrect email or password')</script>";
  
}


?>





<!DOCTYPE html>
<html lang="en">
  <head>
   <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>PK Shop</b>Admin</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="" method="post">
      <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="dist/js/adminlte.min.js"></script>
  </body>
</html>
