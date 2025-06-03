<?php 
include_once '../partials/config.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
};

 if(isset($_SESSION['user'])){
    if(!in_array('add-product',$_SESSION['user']['auth'])){
        header('location: http://localhost/CAFE/manage/manage-product.php');
        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>".  $_SESSION['user']['username']  .", Have No access to: Update product Page</h5>";
        exit;
    }
}else{
        header('location: http://localhost/CAFE/login.php');
        exit;
};

?>
<?php
           # Insert DATA
            if (isset($_POST['submit'])) {
                $ID = $_POST['ID'];

                $name = htmlspecialchars($_POST['name'],ENT_QUOTES,'UTF-8');
                $ingredients = htmlspecialchars($_POST['ingredients'],ENT_QUOTES,'UTF-8');
                $details = htmlspecialchars($_POST['details'],ENT_QUOTES,'UTF-8');
               
                $price = htmlspecialchars($_POST['price'],ENT_QUOTES,'UTF-8');
                $category = htmlspecialchars($_POST['category'],ENT_QUOTES,'UTF-8');
                $active = htmlspecialchars($_POST['active'],ENT_QUOTES,'UTF-8');

                $featured = htmlspecialchars($_POST['featured'],ENT_QUOTES,'UTF-8');
                if(!empty($_FILES['image_path']['tmp_name'])){
                    $image_details = $_FILES['image_path'];


                    #remove file, so we can create new one: 
                    $ext =  pathinfo($image_details['name'],PATHINFO_EXTENSION );
                    
                    $folder_path = "images/product/product_".$ID. '.' .$ext;
                    ####
                    $image_orig_path = $image_details['tmp_name'];


                    $mime = mime_content_type($image_orig_path);
                    $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif','image/webp', 'image/svg+xml'];

                    # avoid server crash if file above than 2Megabyte;
                    if(!in_array($mime,$allowed_mimes)) {
                            $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Not Image Format (EX: png, jpg, jpeg, webp, gif, svg )</h5>";
                            header("location: http://localhost/CAFE/update/update-product.php?id=".$ID );
                            exit;
                    };

                    if($image_details['size'] > ((1024*1024) *2) ){
                            $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>File Very Large </h5>";
                            header("location: http://localhost/CAFE/update/update-product.php?id=".$ID );
                            exit;

                    };
                    if(!move_uploaded_file($image_orig_path,'../'.$folder_path)){
                            $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Failed to Upload image </h5>";
                            header("location: http://localhost/CAFE/update/update-product.php?id=".$ID );
                            exit;
                    };
                    $stmt = $conn->prepare("UPDATE tbl_product SET name = ?, details = ?, ingredients = ?, price = ?, category = ?, active = ?, featured = ?, image_source = ? WHERE id = ?");
                    $stmt->bind_param('ssssssssi', $name , $details , $ingredients , $price , $category , $active , $featured , $folder_path, $ID);

                }else{
                    $stmt = $conn->prepare("UPDATE tbl_product SET name = ?, details = ?, ingredients = ?, price = ?, category = ?, active = ?, featured = ? WHERE id = ?");
                    $stmt->bind_param('sssssssi', $name , $details , $ingredients , $price , $category , $active , $featured , $ID);

                };
                
                ####
                if ($stmt){
                    if ($stmt->execute()) {
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-success-2'>Product Updated</h5>";
                        header("location: http://localhost/CAFE/manage/manage-product.php?id=".$ID );
                        exit;

                    }else{
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Failed to Update product</h5>";
                        header("location: http://localhost/CAFE/manage/manage-product.php?id=".$ID );
                        exit;
                        };
                };


            };
?>
<!DOCTYPE html>
<html>
    <head>
        
        <title></title>
        <link rel="stylesheet" href='<?php echo "../admin.css" . "?v= ".time(); ?>'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>

    <body>
        <div class="main-container">
        <div class="blur-background">
        <div class="wrapper">
            <header class='horizontal-line'>
                    <a class="logout-btn" href=<?php echo SITEURL."login.php" ?> >LogOut</a>
                <nav>

                    <ul>
                            <a href=<?php echo SITEURL."manage/manage-orders.php" ?> >Orders</a>
                            <a href=<?php echo SITEURL."manage/manage-category.php" ?> >Categories</a>
                            <a href=<?php echo SITEURL."manage/manage-admin.php" ?>> Admins</a>
                            <a href=<?php echo SITEURL."manage/manage-product.php" ?> >Products</a>
                    </ul>
                </nav>
            </header>
        </div>
