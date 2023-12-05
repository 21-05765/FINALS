<?php
require_once('../config.php');
require_once('../tcpdf/tcpdf.php');
include 'ir_pdf_function.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $selectRequestQuery = $conn->prepare("SELECT * FROM `income_requests` WHERE user_id = ?");
    $selectRequestQuery->execute([$user_id]);
    $userData = $selectRequestQuery->fetch(PDO::FETCH_ASSOC);

    if ($userData) {
        generatePDF($userData);
    } else {
        echo "Invalid user_id.";
    }
} else {
    echo "User ID not provided.";
}
?>
