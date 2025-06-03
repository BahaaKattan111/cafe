<?php 
include('partials/header.php');

?>


        <div class="wrapper" style='padding-top:5em;'>
            <section>
                <h1 class="item-title | horizontal-line">
                    <p class="product-category"> <?php echo $_GET['category']?> </p>
                </h1>
               <div class="slider-1">
                    <div id="left-arrow"> < </div>
                    <ul class="slider">

                    <?php

                    $sql = 'SELECT * FROM tbl_product WHERE category= ? AND active= ?';
                    $stmt =$conn->prepare($sql);
                    $isactive = 'yes';
                    
                    $stmt->bind_param('ss', $_GET['category'], $isactive);
                    $stmt->execute();
                    $res = $stmt->get_result();

                    $count=mysqli_num_rows($res);
                    if ($count> 0){
                        while($row = $res->fetch_assoc()){
                    
                    ?>

                        <li>
                            <a href="product-page.php?id=<?php echo $row['id'];?>">
                                <div class="item-title">
                                    <p class="name"><?php echo $row['name'] ;?></p>
                                </div>

                                
                                <form action="" method="POST">
                                    <input type="hidden" name="product-id" value="<?php echo $row['id'];?>">
                                    <input type="submit" class='addtocart-btn' name="addtocart-btn" value="Add to Cart">
                                </form>

                                <p class="price">$ <?php echo $row['price'] ;?></p>
                                <img src="<?php echo $row['image_source'] ;?>" alt="<?php echo $row['name'] ;?>">
                            </a>
                        </li>                    


                    <?php
                        if (isset($_POST['addtocart-btn'])){
                            if (!isset($_SESSION['cart'][ $_POST["product-id"] ])) {
                                    $_SESSION['cart'][ $_POST["product-id"] ] = 1;
                                } else {
                                    $_SESSION['cart'][ $_POST["product-id"] ] ++ ;
                                };
                            header('location: http://localhost/CAFE/products-by-category.php?category='.$_GET['category']);
                            exit;
                            };  
                                
                        };
                    }else{
                        $_SESSION['COMMENT_category'] = '<h5 class="comment-failed">No Products Found</h5>';
                    };
                    ?>

                    </ul>

                    <div id="right-arrow"> > </div>
                </div>

            </section>
        </div>



<?php include('partials/featured.php');?>
<?php include('partials/footer.php');?>

