
<?php

include("header.php");


$userId = $_SESSION['user_id'];


if(isset($_SESSION['cart'])){
	$total = 0;
    foreach ($_SESSION['cart'] as $key => $qty) {
		$id = str_replace('id','',$key);

		$stmt = $pdo->prepare("SELECT * FROM products WHERE id=$id");
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        $total += $res['price'] * $qty ;
	}

	// insert into sale_orders table

	$stmt = $pdo->prepare("INSERT INTO sale_orders(user_id,total_prices,ordered_date)
	        VALUES(:user_id,:total_price,:date)");
	 $result = $stmt->execute(
		array(':user_id' => $userId,':total_price' => $total,':date' =>date('Y-m-d H-i-s'))
	);

	if($res){
		$sale_order_id = $pdo->lastInsertId();

		foreach ($_SESSION['cart'] as $key => $qty) {
			$pid = str_replace('id','',$key);

			$stmt = $pdo->prepare("INSERT INTO sale_orders_detail(sale_order_id,product_id,quantity)
	        VALUES(:sod,:pid,:qty)");

			$result = $stmt->execute(
				array(':sod' => $sale_order_id,':pid' => $pid,':qty' => $qty)
			);

			$qtyStmt = $pdo->prepare("SELECT quantity FROM products WHERE id=$pid");
			$qtyStmt ->execute();
			$qtres = $qtyStmt->fetch(PDO::FETCH_ASSOC);

			$updatQty = $qtres['quantity'] - $qty;

			$updstmt = $pdo->prepare("UPDATE products SET quantity=:qty WHERE id=:pid");
			 $updres = $updstmt->execute(
				array(':qty' => $updatQty,':pid' => $pid)
			);


			if($updres){
				unset($_SESSION['cart']);

				
			}
		}
	}
}

?>



	<!--================Order Details Area =================-->
	<section class="order_details section_gap">
		<div class="container">
			<h3 class="title_confirmation">Thank you. Your order has been received.</h3>
			<div class="row order_d_inner">
			</div>
			<!-- <div class="order_details_table">
				<h2>Order Details</h2>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th scope="col">Product</th>
								<th scope="col">Quantity</th>
								<th scope="col">Total</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<p>Pixelstore fresh Blackberry</p>
								</td>
								<td>
									<h5>x 02</h5>
								</td>
								<td>
									<p>$720.00</p>
								</td>
							</tr>
							<tr>
								<td>
									<p>Pixelstore fresh Blackberry</p>
								</td>
								<td>
									<h5>x 02</h5>
								</td>
								<td>
									<p>$720.00</p>
								</td>
							</tr>
							<tr>
								<td>
									<p>Pixelstore fresh Blackberry</p>
								</td>
								<td>
									<h5>x 02</h5>
								</td>
								<td>
									<p>$720.00</p>
								</td>
							</tr>
							<tr>
								<td>
									<h4>Subtotal</h4>
								</td>
								<td>
									<h5></h5>
								</td>
								<td>
									<p>$2160.00</p>
								</td>
							</tr>
							<tr>
								<td>
									<h4>Shipping</h4>
								</td>
								<td>
									<h5></h5>
								</td>
								<td>
									<p>Flat rate: $50.00</p>
								</td>
							</tr>
							<tr>
								<td>
									<h4>Total</h4>
								</td>
								<td>
									<h5></h5>
								</td>
								<td>
									<p>$2210.00</p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div> -->
	</section>
	<!--================End Order Details Area =================-->

	<!-- start footer Area -->
<?php 
  include("footer.php");
?>