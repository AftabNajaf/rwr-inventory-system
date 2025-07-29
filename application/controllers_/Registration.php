<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends CI_Controller {

	public function __construct() {

        parent::__construct();
		$this->load->helper('url');
		$this->load->helper('function_helper');
		$this->load->Model('AdminModel');
		
		
    }
  
    public function index()
	{/*	$settings = $this->AdminModel->getSetting();
		$userData = $this->session->get_userdata();
		$data =  array(
			'settings' => $settings,
			'userData' => $userData
		);
		
		$this->load->view('admin_pages/blankdb',$data);
		
		
	*/}
	public function getsingleFieldAjax()
	{	#targetfield,value,table,keyindex
		$tableName 	= $this->input->post('table');
		$value		= $this->input->post('value');
		$fieldName 	= $this->input->post('targetfield');
		$obj='';
		
		$ind = $this->AdminModel->getRows($tableName,array($fieldName=>$value));
              foreach ($ind as $key => $value) {
                  	$obj.= "<option value='$value[id]'>".$value['title']."</option>";
                  }    
        echo $obj;
	}
	
	public function usernameAvailability()
	{	
		$username = $this->AdminModel->getField('e_admin',$cond = array('username'=>$this->input->post('uname')),'username');
	
		if($username=='Not found')
		{
			echo '<br><small style="color:green">Username available!</small>';
		}
		else
		{
			echo '<br><small style="color:red">Username already exist...</small>';
		}
	}
	
	public function crudSimple()
	{   
		$data 		= $_POST;  
		$tmp_pass	= time();
		$data['password'] = MD5($tmp_pass);
		
		
		//echo "<pre>"
		//print_r($data);exit;

		if ($this->AdminModel->insertData('e_admin',$data))
		{  
			$insertid= $this->db->insert_id();
			### send verification email	
			$this->load->library('phpmailer_lib');
        	$mail = $this->phpmailer_lib->load();

			//$mail = new PHPMailer; 
 
			// Server settings 
			//$mail->SMTPDebug = SMTP::DEBUG_SERVER;    //Enable verbose debug output 
			$mail->isSMTP();                            // Set mailer to use SMTP 
			$mail->Host = 'smtp.dreamhost.com';           // Specify main and backup SMTP servers 
			$mail->SMTPAuth = true;                     // Enable SMTP authentication 
			$mail->Username = 'alerts@rwrmis.com';       // SMTP username 
			$mail->Password = 'Star_5112';         // SMTP password 
			$mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted 
			$mail->Port = 465;                          // TCP port to connect to 
			 
			// Sender info 
			$mail->setFrom('alerts@rwrmis.com', 'RWR'); 
			$mail->addReplyTo('alerts@rwrmis.com', 'RWR'); 
			 
			// Add a recipient 
			$mail->addAddress($data['email']); 
			 
			//$mail->addCC('cc@example.com'); 
			//$mail->addBCC('bcc@example.com'); 
			 
			// Set email format to HTML 
			$mail->isHTML(true); 
			 
			// Mail subject 
			$mail->Subject = 'RWR Registration'; 
			 
			// Mail body content 
			$bodyContent = '<h1>Thank you for Sign up with RWR MIS</h1>'; 
			$bodyContent .= '<p>Dear '.$data['name'].'<br><br>'.'To  complete your singup process with  RWR-MIS, please use following credentials to signin:-<br><br> Username : '.$data['username'].'<br>Password : '.$tmp_pass.'<br><br>RWR Pvt Ltd</p>'; 
			$mail->Body    = $bodyContent; 
			 
			// Send email 
			if(!$mail->send()) {  
				$xmsg = 'A verification email FAILURE for '.$data['email'].'Mailer Error: '.$mail->ErrorInfo;
				$this->session->set_flashdata('alert_data', array(
							'type' => 'danger', 
							'details' => "Signup completed. ".$xmsg
							)); 
			} else { 
					$xmsg = 'A verification email has been sent to '.$data['email'];
				$this->session->set_flashdata('alert_data', array(
							'type' => 'success', 
							'details' => "Signup completed. ".$xmsg
							)); 
			}
	}
	}
	
	public function emailValidity()
	{	
		$username = $this->AdminModel->getField('e_admin',$cond = array('email'=>$this->input->post('uname')),'email');
	
		if($username=='Not found')
		{
			echo '<br><h6 style="color:red; background-color:white;background-color: rgb(234 241 235 / 81%); padding: 5px">
			Email not found...!</h6>';
		}
		else
		{
			echo 'success';
		}
	}
	public function resetPassword()
	{   
		$data 		= $_POST;  
		$tmp_pass	= time();
		$data['password'] = MD5($tmp_pass);
		$cmail = $data['uname'];
		unset($data['uname']);
		//echo "<pre>";
		//print_r($data); exit;
		if ($this->AdminModel->updateRow('e_admin',array('email'=>$cmail),$data))
		{  
			$this->load->library('phpmailer_lib');
        	$mail = $this->phpmailer_lib->load();

			//$mail = new PHPMailer; 
 
			// Server settings 
			//$mail->SMTPDebug = SMTP::DEBUG_SERVER;    //Enable verbose debug output 
			$mail->isSMTP();                            // Set mailer to use SMTP 
			$mail->Host = 'smtp.dreamhost.com';           // Specify main and backup SMTP servers 
			$mail->SMTPAuth = true;                     // Enable SMTP authentication 
			$mail->Username = 'alerts@rwrmis.com';       // SMTP username 
			$mail->Password = 'Star_5112';         // SMTP password 
			$mail->SMTPSecure = 'ssl';                  // Enable TLS encryption, `ssl` also accepted 
			$mail->Port = 465;                          // TCP port to connect to 
			 
			// Sender info 
			$mail->setFrom('alerts@rwrmis.com', 'RWR'); 
			$mail->addReplyTo('alerts@rwrmis.com', 'RWR'); 
			 
			// Add a recipient 
			$mail->addAddress($cmail); 
			 
			//$mail->addCC('cc@example.com'); 
			//$mail->addBCC('bcc@example.com'); 
			 
			// Set email format to HTML 
			$mail->isHTML(true); 
			 
			// Mail subject 
			$mail->Subject = 'RWR Password Reset'; 
			 
			// Mail body content 
			$bodyContent = '<h1>Password Reset</h1>'; 
			$bodyContent .= '<p>Dear User<br><br>Your password has been reset with RWR-MIS as  below:-<br>'.
			'Password : '.$tmp_pass.'<br><br>RWR Pvt Ltd</p>'; 
			$mail->Body    = $bodyContent; 
			 
			// Send email 
			if(!$mail->send()) {  
				$xmsg = 'Email with new password FAILEd to reach '.$cmail.' Mailer Error: '.$mail->ErrorInfo;
				$this->session->set_flashdata('alert_data', array(
							'type' => 'danger', 
							'details' => $xmsg
							)); 
			} else { 
					$xmsg = 'An email with password details has been sent to '.$cmail;
				$this->session->set_flashdata('alert_data', array(
							'type' => 'success', 
							'details' => $xmsg
							)); 
			}
			
	}
	
	}
	
}