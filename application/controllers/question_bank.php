<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Question_bank extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_qbank');
		$this->load->model('model_quiz');
	}
	
	public function home()
	{
		$this->load->view('qbank/qbank_home');
	}
	public function view()
	{
		$this->load->view('qbank/view_qbank');
	}
	public function edit()
	{
		$this->load->view('qbank/edit_question');
	}
	public function delete_question()
	{
		$sub_id=$this->uri->segment(3);
		$q_id=$this->uri->segment(4);
		if(!isset($q_id))
		{
			redirect('template/404');
		}
		$result=$this->model_qbank->delete_question($q_id);
		redirect("question_bank/view/$sub_id");
		
	}
	public function add_question()
	{
		$test_id=$this->uri->segment(3);
		if(!isset($test_id))
		{
			redirect('template/404');
		}
		$sub_id=$this->login_database->encryptor('decrypt',$test_id);
		
			$data=array(
				'sub_id'=>$sub_id,
				'question'=>$this->input->post("question"),
				'option_a'=>$this->input->post('option_a'),
				'option_b'=>$this->input->post('option_b'),
				'option_c'=>$this->input->post('option_c'),
				'option_d'=>$this->input->post('option_d'),
				'answer'=>$this->input->post('answer'),
				'explanation'=>$this->input->post('explanation')
			);			
			
		$result=$this->model_qbank->add_question($data);
		if($result==TRUE)
		{
			$this->session->set_flashdata("message","one question added successfully");
		}
		else
		{
			$this->session->set_flashdata("message","Error Occured");
			
		}
		redirect("question_bank/home/$test_id");	
	}
	public function edit_chapter_title()
	{
		$this->form_validation->set_rules('title','title','required');
		if($this->form_validation->run())
		{
			$sub_id=$this->uri->segment(3);
			$content=array(				
				'title'=>$this->input->post('title')			
				);
			
			$this->model_home->edit_sub_category($sub_id,$content);
		}
		else
		{
			$this->session->set_flashdata("message",validation_errors());
		}
		redirect('admin_home/read_view/'  . $sub_id ,'refresh');
	}	
	public function delete_attachment()
	{
		$sub_id=$this->uri->segment(3);
			$content=array(				
				'attachment'=>''			
				);
			
			$this->model_home->edit_sub_category($sub_id,$content);
		
		redirect('admin_home/read_view/'  . $sub_id ,'refresh');
	}	
	public function add_attachment()
	{
		$sub_id=$this->uri->segment(3);
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
				redirect("admin_home/read_view/$sub_id",'refresh');
			}
		}
		else
		{
			$upath = '';
		}
		
			$content=array(				
				'attachment'=>$upath			
				);
			
			$this->model_home->edit_sub_category($sub_id,$content);
		
		redirect('admin_home/read_view/'  . $sub_id ,'refresh');
		
	}	
	public function edit_question()
	{
		$sub_id=$this->uri->segment(3);
		$qid=$this->uri->segment(4);
		if(!(isset($qid) && isset($sub_id)) )
		{
			redirect('template/404');
		}
		
			$data=array(
				'question'=>$this->input->post("question"),
				'option_a'=>$this->input->post('option_a'),
				'option_b'=>$this->input->post('option_b'),
				'option_c'=>$this->input->post('option_c'),
				'option_d'=>$this->input->post('option_d'),
				'answer'=>$this->input->post('answer'),
				'explanation'=>$this->input->post('explanation')
			);			
			
		$result=$this->model_qbank->update_question($qid,$data);
		if($result==TRUE)
		{
			$this->session->set_flashdata("message","question updated successfully");
		}
		else
		{
			$this->session->set_flashdata("message","Error Occured");
			
		}
		redirect("question_bank/view/$sub_id");	
	}
	
}