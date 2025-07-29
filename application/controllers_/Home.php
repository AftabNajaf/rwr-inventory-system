<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct()
	{
        parent::__construct();
		$this->load->helper('url');
		$this->load->helper('function_helper');
		$this->load->Model('AdminModel');
		$this->load->library('pagination');
		$this->load->library('image_lib');
		$this->load->library('upload');
		$this->load->library('SSP');
		$this->load->library('ExcelExport'); 
		login_check();		 
    }
 
    public function index()
	{	$settings = $this->AdminModel->getSetting();
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData
		);
		
		$this->load->view('admin_pages/blankdb',$data);
		
		
	}
	function clean($string)
	{
		   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

		   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
	public function settings()
	{
		$settings = $this->AdminModel->getSetting();
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData
		);
		
		$this->load->view('admin_pages/settings_view',$data);
	}	
	public function user_profile()
	{
		$settings = $this->AdminModel->getSetting();
		$cmsUser = $this->AdminModel->getRow('e_admin',array('userid'=>$this->session->userid));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'cmsUser'  => $cmsUser
		);
		
		$this->load->view('admin_pages/user_profile_view',$data);
	}		
	public function user_roles()
	{
		$settings = $this->AdminModel->getSetting();
		$eroles = $this->AdminModel->getTableData('e_roles');
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'eroles'  => $eroles
		);
		
		$this->load->view('admin_pages/user_roles_view',$data);
	}	
	public function users()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		if($this->session->role == 'level2')
		$cmsUser = $this->AdminModel->getRows('e_admin',array('departmentid'=>$this->session->departmentid));
		else if($this->session->role == 'admin')
		$cmsUser = $this->AdminModel->myQueryz("select * from e_admin where userid>1");
		else
		$cmsUser = array();//$this->AdminModel->getRows('e_admin',array('userid'=>$this->session->userid));
		
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => $cmsUser
		);
		
		$this->load->view('admin_pages/users',$data);
	}	
	public function feedbackform()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		if($this->session->role == 'admin')
		$rooms = $this->AdminModel->getRows("e_contact_us");
		else
		$rooms = $this->AdminModel->getRows("e_contact_us",array("userid"=>$this->session->userid));
		
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => $rooms
		);
		
		$this->load->view('admin_pages/feedback',$data);
	}	
	public function units()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->getRows("e_units");
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/units',$data);
	}	
	public function suppliers()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->getRows("e_suppliers");
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/suppliers',$data);
	}
	public function customers()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->getRows("e_customers");
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/customers',$data);
	}	
	public function departments()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->getRows("e_departments");
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/departments',$data);
	}
	public function categories()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/categories',$data);
	}
	
	public function alloc_details($instrument,$item_id)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$iList = $this->AdminModel->getRows('e_items_alloc',array("item_id"=>$item_id));//,"instrument"=>$instrument
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => $iList
		);
		
		$this->load->view('admin_pages/alloc_details',$data);
	}
	
	
		
	public function itemsgrid()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM table38 where ASAD_ID>=22956 order by ASAD_ID ')->result_array();
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/itemsgrid',$data);
	}
	
	public function items($itid=0)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$fdata = $this->AdminModel->getRow('e_items',array("id"=>$itid));//$this->db->list_fields('e_items');//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'fdata'  => $fdata,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/items',$data);
	}	
	public function pol()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		//$amenities = $this->AdminModel->getDataJoin("e_items","e_items_qty","ON e_items.id=e_items_qty.item_id",$cond = array('e_items_qty.item_quantity <= '=>'e_items.min_qty'));
		
//select m.id,m.department_id, m.section_id,mi.project_id, m.generated_by, mi.item_id,mi.assembly_id,mi.sub_assembly_id,mi.qty_needed, ia.item_quantity from e_mat m, e_matitem mi,e_items_alloc ia where m.store_remarks like "%Partially%" and m.id=mi.mat_id and mi.mat_id=ia.instrument_no and mi.qty_needed>ia.item_quantity

$amenities = $this->AdminModel->myQuery('select m.id mat_id,m.department_id, m.section_id,mi.project_id, m.generated_by, mi.item_id,mi.assembly_id,mi.sub_assembly_id,mi.qty_needed, ia.item_quantity from e_mat m, e_matitem mi,e_items_alloc ia where m.store_remarks like "%Partially%" and m.id=mi.mat_id and mi.mat_id=ia.instrument_no and mi.qty_needed>ia.item_quantity')->result_array();

		//getRows('e_items',array("parentid"=>0));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/pol',$data);
	}
