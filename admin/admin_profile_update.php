<?php
include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:../login.php');
    exit();
}

$message = [];

if (isset($_POST['update'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);

    $updateProfileQuery = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
    $updateProfileQuery->execute([$name, $email, $admin_id]);

    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = 'Image size is too large';
        } else {

            $update_image = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
            if ($update_image->execute([$image, $admin_id])) {
                move_uploaded_file($image_tmp_name, $image_folder);

                if (file_exists('uploaded_img/' . $old_image)) {
                    unlink('uploaded_img/' . $old_image);
                }

                $message[] = 'Image has been updated!';
            } else {
                $message[] = 'Failed to update image';
            }
        }
    }

    $old_pass = $_POST['old_pass'];
    $previous_pass = md5($_POST['previous_pass']);
    $previous_pass = filter_var($previous_pass, FILTER_SANITIZE_STRING);
    $new_pass = md5($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $confirm_pass = md5($_POST['confirm_pass']);
    $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

    if (!empty($previous_pass) || !empty($new_pass) || !empty($confirm_pass)) {
        if ($previous_pass != $old_pass) {
            $message[] = 'Old password not matched!';
        } elseif ($new_pass != $confirm_pass) {
            $message[] = 'Confirm password not matched!';
        } else {
            $update_password = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_password->execute([$confirm_pass, $admin_id]);

        }
    }
    $message[] = 'Profile updated successfully!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile Update</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_update.css">
</head>

<body>

    <header>
    <div>
            <img src="../img/baletes.png" alt="Header Image">
            <a class="logo-link"><span>Barangay Balete</span></a>
            <span class="admin-title"> ADMIN </span>
        </div>

        <nav>
            <?php
            $selectProfileQuery = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $selectProfileQuery->execute([$admin_id]);
            $fetchProfile = $selectProfileQuery->fetch(PDO::FETCH_ASSOC);
            ?>

            <a href="admin.php">Home</a>
            <div class="dropdown">
                <a href="#">Manage Requests</a>
                <div class="dropdown-content">
                    <a href="admin_brr.php">Residency Requests</a>
                    <a href="admin_bcr.php">Clearance Requests</a>
                    <a href="admin_bbr.php">Business Clearance Requests</a>
                    <a href="admin_bir.php">Low Income Requests</a>
                </div>
            </div>
            <a href="admin_profile_update.php">Update Profile</a>
            <a href="../logout.php" class="delete-btn">Logout</a>
        </nav>
    </header>

    <div class="content">
    <?php
        if (isset($_POST['update'])) {
            foreach ($message as $msg) {
                echo '<div class="message success"><span>' . $msg . '</span></div>';
            }
            $message[] = '<span class="message success small">Profile updated successfully!</span>';
        }
        ?>
        <section class="update-profile-container">
            <h2 class="title">Update Profile</h2>
            <form action="" method="post" enctype="multipart/form-data">
                <img src="../uploaded_img/<?= $fetchProfile['image']; ?>" alt="">
                <div class="flex">
                    <div class="inputBox">
                        <span>Username : </span>
                        <input type="text" name="name" required class="box" placeholder="Enter your username"
                            value="<?= $fetchProfile['name']; ?>">
                        <span>Email : </span>
                        <input type="email" name="email" required class="box" placeholder="Enter your Email"
                            value="<?= $fetchProfile['email']; ?>">
                            <span>profile pic : </span>
                            <input type="hidden" name="old_image" value="<?= $fetchProfile['image']; ?>">
            <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
                    </div>
                    <div class="inputBox">
                        <input type="hidden" name="old_pass" value="<?= $fetchProfile['password']; ?>">
                        <span>Old Password :</span>
                        <input type="password" class="box" name="previous_pass"
                            placeholder="Enter Previous Password">
                        <span>New Password :</span>
                        <input type="password" class="box" name="new_pass" placeholder="Enter New Password">
                        <span>Confirm Password :</span>
                        <input type="password" class="box" name="confirm_pass" placeholder="Re-Type Password">
                    </div>
                </div>
                <div class="flex-btn">
                    <input type="submit" value="Update" name="update" class="btn">
                    <a href="admin_page.php" class="option-btn">Back</a>
                </div>
            </form>
        </section> 
    </div>
</body>

</html>
