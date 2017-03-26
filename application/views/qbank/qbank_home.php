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
		redirect('user_home/error');
	}
	$user=$this->login_database->read_user_info($mail_id);
	$this->load->helper('inflector');
	$name=$user['first_name'] . '_' . $user['last_name'];
	$name=humanize($name);
	
	
	$sub_id= $this->uri->segment(3);
	$str=$this->login_database->encryptor('decrypt',$sub_id);
	if(!isset($str)) redirect('user_home/error');
	$titles= $this->model_home->content($str);
	if($titles==FALSE)
	{
		redirect('user_home/error');
	}
	$cat=$titles['category_id'];
	$file= base_url() . 'attachment/' . $titles['attachment'];
	$exist=file_exists($file);
	$this->load->helper('file');
	$data=array('title'=>$titles['title']);
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/admin_menu',$data);
	
	$author=$this->login_database->read_user_info($titles['mail_id']);
	$aname=$author['first_name'] . '_' . $author['last_name'];
	$aname=humanize($aname);
	$stud_id=$this->login_database->encryptor('encrypt',$titles['mail_id']);
		
	
		
?> 
<!--main Title bar-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			
			<?PHP $this->load->view('template/alert'); ?>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="room-container">
					<div class="row">
						<div class="col-md-2">
							<div class="dash-calendar  col-md-offset-1 col-xs-7 ">
								<div class="dash-date">
									Posted On <?PHP $date=$titles['created_date']; ?>
								</div>
								<div class="dash-year">
									<?= date("h:i A",strtotime($date)); ?>
								</div>
								<div class="dash-month">
									<?= date("M",strtotime($date)); ?>
								</div>
								<div class="dash-month">
									<?= date("d",strtotime($date)); ?>
								</div>
								<div class="dash-year">
									<?= date("Y",strtotime($date)); ?>
								</div>
								
							</div>
						</div>
						<div class="col-md-8">
							<div class="test-header">
								<?= $titles['title']; ?>
							</div>
							<div class="test-category">
								<?= $this->model_home->id_to_cat($titles['category_id']); ?>
							</div>
							<div class="test-category">
								<?PHP $cls='style="color:#023;"'; ?>
								Posted By : <?= anchor("user_home/view_student_profile/$stud_id",$aname,$cls); ?>
							</div>
							<div class="test-category">
								<?PHP if(	!$titles['attachment']==NULL): 
									$file= base_url() . 'attachment/' . $titles['attachment'];
								?>
									<i class="fa fa-paperclip fa-lg" aria-hidden="true"></i>
										<a style="color:#fff;" href="<?= $file; ?>"  target="_blank">View Attachment</a>
								
								<?PHP else: ?>
									Add Attachment
								<?PHP endif; ?>
							</div>
						</div>
						<div class="col-md-2">
							<div class="dash-calendar  col-md-offset-1 col-xs-7 ">
								<div class="dash-date">
									<a class="btn-danger" style="float:right; border-radius:10px;" href="<?= site_url(); ?>/question_bank/home/<?= $sub_id; ?>">Question Bank</a>
				
									<?PHP $questions=$this->model_qbank->get_questions($str); ?>
								</div>
								<div class="dash-month">
									<?= count($questions); ?>
								</div>
								<div class="dash-year">
									Questions
								</div>
								
							</div>
						</div>
					</div>
					
				</div>
				
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12" style="padding:10px">
				<a class="btn btn-danger btn-md" href="<?= site_url(); ?>/admin_home/read_view/<?= $str; ?>">Back </a>
				<a class="btn btn-success btn-md" style="float:right;" href="<?= site_url(); ?>/question_bank/view/<?= $sub_id; ?>">view Questions</a>
				
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<section class="panel">
					<header class="panel-heading">
						Add Questions
					</header>
					<div class="panel-body">
						<div id="accordion1"  style="background:none; padding:30px; border:none;">
							<?PHP	
								
								$cls=array('class'=>'form-horizontal');
								echo form_open("question_bank/add_question/$sub_id",$cls);
							?>
									<div class="form-group"  style="border-style:none">
										<div class="col-sm-12">
											<textarea class="form-control ckeditor" name="question" placeholder="Type the question" rows="3"></textarea>
										</div>
									</div>
									<div class="form-group" style="border-style:none">
										<label class="col-sm-1 control-label">A</label>
										<div class="col-sm-5">
											<input type="text" name="option_a" class="form-control"  required="true"  placeholder="Option A">
										</div>
										<label class="col-sm-1 control-label">B</label>
										<div class="col-sm-5">
											<input type="text" name="option_b" class="form-control"  required="true"  placeholder="Option B">
										</div>
									</div>
									<div class="form-group"  style="border-style:none">
										<label class="col-sm-1 control-label">C</label>
										<div class="col-sm-5">
											<input type="text" name="option_c" class="form-control"  required="true"  placeholder="Option C">
										</div>
										<label class="col-sm-1 control-label">D</label>
										<div class="col-sm-5">
											<input type="text" name="option_d" class="form-control"  required="true"  placeholder="Option D">
										</div>
									</div>
									<div class="form-group" style="float:center;">
										<label class="col-sm-5 control-label">Choose Correct Answer</label>
										<div class="col-xs-6">
											<div class="btn-group btn-block btn-xs" data-toggle="buttons">
												<label class="btn btn-danger active">
													<input type="radio" name="answer" value="a" checked id="option1"> Option A
												</label>
												<label class="btn btn-danger ">
													<input type="radio" name="answer" value="b" id="option2"> Option B
												</label>
												<label class="btn btn-danger">
													<input type="radio" name="answer" value="c" id="option3"> Option C
												</label>
												<label class="btn btn-danger">
													<input type="radio" name="answer" value="d" id="option4"> Option D
												</label>
											</div>
										</div>
										<div class="row col-sm-offset-1 col-sm-10 ans-explain" >
											<a class="accordion-toggle ans-explain-label" data-toggle="collapse" data-parent="#accordion1" href="#exp">Explanation (Optional)</a>
											<div id="exp" class="form-group panel-collapse collapse"  style="border-style:none">
												<div class="col-sm-offset-1 col-sm-11">
													<textarea class="form-control ckeditor" name="explanation" placeholder="Explain here (optional)" rows="3"></textarea>
												</div>
											</div>
										</div>
										
									</div>
									
								
								<button class="btn btn-primary btn-lg btn-block" type="submit">Add Question</button>
							<?= form_close(); ?>
						</div>
					</div>
				</section>
			</div>
		</div>
		
	</section>
</section>
      <!--main content end-->

<!-- ck editor -->
    <script type="text/javascript" src="<?= base_url(); ?>assets/ckeditor/ckeditor.js"></script>
<?PHP $this->load->view('template/footer'); ?>
