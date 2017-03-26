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
	$data=array('title'=>'Quiz');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/admin_menu',$data);
	$id=$this->uri->segment(3);
	if(! isset($id))
	{
		redirect('user_home/error');
	}
	$test= $this->model_quiz->get_test_details($id);
	$questions=NULL;
	$questions=$this->model_quiz->get_questions($id);
	$status=$this->model_quiz->get_test_status($id);
	$student=$this->model_quiz->get_student_details($test['mail_id']);
	$sname=$student['first_name'] . '_' . $student['last_name'];
	$sname=humanize($sname);
	$stud_id=$this->login_database->encryptor('encrypt',$test['mail_id']);
?> 
<!--main Title bar-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_title" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title">Update Title</h4>
			</div>
			<div class="modal-body">
				<?PHP	$cls=array('class'=>'form-horizontal');
					echo form_open("quiz/edit_test_title/$id",$cls);
				?>	
					<div class="form-group">
						<label class="col-lg-4 control-label">Enter Test Title</label>
						<div class="col-lg-4">
							<input type="text" name="title" class="form-control" value="<?= $test['title']; ?>" placeholder="Test title" required autofocus>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-4 col-lg-6">
							<button type="submit" class="btn btn-info">Update</button>
						</div>
					</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_duration" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title">Update Duration</h4>
			</div>
			<div class="modal-body">
				<?PHP	$cls=array('class'=>'form-horizontal');
					echo form_open("quiz/edit_test_duration/$id",$cls);
				?>	
					<div class="form-group">
						<label class="col-lg-4 control-label">Enter Duration</label>
						<div class="col-lg-4">
							<input type="number" name="duration" class="form-control" value="<?= $test['duration']; ?>" placeholder="Test duration" required autofocus>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-4 col-lg-6">
							<button type="submit" class="btn btn-info">Update</button>
						</div>
					</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_start_date" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title">Update Test Start Date</h4>
			</div>
			<div class="modal-body">
				<?PHP	$cls=array('class'=>'form-horizontal');
					echo form_open("quiz/edit_test_st_date/$id",$cls);
				?>	
					<div class="form-group">
						<label class="col-lg-4 control-label">Choose Date</label>
						<div class="col-lg-6">
							<input type="datetime-local" name="start_date" value="<?= $test['start_date']; ?>" min="<?= date('Y-m-d\TH:i',now()); ?>" max="<?= date('Y-m-d\TH:i',strtotime($test['end_date'])); ?>"  class="form-control"  placeholder="Start Date & Time" required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-4 col-lg-6">
							<button type="submit" class="btn btn-info">Update</button>
						</div>
					</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_end_date" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title">Update Test End Date</h4>
			</div>
			<div class="modal-body">
				<?PHP	$cls=array('class'=>'form-horizontal');
					echo form_open("quiz/edit_test_end_date/$id",$cls);
				?>	
					<div class="form-group">
						<label class="col-lg-4 control-label">Choose Date</label>
						<div class="col-lg-6">
							<input type="datetime-local" name="end_date" value="<?= $test['end_date']; ?>" min="<?= $test['start_date']; ?>"  class="form-control"  placeholder="Start Date & Time" required>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-4 col-lg-6">
							<button type="submit" class="btn btn-info">Update</button>
						</div>
					</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
