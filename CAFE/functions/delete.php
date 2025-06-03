<?php

include_once ("../partials/config.php");
if (session_status() == PHP_SESSION_NONE){
    session_start();
};


if (isset($_GET['section']) && isset($_GET['id'])) {
    $section = $_GET['section'];
    $id = (int)$_GET['id']; 

    $table = '';
    switch ($section) {
        case 'admin':
            if(isset($_SESSION['user'])){
                if(!in_array('add-admin',$_SESSION['user']['auth'])){
                    header('location: http://localhost/CAFE/manage/manage-admin.php');
                    $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>".  $_SESSION['user']['username']  .", Have No access to: Delete Admin Action</h5>";
                    exit;
                }
            }else{
                    header('location: http://localhost/CAFE/login.php');
                    exit;
            };

            $table = 'tbl_admin';
            break;
        case 'orders':
            if(isset($_SESSION['user'])){
                if(!in_array('add-orders',$_SESSION['user']['auth'])){
                    header('location: http://localhost/CAFE/manage/manage-orders.php');
                    $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>".  $_SESSION['user']['username']  .", Have No access to: Delete Orders Action</h5>";
                    exit;
                }
            }else{
                    header('location: http://localhost/CAFE/login.php');
                    exit;
            };

            $table = 'orders'; 
            break;

        case 'category':
            if(isset($_SESSION['user'])){
                if(!in_array('add-category',$_SESSION['user']['auth'])){
                    header('location: http://localhost/CAFE/manage/manage-category.php');
                    $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>".  $_SESSION['user']['username']  .", Have No access to: Delete Category Action</h5>";
                    exit;
                }
            }else{
                    header('location: http://localhost/CAFE/login.php');
                    exit;
            };

            $table = 'tbl_category';
            break;
        case 'product':
            if(isset($_SESSION['user'])){
                if(!in_array('add-product',$_SESSION['user']['auth'])){
                    header('location: http://localhost/CAFE/manage/manage-product.php');
                    $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>".  $_SESSION['user']['username']  .", Have No access to: Delete product Action</h5>";
                    exit;
                }
            }else{
                    header('location: http://localhost/CAFE/login.php');
                    exit;
            };

            $table = 'tbl_product';
            break;
        default:
            die("Invalid section.");
    }

    $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt){
        if($stmt->execute()){
            if ($stmt->execute()) {
                $_SESSION['COMMENT_add'] = "<h5 class='comment-success-2'>". ucwords($section) ." Deleted</h5>";
                header("location: http://localhost/CAFE/manage/manage-".$section.".php");
                exit;
            }else{
                $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Failed to Delete ".ucwords($section)."</h5>";
                header("location: http://localhost/CAFE/manage/manage-".$section.".php");
                exit;
                };
        };

    };
}


?>