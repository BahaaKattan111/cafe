<?php 
include 'partials/config.php'; 

if(!isset($_SESSION['user'])){
    header("location: http://localhost/CAFE/login.php");
    $conn->close();
    $stmt->close();
    exit;
}
?>


<!DOCTYPE html>
<html>
    <head>
        
        <title></title>
        <link rel="stylesheet" href='<?php echo "admin.css" . "?v= ".time(); ?>'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>

    <body>
    <div class="main-container">
    <div class="blur-background">

        <?php
            if (isset($_SESSION["COMMENT_add"])){
                echo $_SESSION['COMMENT_add'];
                unset( $_SESSION['COMMENT_add']);
            };

            ?>
        <br>
        <a class="logout-btn" href=http://localhost/CAFE/login.php >Log out</a>
        <br>
        <br>

        <div class="wrapper">
    
            <div class="user-details">

                    <h5 class="username">
                        User Name:  <span class="session-info"><?php echo $_SESSION['user']['username'] ?></span>
                    </h5>

                    <h5 class="auth">
                        Access:  <span class="session-info"><?php echo implode(' | ',$_SESSION['user']['auth']) ?></span>
                    </h5>
            </div>
        </div>        <div class="wrapper">
            <section class='main-ul' >

                    <ul >
                        <a href="manage/manage-admin.php">Manage Admin</a>
                        <a href="manage/manage-category.php">Manage Category</a>
                        <a href="manage/manage-orders.php">Manage Orders</a>
                        <a href="manage/manage-product.php">Manage Product</a>
                    </ul>

                    <ul>
                        <a href="add/add-admin.php">Add Admin</a>
                        <a href="add/add-category.php">Add Category</a>
                        <a href="add/add-product.php">Add Product</a>
                    </ul>
            </section>
        </div>
        </div>
        </div>

    </body>
</html>