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
	
	$questions=$this->model_qbank->get_questions($str);
	
		
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
										<a style="color:#fff;" href="<?= $file; ?>"  target="_blank">Attachment</a>
								
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
				<a class="btn btn-success btn-md" style="float:right;" href="<?= site_url(); ?>/question_bank/home/<?= $sub_id; ?>">Add Questions</a>
				
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="row quiz-containerr ">
					<div class="category-containerr col-md-offset-1 col-md-10 " style="padding-bottom:0px;">
						<div class="">
							<div style="border:none;">
								<div class="row ">
									<div style="padding:2px; letter-spacing: 1px;" id="accordion1">
										<?PHP if(count($questions)>0): ?>
											<?PHP $i=0; foreach ($questions as $q): ?>
												<div class="row">
													<div class="col-xs-12" style="margin-bottom:20px;">
														<div class=" col-md-12">
															<div class="row">
																
																<div class="col-md-12 room-content">
																		<a  style="float:left; margin-right:4px; color:#111;"> <?= $i=$i+1; ?> ) </a>
																		<a  style="float:right; margin-right:4px; color:red;" href="<?= site_url(); ?>/question_bank/delete_question/<?= $sub_id; ?>/<?= $q['question_id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a>
																		<a style="float:right; margin-right:4px;" href="<?= site_url(); ?>/question_bank/edit/<?= $sub_id; ?>/<?= $q['question_id']; ?>"><i class="fa fa-edit" aria-hidden="true"></i></a>
																	
																	<?= $q['question']; ?>
																		
																
																<div class="row col-sm-offset-1" >
																	<div class="row col-sm-12 ans-option" >
																		<div class="col-sm-6 <?PHP if($q['answer']=='a') echo ' correct'; ?>">
																			<div class="col-xs-11">A) <?= $q['option_a']; ?></div>												
																		</div>
																		<div class="col-sm-6 <?PHP if($q['answer']=='b') echo ' correct'; ?>">
																			<div class="col-xs-11">B) <?= $q['option_b']; ?></div>												
																		</div>
																		<div class="col-sm-6 <?PHP if($q['answer']=='c') echo ' correct'; ?>">
																			<div class="col-xs-11">C) <?= $q['option_c']; ?></div>												
																		</div>
																		<div class="col-sm-6 <?PHP if($q['answer']=='d') echo ' correct'; ?>">
																			<div class="col-xs-11">D) <?= $q['option_d']; ?></div>												
																		</div>
																	</div>
																</div>
																<?PHP if(($q['explanation']!=NULL)): ?>
																	<div class="col-sm-offset-1 ans-explain" >
																		<a class="accordion-toggle ans-explain-label" data-toggle="collapse" data-parent="#accordion1" href="#<?= $q['question_id']; ?>">Explanation</a>
																
																		<div id="<?= $q['question_id']; ?>" class="panel-collapse collapse">
																			<?= $q['explanation']; ?>
																		</div>
																	</div>
																<?PHP endif; ?>
																</div>
															</div>
														</div>
													</div>
												</div>
												
												
												
											<?PHP endforeach; ?>
											<?PHP else: ?>
												no questions 
										<?PHP endif; ?>
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</section>
</section>
      <!--main content end-->

<!-- ck editor -->
    <script type="text/javascript" src="<?= base_url(); ?>assets/ckeditor/ckeditor.js"></script>
<?PHP $this->load->view('template/footer'); ?>
