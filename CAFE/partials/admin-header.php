
<!DOCTYPE html>
<html>
    <head>
        
        <title>Control Panel</title>

        <link rel="stylesheet" href= <?php echo  'admin.css?v=' .time(); ?> >
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>

    <body>
        <div class="main-container">
        <div class="blur-background">
        <div class="wrapper">
            
            <header class='horizontal-line'>
                <a href="../admin.php">Main</a>
                <nav >

                    <ul >
                        <a href=<?php echo SITEURL."manage/manage-orders.php" ?> >Manage Orders</a>
                        <a href=<?php echo SITEURL."manage/manage-category.php" ?> >Manage Category</a>
                        <a href=<?php echo SITEURL."manage/manage-admin.php" ?>> Manage Admin</a>
                        <a href=<?php echo SITEURL."manage/manage-product.php" ?> >Manage Product</a>
                    </ul>


                </nav>


                <div class="ham-btn">
                    meinu
                </div>
            </header>
        </div>
        <a class="logout-btn" href=http://localhost/CAFE/login.php >Log out</a>

        <?php 
        if(isset($_SESSION['COMMENT_add'])){
            echo  $_SESSION['COMMENT_add'];
            unset($_SESSION['COMMENT_add']);
        }   
        ?>
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
        </div>
            <br>
            <br>
