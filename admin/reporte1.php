

<?php
/**
 *
 * This file is part of HESK - PHP Help Desk Software.
 *
 * (c) Copyright Klemen Stirn. All rights reserved.
 * https://www.hesk.com
 *
 * For the full copyright and license agreement information visit
 * https://www.hesk.com/eula.php
 *
 */
define('HESK_PATH','../');


require HESK_PATH."fpdf/fpdf.php";

class PDF extends FPDF{

    function header(){
        // Logo
        $this->Image(HESK_PATH.'img/Logo_Comprolab.png',10,8,33);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Movernos a la derecha
        $this->Cell(70);
        // Título
        $this->Cell(50,10,'Reporte de tickets',0,0,'C');
        // Salto de línea
        $this->Ln(20);
    }

    function footer(){
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
    }

}

$mysql = new mysqli('localhost','root','','helpdesk','3306');

$sql = 'SELECT COUNT(hesk_tickets.id) as cantidad, hesk_users.name as nombre FROM hesk_tickets join hesk_users ON hesk_users.id = hesk_tickets.openedby GROUP BY hesk_users.name';

$res = $mysql->query($sql);

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->Ln();
$pdf->Cell(35,5,"Abierto por",1,'C',);
$pdf->Cell(35,5,"Cantidad",1,'C',);

while($reg = $res->fetch_assoc()){
    
    $pdf->Ln();
    $pdf->Cell(35,5,"$reg[nombre]",1,'C',);
    $pdf->Cell(35,5,"$reg[cantidad]",1,'C',);
}

$pdf->Ln();
$pdf->Cell(35,5,"Abierto por",1,'C',);
$pdf->Cell(35,5,"Cantidad",1,'C',);
    

$pdf->Output();
?>