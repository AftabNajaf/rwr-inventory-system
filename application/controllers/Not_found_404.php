<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Not_found_404 extends CI_Controller {

	public function __construct() {

        parent::__construct();
		$this->load->helper('url');
		$this->load->helper('function_helper');
		$this->load->Model('SiteModel');
    }

	public function index()
	{

		$settings = $this->SiteModel->getSetting();
		$pageData 	= $this->SiteModel->getPageData('Not_found_404');
		$data =  array(
				'settings' => $settings,
				'controller'=> 'Pages',
				'pageData'	=>$pageData
				);
				
				$this->load->view('site_pages/404',$data);
	}
}
