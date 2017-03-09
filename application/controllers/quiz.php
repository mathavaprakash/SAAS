<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Quiz extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_database');
		$this->load->model('model_quiz');	
		$this->load->model('model_home');		
	}
	public function index()
	{
		//$this->output->cache(5);
		$this->load->view('quiz/home');
		
	}
	public function test_home()
	{
		//$this->output->cache(5);
		$this->load->view('online_test/test_home');
		
	}
	public function detail_view()
	{
		$this->load->view('quiz/details');		
	}
	public function add_questions()
	{
		$this->load->view('quiz/add_questions');		
	}
	public function edit_question()
	{
		$this->load->view('quiz/edit_questions');		
	}
	
	public function add_test()
	{
		$rule = $this->config->item('new_test');
		$this->form_validation->set_rules($rule);
		if($this->form_validation->run())
		{
			$stdate=$this->input->post('start_date');
			$enddate=$this->input->post('end_date');
			$duration=$this->input->post('duration');
			$time_now=strtotime("now");
			$time_st=strtotime($stdate);
			$time_end=strtotime($enddate);
			$time_st=$time_st+($duration*60);
			if($time_st<$time_now OR $time_end<$time_st)
			{
				$this->session->set_flashdata("message","You are Entered wrong Dates. Please correct it.");
				redirect('quiz/index');
			}
			else
			{
				$data=array(
					'title'=>$this->input->post('title'),
					'category_id'=>$this->input->post('category'),
					'start_date'=>$stdate,
					'end_date'=>$enddate,
					'duration'=>$duration,
					'no_of_questions'=>$this->input->post('no_of_questions'),
					'active'=>2
				);
				$result=$this->model_quiz->new_test($data);
				if($result)
				{
					$this->session->set_flashdata("message","New Test Added Successfully. Now You Can Add Questions for this Test.");
					redirect('quiz/index');
				}
				else
				{
					$this->session->set_flashdata("message","Error Occured. Try Again");
					redirect('quiz/index');
				}
			}
			
		}
		else
		{
			$this->session->set_flashdata("message",validation_errors());
			redirect('quiz/index');
		}
	}
	public function edit_test_title($test_id)
	{
		$data=array('title'=>$this->input->post('title'));
		$result=$this->model_quiz->update_test($test_id,$data);
		if($result)
		{
			$this->session->set_flashdata("message","Title Updated Successfully.");
			redirect("quiz/detail_view/$test_id");
		}
		else
		{
			$this->session->set_flashdata("message","Error Occured. Try Again");
			redirect("quiz/detail_view/$test_id");
		}
	}
	public function edit_test_duration($test_id)
	{
		$data=array('duration'=>$this->input->post('duration'));
		$result=$this->model_quiz->update_test($test_id,$data);
		if($result)
		{
			$this->session->set_flashdata("message","Duration Updated Successfully.");
			redirect("quiz/detail_view/$test_id");
		}
		else
		{
			$this->session->set_flashdata("message","Error Occured. Try Again");
			redirect("quiz/detail_view/$test_id");
		}
	}
	public function edit_test_st_date($test_id)
	{
		$data=array('start_date'=>$this->input->post('start_date'));
		$result=$this->model_quiz->update_test($test_id,$data);
		if($result)
		{
			$this->session->set_flashdata("message","Start Time Updated Successfully.");
			redirect("quiz/detail_view/$test_id");
		}
		else
		{
			$this->session->set_flashdata("message","Error Occured. Try Again");
			redirect("quiz/detail_view/$test_id");
		}
	}
	public function edit_test_end_date($test_id)
	{
		$data=array('end_date'=>$this->input->post('end_date'));
		$result=$this->model_quiz->update_test($test_id,$data);
		if($result)
		{
			$this->session->set_flashdata("message","End Time Updated Successfully.");
			redirect("quiz/detail_view/$test_id");
		}
		else
		{
			$this->session->set_flashdata("message","Error Occured. Try Again");
			redirect("quiz/detail_view/$test_id");
		}
	}
	public function close_test($test_id)
	{
		$data=array('active'=>3);
		$result=$this->model_quiz->update_test($test_id,$data);
		if($result)
		{
			$this->session->set_flashdata("message","Test Closed Successfully.");
			redirect("quiz/detail_view/$test_id");
		}
		else
		{
			$this->session->set_flashdata("message","Error Occured. Try Again");
			redirect("quiz/detail_view/$test_id");
		}
	}
	public function delete_test($test_id)
	{
		$result=$this->model_quiz->delete_test($test_id);
		redirect("quiz/index");
		
	}
	
	
	public function save_questions()
	{
		$test_id=$this->uri->segment(3);
		$no_of_questions=$this->uri->segment(4);		
		for($key=0;$key<$no_of_questions;$key++)
		{
			$data=array(
				'test_id'=>$test_id,
				'question'=>$this->input->post("question".$key),
				'option_a'=>$this->input->post('option_a'.$key),
				'option_b'=>$this->input->post('option_b'.$key),
				'option_c'=>$this->input->post('option_c'.$key),
				'option_d'=>$this->input->post('option_d'.$key),
				'answer'=>$this->input->post('answer'.$key)
			);			
			$this->model_quiz->new_questions($data);
		}
		$result=$this->model_quiz->validate_question($test_id);
		$data=array(
				'active'=>1);
		$this->model_quiz->update_test($test_id,$data);
		redirect("quiz/detail_view/$test_id");			
	}
	public function update_questions()
	{
		$test_id=$this->uri->segment(3);
		$no_of_questions=$this->uri->segment(4);
		$questions=$this->model_quiz->get_questions($test_id);		
		foreach ($questions as $q)
		{
			$key=$q['question_id'];
			$data=array(
				'question'=>$this->input->post("question".$key),
				'option_a'=>$this->input->post('option_a'.$key),
				'option_b'=>$this->input->post('option_b'.$key),
				'option_c'=>$this->input->post('option_c'.$key),
				'option_d'=>$this->input->post('option_d'.$key),
				'answer'=>$this->input->post('answer'.$key)
			);			
			$this->model_quiz->update_questions($key,$data);
		}
		redirect("quiz/detail_view/$test_id");			
	}
	public function validate_date($str)
	{
		$today=now();
		$date=strtotime($str);
		if ($today>$date)
		{
				$this->form_validation->set_message('validate_date', 'The {field} invalid. please enter future date & time');
				return FALSE;
		}
		else
		{
				return TRUE;
		}
	}
}