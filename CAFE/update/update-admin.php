<?php 
include_once '../partials/config.php'; 
include_once '../functions/utils.php'; 

if(isset($_SESSION['user'])){
    if(!in_array('add-admin',$_SESSION['user']['auth'])){
        
        header('location: http://localhost/CAFE//manage/manage-admin.php');
        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>".  $_SESSION['user']['username']  .", Have No access to: Update Admin Page</h5>";
        exit;
    }
}else{
        header('location: http://localhost/CAFE/login.php');
        exit;
};

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


            # Insert DATA
            if (isset($_POST['submit'])) {
                $full_name = htmlspecialchars($_POST['full_name'], ENT_QUOTES, 'UTF-8' );
                $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8' );
                $is_active = htmlspecialchars($_POST['active'], ENT_QUOTES, 'UTF-8' );
                $password = password_hash(htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') , PASSWORD_DEFAULT);
                $access_options=  implode(',',$_POST['access']);

                if (!empty($_POST['password'])){
                   $stmt = $conn->prepare("UPDATE tbl_admin SET auth=? ,full_name=?,active=?, username=?, password= ? WHERE id=?");
                    $stmt->bind_param('ssssi', $access_options ,$full_name,$is_active, $username, $password,$_GET['id']);

                }else{
                    $stmt = $conn->prepare("UPDATE tbl_admin SET auth=?, full_name=?,active=? , username=?  WHERE id=?");
                    $stmt->bind_param('ssssi', $access_options, $full_name, $is_active, $username, $_GET['id']);

                };
                if ($stmt){
                    if ($stmt->execute()) {
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-success-2'>Admin Updated</h5>";
                        $stmt->close();
                        $conn->close();
                        header("location: http://localhost/CAFE/manage/manage-admin.php");
                        exit;
                    }else{
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Failed to Updated Admin</h5>";
                        $stmt->close();
                        $conn->close();
                        header("location: http://localhost/CAFE/manage/manage-admin.php");
                        exit;
                        }
                };

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
                    <a class="logout-btn" href=<?php echo SITEURL."login.php" ?> >LogOut</a>
                <nav >

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
        <h1 class="item-title horizontal-line">Update Admin</h1>
        <?php
                    # TYPE COMMENT

            $stmt = $conn->prepare('SELECT * FROM tbl_admin WHERE id= ?');
            $stmt->bind_param('s', $_GET['id']);
            if($stmt->execute()){
               $res= $stmt->get_result();

                while($row = $res->fetch_assoc()){
                    ?>


        <form method='POST' action=''>

        <ul class="table-add">

            <li>
                <label class='th-add'>Full Name</label>
                    
                <input class='tr-add' type='text' name='full_name' value="<?php echo $row['full_name']; ?>" placeholder='Full Name' required />
            </li>
            
            <li>
                <label class='th-add'>User Name</label>
                    
                <input class='tr-add' type='text' name='username' value="<?php echo $row['username']; ?>" placeholder='User Name' required />
            </li>

            <li>
                <label class='th-add'>Password</label>
                    
                <input class='tr-add' type='text' name='password' placeholder='New Password or Keep Old Password ' />

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
                
                <?php
                $auth = explode(',',$row['auth']); 
                ?>
                <ul  class="scrolling-list">
                            <div class="option">
                                <label for="manage-orders">Manage-Orders</label>
                                <input  id="manage-orders" type="checkbox"   <?php check_if_option_in_array($auth,'manage-orders'); ?> name="access[]" value="manage-orders" >

                            </div>
                        
                            <div class="option">
                                <label for="manage-product">Manage-Product</label>
                                <input  id="manage-product" type="checkbox" <?php check_if_option_in_array($auth,'manage-product'); ?> name="access[]" value="manage-product" >

                            </div>
                        
                            <div class="option">
                                <label for="manage-category">Manage-Category</label>
                                <input  id="manage-category" type="checkbox" <?php check_if_option_in_array($auth,'manage-category'); ?> name="access[]" value="manage-category" >

                            </div>

                        
                            <div class="option">
                                <label for="manage-admin">Manage-Admin</label>
                                <input  id="manage-admin" type="checkbox" <?php check_if_option_in_array($auth,'manage-admin'); ?> name="access[]" value="manage-admin" >

                            </div>
                        
                            <div class="option">
                                <label for="add-admin">Add-Admin</label>
                                <input  id="add-admin" type="checkbox" <?php check_if_option_in_array($auth,'add-admin'); ?> name="access[]" value="add-admin" >

                            </div>
                        
                            <div class="option">
                                <label for="add-category">Add-Category</label>
                                <input  id="add-category" type="checkbox" <?php check_if_option_in_array($auth,'add-category'); ?> name="access[]" value="add-category" >
                            </div>
                            <div class="option">
                                <label for="add-product">Add-Product</label>
                                <input  id="add-product" type="checkbox" <?php check_if_option_in_array($auth,'add-product'); ?> name="access[]" value="add-product" >
                            </div>
                        
                </ul>
            </li>
        </ul>
            <input  class='td add-btn' name='submit' type='submit' value='UPDATE'/>
        </form>

        <?php
                };

            };?>



    </section>
</div>
</div>
</div>
</body>
</html>
