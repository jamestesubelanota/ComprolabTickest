

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

    
    var $B=0;
    var $I=0;
    var $U=0;
    var $HREF='';
    var $ALIGN='';

    function WriteHTML($html)
    {
        //HTML parser
        $html=str_replace("\n",' ',$html);
        $a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                //Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                elseif($this->ALIGN=='center')
                    $this->Cell(0,5,$e,0,1,'C');
                else
                    $this->Write(5,$e);
            }
            else
            {
                //Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    //Extract properties
                    $a2=explode(' ',$e);
                    $tag=strtoupper(array_shift($a2));
                    $prop=array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $prop[strtoupper($a3[1])]=$a3[2];
                    }
                    $this->OpenTag($tag,$prop);
                }
            }
        }
    }

    function OpenTag($tag,$prop)
    {
        //Opening tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF=$prop['HREF'];
        if($tag=='BR')
            $this->Ln(5);
        if($tag=='P')
            $this->ALIGN=$prop['ALIGN'];
        if($tag=='HR')
        {
            if( !empty($prop['WIDTH']) )
                $Width = $prop['WIDTH'];
            else
                $Width = $this->w - $this->lMargin-$this->rMargin;
            $this->Ln(2);
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetLineWidth(0.4);
            $this->Line($x,$y,$x+$Width,$y);
            $this->SetLineWidth(0.2);
            $this->Ln(2);
        }
    }

    function CloseTag($tag)
    {
        //Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF='';
        if($tag=='P')
            $this->ALIGN='';
    }

    function SetStyle($tag,$enable)
    {
        //Modify style and select corresponding font
        $this->$tag+=($enable ? 1 : -1);
        $style='';
        foreach(array('B','I','U') as $s)
            if($this->$s>0)
                $style.=$s;
        $this->SetFont('',$style);
    }

    function PutLink($URL,$txt)
    {
        //Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }

    // Cargar los datos
function LoadData($file)
{
    // Leer las líneas del fichero
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}

// Tabla coloreada
function FancyTable($header, $data)
{
    // Colores, ancho de línea y fuente en negrita
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Cabecera
    $w = array(40, 35, 45, 40);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Restauración de colores y fuentes
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Datos
    $fill = false;
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Línea de cierre
    $this->Cell(array_sum($w),0,'','T');
}

}

$mysql = new mysqli('localhost','root','','helpdesk','3306');

//ht=hesk_tickets, hc=hesk_categories, hu=hesk_users
$sql = 'SELECT ht.trackid AS idTicket, 
        ht.name AS cliente, 
        hc.name AS categoria, 
        ht.message AS mensaje, 
        ht.dt AS fecha, 
        hu.name AS encargado
        FROM hesk_tickets ht 
        JOIN hesk_categories hc
        ON ht.category=hc.id
        JOIN hesk_users hu
        ON ht.owner=hu.id
        WHERE TIMESTAMPDIFF(day,ht.dt,now())>=3 AND ht.status<>3';

$res = $mysql->query($sql);

$pdf = new PDF();
$pdf->SetTitle('Reporte de tickets vencidos');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFillColor(255,0,0);
$pdf->SetTextColor(255);
$pdf->SetDrawColor(255,0,0);
$pdf->SetLineWidth(.3);
$pdf->SetFont('','B');

$pdf->Ln();
$pdf->Cell(32,5,"id ticket",1,0,'C',true);
$pdf->Cell(32,5,"cliente",1,0,'C',true);
$pdf->Cell(32,5,"categoria",1,0,'C',true);
$pdf->Cell(32,5,"mensaje",1,0,'C',true);
$pdf->Cell(32,5,"fecha",1,0,'C',true);
$pdf->Cell(32,5,"encargado",1,0,'C',true);


$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
$pdf->SetFont('Arial','',12);

$fill = false;

while($reg = $res->fetch_assoc()){
    
    $pdf->Ln();
    $pdf->Cell(32,5,"$reg[idTicket]",'LR',0,'L',$fill);
    $pdf->Cell(32,5,"$reg[cliente]",'LR',0,'L',$fill);
    $pdf->Cell(32,5,"$reg[categoria]",'LR',0,'L',$fill);
    $pdf->Cell(32,5,"$reg[mensaje]",'LR',0,'L',$fill);
    $pdf->Cell(32,5,substr("$reg[fecha]",0,-2),'LR',0,'L',$fill);
    $pdf->Cell(32,5,"$reg[encargado]",'LR',0,'L',$fill);
    $fill = !$fill;
}

$pdf->SetFillColor(255,0,0);
$pdf->SetTextColor(255);
$pdf->SetDrawColor(255,0,0);
$pdf->SetLineWidth(.3);
$pdf->SetFont('','B',14);

$pdf->Ln();
$pdf->Cell(32,5,"id ticket",1,0,'C',true);
$pdf->Cell(32,5,"cliente",1,0,'C',true);
$pdf->Cell(32,5,"categoria",1,0,'C',true);
$pdf->Cell(32,5,"mensaje",1,0,'C',true);
$pdf->Cell(32,5,"fecha",1,0,'C',true);
$pdf->Cell(32,5,"encargado",1,0,'C',true);
    
$pdf->AddPage();
$pdf->Ln();
$pdf->SetFont('Arial');
$pdf->WriteHTML(
    '
        <table style="width=100%" class="">
            <tr class="">
                <th class="">Hola</th>
                <th class="">Adios</th>
            </tr>
            <tr class="">
                <td class="">dato</td>
                <td class="">dato</td>
            </tr>
        </table>
    '
);

$header = array(utf8_decode('País'), 'Capital', 'Superficie (km2)', 'Pobl. (en miles)');
// Carga de datos
$data = $pdf->LoadData('../fpdf/tutorial/paises.txt');
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->Ln();
$pdf->FancyTable($header,$data);

$pdf->Output("I","reporte tickets",true);
?>