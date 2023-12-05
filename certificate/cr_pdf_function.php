<?php
require_once('../tcpdf/tcpdf.php');

class MyPDF extends TCPDF {
    public function Header() {
        $this->SetHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->Cell(0, 25, 'Republic of the Philippines', 0, false, 'C');
        $this->Ln(5);
        $this->Cell(0, 25, 'City of Batangas', 0, false, 'C');
        $this->Ln(5);
        $this->Cell(0, 25, 'BARANGAY BALETE', 0, false, 'C');
        $this->Ln(5);
        $this->Cell(0, 25, 'OFFICE OF THE PUNONG BARANGAY', 0, false, 'C');
        $this->Ln(5);
        $this->Cell(0, 25, 'Landline: (043) 740 9638  E-mail: info@baletebatangas.gov.ph', 0, false, 'C');
        $this->Ln(8);
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
    }

    public function getUserIdFromSession() {
        session_start();
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        } else {
            return null;
        }
    }
}

$pdf = new MyPDF();
$pdf->SetCreator('Alvin');
$pdf->SetAuthor('Barangay Balete');
$pdf->SetTitle('Certificate of Clearance');
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 82, 'CERTIFICATE OF CLEARANCE', 0, 1, 'C');
$pdf->SetFont('helvetica', '', 12);
$date = date('F j, Y');
$pdf->Cell(0, 0, "Date: $date", 0, 1, 'L');
$pdf->Ln(0);

$mysqli = new mysqli('localhost', 'root', '', 'user_form');
if ($mysqli->connect_error) {
    die('Connection failed: ' . $mysqli->connect_error);
}

$user_id = $pdf->getUserIdFromSession();

if ($user_id !== null) {
    $stmt = $mysqli->prepare("SELECT * FROM clearance_requests WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            $name = $userData['name'];
            $age = $userData['age'];
            $address = $userData['address'];
            $purpose = $userData['purpose'];

            $content = "\nTO WHOM IT MAY CONCERN:      \n
This is to certify that, based on the records of this Barangay of the date the person whose \nname personal circumstances and signature below has not been accused nor has a pending \ncase of any sort in Barangay Balete, Batangas City:\n
Name:              $name
Address:           $address
Age:                 $age
Purpose:           $purpose


Sincerely,\n
                                                                                                            Rodelo P. Banatlao
                                                                                                            Barangay Captain

";

            $pdf->MultiCell(0, 10, $content);
        } else {
            echo "No user data found.";
        }

        $stmt->close();
    } else {
        echo "Error in preparing the statement.";
    }

} else {
    echo "User ID not found in the session.";
}

$mysqli->close();
$pdf->Output('Barangay Clearance.pdf', 'I');
?>
