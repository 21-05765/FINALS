<?php
include '../config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:../login.php');
    exit();
}

$selectProfileQuery = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$selectProfileQuery->execute([$_SESSION['user_id']]);
$fetchProfile = $selectProfileQuery->fetch(PDO::FETCH_ASSOC);

$residencyRequestQuery = $conn->prepare("SELECT * FROM `residency_requests` WHERE user_id = ?");
$residencyRequestQuery->execute([$_SESSION['user_id']]);
$residencyRequests = $residencyRequestQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>View Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/request_view.css">
</head>

<body>
    <header>
        <div>
            <img src="../img/baletes.png" alt="Header Image">
            <span>Barangay Balete</span>
        </div>

        <nav>
            <a href="user_page.php">Home</a>
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

    <div class="list">
        <h2>Residency Requests</h2>
        <?php
        if (empty($residencyRequests)) {
            echo "<p>No residency requests found.</p>";
        } else {
            echo "<table>";
            echo "<thead><tr><th>Name</th><th>Purpose</th><th>Age</th><th>Civil Status</th><th>Citizenship</th><th>Gender</th><th>Address</th><th>Date Submitted</th><th>Status</th><th>Action</th></tr></thead>";
            echo "<tbody>";
            foreach ($residencyRequests as $request) {
                echo "<tr>";
                echo "<td>{$request['name']}</td>";
                echo "<td>" . (isset($request['purpose']) ? $request['purpose'] : "") . "</td>";
                echo "<td>{$request['age']}</td>";
                echo "<td>{$request['civil_status']}</td>";
                echo "<td>" . (isset($request['citizenship']) ? $request['citizenship'] : "") . "</td>";
                echo "<td>" . (isset($request['gender']) ? $request['gender'] : "") . "</td>";
                echo "<td>{$request['address']}</td>";
                echo "<td>{$request['date']}</td>";
                echo "<td>{$request['status']}</td>";
                echo "<td><a href='../certificate/r_certificate.php?user_id={$request['user_id']}' target='_blank'>View</a></td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }
        ?>
    </div>

</body>

</html>
