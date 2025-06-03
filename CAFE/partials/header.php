<?php include_once('config.php'); 

if (!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
};

?>

<!DOCTYPE html>
<html>
    <head>
        
        <title></title>
        <link rel="stylesheet" href='<?php echo "./style.css" . "?v= ".time(); ?>'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>

    <body>
        
        <div class="wrapper">
            <header class='horizontal-line'>
                <a href="index.php">LOGO</a>

                <nav >
                    <a href="index.php">Home</a>
                    <a href="cart.php">Cart</a>
                    <a href="">Contact</a>
                </nav>
                <div class="ham-btn">
                    =
                </div>
            </header>
        </div>
