<?php
   session_start();

   include("../config/config.php");
   include("../config/common.php");


   if($_POST){
       if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) 
	   || empty($_POST['address']) || empty($_POST['phone']) ){
	
	    if(empty($_POST['name']) ){
			$nameError = "* User name cannot be null";
		}
		if(empty($_POST['email']) ){
			$emailError = "* Email cannot be null";
		}
		if(empty($_POST['password']) ){
			$passError = "*Password cannot be null";

		}
		if(!empty($_POST['password']) && strlen($_POST['password']) < 4){
			$passError = "*password should be 4 character at least";
		   }
		if(empty($_POST['address']) ){
			$addError = "* address cannot be null";
		}
		if(empty($_POST['phone']) ){
			$phoneError = "* Phone number  cannot be null";
		}
	
	}else{
		$name = $_POST['name'];
		$email = $_POST['email'];
		$password = password_hash($_POST['password'],PASSWORD_DEFAULT);
		$address = $_POST['address'];
		$phone = $_POST['phone'];
		$role = 0;


		$estmt = $pdo->prepare("SELECT * FROM user WHERE email=:email");
		$eRes = $estmt->execute([':email' => $email]);

		if($eRes){
			echo "<script>alert('this Email was already have an account try another email');</script>";
		}else{

			$stmt = $pdo->prepare("INSERT INTO user(name,email,password,address,phone,role)
		             VALUES(:name,:email,:password,:address,:phone,:role)");

			$res = $stmt->execute(
				array(':name' => $name,':email' => $email,':password' => $password,
					':address' => $address,':phone' => $phone,':role' => $role)
			);

			if($res){
				echo "<script>alert('register successfully ');window.location.href='login.php';</script>";
			}

		}
		
  }
}





?>



<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>Karma Shop</title>

	<!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/main.css">
</head>

<body>

	<!-- Start Header Area -->
	<header class="header_area sticky-header">
	<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h" href="index.html"><h4>Poekaung Shopping<h4></a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
					 aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse offset" id="navbarSupportedContent">
						
					</div>
				</div>
			</nav>
		</div>
	</header>
	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Login/Register</h1>
					<nav class="d-flex align-items-center">
						<a href="index.php">Home<span class="lnr lnr-arrow-right"></span></a>
						<a href="login.php">Login/Register</a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Login Box Area =================-->
	<section class="login_box_area section_gap">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="login_box_img">
						<img class="img-fluid" src="img/login.jpg" alt="">
						<div class="hover">
							<h4>Visited to our website?</h4>
							<p>There are advances being made in science and technology everyday, and a good example of this is the</p>
							<a class="primary-btn" href="login.php">Login an Account</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="login_form_inner">
						<h3>Register to enter</h3>
						<form class="row login_form" action="" method="post" id="contactForm" novalidate="novalidate">
						        <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">

						     <p><span style="color : red"><?php echo empty($nameError) ? '' : $nameError ; ?></span></p>
                           <div class="col-md-12 form-group">
								<input type="text" class="form-control" id="name" name="name" placeholder="Username" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Username'">
						   </div>

							  <p><span style="color : red"><?php echo empty($emailError) ? '' : $emailError ; ?></span></p>
							<div class="col-md-12 form-group">
								<input type="email" class="form-control" id="name" name="email" placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'">
							</div>

							  <p><span style="color : red"><?php echo empty($passError) ? '' : $passError ; ?></span></p>
							<div class="col-md-12 form-group">
								<input type="password" class="form-control" id="name" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">
							</div>

							   <p><span style="color : red"><?php echo empty($addError) ? '' : $addError ; ?></span></p>
							<div class="col-md-12 form-group">
								<input type="address" class="form-control" id="name" name="address" placeholder="Your address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Address'">
							</div>

							   <p><span style="color : red"><?php echo empty($phoneError) ? '' : $phoneError ; ?></span></p>
                            <div class="col-md-12 form-group">
								<input type="phone" class="form-control" id="name" name="phone" placeholder="Phone" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone'">
							</div>
	
							<div class="col-md-12 form-group">
								<button type="submit" value="submit" class="primary-btn">Register </button>
								
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->

	<!-- start footer Area -->

<?php include("footer.php") ?>