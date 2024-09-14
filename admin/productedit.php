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

    if(!empty($_GET['id'])){
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);
        $res = $stmt->execute();
        $res = $stmt->fetchAll();
    }

    if(!empty($_POST)){

      if(empty($_POST['name']) || empty($_POST['description']) 
          || empty($_POST['quantity']) || empty($_POST['price'])
          || empty($_FILES['image'])   || empty($_POST['category'])

         ){
           if(empty($_POST['name'])){
           $nameError = " Name cannot be null";
           }
           if(empty($_POST['description'])){
           $descriptionError = "description  cannot be null";
           }
           if(empty($_POST['category'])){
           $catError = "Category  cannot be null";
           }
           if(empty($_FILES['image'])){
           $imgError = "Image  cannot be null";
           }
           if(empty($_POST['quantity'])){
           $quanError = "quantity  cannot be null";
   
           }elseif((is_numeric($_POST['quantity']) != 1)){
               $quanError = "quantity  access only number";
           }
           if(empty($_POST['price'])){
           $priceError = "price cannot be null";
           }elseif($_POST['price'] && (is_numeric($_POST['price']) != 1)){
               $priceError = "price  access only number";
           }
   
       }else{

          if((is_numeric($_POST['price']) != 1)){
              $priceError = "price  access only number";
          }else{
            $priceError = '';
          }
          if((is_numeric($_POST['quantity']) != 1)){
            $quanError = "quantity  access only number";
          }else{
            $quanError = '';
          }
          
          if($quanError == '' && $priceError == ''){ 
              

            if(($_FILES['image']['name'] != null )){ 
             
              $file = 'images/'.($_FILES['image']['name']);
              $image_type = pathinfo($file,PATHINFO_EXTENSION);
     
              if($image_type != 'jpeg' && $image_type != 'jpg' && $image_type != 'png'){
                 echo "<script>alert('Image type must be jpg or jpeg or png')</script>";
              }else{
                 $name = $_POST['name'];
                 $description = $_POST['description'];
                 $price = $_POST['price'];
                 $category = $_POST['category'];
                 $quantity = $_POST['quantity'];
                 $image = $_FILES['image']['name'];
                 $id = $_POST['id'];
  
                 move_uploaded_file($_FILES['image']['tmp_name'],$file);
         
                $stmt = $pdo->prepare("UPDATE products SET name=:name,description=:description,
                       category_id=:category_id,price=:price,quantity=:quantity,image=:image WHERE id=".$_POST['id']);
                 
                 $result = $stmt->execute(
                     array(':name' => $name,':description' => $description,
                           ':category_id' => $category,':quantity' => $quantity,
                           ':price' => $price, ':image' => $image)
                 );
     
                 if($result){
                   echo "<script>alert('Product edited successfully');window.location.href='index.php';</script>";
                 }
              }
             }else{
              $name = $_POST['name'];
              $description = $_POST['description'];
              $price = $_POST['price'];
              $category = $_POST['category'];
              $quantity = $_POST['quantity'];
              
               
              $stmt = $pdo->prepare("UPDATE products SET name = :name,description = :description,
                       category_id = :category_id,price=:price,quantity=:quantity WHERE id=".$_POST['id']);
  
                $result = $stmt->execute(
                  array(':name'=> $name,':description' => $description,
                        ':category_id' => $category,':quantity' => $quantity,
                        ':price' => $price)
                );
                            // 
  
              if($result){
              echo "<script>alert('Product edited successfully');window.location.href='index.php';</script>";
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
                 <input type="hidden" name="id" value="<?php echo $res[0]['id'];?>">
                <div class="form-group">
                <label for="name">Name</label>
                <p style="color:red"><?php echo empty($nameError) ? '' : $nameError; ?></p>
                <input type="text" name="name" class="form-control" id="name" value="<?= $res[0]['name'] ?>" >
              </div>
              <div class="form-group">
                 <label for="description" >Description</label>
                 <p style="color:red"><?php echo empty($descriptionError) ? '' : $descriptionError; ?></p>
                <textarea name="description" class="form-control" rows="8" columns="30" id="description" ><?= $res[0]['description'] ?></textarea>
              </div>
              <div class="form-group">
                 <label for="category">Category</label>
                 <p style="color:red"><?php echo empty($catError) ? '' : $catError; ?></p>
                 <?php 
                   $catstatement = $pdo->prepare("SELECT * FROM categories");
                   $catstatement->execute();
                   $cactresult = $catstatement->fetchAll();
                   
                 ?>
                  <select name="category" id="category" class="form-control">
                    <option value="">SelectCategory</option>
                    <?php foreach ($cactresult as  $value) {  ?>
                          
                      <?php  if($value['id'] === $res[0]['category_id']) : ?>

                       <option value="<?php echo $value['id']?>" selected><?php echo $value['name'] ?></option>

                      <?php else : ?>
                      <option value="<?php echo $value['id']?>"><?php echo $value['name'] ?></option>
                      <?php endif ?>
                    <?php }?>
                  </select>
              </div>
              <div class="form-group">
                 <label for="quantity" >Quantity</label>
                 <p style="color:red"><?php echo empty($quanError) ? '' : $quanError; ?></p>
                 <input type="number" class="form-control" name="quantity" id="quantity" value="<?= $res[0]['quantity'] ?>" >
              </div>
              <div class="form-group">
                 <label for="price">Price</label>
                 <p style="color:red"><?php echo empty($priceError) ? '' : $priceError; ?></p>
                 <input type="number" class="form-control" name="price" id="price" value="<?= $res[0]['price'] ?>">
              </div>
              <div class="form-group">
                 <label for="image" >Image</label>
                 <p style="color:red"><?php echo empty($imgError) ? '' : $imgError; ?></p>
                 <img src="images/<?= $res[0]['image'] ?>" alt="" style="width: 150px"><br><br>
                 <input type="file" name="image" id="image">
              </div>
              <div class="form-group">
                 <input type="submit" value="submit" class="btn btn-success">
                 <a href="index.php" class="btn btn-warning" type="button">Back</a>
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