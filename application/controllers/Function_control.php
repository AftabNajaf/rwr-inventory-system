<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Function_control extends CI_Controller {

	public function __construct(){
		
        parent::__construct();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('function_helper');
		$this->load->Model('AdminModel');
		$this->load->library('image_lib');
		$this->load->library('upload');
		$this->load->library('SSP');
		login_check();
		$this->load->helper('security');
    }

	public function index()
	{
	  echo "Nothing to show. Function_control";
	}
	
	public function getItemName(){
		$itemID = $this->input->post('itemID');
		echo $itemID."-".getField("e_items",array("id"=>$itemID),"item_name");
		
	}
	public function getPOItemDetail(){
		$itemID = $this->input->post('itemID');
		 
		$data = $this->AdminModel->getRow("e_items_qty",array("item_id"=>$itemID));
		$data['item_id']=$itemID."-".getField("e_items",array("id"=>$itemID),"item_name");
		echo json_encode($data);
		
	}
	public function getPOItemDetailById(){
		$itemID = $this->input->post('itemID');
		
		$datax = $this->AdminModel->getRow("e_poitems",array("id"=>$itemID));
		
		$data = $this->AdminModel->getRow("e_items_qty",array("item_id"=>$datax['item_id']));
	
		$output['qty_needed']=$datax['qty_needed'];
		$output['item_id']=$datax['item_id']."-".getField("e_items",array("id"=>$datax['item_id']),"item_name");;
		$output['supplier_ref']=$data['supplier_ref'];
		$output['part_number']=$data['part_number'];
		$output['xitem']=$datax['item_id'];
		echo json_encode($output);
		
	}
	public function trackCategory(){
		$catid = $this->input->post('catid');
		$tblName = $this->input->post('tableName');
		$r = $this->AdminModel->getRow('e_categories', array("id"=>$catid));
		$i = $this->AdminModel->getRow('e_items', array("id"=>$catid)); 
		$str='';
		if($r && !$i){
			$str 	= $r['id'];
			$parent	= $r['parentid'];
			while($parent>0){
				$x = $this->AdminModel->getRow('e_categories',array('id'=>$parent));
				$str = $x['id'].','.$str;
				$parent	= $x['parentid'];
			}
		}
		else if($i){
			$parentx	= $this->AdminModel->getRow('e_categories', array("id"=>$i['item_category']));
			if($parentx) $parent=$parentx['parentid']; else $parent=0;
			$str 	= $i['item_category'];
			while($parent>0){
				$x = $this->AdminModel->getRow('e_categories',array('id'=>$parent));
				$str = $x['id'].','.$str;
				$parent	= $x['parentid'];
			}
			
			
			$str .=','.$i['id'];
			
		}
		
		echo $str;
	}
	
	public function trackTempPath(){
		$catid = $this->input->post('catid');				
		echo $this->AdminModel->getCatPath($catid);
	}
	
	public function applyMPF(){
		$value = $this->input->post('val');
		$id  =  $this->input->post('matid');
		
		//$catz = $this->AdminModel->myQueryx(");
		$this->db->query("update e_matitem set qty_needed = (qty_needed * ".$value." )  where mat_id = ".$id);
		echo "update e_matitem set qty_needed = (qty_needed * ".$value." )  where mat_id = ".$id;
	}
	
	public function getAvailQty(){
		$value = $this->input->post('itemID');
		$catz = $this->AdminModel->myQueryx("SELECT i.id,i.item_name,i.item_functionality,i.part_number, i.item_serial_no,i.image,i.doc_list,i.item_description,i.min_qty,i.max_qty,i.supplier_id, i.supplier_ref,i.item_category, i.item_addl_remarks FROM e_items i where  i.id=".$value);
		$this->db->query('SET SESSION SQL_MODE=""');
		$catzQ = $this->AdminModel->myQueryx("SELECT sum(q.item_quantity) item_quantity, location, custodian FROM  e_items_qty q where q.item_id=".$value." order by id DESC");
		
		$txt ='';
	if($catz){
		 extract($catz);
			if($image!='')
			$image='<a href="'.base_url().'assets/img/itmimages/'.$image.'" data-fancybox data-caption="'.$item_name.'"><img class="img-thumbnail" src="'.base_url().'assets/img/itmimages/'.$image.'" style="width:150px" ></a> ';
			else
			$image='<a href="'.base_url().'assets/img/itmimages/'.$id.'.jpg" data-fancybox data-caption="'.$item_name.'"><img class="img-thumbnail" src="'.base_url().'assets/img/itmimages/'.$id.'.jpg" style="width:150px" ></a> ';
			 
			if($doc_list!='')
			$doc_list='<a href="'.base_url().'assets/lib/'.$doc_list.'" target="blank">
             <img src="'.base_url().'assets/img/docicon.png"  style="width:50px"></a> ';
			 
			 ###restock
			 $xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('restock')");//
			if($xx[0]['item_quantity']>=0){} 
			else $xx[0]['item_quantity']=0;
			
			$restockedItems = $xx[0]['item_quantity'];
			
			###release
			 $xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('release')");// group by from_instrument,from_instrument_no
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$releaseItems = $xx[0]['item_quantity'];
			
			
			###TMP ISSUES
			$xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('tmpissue') ");// group by instrument,instrument_no
			
			$yy = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('restock') and from_instrument in ('tmpissue') ");// group by instrument,instrument_no
			if($yy[0]['item_quantity']>=0){} else $yy[0]['item_quantity']=0;
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$tmpissueItems = $xx[0]['item_quantity']-$yy[0]['item_quantity'];
			
			###ADV ISSUES
			$xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('adv')");// group by instrument,instrument_no
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$advbookedItems = $xx[0]['item_quantity'];
			
			###MAT ISSUES
			$xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('mat')");// group by instrument,instrument_no
			$yy = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('restock') and from_instrument in ('mat') ");// group by instrument,instrument_no
			if($yy[0]['item_quantity']>=0){} else $yy[0]['item_quantity']=0;
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$matissuedItems = $xx[0]['item_quantity']-$yy[0]['item_quantity'];
			
			###MAT ISSUES
			$xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('mmov')");// group by instrument,instrument_no
			$yy = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('restock') and from_instrument in ('mmov') ");// group by instrument,instrument_no
			if($yy[0]['item_quantity']>=0){} else $yy[0]['item_quantity']=0;
			
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$mmrItems = $xx[0]['item_quantity']-$yy[0]['item_quantity'];
			/* $xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('mat','adv','tmpissue')");
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;*/
			
			
			
		
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>MIS # : </strong>".$id."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong> Name : </strong>".$item_name."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Ser # : </strong>".$item_serial_no."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Part number  : </strong>".$part_number."</div>";
$txt .="<div style='border-bottom: #ddd solid 1px'><strong>Min Qty  : </strong>".$min_qty."</div>";
$txt .="<div style='border-bottom: #ddd solid 1px'><strong>Max Qty  : </strong>".$max_qty."</div>";
$txt .="<div style='border-bottom: #ddd solid 1px'><strong>Unit Price  : </strong>".getField("e_items_qty",array("item_id"=>$id),"pkr_unit_price")."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Supplier Ref  : </strong>".$supplier_ref."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Supplier Code : </strong>".$item_addl_remarks."</div><br/>";
//getField("e_suppliers",array("id"=>$supplier_id),"title")
		if($catzQ){
		 extract($catzQ); 
		 $availInStore = ($item_quantity-$tmpissueItems-$advbookedItems-$matissuedItems-$mmrItems-$releaseItems);//+$releaseItems+$restockedItems
		 
		  $txt .="<div style='border-bottom:get #ddd solid 1px'><strong>Avail Qty  : </strong>".$availInStore."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Temp.Issued  : </strong><a href='".base_url()."Home/alloc_details/tmpissue/".$id."'>".($tmpissueItems)."</a></div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Adv. Booked  : </strong><a href='".base_url()."Home/alloc_details/adv/".$id."'>".($advbookedItems-$releaseItems)."</a></div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>MMR. Issue  : </strong><a href='".base_url()."Home/alloc_details/mmov/".$id."'>".$mmrItems."</a></div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Mat. Isssue  : </strong><a href='".base_url()."Home/alloc_details/mat/".$id."'>".($matissuedItems)."</a></div><br/>";
		  
		}
	
$txt .="<div style='border-bottom: #ddd solid 1px'><strong>Functionality: </strong><br>".$item_functionality."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Description : </strong><br>".$item_description."</div><br/>";
		  
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Location : </strong><br>".$location."</div><br/>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Custodian : </strong><br>".$custodian."</div><br/>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Status : </strong><br>".$item_addl_remarks."</div><br/>";
		  
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Path  : </strong>".$this->AdminModel->getCatPath($item_category)."</div><br />";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Document  : </strong>".$doc_list."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Image  : </strong><br />".$image."</div><br /><br/>";
		  
	}
		echo $availInStore;//echo $txt;
	}
	public function getItemDetail(){
		$value = $this->input->post('itemID');
		$catz = $this->AdminModel->myQueryx("SELECT i.id,i.item_name,i.item_functionality,i.part_number, i.item_asset_no,i.item_serial_no,i.image,i.doc_list,i.item_description,i.min_qty,i.max_qty,i.supplier_id, i.supplier_ref,i.item_category, i.item_addl_remarks,i.weblink FROM e_items i where  i.id=".$value);
		$this->db->query('SET SESSION SQL_MODE=""');
		$catzQ = $this->AdminModel->myQueryx("SELECT sum(q.item_quantity) item_quantity, location, custodian FROM  e_items_qty q where q.item_id=".$value." order by id DESC");
		
		$txt ='';
	if($catz){
		 extract($catz);
			if($image!='')
			$image='<a href="'.base_url().'assets/img/itmimages/'.$image.'" data-fancybox data-caption="'.$item_name.'"><img class="img-thumbnail" src="'.base_url().'assets/img/itmimages/'.$image.'" style="width:150px" ></a> ';
			else
			$image='<a href="'.base_url().'assets/img/itmimages/'.$id.'.jpg" data-fancybox data-caption="'.$item_name.'"><img class="img-thumbnail" src="'.base_url().'assets/img/itmimages/'.$id.'.jpg" style="width:150px" ></a> ';
			 
			if($doc_list!='')
			$doc_list='<a href="'.base_url().'assets/lib/'.$doc_list.'" target="blank">
             <img src="'.base_url().'assets/img/docicon.png"  style="width:50px"></a> ';
			 
			 ###restock
			 $xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('restock')");//
			if($xx[0]['item_quantity']>=0){} 
			else $xx[0]['item_quantity']=0;
			
			$restockedItems = $xx[0]['item_quantity'];
			
			###release
			 $xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('release')");// group by instrument,instrument_no
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$releaseItems = $xx[0]['item_quantity'];
			
			
			###TMP ISSUES
			$xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('tmpissue') ");// group by instrument,instrument_no
			
			$yy = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('restock') and from_instrument in ('tmpissue') ");// group by instrument,instrument_no
			if($yy[0]['item_quantity']>=0){} else $yy[0]['item_quantity']=0;
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$tmpissueItems = $xx[0]['item_quantity'];//-$yy[0]['item_quantity'];
			
			###ADV ISSUES
			$xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('adv')");// group by instrument,instrument_no
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$advbookedItems = $xx[0]['item_quantity'];
			
			###MAT ISSUES
			$xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('mat')");// group by instrument,instrument_no
			$yy = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('restock') and from_instrument in ('mat') ");// group by instrument,instrument_no
			if($yy[0]['item_quantity']>=0){} else $yy[0]['item_quantity']=0;
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$matissuedItems = $xx[0]['item_quantity'];//-$yy[0]['item_quantity'];
			
			###MAT ISSUES
			$xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('mmov')");// group by instrument,instrument_no
			$yy = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('restock') and from_instrument in ('mmov') ");// group by instrument,instrument_no
			if($yy[0]['item_quantity']>=0){} else $yy[0]['item_quantity']=0;
			
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;
			
			$mmrItems = $xx[0]['item_quantity']-$yy[0]['item_quantity'];
			/* $xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$value." and instrument in ('mat','adv','tmpissue')");
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;*/
			
			
			
		
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>MIS # : </strong>".$id."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong> Name : </strong>".$item_name."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Ser # : </strong>".$item_serial_no."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Part number  : </strong>".$part_number."</div>";
		   $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Asset #  : </strong>".$item_asset_no."</div>";
$txt .="<div style='border-bottom: #ddd solid 1px'><strong>Min Qty  : </strong>".$min_qty."</div>";
$txt .="<div style='border-bottom: #ddd solid 1px'><strong>Max Qty  : </strong>".$max_qty."</div>";
$txt .="<div style='border-bottom: #ddd solid 1px'><strong>Unit Price  : </strong>".getField("e_items_qty",array("item_id"=>$id),"pkr_unit_price")."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Supplier Ref  : </strong>".getField("e_items_qty",array("item_id"=>$id),"supplier_order_code")."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Supplier Code : </strong>".$item_addl_remarks."</div><br/>";
//getField("e_suppliers",array("id"=>$supplier_id),"title")
		if($catzQ){
		 extract($catzQ); 
		 $availInStore = ($item_quantity-$tmpissueItems-$advbookedItems-$matissuedItems-$mmrItems+$releaseItems);//+$restockedItems
		 
		  $txt .="<div style='border-bottom:get #ddd solid 1px'><strong>Avail Qty  : </strong>".$availInStore."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Temp.Issued  : </strong><a href='".base_url()."Home/alloc_details/tmpissue/".$id."'>".($tmpissueItems)."</a></div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Adv. Booked  : </strong><a href='".base_url()."Home/alloc_details/adv/".$id."'>".($advbookedItems)."</a></div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>MMR. Issue  : </strong><a href='".base_url()."Home/alloc_details/mmov/".$id."'>".$mmrItems."</a></div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Mat. Isssue  : </strong><a href='".base_url()."Home/alloc_details/mat/".$id."'>".($matissuedItems)."</a></div><br/>";
		  
		}
	  
$txt .="<div style='border-bottom: #ddd solid 1px'><strong>Functionality: </strong><br>".$item_functionality."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Description : </strong><br>".$item_description."</div><br/>";
		  
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Location : </strong><br>".$location."</div><br/>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Custodian : </strong><br>".$custodian."</div><br/>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Status : </strong><br></div><br/>";
		  
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Path  : </strong>".$this->AdminModel->getCatPath($item_category)."</div><br />";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Document  : </strong>".$doc_list."</div>";
		  $txt .="<div style='border-bottom: #ddd solid 1px'><strong>Image  : </strong><br />".$image."</div><br/>";
$txt .="<div style='border-bottom: #ddd solid 1px'><strong>Weblink  : </strong><br /><a href='".$weblink."' target='_blank'>Click Here</a></div><br/>";
$txt .="<div style='border-bottom: #ddd solid 1px'><strong>Document URL  : </strong><br /> <a href='file://192.168.0.200/MIS Docs/".$id."'>Click Here</a></div><br /><br/>";

		  
	}
		echo $txt;
	}
	
	
	public function searchForm(){
		$value = $this->input->post('searchString');
		$field = $this->input->post('searchOption');
		$data ='';
		

		
		$xtra='';
		if(strpos($value, ' ') !== false){//(str_contains($value, ' ')){
		$pieces = explode(" ", $value);
		$inString = ' in ('.implode(',', array_map(function($value){return "'" . $value . "'";},$pieces)).')';
		$xtra = ' OR item_description '.$inString;
		$xtra .= ' OR item_name '.$inString;
		}
		$keywords = explode(' ', $value); $qry="SELECT * FROM e_items WHERE ";
		if($field=='all'){
				foreach ($keywords as $keyword) {
					$qry .= "(id = '$keyword' OR item_description LIKE '%$keyword%' OR item_name LIKE '%$keyword%' OR part_number LIKE '%$keyword%' OR item_functionality LIKE '%$keyword%' OR item_serial_no LIKE '%$keyword%') AND ";
					}
				$qry = rtrim($qry, 'AND ');
		}
		else if($field=='id')
		$qry .= $field." = '".$value."'".$xtra;
		else
		$qry .= $field." like '%".$value."%'".$xtra;
		
		
		$itmz = $this->AdminModel->myQueryz($qry);
		
		#---incl catz
		$catz = $this->AdminModel->myQueryz("select * from e_categories where id='".$value."' OR title like '%".$value."%'");
		
		//echo $this->db->last_query();
		if($field!='id'){
		foreach($catz as $item){
			extract($item);
			$data .='<li><a href="#" onclick="searchMagic('.$id.')">'.$id.'-'.$title.'</li>';
		}
		}
		#----
		
		foreach($itmz as $item){
			extract($item);
			$data .='<li><a href="#" onclick="searchMagic('.$id.')">'.$id.'-'.$item_name.'</li>';
		}
		
		echo $data;
	}
	
	public function searchCatString(){
		$value = $this->input->post('searchString');
		$tblName = $this->input->post('tableName');
		$catz = $this->AdminModel->myQueryz("select * from e_categories where id='".$value."' OR title like '%".$value."%'");
		$data ='';
		foreach($catz as $item){
			extract($item);
			$data .='<li><a href="#" onclick="searchMagic('.$id.')">'.$title.'</li>';
		}
		// items
		if($tblName=='e_items'){
			$itemx = $this->AdminModel->myQueryz("select * from e_items where id='".$value."' OR item_category='".$value."' OR item_name like '%".$value."%'");
			$idata='<h5>Items </h5><ul>';
			foreach($itemx as $itm){
			$idata .='<li><a href="#" onclick="searchMagic('.$itm['id'].')">'.$itm['item_name'].'</li>';
			}
			$data .=$idata.'</ul>';
		}
		echo $data;
	}
	
	public function getChilds(){
		$xid = $this->input->post('parentid');
		$tblName = $this->input->post('tableName');
		$catz = $this->AdminModel->getRows('e_categories',array("parentid"=>$xid));
		//$items = $this->AdminModel->getRows('e_items',array("parentid"=>$id));
		$data ="";
		
		foreach($catz as $item){
			extract($item);
			$items = $this->AdminModel->getRows('e_items',array("item_category"=>$id));
			
			$skats = $this->AdminModel->getRow('e_categories',array("parentid"=>$id));
			
			if($tblName=='e_items'){##########---ITEMS
				if(($items && $skats)){
					$title = '<i class="fa fa-list-alt"></i> '.$title;
					$data .='<span class="accordion-item class'.$id.'" onclick="getChildItems('.$id.')" data-panel-id="nesting-panel-x'.$id.'">
					<button class="btn btn-success btn-sm" onclick="add(\'add\','.$id.')" ><i class="fa fa-plus"></i></button>&nbsp;'.
					str_pad($id,5,"0",STR_PAD_LEFT).'----'.$title.'</span><div class="accordion-panel" id="nesting-panel-x'.$id.'"></div>';
				}
				else if($items && !$skats){
					$title = '<i class="fa fa-list"></i> '.$title;
					$data .='<span class="accordion-item class'.$id.'" onclick="getChildItems('.$id.')" data-panel-id="nesting-panel-x'.$id.'">
					<button class="btn btn-success btn-sm" onclick="add(\'add\','.$id.')" ><i class="fa fa-plus"></i></button>&nbsp;'.
					str_pad($id,5,"0",STR_PAD_LEFT).'----'.$title.'</span><div class="accordion-panel" id="nesting-panel-x'.$id.'"></div>';
				}
				else if(!$items && $skats){
					$title = '<i class="fa fa-plus-circle"></i> '.$title;
					$data .='<span class="accordion-item class'.$id.'" onclick="getChilds('.$id.')" data-panel-id="nesting-panel'.$id.'">
					<button class="btn btn-success btn-sm" onclick="add(\'add\','.$id.')" ><i class="fa fa-plus"></i></button>&nbsp;'.
					str_pad($id,5,"0",STR_PAD_LEFT).'----'.$title.'</span><div class="accordion-panel" id="nesting-panel'.$id.'"></div>';
				}
				else{
					$data .='<span class="accordion-item class'.$id.'" onclick="getChilds('.$id.')" data-panel-id="nesting-panel'.$id.'">
					<button class="btn btn-success btn-sm" onclick="add(\'add\','.$id.')" ><i class="fa fa-plus"></i></button>&nbsp;'.
					str_pad($id,5,"0",STR_PAD_LEFT).'----'.$title.'</span><div class="accordion-panel" id="nesting-panel'.$id.'"></div>';
				}
				
			}
			else{########-CATEGORIES
				if($this->AdminModel->getRow('e_categories',array("parentid"=>$id)))
					$title = '<i class="fa fa-plus-circle"></i> '.$title;
				if($this->session->role!='admin')
				$data .='<span class="accordion-item class'.$id.'" onclick="getChilds('.$id.')" data-panel-id="nesting-panel'.$id.'">
				<button class="btn btn-success btn-sm" onclick="add(\'add\','.$id.')" ><i class="fa fa-plus"></i></button>&nbsp;'.
				str_pad($id,5,"0",STR_PAD_LEFT).'----'.$title.'</span><div class="accordion-panel" id="nesting-panel'.$id.'"></div>';
				else
				$data .='<span class="accordion-item class'.$id.'" onclick="getChilds('.$id.')" data-panel-id="nesting-panel'.$id.'">
				<button class="btn btn-danger btn-sm" onclick="deleteRecord(\'id\','.$id.',\'e_categories\')"><i class="fa fa-trash "></i>
				</button><button class="btn btn-primary btn-sm" onclick="add(\'edit\','.$id.')" ><i class="fa fa-pencil"></i></button>
				<button class="btn btn-success btn-sm" onclick="add(\'add\','.$id.')" ><i class="fa fa-plus"></i></button>&nbsp;'.
				str_pad($id,5,"0",STR_PAD_LEFT).'----'.$title.'</span><div class="accordion-panel" id="nesting-panel'.$id.'"></div>';
			}
		}
		
		echo $data;
	}
	
	public function getChildItems(){
		$xid = $this->input->post('parentid');
		$tblName = $this->input->post('tableName');
		$items = $this->AdminModel->getRows('e_items',array("item_category"=>$xid));
		$catz = $this->AdminModel->getRows('e_categories',array("parentid"=>$xid));
		if($catz)
		$data=$this->getChildItemX($xid);
		else
		$data ="";
		
		foreach($items as $itm){ 
			//extract($item);
			$data .='<span class="accordion-item class-x'.$xid.'" id="i'.$itm['id'].'">
			<button class="btn btn-danger btn-sm" onclick="deleteRecord(\'id\','.$itm['id'].',\'e_items\')"><i class="fa fa-trash "></i>
			</button> <button class="btn btn-primary btn-sm" onclick="add(\'edit\','.$itm['id'].')" ><i class="fa fa-pencil"></i></button>&nbsp;
			<a onclick="addEditItemStock('.$itm['id'].')">'.str_pad($itm['id'],5,"0",STR_PAD_LEFT).'----'.$itm['item_name'].'</a></span>';
		}
		
		echo $data;
	}
	
	public function getChildItemX($xid){
		$catz = $this->AdminModel->getRows('e_categories',array("parentid"=>$xid));
		$data ="";
		
		foreach($catz as $item){
			extract($item);
			$items = $this->AdminModel->getRows('e_items',array("item_category"=>$id));
			
			$skats = $this->AdminModel->getRow('e_categories',array("parentid"=>$id));

				if(($items && $skats)){
					$title = '<i class="fa fa-plus">B</i> '.$title;
					$data .='<span class="accordion-item class'.$id.'" onclick="getChildItems('.$id.')" data-panel-id="nesting-panel-x'.$id.'">
					<button class="btn btn-success btn-sm" onclick="add(\'add\','.$id.')" ><i class="fa fa-plus"></i></button>&nbsp;'.
					str_pad($id,5,"0",STR_PAD_LEFT).'----'.$title.'</span><div class="accordion-panel" id="nesting-panel-x'.$id.'"></div>';
				}
				else if($items && !$skats){
					$title = '<i class="fa fa-plus">I</i> '.$title;
					$data .='<span class="accordion-item class'.$id.'" onclick="getChildItems('.$id.')" data-panel-id="nesting-panel-x'.$id.'">
					<button class="btn btn-success btn-sm" onclick="add(\'add\','.$id.')" ><i class="fa fa-plus"></i></button>&nbsp;'.
					str_pad($id,5,"0",STR_PAD_LEFT).'----'.$title.'</span><div class="accordion-panel" id="nesting-panel-x'.$id.'"></div>';
				}
				else if(!$items && $skats){
					$title = '<i class="fa fa-plus-circle"></i> '.$title;
					$data .='<span class="accordion-item class'.$id.'" onclick="getChilds('.$id.')" data-panel-id="nesting-panel'.$id.'">
					<button class="btn btn-success btn-sm" onclick="add(\'add\','.$id.')" ><i class="fa fa-plus"></i></button>&nbsp;'.
					str_pad($id,5,"0",STR_PAD_LEFT).'----'.$title.'</span><div class="accordion-panel" id="nesting-panel'.$id.'"></div>';
				}
				else{
					$data .='<span class="accordion-item class'.$id.'" onclick="getChilds('.$id.')" data-panel-id="nesting-panel'.$id.'">
					<button class="btn btn-success btn-sm" onclick="add(\'add\','.$id.')" ><i class="fa fa-plus"></i></button>&nbsp;'.
					str_pad($id,5,"0",STR_PAD_LEFT).'----'.$title.'</span><div class="accordion-panel" id="nesting-panel'.$id.'"></div>';
				}
		}
		return $data;
	}
	
	public function getFormsView()
	{  
		extract($_POST);
		//viewType,action,tableName,key,val
		if($val==0){
	            $data2=array();
	            $dt= $this->db->list_fields($tableName);
	        
	            foreach($dt as $rec)
	 		        $data2[$rec]='';
		}
		else{
			if($tableName=='e_assemblies' && $action=='add' && $val>0){
				$data2=array();
	            $dt= $this->db->list_fields($tableName);
	        
	            foreach($dt as $rec)
	 		        $data2[$rec]='';
					
					$data2['projectid']=$val;
			}
		   else if($tableName=='e_items' && $action=='add' && $val>0){
				$data2=array();
	            $dt= $this->db->list_fields($tableName);
	        
	            foreach($dt as $rec)
	 		        $data2[$rec]='';
					
					$data2['item_category']=$val;
			}
			else if($tableName=='e_items_qty' && $val>0){
				if($action=='add'){
					$data2=array();
	            	$dt= $this->db->list_fields($tableName);
	        
	            	foreach($dt as $rec)
	 		        	$data2[$rec]='';
					
						$data2['item_id']=$val;
						$data2['grn_no']=$pono;
				}
				else if($action=='edit'){
					$data2 = $this->AdminModel->getRow($tableName,array('item_id' => $val,'grn_no'=>$pono));
				}
			}
			else if($tableName=='e_items_alloc' && $val>0){
				if($action=='add'){
					$data2=array();
	            	$dt= $this->db->list_fields($tableName);
	        
	            	foreach($dt as $rec)
	 		        	$data2[$rec]='';
					
						$data2['item_id']=$val;
						$data2['instrument']=$instrument;
						$data2['instrument_no']=$pono;
				}
				else if($action=='edit'){
					$data2 = $this->AdminModel->getRow($tableName,array('item_id' => $val,'instrument_no'=>$pono));
				}
			}
		   else{
			   $data2 = $this->AdminModel->getRow($tableName,array($key => $val));
			}
		}	
		
			$data = array(
						'viewType'	=> $viewType,
						'action'	=> $action,						
						'tableName' => $tableName,
						'key'		=> $key,
						'val'		=> $val,
						'data'		=> $data2
					);
		  
		   $this->load->view('called_views/admin_views',$data);
		

	}
	
	public function updateSelectedItem(){
		$tableName 	= $_POST['tableName'];
		$id 		= $this->input->post('id');
		$itemx 		= $this->AdminModel->getRow($tableName,array('id'=>$id));
		$itemx['ititle']=$this->AdminModel->getField('e_items',array('id'=>$itemx['item_id']),'item_name');
		echo json_encode($itemx);
	}
	
	public function fetchItemtoRestock(){
		$tableName 	= $_POST['tableName'];
		$id 		= $this->input->post('id');
		if($tableName=='e_matitem') $instm='mat'; else $instm='tmpissue';
		$itemx 		= $this->AdminModel->getRow($tableName,array('id'=>$id));
$itemx['ititle']=$this->AdminModel->getField('e_items',array('id'=>$itemx['item_id']),'item_name');
$itemx['assembly']=$this->AdminModel->getField('e_assemblies',array('id'=>$itemx['assembly_id'],'projectid'=>$itemx['project_id']),'title');
$itemx['sbassembly']=$this->AdminModel->getField('e_assemblies',array('id'=>$itemx['sub_assembly_id'],'projectid'=>$itemx['project_id']),'title');
		if($instm=='tmpissue'){
		$itemx['qty_issued']=$this->AdminModel->getField("e_items_alloc",array("instrument"=>$instm,"instrument_no"=>$itemx['adv_id'],"item_id"=>$itemx['item_id']),"item_quantity");
		
		$itemx['qty_limit']=$this->AdminModel->getSumRows("e_items_alloc","item_quantity",array("from_instrument"=>$instm,"from_instrument_no"=>$itemx['adv_id'],"item_id"=>$itemx['item_id']));
		}
		else{
		$itemx['qty_issued']=$this->AdminModel->getField("e_items_alloc",array("instrument"=>$instm,"instrument_no"=>$itemx['mat_id'],"item_id"=>$itemx['item_id']),"item_quantity");
		
		$itemx['qty_limit']=$this->AdminModel->getSumRows("e_items_alloc","item_quantity",array("from_instrument"=>$instm,"from_instrument_no"=>$itemx['mat_id'],"item_id"=>$itemx['item_id']));
		}
		
		if($itemx['qty_limit']>0)
		$itemx['qty_limit']=$itemx['qty_issued']-$itemx['qty_limit'];
		else
		$itemx['qty_limit']=$itemx['qty_issued'];
		
		echo json_encode($itemx);
	}
	
	
	public function fetchItemtoRelease(){
		$tableName 	= $_POST['tableName'];
		$id 		= $this->input->post('id');
		if($tableName=='e_advitems')
			$instm='adv';
		else
			$instm='mmov';
		$itemx 		= $this->AdminModel->getRow($tableName,array('id'=>$id));

		$itemx['ititle']=$this->AdminModel->getField('e_items',array('id'=>$itemx['item_id']),'item_name');
	if($instm=='mmov'){
		$itemx['assembly']='';
		$itemx['sbassembly']='';
	}
	else{
	$itemx['assembly']=$this->AdminModel->getField('e_assemblies',array('id'=>$itemx['assembly_id'],'projectid'=>$itemx['project_id']),'title');
	$itemx['sbassembly']=$this->AdminModel->getField('e_assemblies',array('id'=>$itemx['sub_assembly_id'],'projectid'=>$itemx['project_id']),'title');
	}
	
	$itemx['qty_issued']=$this->AdminModel->getField("e_items_alloc",array("instrument"=>$instm,"instrument_no"=>$itemx['adv_id'],"item_id"=>$itemx['item_id']),"item_quantity");
	
	//echo $this->db->last_query().$tableName; exit;
	
		echo json_encode($itemx);
	}
	
	public function submitCase(){
		$tableName 	= $_POST['tableName'];
		$id 		= $this->input->post('val');
		$field		= $this->input->post('field');
		$fieldval	= $this->input->post('fieldval');
		$data = array($field=>$fieldval);
		if($fieldval=='Approved'){
		$data['approved_by']	=$this->session->userid;
		$approvedBom 			=$this->AdminModel->getRow('e_bom',array('id'=>$id));
		$approvedBomItems		=$this->AdminModel->getRows('e_bomitem',array('bom_id'=>$id));
		
		$approvedBom['approved_status']='Draft';
		$approvedBom['generated_by']=$this->session->userid;
		$approvedBom['generated_on']=date("Y-m-d", time());
		$approvedBom['approved_by']=0;
		$approvedBom['bom_remarks']='';
		unset($approvedBom['id']);
		## copy approve bom to mar:
		/*$this->AdminModel->insertData('e_mat',$approvedBom);
		$mat_id=$this->db->insert_id();
			foreach($approvedBomItems as $b){
				$b['mat_id']=$mat_id;
				$b['item_notes']='';
				unset($b['id']);
				unset($b['bom_id']);
				$this->AdminModel->insertData('e_matitem',$b);		
			}	*/	
		}
		else
		$data['approved_by']=0;
		$this->AdminModel->updateRow($tableName,array('id'=>$id),$data);
	}
	
	public function submitBomAsMat(){
		$id 		= $this->input->post('val');

		$approvedBom 	=$this->AdminModel->getRow('e_bom',array('id'=>$id));
		$approvedBomItems		=$this->AdminModel->getRows('e_bomitem',array('bom_id'=>$id));
		
		$approvedBom['approved_status']='Draft';
		$approvedBom['generated_by']=$this->session->userid;
		$approvedBom['generated_on']=date("Y-m-d h:i:s", time());
		$approvedBom['approved_by']=0;
		$approvedBom['bom_remarks']='';
		unset($approvedBom['id']);
		$approvedBom['bomid']=$id;
			/*##validate if this bom not already in  mats
			$vBom = $approvedBom;
			unset($vBom['generated_by']);
			unset($vBom['generated_on']);
			$xtwo	=$this->AdminModel->getRow('e_mat',$vBom);
			//echo $this->db->last_query();exit;*/
		//if(!$xtwo){
		## copy approve bom to mar:
		$this->AdminModel->insertData('e_mat',$approvedBom);
		$mat_id=$this->db->insert_id();
			foreach($approvedBomItems as $b){
				$b['mat_id']=$mat_id;
				$b['item_notes']='';
				unset($b['id']);
				unset($b['bom_id']);
				$this->AdminModel->insertData('e_matitem',$b);		
			}	
		//}
	}
	
	public function crudSimple()
	{   $index 		= $_POST['keyIndex'];
		$tableName 	= $_POST['tableName'];
		$id 		= $this->input->post($index);
		$action 	= $_POST['action'];
		$data 		= $_POST;  
		
		
		if($tableName=='e_items') $imgpath = 'assets/img/itmimages/';
		else
		$imgpath='assets/img/';
		
		if (isset($_FILES) && !empty($_FILES['image']['name']) )
		{
			$photo =  $this->processImg($_FILES,'image',$imgpath);
			//$this->create_thumbnail($photo);
			 $data = $data + array('image'=>$photo);
		}		
		if (isset($_FILES) && !empty($_FILES['imagez']['name']) )
		{
			$photo =  $this->processMultipleIamges($_FILES,'imagez',$imgpath);
			//$this->create_thumbnail($photo);
			 $data = $data + array('imagez'=>$photo);

		}
		if (isset($_FILES) && !empty($_FILES['doc_list']['name']) )
		{
			$photo =  $this->processFile($_FILES,'doc_list','assets/lib/');
			//$this->create_thumbnail($photo);
			 $data = $data + array('doc_list'=>$photo);
		}
		if (isset($_POST['password']))
		{
			$password = $this->input->post('password');
			if(empty($password)) unset($data['password']);
			if(empty($password) || $password == 'fk0001') unset($data['password']);
			else $data['password'] = md5($password);
		}

		unset($data['tableName']);
		unset($data['keyIndex']);
		unset($data[$index]);
		unset($data['action']);
		
		if(isset($data['assembly_id'])&& ($data['assembly_id']!='' && $data['sub_assembly_id']!='')){
				$this->session->assembly_id=$data['assembly_id'];
				$this->session->sub_assembly_id=$data['sub_assembly_id'];
		}
		else
		$this->session->assembly_id=$this->session->sub_assembly_id=0;

	//	echo "<pre>";
	//	print_r($data);exit;ge
		$tabls = array('e_bomitem','e_matitem','e_advitems','e_tempissueitems','e_poitems','e_dcitems','e_mmitems','e_restockitems','e_releaseitems');
		if (in_array($tableName, $tabls)&& $action=='add'){
			extract($data);
			if($tableName=='e_bomitem')
			$allReadyexistingItem = $this->AdminModel->getRow($tableName,array('bom_id'=>$bom_id,'project_id'=>$project_id,'assembly_id'=>$assembly_id,'sub_assembly_id'=>$sub_assembly_id,'item_id'=>$item_id));
			elseif($tableName=='e_matitem')
			$allReadyexistingItem = $this->AdminModel->getRow($tableName,array('mat_id'=>$mat_id,'project_id'=>$project_id,'assembly_id'=>$assembly_id,'sub_assembly_id'=>$sub_assembly_id,'item_id'=>$item_id));
			elseif($tableName=='e_advitems'|| $tableName=='e_tempissueitems')
			$allReadyexistingItem = $this->AdminModel->getRow($tableName,array('adv_id'=>$adv_id,'project_id'=>$project_id,'assembly_id'=>$assembly_id,'sub_assembly_id'=>$sub_assembly_id,'item_id'=>$item_id));
			elseif($tableName=='e_poitems')
			$allReadyexistingItem = $this->AdminModel->getRow($tableName,array('po_id'=>$po_id,'item_id'=>$item_id));
			elseif($tableName=='e_dcitems' || $tableName=='e_mmitems' || $tableName=='e_restockitems' || $tableName=='e_releaseitems')
			$allReadyexistingItem = $this->AdminModel->getRow($tableName,array('adv_id'=>$adv_id,'item_id'=>$item_id));
			
			//print_r($allReadyexistingItem);
			//echo $this->db->last_query(); exit;
			if($allReadyexistingItem){
				$action='edit';
				if($tableName=='e_restockitems' || $tableName=='e_releaseitems'){
					
				}
				else
				$data['qty_needed']+=$allReadyexistingItem['qty_needed'];
				$id = $allReadyexistingItem['id'];
			}
			
			
			
			
			
		}
		
		if($tableName=='e_items_alloc'){
			extract($data);
			
			if($instrument=='restock'){
				$alpha = $this->AdminModel->getRow('e_restock',array('id'=>$instrument_no));
				if($alpha['instrument']=='MATS')
				$data['from_instrument']='mat';
				else if($alpha['instrument']=='MISNO')
				$data['from_instrument']='misno';
				else
				$data['from_instrument']='tmpissue';
				$data['from_instrument_no']=$alpha['instrument_no'];
				  
			}
			
			if($instrument=='release'){
				$alpha = $this->AdminModel->getRow('e_release',array('id'=>$instrument_no));
				if($alpha['instrument']=='ADV'){
				$data['from_instrument']='adv';
					$beta = $this->AdminModel->getRow('e_releaseitems',array('adv_id'=>$alpha['id'],'item_id'=>$item_id));
				  $iq=$beta['qty_needed']-$item_quantity;
				  $this->db->query("update e_items_alloc set item_quantity=".$iq." where instrument='".$data['from_instrument']."' and instrument_no='".$instrument_no."' and item_id=".$beta['item_id']);
				}
				else
				$data['from_instrument']='mmov';
				$data['from_instrument_no']=$alpha['instrument_no'];
			}
			
			$allReadyexistingItem = $this->AdminModel->getRow($tableName,array('instrument'=>$instrument,'instrument_no'=>$instrument_no,'item_id'=>$item_id));
			
			if($instrument=='mmov'){
				$a = $this->AdminModel->getRow('e_mm',array('id'=>$instrument_no));
				if($a['askfrom']>0){
				$b = $this->AdminModel->getRow('e_mmitems',array('userid'=>$a['askfrom'],'item_id'=>$item_id));
				$c = $this->AdminModel->getRow($tableName,array('instrument'=>$instrument,'instrument_no'=>$b['adv_id'],'item_id'=>$item_id));
					if($c['item_quantity']>0){
						$q=$c['item_quantity']-$data['item_quantity'];
						$this->AdminModel->updateRow($tableName,array('instrument'=>$instrument,'id'=>$c['id'],'item_id'=>$item_id),array('item_quantity'=>$q));
					}
				}
			}
			if($data['from_instrument']=='tmpissue'){
				$a = $this->AdminModel->getRow('e_restock',array('id'=>$instrument_no));
				if($a['instrument_no']>0){
				$c = $this->AdminModel->getRow($tableName,array('instrument'=>$data['from_instrument'],'instrument_no'=>$a['instrument_no'],'item_id'=>$item_id));
					if($c['item_quantity']>0){
						$q=$c['item_quantity']-$data['item_quantity'];
						$this->AdminModel->updateRow($tableName,array('id'=>$c['id'],'item_id'=>$item_id),array('item_quantity'=>$q));
					}
				}
			}
			if($data['from_instrument']=='mat'){
				$a = $this->AdminModel->getRow('e_restock',array('id'=>$instrument_no));
				if($a['instrument_no']>0){
				$c = $this->AdminModel->getRow($tableName,array('instrument'=>$data['from_instrument'],'instrument_no'=>$a['instrument_no'],'item_id'=>$item_id));
					if($c['item_quantity']>0){
						$q=$c['item_quantity']-$data['item_quantity'];
						$this->AdminModel->updateRow($tableName,array('id'=>$c['id'],'item_id'=>$item_id),array('item_quantity'=>$q));
					}
				}
			}
			//print_r($allReadyexistingItem);
			//echo $action.$this->db->last_query(); exit;
			if($allReadyexistingItem){
				$action='edit';
				$id=$allReadyexistingItem['id'];
				
			}
		}
		
		if($tableName=='e_restock'){
			extract($data);
			
			if($instrument=='MATS'){
			$allReadyexistingItem = $this->AdminModel->getRow('e_mat',array('id'=>$instrument_no));
			}
			else{
			$allReadyexistingItem = $this->AdminModel->getRow('e_tempissue',array('id'=>$instrument_no));
			}
			if($allReadyexistingItem){
				$data['department_id']=$allReadyexistingItem['department_id'];
				
				
				$from_instrument_no=$instrument_no;
			}
			else
			$data['department_id']=$this->session->departmentid;
		}
		if($tableName=='e_admin'){
			$duties_arr = $this->input->post('privileges');
			$data['privileges'] = implode(',', $duties_arr);
		}
		
		if($tableName=='e_items_qty'){
			$sno = $data['serial_no'];
			unset($data['serial_no']);
			if(trim($sno)!=''){
				$idtl = $this->AdminModel->getRow('e_items',array('id'=>$data['item_id']));
				//print_r($idtl); 
				$id = $idtl['id'];
				unset($idtl['id']);
				$idtl['item_serial_no'].=','.$sno;
				if(trim($sno) && $id){
					$this->AdminModel->updateRow('e_items',array('id'=>$id),$idtl);
				}
			}
		}
		
		if($tableName=='e_release'){
			extract($data);
			if($instrument=='ADV'){
				$allReadyexistingItem = $this->AdminModel->getRow('e_adv',array('id'=>$instrument_no));
				if($allReadyexistingItem){
					$data['department_id']=$allReadyexistingItem['department_id'];
				}
			}
			else
				$data['department_id']=$this->session->departmentid;
		}
		
		if($tableName=='e_items'){
			
			if($data['newparentid']>0){
				$data['item_category']=$data['newparentid'];
			}
			unset($data['newparentid']);
		}
		
		if(isset($_POST['passwordxx'])){//$tableName=='e_tempissue' && 
			    $password= substr(md5($this->input->post('passwordxx')),0,15);
				$xx=$this->AdminModel->getRow('e_admin',array('userid'=>$this->session->userid,'password'=>$password));
				if($xx){
				$data['user_receivedon']=date("Y-m-d H:i:s",time());
				unset($data['passwordxx']);unset($data['passwordxx']);
				}
				else{
				$data['user_receivedon']='';	
				
				}
		}
		if ($action=='add' && $this->AdminModel->insertData($tableName,$data))
		{  
			$insertid= $this->db->insert_id();
			
			##log object##
			$logs['action']='Add'; $logs['tbl_nmae']=$tableName; $logs['instrument']=$tableName; $logs['record_id']=$insertid;
			$logs['qry_statment']=$this->db->last_query(); $logs['userid']=$this->session->userid;
			@$this->AdminModel->insertData('e_logs',$logs);
			####_###
			
			if($tableName=='e_items') $mis=' Item created with MIS# :'.$insertid; else $mis='';
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success. ".$mis
				));
			echo 'successfully';
		}
		elseif ($action=='edit' && $this->AdminModel->updateRow($tableName,array($index=>$id),$data))
		{  
			##log object##
			$logs['action']='Update'; $logs['tbl_nmae']=$tableName; $logs['instrument']=$tableName; $logs['record_id']=$id;
			$logs['qry_statment']=$this->db->last_query(); $logs['userid']=$this->session->userid;
			@$this->AdminModel->insertData('e_logs',$logs);
			####_###
			
			if($tableName=='e_items') $mis=' Item updated - MIS# :'.$id; else $mis='';
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success. ".$mis
				));
			echo 'successfully';
		}
		else  
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed"
				));
	}
	
		
	public function exportExcel(){
		$excel_data = $this->input->post('excel_data');
		echo header('Content-Type: application/vnd.ms-excel');  
 		echo header('Content-disposition: attachment; filename='.rand().'.xls');  
 		echo $excel_data;
	}
	

	/////////////////////--------------////////////////////
	public function getView()
	{
		extract($_POST);
		switch ($viewType) 
		{
			case 'addAmenities':
				$data2 = $this->AdminModel->getRow('e_amenities',array('userid'=>$id));
				if ($data2)
				{
					$data = array(
						'viewType'	=> $viewType,
						'data'		=> $data2
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;
			case 'editCmsUser':
				$data2 = $this->AdminModel->getRow('e_admin',array('userid'=>$id));
				if ($data2)
				{
					$data = array(
						'viewType'	=> $viewType,
						'data'		=> $data2
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;
			case 'editUserRole':
				$data2 = $this->AdminModel->getRow('e_roles',array('id'=>$id));
				if ($data2)
				{
					$data = array(
						'viewType'	=> $viewType,
						'data'		=> $data2
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;
			case 'editContractItem':
				$data2 = $this->AdminModel->getRow('e_contract_items',array('id'=>$id));
				if ($data2)
				{
					$data = array(
						'viewType'	=> $viewType,
						'data'		=> $data2
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;
			case 'editEmployee':
				$data2 = $this->AdminModel->getRow('e_employees',array('id'=>$id));
				if ($data2)
				{
					$data = array(
						'viewType'	=> $viewType,
						'data'		=> $data2
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;
			case 'addEmployee':
					$data = array(
						'viewType'	=> $viewType
					);
					$this->load->view('called_views/admin_views',$data);
				break;	
			case 'editBuilders':
				$data2 = $this->AdminModel->getRow('e_builders',array('id'=>$id));
				if ($data2)
				{
					$data = array(
						'viewType'	=> $viewType,
						'data'		=> $data2
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;
			case 'editSchedules':
				$data2 = $this->AdminModel->getRow('e_schedules',array('id'=>$id));
				if ($data2)
				{
					$data = array(
						'viewType'	=> $viewType,
						'data'		=> $data2
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;	
			case 'editProject':
				$data2 = $this->AdminModel->getRow('e_projects',array('id'=>$id));
				$citems = '';//$this->AdminModel->getRows('e_project_citems',array('project_id'=>$id));
				$orignal_citems = $this->AdminModel->getRows('e_contract_items',array());
				if ($data2)
				{
					$data = array(
						'viewType'	=> $viewType,
						'citems'	=> $citems,
						'ocitems'	=> $orignal_citems,
						'data'		=> $data2
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;
			case 'editEquipment':
				$data2 = $this->AdminModel->getRow('e_equipments',array('id'=>$id));
				if ($data2)
				{
					$data = array(
						'viewType'	=> $viewType,
						'data'		=> $data2
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;	
			case 'editInventory':
				$data2 = $this->AdminModel->getRow('e_inventory',array('id'=>$id));
				if ($data2)
				{
					$data = array(
						'viewType'	=> $viewType,
						'data'		=> $data2
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;		
			case 'createDaily':
				
					$data = array(
						'viewType'	=> $viewType,
						'builderid'	=> $builderid,
						'projid'	=> $projid
					);
					$this->load->view('called_views/admin_views',$data);
				break;		
			case 'scheduleDaily':
				$data2 = $this->AdminModel->getRow('e_schedules',array('id'=>$sid));
				if ($data2)
				{   $data = array(
						'viewType'	=> $viewType,
						'builderid'	=> $builderid,
						'projid'	=> $projid,
						'data'		=> $data2
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;			
			case 'updateDaily':
				$data2 = $this->AdminModel->getRow('e_wsdaily',array('id'=>$id));
				if ($data2)
				{   $data = array(
						'viewType'	=> $viewType,
						'action'	=> $action,
						'data'		=> $data2
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;
			case 'createContract':
					$data = array(
						'viewType'	=> $viewType,
						'builderid'	=> $builderid,
						'projid'	=> $projid,
						'lot_no'	=> $lotno,
						'citno'	=> $citno
					);
					$this->load->view('called_views/admin_views',$data);
				break;	
			case 'updateContract':
				$data2 = $this->AdminModel->getRow('e_wsdaily',array('id'=>$id));
				if ($data2)
				{   $data = array(
						'viewType'	=> $viewType,
						'action'	=> $action,
						'data'		=> $data2
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;
			case 'addHours':
				$wsdata = $this->AdminModel->getRow('e_wsdaily',array('id'=>$wsid));
				$edata = $this->AdminModel->getRow('e_employees',array('id'=>$empid));
				if ($wsdata)
				{   $data = array(
						'viewType'	=> $viewType,
						'edata'	 => $edata,
						'wsdata' => $wsdata
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;	
			case 'updateHours':
				$wsdata = $this->AdminModel->getRow('e_wsdaily',array('id'=>$wsid));
				$hdata 	= $this->AdminModel->getRow('e_wshours',array('id'=>$empid));
				if ($wsdata)
				{   $data = array(
						'viewType'	=> $viewType,
						'hdata'	 => $hdata,
						'wsdata' => $wsdata
					);
					$this->load->view('called_views/admin_views',$data);
				}
				else
				echo "No record found in databae";
				break;	
			default:
				echo "No view defined";
				break;
		}
	}
	###############generic for getView para by post : tableName,key,value and viewType

	public function getView2()
	{
		extract($_POST);
		$data2 = $this->AdminModel->getRow($tableName,array($key => $id));
		
		if ($data2)
		{
			$data = array(
						'viewType'	=> $viewType,
						'data'		=> $data2,
						'tableName' => $tableName,
						'key'		=> $key,
						'id'		=> $id
					);
			$this->load->view('called_views/admin_views',$data);
		}

	}
	
	protected function reload()
	{
		echo "<script>location.reload()</script>";
	}
	
	
	
	public function saveSimple()
	{   $data = $_POST;  $data2=array();
		if (isset($_FILES) && !empty($_FILES['image']['name']) )
		{
			$photo =  $this->processImg($_FILES,'image','assets/img/');
			//$this->create_thumbnail($photo);
			 $data = $data + array('image'=>$photo);
		}			
		if (isset($_FILES) && !empty($_FILES['doc_list']['name']) )
		{
			$photo =  $this->processFile($_FILES,'doc_list','assets/lib/');
			//$this->create_thumbnail($photo);
			 $data = $data + array('doc_list'=>$photo);
		}
		if (isset($_POST['password']))
		{
			$password = $this->input->post('password');
			if(empty($password)) unset($data['password']);
			else $data['password'] = md5($password);
		}
		
		$tableName = $data['tableName'];
		if($tableName=='e_schedules')
		$data['schedule_date']	= date("Y-m-d", strtotime(str_replace("-","/",$data['schedule_date'])));
		if($tableName=='e_inventory')
		$data['purchase_date']	= date("Y-m-d", strtotime(str_replace("-","/",$data['purchase_date'])));
		
		if($tableName=='e_equipments'){
		$data['issue_date']	= date("Y-m-d", strtotime(str_replace("-","/",$data['issue_date'])));
		$data['acquisition_date']	= date("Y-m-d", strtotime(str_replace("-","/",$data['acquisition_date'])));
		$data['date_of_return']	= date("Y-m-d", strtotime(str_replace("-","/",$data['date_of_return'])));
		}
		if($tableName=='e_projects'){
			$cirates = $data['rate'];
			$citemid = $data['contract_item_id'];
			unset($data['rate']);
			unset($data['contract_item_id']);
		}
		
		unset($data['tableName']);
		//echo "<pre>";
		//print_r($data); exit;
		//insertData
		if ($this->AdminModel->insertData($tableName,$data))
		{  
			$insertid= $this->db->insert_id();
			if($tableName=='e_projects' && $insertid>0){
				for($i=0; $i< sizeOf($citemid); $i++){
					$data2['rate']= round($cirates[$i],2);
					$data2['cid'] = $citemid[$i];
					$data2['project_id'] = $insertid;
					$this->AdminModel->insertData('e_project_citems',$data2);
					$data2 = array();
				}

		
			}
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
			echo 'successfully';
		}
		else  
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed"
				));
	}
	
	public function updateSimple()
	{
		$index 		= $_POST['keyIndex'];
		$tableName 	= $_POST['tableName'];
		$id = $this->input->post($index);
		$data = $_POST;  $data2=array();
		
		if($tableName=='e_schedules')
		$data['schedule_date']	= date("Y-m-d", strtotime(str_replace("-","/",$data['schedule_date'])));
		if($tableName=='e_inventory')
		$data['purchase_date']	= date("Y-m-d", strtotime(str_replace("-","/",$data['purchase_date'])));
		
		if($tableName=='e_equipments'){
		$data['issue_date']	= date("Y-m-d", strtotime(str_replace("-","/",$data['issue_date'])));
		$data['acquisition_date']	= date("Y-m-d", strtotime(str_replace("-","/",$data['acquisition_date'])));
		$data['date_of_return']	= date("Y-m-d", strtotime(str_replace("-","/",$data['date_of_return'])));
		}
		if($tableName=='e_projects'){
			$cirates = $data['rate'];
			$pcid 	= $data['pcid'];
			$citemid = $data['contract_item_id'];
			unset($data['rate']);
			unset($data['pcid']);
			unset($data['contract_item_id']);
		}
		unset($data['keyIndex']);
		unset($data['tableName']);
		
		if($tableName=='e_wsdaily'){//if(isset($_POST['sheet_type']) && $_POST['sheet_type']=='contract'){
			$data['work_date'] = date("Y-m-d", strtotime(str_replace("-","/",$data['work_date'])));
			$data['estimate_date'] = date("Y-m-d", strtotime(str_replace("-","/",$data['estimate_date'])));
			$data['paid_po_date'] = date("Y-m-d", strtotime(str_replace("-","/",$data['paid_po_date'])));
		}		
		
		 if (isset($_FILES) && !empty($_FILES['image']['name']) )//&& ($_FILES['image']['error'][0] == 0)
		{
			$photo =  $this->processImg($_FILES,'image','assets/img/');
			//$this->create_thumbnail($photo);
			 $data = $data + array('image'=>$photo);
		}			
		
		if (isset($_FILES) && !empty($_FILES['doc_list']['name']) )
		{
			$photo =  $this->processFile($_FILES,'doc_list','assets/lib/');
			//$this->create_thumbnail($photo);
			 $data = $data + array('doc_list'=>$photo);
		}
		
		  
		if (isset($_POST['password']))
		{
			$password = $this->input->post('password');
			if(empty($password) || $password == 'fk0001') unset($data['password']);
			else $data['password'] = md5($password);
		}
		
		if(@$data['doc_list']=='')
				unset($data['doc_list']);
		if(@$data['image']=='')
				unset($data['image']);
		// echo "<pre>-";
		//print_r($data); exit;
		 
		
		if ($this->AdminModel->updateRow($tableName,array($index=>$id),$data))
		{
			if($tableName=='e_projects'){
				for($i=0; $i< sizeOf($citemid); $i++){
					$data2['rate']= round($cirates[$i],2);
					$data2['cid'] = $citemid[$i];
					$data2['project_id'] = $id;
					if($pcid[$i]==0)
					$this->AdminModel->insertData('e_project_citems',$data2);
					else
					$this->AdminModel->updateRow('e_project_citems',array('id'=>$pcid[$i]),$data2);
					
					$data2 = array();
				}
			}
			
			if($tableName=='e_equipments'){ //&& $data['status']=='Returned'
				$equipId = $id; $equipTotalRent = $data['sale_price'];
				#--get e_wcost total
				$totSheets = $this->AdminModel->countRows('e_wscost',$cond = array('eqpt_id'=>$equipId));
				$newPrice = $equipTotalRent/$totSheets;
				$res = $this->AdminModel->getRows('e_wscost',$cond = array('eqpt_id'=>$equipId));
				foreach($res as $r){
					$data2['item_cost']= $newPrice; 
					$data2['net_cost']= ($newPrice*$r['item_qty']); 
					$this->AdminModel->updateRow('e_wscost',array('cid'=>$r['cid']),$data2);
				}
				$data2 = array();
				foreach($res as $r){
					$sheetId = $r['wsid'];
					$data2['eqpt_material_cost'] = getSumRows('e_wscost','net_cost',$cond= array('wsid'=>$sheetId));
					$this->AdminModel->updateRow('e_wsdaily',array('id'=>$sheetId),$data2);
				}
			}
			
			
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
		}
		else
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed(@query updateSimple)"
				));

	}

	
	public function setTableFieldValue(){
		$field_name	=$_POST['tbl_field'];
		$index		=$_POST['row_id'];
		$data[$field_name]='';
		if ($this->AdminModel->updateRow('e_employees',array('id'=>$index),$data))
		echo $this->db->last_query();
	}

	###############update setting#########
	public function updateSetting()
	{
		$id = $this->input->post('id');
		$data = $_POST;
				
		if (isset($_FILES) && !empty($_FILES['image']['name']) )
		{
			$photo =  $this->processImg($_FILES,'image','assets/img/');
			//$this->create_thumbnail($photo);
			 $data = $data + array('image'=>$photo);
		}	
		if (isset($_FILES) && !empty($_FILES['icon']['name']) )
		{
			$photoz =  $this->processImg($_FILES,'icon','assets/img/');
			//$this->create_thumbnail($photo);
			 $data = $data + array('icon'=>$photoz);
		}		
		//echo "<pre>";
		//echo print_r($data); 
		
		if ($this->AdminModel->updateRow('e_settings',array('id'=>$id),$data))
		{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
		}
		else
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed(@query updateSetting)"
				));
	}
	############cms_user_view
	public function saveCmsUser()
	{
		if (isset($_FILES) && !empty($_FILES['image']['name']))
		{
			$photo =  $this->processImg($_FILES,'image','assets/img/');
		}
		else
			$photo = "";

		$duties_arr = $this->input->post('duties[]');
		$duties = implode(',', $duties_arr);
		
		$data = array(
			'name'		 => $this->input->post('name'),
			'username'	 => $this->input->post('username'),
			'password' 	 => md5($this->input->post('password')),
			'email'		 => $this->input->post('email'),
			'role'		 => $this->input->post('role'),
			'status'	 => $this->input->post('status'),
			'duties'	 => $duties,
			'image'	 => $photo
		);
		if ($this->AdminModel->insertData('e_admin',$data))
		{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
		}
		else
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed"
				));

	}

	public function updateCmsUser()
	{
		$userid = $this->input->post('userid');
		$duties_arr = $this->input->post('duties[]');
		$duties = implode(',', $duties_arr);
		
		$data = array(
				'name'		 => $this->input->post('name'),
				'username'	 => $this->input->post('username'),
				'email'		 => $this->input->post('email'),
				'role'		 => $this->input->post('role'),
				'status'	 => $this->input->post('status'),
				'duties'	 => $duties
			);
		
		if (isset($_FILES) && !empty($_FILES['image']['name']))
		{
			$this->deleteImage('e_admin','userid',$userid,'image','assets/img/');
			$photo =  $this->processImg($_FILES,'image','assets/img/');
			$data = $data + array('image'=>$photo);
		}

		$password = $this->input->post('password');
		if($password != 'fk0001' && !empty($password))
		{
			$data = $data + array('password'=>md5($password));
		}
		
		if ($this->AdminModel->updateRow('e_admin',array('userid'=>$userid),$data))
		{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
		}
		else
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed(@query updateCmsUser)"
				));

	}
	
	public function updateCmsUserPassword()
	{
		$userid = $this->input->post('userid');
		
		$data = $_POST;

		if (isset($_FILES) && !empty($_FILES['files']['name']))
		{
			$this->deleteImage('e_admin','userid',$userid,'image','assets/img/');
			$photo =  $this->processImg($_FILES);
			$this->create_thumbnail($photo);
			$data = $data + array('image'=>$photo);
		}

		$password = $data['password'];
		if(!empty($password))
		{
			$data['password']=md5($password);
		}
		else unset($data['password']);
		//echo '<pre>';
		//print_r($data); exit;
		unset($data['tableName']);
		if ($this->AdminModel->updateRow('e_admin',array('userid'=>$userid),$data))
		{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
		}
		else
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed(@query updateCmsUser)"
				));

	}
	#___________________________________________
	
	public function saveSheetFile()
	{

		if (isset($_FILES) && !empty($_FILES['files']['name']))
		{
			 $file =  $this->processFile($_FILES,'files','phpspreadsheet/','xlsx|xls');
		}
		else
			$file = 0;
		if ($file) {
			echo  $file;
		}
		else
			echo '0';
		
	}
	
	##########
	public function saveNameSlug()
	{
		$data = $_POST;
		if (isset($_FILES) && !empty($_FILES['files']['name']))
		{
			$photo =  $this->processImg($_FILES);
			$this->create_thumbnail($photo);
			$data = $data + array('image' => $photo);
		}
		
		
		$tableName = $data['tableName'];
		unset($data['tableName']);
		if (isset($_POST['password']))
		{
			$password = $this->input->post('password');
			if(empty($password)) unset($data['password']);
			else $data['password'] = md5($password);
		}
		
		$data['slug'] = $this->clean($this->input->post($this->input->post('name_field')));


		//   echo "<pre>";
		//  print_r($data);
		// echo $tableName;

		if ($this->AdminModel->insertData($tableName,$data))
		{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
		}
		else
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed"
				));
	}
	public function updateNameSlug()
	{
		$index 		= $_POST['keyIndex'];
		$tableName 	= $_POST['tableName'];
		$id = $this->input->post($index);
		$data = $_POST;
		unset($data['keyIndex']);
		unset($data['tableName']);

		if (isset($_FILES) && !empty($_FILES['files']['name']))
		{
			$this->deleteImage($tableName,$index,$id,'image','assets/cms_images/');
			$photo =  $this->processImg($_FILES);
			$this->create_thumbnail($photo);
			$data = $data + array('image'=>$photo);
		}
		if (isset($_POST['password']))
		{
			$password = $this->input->post('password');
			if(empty($password)) unset($data['password']);
			else $data['password'] = md5($password);
		}
		$data['slug'] = $this->clean($this->input->post($this->input->post('name_field')));
		// $data['slug'] = $this->clean($this->input->post('name'));


		// echo "<pre>";
		// print_r($data);
		
		if ($this->AdminModel->updateRow($tableName,array($index=>$id),$data))
		{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
		}
		else
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed(@query updateSimple)"
				));

	}
	public function saveCity()
	{
		$data = $_POST;
		$data['slug'] = $this->clean($this->input->post('city_name'));
		// echo $slug = $data['slug'];
		$already = $this->AdminModel->multiCondition('e_cities',array('slug'=>$data['slug']));
		if($already) {
			echo "District Already Exists";
			exit();
		}

		 //  echo "<pre>";
		 // print_r($data);
		// echo $tableName;

		if ($this->AdminModel->insertData('e_cities',$data))
		{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
			echo "District Added";
			$this->reload();
		}
		else{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed"
				));
			echo "failed";
		}

	}
	
	public function updateCity()
	{
		$index 		= $_POST['keyIndex'];
		$tableName 	= $_POST['tableName'];
		$id = $this->input->post($index);
		$data = $_POST;
		unset($data['keyIndex']);
		unset($data['tableName']);
		
		$data['slug'] = $this->clean($this->input->post('city_name'));
		// echo $slug = $data['slug'];
		// $already = $this->AdminModel->multiCondition('e_cities',array('slug'=>$data['slug']));
		// if($already) {
		// 	echo "City Already Exists";
		// 	exit();
		// }

		 //  echo "<pre>";
		 // print_r($data);
		// echo $tableName;

		if ($this->AdminModel->updateRow($tableName,array($index=>$id),$data))
		{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
			echo "City Added";
		}
		else{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed"
				));
			echo "failed";
		}

	}
	public function saveFile()
	{
		$data = $_POST;
		if (isset($_FILES) && !empty($_FILES['files']['name']))
		{
			$photo =  $this->processFile($_FILES);
			
			$data = $data + array('file' => $photo);
		}
		
		
		$tableName = $data['tableName'];
		unset($data['tableName']);
		
		//   echo "<pre>";
		//  print_r($data);
		// echo $tableName;

		if ($this->AdminModel->insertData($tableName,$data))
		{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
			echo 'successfully';
		}
		else
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed"
				));
	}
	
	##########################change product  dispaly
	private function show_alert($string = 'Action Failed',$alert_type = 'info'){
		echo "<div class='alert alert-".$alert_type."'>".$string."</div>";
	}

	
	#########################get Option add product
	//add product page for getting sub cat while selecting cat
	public function getCitiesOption()
	{
		$data = array('province_id'=>$this->input->post('province_id'));
		return getOption('e_cities','city_id','city_name','',$data);

	}
	#####################clean slug
	 function clean($string) {
		   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

		   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		}
	function clean2($string) {
		   $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.

		   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		}
	###################### saving menu
	public function saveMenu()
	{		
		$data = $_POST;
		if (isset($_FILES) && !empty($_FILES['files']['name']))
		{
			$photo =  $this->processImg($_FILES);
			$this->create_thumbnail($photo);
		}
		else
			$photo = "";

		if (isset($_FILES) && $_FILES['files2']['error'][0] == 0)
		{	
			$photo2 =  $this->processMultipleIamges($_FILES,'files2');
			$data = $data + array('image2'=>$photo2);	
		}

	
		$parent_id = $this->input->post('parent_id');
		if (!$parent_id) {
			$level = 0;
		}
		elseif (is_numeric($parent_id) && $parent_id)
		{
			$level = 1;
		}
		else
		{
			$level = 2;
			$parent_id = substr($parent_id, 4);
			$data['parent_id'] = $parent_id;
		} 
		

		//echo $parent_id;
		// if ($parent_id != '0') $level = 1;
		// else $level = 0;
		$slug = $this->input->post('slug');
		if(empty($slug)) $slug = time().rand(1,666);
		$data['slug'] = $this->clean($slug);

		$duties_arr = $this->input->post('position[]');
		if (!empty($duties_arr)) 
			$position = implode(',', $duties_arr);
		else
			$position = "";
		
		
		
		unset($data['position']);
		$data = $data + array('image' => $photo,
								'position' => $position,
								'level'=>$level);
		//  echo "<pre>";
		 // print_r($data);

		if ($this->AdminModel->insertData('e_menu',$data))
		{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
		}
		else
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed"
				));
	}

	public function updateMenu()
	{
		$menuid = $this->input->post('menuid');
		
		$parent_id = $this->input->post('parent_id');
		if ($parent_id != '0') $level = 1;
		else $level = 0;

		$duties_arr = $this->input->post('position[]');
		if (!empty($duties_arr)) 
			$position = implode(',', $duties_arr);
		else
			$position = "";
		
		
		$data = $_POST;
		$parent_id = $this->input->post('parent_id');
		if (!$parent_id) {
			$level = 0;
		}
		elseif (is_numeric($parent_id) && $parent_id)
		{
			$level = 1;
		}
		else
		{
			$level = 2;
			$parent_id = substr($parent_id, 4);
			$data['parent_id'] = $parent_id;
		} 
		$slug = $this->input->post('slug');
		if(empty($slug)) $slug = time().rand(1,666);
		$data['slug'] = $this->clean($slug);
		
		unset($data['position']);
		$data = $data + array('position' => $position,'level'=>$level);

		if (isset($_FILES) && !empty($_FILES['files']['name']))
		{
			$this->deleteImage('e_menu','menuid',$menuid,'image','assets/cms_images/');
			$photo =  $this->processImg($_FILES);
			$this->create_thumbnail($photo);
			$data = $data + array('image'=>$photo);
		}
		if (isset($_FILES) && $_FILES['files2']['error'][0] == 0)
		{	
			$this->deleteMultipleImage('e_menu','menuid',$menuid,'image2','assets/cms_images/');
			$photo =  $this->processMultipleIamges($_FILES,'files2');
			$data = $data + array('image2'=>$photo);
		}

		// echo "<pre>";
		// print_r($data);
		
		if ($this->AdminModel->updateRow('e_menu',array('menuid'=>$menuid),$data))
		{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success"
				));
		}
		else
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Action Failed(@query updateCmsUser)"
				));

	}
	//////////////////////saving contact us site page
	public function saveContactUs()
	{

		$data = $_POST;
		if (strcasecmp($_SESSION['captchaWord'], $data['captcha']) == 0) 
		{
			unset($_SESSION['captchaWord']);

			$tableName = $data['tableName'];
			unset($data['tableName']);
			$data = $data + array('image' => $photo);
			//   echo "<pre>";
			//  print_r($data);
			// echo $tableName;

			if ($this->AdminModel->insertData($tableName,$data))
			{
				$this->session->set_flashdata('alert_data', array(
					'type' => 'success', 
					'details' => "Action Success"
					));
			}
			else
				$this->session->set_flashdata('alert_data', array(
					'type' => 'danger', 
					'details' => "Action Failed"
					));
			echo "<div>Your Message sent successfully<br>Redircting...</div>";
		}
		else
			echo "<div>Your Message not sent! Invalid Captcha Value<br>Redircting...</div>";

	}
	//////////////// GENERIC Function for delete row//////////////
	public function deleteImage($tableName,$key,$value,$fieldName,$filePath)
	{
		 $filename = $this->AdminModel->getField($tableName,array($key=>$value),$fieldName);
		if (!empty($filename) && $filename != '0') 
		{
			$image = $_SERVER['DOCUMENT_ROOT'].'/'.CMS_PATH.$filePath.$filename;
			$image_thumbnail = $_SERVER['DOCUMENT_ROOT'].'/'.CMS_PATH.$filePath.'thumbnail/'.$filename;
			if (file_exists($image)) unlink($image);
			if (file_exists($image_thumbnail)) unlink($image_thumbnail);
		}
	}
	public function deleteMultipleImage($tableName,$key,$value,$fieldName,$filePath)
	{
		 $filename = $this->AdminModel->getField($tableName,array($key=>$value),$fieldName);
		if (!empty($filename) && $filename != '0') 
		{
			$images = explode(';', $filename);
			foreach ($images as $file)
			{
				$image = $_SERVER['DOCUMENT_ROOT'].'/'.CMS_PATH.$filePath.$file;
				//$image_thumbnail = $_SERVER['DOCUMENT_ROOT'].'/'.CMS_PATH.$filePath.'thumbnail/'.$file;
				if (file_exists($image)) unlink($image);
				//if (file_exists($image_thumbnail)) unlink($image_thumbnail);
			}
			
		}
	}
	 public function processImg($files,$filename = 'files',$upload_path = 'assets/img/')
	{
		$config['upload_path']          = $upload_path;
        $config['allowed_types']        = 'gif|jpg|png|ico|jpeg';
        $config['max_size']             = 3000;
       // $config['max_width']            = 3000 ;
        //$config['max_height']           = 2000;
	
        $new_name = time().mt_rand(1,99999);
		$config['file_name'] = $new_name;
        $photos = "";
        $dataInfo = array();

      	$this->upload->initialize($config);
        if ( ! $this->upload->do_upload($filename))
        {
            $error = array('error' => $this->upload->display_errors());
            // print_r($error);
            // exit();
            // return false;
        }
        else
        {
             $data = array('upload_data' => $this->upload->data());
             return $data['upload_data']['file_name'];
        }
	}
	 public function processVideo($files,$filename = 'files',$upload_path = 'assets/img/')
	{
		$config['upload_path']          = $upload_path;
        $config['allowed_types']        = 'mp4';
        $config['max_size']             = 102400;
       // $config['max_width']            = 3000 ;
        //$config['max_height']           = 2000;
        $new_name = time().mt_rand(1,99999);
		$config['file_name'] = $new_name;
        $photos = "";
        $dataInfo = array();
      	
      	
      	$this->upload->initialize($config);
      	

        if ( ! $this->upload->do_upload($filename))
        {
            $error = array('error' => $this->upload->display_errors());
            // print_r($error);
            // exit();
            // return false;
        }
        else
        {
             $data = array('upload_data' => $this->upload->data());
             return $data['upload_data']['file_name'];
        }
	}

	public function create_thumbnail($filename,$multi_images = FALSE)
	{
			
			$target_path = $_SERVER['DOCUMENT_ROOT'] . '/'.CMS_PATH.'assets/cms_images/thumbnail';
			$config = array(
			  'image_library' => 'gd2',
			  'source_image'  => '',
			  'new_image' => $target_path,
			  'maintain_ratio' => TRUE,
			  'create_thumb' => TRUE,
			  'thumb_marker' => '',
			  'width' => 150,
			  'height' => 150
		  );
		  if ($multi_images)
		  {
			foreach ($filename as $file)
			{
				 $source_path = $_SERVER['DOCUMENT_ROOT'].'/'.CMS_PATH.'assets/cms_images/'.$file;
				$config['source_image'] = $source_path; 
				 $this->image_lib->initialize($config);
				  if (!$this->image_lib->resize())
					  echo $this->image_lib->display_errors();
			}
		  }
		  else
		  {
			$source_path = $_SERVER['DOCUMENT_ROOT'].'/'.CMS_PATH.'assets/cms_images/'.$filename;
			$config['source_image'] = $source_path;
			$this->image_lib->initialize($config);
			if (!$this->image_lib->resize())
				 echo $this->image_lib->display_errors();
				  
		  }
		  $this->image_lib->clear();
		}
	
	public function processMultipleIamges($files,$filename = 'files',$upload_path = 'assets/img/')
	{
		$config['upload_path']          = $upload_path;
        $config['allowed_types']        = 'gif|jpg|png|jpeg';
        $config['max_size']             = 3000;
       // $config['max_width']            = 3000 ;
        //$config['max_height']           = 2000;
        
        $photos = array();
        $dataInfo = array();

        $cpt = count($_FILES[$filename]['name']);
        for($i=0; $i < $cpt; $i++)
        {   
   			$new_name = time().rand(9,99999);
			$config['file_name'] = $new_name.$i;       
            $_FILES[$filename]['name']= $files[$filename]['name'][$i];
            $_FILES[$filename]['type']= $files[$filename]['type'][$i];
            $_FILES[$filename]['tmp_name']= $files[$filename]['tmp_name'][$i];
            $_FILES[$filename]['error']= $files[$filename]['error'][$i];
            $_FILES[$filename]['size']= $files[$filename]['size'][$i];  

            $this->upload->initialize($config);
            if ( ! $this->upload->do_upload($filename))
            {
                $error = array('error' => $this->upload->display_errors());
                //print_r($error);
            }
            else
            {  
                $dataInfo = array('upload_data' => $this->upload->data());
               array_push($photos,$dataInfo['upload_data']['file_name']);
			   #$photos[] = $dataInfo['upload_data']['file_name'];
               
            }

            
        }
        return implode(',',$photos );
	}

	
	public function processMultipleFiles($files,$filename = 'files',$thumbnail = FALSE)
	{
		$config['upload_path']          = "assets/cms_images/";
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 3000;
       // $config['max_width']            = 3000 ;
        //$config['max_height']           = 2000;
        
        $photos = array();
        $dataInfo = array();
        // echo "<pre>";
        // print_r($_FILES);
       
        
        $cpt = count($_FILES[$filename]['name']);
        for($i=0; $i < $cpt; $i++)
        {   
   			$new_name = time().rand(9,99999);
			$config['file_name'] = $new_name.'_'.$config['file_name'];       
            $_FILES[$filename]['name']= $files[$filename]['name'][$i];
            $_FILES[$filename]['type']= $files[$filename]['type'][$i];
            $_FILES[$filename]['tmp_name']= $files[$filename]['tmp_name'][$i];
            $_FILES[$filename]['error']= $files[$filename]['error'][$i];
            $_FILES[$filename]['size']= $files[$filename]['size'][$i];  

            $this->upload->initialize($config);
            // $this->load->library('upload', $config);

            if ( ! $this->upload->do_upload($filename))
            {
                $error = array('error' => $this->upload->display_errors());
                //print_r($error);
            }
            else
            {  
                $dataInfo = array('upload_data' => $this->upload->data());
                $photos[] = $dataInfo['upload_data']['file_name'];
               
            }

            
        }
        if($thumbnail) $this->create_thumbnail($photos,TRUE);
        return implode(';',$photos );
	}
	
   	 public function processFile($files,$filename = 'files',$upload_path = "assets/lib/uploads",$allowed_types = "docx|doc|pdf|txt|xls|xlsx")
	{	$config['upload_path']          = $upload_path;
		$config['allowed_types']        = $allowed_types;
		$config['max_size']             = 20000;
		
		$doks = array();
        $dataInfo = array();
		
		$cpt = count($_FILES[$filename]['name']);
		if($cpt > 1){
			for($i=0; $i < $cpt; $i++)
			{   
				$new_name = time().rand(9,99999);
				$config['file_name'] = $new_name.'_'.$_FILES[$filename]['name'][$i];       
				$_FILES[$filename]['name']= $files[$filename]['name'][$i];
				$_FILES[$filename]['type']= $files[$filename]['type'][$i];
				$_FILES[$filename]['tmp_name']= $files[$filename]['tmp_name'][$i];
				$_FILES[$filename]['error']= $files[$filename]['error'][$i];
				$_FILES[$filename]['size']= $files[$filename]['size'][$i];  
	
				$this->upload->initialize($config);
				// $this->load->library('upload', $config);
	
				if ( ! $this->upload->do_upload($filename))
				{
					$error = array('error' => $this->upload->display_errors());
					//print_r($error);
				}
				else
				{  
					$dataInfo = array('upload_data' => $this->upload->data());
					$doks[] = $dataInfo['upload_data']['file_name'];
				   
				}
	
				
			}
			//if($thumbnail) $this->create_thumbnail($photos,TRUE);
			return implode(',',$doks );
		}
		else{
			$new_name = time().mt_rand(1,999000); 
			$config['file_name'] = $new_name.'_'.$_FILES[$filename]['name'];
			$photos = "";
			$dataInfo = array();
			$this->upload->initialize($config);
			// $this->load->library('upload', $config);

			if ( ! $this->upload->do_upload($filename))
			{
				$error = array('error' => $this->upload->display_errors());
				 //print_r($error);
				 //exit();
				//return false;
			}
			else
			{
				 $data = array('upload_data' => $this->upload->data());
				 return $data['upload_data']['file_name'];
			}
		}
	}

	public function deleteSimple()
	{
		if (isset($_POST['tableName']) && isset($_POST['key']) && isset($_POST['value']))
		{
			if (!empty($_POST['tableName']) && !empty($_POST['key']) && !empty($_POST['value']))
			{
				
				$this->AdminModel->delRow($_POST['tableName'],array($_POST['key']=>$_POST['value']));
				$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success."
				));
				echo show_flash_data();
			}
			else
			{
				$this->session->set_flashdata('alert_data', array(
				'type' => 'warning', 
				'details' => " Developer Error! Empty Parameter"
				));
				echo show_flash_data();
			}
		}
		else
			{
				$this->session->set_flashdata('alert_data', array(
				'type' => 'warning', 
				'details' => " Developer Error! Empty Parameter"
				));
				echo show_flash_data();
			}
	}
	
	public function deleteRecord()
	{

		if (isset($_POST['tableName']) && isset($_POST['key']) && isset($_POST['value']))
		{
			if (!empty($_POST['tableName']) && !empty($_POST['key']) && !empty($_POST['value']))
			{
				if (isset($_POST['file']) && !empty($_POST['file']))
				{
					 $this->deleteImage($_POST['tableName'],$_POST['key'],$_POST['value'],$_POST['file'],$_POST['file_path']);
				}
				$this->AdminModel->delRow($_POST['tableName'],array($_POST['key']=>$_POST['value']));
				
			##log object##
			$logs['action']='Delete'; $logs['tbl_nmae']=$_POST['tableName']; $logs['instrument']=$_POST['tableName']; 
			$logs['record_id']=$_POST['value'];
			$logs['qry_statment']=$this->db->last_query(); $logs['userid']=$this->session->userid;
			@$this->AdminModel->insertData('e_logs',$logs);
			####_###
			
			
				$this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Action Success."
				));
				echo show_flash_data();
			}
			else
			{
				$this->session->set_flashdata('alert_data', array(
				'type' => 'warning', 
				'details' => " Developer Error! Empty Parameter"
				));
				echo show_flash_data();
			}
		}
		else
		{
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => "Developer Error: Parameter Missing"
				));
			echo show_flash_data();
		}
		
	}
	
	public function getDoubleFieldAjax()
	{	#targetfield,value,table,keyindex
		$data = array();
		$key 		= $this->input->post('keyindex');
		$tableName 	= $this->input->post('table');
		$value		= $this->input->post('value');
		$fieldName 	= $this->input->post('targetfield');
		$fieldNameTwo 	= $this->input->post('targetfieldtwo');
		
		$data[0] = $this->AdminModel->getField($tableName,array($key=>$value),$fieldName);
		$data[1] = $this->AdminModel->getField($tableName,array($key=>$value),$fieldNameTwo);
		
		echo json_encode($data);
	}
	
	
	public function getsingleFieldAjaxR()
	{	#targetfield,value,table,keyindex
		$tableName 	= $this->input->post('table');
		$value		= $this->input->post('value');
		$fieldName 	= $this->input->post('targetfield');
		$uid 	= $this->input->post('uid');
		$obj='<option></option>';
		if($value=="MATS"){
			$ind = $this->AdminModel->getRows('e_mat',array('generated_by'=>$this->session->userid,'approved_status'=>'Approved'));
           // echo $this->db->last_query(); 
			foreach ($ind as $key => $value) {
                  	$obj.= "<option value='$value[id]'>MAT No: ".$value['id']."</option>";
                  } 
		}
		elseif($value=="TMPISSUES"){
		$ind = $this->AdminModel->getRows('e_tempissue',array('generated_by'=>$this->session->userid,'approved_status'=>'Approved'));
              foreach ($ind as $key => $value) {
                  	$obj.= "<option value='$value[id]'>Temp Issue No: ".$value['id']."</option>";
                  } 			
		}
		elseif($value=="ADV"){
		$ind = $this->AdminModel->getRows('e_adv',array('bom_remarks<>'=>'','generated_by'=>$uid,'approved_status'=>'Approved'));
              foreach ($ind as $key => $value) {
                  	$obj.= "<option value='$value[id]'>Adv Booking No: ".$value['id']."</option>";
                  } 			
		}
		elseif($value=="MMR"){
		$ind = $this->AdminModel->getRows('e_mm',array('bom_remarks<>'=>'','generated_by'=>$uid,'approved_status'=>'Approved'));
              foreach ($ind as $key => $value) {
                  	$obj.= "<option value='$value[id]'>MM No: ".$value['id']."</option>";
                  } 			
		}
		else{		
		$ind = $this->AdminModel->getRows($tableName,array($fieldName=>$value));
              foreach ($ind as $key => $value) {
                  	$obj.= "<option value='$value[id]'>".$value['title']."</option>";
                  }   
		}
        echo $obj;
	}
	
	
	public function getsingleFieldAjax()
	{	#targetfield,value,table,keyindex
		$tableName 	= $this->input->post('table');
		$value		= $this->input->post('value');
		$fieldName 	= $this->input->post('targetfield');
		$obj='<option></option>';
		if($value=="MATS"){
			$ind = $this->AdminModel->getRows('e_mat',array('bom_remarks<>'=>'','generated_by'=>$this->session->userid,'approved_status'=>'Approved'));
           // echo $this->db->last_query(); 
			foreach ($ind as $key => $value) {
                  	$obj.= "<option value='$value[id]'>MAT No: ".$value['id']."</option>";
                  } 
		}
		elseif($value=="TMPISSUES"){
		$ind = $this->AdminModel->getRows('e_tempissue',array('bom_remarks<>'=>'','generated_by'=>$this->session->userid,'approved_status'=>'Approved'));
              foreach ($ind as $key => $value) {
                  	$obj.= "<option value='$value[id]'>Temp Issue No: ".$value['id']."</option>";
                  } 			
		}
		elseif($value=="ADV"){
		$ind = $this->AdminModel->getRows('e_adv',array('bom_remarks<>'=>'','generated_by'=>$this->session->userid,'approved_status'=>'Approved'));
              foreach ($ind as $key => $value) {
                  	$obj.= "<option value='$value[id]'>Adv Booking No: ".$value['id']."</option>";
                  } 			
		}
		elseif($value=="MMR"){
		$ind = $this->AdminModel->getRows('e_mm',array('bom_remarks<>'=>'','generated_by'=>$this->session->userid,'approved_status'=>'Approved'));
              foreach ($ind as $key => $value) {
                  	$obj.= "<option value='$value[id]'>MM No: ".$value['id']."</option>";
                  } 			
		}
		else{		
		$ind = $this->AdminModel->getRows($tableName,array($fieldName=>$value));
              foreach ($ind as $key => $value) {
                  	$obj.= "<option value='$value[id]'>".$value['title']."</option>";
                  }   
		}
        echo $obj;
	}
	
	public function getGovvaccinationsReport(){		
		$data = $_POST; $addl_string='';
		
		if($data['location']>0)
			$addl_string .= " and location = '".$data['location']."'";
		
		if($data['district']!='')
			$addl_string .= " and district = '".$data['district']."'";
			
		if($data['mobileno']!='')
			$addl_string .= " and mobileno = '".$data['mobileno']."'";
		
		if($data['citizenid']!='')
			$addl_string .= " and citizenid = '".$data['citizenid']."'";
		
		if($data['date_from']!='' && $data['date_to']!='' )
			$addl_string .= " and vaccine_date >= '".$data['date_from']."' and vaccine_date <= '".$data['date_to']."'";
			
		/*elseif($data['date_from']!='' && $data['date_to']=='' )
			$addl_string .= " and vaccine_date = '".$data['date_from']."'";*/
		
		$records = $this->AdminModel->myQuery("SELECT idtype, citizenid, name, fathername, status, mobileno, gender, age, healthunitid, location, vaccinatorno, vaccine_date, disability, sociostatus, vaccineid, batchno, dosage FROM e_vaccinations where idtype in ('CNIC','POR')  ".$addl_string)->result_Array();
		$cnt=0;
		foreach($records as $rec){
				$records[$cnt]['location'] = getField('e_locations',array('id'=>$records[$cnt]['location']),'title');
				$records[$cnt]['vaccineid'] = getField('e_vaccines',array('id'=>$records[$cnt]['vaccineid']),'title');
				$records[$cnt]['citizenid'] = strtolower($records[$cnt]['citizenid']);
				//$records[$cnt]['vaccine_date'] = PHPExcel_Shared_Date::PHPToExcel($records[$cnt]['vaccine_date']);//date('d/m/Y',strtotime($records[$cnt]['vaccine_date']));
		 $cnt++;
		}
	//	echo "SELECT idtype, citizenid, name, fathername, status, mobileno, gender, age, healthunitid, location, vaccinatorno, vaccine_date, disability, sociostatus, vaccineid, batchno, dosage FROM e_vaccinations where idtype in ('CNIC','POR')  ".$addl_string;
		echo json_encode($records);
	}
	
	public function getVtvaccinationsReport(){		
		$data = $_POST; $addl_string='';
		
		if($data['location']>0)
			$addl_string .= " and location = '".$data['location']."'";
		
		if($data['mobileno']!='')
			$addl_string .= " and mobileno = '".$data['mobileno']."'";
		
		if($data['citizenid']!='')
			$addl_string .= " and citizenid = '".$data['citizenid']."'";
		
		if($data['date_from']!='' && $data['date_to']!='' )
			$addl_string .= " and vaccine_date >= '".$data['date_from']."' and vaccine_date <= '".$data['date_to']."'";
		/*elseif($data['date_from']!='' && $data['date_to']=='' )
			$addl_string .= " and vaccine_date = '".$data['date_from']."'";*/
		//echo " SELECT idtype, citizenid, passportno, name, fathername, status, mobileno, gender, age, healthunitid, location, vaccinatorno, vaccine_date, disability, sociostatus, vaccineid, batchno, dosage FROM e_vaccinations where 1 ".$addl_string;	
		$records = $this->AdminModel->myQuery("SELECT idtype, citizen_type,citizenid, passportno, name, fathername, status, mobileno, gender, age, healthunitid, location, actual_location,vaccinatorno, vaccine_date, disability, sociostatus, vaccineid, batchno, dosage,district,teamid FROM e_vaccinations where 1 ".$addl_string)->result_Array();
		$cnt=0;
		foreach($records as $rec){
				$records[$cnt]['location'] = getField('e_locations',array('id'=>$records[$cnt]['location']),'title');
				$records[$cnt]['vaccineid'] = getField('e_vaccines',array('id'=>$records[$cnt]['vaccineid']),'title');
				$records[$cnt]['teamid'] = getField('e_admin',array('userid'=>$records[$cnt]['teamid']),'name');
				//$records[$cnt]['vaccine_date'] = PHPExcel_Shared_Date::PHPToExcel($records[$cnt]['vaccine_date']);//date('d/m/Y',strtotime($records[$cnt]['vaccine_date']));
		 $cnt++;
		}
		echo json_encode($records);
	}
	
	public function getVialsReport(){		
		$data = $_POST; $addl_string='';
		
		if($data['vaccineid']>0)
			$addl_string .= " and vaccineid = '".$data['vaccineid']."'";
		
		if($data['date_from']!='' && $data['date_to']!='' )
			$addl_string .= " and issuancedate BETWEEN '".$data['date_from']."' AND '".$data['date_to']."'";
		/*elseif($data['date_from']!='' && $data['date_to']=='' )
			$addl_string .= " and vaccine_date = '".$data['date_from']."'";*/
			
		$records = $this->AdminModel->myQuery(" SELECT issuancedate,vaccineid,batchno, received, consumed,wasted, returned FROM e_vials where 1 ".$addl_string)->result_Array();
		$cnt=0;
		foreach($records as $rec){
				$records[$cnt]['vaccineid'] = getField('e_vaccines',array('id'=>$records[$cnt]['vaccineid']),'title');
		 $cnt++;
		}
		echo json_encode($records);
	}
	
	public function getSessionReport(){		
		$data = $_POST; $addl_string='';
		
		if($data['topic']!='')
			$addl_string .= " and topic = '".$data['topic']."'";
		
		if($data['date_from']!='' && $data['date_to']!='' )
			$addl_string .= " and sessiondate BETWEEN '".$data['date_from']."' AND '".$data['date_to']."'";
		/*elseif($data['date_from']!='' && $data['date_to']=='' )
			$addl_string .= " and vaccine_date = '".$data['date_from']."'";*/
			
		$records = $this->AdminModel->myQuery(" SELECT sessiondate,topic,location,actual_location,males, females, transgenders  FROM e_healthsessions where 1 ".$addl_string)->result_Array();
		
		$cnt=0;
		foreach($records as $rec){
				$records[$cnt]['location'] = getField('e_locations',array('id'=>$records[$cnt]['location']),'title');
				//$records[$cnt]['vaccine_date'] = PHPExcel_Shared_Date::PHPToExcel($records[$cnt]['vaccine_date']);//date('d/m/Y',strtotime($records[$cnt]['vaccine_date']));
		 $cnt++;
		}
		
		
		echo json_encode($records);
	}
	
	public function getFeedbackReport(){		
		$data = $_POST; $addl_string='';
				
		if($data['date_from']!='' && $data['date_to']!='' )
			$addl_string .= " and tdate BETWEEN '".$data['date_from']."' AND '".$data['date_to']."'";
		/*elseif($data['date_from']!='' && $data['date_to']=='' )
			$addl_string .= " and vaccine_date = '".$data['date_from']."'";*/
			
		$records = $this->AdminModel->myQuery(" SELECT tdate,name,subject, phone, email,message  FROM e_contact_us where 1 ".$addl_string)->result_Array();
		
		echo json_encode($records);
	}
	
	public function getPOL()
	{	
		$data=$nestedData=array();
		$this->db->query("SET SESSION sql_mode = ''");
		$wsdaily = $this->AdminModel->myQueryz("select i.id,i.item_name,i.part_number,i.min_qty, SUM(q.item_quantity) item_quantity from e_items i, e_items_qty q where i.id=q.item_id  group by q.item_id");// HAVING item_quantity <=i.min_qty");

		#$wsdaily = $this->AdminModel->getDataJoin("e_items","e_items_qty","ON e_items.id=e_items_qty.item_id",$cond = array('e_items_qty.item_quantity <= '=>'e_items.min_qty'));		
		
		
			foreach ($wsdaily as $row){ 
			extract($row);	
			$nestedData=array();
			
			 ###restock
			 $xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$id." and instrument in ('restock')");
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;			
			$restockedItems = $xx[0]['item_quantity'];
			
			/*###release
			 $xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$id." and instrument in ('release')");
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;			
			$releaseItems = $xx[0]['item_quantity'];
			*/
			###TMP ISSUES
			$xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$id." and instrument in ('tmpissue','adv','mat','mmov')");
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;			
			$tmpissueItems = $xx[0]['item_quantity'];
			
			$availInStore = ($item_quantity-$tmpissueItems+$restockedItems);//+$releaseItems);
			
			if($availInStore>$min_qty) continue;
			
				$nestedData['id'] 					= $id; #'j M Y h:i a'
		        $nestedData['item_name'] 			=  $item_name;
		        $nestedData['part_number'] 			= $part_number;
		        $nestedData['min_qty'] 				= $min_qty;
				$nestedData['item_quantity'] 		= $availInStore;//$item_quantity;
                array_push($data,$nestedData);//$output[] = $nestedData;
					
			}//endforeach
		
		$columns = array(
			array( 'db' => 'id',         		    'dt' => 0 ),
			array( 'db' => 'item_name',        	    'dt' => 1 ),
			array( 'db' => 'part_number',           'dt' => 2 ),
			array( 'db' => 'min_qty',   			'dt' => 3 ),
			array( 'db' => 'item_quantity',   		'dt' => 4 )
		);
		$recordsz = SSP::simpleAIK( $_GET,  $columns, $data );
    	echo json_encode($recordsz);
			 
	}
