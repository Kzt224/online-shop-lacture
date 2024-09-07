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

// check method and insert database from form

if($_POST){

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
            
          

            move_uploaded_file($_FILES['image']['tmp_name'],$file);

            $statement = $pdo->prepare("INSERT INTO products(name,description,category_id,quantity,price,image)
                                       VALUES(:name,:description,:category_id,:quantity,:price,:image)");
            
            $result = $statement->execute(
                array(':name' => $name,':description' => $description,
                      ':category_id' => $category,':quantity' => $quantity,
                      ':price' => $price, ':image' => $image)
            );

            if($result){
              echo "<script>alert('Product add successfully');window.location.href='index.php';</script>";
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
                <div class="form-group">
                <label for="name">Name</label>
                <p style="color:red"><?php echo empty($nameError) ? '' : $nameError; ?></p>
                <input type="text" name="name" class="form-control" id="name" >
              </div>
              <div class="form-group">
                 <label for="description" >Description</label>
                 <p style="color:red"><?php echo empty($descriptionError) ? '' : $descriptionError; ?></p>
                <textarea name="description" class="form-control" rows="8" columns="80" id="description" ></textarea>
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
                    <?php 
                      foreach ($cactresult as  $value) {  ?>
                          <option value="<?php echo $value['id']?></"><?php echo $value['name'] ?></option>
                         <?php
                      }
                    ?>
                  </select>
              </div>
              <div class="form-group">
                 <label for="quantity" >Quantity</label>
                 <p style="color:red"><?php echo empty($quanError) ? '' : $quanError; ?></p>
                 <input type="number" class="form-control" name="quantity" id="quantity">
              </div>
              <div class="form-group">
                 <label for="price">Price</label>
                 <p style="color:red"><?php echo empty($priceError) ? '' : $priceError; ?></p>
                 <input type="number" class="form-control" name="price" id="price">
              </div>
              <div class="form-group">
                 <label for="image" >Image</label>
                 <p style="color:red"><?php echo empty($imgError) ? '' : $imgError; ?></p>
                 <input type="file" name="image" id="image">
              </div>
              <div class="form-group">
                 <input type="submit" value="submit" class="btn btn-success">
                 <a href="category.php" class="btn btn-warning" type="button">Back</a>
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