        <div class="wrapper">
            <aside>
                <h1 class="item-title | horizontal-line">Featured</h1>
                
                <div class="slider-2">
                    <div id="left-arrow"> < </div>
                    <ul class="slider">
                        <?php
                        # select only  if active , and products inside featured section
                        $sql = 'SELECT * FROM tbl_product WHERE featured = ? AND active = ? ';
                        $stmt = $conn->prepare($sql);
                        $isfeatured = 'yes';
                        $isactive = 'yes';
                        $stmt->bind_param('ss', $isfeatured, $isactive);
                        $stmt->execute();

                        $res=$stmt->get_result();
                        while($row=$res->fetch_assoc()){
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
                            if(isset($_POST['addtocart-btn'])){
                                if (isset($_SESSION['cart'][ $_POST["product-id"] ])) {
                                        $_SESSION['cart'][ $_POST["product-id"] ]++;
                                    } else {
                                        $_SESSION['cart'][ $_POST["product-id"] ] = 1;
                                    }

                            }else{
                                continue;
                            };
                        };
                        ?>
                    </ul>
                    <div id="right-arrow"> > </div>

                </div>     
            </aside>
        </div>