<?php
$host = 'localhost';
$dbname = 'user_form';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_status'])) {
            $userId = $_POST['user_id'];
            $newStatus = $_POST['new_status'];

            if (in_array($newStatus, ['approved', 'completed'])) {
                $updateQuery = "UPDATE income_requests SET status = :status WHERE user_id = :user_id";
                $updateStmt = $pdo->prepare($updateQuery);
                $updateStmt->bindParam(':status', $newStatus, PDO::PARAM_STR);
                $updateStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $updateStmt->execute();
            } else {
                echo "Invalid status selected.";
            }
        } elseif (isset($_POST['delete_request'])) {
            $userIdToDelete = $_POST['user_id'];
            $deleteQuery = "DELETE FROM income_requests WHERE user_id = :user_id";
            $deleteStmt = $pdo->prepare($deleteQuery);
            $deleteStmt->bindParam(':user_id', $userIdToDelete, PDO::PARAM_INT);
            $deleteStmt->execute();
        }
    }

    $query = "SELECT * FROM income_requests";
    $stmt = $pdo->query($query);

    if ($stmt->rowCount() > 0) {
        $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $requests = [];
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Low Income Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="../css/admin_manage.css">
    <style>
        .confirmation-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        .confirmation-popup h3 {
            margin-bottom: 15px;
            color: #333;
        }

        .confirmation-buttons {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .confirmation-popup button {
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-left: 10px;
            font-size: 14px;
            font-weight: bold;
        }

        .confirmation-popup #confirm-delete {
            background-color: #dc3545;
            color: #fff;
        }

        .confirmation-popup #cancel-delete {
            background-color: #ffc107;
            color: #fff;
        }
    </style>
    <script>
        function deleteRequest(userId) {
            document.getElementById('confirmation-popup').style.display = 'block';
            document.getElementById('delete-user-id').value = userId;
        }

        function confirmDelete() {
            var userId = document.getElementById('delete-user-id').value;

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "<?php echo $_SERVER['PHP_SELF']; ?>", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    location.reload();
                }
            };
            xhr.send("delete_request=1&user_id=" + userId);
            document.getElementById('confirmation-popup').style.display = 'none';
        }

        function cancelDelete() {
            document.getElementById('confirmation-popup').style.display = 'none';
        }
    </script>
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

    <h2>Low Income Requests</h2>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Civil Status</th>
                <th>Address</th>
                <th>Income</th>
                <th>Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $request) : ?>
                <tr>
                    <td><?= $request['user_id']; ?></td>
                    <td><?= $request['name']; ?></td>
                    <td><?= $request['age']; ?></td>
                    <td><?= $request['civil_status']; ?></td>
                    <td><?= $request['address']; ?></td>
                    <td><?= $request['income']; ?></td>
                    <td><?= $request['date']; ?></td>
                    <td><?= $request['status']; ?></td>
                    <td>
                        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                            <input type="hidden" name="user_id" value="<?= $request['user_id']; ?>">
                            <select name="new_status">
                                <option value="approved">APPROVED</option>
                                <option value="completed">COMPLETED</option>
                            </select>
                            <button type="submit" name="update_status" class="update-btn">
                                UPDATE
                            </button>
                            <button type="button" onclick="deleteRequest(<?= $request['user_id']; ?>)" class="delete-btn">
                                DELETE
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div id="confirmation-popup" class="confirmation-popup">
        <h3>Are you sure you want to delete this request?</h3>
        <div class="confirmation-buttons">
            <button id="cancel-delete" onclick="cancelDelete()">Cancel</button>
            <button id="confirm-delete" onclick="confirmDelete()">Yes, Delete</button>
        </div>
        <input type="hidden" id="delete-user-id">
    </div>

</body>
</html>
