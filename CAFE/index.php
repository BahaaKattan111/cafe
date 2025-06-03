<?php include('partials/header.php')?>

        <div class="main-background">
            <img src="images/momo.jpg" alt="">
        </div>

        <?php if(isset($_SESSION['COMMENT_product'])){
                echo $_SESSION['COMMENT_product'];
                unset($_SESSION['COMMENT_product']);};?>
                
        <div class="wrapper">
            <aside>
                <h1 class="item-title | horizontal-line">Categories</h1>
                
                <div class="slider-1">
                    <div id="left-arrow"> < </div>
                
                    <ul class="slider">
                        <?php
                        $sql = 'SELECT * FROM tbl_category';
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();

                        $res=$stmt->get_result();
                        while($row=$res->fetch_assoc()){
                        ?>

                        <li>
                            <a href=" <?php echo 'products-by-category.php?category='.$row['name'];?>" >
                                <h3 class="item-title"> <?php echo $row['name'] ;?></h3>
                            <img src="<?php echo $row['image_source'] ;?>" alt="<?php echo $row['name'] ;?>">
                            </a>
                        </li>                    
                        <?php }; ?>
                    </ul>
                    <div id="right-arrow"> > </div>

            </aside>
        </div>


<?php 
include_once ('partials/featured.php');

include_once ('partials/footer.php');
?>