public function polall()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->getDataJoin("e_items","e_items_qty","ON e_items.id=e_items_qty.item_id",$cond = array('e_items_qty.item_quantity <= '=>'e_items.min_qty'));
		


		//getRows('e_items',array("parentid"=>0));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/polall',$data);
	}
	public function po()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		if($this->session->role == 'level1')
		$boms = $this->AdminModel->getRows('e_po',array('generated_by'=>$this->session->userid,'approved_status <>'=>'Submitted'));//
		elseif($this->session->role == 'level2')
		$boms = $this->AdminModel->myQuery("select * from e_po where approved_status <> 'Draft' OR generated_by =".$this->session->userid)->result_array();
		//getRows('e_po',array('approved_status <>'=>'Draft',' OR generated_by'=>$this->session->userid));//'department_id'=>$this->session->departmentid,
		else
		$boms = $this->AdminModel->getRows('e_po',array());
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'boms'  => $boms,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/po',$data);
	}
	public function poitems($poID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_po',array('id'=>$poID));
		$bomi = $this->AdminModel->getRows('e_poitems',array('po_id'=>$poID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/poitems',$data);
	}
	public function grnitems($poID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_po',array('id'=>$poID));
		$bomi = $this->AdminModel->getRows('e_poitems',array('po_id'=>$poID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/grnitems',$data);
	}
	
	public function bmlib()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		$boms = $this->AdminModel->getRows('e_bom',array('approved_status'=>'Approved'));//'department_id'=>$this->session->departmentid,

		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'boms'  => $boms,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/bomlib',$data);
	}
	
	
	public function bom()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		if($this->session->role == 'level1')
		$boms = $this->AdminModel->getRows('e_bom',array('generated_by'=>$this->session->userid));//,'approved_status <>'=>'Submitted'
		elseif($this->session->role == 'level2')
		$boms = $this->AdminModel->getRows('e_bom',array('department_id'=>$this->session->departmentid,'approved_status <>'=>'Draft'));//
		else
		$boms = $this->AdminModel->getRows('e_bom',array());
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'boms'  => $boms,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/bom',$data);
	}
	public function bomitems($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_bom',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_bomitem',array('bom_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/bomitems',$data);
	}
	public function grn()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		if($this->session->role == 'level1')
		$boms = $this->AdminModel->getRows('e_po',array('approved_status '=>'Approved'));
		elseif($this->session->role == 'level2')
		$boms = $this->AdminModel->getRows('e_po',array('approved_status'=>'Approved'));
		else
		$boms = $this->AdminModel->getRows('e_po',array());
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'boms'  => $boms,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/grn',$data);
	}	
	public function itemqty()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/itemqty',$data);
	}
	public function mat()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		if($this->session->role == 'level1')
		$boms = $this->AdminModel->getRows('e_mat',array('generated_by'=>$this->session->userid));//,'approved_status <>'=>'Submitted'
		elseif($this->session->role == 'level2')
		$boms = $this->AdminModel->myQuery('SELECT * FROM e_mat where generated_by='.$this->session->userid.' OR (department_id ='.$this->session->departmentid.' AND approved_status <> "Draft")')->result_array();
		//getRows('e_mat',array('department_id'=>$this->session->departmentid,'approved_status <>'=>'Draft','OR generated_by'=>$this->session->userid));//
		else
		$boms = $this->AdminModel->getRows('e_mat',array());
		
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'boms'  => $boms,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/mat',$data);
	}
	public function matitems($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_mat',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_matitem',array('mat_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/matitems',$data);
	}
	public function matitems_allocation($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_mat',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_matitem',array('mat_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		if($bom[0]['user_receivedon']=='')
		$this->load->view('admin_pages/matitems_allocation',$data);
		else
		redirect('Home/reqs');
	}
	public function projects()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->getRows("e_projects");
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/projects',$data);
	}
	public function assemblies($projectid)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->getRows("e_assemblies",array('projectid'=>$projectid));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'projid'=>$projectid,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/assemblies',$data);
	}
	
	public function adv()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		if($this->session->role == 'level1')
		$boms = $this->AdminModel->getRows('e_adv',array('generated_by'=>$this->session->userid));//,'approved_status <>'=>'Submitted'
		elseif($this->session->role == 'level2')
		$boms = $this->AdminModel->getRows('e_adv',array('department_id'=>$this->session->departmentid,'approved_status<>'=>'Draft'));
		else
		$boms = $this->AdminModel->getRows('e_adv',array());
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'boms'  => $boms,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/adv',$data);
	}
	public function advitems($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_adv',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_advitems',array('adv_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/advitems',$data);
	}
	public function advitems_allocation($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_adv',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_advitems',array('adv_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		
		if($bom[0]['user_receivedon']=='')
		$this->load->view('admin_pages/advitems_allocation',$data);
		else
		redirect('Home/reqs');
	}
	
	public function tempissue()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		if($this->session->role == 'level1')
		$boms = $this->AdminModel->getRows('e_tempissue',array('generated_by'=>$this->session->userid));//,'approved_status <>'=>'Submitted'
		elseif($this->session->role == 'level2')
		$boms = $this->AdminModel->getRows('e_tempissue',array('department_id'=>$this->session->departmentid,'approved_status <>'=>'Draft'));
		else
		$boms = $this->AdminModel->getRows('e_tempissue',array());
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'boms'  => $boms,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/tempissue',$data);
	}
	public function tmpissueitems($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_tempissue',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_tempissueitems',array('adv_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/tmpissueitems',$data);
	}
	public function tmpissueitems_allocation($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_tempissue',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_tempissueitems',array('adv_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		
		if($bom[0]['user_receivedon']=='')
		$this->load->view('admin_pages/tmpissueitems_allocation',$data);
		else
		redirect('Home/reqs');
	}
	
	public function restock()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		if($this->session->role == 'level1')
		$boms = $this->AdminModel->getRows('e_restock',array('generated_by'=>$this->session->userid));//'approved_status <>'=>'Submitted'
		elseif($this->session->role == 'level2')
		$boms = $this->AdminModel->getRows('e_restock',array('department_id'=>$this->session->departmentid,'approved_status<>'=>'Draft'));
		else
		$boms = $this->AdminModel->getRows('e_restock',array());
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'boms'  => $boms,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/restock',$data);
	}
	public function restockitems($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_restock',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_restockitems',array('adv_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/restockitems',$data);
	}
	
	public function restockitems_allocation($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_restock',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_restockitems',array('adv_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		if($bom[0]['user_receivedon']=='')
		$this->load->view('admin_pages/restockitems_allocation',$data);
		else
		redirect('Home/reqs');
		
	}
	
	public function release()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		if($this->session->role == 'level1')
		$boms = $this->AdminModel->getRows('e_release',array('generated_by'=>$this->session->userid));//,'approved_status <>'=>'Submitted'
		elseif($this->session->role == 'level2')
		$boms = $this->AdminModel->getRows('e_release',array('department_id'=>$this->session->departmentid,'approved_status<>'=>'Draft'));
		else
		$boms = $this->AdminModel->getRows('e_release',array());
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'boms'  => $boms,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/release',$data);
	}
	
	public function relreqbyuser()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		if($this->session->role == 'level1')
		$boms = $this->AdminModel->getRows('e_release',array('askfrom'=>$this->session->userid,'approved_status'=>'Approved'));//,'approved_status <>'=>'Submitted'
		elseif($this->session->role == 'level2')
		$boms = $this->AdminModel->getRows('e_release',array('department_id'=>$this->session->departmentid,'approved_status<>'=>'Draft'));
		else
		$boms = $this->AdminModel->getRows('e_release',array());
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'boms'  => $boms,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/relreqbyuser',$data);
	}
	
	public function releaseitems($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_release',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_releaseitems',array('adv_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/releaseitems',$data);
	}
	
	public function releaseitems_allocation($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_release',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_releaseitems',array('adv_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		if($bom[0]['user_receivedon']=='')
		$this->load->view('admin_pages/releaseitems_allocation',$data);
		else
		redirect('Home/reqs');
		
	}
	
	public function releaseitems_allocation_user($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_release',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_releaseitems',array('adv_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		
		if($bom[0]['user_receivedon']=='')
		$this->load->view('admin_pages/releaseitems_allocation_user',$data);
		else
		redirect('Home/relreqbyuser');
	}
	
	
	public function dc()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		if($this->session->role == 'level1')
		$boms = $this->AdminModel->getRows('e_dc',array('approved_status <>'=>'Submitted'));//'generated_by'=>$this->session->userid,
		elseif($this->session->role == 'level2')
		$boms = $this->AdminModel->getRows('e_dc',array('approved_status<>'=>'Draft'));//'department_id'=>$this->session->departmentid,
		else
		$boms = $this->AdminModel->getRows('e_dc',array());
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'boms'  => $boms,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/dc',$data);
	}
	public function dcitems($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_dc',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_dcitems',array('adv_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/dcitems',$data);
	}
	
	
	public function mmbyuser()
	{		
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		if($this->session->role == 'level1')
		$boms = $this->AdminModel->getRows('e_mm',array('askfrom'=>$this->session->userid,'approved_status'=>'Approved'));
		elseif($this->session->role == 'level2')
		$boms = $this->AdminModel->getRows('e_mm',array('approved_status <>'=>'Draft'));//'department_id'=>$this->session->departmentid,
		else
		$boms = $this->AdminModel->getRows('e_mm',array());
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'boms'  => $boms,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/mmbyuser',$data);
	}
	
	public function mmov()
	{		
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		if($this->session->role == 'level1')
		$boms = $this->AdminModel->getRows('e_mm',array('generated_by'=>$this->session->userid));//'askfrom'=>0,,'approved_status <>'=>'Submitted'
		elseif($this->session->role == 'level2')
		$boms = $this->AdminModel->getRows('e_mm',array('approved_status <>'=>'Draft'));//'department_id'=>$this->session->departmentid,
		else
		$boms = $this->AdminModel->getRows('e_mm',array());
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'boms'  => $boms,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/mmov',$data);
	}
	
	public function matmoveitems($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_mm',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_mmitems',array('adv_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/matmovitems',$data);
	}
	public function matmoveitems_allocation($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_mm',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_mmitems',array('adv_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		if($bom[0]['user_receivedon']=='')
		$this->load->view('admin_pages/matmoveitems_allocation',$data);
		else
		redirect('Home/reqs');
		
	}
	
	public function matmoveitems_allocation_user($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_mm',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_mmitems',array('adv_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		if($bom[0]['user_receivedon']=='')
		$this->load->view('admin_pages/matmoveitems_allocation_user',$data);
		else
		redirect('Home/mmbyuser');
		
	}
	
	public function fixed()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		if($this->session->role == 'level1')
		$boms = $this->AdminModel->getRows('e_fixed',array('generated_by'=>$this->session->userid,'approved_status '=>'Draft'));
		elseif($this->session->role == 'level2')
		$boms = $this->AdminModel->getRows('e_fixed',array('department_id'=>$this->session->departmentid,'approved_status'=>'Submitted'));
		else
		$boms = $this->AdminModel->getRows('e_fixed',array());
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'boms'  => $boms,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/fixed',$data);
	}
	public function fixeditems($bomID)
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		//if($this->session->role != 'level1')
		$bom = $this->AdminModel->getRows('e_fixed',array('id'=>$bomID));
		$bomi = $this->AdminModel->getRows('e_fixeditems',array('fixed_id'=>$bomID));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'bom'  => $bom,
			'bomi'  => $bomi,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/fixeditems',$data);
	}
	
	public function feedbackrpt()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$rooms = $this->AdminModel->myQuery('SELECT * FROM e_contact_us')->result_array();  
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => $rooms
		);
		
		$this->load->view('admin_pages/feedbackrpt',$data);
	}
	
	public function reqs()
	{
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		
		if($this->session->role == 'level1' || $this->session->role == 'level2'){
		$mats		 = $this->AdminModel->getRows('e_mat',array('approved_status'=>'Approved'));
		$grns		 = $this->AdminModel->getRows('e_po',array('approved_status'=>'Approved'));
		$advs		 = $this->AdminModel->getRows('e_adv',array('approved_status'=>'Approved'));
		$tmpissues	 = $this->AdminModel->getRows('e_tempissue',array('approved_status'=>'Approved'));
		$mmovs	 	= $this->AdminModel->getRows('e_mm',array('approved_status'=>'Approved','askfrom'=>0));
		$restocks	 = $this->AdminModel->getRows('e_restock',array('approved_status'=>'Approved'));
		$relreqs	 = $this->AdminModel->getRows('e_release',array('approved_status'=>'Approved'));
		}
		else{//for admin
		$mats		 = $this->AdminModel->getRows('e_mat',array());
		$grns		 = $this->AdminModel->getRows('e_po',array());
		$advs		 = $this->AdminModel->getRows('e_adv',array());
		$tmpissues	 = $this->AdminModel->getRows('e_tempissue',array());
		$mmovs	 	= $this->AdminModel->getRows('e_mm',array());
		$restocks	 = $this->AdminModel->getRows('e_restock',array());
		$relreqs	 = $this->AdminModel->getRows('e_release',array());
		}
		
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'mats'  => $mats,
			'grns'  => $grns,
			'advs'  => $advs,
			'tmpissues'  => $tmpissues,
			'mmovs'  => $mmovs,
			'restocks'  => $restocks,
			'relreqs'  => $relreqs,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/reqs',$data);
	}
	
	
	public function rwrdata()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$userData = $this->session->get_userdata();

		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => ''
		);
		
		$this->load->view('admin_pages/rwrdata',$data);
	}
	
	public function ExportData(){
		
		########
		 // Set a flag to indicate the download has not started yet
    $downloadStarted = false;

    // Check if the "downloadStarted" cookie or session variable is set
    if (isset($_COOKIE['downloadStarted']) && $_COOKIE['downloadStarted'] == 'true') {
        $downloadStarted = true;
        // Clear the "downloadStarted" cookie
        setcookie('downloadStarted', '', time() - 3600, '/'); // Clear the cookie
    }
		########
		// Perform your MySQL query and get the result
       // $this->db->query("set SESSION sql_mode=''");
		$result = $this->AdminModel->myQuery('select i.id, i.item_name, i.item_category, i.supplier_id, i.part_number, i.item_description, i.item_functionality, i.min_qty, i.max_qty, i.item_serial_no, i.item_asset_no, q.item_quantity qty,u.title unit, i.image, i.doc_list datasheet, i.item_addl_remarks path, q.pkr_unit_price price, q.item_value item_value,q.location location, q.custodian custodian,q.supplier_ref supplier_ref, q.supplier_order_code supplier_order_code, i.item_addl_remarks remarks_status from e_items i, e_items_qty q, e_units u where i.id=q.item_id and i.item_unit=u.id limit 0,20');
##select i.id, i.item_name, i.item_category, i.supplier_id, i.part_number, i.item_description, i.item_functionality, i.min_qty, i.max_qty, i.item_serial_no, i.item_asset_no, q.item_quantity qty,u.title unit, i.image, i.doc_list datasheet, i.item_addl_remarks path, q.pkr_unit_price price, q.item_value item_value,q.location location, q.custodian custodian,q.supplier_ref supplier_ref, q.supplier_order_code supplier_order_code, i.item_addl_remarks remarks_status from e_items i, e_items_qty q, e_units u where i.id=q.item_id and i.item_unit=u.id group by i.item_id 

		$columns = $result->list_fields();
		$dataArray = array();
		
		foreach ($result->result_array() as $row) {
			$row['path'] = $this->AdminModel->getCatPath($row['item_category']);
			array_push($dataArray,$row);//
			//$dataArray[] = $row;//
		}
		
        // Export the result to Excel
        $this->excelexport->exportData($dataArray, $columns, 'data_export');
		
		######
		 if ($downloadStarted) {
        // Echo a response back to the client indicating the download started
        header('Content-Type: application/json');
        echo json_encode(['downloadStarted' => true]);
        exit;
    	}
		######
	}
	
	/*public function ImportData(){
		// Increase the maximum execution time to a suitable value (e.g., 10 minutes)
    	set_time_limit(1200);
				// Path to the uploaded Excel file
				//$excelFile = $_FILES['doc_list']['tmp_name'];
				$excelFile='';
		if (isset($_FILES) && !empty($_FILES['doc_list']['name']) )
		{
			$excelFile =  $this->processFile($_FILES,'doc_list','assets/lib/uploads/');
		}
		
		$excelFile = 'assets/lib/uploads/'.$excelFile;
				$objPHPExcel = PHPExcel_IOFactory::load($excelFile);
				
				// Select the active sheet
				$sheet = $objPHPExcel->getActiveSheet();
				
				// Iterate through the rows of the sheet
				foreach ($sheet->getRowIterator() as $row) {
					// Get the cell values from the current row
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false);
					
					$data = array();
					foreach ($cellIterator as $cell) {
						$data[] = $cell->getValue();
					}
					
					// Extract the relevant data from the array
					$foreign_key = $data[0];
					if($foreign_key=='id') continue;
					$data[12] = getField("e_units",array("id"=>$data[12]),"title");
					
				#id	item_name	item_category	supplier_id	 part_number	item_description	item_functionality	min_qty	  max_qty	item_serial_no	item_asset_no	qty	unit	image	doc_list	Path	price	value	Location	Custodian	supplier_ref	supplier_order_code
	
###qty 11, UNIT12, pkr_unit_price 16, item_vale 17, location 18, custodian 19,  supplier_ref 20, supplier_order_code 21
					// Check if the record already exists in the first table
					$existingRecord = $this->AdminModel->getRows('e_items', array('id' => $foreign_key));
					
					if ($existingRecord) {
						// If the record exists, update the values
						$this->db->where('id', $foreign_key);
						$this->db->update('e_items', array('item_name' => $data[1], 'item_category' => $data[2],'supplier_id' => $data[3] ,'part_number' => $data[4],'item_description' => $data[5],'item_functionality' => $data[6],'min_qty' => $data[7],'max_qty' => $data[8],'item_serial_no' => $data[9],'item_asset_no' => $data[10],'image' => $data[13],'doc_list' => $data[14],'item_unit' =>$data[12]));
					} else {
						// If the record doesn't exist, insert a new record in the first table
						$this->db->insert('e_items', array('id' => $data[0],'item_name' => $data[1], 'item_category' => $data[2],'supplier_id' => $data[3] ,'part_number' => $data[4],'item_description' => $data[5],'item_functionality' => $data[6],'min_qty' => $data[7],'max_qty' => $data[8],'item_serial_no' => $data[9],'item_asset_no' => $data[10],'image' => $data[13],'doc_list' => $data[14],'item_unit' =>$data[12]));
					}
					
					// Insert the record in the second table
					##$this->db->insert('table2', array('foreign_key' => $foreign_key, 'value3' => $value3, 'value4' => $value4));
				}	
			
				$settings = $this->AdminModel->getSetting();
						$userData = $this->session->get_userdata();
				
						$data =  array(
							'settings' => $settings,
							'userData' => $userData,
							'data'  => array()
						);
				$this->load->view('admin_pages/rwrdata',$data);
	}*/
	
	
	public function ImportData() {
    // Increase the maximum execution time to a suitable value (e.g., 10 minutes)
    set_time_limit(600);

    // Path to the uploaded Excel file
    $excelFile = '';
   /* if (isset($_FILES) && !empty($_FILES['doc_list']['name'])) {
        $excelFile = $this->processFile($_FILES, 'doc_list', 'assets/lib/uploads/');
    }

    $excelFile = 'assets/lib/uploads/' . $excelFile;
	*/
	// Path to the uploaded Excel file
	$excelFile = $_FILES['doc_list']['tmp_name'];
				
    $objPHPExcel = PHPExcel_IOFactory::load($excelFile);
    $sheet = $objPHPExcel->getActiveSheet();
    $rowIterator = $sheet->getRowIterator();

    // Process the rows in smaller chunks
    $chunkSize = 50; // Adjust the chunk size as needed

    // Start processing from the second row (skip the header row)
    $rowIterator->seek(1);
$xxxxx=0;
    while ($rowIterator->valid()) {
        $dataChunk = array();

        for ($i = 0; $i < $chunkSize && $rowIterator->valid(); $i++) {
            $row = $rowIterator->current();

            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = array();
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            $dataChunk[] = $rowData;

            $rowIterator->next();
        }

        // Process the chunk of data
        $this->processDataChunk($dataChunk);
		
    }

    $this->session->set_flashdata('alert_data', array(
				'type' => 'success', 
				'details' => "Process completed"
				));
		$settings = $this->AdminModel->getSetting();
		$userData = $this->session->get_userdata();

		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => array()
		);
		
		$this->load->view('admin_pages/rwrdata',$data);
}

