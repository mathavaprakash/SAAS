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
	public function create()
	{
		$this->load->view('online_test/create_test');		
	}
	public function success()
	{
		$this->load->view('online_test/submitted');		
	}
	public function live()
	{
		$this->load->view('online_test/online_test');		
	}
	public function add_test()
	{
		$time_stamp="";
		
		$mail_id=$this->login_database->encryptor('decrypt',$this->uri->segment(3));
		$user=$this->login_database->read_user_info($mail_id);
		if(!(isset($user)&& isset($mail_id)))
		{
			$this->session->set_flashdata("message","Invalid Student ID");
			$this->load->view('template/404');
		}
		$status=($user['active']==10)?1:0;
		
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
				$time_stamp=now();
				$data=array(
					'title'=>$this->input->post('title'),
					'mail_id'=>$mail_id,
					
					'category_id'=>$this->input->post('category'),
					'start_date'=>$stdate,
					'end_date'=>$enddate,
					'duration'=>$duration,
					'no_of_questions'=>$this->input->post('no_of_questions'),
					'active'=>2,
					'create_time'=>$time_stamp
				);
				$result=$this->model_quiz->new_test($data);
				if($result)
				{
					$test=$this->model_quiz->get_temp_test_id($time_stamp);
					if(isset($test))
					{
						$test_id=$this->login_database->encryptor('encrypt',$test['test_id']);
						redirect("online_test/create_questions/$test_id",'refresh');
					}
					else
					{
						$this->session->set_flashdata("message","Error Occured. Try Again");
						
					}
					//$this->session->set_flashdata("message","New Test Added Successfully. Now You Can Add Questions for this Test.");
					
				}
				else
				{
					$this->session->set_flashdata("message","Error Occured. Try Again");
					
				}
				
			}
			
		}
		else
		{
			$this->session->set_flashdata("message",validation_errors());
			
		}
		if($status==1)
		{
			
			redirect('quiz/index/','refresh');
		}
		else
		{
			redirect('online_test/index/','refresh');
		}
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
	public function create_questions()
	{
		$this->load->view('online_test/add_questions');
	}
	public function save_questions()
	{
		$mail_id=$this->uri->segment(3);
		$test_id=$this->uri->segment(4);
		if(!(isset($mail_id) && isset($test_id)))
		{
			redirect('template/404');
		}
		$mail_id=$this->login_database->encryptor('decrypt',$mail_id);
		$test_id=$this->login_database->encryptor('decrypt',$test_id);
		$user=$this->login_database->read_user_info($mail_id);
		if(!isset($user))
		{
			$this->session->set_flashdata("message","Invalid Student ID");
			$this->load->view('template/404');
		}
		$status=($user['active']==10)?1:0;
		$test=$this->model_quiz->get_test_details($test_id);
		if(!isset($test))
		{
			$this->session->set_flashdata("message","Invalid test id");
			$this->load->view('template/404');
		}
		
		$no_of_questions=$test['no_of_questions'];
		for($key=0;$key<$no_of_questions;$key++)
		{
			$data=array(
				'test_id'=>$test_id,
				'question'=>$this->input->post("question".$key),
				'option_a'=>$this->input->post('option_a'.$key),
				'option_b'=>$this->input->post('option_b'.$key),
				'option_c'=>$this->input->post('option_c'.$key),
				'option_d'=>$this->input->post('option_d'.$key),
				'answer'=>$this->input->post('answer'.$key),
				'explanation'=>$this->input->post('explanation'.$key)
			);			
			$this->model_quiz->new_questions($data);
		}
		$result=$this->model_quiz->validate_question($test_id);
		$val=($user['active']==10)?1:10;
		$data=array('active'=>$val);
		$result=$this->model_quiz->update_test($test_id,$data);
		if($result==TRUE)
		{
			if($status==1)
			{
				$this->session->set_flashdata("message","Your Test Published to Public");
				redirect("quiz/detail_view/$test_id");
			}
			else
			{
				$this->session->set_flashdata("message","Your Test Created Successfully and need approve from admin. once our admin approved your test, your test will be shown public.");
				redirect('online_test/index/','refresh');
			}
		}
		else
		{
			$this->session->set_flashdata("message","Invalid Student ID");
			$this->load->view('template/404');
		}
			
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