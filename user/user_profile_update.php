<?php
include '../config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:../login.php');
    exit();
}

$message = [];

if (isset($_POST['update'])) {

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);

    $updateProfileQuery = $conn->prepare("UPDATE `users` SET name = ?, email = ? WHERE id = ?");
    $updateProfileQuery->execute([$name, $email, $user_id]);

    $oldImage = $_POST['old_image'];
    $newImage = $_FILES['image']['name'];
    $imageTmpName = $_FILES['image']['tmp_name'];
    $imageSize = $_FILES['image']['size'];
    $imageFolder = '../uploaded_img/' . $newImage;

    if (!empty($newImage)) {
        if ($imageSize > 2000000) {
            $message[] = 'Image size is too large';
        } else {
            $updateImageQuery = $conn->prepare("UPDATE `users` SET image = ? WHERE id = ?");
            if ($updateImageQuery->execute([$newImage, $user_id])) {
                move_uploaded_file($imageTmpName, $imageFolder);

                if (file_exists('../uploaded_img/' . $oldImage)) {
                    unlink('../uploaded_img/' . $oldImage);
                    $message[] = 'Image has been updated!';
                }
            } else {
                $message[] = 'Failed to update image';
            }
        }
    }

    $oldPassword = $_POST['old_pass'];
    $previousPassword = md5($_POST['previous_pass']);
    $newPassword = md5($_POST['new_pass']);
    $confirmPassword = md5($_POST['confirm_pass']);

    if (!empty($previousPassword) || !empty($newPassword) || !empty($confirmPassword)) {
        if ($previousPassword != $oldPassword) {
            $message[] = 'Old password not matched!';
        } elseif ($newPassword != $confirmPassword) {
            $message[] = 'Confirm password not matched!';
        } else {
            $updatePasswordQuery = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $updatePasswordQuery->execute([$confirmPassword, $user_id]);
            $message[] = 'Password has been updated!';
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
    <title>User Profile Update</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/user_profiles.css">
</head>

<body>
    <?php
    foreach ($message as $msg) {
        echo '<div class="message"><span>' . $msg . '</span><i class="fas fa-times" onclick="this.parentElement.remove();"></i></div>';
    }
    ?>

    <header>
        <div>
            <img src="../img/baletes.png" alt="Header Image">
            <span>Barangay Balete</span>
        </div>

        <nav>
            <?php
            $selectProfileQuery = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $selectProfileQuery->execute([$user_id]);
            $fetchProfile = $selectProfileQuery->fetch(PDO::FETCH_ASSOC);
            ?>

            <a href="user_page.php" >Home</a>
            <div class="dropdown">
                <a href="#" class="dropbtn">View Requests</a>
                <div class="dropdown-content">
                    <a href="brr_view.php">Residency Requests</a>
                    <a href="bcr_view.php">Clearance Requests</a>
                    <a href="bbr_view.php">Business Clearance Requests</a>
                    <a href="bir_view.php">Low Income Requests</a>
                </div>
            </div>
            <a href="user_profile_update.php">Update Profile</a>
            <a href="../logout.php" class="delete-btn">Logout</a>
        </nav>
    </header>

    <div class="content">
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
                    <a href="user_page.php" class="option-btn">Back</a>
                </div>
            </form>
        </section>
    </div>

</body>

</html>
