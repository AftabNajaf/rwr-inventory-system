<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct() {
        parent::__construct();
		$this->load->helper('url');
		$this->load->helper('function_helper');
		$this->load->Model('AdminModel');
		$this->load->library('pagination');
		$this->load->library('image_lib');
		login_check();
    }
  
    public function index()
	{	
		$settings = $this->AdminModel->getSetting();
		$amenities = $this->AdminModel->myQuery('SELECT * FROM e_categories order by parentid,id')->result_array();//->getRows("e_categories");
		$treeCats = $this->AdminModel->getRows('e_categories',array("parentid"=>0));
		
		$stats['totItems']= $this->AdminModel->countRows('e_items',$cond = array());
		$stats['totItemsVal']= $this->AdminModel->getSumRows('e_items_qty','item_value',$cond = array());
		$stats['criticalItems']= count($this->AdminModel->getDataJoin("e_items","e_items_qty","ON e_items.id=e_items_qty.item_id",$cond = array('e_items_qty.item_quantity <= '=>'e_items.min_qty')));
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData,
			'treeCats'  => $treeCats,
			'stats'  => $stats,
			'data'  => $amenities
		);
		$this->load->view('admin_pages/dashboard_view',$data);
	}
	
}
