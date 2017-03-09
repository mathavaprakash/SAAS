<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Authentication extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_database');
		$key = bin2hex($this->encryption->create_key(16));
		$config['encryption_key'] = hex2bin($key);
	}
	public function index()
	{
		if($this->session->tempdata('mail_id'))
		{				
			redirect('user_home/index');
		}
		elseif($this->session->tempdata('email_id'))
		{
			redirect('admin_home/');
		}
		else
		{
			$this->load->view('login_form');
		}
		
	}
	public function user_login_process()
	{
		$rule = $this->config->item('login');
		$this->form_validation->set_rules($rule);
		if($this->form_validation->run())
		{
			
			$data=array(
				'user_id'=>$this->input->post('userid'),
				'password'=>$this->input->post('password')
			);
			$result=$this->login_database->login($data);
			if($result)
			{
				$userid=$this->input->post('userid');
				$aa=$this->encryption->encrypt($userid);
				
				$result=$this->login_database->read_user_info($userid);
				$act=$result['active'];
				if($act==1)	//user login
				{
					$this->session->set_tempdata('mail_id',$aa,TIMEOUT);
					redirect('user_home/index');
				}
				else if($act==10)		//admin login
				{
					$this->session->set_tempdata('email_id',$aa,TIMEOUT);
					redirect('admin_home/');
				}
				else if($act==11)		//admin login
				{
					$this->session->set_flashdata('message','Must Change Your Password First');
					$this->session->set_tempdata('email_id',$aa,TIMEOUT);	
					redirect('admin_home/profile_view');
				}
				else if($act==2)		//user login with profile view to change password.
				{
					$this->session->set_flashdata('message','Must Change Your Password First');
					$this->session->set_tempdata('mail_id',$aa,TIMEOUT);
					redirect('user_home/profile_view');
				}
				else	//invalid user
				{
					$this->session->set_flashdata('message','Your Account Blocked by Administrator');
					redirect('');
				}
			}
			else	//invalid user
			{
				$this->session->set_flashdata('message','Invalid Mail ID or Password');
				redirect('');
			}
		}
		else	//invalid user
		{
			$this->session->set_flashdata('message','Validation Error'. validation_errors());
			redirect('');
		}
	}
	public function dashboard()
	{
		$this->load->view('user/home');
	}
	public function registration_form_view()
	{
		$this->load->view('registration_form');
	}
	public function user_registration_process()
	{
		$rule = $this->config->item('signup');
		$this->form_validation->set_rules($rule);
		if($this->form_validation->run()===FALSE)
		{
			
			//$this->load->view('login_form');
			$this->session->set_flashdata('message','Validation Error'. validation_errors());				
			redirect('user_authentication/registration_form_view');
		}
		else
		{
			$data=array(
				'mail_id'=>$this->input->post('mail_id'),
				'first_name'=>$this->input->post('first_name'),
				'last_name'=>$this->input->post('last_name'),
				'gender'=>$this->input->post('gender'),
				'dob'=>$this->input->post('dob'),
				'phone'=>$this->input->post('phone')
			);
			$result=$this->login_database->register($data);
			if($result)
			{
				$mail_id=$this->input->post('mail_id');
				$data=array(
					'password'=>'1234',
					'active'=>'2'
				);
				$result=$this->login_database->reset_password($mail_id,$data);
				if($result)
				{
					$this->session->set_flashdata("message","OTP(One Time Password) sent to your Registered Email.");
					redirect('');
				}
				else
				{
					$this->session->set_flashdata("message","Unable to Reset your Password. Please try again.");
					redirect('');
				}
				$this->session->set_flashdata('message','Your Account Registered Successfully. OTP Send to your registered mail.');						
			
			}
			else
			{
				$this->session->set_flashdata('message','Unable to Register. Please try again later.');						
			}
			redirect('');
		}
	}
	public function validate_date($str)
	{
		$today=now();
		$date=strtotime($str);
		if ($today<$date)
		{
				$this->form_validation->set_message('validate_date', 'The {field} invalid. please enter future date & time');
				return FALSE;
		}
		else
		{
				return TRUE;
		}
	}
	public function forget_form_view()
	{
		$this->load->view('forget_password');
	}
	public function forget_password_process()
	{
		$this->form_validation->set_rules('userid','user name','required|valid_email');
		if($this->form_validation->run())
		{ 
			$mail_id=$this->input->post('userid');
			$result=$this->login_database->read_user_info($mail_id);
			if($result)
			{
				if($result['active']===1 OR $result['active']===2)
				{
					$data=array(
						'password'=>'1111',
						'active'=>'2'
					);
				}
				else if($result['active']===10 OR $result['active']===11)
				{
					$data=array(
						'password'=>'1111',
						'active'=>'11'
					);
				}
				else
				{
					$this->session->set_flashdata("message","Unable to Reset your Password. Please try again.");
					redirect('');
				}
				
				$result=$this->login_database->reset_password($mail_id,$data);
				if($result)
				{
					/*
					$from_email = "admin@saas.com";
					$to_email = $this->input->post('userid');
					//Load email library
					$this->load->library('email');
					$this->email->from($from_email, 'Admin, SAAS');
					$this->email->to($to_email);
					$this->email->subject('Email Test');
					$this->email->message('Testing the email class.');
					//Send mail
					
					if($this->email->send())
					{
						$this->session->set_flashdata("message","OTP(One Time Password) sent to your Registered Email Successfully.");
					}
					else
					{
						$this->session->set_flashdata("message","Error in sending Email.");
					} */
					//$this->load->view('home');
					
					$this->session->set_flashdata("message","OTP(One Time Password) sent to your Registered Email.");
					//header("location:http://localhost/saas/");
					redirect('');
				}
				else
				{
					$this->session->set_flashdata("message","Unable to Reset your Password. Please try again.");
					//header("location:http://localhost/saas/");
					redirect('');
				}
			}
			else
			{
				$msg="Mail ID not found in our database. Need New Account?"; 
				$msg.=  '<a href="index.php/user_authentication/registration_form_view"> Click Here!</a>'; 
				$this->session->set_flashdata('message',$msg);
				//$this->load->view('login_form');
				//header("location:http://localhost/saas/");
				redirect('');
			}
		}
		else
		{
			
			$this->session->set_flashdata('message','Validation Error'. validation_errors());
			//$this->load->view('login_form');
			//header("location:http://localhost/saas/");
			redirect('');
		}
	}
	public function send_mail()
	{
		$from_email = "admin@saas.com";
		$to_email = $this->input->post('userid');
		//Load email library
		$this->load->library('email');
		$this->email->from($from_email, 'Admin, SAAS');
		$this->email->to($to_email);
		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');
		//Send mail
		if($this->email->send())
		{
			$this->session->set_flashdata("message","Email sent successfully.");
		}
		else
		{
			$this->session->set_flashdata("message","Error in sending Email.");
		}
		
	}
}
