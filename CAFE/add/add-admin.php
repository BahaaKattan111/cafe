<?php 
include_once '../partials/config.php'; 



if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if(isset($_SESSION['user'])){
    if(!in_array('add-admin',$_SESSION['user']['auth'])){
        header('location: http://localhost/CAFE/admin.php');
        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>".  $_SESSION['user']['username']  .", Have No access to: Add Admin Page</h5>";
        exit;
    }
}else{
        header('location: http://localhost/CAFE/login.php');
        exit;
};

            # Insert DATA
if (isset($_POST['submit'])) {
    $full_name = htmlspecialchars($_POST['full_name'], ENT_QUOTES, 'UTF-8' );
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8' );
    $password = password_hash(htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') , PASSWORD_DEFAULT);
    if (isset($_POST['access'])) {
            $access_options=  implode(',',$_POST['access']);
    }else{
            $_SESSION['COMMENT_add'] = "<h5 class='comment-failed'>Choose Accessibilties</h5>";
            header('location: http://localhost/CAFE/add/add-admin.php');
            exit;
    }

    $stmt = $conn->prepare("INSERT INTO tbl_admin (full_name, username, password, auth) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('ssss', $full_name, $username, $password, $access_options);
    if ($stmt){
        if ($stmt->execute()) {
            $_SESSION['COMMENT_add'] = "<h5 class='comment-success'>Admin Created</h5>";
            header('location: http://localhost/CAFE/add/add-admin.php');
            exit;
        }else{
            $_SESSION['COMMENT_add'] = "<h5 class='comment-failed'>Failed to Create Admin</h5>";
            header('location: http://localhost/CAFE/add/add-admin.php');
            exit;
            }
    }

    $stmt->close();
    $conn->close();

}


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
                    <a  href=<?php echo SITEURL."login.php" ?> >Log Out</a>

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
                <section>
                    <h1 class="item-title horizontal-line">New Admin</h1>
                    <?php
                                # TYPE COMMENT

                        if (isset($_SESSION['COMMENT_add'])){
                            echo $_SESSION['COMMENT_add'];
                            unset($_SESSION['COMMENT_add']);
                        }
                    ?>
                    <form method='POST' action=''>

                    <ul class="table-add">

                        <li>
                            <label class='th-add'>Full Name</label>
                                
                            <input class='tr-add' type='text' name='full_name' placeholder='Full Name' required />
                        </li>
                        
                        <li>
                            <label class='th-add'>User Name</label>
                                
                            <input class='tr-add' type='text' name='username' placeholder='User Name' required />
                        </li>

                        <li>
                            <label class='th-add'>Password</label>
                                
                            <input class='tr-add' type='text' name='password' placeholder='Password' required />
                        </li>

                        <li>
                            <label class='th-add'>Is Active</label>
                            <select class='tr-add' name='active' required>
                                <option value="yes" selected>Yes</option>
                                <option value="no">No</option>
                            </select>
                        </li>

                        <li>
                            <label class='th-add'>Accessibilty</label>
                            <ul  class="scrolling-list">
                                        <div class="option">
                                            <label for="manage-orders">Manage-Orders</label>
                                            <input  id="manage-orders" type="checkbox" name="access[]" value="manage-orders" >
                                        </div>
                                    
                                        <div class="option">
                                            <label for="manage-product">Manage-Product</label>
                                            <input  id="manage-product" type="checkbox" name="access[]" value="manage-product" >

                                        </div>
                                    
                                        <div class="option">
                                            <label for="manage-category">Manage-Category</label>
                                            <input  id="manage-category" type="checkbox" name="access[]" value="manage-category" >

                                        </div>

                                    
                                        <div class="option">
                                            <label for="manage-admin">Manage-Admin</label>
                                            <input  id="manage-admin" type="checkbox" name="access[]" value="manage-admin" >

                                        </div>
                                    
                                        <div class="option">
                                            <label for="add-admin">Edit-Admin</label>
                                            <input  id="add-admin" type="checkbox" name="access[]" value="add-admin" >
                                        </div>
                                    
                                        <div class="option">
                                            <label for="add-category">Edit-Category</label>
                                            <input  id="add-category" type="checkbox" name="access[]" value="add-category" >
                                        </div>
                                        <div class="option">
                                            <label for="add-product">Edit-Product</label>
                                            <input  id="add-product" type="checkbox" name="access[]" value="add-product" >
                                        </div>
                                    
                            </ul>
                        </li>
                    </ul>
                        <input  class='td add-btn' name='submit' type='submit' value='SAVE'/>
                    </form>

                </section>
            </div>
        </div>
    </body>
</html>
