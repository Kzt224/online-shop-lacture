<?php


include("header.php");


 if(!$_SESSION['logged_in'] && !$_SESSION['user_id']){
	header("location: login.php");
  }
?>

    <!-- End Banner Area -->

    <!--================Cart Area =================-->
    <section class="cart_area">
        <div class="container">
            <div class="cart_inner">
                <div class="table-responsive">
                    <?php if(!empty($_SESSION['cart'])) :?>
                        <table class="table">
                          <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Actions</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php  

                            $total = 0;
                            foreach ($_SESSION['cart'] as $key => $qty) :
                               $id = str_replace('id','',$key);
                               
                               $stmt = $pdo->prepare("SELECT * FROM products WHERE id=$id");
                               $stmt->execute();
                               $res = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                               $total += $res['price'] * $qty ;
                        
                            ?>
                                <tr>
                                <td>
                                    <div class="media">
                                        <div class="d-flex">
                                            <img src="../admin/images/<?= escape($res['image']) ?>" width="100px" height="110px">
                                        </div>
                                        <div class="media-body">
                                            <p><?= $res['name'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <h5>$<?= $res['price'] ?></h5>
                                </td>
                                <td>
                                    <div class="product_count">
                                        <input type="text" name="qty" id="sst" maxlength="12" value="<?= $qty ?>" title="Quantity:"
                                            class="input-text qty" readonly>
                                    </div>
                                </td>
                                <td>
                                    <h5>$<?= $res['price'] * $qty ?></h5>
                                </td>
                                <td>
                                    <a 
                                     style="line-height: 30px;border-radius: 10px;"
                                    class="primary-btn" href="cart_item_clear.php?pid=<?= $res['id'] ?>">Delete</a>
                                </td>
                                
                            </tr>

                            <?php endforeach ?>
                            <tr>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <h5>Subtotal</h5>
                                </td>
                                <td>
                                    <h5>$<?= $total ?></h5>
                                </td>
                            </tr>
                            
                            <tr class="out_button_area">
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                
                                <td>
                                    <div class="checkout_btn_inner d-flex align-items-center">
                                        <a class="gray_btn" href="clear.php">ClearAll</a>       
                                        <a class="primary-btn" href="index.php">Continue Shopping</a>
                                        <a class="gray_btn" href="checkout.php">Submit Order</a>       
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                   <?php endif ?>
                </div>
            </div>
        </div>
    </section><br>

    <!--================End Cart Area =================-->

    <!-- start footer Area -->
  <?php include("footer.php") ?>