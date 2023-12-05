<?php
require_once('../tcpdf/tcpdf.php');

class MyPDF extends TCPDF {
    public function Header() {
        $this->SetHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->Cell(0, 25, 'Republic of the Philippines', 0, false, 'C');
        $this->Ln(5); // Adjusted spacing
        $this->Cell(0, 25, 'City of Batangas', 0, false, 'C');
        $this->Ln(5); // Adjusted spacing
        $this->Cell(0, 25, 'BARANGAY BALETE', 0, false, 'C');
        $this->Ln(5); // Adjusted spacing
        $this->Cell(0, 25, 'OFFICE OF THE PUNONG BARANGAY', 0, false, 'C');
        $this->Ln(5); // Adjusted spacing
        $this->Cell(0, 25, 'Landline: (043) 740 9638  E-mail: info@baletebatangas.gov.ph', 0, false, 'C');
        $this->Ln(8); // Adjusted spacing
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
$pdf->SetTitle('Certificate of Low Income');
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 75, 'CERTIFICATE OF LOW INCOME', 0, 1, 'C');
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
    $stmt = $mysqli->prepare("SELECT * FROM income_requests WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $userData = $result->fetch_assoc();
            $name = $userData['name'];
            $age = $userData['age'];
            $civilStatus = $userData['civil_status'];
            $address = $userData['address'];
            $income = $userData['income'];
            $date = $userData['date'];

            $content = "\nTO WHOM IT MAY CONCERN:      \n
I, $name, residing at $address, do solemnly affirm and declare under oath the following:
Full name : $name
Age : $age
Address : $address
Civil Status : $civilStatus

My annual income of FIFTY FIVE THOUSAND PESOS (P55,000.00) is so meager and only enough to cover our daily needs

I am executing this affidavit to attest to my current financial situation. I am applying for Specify Government Assistance Program, and as part of the application process, I am required to provide documentation of my low income.

I declare that the information provided in this affidavit is true and correct to the best of my knowledge and belief. I understand that any false statement made herein may subject me to penalties under the law.

$name
Signature
$date
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
$pdf->Output('Certificate of Low Income.pdf', 'I');
?>
