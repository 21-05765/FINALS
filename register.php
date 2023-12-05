<?php

include 'config.php';

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = md5($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = md5($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_size = $_FILES['image']['size'];
   $image_folder = 'uploaded_img/'.$image;

   $select = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select->execute([$email]);

   if($select->rowCount() > 0){
      $message[] = 'user already exist!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }elseif($image_size > 2000000){
         $message[] = 'image size is too large!';
      }else{
         $insert = $conn->prepare("INSERT INTO `users`(name, email, password, image) VALUES(?,?,?,?)");
         $insert->execute([$name, $email, $cpass, $image]);
         if($insert){
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'registered succesfully!';
            header('location:login.php');
         }
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Account Registration</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/register.css">
</head>
<body>

<header>
    <div>
        <img src="img/baletes.png" alt="Barangay Balete Logo">
        <span>Barangay Balete</span>
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
      <h3>Register Now <i class="fas fa-user-plus"></i></h3>
      <div class="input-container">
         <i class="fas fa-user"></i>
         <input type="text" required placeholder="Username" class="box" name="name">
      </div>
      <div class="input-container">
         <i class="fas fa-envelope"></i>
         <input type="email" required placeholder="Email" class="box" name="email">
      </div>
      <div class="input-container">
         <i class="fas fa-lock"></i>
         <input type="password" required placeholder="Password" class="box" name="pass">
      </div>
      <div class="input-container">
         <i class="fas fa-lock"></i>
         <input type="password" required placeholder="Confirm Password" class="box" name="cpass">
      </div>
      <div class="input-container">
         <i class="fas fa-image"></i>
         <input type="file" name="image" required class="box" accept="image/jpg, image/png, image/jpeg">
      </div>
      <p>Already have an account? <a href="login.php">Sign In</a></p>
      <input type="submit" value="Register now" class="btn" name="submit">
   </form>
</section>

</body>
</html>