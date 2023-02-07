<?php                
    session_start();
    include("../../../system/dbconn.php");
    mysqli_select_db($dbconn_ipadManagement, 'ipadManagement');
    include_once('tcpdf_6_2_13/tcpdf/tcpdf.php');

$action_ID = $_POST['actions'];
$dateAssign = date('F d, Y', strtotime($_POST['dateAssign']));

$inv_mst_query = "SELECT ipad.*,owner.*,rental_return.*,department.*,users.*,rental.*,Accesories_ipad.* FROM rental_return
    INNER JOIN ipad ON ipad.ID_ipad = rental_return.ID_ipad
    INNER JOIN owner ON owner.ID_owner = rental_return.ID_owner
    INNER JOIN department ON department.ID_dept = owner.ID_dept
    INNER JOIN rental ON rental.ID_rental = rental_return.ID_rental
    INNER JOIN users ON users.ID_users = rental.ID_users
    INNER JOIN Accesories_ipad ON Accesories_ipad.ID_ipad = ipad.ID_ipad
    WHERE rental_return.ID_rentalReturn = '".$action_ID."' ";

$inv_mst_results = mysqli_query($dbconn_ipadManagement,$inv_mst_query);   
$count = mysqli_num_rows($inv_mst_results);  
if($count>0) 
{
    $inv_mst_data_row = mysqli_fetch_array($inv_mst_results, MYSQLI_ASSOC);
    
    if($inv_mst_data_row['adapter_ipad'] == '1'){
        $adapter = "One (1) x USB-C Power Adapter";
    }
    else{
        $adapter = '';
    }
    
    if($inv_mst_data_row['wire_ipad'] == '1'){
        $cable = 'One (1) x USB-C Charge Cable';
    }
    else{
        $cable = '';
    }
    
    if($inv_mst_data_row['accesories_ipad'] == '1'){
        $casing = 'One (1) x '.$inv_mst_data_row['name_accesories'];
    }
    else{
        $casing = '';
    }
    $sim = 'One (1) x Maxis Simcard (if any)';

	//----- Code for generate pdf
	$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);  
	//$pdf->SetTitle("Export HTML Table data to PDF using TCPDF in PHP");  
	$pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
	$pdf->SetDefaultMonospacedFont('helvetica');  
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
	$pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);  
	$pdf->setPrintHeader(false);  
	$pdf->setPrintFooter(false);  
	$pdf->SetAutoPageBreak(TRUE, 10);  
	$pdf->SetFont('helvetica', '', 12);  
	$pdf->AddPage(); //default A4
	//$pdf->AddPage('P','A5'); //when you require custome page size 
	
	$content = ''; 

	$content .= '
	<style type="text/css">
        table{
            font-size:10px;
            font-family: "Gothic A1", sans-serif;
            color:black;
            letter-spacing:0.5px;
            text-align: justify;
            text-justify: inter-word;
            
        }
        
        .height{
            line-height: 20.5px !important;
        }
        
        .bg-color{
            background-color:grey !important;
        }
        
        .padding{
            margin-bottom:20px !important;
        }
	</style> 
    
	<table cellpadding="0" cellspacing="0" style="width:100%;">
        <table style="width:100%;" >
            <tr >
                <td colspan="5" align="center"><img width="110px" src="../../../system/images/pnb.png"></td>
            </tr>
            <tr class="height bg-color padding" >
                <td colspan="5" align="center"><b >RETURN FORM</b></td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td align="left" width="60px">&nbsp;&nbsp;To</td>
                <td align="center" width="60px">:</td>
                <td align="left" width="530px">Head, Company Secretary Department<br>Permodalan Nasional Berhad</td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td align="left" width="60px">&nbsp;&nbsp;From</td>
                <td align="center" width="60px">:</td>
                <td align="left" width="220px">'.ucwords($inv_mst_data_row['name_owner']).'</td>
                <td align="left" width="40px">Date :</td>
                <td align="left" width="110px">'.$dateAssign.'</td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td align="left" width="60px"><b>&nbsp;&nbsp;RE</b></td>
                <td align="center" width="60px">:</td>
                <td align="left" width="530px"><b>RETURN OF IPAD</b></td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td colspan="5" width="510px"><hr></td>
            </tr>
            
            <tr>
                <td align="left" width="50px">&nbsp;&nbsp;1.</td>
                <td align="left" width="460px"><p style="text-align: justify;text-justify: inter-word;" >The above refers.</p></td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td align="left" width="50px">&nbsp;&nbsp;2.</td>
                <td align="left" width="460px" ><p style="text-align: justify;text-justify: inter-word;" >I hereby return the following device and its accessories to the Company Secretary Department:</p> </td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="495px" >
                        <tr>
                            <th align="center" colspan="3" style="border: 0.5px solid black;" ><b>Hardware Profile</b></th>
                            <th align="center" style="border: 0.5px solid black;" ><b>Serial No</b></th>
                            <th align="center" style="border: 0.5px solid black;"><b>PNB Tag No</b></th>
                        </tr>
                        <tr>
                            <td align="center" width="20px" style="border: 0.5px solid black;" >a.</td>
                            <td align="left" style="border: 0.5px solid black;" width="76px">Device</td>
                            <td align="left" style="border: 0.5px solid black;" width="201px" >One (1) '.ucwords($inv_mst_data_row['assetType_ipad']).'</td>
                            <td rowspan="2" align="center" style="border: 0.5px solid black;" ><div style="line-height:60px;">'.strtoupper($inv_mst_data_row['serialNo_ipad']).'</div></td>
                            <td rowspan="2" align="center" style="border: 0.5px solid black;" ><div style="line-height:60px;">700'.ucwords($inv_mst_data_row['rfidno_ipad']).'0000</div></td>
                        </tr>
                        <tr>
                            <td align="center" style="border: 0.5px solid black;" >b.</td>
                            <td align="left" style="border: 0.5px solid black;" >Model</td>
                            <td align="left" style="border: 0.5px solid black;" >'.ucwords($inv_mst_data_row['modelType_ipad']).'</td>
                        </tr>
                        <tr>
                            <td align="center" style="border: 0.5px solid black;" >b.</td>
                            <td align="left" style="border: 0.5px solid black;" >Accessories</td>
                            <td colspan="3" align="left" style="border: 0.5px solid black;" >'.$cable.'<br>'.$adapter.'<br>'.$casing.'<br>'.$sim.'</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td align="left" width="90px">&nbsp;&nbsp;Thank You.</td>
                <td align="left" width="460px"></td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td align="left" width="100px">&nbsp;&nbsp;Signature</td>
                <td align="left" width="150px">:<hr> </td>
            </tr>
            <tr>
                <td align="left" width="100px">&nbsp;&nbsp;Name</td>
                <td align="left" width="260px">: '.ucwords($inv_mst_data_row['name_owner']).' </td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td align="left" width="100px">&nbsp;&nbsp;Date</td>
                <td align="left" width="260px">:  </td>
            </tr>
            <tr><td colspan="5">&nbsp;</td></tr>
            <tr class="height bg-color padding" >
                <td width="506px" colspan="3" align="center"><b >FOR COMPANY SECRETARY DEPARTMENT USE ONLY</b></td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td align="left" ><img src="../../../system/images/round.png" width="10px"> Acknowledgement of Asset Return</td>
                <td align="left" ><img src="../../../system/images/round.png" width="10px"> Approve For Transfer to Procument Department</td>
                <td align="left" ><img src="../../../system/images/round.png" width="10px"> Recommend For Disposal</td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td align="left" width="100px">&nbsp;&nbsp;Recommend By</td>
                <td align="left" width="260px"></td>
            </tr>
            <tr><td colspan="5">&nbsp;</td></tr>
            <tr>
                <td align="left" width="100px">&nbsp;&nbsp;Name</td>
                <td align="left" width="260px">: '.ucwords($inv_mst_data_row['name_users']).' </td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td align="left" width="100px">&nbsp;&nbsp;Designation</td>
                <td align="left" width="260px">: Company Secretary Department  </td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td align="left" width="100px">&nbsp;&nbsp;Date</td>
                <td align="left" width="260px">: '.$dateAssign.'  </td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td align="left" width="100px">&nbsp;&nbsp;Approved By</td>
                <td align="left" width="260px"></td>
            </tr>
            <tr><td colspan="5">&nbsp;</td></tr>
            <tr>
                <td align="left" width="100px">&nbsp;&nbsp;Name</td>
                <td align="left" width="260px">: Hartini Binti Zainal Abidin </td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td align="left" width="100px">&nbsp;&nbsp;Designation</td>
                <td align="left" width="260px">: Group Head, Company Secretary & Legal</td>
            </tr>
            <tr>
                <td colspan="5"></td>
            </tr>
            <tr>
                <td align="left" width="100px">&nbsp;&nbsp;Date</td>
                <td align="left" width="260px">: '.$dateAssign.'  </td>
            </tr>
        </table>
    </table>'; 
    
$pdf->writeHTML($content);

$file_location = "/home/fbi1glfa0j7p/public_html/examples/generate_pdf/uploads/"; //add your full path of your server
//$file_location = "/opt/lampp/htdocs/examples/generate_pdf/uploads/"; //for local xampp server

$datetime=date('dmY_hms');
$file_name = "acknowledgement_Receipt_".ucwords($inv_mst_data_row['name_owner']).".pdf";
ob_end_clean();

if($_POST['ACTION']=='VIEW') 
{
	$pdf->Output($file_name, 'I'); // I means Inline view
} 
else if($_POST['ACTION']=='DOWNLOAD')
{
	$pdf->Output($file_name, 'D'); // D means download
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}
else if($_POST['ACTION']=='UPLOAD')
{
$pdf->Output($file_location.$file_name, 'F'); // F means upload PDF file on some folder
echo "Upload successfully!!";
}

//----- End Code for generate pdf
	
}
else
{
	echo 'Record not found for PDF.';
}

?>