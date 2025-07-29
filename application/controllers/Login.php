<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() 
	{
		parent::__construct();
		$this->load->helper("url");
		$this->load->helper("function_helper");
		$this->load->model('AdminModel');
	
    }

	public function index()
	{	

		if(isset($this->session->userdata['logged_in']) && $this->session->userdata['logged_in'] == 'yes')
		{
			redirect('Home');
		}
		else
		{
			
		 $data['settings'] = $this->AdminModel->getSetting();
			$this->load->view('admin_pages/login_view',$data);
		}

	}
	public function signup()
	{	
		 if(isset($this->session->userdata['logged_in']) && $this->session->userdata['logged_in'] = 'yes')
		{
			redirect('Home');
		}
		else
		{
			$data['settings'] = $this->AdminModel->getSetting();
			$this->load->view('admin_pages/signup_view',$data);
		}
	}
	
	public function forgot()
	{	
		if(isset($this->session->userdata['logged_in']) && $this->session->userdata['logged_in'] = 'yes')
		{
			redirect('Home');
		}
		else
		{
			$data['settings'] = $this->AdminModel->getSetting();
			$this->load->view('admin_pages/forgot_view',$data);
		}
	}
	
	
	public function authenticate_login()
	{
		$username     = $this->input->post('username');
		$pass         = substr(md5($this->input->post('password')),0,15);
		$userData 	  = $this->AdminModel->validateLogin($username,$pass);

		if($userData)
		{
			
			$this->session->set_userdata('logged_in', 'yes');
			$this->session->set_userdata('lang', 'english');
			$this->session->set_userdata($userData);
			//echo "<pre>";
		 	//print_r($this->session->get_userdata());
			@$this->AdminModel->updateRow('e_admin',array('userid'=>$this->session->userid),array('emailverified'=>'yes'));
			if($this->session->role=='admin')
		 	redirect('Dashboard/index');
			else
			redirect('Dashboard/index');//redirect('Home');
		}
		else
		{
			//$this->db->last_query()
			$this->session->set_flashdata('alert_data', array(
				'type' => 'danger', 
				'details' => " Incorrect credentials <strong>OR</strong> <br> Account may not Active by Admin"
				));
			 	redirect('Login');
		}
			
	

	}



	public function logout() 

	{

		/*echo '<pre>';

		  print_r($this->session);

		  exit;*/

		$session_data = array(
				'logged_in'	        => '',	
				'user_id'      		=> '',
				'username'       	=> '',
				'user_pass'       	=> '',
				'_config:protected' => '',
				);

		$this->session->unset_userdata('logged_in');
		$this->session->unset_userdata('user_data', $session_data);
		redirect('Home');

	}



}

