<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="../css/user_page.css">
    
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

    <script src="../script/user_script.js"></script>

    <div class="content">
        <div class="clickable-boxes">
            <div class="box" onclick="handleBoxClick(1)">
                <h4>Barangay Residency</h4>
                <p>Request</p>
            </div>
            <div class="box" onclick="handleBoxClick(2)">
                <h4>Barangay Clearance</h4>
                <p>Request</p>
            </div>
            <div class="box" onclick="handleBoxClick(3)">
                <h4>Business Clearance</h4>
                <p>Request</p>
            </div>
            <div class="box" onclick="handleBoxClick(4)">
                <h4>Certificate of Low Income</h4>
                <p>Request</p>
            </div>
        </div>
    </div>

</body>

</html>
