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
	$questions=$this->model_quiz->get_questions($id);
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
							echo form_open("quiz/update_questions/$id/$no_of_questions",$cls);
						?>
							<?PHP $i=0; foreach ($questions as $q): ?>
								<div class="form-group"  style="border-style:none">
									<label class="col-sm-1 control-label"><?= $i+1; ?></label>
									<div class="col-sm-11">
										<textarea class="form-control ckeditor" name="question<?= $q['question_id']; ?>" placeholder="Type the question" rows="3"><?= $q['question']; ?></textarea>
									</div>
								</div>
								<div class="form-group" style="border-style:none">
									<label class="col-sm-1 control-label">A</label>
									<div class="col-sm-5">
										<input type="text" name="option_a<?= $q['question_id']; ?>" value="<?= $q['option_a']; ?>" class="form-control"  required="true"  placeholder="Option A">
									</div>
									<label class="col-sm-1 control-label">B</label>
									<div class="col-sm-5">
										<input type="text" name="option_b<?= $q['question_id']; ?>" value="<?= $q['option_b']; ?>" class="form-control"  required="true"  placeholder="Option B">
									</div>
								</div>
								<div class="form-group"  style="border-style:none">
									<label class="col-sm-1 control-label">C</label>
									<div class="col-sm-5">
										<input type="text" name="option_c<?= $q['question_id']; ?>" value="<?= $q['option_c']; ?>" class="form-control"  required="true"  placeholder="Option C">
									</div>
									<label class="col-sm-1 control-label">D</label>
									<div class="col-sm-5">
										<input type="text" name="option_d<?= $q['question_id']; ?>" value="<?= $q['option_d']; ?>" class="form-control"  required="true"  placeholder="Option D">
									</div>
								</div>
								<div class="form-group" style="float:center;">
									<label class="col-sm-5 control-label">Choose Correct Answer</label>
									<div class="col-xs-6">
										<div class="btn-group btn-block btn-xs" data-toggle="buttons">
											<label class="btn btn-danger <?= ($q['answer']=='a')?'active':''; ?>">
												<input type="radio" name="answer<?= $q['question_id']; ?>" value="a" <?= ($q['answer']=='a')?'checked':''; ?> id="option1"> Option A
											</label>
											<label class="btn btn-danger <?= ($q['answer']=='b')?'active':''; ?>">
												<input type="radio" name="answer<?= $q['question_id']; ?>" value="b" <?= ($q['answer']=='b')?'checked':''; ?> id="option2"> Option B
											</label>
											<label class="btn btn-danger <?= ($q['answer']=='c')?'active':''; ?>">
												<input type="radio" name="answer<?= $q['question_id']; ?>" value="c" <?= ($q['answer']=='c')?'checked':''; ?> id="option3"> Option C
											</label>
											<label class="btn btn-danger <?= ($q['answer']=='d')?'active':''; ?>">
												<input type="radio" name="answer<?= $q['question_id']; ?>" value="d" <?= ($q['answer']=='d')?'checked':''; ?> id="option3"> Option D
											</label>
										</div>
									</div>
									<div class="row col-sm-offset-1 col-sm-10 ans-explain" >
											<a class="accordion-toggle ans-explain-label" data-toggle="collapse" data-parent="#accordion1" href="#exp<?= $q['question_id']; ?>">Explanation (Optional)</a>
											<div id="exp<?= $q['question_id']; ?>" class="form-group panel-collapse collapse"  style="border-style:none">
												<div class="col-sm-offset-1 col-sm-11">
													<textarea class="form-control ckeditor" name="explanation<?= $q['question_id']; ?>" placeholder="Explain here (optional)" rows="3">
														<?= $q['explanation']; ?>
													</textarea>
												</div>
											</div>
										</div>
								</div>
								
							<?PHP endforeach; ?>
							<button class="btn btn-primary btn-lg btn-block" type="submit">Edit Questions</button>
						<?= form_close(); ?>
					</div></div>
				</section>
			</div>
		</div>
	</section>
</section>
      <!--main content end-->
	  <script type="text/javascript" src="<?= base_url(); ?>assets/ckeditor/ckeditor.js"></script>

<?PHP $this->load->view('template/footer'); ?>
