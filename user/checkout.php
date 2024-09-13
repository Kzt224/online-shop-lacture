<?php



include("header.php");

 

 if(!$_SESSION['logged_in'] && !$_SESSION['user_id']){
	header("location: login.php");
  }

  
?>

    <!-- Start Banner Area -->

    <!--================Checkout Area =================-->
    <section class="checkout_area section_gap">
        <div class="container">
            
            <div class="billing_details">
                <div class="row">
                    <div class="col-lg-12">
                        <?php if(!empty($_SESSION['cart'])) : ?>
                            <div class="order_box">
                            <h2>Your Order</h2>
                            <ul class="list">
                                <li><a href="#">Product <span>Total</span></a></li>
                                <?php 

                                   $total = 0;
                                foreach ($_SESSION['cart'] as $key => $qty) :
                                    $id = str_replace('id','',$key);

                                    $stmt = $pdo->prepare("SELECT * FROM products WHERE id=$id");
                                    $stmt->execute();
                                    $res = $stmt->fetch(PDO::FETCH_ASSOC);
                                 
                                    $total += $res['price'] * $qty ;
                                    
                                    
                                    ?>
                                 <li><a href="#"><?= $res['name'] ?> <span class="middle">X <?= $qty ?>pcs</span> <span class="last">$<?= $res['price'] * $qty ?></span></a></li>


                                <?php endforeach ?>
                                
                            </ul>
                            <ul class="list list_2">
                                <li><a href="#">Total <span>$<?= $total ?></span></a></li>
                            </ul><br><br>
                             
                            <a class="primary-btn" href="confirmation.php">Proceed to Pay</a>
                        </div>

                        <?php endif ?>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Checkout Area =================-->

    <!-- start footer Area -->
  
    <?php 
    include("footer.php");
    
    ?>