public function getPOL2()
	{	
		$data=$nestedData=array();
		$wsdaily = $this->db->query("select m.id mat_id,m.department_id, m.section_id,mi.project_id, m.generated_by, mi.item_id,mi.assembly_id,mi.sub_assembly_id,mi.qty_needed, ia.item_quantity from e_mat m, e_matitem mi,e_items_alloc ia where m.store_remarks like '%Partially%' and m.id=mi.mat_id and mi.mat_id=ia.instrument_no and mi.qty_needed>ia.item_quantity");//->result_array();

foreach ($wsdaily->result_array() as $row) { 
    extract($row); // Extract variables from the row array
    
    $nestedData = array(
        'mat_id' => $mat_id,
        'department_id' => getField("e_departments",array("id"=>$department_id),"title"),
        'section_id' => getField("e_departments",array("id"=>$section_id),"title"),
        'project_id' => getField("e_projects",array("id"=>$project_id),"title"),
        'generated_by' => getField("e_admin",array("userid"=>$generated_by),"name"),
        'mis_no' => $item_id,
        'assembly_id' => getField("e_assemblies",array("id"=>$assembly_id),"title"),
        'sub_assembly_id' => getField("e_assemblies",array("id"=>$sub_assembly_id),"title"),
        'qty_needed' => $qty_needed,
        'issue_quantity' => $item_quantity,
	'req_quantity' => ($qty_needed-$item_quantity)
    );
    
    $data[] = $nestedData; // Push the $nestedData array into the $data array
}
		$columns = array(
			array( 'db' => 'mis_no',         	'dt' => 0 ),
			array( 'db' => 'mat_id',        	'dt' => 1 ),
			array( 'db' => 'generated_by',          'dt' => 2 ),
			array( 'db' => 'department_id',   	'dt' => 3 ),
			array( 'db' => 'section_id',   		'dt' => 4 ),
			array( 'db' => 'project_id',         	'dt' => 5 ),
			array( 'db' => 'assembly_id',        	'dt' => 6 ),
			array( 'db' => 'sub_assembly_id',       'dt' => 7 ),
			array( 'db' => 'qty_needed',   		'dt' => 8 ),
			array( 'db' => 'issue_quantity',   	'dt' => 9 ),
			array( 'db' => 'req_quantity',   	'dt' => 10 )
		);

		$recordsz = SSP::simpleAIK( $_GET,  $columns, $data);
    	echo json_encode($recordsz);
			 
	}

	public function getPOLXXXXX()
	{	
		$data=$nestedData=array();
		$this->db->query("SET SESSION sql_mode = ''");
		$wsdaily = $this->AdminModel->myQueryz("select i.id,i.item_name,i.part_number,i.min_qty, SUM(q.item_quantity) item_quantity from e_items i, e_items_qty q where i.id=q.item_id  group by q.item_id");// HAVING item_quantity <=i.min_qty");

		#$wsdaily = $this->AdminModel->getDataJoin("e_items","e_items_qty","ON e_items.id=e_items_qty.item_id",$cond = array('e_items_qty.item_quantity <= '=>'e_items.min_qty'));		
		
		
			foreach ($wsdaily as $row){ 
			extract($row);	
			$nestedData=array();
			
			 ###restock
			 $xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$id." and instrument in ('restock')");
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;			
			$restockedItems = $xx[0]['item_quantity'];
			
			/*###release
			 $xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$id." and instrument in ('release')");
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;			
			$releaseItems = $xx[0]['item_quantity'];
			*/
			###TMP ISSUES
			$xx = $this->AdminModel->myQueryz("select sum(item_quantity) item_quantity from e_items_alloc where item_id=".$id." and instrument in ('tmpissue','adv','mat','mmov')");
			if($xx[0]['item_quantity']>=0){} else $xx[0]['item_quantity']=0;			
			$tmpissueItems = $xx[0]['item_quantity'];
			
			$availInStore = ($item_quantity-$tmpissueItems+$restockedItems);//+$releaseItems);
			
			if($availInStore>$min_qty) continue;
			
				$nestedData['id'] 					= $id; #'j M Y h:i a'
		        $nestedData['item_name'] 			=  $item_name;
		        $nestedData['part_number'] 			= $part_number;
		        $nestedData['min_qty'] 				= $min_qty;
				$nestedData['item_quantity'] 		= $availInStore;//$item_quantity;
                array_push($data,$nestedData);//$output[] = $nestedData;
					
			}//endforeach
		
		$columns = array(
			array( 'db' => 'id',         		    'dt' => 0 ),
			array( 'db' => 'item_name',        	    'dt' => 1 ),
			array( 'db' => 'part_number',           'dt' => 2 ),
			array( 'db' => 'min_qty',   			'dt' => 3 ),
			array( 'db' => 'item_quantity',   		'dt' => 4 )
		);
		$recordsz = SSP::simpleAIK( $_GET,  $columns, $data );
    	echo json_encode($recordsz);
			 
	}
	
	public function getLogs()
	{	
		$data=$nestedData=array();		
		$wsdaily =  $this->AdminModel->getTableData('e_logs');
		
			foreach ($wsdaily as $row){ 
			extract($row);	
			$nestedData=array();
					
				$nestedData['action'] 			= $action; 
				$nestedData['tbl_name'] 		= $tbl_nmae; 
				$nestedData['instrument'] 		= $instrument; 
		        $nestedData['record_id'] 		=  $record_id;
		        $nestedData['mis_no'] 			= $mis_no;
				$nestedData['qry_statment'] 			= $qry_statment;
		        $nestedData['userid'] 			= getField("e_admin",array("userid"=>$userid),"name");
				$nestedData['createdon'] 		= $createdon;
                array_push($data,$nestedData);//$output[] = $nestedData;
					
			}//endforeach
		
		$columns = array(
			array( 'db' => 'action',         	'dt' => 0 ),
			array( 'db' => 'tbl_name',        	'dt' => 1 ),
			array( 'db' => 'instrument',   		'dt' => 2 ),
			array( 'db' => 'record_id',   		'dt' => 3 ),
			array( 'db' => 'mis_no',            'dt' => 4 ),
			array( 'db' => 'qry_statment',            'dt' => 5 ),
			array( 'db' => 'userid',   			'dt' => 6 ),
			array( 'db' => 'createdon',   		'dt' => 7 )
		);
		$recordsz = SSP::simpleAIK( $_GET,  $columns, $data );
    	echo json_encode($recordsz);
			 
	}
	
	public function getItemsInventory()
	{	
		$data=$nestedData=array();
		$this->db->query("SET SESSION sql_mode = ''");
		$wsdaily = $this->AdminModel->myQueryz("select i.id, i.item_name, i.item_category, i.supplier_id, i.part_number, i.item_description, i.item_functionality, i.min_qty, i.max_qty, i.item_serial_no, i.item_asset_no, sum(q.item_quantity) qty,u.title unit, i.image, i.doc_list, i.item_addl_remarks path, q.pkr_unit_price price, q.item_value value,q.location location, q.custodian custodian,q.supplier_ref supplier_ref, q.supplier_order_code supplier_order_code from e_items i, e_items_qty q, e_units u where i.id=q.item_id and i.item_unit=u.id group by q.item_id");

		foreach ($wsdaily as $row){ 
			extract($row);	
			$nestedData=array();
			$path='';
				$nestedData['id'] 					= $id;
		        $nestedData['item_name'] 			= $item_name;
		        $nestedData['item_category'] 		= $item_category;
		        $nestedData['supplier_id'] 			= $supplier_id;
				$nestedData['supplier_ref'] 		= $supplier_ref;
				$nestedData['part_number'] 			= $part_number;
		        $nestedData['item_description'] 	=  $item_description;
		        $nestedData['item_functionality'] 	= $item_functionality;
		        $nestedData['min_qty'] 				= $min_qty;
				$nestedData['max_qty'] 				= $max_qty;
				$nestedData['item_serial_no'] 		= $item_serial_no;
		        $nestedData['item_asset_no'] 		=  $item_asset_no;
		        $nestedData['qty'] 					= $qty;
		        $nestedData['unit'] 				= $unit;
				$nestedData['image'] 				= $image;
				$nestedData['doc_list'] 			= $doc_list;
		        $nestedData['path'] 				=  $path;
				$nestedData['pkr_unit_price'] 		=  $pkr_unit_price;
				$nestedData['item_value'] 			=  $item_value;
				$nestedData['location'] 			=  $location;
				$nestedData['custodian'] 			=  $custodian;
				$nestedData['supplier_ref'] 		=  $supplier_ref;
				$nestedData['supplier_order_code'] 	=  $supplier_order_code;
				
			
				
                array_push($data,$nestedData);//$output[] = $nestedData;
					
			}//endforeach
		
		$columns = array(
				array( 'db' => 'id',         		    'dt' =>0),
		        array( 'db' => 'item_name',         	'dt' =>1),
		        array( 'db' => 'item_category',         'dt' =>2),
		        array( 'db' => 'supplier_id',         	'dt' =>3),
				array( 'db' => 'supplier_ref',         	'dt' =>4),
				array( 'db' => 'part_number',         	'dt' =>5),
		        array( 'db' => 'item_description',      'dt' =>6),
		        array( 'db' => 'item_functionality',    'dt' =>7),
		        array( 'db' => 'min_qty',         		'dt' =>8),
				array( 'db' => 'max_qty',         		'dt' =>9),
				array( 'db' => 'item_serial_no',        'dt' =>10),
		        array( 'db' => 'item_asset_no',         'dt' =>11),
		        array( 'db' => 'qty',         		    'dt' =>12),
		        array( 'db' => 'unit',         		    'dt' =>13),
				array( 'db' => 'image',         		'dt' =>14),
				array( 'db' => 'doc_list',         		'dt' =>15),
		        array( 'db' => 'path',         		    'dt' =>16),
		        array( 'db' => 'pkr_unit_price',        'dt' =>17),
		        array( 'db' => 'item_value',         	'dt' =>18),
		        array( 'db' => 'location',         		'dt' =>19),
		        array( 'db' => 'custodian',         	'dt' =>20),
		        array( 'db' => 'supplier_ref',         	'dt' =>21),
		        array( 'db' => 'supplier_order_code',   'dt' =>22)
		);
		$recordsz = SSP::simpleAIK( $_GET,  $columns, $data );
    	echo json_encode($recordsz);
			 
	}
	
	public function setStoreRemarks(){
		extract($_POST);//",{tid,tblName,tblxi,tblx,rowkey,tblxi_id}
		$qtNeeded = $this->AdminModel->getSumRows($tblxi,'qty_needed',array($tblxi_id=>$rowkey));
		
		$qtIssued = $this->AdminModel->getSumRows('e_items_alloc','item_quantity',array('instrument_no'=>$rowkey,'instrument'=>$tblName));
		
		
		$ds=array();
		if($qtNeeded==$qtIssued && $qtIssued>0)
			$ds['store_remarks']='Issued';
		if($qtNeeded>$qtIssued && $qtIssued>0)
			$ds['store_remarks']='Partially Issued'; //$this->AdminModel->updateRow($tblx,array('id'=>$rowkey),array('store_remarks'=>''));
		if($qtIssued==0)
			$ds['store_remarks']='Pending';
		
		if($tblx=='e_restock'){
			$ds['bom_remarks']='Restocked';
			$ds['user_receivedon']=date("Y-m-d H:n:s",time());
		}
		
		$this->AdminModel->updateRow($tblx,array('id'=>$rowkey),$ds);
			/*$row[$index]= getField($tblx,array('id'=>$rowkey),'store_remarks');
			if($row[$index]=='')
			$row[$index]='Pending';*/
			
	}
	
	public function partnoAvailability()
	{	
		$username = $this->AdminModel->getField('e_items',$cond = array('part_number'=>$this->input->post('uname')),'part_number');
	
		if($username=='Not found' || $this->input->post('uname')=='')
		{
			echo '';
		}
		else
		{
			echo 'Duplicate';
		}
	}
}

