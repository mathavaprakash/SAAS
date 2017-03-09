<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Online_Test extends CI_Controller
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
		$this->load->view('online_test/test_home');
		
	}
	public function detail_view()
	{
		$this->load->view('online_test/details');		
	}
	public function success()
	{
		$this->load->view('online_test/submitted');		
	}
	public function live()
	{
		$this->load->view('online_test/online_test');		
	}
	public function take_test()
	{
		
		$test_id=$this->uri->segment(3);
		if($this->session->tempdata('mail_id') && isset($test_id))
		{
			$aa=$this->session->tempdata('mail_id');
			$mail_id=$this->encryption->decrypt($aa);
			$status=$this->model_quiz->get_user_test_status($test_id,$mail_id);
			if($status=='tab1')
			{
				$data=array(
					'test_id'=>$test_id,
					'mail_id'=>$mail_id,
					'start_date'=>date('Y-m-d\TH:i:s',now()),
					'end_date'=>'-',
					'marks'=>0,
					'status'=>'1'
				);			
				$result=$this->model_quiz->take_test($data);
				if($result)
				{
					$ticket=$this->model_quiz->check_attended($test_id,$mail_id);
					if(isset($ticket))
					{
						$ticket_details=array(
							'ticket_id'=>$this->encryption->encrypt($ticket['ticket_id']),
							'mail'=>$this->encryption->encrypt($mail_id)
						);
						$test=$this->model_quiz->get_test_details($test_id);
						$timeout=0;
						$duration=$test['duration'];
						$timeout=($duration * 60);
						//$this->session->unset_tempdata('mail_id');
						$this->session->set_tempdata($ticket_details,NULL,$timeout);
						redirect('online_test/live');
						//echo "<script>window.close();</script>";
						//echo '<script>window.open("<?= site_url() /online_test/live","SAAS Online Test","width=1000,height=700,0,status=0,scrollbars=1");</script>';
					}
					else
					{
						$this->session->set_flashdata("message","ticket not found");
						redirect("user_home/error");
					}
					
				}
				else
				{
					$this->session->set_flashdata("message","data error");
					redirect("user_home/error");
				}
			}
			else
			{
				$this->session->set_flashdata("message","Test not Available");
				redirect("user_home/error");
			}
		}
		else
		{
			redirect("user_home/error");
		}
	}
	public function submit_test()
	{
		$ticket_id=$this->uri->segment(3);
		$ticket=$this->model_quiz->get_ticket_details($ticket_id);
		if(!isset($ticket))
		{
			$this->session->set_flashdata("message","ticket not found");
			redirect("user_home/error");
		}
		if($ticket['status']!=1)
		{
			$this->session->set_flashdata("message","ticket not found");
			redirect("user_home/error");
		}
		$test_id=$ticket['test_id'];
	
		$questions=$this->model_quiz->get_questions($test_id);
		if(!isset($questions))
		{
			$this->session->set_flashdata("message","questions not found");
			redirect("user_home/error");
		} 
		$marks=0;
		$ans_array=array();
		foreach($questions as $q)
		{
			$q_id=$q['question_id'];
			$answer=$q['answer'];
			$user_answer=$this->input->post($q_id);
			if(isset($user_answer))
			{
				$ans_array[]= "$q_id@$user_answer";
			}
			if($user_answer==$answer)
			{
				$marks=$marks+1;
			}
		}
		if(count($ans_array)>0)
		{
			$student_answers=implode('*',$ans_array);
		}
		else
		{
			$student_answers="";
		}
		$points=$marks*100;
		$data=array(
			'end_date'=>date('Y-m-d\TH:i:s',now()),
			'marks'=>$points,
			'status'=>'2',
			'answers'=>$student_answers
		);
		$result=$this->model_quiz->submit_test($ticket_id,$data);
		if($result)
		{
			$points=$marks*100;
			$result=$this->model_quiz->add_points($ticket['mail_id'],$points);
			$this->session->set_flashdata("message","You Are finished Your Test Successfully");
			//echo "<script>window.close();</script>";
			redirect("online_test/success/$test_id");
		}
		else
		{
			$this->session->set_flashdata("message","Your Test UnSuccessfull");
			redirect("user_home/error");
		}
		
	}
	
}