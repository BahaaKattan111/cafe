<?php 
include_once '../partials/config.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../functions/search.php';

if(isset($_SESSION['user'])){
    if(!in_array('manage-product',$_SESSION['user']['auth'])){
        header('location: http://localhost/CAFE/admin.php');
        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>".  $_SESSION['user']['username']  .", Have No access to: Manage Product Page</h5>";
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
        
        <title>Manage Products</title>
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
                            <a href=<?php echo SITEURL."manage/manage-orders.php" ?> >Orders</a>
                            <a href=<?php echo SITEURL."manage/manage-category.php" ?> >Categories</a>
                            <a href=<?php echo SITEURL."manage/manage-admin.php" ?>> Admins</a>
                            <a href=<?php echo SITEURL."manage/manage-product.php" ?> >Products</a>
                </nav>
            </header>
        </div>
        <?php
        if(isset($_SESSION['COMMENT_add'])){
            echo  $_SESSION['COMMENT_add'];
            unset($_SESSION['COMMENT_add']);
        }   
        ?>

        <div class="wrapper">
            <div class="user-details">
                    <h5 class="username">
                        User Name:  <span class="session-info"><?php echo $_SESSION['user']['username'] ?></span>
                    </h5>

                    <h5 class="auth">
                        Access:  <span class="session-info"><?php echo implode(' | ',$_SESSION['user']['auth']) ?></span>
                    </h5>
            </div>
        </div>

    <div class="wrapper">


            <section >
            <div class="manage-header">
                <a href=<?php echo SITEURL."add/add-product.php"?> class="add-btn" >Add Product</a>
            </div>

            <h1 class=" item-title | horizontal-line" >Products</h1>

            
                <ul class="table">

                <form method="GET" class='tr-table'>
                        
                        <input type="search" name="image_source" class='th' placeholder="Image"/>
                        <input type="search" name="name" class='th' placeholder="Product Name"/>
                        <input type="search" name="category" class='th' placeholder="Category"/>
                        <input type="search" name="active" class='th' placeholder="Active"/>
                        <input type="search" name="featured" class='th' placeholder="Featured"/>
                        <input type="search" name="details" class='th' placeholder="Details"/>
                        <input type="search" name="ingredients" class='th' placeholder="Ingredients"/>
                        <input type="search" name="price" class='th' placeholder="Price"/>
                        

                        <input type="submit" class="search-btn" name="search-btn" value="Search">
                </form>
<?php
                    $row = ['id','image_source','name','category','active','featured','details','ingredients','price'];
                    
                    SEARCH($conn, $row, 'tbl_product','product');

                    ?>
                </ul>
            </section>
        </div>
</div>
</div>
</body>
</html>