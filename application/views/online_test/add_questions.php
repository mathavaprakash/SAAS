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
	$enc_id=$this->uri->segment(3);
	if(! isset($enc_id))
	{
		redirect('template/error');
	}
	$id=$this->login_database->encryptor('decrypt',$enc_id);
						
	$test= $this->model_quiz->get_test_details($id);
	if(! isset($test))
	{
		redirect('user_home/error');
	}
	$no_of_questions=$test['no_of_questions'];
?> 
<!--main Title bar-->
<section id="main-content" >
	<section class="wrapper" style="width:99%;">
		<div class="row">
			<div class="col-lg-12">
				<div class="page-header"><i class="fa fa-desktop" aria-hidden="true"></i>
				 Create Test</div>
				<?PHP $this->load->view('template/alert'); ?>
				
				<section class="panel">
					<header class="panel-heading">
						Add Questions
					</header>
					<div class="panel-body">
						<div id="accordion1"  style="background:#c2c2d6; padding:30px; border:none;">
							<?PHP	
								$mid=$this->login_database->encryptor('encrypt',$mail_id);
			
								$cls=array('class'=>'form-horizontal');
								echo form_open("online_test/save_questions/$mid/$enc_id",$cls);
							?>
								<?PHP for($i=0; $i<$no_of_questions; $i++): ?>
									<div class="form-group"  style="border-style:none">
										<label class="col-sm-1 control-label"><?= $i+1; ?></label>
										<div class="col-sm-11">
											<textarea class="form-control ckeditor" name="question<?= $i; ?>" placeholder="Type the question" rows="3"></textarea>
										</div>
									</div>
									<div class="form-group" style="border-style:none">
										<label class="col-sm-1 control-label">A</label>
										<div class="col-sm-5">
											<input type="text" name="option_a<?= $i; ?>" class="form-control"  required="true"  placeholder="Option A">
										</div>
										<label class="col-sm-1 control-label">B</label>
										<div class="col-sm-5">
											<input type="text" name="option_b<?= $i; ?>" class="form-control"  required="true"  placeholder="Option B">
										</div>
									</div>
									<div class="form-group"  style="border-style:none">
										<label class="col-sm-1 control-label">C</label>
										<div class="col-sm-5">
											<input type="text" name="option_c<?= $i; ?>" class="form-control"  required="true"  placeholder="Option C">
										</div>
										<label class="col-sm-1 control-label">D</label>
										<div class="col-sm-5">
											<input type="text" name="option_d<?= $i; ?>" class="form-control"  required="true"  placeholder="Option D">
										</div>
									</div>
									<div class="form-group" style="float:center;">
										<label class="col-sm-5 control-label">Choose Correct Answer</label>
										<div class="col-xs-6">
											<div class="btn-group btn-block btn-xs" data-toggle="buttons">
												<label class="btn btn-danger active">
													<input type="radio" name="answer<?= $i; ?>" value="a" checked id="option1"> Option A
												</label>
												<label class="btn btn-danger ">
													<input type="radio" name="answer<?= $i; ?>" value="b" id="option2"> Option B
												</label>
												<label class="btn btn-danger">
													<input type="radio" name="answer<?= $i; ?>" value="c" id="option3"> Option C
												</label>
												<label class="btn btn-danger">
													<input type="radio" name="answer<?= $i; ?>" value="d" id="option4"> Option D
												</label>
											</div>
										</div>
										<div class="row col-sm-offset-1 col-sm-10 ans-explain" >
											<a class="accordion-toggle ans-explain-label" data-toggle="collapse" data-parent="#accordion1" href="#exp<?= $i; ?>">Explanation (Optional)</a>
											<div id="exp<?= $i; ?>" class="form-group panel-collapse collapse"  style="border-style:none">
												<div class="col-sm-offset-1 col-sm-11">
													<textarea class="form-control ckeditor" name="explanation<?= $i; ?>" placeholder="Explain here (optional)" rows="3"></textarea>
												</div>
											</div>
										</div>
										
									</div>
									
								<?PHP endfor; ?>
								<button class="btn btn-primary btn-lg btn-block" type="submit">Submit Questions</button>
							<?= form_close(); ?>
						</div>
					</div>
				</section>
			</div>
		</div>
		
	  
	</section>
</section>
      <!--main content end-->
	  <script type="text/javascript" src="<?= base_url(); ?>assets/ckeditor/ckeditor.js"></script>

<?PHP $this->load->view('template/footer'); ?>
