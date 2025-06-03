<?php
include('partials/header.php');
?>

<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['product-id'];

    if (isset($_POST['addtocart-btn'])) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] ++;
        }else{
            $_SESSION['cart'][$id] = 1;

        };

    }

    if (isset($_POST['removeitem-btn'])) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]--;
            if ($_SESSION['cart'][$id] <= 0) {
                unset($_SESSION['cart'][$id]);
            }
        }
    }    
    header("Location: http://localhost/CAFE/cart.php");
    exit;

}
?>

<div class="wrapper" style='padding-top:5em;'>
    <section>
        <a href="place-order.php"><h1>Buy Now </h1></a>
        <div class="slider-1">
            <div id="left-arrow"> < </div>
        
            <ul class="slider">


<?php
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $qty) {
        $sql = 'SELECT * FROM tbl_product WHERE id=? AND active=?';
        $stmt = $conn->prepare($sql);
        $isactive = 'yes';
        $stmt->bind_param('is', $product_id, $isactive);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
?>
                <li>
                    <a href="product-page.php?id=<?php echo $row['id']; ?>">
                        <div class="item-title">
                            <p class="name"><?php echo $row['name']; ?></p>
                            <p class="product-category">
                                <span>Quantity</span>
                                <?php echo $qty; ?>
                            </p>
                        </div>
                        <form action="" method="POST" class="cart-buttons">
                            <input type="hidden" name="product-id" value="<?php echo $row['id']; ?>">
                            <input type="submit" class="addtocart-btn" name="addtocart-btn" value="Add +">
                            <input type="submit" class="addtocart-btn" name="removeitem-btn" value="Delete  -">
                        </form>
                        <p class="price">$ <?php echo $row['price']; ?></p>
                        <img src="<?php echo $row['image_source']; ?>" alt="<?php echo $row['name']; ?>">
                    </a>
                </li>
<?php
            }
        }
    }

} else {
    echo "<h5 class='comment-emptycart'>Your Cart is Empty</h5>";
}
?>
            </ul>
            <div id="right-arrow"> > </div>
        </div>

    </section>
</div>

<?php include('partials/featured.php'); ?>
<?php include('partials/footer.php'); ?>
