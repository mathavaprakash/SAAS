<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	if($this->session->tempdata('mail_id'))
	{
		$aa=$this->session->tempdata('mail_id');
		$mail_id=$this->encryption->decrypt($aa);
		$this->session->set_tempdata('mail_id',$aa,TIMEOUT);
	}
	else
	{
		redirect('user_home/error');
	}
	$user=$this->login_database->read_user_info($mail_id);
	if(!isset($user))
	{
		$this->session->set_flashdata("message","Invalid User");
		redirect('user_home/error');
	}
	$this->load->helper('inflector');
	$name=$user['first_name'] . '_' . $user['last_name'];
	$name=humanize($name);
	$data=array('title'=>'Class Room');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/user_menu',$data);
	//if(!($this->encryption->decrypt($this->uri->segment(3))))
	//{
	//	$this->load->view('template/404');
	//}
	//else
	//{
		//$stud_id=$this->encryption->decrypt($_GET['id']);
		$stud_id=$this->login_database->encryptor('decrypt',$this->uri->segment(3));
															
		//$stud_id = $this->encrypt->decode($this->uri->segment(3));
	//}
	$student=$this->login_database->read_user_info($stud_id);
	if(!isset($student))
	{
		$this->session->set_flashdata("message","Invalid Student ID");
		$this->load->view('template/404');
	}
	$sname=$student['first_name'] . '_' . $student['last_name'];
	$sname=humanize($sname);
	$rank=$this->model_quiz->get_overall_rank($stud_id);
	$profile_pic=$this->login_database->profile_pic_url($stud_id);
	
?> 
<!--main Title bar-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<?PHP $this->load->view('template/alert'); ?>
			</div>
		</div>
		<!--collapse start-->
		<div class="row">
			<div class="col-md-3">
				<div class="col-xs-12 profile-pic-container" >
					<img class="profile-pic" src="<?= base_url() . $profile_pic; ?>" alt="<?= $sname; ?>">
					<div class="col-xs-12 profile-stud-name">
						<?= $sname; ?>
					</div>
				</div>
				<div class="col-xs-12" style="padding-top:30px;">
					<div class="col-xs-12 pro-content1" style="color:gold;">
						<?PHP for($i=0;$i<$rank['star'];$i++):  ?> 
								<i class="fa fa-star fa-2x" aria-hidden="true"></i>
							<?PHP endfor; ?>
							<?PHP for($i=$rank['star'];$i<5;$i++):  ?> 
								<i class="fa fa-star-o fa-2x" aria-hidden="true"></i>
							<?PHP endfor; ?> 
						
					</div>
					<div class="col-xs-12 pro-content1">
						
						Overall Rank : <?= $rank['rank']; ?> / <?= $rank['total']; ?>
					
					</div>
					<div class="col-xs-12  pro-content1">
						Total Points : <?= $student['points']; ?> 
					</div>
				</div>
				
				
				
				
			</div>
			<div class="profile-body col-md-8">
				<div class="col-xs-12 pro-content">
					graph and content will place here
				</div>
			</div>
			
		</div>
	  
	</section>
</section>
      <!--main content end-->
<?PHP $this->load->view('template/footer'); ?>
