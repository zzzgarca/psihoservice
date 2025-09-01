<?php
class FacturiController
{
    public function descarca($idSedinta = null)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) { header('Location: ' . BASE_URL . 'login'); exit; }

        $idSedinta = (int)($idSedinta ?? 0);
        if ($idSedinta <= 0) { http_response_code(400); exit('ID invalid.'); }

        $pdo = require dirname(__DIR__, 2) . '/config/db.php';

        $sql = "SELECT f.ID_Factura, f.Suma, f.DataEmiterii,
                       s.ID_Sedinta, s.ID_Prestator, s.ID_Contract,
                       sv.Tip AS Serviciu,
                       u.Nume, u.Prenume
                FROM Facturi f
                JOIN Sedinte s ON s.ID_Sedinta = f.ID_Sedinta
                LEFT JOIN Servicii sv ON sv.ID_Serviciu = s.ID_Serviciu
                JOIN Users u ON u.ID_User = s.ID_Client
                WHERE f.ID_Sedinta = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idSedinta]);
        $fact = $stmt->fetch();

        if (!$fact) { http_response_code(404); exit('Factura nu există.'); }
        if (!in_array($_SESSION['role'], ['psiholog','administrator'], true)) { http_response_code(403); exit('Acces interzis.'); }
        if ($_SESSION['role'] === 'psiholog' && (int)$fact['ID_Prestator'] !== (int)$_SESSION['user_id']) { http_response_code(403); exit('Acces interzis.'); }

        require_once dirname(__DIR__, 2) . '/app/lib/fpdf/fpdf.php';

        $serie = 'PSV';
        $numar = (int)$fact['ID_Factura'];
        $furnizor = 'PsihoService';
        $cif = 'CIF 233524';
        $dataEmiterii = $fact['DataEmiterii'] ?: date('Y-m-d');
        $client = trim(($fact['Nume'] ?? '') . ' ' . ($fact['Prenume'] ?? ''));
        $serviciu = $fact['Serviciu'] ?? '';
        $contractNr = $fact['ID_Contract'] ? ('#' . $fact['ID_Contract']) : '-';
        $suma = number_format((float)$fact['Suma'], 2, '.', '');

        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(0,10, 'Factura ' . $serie . ' ' . $numar, 0, 1, 'C');

        $pdf->SetFont('Arial','',11);
        $pdf->Cell(100,8, 'Furnizor: ' . $furnizor, 0, 0);
        $pdf->Cell(0,8, $cif, 0, 1);
        $pdf->Cell(100,8, 'Data emiterii: ' . $dataEmiterii, 0, 1);

        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,8, 'Client', 0, 1);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(0,8, $client, 0, 1);

        $pdf->Ln(4);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(0,8, 'Detalii serviciu', 0, 1);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(0,8, 'Serviciu: ' . $serviciu, 0, 1);
        $pdf->Cell(0,8, 'Contract: ' . $contractNr, 0, 1);

        $pdf->Ln(6);
        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(120,8, 'Descriere', 1, 0, 'L');
        $pdf->Cell(0,8, 'Suma (RON)', 1, 1, 'R');

        $pdf->SetFont('Arial','',11);
        $pdf->Cell(120,8, 'Servicii psihologice', 1, 0, 'L');
        $pdf->Cell(0,8, $suma, 1, 1, 'R');

        $pdf->SetFont('Arial','B',12);
        $pdf->Cell(120,8, 'Total', 1, 0, 'L');
        $pdf->Cell(0,8, $suma, 1, 1, 'R');

        $filename = 'Factura-' . $serie . '-' . $numar . '.pdf';
        $pdf->Output('D', $filename);
        exit;
    }
}
