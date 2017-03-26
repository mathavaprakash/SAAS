<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discussions extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_discussion');
	}
	public function index()
	{
		
		
	}
	public function add_discussion()
	{
		$aa=$this->session->tempdata('mail_id');
		$mail_id=$this->encryption->decrypt($aa);
		$this->form_validation->set_rules('comments','comments','required');
		if($this->form_validation->run())
		{ 
			$str=$this->uri->segment(3);
			$comment=array(
				'creator_id'=>$mail_id,
				'sub_id'=>$str, //$this->uri->segment(3),
				'state'=>1,
				'question'=>$this->input->post('comments')
				);
			$this->model_discussion->add_discussion($comment);
		}
		else
		{
			$this->session->set_flashdata("message",validation_errors());
		}
		redirect('user_home/read_view/'.$str,'refresh');
	}
	public function add_comment()
	{
		$aa=$this->session->tempdata('mail_id');
		$mail_id=$this->encryption->decrypt($aa);
		$this->form_validation->set_rules('reply','reply','required');
		if($this->form_validation->run())
		{ 	
			$id=$this->uri->segment(3);
			$str=$this->uri->segment(4);
			$reply=array(
				'discussion_id'=>$str,
				'mail_id'=>$mail_id,
				'comment'=>$this->input->post('reply'),
				'state'=>1
				);
			$this->model_discussion->add_comment($reply);
		}
		else
		{
			$this->session->set_flashdata("message",validation_errors());
		}
		redirect('user_home/read_view/'.$id,'refresh');
	}
	public function delete_comment()
	{
			$id=$this->uri->segment(4);	
			if($this->model_discussion->delete_comment($id))
			{
				$this->session->set_flashdata("message",'Comment deleted successfully');
			}
			else
			{
				$this->session->set_flashdata("message",validation_errors());
			}
			$str=$this->uri->segment(3);
			redirect('user_home/read_view/'.$str,'refresh');			
	}
	public function delete_discussion()
	{
			$id=$this->uri->segment(4);	
			if($this->model_discussion->delete_discussion($id))
			{
				$this->session->set_flashdata("message",'Discussion deleted successfully');
			}
			else
			{
				$this->session->set_flashdata("message",validation_errors());
			}
			$str=$this->uri->segment(3);
			redirect('user_home/read_view/'.$str,'refresh');			
					
	}
	public function add_discussion_admin()
	{
		$aa=$this->session->tempdata('email_id');
		$mail_id=$this->encryption->decrypt($aa);
		$this->form_validation->set_rules('comments','comments','required');
		if($this->form_validation->run())
		{ 
			$str=$this->uri->segment(4);
			$comment=array(
				'creator_id'=>$mail_id,
				'sub_id'=>$str, //$this->uri->segment(3),
				'state'=>1,
				'question'=>$this->input->post('comments')
				);
			$this->model_discussion->add_discussion($comment);
		}
		else
		{
			$this->session->set_flashdata("message",validation_errors());
		}
		$cat=$this->uri->segment(3);
		redirect('admin_home/read_view/'.$str,'refresh');
	}
	public function add_comment_admin()
	{
		$aa=$this->session->tempdata('email_id');
		$mail_id=$this->encryption->decrypt($aa);
		$this->form_validation->set_rules('reply','reply','required');
		if($this->form_validation->run())
		{ 	
			$id=$this->uri->segment(4);
			$str=$this->uri->segment(5);
			$reply=array(
				'discussion_id'=>$str,
				'mail_id'=>$mail_id,
				'comment'=>$this->input->post('reply'),
				'state'=>1
				);
			$this->model_discussion->add_comment($reply);
		}
		else
		{
			$this->session->set_flashdata("message",validation_errors());
		}
		$cat=$this->uri->segment(3);
		redirect('admin_home/read_view/'.$id,'refresh');
	}
	public function delete_comment_admin()
	{
			$id=$this->uri->segment(5);	
			if($this->model_discussion->delete_comment($id))
			{
				$this->session->set_flashdata("message",'Comment deleted successfully');
			}
			else
			{
				$this->session->set_flashdata("message",validation_errors());
			}
			$str=$this->uri->segment(4);
			$cat=$this->uri->segment(3);
			redirect('admin_home/read_view/'.$str,'refresh');			
	}
	public function delete_discussion_admin()
	{
			$id=$this->uri->segment(5);	
			if($this->model_discussion->delete_discussion($id))
			{
				$this->session->set_flashdata("message",'Discussion deleted successfully');
			}
			else
			{
				$this->session->set_flashdata("message",validation_errors());
			}
			$str=$this->uri->segment(4);
			$cat=$this->uri->segment(3);
			redirect('admin_home/read_view/'.$str,'refresh');			
	}
	
}