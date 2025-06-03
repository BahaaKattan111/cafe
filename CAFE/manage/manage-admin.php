<?php 
include '../partials/config.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../functions/search.php';

if(isset($_SESSION['user'])){
    if(!in_array('manage-admin',$_SESSION['user']['auth'])){
        header('location: http://localhost/CAFE/admin.php');
        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>".  $_SESSION['user']['username']  .", Have No access to: Manage Admin Page</h5>";
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
        
        <title>Manage Orders</title>
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
        <br>
        <a class="logout-btn" href=<?php echo SITEURL."login.php" ?> >Log Out</a>
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
                    <a href=<?php echo SITEURL."add/add-admin.php"?> class="add-btn" >Add Admin</a>
                </div>
                <h1 class=" item-title | horizontal-line" >Admins</h1>
                <ul class="table">

                <form method="GET" class='tr-table'>
                        <input type="search" name="create_date" class='th' placeholder="Last Edit"/>
                        <input type="search" name="full_name" class='th' placeholder="Full Name"/>
                        <input type="search" name="username" class='th' placeholder="User Name"/>
                        <input type="search" name="active" class='th' placeholder="Is Active"/>
                        <input type="search" name="auth" class='th' placeholder="Accessibilty"/>
                        
                        <input type="submit" class="search-btn" name="search-btn" value="Search">
                </form>
                    <?php
                    $row = ['id', 'create_date','full_name','username' ,'active','auth'];
                    
                    SEARCH($conn, $row, 'tbl_admin','admin');

                    ?>
                </ul>
            </section>
        </div>
    </div>
    </div>
    </body>
</html>