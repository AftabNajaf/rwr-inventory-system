<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

	public function __construct() {

        parent::__construct();
		$this->load->helper('url');
		$this->load->helper('function_helper');
		$this->load->Model('SiteModel');
		$this->load->library('pagination');
    }
    public function _remap($page,$para = array())
    {
    	$this->subPages($page,$para);
    }
	public function index()
	{
		echo "pages cotnroller index";
	}	
	public function subPages($page,$para)
	{
		if ($page == 'stock-details')
		{
			$settings = $this->SiteModel->getSetting();
			$pageData = $this->SiteModel->getPageData('stock');
			$data =  array(
				'settings' => $settings,
				'pageData' => $pageData,
				'controller'=> 'Pages'
			);
			
			$this->load->view('site_pages/stock_details',$data);
		}
		elseif ($page == 'analysis-index')
		{
			$settings = $this->SiteModel->getSetting();
			$pageData = $this->SiteModel->getPageData('analysis');
			$data =  array(
				'settings' => $settings,
				'pageData' => $pageData,
				'controller'=> 'Pages'
			);
			
			$this->load->view('site_pages/analysis_index',$data);
		}
		elseif ($page == 'dashboard')
		{
			$settings = $this->SiteModel->getSetting();
			$pageData = $this->SiteModel->getPageData('dashboard');
			$data =  array(
				'settings' => $settings,
				'pageData' => $pageData,
				'controller'=> 'Pages'
			);
			
			$this->load->view('site_pages/dashboard',$data);
		}
		elseif ($page == 'data-grid')
		{
			$settings = $this->SiteModel->getSetting();
			$pageData = $this->SiteModel->getPageData('analysis');
			$data =  array(
				'settings' => $settings,
				'pageData' => $pageData,
				'controller'=> 'Pages'
			);
			
			$this->load->view('site_pages/data_grid',$data);
		}		
		elseif ($page == 'contact-us')
		{
			
			// $this->load->helper('captcha')
			$settings = $this->SiteModel->getSetting();
			$pageData = $this->SiteModel->getPageData($page);
			
			if ($pageData)
			{
				$data =  array(
				'settings' => $settings,
				'pageData' => $pageData,
				'controller'=> 'Pages',
				'currentPage'=>$page,
				
				);
				
				$this->load->view('site_pages/contact_us_view',$data);
			}

		}
		else
		{
			$settings = $this->SiteModel->getSetting();
			$pageData = $this->SiteModel->getPageData($page);
			if ($pageData)
			{
				$data =  array(
				'settings' => $settings,
				'pageData' => $pageData,
				'controller'=> 'Pages',
				'currentPage'=>$page
				);
				
				$this->load->view('site_pages/dynamic_view',$data);
			}
			else
			{
				$data =  array(
				'settings' => $settings,
				'pageData' => $pageData,
				'controller'=> 'Pages',
				);
				
				$this->load->view('site_pages/404',$data);
			}

			
		}

	}
}