<br>

<div class="wrapper">
    <section>
        <h1 class="item-title horizontal-line">Update Product</h1>
        <?php

            $stmt = $conn->prepare("SELECT * FROM tbl_product WHERE id= ? ");
            $ID=  $_GET['id']; 
            $stmt->bind_param('i', $ID);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {


            ?>
        <form method='POST' enctype='multipart/form-data'>
            <ul class="table-add">
                <li>
                    <label class='th-add'>Product Name</label>
                    
                    <input type='hidden' name='ID'  value="<?php echo $row['id'];?>" required />
                    <input class='tr-add' type='text' name='name' placeholder='Product Name' value="<?php echo $row['name'];?>" required />
                </li>
                <li>
                    <label class='th-add'>Details</label>
                    
                    <input class='tr-add' type='text' name='details' placeholder='Details' value="<?php echo $row['details'];?>" required />
                </li>
                <li>
                    <label class='th-add'>Ingredient</label>
                    
                    <input class='tr-add' type='text' name='ingredients' placeholder='Ingredients' value="<?php echo $row['ingredients'];?>" required />
                </li>
                <li>
                    <label class='th-add'>Price</label>
                    
                    <input class='tr-add' type='text' name='price' placeholder='Price' value="<?php echo $row['price'];?>" required />
                </li>

                <li>
                    <label class='th-add'>Category</label>
                    <ul  class="scrolling-list">

                    <?php
                    # TYPE COMMENT

                    $stmt = $conn->prepare('SELECT DISTINCT name FROM tbl_category');
                    if($stmt->execute()){
                        $res= $stmt->get_result();
                        while($row2 = $res->fetch_assoc()){

                        echo "<div class='option'>";
                        echo "    <label for='manage-orders'>". $row2['name']. " </label>";
                        echo "<input  id='manage-orders' type='checkbox' name='access[]' value=" .$row2['name']. " >";
                        echo "</div>";
                                    
                        };
                    };
                    ?>
                    </ul>  
                </li>
                <li>
                    <label class='th-add'>Image</label>
                    
                    <div class="tr">
                        <label for="image-file">
                            <input  id="image-file" type='file' name='image_path'/>
                            Add Image
                        </label>
                            <img  src="<?php echo '../'.$row['image_source'] ;?>" alt="">
                    </div>
                </li>
                <li>
                    <label class='th-add'>Active</label>
                    
                    <div class='td-actions'>
                    <?php
                        if ($row['active'] == 'yes') {?>
                        <label for="active-yes" class="delete-btn"> 
                            <input type="radio" id="active-yes" <?php echo 'checked';?>  name='active' value='yes'>
                            Yes
                        </label>
                        <label for="active-no" class="update-btn"> 
                            <input type="radio"   id="active-no" name='active' value='no'>
                            No
                        </label>
                    <?php }else{

                    ?>
                        <label for="active-yes" class="delete-btn"> 
                            <input type="radio" id="active-yes"  name='active' value='yes'>
                            Yes
                        </label>
                        <label for="active-no" class="update-btn"> 
                            <input type="radio"   id="active-no" <?php echo 'checked';?>  name='active' value='no'>
                            No
                        </label>

                    <?php  
            };
                    ?>
                    </div>
                </li>

                                <li>
                    <label class='th-add'>Featured</label>
                    
                    <div class='td-actions '>
                    <?php
                        if ($row['featured'] == 'yes') {?>
                        <label for="featured-yes" class="delete-btn"> 
                            <input type="radio" id="featured-yes" <?php echo 'checked';?>  name='featured' value='yes'>
                            Yes
                        </label>
                        <label for="featured-no" class="update-btn"> 
                            <input type="radio"   id="featured-no" name='featured' value='no'>
                            No
                        </label>
                    <?php }else{

                    ?>
                        <label for="featured-yes" class="delete-btn"> 
                            <input type="radio" id="featured-yes"  name='featured' value='yes'>
                            Yes
                        </label>
                        <label for="featured-no" class="update-btn"> 
                            <input type="radio"   id="featured-no" <?php echo 'checked';?>  name='featured' value='no'>
                            No
                        </label>

                    <?php  
            };
                    ?>
                    </div>
                </li>

            
        </ul>
            <input  type='submit' class='add-btn' name='submit'  value='UPDATE' />

        </form>

        <?php
            
             };?>
    </section>
</div>
</div>
</div>
</body>
</html>