</div>
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h3 class="page-header"><i class="fa fa fa-bars"></i> Quiz</h3>
				<?PHP $this->load->view('template/alert'); ?>
				
				<section class="panel">
						<header class="panel-heading tab-bg-primary ">
							<ul class="nav nav-tabs">
								<li class="active">
									<a data-toggle="tab" href="#details">Details</a>
								</li>
								<li class="">
									<a data-toggle="tab" href="#questions">Questions</a>
								</li>
								
							</ul>
						</header>
						<div class="panel-body">
							<div class="tab-content">
								<div id="details" class="tab-pane active">
									<div class="col-lg-8 col-lg-offset-2">
										<section class="panel">
											<table class="table table-striped table-advance table-hover">
												<tbody>
													<tr>
														<td>Test Title</td>
														<td><?= $test['title']; ?> &nbsp; <a href="#edit_title" data-toggle="modal"><i class="fa fa-pencil" aria-hidden="true"></i></a></td>
													</tr>
													<tr>
														<td>Category</td>
														<td><?= $this->model_quiz->id_to_cat($test['category_id']); ?></td>
													</tr>
													<tr>
														<td>Created BY</td>
														<td> <?= anchor("user_home/view_student_profile/$stud_id",$sname); ?></td>
													</tr>
													<tr>
														<td>Start Time</td>
														<td><?= $this->model_quiz->cdate($test['start_date']); ?> 
															&nbsp; 
															<?PHP if($status=='Ready'): ?>
																<a href="#edit_start_date" data-toggle="modal">
																<i class="fa fa-pencil" aria-hidden="true"></i></a>
															<?PHP endif; ?>
														</td>
													</tr>
													<tr>
														<td>End Time</td>
														<td><?= $this->model_quiz->cdate($test['end_date']); ?>
															 &nbsp;
															 <?PHP if($status=='Ready' OR $status=='Active' OR $status=='Finished'): ?>
																<a href="#edit_end_date" data-toggle="modal">
																<i class="fa fa-pencil" aria-hidden="true"></i></a>
															<?PHP endif; ?>
														</td>
													</tr>
													<tr>
														<td>Test Duration (Mins)</td>
														<td><?= $test['duration']; ?>
															&nbsp; 
															<?PHP if($status=='Ready'): ?>
																<a href="#edit_duration" data-toggle="modal">
																<i class="fa fa-pencil" aria-hidden="true"></i></a>
															<?PHP endif; ?>
														</td>
													</tr>
													<tr>
														<td>Number of Questions</td>
														<td><?= $test['no_of_questions']; ?></td>
													</tr>
													<tr class="danger">
														<td>Status</td>
														<td><?= $status; ?>
															&nbsp; 
															<?PHP if($status=='Pending'): ?>
																<a href="<?= site_url(); ?>/quiz/approve_test/<?= $id ?>" data-toggle="modal">Approve Test
																<i class="fa fa-pencil" aria-hidden="true"></i></a>
															<?PHP endif; ?>
															<?PHP if($status=='Ready' OR $status=='Active' OR $status=='Finished'): ?>
																<a href="<?= site_url(); ?>/quiz/close_test/<?= $id ?>" data-toggle="modal">Close Test
																<i class="fa fa-pencil" aria-hidden="true"></i></a>
															<?PHP endif; ?>
															<?PHP if($status=='Ready' OR $status=='Pending' OR $status=='Error' OR $status=='Closed' OR $status=='Incomplete'): ?>
																<a href="<?= site_url(); ?>/quiz/delete_test/<?= $id ?>" data-toggle="modal">Delete Test
																<i class="fa fa-pencil" aria-hidden="true"></i></a>
															<?PHP endif; ?>
														</td>
													</tr>
												</tbody>
											</table></br>
											
										</section>
									</div>
								</div>
								<div id="questions" class="tab-pane">
									<?PHP if( $status=='Incomplete'): ?>
										<div class="col-lg-offset-3 col-lg-6">
											<div class="btn-group btn-group-justified">
												<a class="btn btn-primary" href="<?= site_url(); ?>/quiz/add_questions/<?= $id; ?>">Add Questions</a>
											</div>
										</div>
									<?PHP else: ?>
										<div class="row quiz-containerr ">
			
											<div class="category-containerr col-md-offset-1 col-md-10 " style="padding-bottom:0px;">
											  
												<div class="">
													<div style="border:none;">
														
														<div class="row ">
															<div style="padding:2px; letter-spacing: 1px;" id="accordion1">
																<?PHP $i=0; foreach ($questions as $q): ?>
																	<div class="ans-question">
																		
																		<div class="col-xs-2 col-md-1">
																			<?= $i=$i+1; ?> )
																		</div>
																		<div class="col-xs-10 col-md-11">
																			<?= $q['question']; ?>
																		</div>
																	</div>
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
																<?PHP endforeach; ?>
																
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										
											<?PHP if( $status=='Ready'): ?>
											<div class="row col-sm-offset-5" >
												<a class="btn btn-success btn-lg" href="<?= site_url(); ?>/quiz/edit_question/<?= $id; ?>" title="Edit Questions">Edit Questions</a>
											</div>
											<?PHP endif; ?>
									<?PHP endif; ?>
								</div>
								
							</div>
						</div>
					</section>
			</div>
			
		</div>
		<?PHP 
			
			$attended_students=$this->model_quiz->attended_students($id);
			if($attended_students!=FALSE):
		?>
		
		<div class="row">
                  <div class="col-lg-12">
                      <section class="panel">
                          <header class="panel-heading">
                              Test Attended Student Details
                          </header>
                          
                          <table class="table table-striped table-advance table-hover">
                           <tbody>
                              <tr>
                                 <th><i class="fa fa-slack" aria-hidden="true"></i> S.NO</th>
                                 <th><i class="fa fa-user" aria-hidden="true"></i> Full Name</th>
                                 <th><i class="fa fa-calendar" aria-hidden="true"></i> Attended On</th>
								 <th><i class="fa fa-star" aria-hidden="true"></i> Marks </th>
								</tr>
							  <?PHP $sno=0; foreach($attended_students as $std): ?>
                              <tr>
                                 <td><?= $sno=$sno+1; ?></td>
                                 
									<?PHP
										$mid=$std['mail_id'];
										$student=$this->model_quiz->get_student_details($mid);
										$snam=$student['first_name'] . '_' . $student['last_name'];
										$snam=humanize($snam);
										$std_id=$this->login_database->encryptor('encrypt',$mid);
										
									?>
								 <td><?= anchor("user_home/view_student_profile/$std_id",$snam); ?></td>
                                 <td><?= $this->model_quiz->cdate($std['start_date']); ?></td>
                                 <td><?= $std['marks']; ?></td>
                                 
                              </tr>
                               <?PHP endforeach; ?>                       
                           </tbody>
                        </table>
                      </section>
                  </div>
              </div>
		<?PHP else: ?>
		 
		<?PHP endif; ?>
	</section>
</section>
      <!--main content end-->
<?PHP $this->load->view('template/footer'); ?>
