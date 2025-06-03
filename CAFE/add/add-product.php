<?php 
include_once '../partials/config.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if(isset($_SESSION['user'])){
    if(!in_array('add-product',$_SESSION['user']['auth'])){
        
        header('location: http://localhost/CAFE/admin.php');
        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>".  $_SESSION['user']['username']  .", Have No access to: Add Product Page</h5>";
        exit;
    }
}else{
        header('location: http://localhost/CAFE/login.php');
        exit;
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
        <h1 class="item-title horizontal-line">New Product</h1>
        <?php
            # TYPE COMMENT

            if (isset($_SESSION['COMMENT_add'])){
                echo $_SESSION['COMMENT_add'];
                unset($_SESSION['COMMENT_add']);
            };


            ?>
        <form method='GET' action='' enctype='multipart/form-data'>
            <ul class="table-add">
                <li>
                    <label class='th-add'>Product Name</label>
                    
                    <input class='tr-add' type='text' name='name' placeholder='Product Name' required />
                </li>
                <li>
                    <label class='th-add'>Details</label>
                    
                    <input class='tr-add' type='text' name='details' placeholder='Details' required />
                </li>
                <li>
                    <label class='th-add'>Ingredient</label>
                    
                    <input class='tr-add' type='text' name='ingredients' placeholder='Ingredients' required />
                </li>
                <li>
                    <label class='th-add'>Price</label>
                    
                    <input class='tr-add' type='text' name='price' placeholder='Price' required />
                </li>
                <li>
                    <label class='th-add'>Image</label>
                    
                    <label for="image-file">
                        <input class='tr-add'  id="image-file" type='file' name='image_path' placeholder='Image path' required />
                        Enter Image File    
                    
                    </label>
                </li>
                <li>

                    <label class='th-add'>Category</label>
                    <ul  class="scrolling-list">

<?php
# TYPE COMMENT

$stmt = $conn->prepare('SELECT DISTINCT name FROM tbl_category');
if($stmt->execute()){
    $res= $stmt->get_result();
    while($row = $res->fetch_assoc()){

?>
                    <div class="option">
                        <label for="manage-orders"><?php echo $row['name'] ?> </label>
                        <input  id="manage-orders" type="checkbox" name="access[]" value="<?php echo $row['name'] ?>" >
                    </div>
                                    
<?php
    };
};
?>

                    </ul>

                </li>
                <li>
                    <label class='th-add'>Active</label>
                    
                    <div class='td-actions | tr-add'>
                        <label for="active-yes" class="delete-btn"> 
                            <input type="radio" id="active-yes" checked  name='active' value='yes'>
                            Yes
                        </label>
                        <label for="active-no" class="update-btn"> 
                            <input type="radio"   id="active-no" name='active' value='no'>
                            No
                        </label>
                    </div>
                </li>

                <li>
                    <label class='th-add'>Featured</label>
                    
                    <div class='td-actions | th-add'>
                        <label for="featured-yes" class="delete-btn"> 
                            <input type="radio" id="featured-yes"   name='featured' value='yes'>
                            Yes
                        </label>

                        <label for="featured-no" class="update-btn"> 
                            <input type="radio"   id="featured-no" checked name='featured' value='no'>
                            No
                        </label>
                    </div>
                </li>

        </ul>
            <input  class='add-btn' name='submit' type='submit' value='SAVE'/>

        </form>
        <?php

            # Insert DATA
            if (isset($_GET['submit'])) {

                $name = htmlspecialchars($_GET['name'],ENT_QUOTES,'UTF-8');
                $ingredients = htmlspecialchars($_GET['ingredients'],ENT_QUOTES,'UTF-8');
                $details = htmlspecialchars($_GET['details'],ENT_QUOTES,'UTF-8');
                $price = htmlspecialchars($_GET['price'],ENT_QUOTES,'UTF-8');
                $category = htmlspecialchars($_GET['category'],ENT_QUOTES,'UTF-8');
                
                $active = htmlspecialchars($_GET['active'],ENT_QUOTES,'UTF-8');
                $featured = htmlspecialchars($_GET['featured'],ENT_QUOTES,'UTF-8');
                
                $image_details = $_FILES['image_path'];

                $image_orig_path = $image_details['tmp_name'];

                $ext =  pathinfo($image_details['name'],PATHINFO_EXTENSION );

                $mime = mime_content_type($image_orig_path);
                $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif','image/webp', 'image/svg+xml'];


                $folder_path = "images/product/".$name.'_'.rand(000,999).'.'.$ext;

                # avoid server crash if file above than 2Megabyte;
                if(!in_array($mime,$allowed_mimes)) {
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Not Image Format (EX: png, jpg, jpeg, webp, gif, svg )</h5>";
                        header('location: http://localhost/CAFE/add/add-product.php');
                        exit;
                };

                if($image_details['size'] > ((1024*1024) *2) ){
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>File Very Large </h5>";
                        header('location: http://localhost/CAFE/add/add-product.php');
                        exit;

                };
                if(!move_uploaded_file($image_orig_path,'../'.$folder_path)){
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Failed to Upload image </h5>";
                        header('location: http://localhost/CAFE/add/add-product.php');
                        exit;
                };

                
                ####
                $stmt = $conn->prepare("INSERT INTO tbl_product (name, details,ingredients, price, category, active, featured, image_source) VALUES (?, ?, ?, ?,?, ?, ?, ?)");
                $stmt->bind_param('sssdssss', $name, $details ,$ingredients, $price, $category, $active, $featured, $folder_path);
                if ($stmt){
                    if ($stmt->execute()) {
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-success-2'>Product Created</h5>";
                        header('location: http://localhost/CAFE/add/add-product.php');
                        exit;

                    }else{
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Failed to Create Product</h5>";
                        header('location: http://localhost/CAFE/add/add-product.php');
                        exit;
                        }
                }


            }


            ?>
    </section>
</div>
</div>
</div>
</body>
</html>
