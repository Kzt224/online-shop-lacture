<?php
 session_start();

 include("../config/config.php");
 include("../config/common.php");

 if(!$_SESSION['logged_in'] && !$_SESSION['user_id']){
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

	$numberOfrecs = 6;
	$offSet = ($pageno -1) * $numberOfrecs ;


	if(!empty($_GET['id'])){
        $id = $_GET['id'];

		$pstmt = $pdo->prepare("SELECT * FROM products WHERE category_id = $id");
		$pstmt->execute();
		$rawresult = $pstmt->fetchAll();

		$total_pages = ceil(count($rawresult) / $numberOfrecs);

		$statement = $pdo->prepare("SELECT * FROM products WHERE  category_id = $id  ORDER BY id DESC LIMIT $offSet,$numberOfrecs ");
		$statement->execute();
		$result = $statement->fetchAll();
		
	}else{
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
				$statement = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%'  ORDER BY id DESC");
				$statement->execute();
				$rawresult = $statement->fetchAll();
				$total_pages = ceil(count($rawesult) / $numberOfrecs);

				$statement = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%'  ORDER BY id DESC LIMIT $offSet,$numberOfrecs ");
				$statement->execute();
				$result = $statement->fetchAll();
		}
	}

     
?>

<?php include('header.php') ?>
     <!-- End Banner Area -->
	<div class="container">
      <?php  
	    $catstmt = $pdo->prepare("SELECT * FROM categories");
		$catstmt->execute();
		$catres = $catstmt->fetchAll();      

	 ?>
	    
		<div class="row">
			<div class="col-xl-3 col-lg-4 col-md-5">
				<div class="sidebar-categories">
					<div class="head">Products Categories</div>
					<ul class="main-categories">
					      <ul class="" id="fruitsVegetable">
							 <?php
							   foreach ($catres as $value) { ?>
								  <li class="main-nav-list child"><a href="?id=<?= $value['id'] ?>"><?= escape($value['name']) ?></a></li>
								<?php
							   }
							 
							 ?>
						  </ul>
					</ul>
				</div>
			</div>
			<div class="col-xl-9 col-lg-8 col-md-7">
        <!-- Start Filter Bar -->
				<div class="filter-bar d-flex flex-wrap align-items-center">
					<div class="pagination">
						<a href="?=pageno=1" class="active">First</a>
						<a <?php if($pageno <= 1){echo 'disabled';} ?> href="<?php if($pageno <= 1){echo '#';}else{echo "?pageno=".($pageno -1 );} ?>" class="prev-arrow">
							<i class="fa fa-long-arrow-left" aria-hidden="true"></i>
						</a>
						<a href="#"><?= $pageno ?></a>
						<a <?php if($pageno >= $total_pages){echo 'disabled';} ?> href="<?php if($pageno >= $total_pages){echo "#";}else{echo "?pageno=".($pageno +1);} ?>" class="next-arrow">
							<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
						</a>
						<a href="?pageno=<?= $total_pages ?>" class="active">Last</a>
					</div>
				</div>
				<!-- End Filter Bar -->
				<!-- Start Best Seller -->
				<section class="lattest-product-area pb-40 category-list"><br>
					<div class="row">
						<?php
						   foreach ($result as  $value) { ?>
							 
							 <div class="col-lg-4 col-md-6">
							<div class="single-product">
								<a href="product_detail.php?id=<?=$value['id']?>"><img class="img-fluid" src="../admin/images/<?= $value['image'] ?>" alt="" style="height: 250px !important;"></a>
								 <div class="product-details">
									<h6><?= escape( $value['name'] ) ?></h6>
									<div class="price">
										<h6>$<?= escape($value['price'] ) ?></h6>
										<h6 class="l-through">$210.00</h6>
									</div>
									<div class="prd-bottom">

										<a href="cart.php?id=<?= $value['id'] ?>" class="social-info">
											<span class="ti-bag"></span>
											<p class="hover-text">add to bag</p>
										</a>
										<a href="product_detail.php?id=<?= $value['id'] ?>" class="social-info">
											<span class="lnr lnr-move"></span>
											<p class="hover-text">view more</p>
										</a>
									</div>
								</div>
							</div>
						</div>

							<?php
						   }
						?>
						<!-- single product -->
						
					</div>
				</section>
				<!-- End Best Seller -->
			</div>
		</div>
	</div>
<br><br>

<?php
 include("footer.php");

?>

