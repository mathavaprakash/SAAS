<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	if($this->session->tempdata('mail_id'))
	{
		$aa=$this->session->tempdata('mail_id');
		$mail_id=$this->encryption->decrypt($aa);
		$this->session->set_tempdata('mail_id',$aa,TIMEOUT);
	}
	else if($this->session->tempdata('email_id'))
	{
		$aa=$this->session->tempdata('email_id');
		$mail_id=$this->encryption->decrypt($aa);
		$this->session->set_tempdata('email_id',$aa,TIMEOUT);
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
	$data=array('title'=>'Create Test');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	if($user['active']==10)
	{
		$this->load->view('template/admin_menu',$data);
	}
	else
	{
		$this->load->view('template/user_menu',$data);
	}	
	$category=$this->model_quiz->get_category();
	if(!isset($category))
	{
		$this->session->set_flashdata("message","");
		redirect('user_home/error');
	}
?> 
<!--main Title bar-->
<section id="main-content" >
	<section class="wrapper" style="width:99%;">
		<div class="row">
			<div class="col-lg-12">
				<div class="page-header"><i class="fa fa-desktop" aria-hidden="true"></i>
				 Create Test</div>
				<?PHP $this->load->view('template/alert'); ?>
			</div>
		</div>
		<div class="row">
			<?PHP
				$mid=$this->login_database->encryptor('encrypt',$mail_id);
		
				$cls=array(
					'class'=>'form-horizontal'
				);
				echo form_open("online_test/add_test/$mid",$cls);
			?>
				<div class="form-group">
					<label class="col-lg-4 control-label">Enter Test Title</label>
					<div class="col-lg-4">
						<input type="text" name="title" class="form-control"  placeholder="Test title" required autofocus>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label">Select Category</label>
					<div class="col-lg-4">
						<?PHP 
							$cls= 'class="form-control"  placeholder="Test title"';
						
							echo form_dropdown('category', $category, 'large',$cls); 
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label">Start Date & Time</label>
					<div class="col-lg-4">
						<input type="datetime-local" name="start_date" min="<?= date('Y-m-d\TH:i',time()); ?>"  class="form-control"  placeholder="Start Date & Time" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label">End Date & Time</label>
					<div class="col-lg-4">
						<input type="datetime-local" min="<?= date('Y-m-d\TH:i',time()); ?>" name="end_date" class="form-control"  placeholder="end Date & Time" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label">Test Duration(in Mins)</label>
					<div class="col-lg-4">
						<input type="number" name="duration" min=1 max=300 class="form-control"  placeholder="Test Duration(in Mins)" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label">Number of Questions</label>
					<div class="col-lg-4">
						<input type="number" name="no_of_questions" min=1 max=200 class="form-control"  placeholder="Number of Questions" required>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-lg-offset-4 col-lg-6">
						<button type="submit" class="btn btn-info">Create New Test</button>
					</div>
				</div>
			<?= form_close(); ?>	
		</div>
	  
	</section>
</section>
      <!--main content end-->
	  <script type="text/javascript" src="<?= base_url(); ?>assets/ckeditor/ckeditor.js"></script>

<?PHP $this->load->view('template/footer'); ?>
