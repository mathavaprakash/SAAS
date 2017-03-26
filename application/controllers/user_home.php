<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		//$this->output->cache(5);
		$this->load->view('user/home');
		
	}
	public function profile_view()
	{
		//$this->output->cache(10);
		$aa=$this->session->tempdata('mail_id');
		$mail_id=$this->encryption->decrypt($aa);
		$user=$this->login_database->read_user_info($mail_id);
		$this->load->view('user/profile');
		
	}
	
	public function category_view()
	{
		$this->load->view('user/category');
	}
	public function error()
	{
		$this->load->view('template/404');
	}
	public function sub_category_view()
	{
		$this->load->view('user/sub_category');
	}
	public function read_view()
	{
		$this->load->view('user/read');
	}
	public function create()
	{
		$this->load->view('user/create_chapter');
	}
	
	public function leader_board()
	{
		$this->load->view('online_test/leader_board');
	}
	public function view_student_profile()
	{
		$this->load->view('user/student_profile');
	}
	
	public function change_password()
	{
		$this->form_validation->set_rules('oldpwd','old Password','required');
		$this->form_validation->set_rules('newpwd','new password','required|min_length[4]|max_length[30]');
		$this->form_validation->set_rules('conpwd','conform password','required|matches[newpwd]');
		if($this->form_validation->run())
		{ 
			$aa=$this->session->tempdata('mail_id');
			$mail_id=$this->encryption->decrypt($aa);
			$user=$this->login_database->read_user_info($mail_id);
			$old_pwd=$this->input->post('oldpwd');
			if($old_pwd===$user['password'])
			{
				$data=array(
					'password'=>$old_pwd=$this->input->post('newpwd'),
					'active'=>'1'
				);
				$result=$this->login_database->reset_password($mail_id,$data);
				if($result)
				{
					$this->session->set_flashdata("message","Password changed successfully.");
					redirect('user_home/profile_view');
				}
				else
				{
					$this->session->set_flashdata("message","Unable to change your Password. Please try again.");
					redirect('user_home/profile_view');
				}
			}
			else
			{
				$this->session->set_flashdata("message","Enter your current correct password.");
				redirect('user_home/profile_view');
			}
		}
		else
		{
			$this->session->set_flashdata("message","Validation Error.");
			redirect('user_home/profile_view');
		}
	}
	public function update_profile()
	{
		$this->form_validation->set_rules('first_name','First Name','alpha|required|trim|min_length[2]|max_length[30]');
		$this->form_validation->set_rules('last_name','Last Name','alpha|required|trim|max_length[30]');
		//$this->form_validation->set_rules('gender','gender','required');
		//$this->form_validation->set_rules('dob','Date of birth','required');
		$this->form_validation->set_rules('phone','phone number','required');
		if($this->form_validation->run()==TRUE)
		{ 
			$aa=$this->session->tempdata('mail_id');
			$mail_id=$this->encryption->decrypt($aa);
			$data=array(
				'first_name'=>$this->input->post('first_name'),
				'last_name'=>$this->input->post('last_name'),
				//'gender'=>$this->input->post('gender'),
				//'dob'=>$this->input->post('dob'),
				'phone'=>$this->input->post('phone')
			);
			$result=$this->login_database->reset_password($mail_id,$data);
			if($result)
			{
				$this->session->set_flashdata("message","Your profile was updated successfully.");
				redirect('user_home/profile_view');
			}
			else
			{
				$this->session->set_flashdata("message","Unable to update your profile. Please try again.");
				redirect('user_home/profile_view');
			}
			
		}
		else
		{
			$this->session->set_flashdata("message","Validation Error.");
			redirect('user_home/profile_view');
		}
	}
	public function update_profile1()
	{
		$this->form_validation->set_rules('first_name','First Name','alpha|required|trim|min_length[2]|max_length[30]');
		$this->form_validation->set_rules('last_name','Last Name','alpha|required|trim|max_length[30]');
		//$this->form_validation->set_rules('gender','gender','required');
		//$this->form_validation->set_rules('dob','Date of birth','required');
		$this->form_validation->set_rules('phone','phone number','required');
		if($this->form_validation->run()==TRUE)
		{ 
			$aa=$this->session->tempdata('email_id');
			$mail_id=$this->encryption->decrypt($aa);
			$data=array(
				'first_name'=>$this->input->post('first_name'),
				'last_name'=>$this->input->post('last_name'),
				//'gender'=>$this->input->post('gender'),
				//'dob'=>$this->input->post('dob'),
				'phone'=>$this->input->post('phone')
			);
			$result=$this->login_database->reset_password($mail_id,$data);
			if($result)
			{
				$this->session->set_flashdata("message","Your profile was updated successfully.");
				redirect('admin_home/profile_view');
			}
			else
			{
				$this->session->set_flashdata("message","Unable to update your profile. Please try again.");
				redirect('admin_home/profile_view');
			}
			
		}
		else
		{
			$this->session->set_flashdata("message","Validation Error.");
			redirect('admin_home/profile_view');
		}
	}
	public function upload_picture()
	{
			$aa=$this->session->tempdata('mail_id');
			$mail_id=$this->encryption->decrypt($aa);
			$path=$this->input->post('file');
		/*
			$config['source_image'] = $path;
			$config['image_library'] = 'gd2';
			$config['create_thumb'] = TRUE;
			$config['maintain_ratio'] = TRUE;
			$config['width']         = 130;
			$config['height']       = 150;
			$this->load->library('image_lib', $config);
			$this->image_lib->initialize($config);
			if ( $this->image_lib->resize())
			{ */
				$upload_path=  'assets/profile_pics/'; 
				$config['upload_path']= $upload_path;
				
                $config['file_name']= $mail_id . '.jpg';
                $config['allowed_types']= 'jpeg|jpg|png';
                $config['max_size']= 1000;
                $config['max_width']= 1300;
                $config['max_height']= 1500;
                $config['overwrite']= TRUE;
                $config['file_ext_tolower']	= TRUE;
                $this->load->library('upload', $config);
				$this->upload->initialize($config);
				if ( $this->upload->do_upload('file'))
                {
                    $this->session->set_flashdata("message","Image Uploaded Successfully.");
					redirect('user_home/profile_view');
				}
                else
                {
                    $err=$this->upload->display_errors();
					$this->session->set_flashdata("message",$err . "Image Upload Error" );
					//header("location:http://localhost/saas/index.php/user_home/profile_view");
					redirect('user_home/profile_view','refresh');
			
                }
			/*
			}
			else
			{
					$err=$this->image_lib->display_errors();
					$this->session->set_flashdata("message",$err . "Image Resize Error. Please Try again.");
					redirect('user_home/profile_view');
			} */
	
	}
	public function add_chapter()
	{
		if(($this->uri->segment(3)!=NULL))
		{
			$this->load->view('template/404');
		}
		$mail_id=$this->login_database->encryptor('decrypt',$this->uri->segment(3));
		$user=$this->login_database->read_user_info($mail_id);
		if(!isset($user))
		{
			$this->session->set_flashdata("message","Invalid Student ID");
			$this->load->view('template/404');
		}
		$status=($user['active']==10)?1:0;
			
		$this->form_validation->set_rules('category','category','required');
		$this->form_validation->set_rules('title','chapter title','required');
		//$this->form_validation->set_rules('content','content','required');
		$path="";
		$upath='';
		if($this->form_validation->run())
		{ 
			//print '<script>alert("valid");</script>';
			if(!empty($_FILES['file']['name']))
			{
				//print '<script>alert("file");</script>';
				//$path=$this->input->post('file');
				//$path=$_FILES['file']['name'];
				$config['upload_path']= 'attachment/';
				//$config['file_name'] = $path;               
				$config['allowed_types']= 'pdf|ppt|pptx|doc|docx';
				$config['max_size']= 5000;
				$config['overwrite']= TRUE;
				$config['file_ext_tolower']	= TRUE;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if($this->upload->do_upload('file'))
				{
					
                    $uploadData = $this->upload->data();
                    $upath = $uploadData['file_name'];
                }
				else
				{
                    $upath = '';
					$err=$this->upload->display_errors();
					$this->session->set_flashdata("message",$err . "File Upload Error" );
					if($status==1)
					{
						redirect('admin_home/category_view/','refresh');
					}
					else
					{
						redirect('user_home/category_view/','refresh');
					}
                }
            }
			else
			{
                $upath = '';
            }
            
			$category=array(
				'category_id'=>$this->input->post('category'),
				'mail_id'=>$mail_id,
				'title'=>$this->input->post('title'),
				'contents'=>$this->input->post('content'),
				'attachment'=>$upath,
				'status'=>$status
				);
			$result=$this->model_home->add_sub_category($category);
			if($result==TRUE)
			{
				$this->session->set_flashdata("message","Your Chapter Added Successfully and waiting for Admin's Approval. Once Admin Approved, then Your Chapter will vissible public.");
				
			}
			else
			{
				$this->session->set_flashdata("message","smething went Wrong. Please try again");
			}
			//redirect('user_home/category_view/','refresh');
		}
		else
		{
			$this->session->set_flashdata("message",validation_errors());
		}
		
			
		if($status==1)
		{
			
			redirect('admin_home/category_view/','refresh');
		}
		else
		{
			redirect('user_home/category_view/','refresh');
		}
		
	}
	public function approve_chapter()
	{
		if(($this->uri->segment(3)!=NULL))
		{
			$this->load->view('template/404');
		}
		$sub_id=$this->login_database->encryptor('decrypt',$this->uri->segment(3));
		$content=$this->model_home->content($sub_id);
		if(! isset($content))
		{
			$this->session->set_flashdata("message","smething went Wrong. Please try again");
			redirect('user_home/error','refresh');
		}
		$mail_id=$content['mail_id'];
		$data=array(
			'status'=>1
			);
		$result=$this->model_home->edit_sub_category($sub_id,$data);
		if($result==TRUE)
		{
			$result=$this->model_quiz->add_points($mail_id,300);
			
			$this->session->set_flashdata("message","Chapter vissible public.");
			
		}
		else
		{
			$this->session->set_flashdata("message","smething went Wrong. Please try again");
		}
		redirect('admin_home/category_view/','refresh');
	}
	public function delete_chapter()
	{
		if(($this->uri->segment(3)!=NULL))
		{
			$this->load->view('template/404');
		}
		$sub_id=$this->login_database->encryptor('decrypt',$this->uri->segment(3));
		$data=array(
			'status'=>10
			);
		$result=$this->model_home->edit_sub_category($sub_id,$data);
		if($result==TRUE)
		{
			$this->session->set_flashdata("message","Chapter deleted Successfully.");
			
		}
		else
		{
			$this->session->set_flashdata("message","smething went Wrong. Please try again");
		}
		redirect('admin_home/category_view/','refresh');
	}
	public function logout()
	{
		//$this->session->set_flashdata('message','You are Logged Out Successfully');		
		$this->session->unset_tempdata('mail_id');
		
		//redirect('user_authentication/index');
		$this->load->view('template/logout_message');
	}
	
	public function print_report()
	{
		
	}
	
}