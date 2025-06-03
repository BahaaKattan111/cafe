<?php 
include_once '../partials/config.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(isset($_SESSION['user'])){
    if(!in_array('add-category',$_SESSION['user']['auth'])){
        header('location: http://localhost/CAFE/admin.php');
        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>".  $_SESSION['user']['username']  .", Have No access to: Add Category Page</h5>";
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

        <h1 class="item-title horizontal-line">New Category</h1>
        <?php
            # TYPE COMMENT

            if (isset($_SESSION['COMMENT_add'])){
                echo $_SESSION['COMMENT_add'];
                unset($_SESSION['COMMENT_add']);
            };
            ?>

        <form method='POST' action='' enctype='multipart/form-data'>
            <ul class="table-add">
                <li>
                    <label class='th-add'>Category Name</label>
                    
                    <input class='tr-add' type='text' name='name' placeholder='Enter Name' required />
                </li>
                
                <li>
                    <p class='th-add'>Active</p>
                    
                    <div class='tr-add | td-actions'>
                        <label  for="active-yes" class="delete-btn"> 
                            <input type="radio" id="active-yes" checked  name='active' value='yes'>
                            Yes
                        </label>
                        <label  for="active-no" class="update-btn"> 
                            <input type="radio"   id="active-no" name='active' value='no'>
                            No
                        </label>
                    </div>

                </li>
                
                <li>
                    <label class='th-add'>Image</label>
                    
                    <label for="image-file">
                        <input class='tr-add'  id="image-file" type='file' name='image_path' placeholder='Image path' required />
                        Enter Image File    
                    
                </label>
                </li>
            </ul>
            <input  class='add-btn' name='submit' type='submit' value='SAVE'/>
        </from>
        <?php

            # Insert DATA
            if (isset($_POST['submit'])) {
                $name = htmlspecialchars(strtolower( $_POST['name']),ENT_QUOTES,'UTF-8');
                $active = htmlspecialchars($_POST['active'],ENT_QUOTES,'UTF-8');
                
                $image_details = $_FILES['image_path'];
                $image_orig_path = $image_details['tmp_name'];

                $ext =  pathinfo($image_details['name'],PATHINFO_EXTENSION);
                
                $folder_path = "images/category/" . $name . '_'.rand(000,999).'.'.$ext;

                $mime = mime_content_type($image_orig_path);
                $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif','image/webp', 'image/svg+xml'];

                # avoid server crash if file above than 2Megabyte;
                if(!in_array($mime,$allowed_mimes)) {
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed'>Not Image Format (EX: png, jpg, jpeg, webp, gif, svg )</h5>";
                        header('location: http://localhost/CAFE/add/add-category.php');
                        exit;
                };

                if($image_details['size'] > ((1024*1024) *2) ){
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed'>File Very Large </h5>";
                        header('location: http://localhost/CAFE/add/add-category.php');
                        exit;

                };

                if(!move_uploaded_file($image_orig_path,'../'.$folder_path)){
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed'>Failed to Upload image </h5>";
                        header('location: http://localhost/CAFE/add/add-category.php');
                        exit;
                };

                
                ####
                $stmt = $conn->prepare("INSERT INTO tbl_category (name, active, image_source) VALUES (?, ?, ?)");
                $stmt->bind_param('sss', $name, $active,$folder_path);
                if ($stmt){
                    if ($stmt->execute()) {
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-success'>Category Created</h5>";
                        header('location: http://localhost/CAFE/add/add-category.php');
                        exit;
                    }else{
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed'>Failed to Create Category</h5>";
                        header('location: http://localhost/CAFE/add/add-category.php');
                        exit;
                        }
                }

                $stmt->close();
                $conn->close();

            }


            ?>

    </section>
</div>

</div>
</div>
</body>
</html>
