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
	$qid= $this->uri->segment(4);
	$str=$this->login_database->encryptor('decrypt',$sub_id);
	if(! (isset($str) OR isset($qid))) redirect('user_home/error');
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
	$question=$this->model_qbank->get_ques($qid);	
	
		
?> 
<!--main Title bar-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			
			<?PHP $this->load->view('template/alert'); ?>
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
						Edit Questions
					</header>
					<div class="panel-body">
						<div id="accordion1"  style="background:none; padding:30px; border:none;">
							<?PHP	
								
								$cls=array('class'=>'form-horizontal');
								echo form_open("question_bank/edit_question/$sub_id/$qid",$cls);
							?>
									<div class="form-group"  style="border-style:none">
										<div class="col-sm-12">
											<textarea class="form-control ckeditor" name="question" placeholder="Type the question" rows="3"><?= html_entity_decode($question['question']); ?></textarea>
										</div>
									</div>
									<div class="form-group" style="border-style:none">
										<label class="col-sm-1 control-label">A</label>
										<div class="col-sm-5">
											<input type="text" name="option_a" value="<?= $question['option_a']; ?>" class="form-control"  required="true"  placeholder="Option A">
										</div>
										<label class="col-sm-1 control-label">B</label>
										<div class="col-sm-5">
											<input type="text" name="option_b" value="<?= $question['option_b']; ?>" class="form-control"  required="true"  placeholder="Option B">
										</div>
									</div>
									<div class="form-group"  style="border-style:none">
										<label class="col-sm-1 control-label">C</label>
										<div class="col-sm-5">
											<input type="text" name="option_c" value="<?= $question['option_c']; ?>" class="form-control"  required="true"  placeholder="Option C">
										</div>
										<label class="col-sm-1 control-label">D</label>
										<div class="col-sm-5">
											<input type="text" name="option_d" value="<?= $question['option_d']; ?>" class="form-control"  required="true"  placeholder="Option D">
										</div>
									</div>
									<div class="form-group" style="float:center;">
										<label class="col-sm-5 control-label">Choose Correct Answer</label>
										<div class="col-xs-6">
											<div class="btn-group btn-block btn-xs" data-toggle="buttons">
												<label class="btn btn-danger <?= ($question['answer']=='a')?'active':''; ?>">
													<input type="radio" name="answer" value="a" <?= ($question['answer']=='a')?'checked':''; ?> id="option1"> Option A
												</label>
												<label class="btn btn-danger <?= ($question['answer']=='b')?'active':''; ?>">
													<input type="radio" name="answer" value="b"  <?= ($question['answer']=='b')?'checked':''; ?> id="option2"> Option B
												</label>
												<label class="btn btn-danger <?= ($question['answer']=='c')?'active':''; ?>">
													<input type="radio" name="answer" value="c"  <?= ($question['answer']=='c')?'checked':''; ?> id="option3"> Option C
												</label>
												<label class="btn btn-danger <?= ($question['answer']=='d')?'active':''; ?>">
													<input type="radio" name="answer" value="d"  <?= ($question['answer']=='d')?'checked':''; ?> id="option4"> Option D
												</label>
											</div>
										</div>
										<div class="row col-sm-offset-1 col-sm-10 ans-explain" >
											<a class="accordion-toggle ans-explain-label" data-toggle="collapse" data-parent="#accordion1" href="#exp">Explanation (Optional)</a>
											<div id="exp" class="form-group panel-collapse collapse"  style="border-style:none">
												<div class="col-sm-offset-1 col-sm-11">
													<textarea class="form-control ckeditor" name="explanation" placeholder="Explain here (optional)" rows="3"><?= html_entity_decode($question['explanation']); ?></textarea>
												</div>
											</div>
										</div>
										
									</div>
									
								
								<button class="btn btn-primary btn-lg btn-block" type="submit">Edit Question</button>
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
