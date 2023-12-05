<?php

include '../config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:../login.php');
   exit();
}

$dsn = 'mysql:host=localhost;dbname=user_form';
$username = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

function getTotalClearanceRequests()
{
    global $pdo;

    $query = "SELECT COUNT(*) as total_requests FROM clearance_requests";
    $stmt = $pdo->query($query);

    if ($stmt) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_requests'];
    } else {
        return 0;
    }
}

function getTotalIncomeRequests()
{
    global $pdo;

    $query = "SELECT COUNT(*) as total_requests FROM income_requests";
    $stmt = $pdo->query($query);

    if ($stmt) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_requests'];
    } else {
        return 0;
    }
}

function getTotalResidencyRequests()
{
    global $pdo;

    $query = "SELECT COUNT(*) as total_requests FROM residency_requests";
    $stmt = $pdo->query($query);

    if ($stmt) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_requests'];
    } else {
        return 0;
    }
}

function getTotalBClearanceRequests()
{
    global $pdo;

    $query = "SELECT COUNT(*) as total_requests FROM ub_clearance_requests";
    $stmt = $pdo->query($query);

    if ($stmt) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_requests'];
    } else {
        return 0;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>

<body>
    <header>
        <div>
            <img src="../img/baletes.png" alt="Header Image">
            <a class="logo-link"><span>Barangay Balete</span></a>
            <span class="admin-title"> ADMIN </span>
        </div>
        <nav>
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

    <div class="container">
        <div class="box">
            <i class="fas fa-user-check"></i>
            <strong>Residency Verifications:</strong><br>Manage requests for residency verification.
            <?php echo getTotalResidencyRequests(); ?>
        </div>

        <div class="box">
            <i class="fas fa-file-signature"></i>
            <strong>Official Clearances:</strong><br>Process official clearance requests.
            <?php echo getTotalClearanceRequests(); ?>
        </div>

        <div class="box">
            <i class="fas fa-store-alt"></i>
            <strong>Business Clearances:</strong><br>Handle applications for business clearances.
            <?php echo getTotalBClearanceRequests(); ?>
        </div>

        <div class="box">
            <i class="fas fa-hands-helping"></i>
            <strong>Low-Income Assistance:</strong><br>Assist residents with low-income support requests.
            <?php echo getTotalIncomeRequests(); ?>
        </div>
    </div>

</body>

</html>
