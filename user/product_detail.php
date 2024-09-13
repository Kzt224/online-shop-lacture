<?php 


 include('header.php') ?>


<?php 


if(isset($_GET['id'])){

	$stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
	$stmt->execute();
	$res = $stmt->fetch(PDO::FETCH_ASSOC);

	$catid = $res['category_id'];
}
// prepare query for category


$ctstmt = $pdo->prepare("SELECT * FROM categories WHERE id=$catid");
$ctstmt->execute();
$ctres = $ctstmt->fetch(PDO::FETCH_ASSOC);

?>


<div class="product_image_area" style="padding-top: 0 !important;">
		<div class="container">
			<div class="row s_product_inner">
				<div class="col-lg-6">
					
						<div class="single-prd-item">
							<img class="img-fluid" src="../admin/images/<?= $res['image'] ?>" alt="">
						
					</div>
				</div>
				<div class="col-lg-5 offset-lg-1">
					<div class="s_product_text">
						<h3><?= escape($res['name']) ?></h3>
						<h2>$<?= escape($res['price']) ?></h2>
						<ul class="list">
							<li><a class="active" href="#"><span>Category</span> : <?= escape($ctres['name']) ?></a></li>
							<li><a href="#"><span>Availibility</span> : In Stock <?= $res['quantity'] ?> pcs</a></li>
						</ul>
						<p><?= $res['description'] ?></p>
						<form action="addcart.php" method="POST">
							<input type="hidden" name="_token" value="<?php echo $_SESSION['_token'];?>">
							<input type="hidden" name="id" value="<?= $res['id'] ?>">
								<div class="product_count">
									<label for="qty">Quantity:</label>
									<input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
									<button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
									class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
									<button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
									class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
								</div>
							  <div class="card_area d-flex align-items-center">
								<button type="submit" class="primary-btn" style="border: 1px;" >Add to Cart</button>
								<a class="primary-btn" href="index.php">Back</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
    <br><br><br>

<?php
 include("footer.php");

?>

