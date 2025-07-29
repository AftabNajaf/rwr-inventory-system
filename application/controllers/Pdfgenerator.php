<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pdfgenerator extends CI_Controller {

	public function __construct() {

        parent::__construct();
		$this->load->library('phpqrcode/qrlib');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('function_helper');
//		$this->load->helper('simplexlsx_class_helper');
		$this->load->Model('AdminModel');
		$this->load->Model('SiteModel');
		
		$this->load->library('image_lib');
		$this->load->library('upload');
		
		
				
		
		if (!isset($_SESSION))
		session_start();
    }
	
	
	public function index()
	{  	
	}
	
	#####
	public function generatePDF($pdfFilename, $html){

		$this->load->library('Pdf');
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle($pdfFilename);
		#$pdf->SetHeaderMargin(20);
		$pdf->SetLeftMargin(5);
		$pdf->SetTopMargin(3);
		$pdf->SetRightMargin(5);
		#$pdf->setFooterMargin(5);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor('RWR-MIS');
		//$pdf->SetDisplayMode('real', 'default');
		$pdf->AddPage();
		$pdf->writeHTML($html, true, 0, true, 0);
		$pdf->Output($pdfFilename, 'I');//'I' online view 'D' download
	}
	
	#####
	public function downloadBom($bomid)
	{
		$myobj='';
		$bom = $this->AdminModel->getRow('e_bom',array("id"=>$bomid));
		$bomitems = $this->AdminModel->getRows('e_bomitem',array("bom_id"=>$bomid));
		$settings 	= $this->SiteModel->getSetting();
		//extract($bom);
		
		$myobj .='
		<style>
		table { font-size:10px; font-family :helvetica}
		</style>
        <table border="0" cellpadding="1" cellspacing="0" width="100%">
		<tr>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<img src="'.base_url().'assets/img/167635404789763.png" width="120" alt="" >
			<h5>Date  : '.$bom['generated_on'].'</h5>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h1>Bill of Material - BOM</h1>
			<h5>Generated  By : '.getField('e_admin',array('userid'=>$bom['generated_by']),'name').'</h5>
			<h5>Approved &nbsp;By: '.getField('e_admin',array('userid'=>$bom['approved_by']),'name').'</h5>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h5>BOM # : '.$bomid.'</h5>
			<h5>Project  : '.getField('e_projects',array('id'=>$bom['project_id']),'title').'</h5>
			<h5>Department : '.getField('e_departments',array('id'=>$bom['department_id']),'title').'</h5>
			<h5>Section  : '.getField('e_departments',array('id'=>$bom['section_id']),'title').'</h5>
			</td>
		</tr>
		<tr>
			<td style="padding:0px; border-top:1px solid #222;" colspan="3"><br><br>
			<table border="0" cellpadding="1" cellspacing="0" style="width:100%; border:1px solid #222">
		<tr style="background-color:grey;color:#fff">
			<th style="padding:0px; border:1px solid #222;">Assembly</th>
			<th style="padding:0px; border:1px solid #222;">Sub-Assembly</th>
			<th style="padding:0px; border:1px solid #222;">MIS #</th>
			<th style="padding:0px; border:1px solid #222;">Item</th>
			<th style="padding:0px; border:1px solid #222;">Part No</th>
			<th style="padding:0px; border:1px solid #222; text-align:center">Qty</th>
			<th style="padding:0px; border:1px solid #222; text-align:center">Unit</th>
			<th style="padding:0px; border:1px solid #222;">Unit Price</th>
			<th style="padding:0px; border:1px solid #222;">Price</th>	
		</tr>';
			foreach($bomitems as $i){
				$myobj .='
				<tr>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_assemblies',array('id'=>$i['assembly_id']),'title').'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_assemblies',array('id'=>$i['sub_assembly_id']),'title').'</td>
				<td style="padding:0px; border:1px solid #222;">'.$i['item_id'].'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_items',array('id'=>$i['item_id']),'item_name').'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_items',array('id'=>$i['item_id']),'part_number').'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['qty_needed'].'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_units',array('id'=>getField('e_items',array('id'=>$i['item_id']),'item_unit')),'title').'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_items_qty',array('item_id'=>$i['item_id']),'pkr_unit_price').'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_items_qty',array('item_id'=>$i['item_id']),'pkr_unit_price')*$i['qty_needed'].'</td>	
				</tr>
				<tr>
				<td style="padding:0px; border:1px solid #222; background-color:#ccc;" colspan="4">Functionality :'.getField('e_items',array('id'=>$i['item_id']),'item_functionality').'</td>
				<td style="padding:0px; border:1px solid #222; background-color:#ccc;" colspan="5">Description :'.getField('e_items',array('id'=>$i['item_id']),'item_description').' </td>
				</tr>'; 
			}
			
		$myobj .='</table>
			</td>
		</tr>
    </table>
		';
		//return $myobj;	
		
		$this->generatePDF('BOM_NO:'.$bomid, $myobj);
	}
	
	public function downloadMat($bomid)
	{
		$myobj='';
		$bom = $this->AdminModel->getRow('e_mat',array("id"=>$bomid));
		$bomitems = $this->AdminModel->getRows('e_matitem',array("mat_id"=>$bomid));
		$settings 	= $this->SiteModel->getSetting();
		//extract($bom);
		
		$myobj .='
		<style>
		table { font-size:10px; font-family :helvetica}
		</style>
        <table border="0" cellpadding="1" cellspacing="0" width="100%">
		<tr>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<img src="'.base_url().'assets/img/167635404789763.png" width="120" alt="" >
			<h5>Date  : '.$bom['generated_on'].'</h5>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h1>Material Order - MO</h1>
			<h5>Generated  By : '.getField('e_admin',array('userid'=>$bom['generated_by']),'name').'</h5>
			<h5>Approved &nbsp;By: '.getField('e_admin',array('userid'=>$bom['approved_by']),'name').'</h5>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h5>MO # : '.$bomid.'</h5>
			<h5>Project  : '.getField('e_projects',array('id'=>$bom['project_id']),'title').'</h5>
			<h5>Department : '.getField('e_departments',array('id'=>$bom['department_id']),'title').'</h5>
			<h5>Section  : '.getField('e_departments',array('id'=>$bom['section_id']),'title').'</h5>
			</td>
		</tr>
		<tr>
			<td style="padding:0px; border-top:1px solid #222;" colspan="3"><br><br>
			<table border="0" cellpadding="1" cellspacing="0" style="width:100%; border:1px solid #222">
		<tr style="background-color:grey;color:#fff">
			<th style="padding:0px; border:1px solid #222; width:5%">S.No</th>
			<th style="padding:0px; border:1px solid #222; width:10%">MIS#</th>
			<th style="padding:0px; border:1px solid #222; width:20%">Item Name</th>
			<th style="padding:0px; border:1px solid #222; width:45%">Description</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Qty</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Unit</th>	
		</tr>';
		   $sno=0;
			foreach($bomitems as $i){ $sno++;
			   $qissued = getField('e_items_alloc',array('item_id'=>$i['item_id'],'instrument_no'=>$bomid),'item_quantity');
				$myobj .='
				<tr>
				<td style="padding:0px; border:1px solid #222;">'.$sno.'</td>
				<td style="padding:0px; border:1px solid #222;">'.$i['item_id'].'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_items',array('id'=>$i['item_id']),'item_name').'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_items',array('id'=>$i['item_id']),'item_description').' </td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$qissued.'/'.$i['qty_needed'].'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_units',array('id'=>getField('e_items',array('id'=>$i['item_id']),'item_unit')),'title').'</td>
				</tr>'; 
			}
			
		$myobj .='</table>
			</td>
		</tr>
    </table>
		';
		//return $myobj;	
		
		$this->generatePDF('MAT_NO:'.$bomid, $myobj);
	}
	
	public function downloadPO($bomid)
	{
		$myobj='';
		$bom = $this->AdminModel->getRow('e_po',array("id"=>$bomid));
		$bomitems = $this->AdminModel->getRows('e_poitems',array("po_id"=>$bomid));
		$settings 	= $this->SiteModel->getSetting();
		//extract($bom);
		
		$myobj .='
		<style>
		table { font-size:10px; font-family :helvetica}
		</style>
        <table border="0" cellpadding="1" cellspacing="0" width="100%">
		<tr>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<img src="'.base_url().'assets/img/167635404789763.png" width="120" alt="" >
			<h5>Date  : '.$bom['generated_on'].'</h5>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h1>Purchase Order - PO</h1>
			
			
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h5>PO # : '.$bomid.'</h5>
			<h5>Supplier : '.getField('e_suppliers',array('userid'=>$bom['supplier_id']),'title').'</h5>
			</td>
		</tr>
		<tr>
			<td style="padding:0px; border-top:1px solid #222;" colspan="3"><br><br>
			<table border="0" cellpadding="1" cellspacing="0" style="width:100%; border:1px solid #222">
		<tr style="background-color:grey;color:#fff">
			<th style="padding:0px; border:1px solid #222; width:5%">S.No</th>
			<th style="padding:0px; border:1px solid #222; width:25%">Item Name</th>
			<th style="padding:0px; border:1px solid #222; width:50%">Description</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Qty</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Unit</th>	
		</tr>';
		   $sno=0;
			foreach($bomitems as $i){ $sno++;
				$myobj .='
				<tr>
				<td style="padding:0px; border:1px solid #222;">'.$sno.'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_items',array('id'=>$i['item_id']),'item_name').'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_items',array('id'=>$i['item_id']),'item_description').' </td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['qty_needed'].'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_units',array('id'=>getField('e_items',array('id'=>$i['item_id']),'item_unit')),'title').'</td>
				</tr>'; 
			}
			
		$myobj .='</table>
			</td>
		</tr>
    </table>
		';
		//return $myobj;	
		
		$this->generatePDF('PO_NO:'.$bomid, $myobj);
	}
	
	public function downloadGrn($bomid)
	{
		$myobj='';
		$bom = $this->AdminModel->getRow('e_po',array("id"=>$bomid));
		$bomitems = $this->AdminModel->getRows('e_poitems',array("po_id"=>$bomid));
		$settings 	= $this->SiteModel->getSetting();
		//extract($bom);
		$totamount=0;
		foreach($bomitems as $i){ 
			$unitprice = getField('e_items_qty',array('item_id'=>$i['item_id'],'grn_no'=>$bomid),'pkr_unit_price');
			$qtyReceived = getField('e_items_qty',array('item_id'=>$i['item_id'],'grn_no'=>$bomid),'item_quantity');
			if($unitprice>0 && $qtyReceived>0)
				$totamount += ($unitprice*$qtyReceived);
			else
				$totamount+=0;
		}
		$myobj .='
		<style>
		table { font-size:10px; font-family :helvetica}
		</style>
        <table border="0" cellpadding="1" cellspacing="0" width="100%">
		<tr style="padding:0px; border-bottom:1px solid #222;">
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<img src="'.base_url().'assets/img/167635404789763.png" width="120" alt="" >
			</td>
			<td style="padding-top:50px; border-top:1px solid #fff; text-align:center; width:67%;  colspan="2" ">
			
			<h1><br>Goods Received Note</h1>
			</td>
		</tr>
		<tr>
			<td style="padding:5px; border-top:1px solid #222;" colspan="3">
				<table border="0" cellpadding="2" cellspacing="2" style="width:100%;">
				<tr>
				<th style="padding:0px; width:15%">Supplier</th><td style="width:35%;">'.getField('e_suppliers',array('id'=>$bom['supplier_id']),'title').'</td>
				<th style="padding:0px; width:15%">GRN No</th><td style="width:35%">'.$bomid.'</td>
				</tr>
				<tr>
				<th style="padding:0px; width:15%">Receiver</th><td style="width:35%;">'.getField('e_admin',array('userid'=>getField('e_items_qty',array('grn_no'=>$bomid),'grn_by')),'name').'</td>
				<th style="padding:0px; width:15%">Invice No</th><td style="width:35%"></td>
				</tr>
				<tr>
				<th style="padding:0px; width:15%">Receiving Date</th><td style="width:35%;">'.getField('e_items_qty',array('grn_no'=>$bomid),'purchase_date').'</td>
				<th style="padding:0px; width:15%">Invice Date</th><td style="width:35%"></td>
				</tr>
				<tr>
				<th style="padding:0px; width:15%">PO No</th><td style="width:35%;">'.$bomid.'</td>
				<th style="padding:0px; width:15%">Remarks <small>(if no PO)</small> </th><td style="width:35%"></td>
				</tr>
				<tr>
				<th style="padding:0px; width:15%">Type</th><td style="width:35%;"></td>
				<th style="padding:0px; width:15%">Total Amounts </th><td style="width:35%">'.$totamount.'</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="padding:0px;" colspan="3"><br><br>
			<table border="0" cellpadding="1" cellspacing="0" style="width:100%; border:1px solid #222">
		<tr style="background-color:grey;color:#fff">
			<th style="padding:0px; border:1px solid #222; width:5%">S.No</th>
			<th style="padding:0px; border:1px solid #222; width:15%">MIS #</th>
			<th style="padding:0px; border:1px solid #222; width:40%">Name/Description</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Qty</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Unit</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Rate</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">T.Amount</th>
		</tr>';
		   $sno=0;
			foreach($bomitems as $i){ $sno++;
			$unitprice = getField('e_items_qty',array('item_id'=>$i['item_id'],'grn_no'=>$bomid),'pkr_unit_price');
			$qtyReceived = getField('e_items_qty',array('item_id'=>$i['item_id'],'grn_no'=>$bomid),'item_quantity');
			if($unitprice>0 && $qtyReceived>0)
				$amount = $unitprice*$qtyReceived;
			else
				$amount=0;
				$myobj .='
				<tr>
				<td style="padding:0px; border:1px solid #222;">'.$sno.'</td>
				<td style="padding:0px; border:1px solid #222;">'.$i['item_id'].'</td>
				<td style="padding:0px; border:1px solid #222;"><strong>'.getField('e_items',array('id'=>$i['item_id']),'item_name').'</strong><br>'.getField('e_items',array('id'=>$i['item_id']),'item_description').' </td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['qty_needed'].'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_units',array('id'=>getField('e_items',array('id'=>$i['item_id']),'item_unit')),'title').'</td>
				<td style="padding:0px; border:1px solid #222;">'.$unitprice.'</td>
				<td style="padding:0px; border:1px solid #222;">'.$amount.'</td>
				</tr>'; 
			}
			
		$myobj .='</table>
			</td>
		</tr>
    </table>
		';
		//return $myobj;	
		
		$this->generatePDF('GRN_NO:'.$bomid, $myobj);
	}
	
	public function deliveryChallan($bomid)
	{
		$myobj	=	'';
		$bom 		= $this->AdminModel->getRow('e_dc',array("id"=>$bomid));
		$bomitems 	= $this->AdminModel->getRows('e_dcitems',array("adv_id"=>$bomid));
		$settings 	= $this->SiteModel->getSetting();
		//extract($bom);
		$myobj .='
		<style>
		table { font-size:10px; font-family :helvetica}
		</style>
        <table border="0" cellpadding="1" cellspacing="0" width="100%">
		<tr style="padding:0px; border-bottom:1px solid #222;">
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<img src="'.base_url().'assets/img/167635404789763.png" width="120" alt="" >
			</td>
			<td style="padding-top:50px; border-top:1px solid #fff; text-align:center; width:67%;  colspan="2" ">
			
			<h1><br>Delivery Challan</h1>
			</td>
		</tr>
		<tr>
			<td style="padding:5px; border-top:1px solid #222;" colspan="3"><br>
				<table border="0" cellpadding="2" cellspacing="2" style="width:100%;">
				<tr>
				<td style="padding:0px; width:50%; border:1px solid #222">TO:<h2>'.getField('e_customers',array('id'=>$bom['customer_id']),'title').'</h2></td>
				<td style="padding:0px; width:50%; border:1px solid #222">
				<table border="0" cellpadding="2" cellspacing="2" style="width:100%;">
				<tr style="padding:0px; border-bottom:1px solid #222">
				<td style="width:50%">No</td><td>DC - '.$bom['id'].'</td>
				</tr>
				<tr style="padding:0px; border-bottom:1px solid #222">
				<td style="width:50%">Date</td><td>'.$bom['dc_date'].'</td>
				</tr>
				<tr style="padding:0px; border-bottom:1px solid #222">
				<td style="width:50%">Carrier</td><td>'.$bom['carrier_name'].'</td>
				</tr>
				</table>
				</td>
				</tr>
				<tr>
				<td style="padding:0px; width:50%; border:1px solid #222">ATTN: </td>
				<td style="padding:0px; width:50%; border:1px solid #222">Sale Order: '.$bom['sale_order_no'].'	</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="padding:0px;" colspan="3"><br><br>
			<table border="0" cellpadding="1" cellspacing="0" style="width:100%; border:1px solid #222">
		<tr style="background-color:grey;color:#fff">
			<th style="padding:0px; border:1px solid #222; width:5%">S.No</th>
			<th style="padding:0px; border:1px solid #222; width:15%">MIS #</th>
			<th style="padding:0px; border:1px solid #222; width:40%">Name/Description</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Part Numer</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Model</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Qty</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Unit</th>
		</tr>';
		   $sno=0;
			foreach($bomitems as $i){ $sno++;
			$myobj .='
				<tr>
				<td style="padding:0px; border:1px solid #222;">'.$sno.'</td>
				<td style="padding:0px; border:1px solid #222;">'.$i['item_id'].'</td>
				<td style="padding:0px; border:1px solid #222;"><strong>'.getField('e_items',array('id'=>$i['item_id']),'item_name').'</strong><br>'.getField('e_items',array('id'=>$i['item_id']),'item_description').' </td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_items',array('id'=>$i['item_id']),'part_number').'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center"></td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['qty_needed'].'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_units',array('id'=>getField('e_items',array('id'=>$i['item_id']),'item_unit')),'title').'</td>
				</tr>'; 
			}
			
		$myobj .='</table>
			</td>
		</tr>
		
		<tr>
			<td style="padding:5px; text-align:center" colspan="3"><br><br>
				<table border="0" cellpadding="2" cellspacing="2" style="width:100%;">
				<tr>
				<td style="padding:0px; width:50%; border:0px solid #fff"><strong>Despatched By: </strong></td>
				<td style="padding:0px; width:50%; border:0px solid #fff">Received the above material</td>
				</tr>
				<tr>
				<td style="padding:0px; width:50%; border:0px solid #fff">'.getField('e_admin',array('userid'=>$bom['generated_by']),'name').'</td>
				<td style="padding:0px; width:50%; border:0px solid #fff"></td>
				</tr>
				<tr>
				<td style="padding:0px; width:50%; border:0px solid #fff"> </td>
				<td style="padding:0px; width:50%; border:0px solid #fff"> </td>
				</tr>
				<tr>
				<td style="padding:0px; width:50%; border:0px solid #fff">Signature ____________________</td>
				<td style="padding:0px; width:50%; border:0px solid #fff">Receiver Signature ____________________</td>
				</tr>
				<tr>
				<td style="padding:0px; border:0px solid #fff" colspan="2"><br>Note: Please sign and send the second copy to sender</td>
				</tr>
				</table>
			</td>
		</tr>
		
    </table>
		';
		//return $myobj;	
		
		$this->generatePDF('DC_NO:'.$bomid, $myobj);
	}
	
	public function downloadAdvBooking($bomid)
	{
		$myobj='';
		$bom = $this->AdminModel->getRow('e_adv',array("id"=>$bomid));
		$bomitems = $this->AdminModel->getRows('e_advitems',array("adv_id"=>$bomid));
		$settings 	= $this->SiteModel->getSetting();
		//extract($bom);
		
		$myobj .='
		<style>
		table { font-size:10px; font-family :helvetica}
		</style>
        <table border="0" cellpadding="1" cellspacing="0" width="100%">
		<tr>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<img src="'.base_url().'assets/img/167635404789763.png" width="120" alt="" >
			<h5>Date  : '.$bom['generated_on'].'</h5>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h1>Advance Booking Items</h1>
			<h5>Generated  By : '.getField('e_admin',array('userid'=>$bom['generated_by']),'name').'</h5>
			<h5>Approved &nbsp;By: '.getField('e_admin',array('userid'=>$bom['approved_by']),'name').'</h5>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h5>Adv Booking Request # : '.$bomid.'</h5>
			<h5>Project  : '.getField('e_projects',array('id'=>$bom['project_id']),'title').'</h5>
			<h5>Department : '.getField('e_departments',array('id'=>$bom['department_id']),'title').'</h5>
			<h5>Section  : '.getField('e_departments',array('id'=>$bom['section_id']),'title').'</h5>
			</td>
		</tr>
		<tr>
			<td style="padding:0px; border-top:1px solid #222;" colspan="3"><br><br>
			<table border="0" cellpadding="1" cellspacing="0" style="width:100%; border:1px solid #222">
		<tr style="background-color:grey;color:#fff">
			<th style="padding:0px; border:1px solid #222; width:5%">S.No</th>
			<th style="padding:0px; border:1px solid #222; width:10%">MIS#</th>
			<th style="padding:0px; border:1px solid #222; width:20%">Item </th>
			<th style="padding:0px; border:1px solid #222; width:35%">Description</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Required Date</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Qty</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Unit</th>	
		</tr>';
		   $sno=0;
			foreach($bomitems as $i){ $sno++;
				$myobj .='
				<tr>
				<td style="padding:0px; border:1px solid #222;">'.$sno.'</td>
				<td style="padding:0px; border:1px solid #222;">'.$i['item_id'].'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_items',array('id'=>$i['item_id']),'item_name').'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_items',array('id'=>$i['item_id']),'item_description').' </td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['req_date'].'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['qty_needed'].'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_units',array('id'=>getField('e_items',array('id'=>$i['item_id']),'item_unit')),'title').'</td>
				</tr>'; 
			}
			
		$myobj .='</table>
			</td>
		</tr>
    </table>
		';
		//return $myobj;	
		
		$this->generatePDF('AdvBooking_NO:'.$bomid, $myobj);
	}
	
	public function downloadTempIssue($bomid)
	{
		$myobj='';
		$bom = $this->AdminModel->getRow('e_tempissue',array("id"=>$bomid));
		$bomitems = $this->AdminModel->getRows('e_tempissueitems',array("adv_id"=>$bomid));
		$settings 	= $this->SiteModel->getSetting();
		//extract($bom);
		
		$myobj .='
		<style>
		table { font-size:10px; font-family :helvetica}
		</style>
        <table border="0" cellpadding="1" cellspacing="0" width="100%">
		<tr>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<img src="'.base_url().'assets/img/167635404789763.png" width="120" alt="" >
			<h5>Date  : '.$bom['generated_on'].'</h5>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h1>Temp Issued Items</h1>
			<h3>Issued To : '.getField('e_admin',array('userid'=>$bom['generated_by']),'name').'</h3>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h5>Temp Issue Request # : '.$bomid.'</h5>
			<h5>Project  : '.getField('e_projects',array('id'=>$bom['project_id']),'title').'</h5>
			<h5>Department : '.getField('e_departments',array('id'=>$bom['department_id']),'title').'</h5>
			<h5>Section  : '.getField('e_departments',array('id'=>$bom['section_id']),'title').'</h5>
			</td>
		</tr>
		<tr>
			<td style="padding:0px; border-top:1px solid #222;" colspan="3"><br><br>
			<table border="0" cellpadding="1" cellspacing="0" style="width:100%; border:1px solid #222">
		<tr style="background-color:grey;color:#fff">
			<th style="padding:0px; border:1px solid #222; width:5%">S.No</th>
			<th style="padding:0px; border:1px solid #222; width:20%">MIS No</th>
			<th style="padding:0px; border:1px solid #222; width:35%">Item Name</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Qty Demanded</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Qty Issued</th>			
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Unit</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Issued On</th>
		</tr>';
		   $sno=0;
			foreach($bomitems as $i){ $sno++;
				$myobj .='
				<tr>
				<td style="padding:0px; border:1px solid #222;">'.$sno.'</td>
				<td style="padding:0px; border:1px solid #222;">'.$i['item_id'].'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_items',array('id'=>$i['item_id']),'item_name').'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['qty_needed'].'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_items_alloc',array('instrument'=>'tmpissue','instrument_no'=>$bomid,'item_id'=>$i['item_id']),'item_quantity').'</td>
				
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_units',array('id'=>getField('e_items',array('id'=>$i['item_id']),'item_unit')),'title').'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_items_alloc',array('instrument'=>'tmpissue','instrument_no'=>$bomid,'item_id'=>$i['item_id']),'createdon').'</td>
				</tr>'; 
			}
			
		$myobj .='</table>
			</td>
		</tr>
    </table>
		';
		//return $myobj;	
		
		$this->generatePDF('TempIssue_NO:'.$bomid, $myobj);
	}
	
	public function downloadReleases($bomid)
	{
		$myobj='';
		$bom = $this->AdminModel->getRow('e_release',array("id"=>$bomid));
		$bomitems = $this->AdminModel->getRows('e_releaseitems',array("adv_id"=>$bomid));
		$settings 	= $this->SiteModel->getSetting();
		//extract($bom);
		
		$myobj .='
		<style>
		table { font-size:10px; font-family :helvetica}
		</style>
        <table border="0" cellpadding="1" cellspacing="0" width="100%">
		<tr>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<img src="'.base_url().'assets/img/167635404789763.png" width="120" alt="" >
			<h5>Date  : '.$bom['generated_on'].'</h5>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h1>Release Request Items</h1>
			<h4>Requested By : '.getField('e_admin',array('userid'=>$bom['generated_by']),'name').'</h4>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h5>Release Request # : '.$bomid.'</h5>
			<h5>Project  : '.@getField('e_projects',array('id'=>$bom['project_id']),'title').'</h5>
			<h5>Department : '.@getField('e_departments',array('id'=>$bom['department_id']),'title').'</h5>
			<h5>Section  : '.@getField('e_departments',array('id'=>$bom['section_id']),'title').'</h5>
			</td>
		</tr>
		<tr>
			<td style="padding:0px; border-top:1px solid #222;" colspan="3"><br><br>
			<table border="0" cellpadding="1" cellspacing="0" style="width:100%; border:1px solid #222">
		<tr style="background-color:grey;color:#fff">
			<th style="padding:0px; border:1px solid #222; width:5%">S.No</th>
			<th style="padding:0px; border:1px solid #222; width:20%">MIS No</th>
			<th style="padding:0px; border:1px solid #222; width:35%">Item Name</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Qty Requested</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Qty Released</th>			
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Unit</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Released On</th>
		</tr>';
		   $sno=0;
			foreach($bomitems as $i){ $sno++;
				$myobj .='
				<tr>
				<td style="padding:0px; border:1px solid #222;">'.$sno.'</td>
				<td style="padding:0px; border:1px solid #222;">'.$i['item_id'].'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_items',array('id'=>$i['item_id']),'item_name').'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['qty_needed'].'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['qty_restocked'].'</td>
				
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_units',array('id'=>getField('e_items',array('id'=>$i['item_id']),'item_unit')),'title').'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['issue_date'].'</td>
				</tr>'; 
			}
			
		$myobj .='</table>
			</td>
		</tr>
    </table>
		';
		//return $myobj;	
		
		$this->generatePDF('Release_NO:'.$bomid, $myobj);
	}
	
	
	public function downloadRestock($bomid)
	{
		$myobj='';
		$bom = $this->AdminModel->getRow('e_restock',array("id"=>$bomid));
		$bomitems = $this->AdminModel->getRows('e_restockitems',array("adv_id"=>$bomid));
		$settings 	= $this->SiteModel->getSetting();
		//extract($bom);
		
		$myobj .='
		<style>
		table { font-size:10px; font-family :helvetica}
		</style>
        <table border="0" cellpadding="1" cellspacing="0" width="100%">
		<tr>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<img src="'.base_url().'assets/img/167635404789763.png" width="120" alt="" >
			<h5>Date  : '.$bom['generated_on'].'</h5>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h1>Restock Request Items</h1>
			<h4>Restocked By : '.getField('e_admin',array('userid'=>$bom['generated_by']),'name').'</h4>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h5>Restock Request # : '.$bomid.'</h5>
			</td>
		</tr>
		<tr>
			<td style="padding:0px; border-top:1px solid #222;" colspan="3"><br><br>
			<table border="0" cellpadding="1" cellspacing="0" style="width:100%; border:1px solid #222">
		<tr style="background-color:grey;color:#fff">
			<th style="padding:0px; border:1px solid #222; width:5%">S.No</th>
			<th style="padding:0px; border:1px solid #222; width:20%">MIS No</th>
			<th style="padding:0px; border:1px solid #222; width:35%">Item Name</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Qty </th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Qty Restocked</th>			
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Unit</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Restock Date</th>
		</tr>';
		   $sno=0;
			foreach($bomitems as $i){ $sno++;
				$myobj .='
				<tr>
				<td style="padding:0px; border:1px solid #222;">'.$sno.'</td>
				<td style="padding:0px; border:1px solid #222;">'.$i['item_id'].'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_items',array('id'=>$i['item_id']),'item_name').'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['qty_needed'].'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['qty_restocked'].'</td>
				
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_units',array('id'=>getField('e_items',array('id'=>$i['item_id']),'item_unit')),'title').'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['issue_date'].'</td>
				</tr>'; 
			}
			
		$myobj .='</table>
			</td>
		</tr>
    </table>
		';
		//return $myobj;	
		
		$this->generatePDF('Restock_NO:'.$bomid, $myobj);
	}
	
	
	public function downloadMaterialMovement($bomid)
	{
		$myobj	=	'';
		$bom 		= $this->AdminModel->getRow('e_dc',array("id"=>$bomid));
		$bomitems 	= $this->AdminModel->getRows('e_dcitems',array("adv_id"=>$bomid));
		$settings 	= $this->SiteModel->getSetting();
		//extract($bom);
		$myobj .='
		<style>
		table { font-size:10px; font-family :helvetica}
		</style>
        <table border="0" cellpadding="1" cellspacing="0" width="100%">
		<tr style="padding:0px; border-bottom:1px solid #222;">
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<img src="'.base_url().'assets/img/167635404789763.png" width="120" alt="" >
			</td>
			<td style="padding-top:50px; border-top:1px solid #fff; text-align:center; width:67%;  colspan="2" ">
			
			<h1><br>Material Movement - MMR</h1>
			<h3>MMR No- '.$bom['id'].'</h3>
			</td>
		</tr>
		<tr>
			<td style="padding:5px; border-top:1px solid #222;" colspan="3"><br>
				<table border="0" cellpadding="2" cellspacing="2" style="width:100%;">
				<tr>
				<td style="padding:0px; width:50%; border:1px solid #222">TO:<h2>'.getField('e_customers',array('id'=>$bom['customer_id']),'title').'</h2></td>
				<td style="padding:0px; width:50%; border:1px solid #222">
				<table border="0" cellpadding="2" cellspacing="2" style="width:100%;">
				<tr style="padding:0px; border-bottom:1px solid #222">
				<td style="width:50%">DC No</td><td>DC - '.$bom['id'].'</td>
				</tr>
				<tr style="padding:0px; border-bottom:1px solid #222">
				<td style="width:50%">Date</td><td>'.$bom['dc_date'].'</td>
				</tr>
				<tr style="padding:0px; border-bottom:1px solid #222">
				<td style="width:50%">Carrier</td><td>'.$bom['carrier_name'].'</td>
				</tr>
				</table>
				</td>
				</tr>
				<tr>
				<td style="padding:0px; width:50%; border:1px solid #222">ATTN: </td>
				<td style="padding:0px; width:50%; border:1px solid #222">Sale Order: '.$bom['sale_order_no'].'	</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="padding:0px;" colspan="3"><br><br>
			<table border="0" cellpadding="1" cellspacing="0" style="width:100%; border:1px solid #222">
		<tr style="background-color:grey;color:#fff">
			<th style="padding:0px; border:1px solid #222; width:5%">S.No</th>
			<th style="padding:0px; border:1px solid #222; width:15%">MIS #</th>
			<th style="padding:0px; border:1px solid #222; width:40%">Name/Description</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Part Numer</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Model</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Qty</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Unit</th>
		</tr>';
		   $sno=0;
			foreach($bomitems as $i){ $sno++;
			$myobj .='
				<tr>
				<td style="padding:0px; border:1px solid #222;">'.$sno.'</td>
				<td style="padding:0px; border:1px solid #222;">'.$i['item_id'].'</td>
				<td style="padding:0px; border:1px solid #222;"><strong>'.getField('e_items',array('id'=>$i['item_id']),'item_name').'</strong><br>'.getField('e_items',array('id'=>$i['item_id']),'item_description').' </td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_items',array('id'=>$i['item_id']),'part_number').'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center"></td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['qty_needed'].'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_units',array('id'=>getField('e_items',array('id'=>$i['item_id']),'item_unit')),'title').'</td>
				</tr>'; 
			}
			
		$myobj .='</table>
			</td>
		</tr>
		
		<tr>
			<td style="padding:5px; text-align:center" colspan="3"><br><br>
				<table border="0" cellpadding="2" cellspacing="2" style="width:100%;">
				<tr>
				<td style="padding:0px; width:50%; border:0px solid #fff"><strong>Despatched By: </strong></td>
				<td style="padding:0px; width:50%; border:0px solid #fff">Received the above material</td>
				</tr>
				<tr>
				<td style="padding:0px; width:50%; border:0px solid #fff">'.getField('e_admin',array('userid'=>$bom['generated_by']),'name').'</td>
				<td style="padding:0px; width:50%; border:0px solid #fff"></td>
				</tr>
				<tr>
				<td style="padding:0px; width:50%; border:0px solid #fff"> </td>
				<td style="padding:0px; width:50%; border:0px solid #fff"> </td>
				</tr>
				<tr>
				<td style="padding:0px; width:50%; border:0px solid #fff">Signature ____________________</td>
				<td style="padding:0px; width:50%; border:0px solid #fff">Receiver Signature ____________________</td>
				</tr>
				<tr>
				<td style="padding:0px; border:0px solid #fff" colspan="2"><br>Note: Please sign and send the second copy to sender</td>
				</tr>
				</table>
			</td>
		</tr>
		
    </table>
		';
		//return $myobj;	
		
		$this->generatePDF('MMR_NO:'.$bomid, $myobj);
	}
	
	
	public function downloadMatmov($bomid)
	{
		$myobj='';
		$bom = $this->AdminModel->getRow('e_mm',array("id"=>$bomid));
		$bomitems = $this->AdminModel->getRows('e_mmitems',array("adv_id"=>$bomid));
		$settings 	= $this->SiteModel->getSetting();
		//extract($bom);
		
		$myobj .='
		<style>
		table { font-size:10px; font-family :helvetica}
		</style>
        <table border="0" cellpadding="1" cellspacing="0" width="100%">
		<tr>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<img src="'.base_url().'assets/img/167635404789763.png" width="120" alt="" >
			<h5>Date  : '.$bom['generated_on'].'</h5>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h1>MatMov Items</h1>
			<h3>Issued To : '.getField('e_admin',array('userid'=>$bom['generated_by']),'name').'</h3>
			</td>
			<td style="padding:0px; border-top:1px solid #fff; width:33%; ">
			<h5>MM Request # : '.$bomid.'</h5>
			<h5>Subject  : '.$bom['mm_title'].'</h5>
			</td>
		</tr>
		<tr>
			<td style="padding:0px; border-top:1px solid #222;" colspan="3"><br><br>
			<table border="0" cellpadding="1" cellspacing="0" style="width:100%; border:1px solid #222">
		<tr style="background-color:grey;color:#fff">
			<th style="padding:0px; border:1px solid #222; width:5%">S.No</th>
			<th style="padding:0px; border:1px solid #222; width:20%">MIS No</th>
			<th style="padding:0px; border:1px solid #222; width:35%">Item Name</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Qty Demanded</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Qty Issued</th>			
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Unit</th>
			<th style="padding:0px; border:1px solid #222; text-align:center; width:10%">Issued On</th>
		</tr>';
		   $sno=0;
			foreach($bomitems as $i){ $sno++;
				$myobj .='
				<tr>
				<td style="padding:0px; border:1px solid #222;">'.$sno.'</td>
				<td style="padding:0px; border:1px solid #222;">'.$i['item_id'].'</td>
				<td style="padding:0px; border:1px solid #222;">'.getField('e_items',array('id'=>$i['item_id']),'item_name').'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['qty_needed'].'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['qty_issued'].'</td>
				
				<td style="padding:0px; border:1px solid #222; text-align:center">'.getField('e_units',array('id'=>getField('e_items',array('id'=>$i['item_id']),'item_unit')),'title').'</td>
				<td style="padding:0px; border:1px solid #222; text-align:center">'.$i['issue_date'].'</td>
				</tr>'; 
			}
			
		$myobj .='</table>
			</td>
		</tr>
    </table>
		';
		//return $myobj;	
		
		$this->generatePDF('MM_NO:'.$bomid, $myobj);
	}
	/*
$route['Pdfgenerator/deliveryChallan']='Pdfgenerator/downloadFixedAsset/$1';
*/
	
} 
