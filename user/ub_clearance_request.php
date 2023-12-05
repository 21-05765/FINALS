<?php
include '../config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:../login.php');
    exit();
}

$checkRequestQuery = $conn->prepare("SELECT * FROM `ub_clearance_requests` WHERE user_id = ?");
$checkRequestQuery->execute([$_SESSION['user_id']]);
$existingRequest = $checkRequestQuery->fetch(PDO::FETCH_ASSOC);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($existingRequest) {
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    var modal = document.getElementById("myModal");
                    modal.style.display = "flex";
                });
            </script>';
    } else {

    $bname = $_POST["bname"];
    $btype = $_POST["btype"];
    $name = $_POST["name"];
    $address = $_POST["address"];
    $date = $_POST["date"];

    $insertQuery = $conn->prepare("INSERT INTO `ub_clearance_requests` (user_id, bname, btype, name, address, date) VALUES (?, ?, ?, ?, ?, ?)");
    $insertQuery->execute([$_SESSION['user_id'], $bname, $btype, $name, $address, $date]);

    header('Location: user_page.php');
    exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Business Clearance</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/ub_clearance_request.css">
        <style>
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: center;
            padding: 20px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .warning-message {
            color: #ff6347;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 15px;
        }

        .ok-button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <div>
            <img src="../img/baletes.png" alt="Header Image">
            <span>Barangay Balete</span>
        </div>

        <nav>
            <a href="user_page.php">Home</a>
            <a href="user_viewcert.php">View Requests</a>
            <a href="user_profile_update.php">Update Profile</a>
            <a href="logout.php" class="delete-btn">Logout</a>
        </nav>
    </header>
        <div class="clearance-form">
            <h2>Barangay Business Clearance Form</h2>
            <form action="" method="post">
                <label for="bname">Business Name:</label>
                <input type="text" id="bname" name="bname" required>

                <label for="btype">Business Type:</label>
                <input type="text" id="btype" name="btype" required>
                
                <label for="name">Owner Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>

                <label for="date">Date of Request:</label>
                <input type="date" id="date" name="date" required>

                <button type="submit">Submit Request</button>
            </form>
        </div>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p class="warning-message">Request already submitted. Only one request is allowed.</p>
            <button class="ok-button" onclick="closeModal()">OK</button>
        </div>
    </div>

    <script>
        var modal = document.getElementById('myModal');

        function closeModal() {
            modal.style.display = 'none';
            window.location.href = 'user_page.php';
        }

        window.onclick = function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }
    </script>

</body>

</html>