private function processDataChunk($dataChunk) {
    // Iterate through the data chunk and perform database operations

    foreach ($dataChunk as $data) {
        $foreign_key = $data[0];
        if ($foreign_key == 'id') {//  || $data[1]== ''
            continue;
        }
        if($data[12]>0)
			$data[12] = getField("e_units", array("id" => $data[12]), "title");
		else
			$data['12']=1;

		if($data[1]=='')
			$data[1] = @getField("e_items_all", array("id" => $data[0]), "item_name");
		
		if($data[18]=='')//location
			$data[18] = 'Store';
	
        $existingRecord = $this->AdminModel->getRows('e_items', array('id' => $foreign_key));
#id	item_name	item_category	supplier_id	part_number	item_description	item_functionality	min_qty	max_qty	item_serial_no	item_asset_no	qty-11	unit	image	doc_list	Path	price	value	Location	Custodian	supplier_ref	supplier_order_code	status_remarks
		
        if ($existingRecord) {
            $this->db->where('id', $foreign_key);
            $this->db->update('e_items', array(
                'item_name' => $data[1],
                'item_category' => $data[2],
                'supplier_id' => $data[3],
				'supplier_ref' => $data[20],
                'part_number' => $data[4],
                'item_description' => $data[5],
                'item_functionality' => $data[6],
                'min_qty' => $data[7],
                'max_qty' => $data[8],
                'item_serial_no' => $data[9],
                'item_asset_no' => $data[10],
                'image' => $data[13],
                'doc_list' => $data[14],
                'item_unit' => $data[12],
				 'item_addl_remarks' => $data[22]
            ));
        } else {
            if($this->db->insert('e_items', array(
                'id' => $data[0],
                'item_name' => $data[1],
                'item_category' => $data[2],
                'supplier_id' => $data[3],
				'supplier_ref' => $data[20],
                'part_number' => $data[4],
                'item_description' => $data[5],
                'item_functionality' => $data[6],
                'min_qty' => $data[7],
                'max_qty' => $data[8],
                'item_serial_no' => $data[9],
                'item_asset_no' => $data[10],
                'image' => $data[13],
                'doc_list' => $data[14],
                'item_unit' => $data[12],
				 'item_addl_remarks' => $data[22]
            )))
				$foreign_key=$this->db->insert_id();
        } 
		
		
		
		$existingRecordTwo = $this->AdminModel->getRows('e_items_qty', array('item_id' => $foreign_key));
		

		if(!$data[11]>0) $data[11]=0;
		if(!$data[16]>0) $data[16]=0;
		if(!$data[17]>0) $data[17]=0;
		if($foreign_key>0){
			if ($existingRecordTwo) {
				$this->db->where('item_id', $foreign_key);
				$this->db->update('e_items_qty', array(
					'item_quantity' => $data[11],
					'pkr_unit_price' => $data[16],
					'item_value' => $data[17],
					'location' => $data[18],
					'custodian' => $data[19],
					'supplier_ref' => $data[20],
					'supplier_order_code' => $data[21]
				));
			} else {
				$this->db->insert('e_items_qty', array(
					'item_id'=>$foreign_key,
					'item_quantity' => $data[11],
					'pkr_unit_price' => $data[16],
					'item_value' => $data[17],
					'location' => $data[18],
					'custodian' => $data[19],
					'supplier_ref' => $data[20],
					'supplier_order_code' => $data[21]
				));
			}
		}
		
		
    }
	
}

	
	
	
	public function processFile($files,$filename = 'files',$upload_path = "assets/lib/uploads",$allowed_types = "xls|xlsx")
	{	$config['upload_path']          = $upload_path;
		$config['allowed_types']        = $allowed_types;
		
			$new_name = time().mt_rand(1,999000); 
			$config['file_name'] = $new_name.'_'.$_FILES[$filename]['name'];
			$photos = "";

			$this->upload->initialize($config);


			if ( ! $this->upload->do_upload($filename))
			{
				$error = array('error' => $this->upload->display_errors());
				 print_r($error);
			exit();
				//return false;
			}
			else
			{
				 $data = array('upload_data' => $this->upload->data());
				 return $data['upload_data']['file_name'];
			}
	}
	
	public function elogs()
	{
		//if($this->session->role != 'Admin') redirect('Home');
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->getTableData('e_logs');
		
		//getRows('e_items',array("parentid"=>0));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'data'  => $amenities
		);
		
		$this->load->view('admin_pages/elogs',$data);
	}
}
