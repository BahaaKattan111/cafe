<?php 
include_once '../partials/config.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
};

if(isset($_SESSION['user'])){
    if(!in_array('add-category',$_SESSION['user']['auth'])){
        
        header('location: http://localhost/CAFE/manage/manage-category.php');
        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>".  $_SESSION['user']['username']  .", Have No access to: Update Category Page</h5>";
        exit;
    }
}else{
        header('location: http://localhost/CAFE/login.php');
        exit;
};

# Insert DATA
if (isset($_POST['submit'])) {
    $ID = $_POST['ID'];

    $name = htmlspecialchars(strtolower($_POST['name']),ENT_QUOTES,'UTF-8');
    $active = htmlspecialchars($_POST['active'],ENT_QUOTES,'UTF-8');

    if(!empty($_FILES['image_path']['tmp_name'])){

        $image_details = $_FILES['image_path'];


        #remove file, so we can create new one: 
        $ext =  pathinfo($image_details['name'],PATHINFO_EXTENSION );
        $folder_path = "images/category/category_".$ID. '.' .$ext;
        $old_folder_path = $row['image_source'];

        if(file_exists('../'.$old_folder_path)){
            unlink('../'.$old_folder_path);
        };
        
        ####
        $image_orig_path = $image_details['tmp_name'];


        $mime = mime_content_type($image_orig_path);
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif','image/webp', 'image/svg+xml'];

        # avoid server crash if file above than 2Megabyte;
        if(!in_array($mime,$allowed_mimes)) {
                $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Not Image Format (EX: png, jpg, jpeg, webp, gif, svg )</h5>";
                header("location: http://localhost/CAFE/update/update-category.php?id=".$ID );
                exit;
        };

        if($image_details['size'] > ((1024*1024) *2) ){
                $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>File Very Large </h5>";
                header("location: http://localhost/CAFE/update/update-category.php?id=".$ID );
                exit;

        };
        if(!move_uploaded_file($image_orig_path,'../'.$folder_path)){
                $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Failed to Upload image </h5>";
                header("location: http://localhost/CAFE/update/update-category.php?id=".$ID );
                exit;
        };

        $stmt = $conn->prepare("UPDATE tbl_category SET name = ?, active = ?, image_source = ? WHERE id = ?");
        $stmt->bind_param('sssi', $name, $active,  $folder_path, $ID);

    }else{
        $stmt = $conn->prepare("UPDATE tbl_category SET name = ?, active = ? WHERE id = ?");
        $stmt->bind_param('ssi', $name, $active, $ID);

    };
    
    ####
    if ($stmt){
        if ($stmt->execute()) {
            $_SESSION['COMMENT_add'] = "<h5 class='comment-success-2'>Category Updated</h5>";
            header("location: http://localhost/CAFE/manage/manage-category.php?id=".$ID );
            exit;

        }else{
            $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Failed to Update Category</h5>";
            header("location: http://localhost/CAFE/manage/manage-category.php?id=".$ID );
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
        <h1 class="item-title horizontal-line">Update Category</h1>
        <?php
            $stmt = $conn->prepare("SELECT * FROM tbl_category WHERE id= ? ");
            $ID=  $_GET['id']; 
            $stmt->bind_param('i', $ID);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {


            ?>
        <form method='POST' enctype='multipart/form-data'>
            <ul class="table-add">
                <li>
                    <label class='th-add'>Category Name</label>
                    
                    <input type='hidden' name='ID'  value="<?php echo $row['id'];?>" required />
                    <input class='tr-add' type='text' name='name' placeholder='Category Name' value="<?php echo $row['name'];?>" required />
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
                    
                    <div class='td-actions | tr-add'>
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

            <br>
            <br>
            <br>
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
