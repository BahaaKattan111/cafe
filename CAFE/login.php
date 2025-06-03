<?php
include('partials/config.php');

if (session_status() == PHP_SESSION_NONE){
    session_start();
} ;



?>
<?php
$_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? 0;

if (isset($_SESSION['user'])){
    unset($_SESSION['user']);
}

if (isset($_POST['submit'])){


    $password = $_POST['password'];
    $username = $_POST['username'];
    $stmt = $conn->prepare("SELECT * FROM tbl_admin WHERE username=?");
    $stmt->bind_param("s", $username );
    if($stmt){
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows > 0){# if found username in database
                $row = $result->fetch_assoc();
                $hash_pass = $row["password"];
                $full_name = $row["full_name"];
                $is_active = $row["active"];
                $auth = explode(',',$row["auth"]);
                $is_correct_pass = password_verify($password,$hash_pass );
                if ($is_active=='yes' && $is_correct_pass){
                    unset($_SESSION['login_attempts']);
                    $_SESSION['COMMENT_add'] = "<h5 class='comment-success-2'>$full_name Welcome </h5>";
                    $_SESSION["user"] = [
                        'username' =>$username,
                        'auth' =>$auth,
                    ];
                    header("location: http://localhost/CAFE/admin.php");
                    $conn->close();
                    $stmt->close();
                    exit;
                }elseif($is_active=='no'){
                    $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Failed: Blocked User </h5>";
                }

                else{
                    $_SESSION['login_attempts'] ++;
                    if (($_SESSION['login_attempts']+1) > 5){
                        $_SESSION['COMMENT_add'] =  "<h5 class='comment-failed-2'>If Failed Again You will be Blocked for  While ! " . (10 *$_SESSION['login_attempts'])  ." sec  </h5>";

                    }else{
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Failed: User Name or Password not Correct  </h5>";

                    };

   
                    header("location: http://localhost/CAFE/login.php");
                    $conn->close();
                    $stmt->close();
                    exit;
                };


            }else{
                    $_SESSION['login_attempts'] ++;
                    if (($_SESSION['login_attempts']+1) > 5){
                        $_SESSION['COMMENT_add'] =  "<h5 class='comment-failed-2'>If Failed Again You will be Blocked for  While ! ". (10 *$_SESSION['login_attempts'])  ." sec </h5>";

                    }else{
                        $_SESSION['COMMENT_add'] = "<h5 class='comment-failed-2'>Failed: User Name or Password not Correct  </h5>";

                    };


                    header("location: http://localhost/CAFE/login.php");
                    $conn->close();
                    $stmt->close();
                    exit;
                }
        }
    }
}


?>


<!DOCTYPE html>

<html>
    <head>
        
        <title>Admin Login Page</title>
        <link rel="stylesheet" href='<?php echo "admin.css" . "?v= ".time(); ?>'>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    </head>
    <body>
        <div class="main-container">
            <div class="blur-background">
                <br>
                <br>
                <div class="wrapper">
                    <form method='POST' action=''>

                        <ul class="table-add">
                            <li>
                                <label class='th-add'>User Name</label>
                                    
                                <input class='tr-add' type='text' name='username' placeholder='User Name' required />
                            </li>

                            <li>
                                <label class='th-add'>Password</label>
                                    
                                <input class='tr-add' type='text' name='password' placeholder='Password' required />
                            </li>
                        </ul>
                        <input  class='td add-btn' name='submit' type='submit' value='LOG IN'/>
                    </form>
                        <?php
                        if (isset($_SESSION["COMMENT_add"])){
                            echo $_SESSION['COMMENT_add'];
                            unset( $_SESSION['COMMENT_add']);
                        };

                    if ($_SESSION['login_attempts'] > 5){
                        sleep((10 *$_SESSION['login_attempts']));
                    };
                        ?>
                </div>
            </div>
        </div>
        
    </body>
</html>

