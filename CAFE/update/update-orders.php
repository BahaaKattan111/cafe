<?php 
include '../partials/config.php'; 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
};

if(isset($_SESSION['user'])){
    if(!in_array('add-orders',$_SESSION['user']['auth'])){
        
        header('location: http://localhost/CAFE/manage/manage-orders.php');
        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>".  $_SESSION['user']['username']  .", Have No access to: Update Orders Page</h5>";
        exit;
    }
}else{
        header('location: http://localhost/CAFE/login.php');
        exit;
};
            
# Insert DATA
if (isset($_POST['submit'])) {

    $full_name = htmlspecialchars($_POST['full_name'], ENT_QUOTES, 'UTF-8' );
    
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8' );
    $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8' );
    $city = htmlspecialchars($_POST['city'], ENT_QUOTES, 'UTF-8' );
    
    $region = htmlspecialchars($_POST['region'], ENT_QUOTES, 'UTF-8' );
    $add_on = htmlspecialchars($_POST['add_on'], ENT_QUOTES, 'UTF-8' );
    $status = htmlspecialchars($_POST['status'], ENT_QUOTES, 'UTF-8' );
    
    $stmt = $conn->prepare("UPDATE orders SET full_name= ?   ,phone= ?    ,address= ?   ,city= ?     ,region= ?     ,food_add_ons= ?     ,status= ?       WHERE id=?");

    $stmt->bind_param('sssssssi', $full_name,$phone,$address,$city,$region,$add_on,$status ,$_GET['id']);
    
    if ($stmt){
        if ($stmt->execute()) {
            $_SESSION['COMMENT_add'] = "<h5 class='comment-success-2'>Order Updated</h5>";
            header("location: http://localhost/CAFE/manage/manage-orders.php?id=". $_GET['id']);
            exit;
        }else{
            $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Failed to Update Order</h5>";
            header("location: http://localhost/CAFE/manage/manage-orders.php?id=". $_GET['id']);
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
        <h1 class="item-title horizontal-line">Update Order</h1>
        <?php
            $stmt = $conn->prepare('SELECT * FROM orders WHERE id= ?');
            $stmt->bind_param('s', $_GET['id']);
            if($stmt->execute()){
               $res= $stmt->get_result();

                while($row = $res->fetch_assoc()){
                    ?>


        <form method='POST'>

        <ul class="table-add">

            <li>
                <label class='th-add'>Full Name</label>
                    
                <input class='tr-add' type='text' name='full_name' value="<?php echo $row['full_name']; ?>" placeholder='Full Name' required />
            </li>
            
            <li>
                <label class='th-add'>Phone</label>
                    
                <input class='tr-add' type='text' name='phone' value="<?php echo $row['phone']; ?>" placeholder='Phone' required />
            </li>

            <li>
                <label class='th-add'>Address</label>
                    
                <input class='tr-add' type='text' name='address' value="<?php echo $row['address']; ?>" placeholder='Address' required />
            </li>
            <li>
                <label class='th-add'>City</label>
                    
                <input class='tr-add' type='text' name='city' value="<?php echo $row['city']; ?>" placeholder='City' required />
            </li>
            <li>
                <label class='th-add'>Region</label>
                    
                <input class='tr-add' type='text' name='region' value="<?php echo $row['region']; ?>" placeholder='Region' required />

            </li>
            <li>
                <label class='th-add'>Add on</label>
                    
                <input class='tr-add' type='text' name='add_on' value="<?php echo $row['food_add_ons']; ?>" placeholder='Add on' />
            </li>
            <li>
                <label class='th-add'>Order Status</label>
                    
                <input class='tr-add' type='text' name='status' value="<?php echo $row['status']; ?>" placeholder='Status' required />
            </li>
        </ul>
            <input  class='td add-btn' name='submit' type='submit' value='UPDATE'/>
        </form>
                <?php
                };

            };
?>

    </section>
</div>
</div>
</div>
</body>
</html>
