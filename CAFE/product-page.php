<?php include('partials/header.php')?>
<br>
<br>
<br>
<br>
<div class="wrapper">


    <?php
    $sql='SELECT * FROM tbl_product WHERE id='. $_GET['id'];
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()){
        $res = $stmt->get_result();
        while($row = $res->fetch_assoc()){
    ?>
    <section class='product-details'>

        <h1 class="item-title | horizontal-line">
            <p class="product-category"> <?php echo $row['category']?> </p>
            <p> <?php echo $row['name']?>  </p>
            <p> <?php echo '$'.$row['price']?>  </p>
            
            <form action="" method="POST">
                <input type="hidden" name="product-id" value="<?php echo $row['id'];?>">
                <input type="submit" class="addtocart-btn" name="addtocart-btn" value="Add to Cart">
            </form>
        </h1>

        <img src=" <?php echo $row['image_source']?> " alt=" <?php echo $row['name']?> ">
        
        <h3 class="item-title | horizontal-line">Why You Should Buy</h3>
        <p class='short-desc'><?php echo $row['details']?></p>
        
        <h3 class="item-title | horizontal-line">Ingredients</h3>
        <p class='large-desc'><?php echo $row['ingredients']?></p>
    </section>
    <?php  
        if(isset($_POST['addtocart-btn'])){
            if(isset($_SESSION['cart'][$_POST['product-id']])){
                $_SESSION['cart'][$_POST['product-id']] ++; 
            }else{
                $_SESSION['cart'][$_POST['product-id']] = 1; 
            
            }
        };
        };

    }else{
            $_SESSION['COMMENT_product'] =  "<h5 class='comment-failed'>Failed to Load Product</h5>";
            header('location:  http://localhost/CAFE/index.php');
            exit;
        };?>

        
</div>

<?php include('partials/footer.php');?>

