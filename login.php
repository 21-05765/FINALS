<?php

include 'config.php';

session_start();

if(isset($_POST['submit'])){
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $pass = md5(filter_var($_POST['pass'], FILTER_SANITIZE_STRING));

    $select = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
    $select->execute([$email, $pass]);
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if($select->rowCount() > 0){
        if($row['user_type'] == 'admin'){
            $_SESSION['admin_id'] = $row['id'];
            header('location:admin/admin.php');
        } elseif($row['user_type'] == 'user'){
            $_SESSION['user_id'] = $row['id'];
            header('location:user/user_page.php');
        } else {
            $message[] = 'No user found!';
        }
    } else {
        $message[] = 'Incorrect Email or Password!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login - Barangay Balete</title>
   <script src="js/script.js" defer></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/login.css">
</head>

<body>

<header>
    <div>
        <img src="img/baletes.png" alt="Barangay Balete Logo">
        <span>Barangay Balete</span>
        <span class="admin-title"> (ACGS) </span>
    </div>
</header>

<?php
if(isset($message)){
    foreach($message as $message){
        echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
    }
}
?>

<section class="form-container">
   <form action="" method="post" enctype="multipart/form-data">
      <h3><i class="fas fa-sign-in-alt"></i> Login</h3>
<div class="input-container">
    <i class="fa fa-envelope"></i>
    <input type="email" required placeholder="Email" class="box" name="email">
</div>

<div class="input-container">
    <i class="fa fa-lock"></i>
    <input type="password" required placeholder="Password" class="box" name="pass">
</div>
      <p>Don't have an account? <a href="register.php">Sign up</a></p>
      <input type="submit" value="Login" class="btn" name="submit">
   </form>
</section>

</body>

</html>
