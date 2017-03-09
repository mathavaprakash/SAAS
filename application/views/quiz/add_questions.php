<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	if($this->session->tempdata('email_id'))
	{
		$aa=$this->session->tempdata('email_id');
		$mail_id=$this->encryption->decrypt($aa);
		$this->session->set_tempdata('email_id',$aa,TIMEOUT);
	}
	else
	{
		redirect('admin_home/logout');
	}
	$user=$this->login_database->read_user_info($mail_id);
	$this->load->helper('inflector');
	$name=$user['first_name'] . '_' . $user['last_name'];
	$name=humanize($name);
	$data=array('title'=>'Dashboard');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/admin_menu',$data);
	$id=$this->uri->segment(3);
	if(! isset($id))
	{
		redirect('admin_home/logout');
	}
	$test= $this->model_quiz->get_test_details($id);
	if(! isset($test))
	{
		redirect('admin_home/logout');
	}
	$no_of_questions=$test['no_of_questions'];
?> 
<!--main Title bar-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h3 class="page-header"><i class="fa fa fa-bars"></i> Quiz</h3>
				<?PHP $this->load->view('template/alert'); ?>
				<section class="panel">
					<header class="panel-heading">
						Add Questions
					</header>
					<div class="panel-body"><div   style="background:#c2c2d6; padding:30px; border:none;">
						<?PHP	$cls=array('class'=>'form-horizontal');
							echo form_open("quiz/save_questions/$id/$no_of_questions",$cls);
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
								</div>
							<?PHP endfor; ?>
							<button class="btn btn-primary btn-lg btn-block" type="submit">Submit Questions</button>
						<?= form_close(); ?>
					</div></div>
				</section>
			</div>
		</div>
	</section>
</section>
      <!--main content end-->
<?PHP $this->load->view('template/footer'); ?>
