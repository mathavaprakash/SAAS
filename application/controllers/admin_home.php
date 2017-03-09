<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_database');
		$this->load->model('model_home');
	}
	public function index()
	{
		//$this->output->cache(5);
		$this->load->view('admin/dashboard');
	}
	public function profile_view()
	{
		//$this->output->cache(10);
		$aa=$this->session->tempdata('email_id');
		$mail_id=$this->encryption->decrypt($aa);
		$user=$this->login_database->read_user_info($mail_id);
		$this->load->view('/admin/admin_profile');
	}
	public function category_view()
	{
		$this->load->view('admin/category');
	}
	public function sub_category_view()
	{
		$this->load->view('admin/sub_category');
	}
	public function read_view()
	{
		$this->load->view('admin/read_subject');
	}
	public function online_view()
	{
		$this->load->view('quiz/home');
	}
	public function change_password()
	{
		$this->form_validation->set_rules('oldpwd','old Password','required');
		$this->form_validation->set_rules('newpwd','new password','required|min_length[4]|max_length[30]');
		$this->form_validation->set_rules('conpwd','conform password','required|matches[newpwd]');
		if($this->form_validation->run())
		{ 
			$aa=$this->session->tempdata('email_id');
			$mail_id=$this->encryption->decrypt($aa);
			$user=$this->login_database->read_user_info($mail_id);
			$old_pwd=$this->input->post('oldpwd');
			if($old_pwd==$user['password'])
			{
				$data=array(
					'password'=>$this->input->post('newpwd'),
					'active'=>'10'
				);
				$result=$this->login_database->reset_password($mail_id,$data);
				if($result)
				{
					$this->session->set_flashdata("message","Password changed successfully.");
					redirect('admin_home/profile_view');
				}
				else
				{
					$this->session->set_flashdata("message","Unable to change your Password. Please try again.");
					redirect('admin_home/profile_view');
				}
			}
			else
			{
				$this->session->set_flashdata("message","Enter your current correct password.");
				redirect('admin_home/profile_view');
			}
		}
		else
		{
			$this->session->set_flashdata("message","Validation Error.");
			redirect('admin_home/profile_view');
		}
	}
	public function add_category()
	{
		$this->form_validation->set_rules('category','category','required');
		if($this->form_validation->run())
		{ 
			$category=array('title'=>$this->input->post('category'));
			
			$this->model_home->add_category($category);
			redirect('admin_home/category_view','refresh');
		}
		else
		{
			$this->session->set_flashdata("message",validation_errors());
			redirect('admin_home/category_view','refresh');
		}
	}
	public function add_sub_category()
	{
		$this->form_validation->set_rules('category','sub category','required');
		if($this->form_validation->run())
		{ 
			$str=$this->uri->segment(3);
			$category=array(
				'category_id'=>$this->uri->segment(3),
				'title'=>$this->input->post('category'),
				'contents'=>' aa'
				);
			$this->model_home->add_sub_category($category);
		}
		else
		{
			$this->session->set_flashdata("message",validation_errors());
		}
		redirect('admin_home/sub_category_view/' . $str ,'refresh');
	}
	public function edit_content()
	{
		$this->form_validation->set_rules('content','sub category','required');
		$this->form_validation->set_rules('title','title','required');
		if($this->form_validation->run())
		{
			$cat=$this->uri->segment(3);
			$id=$this->uri->segment(4);
			$content=array(				
				'title'=>$this->input->post('title'),				
				'contents'=>$this->input->post('content')				
				);
			
			$this->model_home->edit_sub_category($id,$content);
		}
		else
		{
			$this->session->set_flashdata("message",validation_errors());
		}
		redirect('admin_home/read_view/' . $cat . '/' . $id ,'refresh');		
	}
	public function delete_attachment()
	{
			$cat=$this->uri->segment(3);
			$id=$this->uri->segment(4);
			$content=array(				
				'attachment'=>''				
				);
			
			if($this->model_home->edit_sub_category($id,$content))
			{
				$this->session->set_flashdata("message",'attachment deleted successfully');
			}
			else
			{
				$this->session->set_flashdata("message",validation_errors());
			}
			redirect('admin_home/read_view/' . $cat . '/' . $id ,'refresh');		
	}
	public function delete_topic()
	{
			$cat=$this->uri->segment(3);			
			$id=$this->uri->segment(4);
			if($this->model_home->delete_sub_category($id))
			{
				$this->session->set_flashdata("message",'Topic deleted successfully');
			}
			else
			{
				$this->session->set_flashdata("message",validation_errors());
			}
			redirect('admin_home/sub_category_view/' . $cat ,'refresh');
	}
	public function delete_category()
	{
			$id=$this->uri->segment(3);	
			if($this->model_home->delete_category($id))
			{
				$this->session->set_flashdata("message",'category deleted successfully');
			}
			else
			{
				$this->session->set_flashdata("message",validation_errors());
			}
			redirect('admin_home/category_view');		
	}
	public function upload_picture()
	{
		/*$this->form_validation->set_rules('file','picture file','required');
		if($this->form_validation->run())
		{ */
			$aa=$this->session->tempdata('email_id');
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
                $config['max_width']= 1000;
                $config['max_height']= 1000;
                $config['overwrite']= TRUE;
                $config['file_ext_tolower']	= TRUE;
                $this->load->library('upload', $config);
				$this->upload->initialize($config);
				if ( $this->upload->do_upload('file'))
                {
                    $this->session->set_flashdata("message","Image Uploaded Successfully.");
					redirect('admin_home/profile_view');
				}
                else
                {
                    $err=$this->upload->display_errors();
					$this->session->set_flashdata("message",$err . "Image Upload Error" );
					//header("location:http://localhost/saas/index.php/admin_home/profile_view");
					redirect('admin_home/profile_view');
			
                }
			/*
			}
			else
			{
					$err=$this->image_lib->display_errors();
					$this->session->set_flashdata("message",$err . "Image Resize Error. Please Try again.");
					redirect('admin_home/profile_view');
			} */
	/*	}
		else
		{
			$this->session->set_flashdata("message","Validation Error.");
			redirect('admin_home/profile_view');
		} */
	}
	public function upload_file()
	{
			$path=$this->input->post('file');
			$config['upload_path']= 'attachment/';
			$config['allowed_types']= 'pdf|ppt|pptx|doc|docx';
			$config['max_size']= 3000;
			$config['overwrite']= TRUE;
			$config['file_ext_tolower']	= TRUE;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);
			$cat=$this->uri->segment(3);
			$id=$this->uri->segment(4);
				
			if ( $this->upload->do_upload('file'))
			{
				$content=array(				
					'attachment'=>$this->upload->data('file_name')				
					);
				
				$this->model_home->edit_sub_category($id,$content);
				$this->session->set_flashdata("message","File Uploaded Successfully.");
				redirect('admin_home/read_view/' . $cat . '/'. $id,'refresh');
			}
			else
			{
				$err=$this->upload->display_errors();
				$this->session->set_flashdata("message",$err . "File Upload Error" );
				redirect('admin_home/read_view/' . $cat . '/'. $id,'refresh');
		
			}
	}
	
	public function logout()
	{
		
		$this->session->set_flashdata('message','You are Logged Out Successfully');
		
		$this->session->unset_tempdata('email_id');
		redirect('user_authentication/index');
	}
}
