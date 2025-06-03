<?php
include('partials/header.php');
?>
    <div class="wrapper" style='padding-top:5em;'>
        <form action="" class='log-form' method="POST">
            <h1>Place Order</h1>
            <div class="group-input">
                <div for="Full_name" ><div>Full Name</div> <div>اسمك الكامل </div></div>
                <input type="text" name="full_name" placeholder="-" required>
            </div>

            <div class="group-input">
                <div for="Full_name" ><div>Phone</div> <div>رقم الهاتف </div> </div>
                <input type="text" name="phone" placeholder="-" required>
            </div>

            <div class="group-input">
                <div for="city" ><div>City</div> <div>المدينة</div> </div>
                <input type="text" name="city" placeholder="-"  required>
            </div>


            <div class="group-input">
                <div for="region" ><div>Region</div> <div>المحافظة</div> </div>
                <input type="text" name="region" placeholder="-"  required>
            </div>
            
                <div class="group-input">
                <div for="address" ><div>Address</div> <div> وصف العنوان</div> </div>
                <input type="text" name="address" placeholder="-" required>
            </div>
            <div class="group-input">
                <div for="region" ><div>Add on</div> <div>اضافات</div> </div>
                <input type="text" name="addon" placeholder="-">
            </div>

            <input type="submit" name="order" class="order-btn" value="Order" >

            <?php

            if(isset($_POST['order'])){
                $full_name = htmlspecialchars($_POST['full_name'] ?? 'none' ,ENT_QUOTES,'UTF-8');
                $phone = htmlspecialchars($_POST['phone'] ?? 'none' ,ENT_QUOTES,'UTF-8');
                $address = htmlspecialchars($_POST['address'] ??  'none' ,ENT_QUOTES,'UTF-8');
                $city = htmlspecialchars($_POST['city'] ??  'none' ,ENT_QUOTES,'UTF-8');
                $region = htmlspecialchars($_POST['region'] ??  'none' ,ENT_QUOTES,'UTF-8');
                $addon = htmlspecialchars($_POST['addon'] ??  'none' ,ENT_QUOTES,'UTF-8');
                if($addon == ''){
                    $addon == 'none';
                }
                $status = htmlspecialchars('prepare' ??  'none' ,ENT_QUOTES,'UTF-8');
                
                $sql = " INSERT INTO orders (full_name, phone, address , city, region, food_add_ons, status)
                 VALUES (?,  ?, ? ,  ?,  ?,? ,?) ";

                 $stmt = $conn->prepare($sql);
                 $stmt->bind_param('sisssss', $full_name,  $phone, $address,  $city,  $region, $addon, $status);
                 $stmt->execute(); 

                 if($stmt){
                    $stmt->close();
                    echo '<h5 class="comment-success"> Oreder Sent | تم ارسال الطلب </h5>';
                 }else{
                    echo '<h5 class="comment-failed"> Failed to Send Oreder | فشل ارسال الطلب  </h5>';
                    echo '<a href="#" class="comment-failed"> Contact Us | تواصل معنا </a>';

                 }
            }
            ?>
        </form>
    </div>

<?php

include('partials/footer.php');
?>

