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
	$data=array('title'=>'Quiz');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/admin_menu',$data);
	$id=$this->uri->segment(3);
	if(! isset($id))
	{
		redirect('admin_home/logout');
	}
	$test= $this->model_quiz->get_test_details($id);
	$questions=NULL;
	$questions=$this->model_quiz->get_questions($id);
	$status=$this->model_quiz->get_test_status($id);
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
															<?PHP if($status=='Ready' OR $status=='Active' OR $status=='Finished'): ?>
																<a href="<?= site_url(); ?>/quiz/close_test/<?= $id ?>" data-toggle="modal">Close Test
																<i class="fa fa-pencil" aria-hidden="true"></i></a>
															<?PHP endif; ?>
															<?PHP if($status=='Ready' OR $status=='Error' OR $status=='Closed' OR $status=='Incomplete'): ?>
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
										
										<div class="row">
											<section class="panel">	
												<div class="panel-body">
													<?PHP $i=0; foreach ($questions as $q): ?>
														<div class="col-lg-12">
															<?= $i=$i+1; ?> ) <?= $q['question']; ?>
														</div>
														<div class="row col-sm-offset-1" >
															<div class="col-sm-5 <?PHP if($q['answer']=='a') echo ' correct'; ?>">
																A) <?= $q['option_a']; ?>
															</div>
															<div class="col-sm-5 <?PHP if($q['answer']=='b') echo ' correct'; ?>">
																B) <?= $q['option_b']; ?>
															</div>
															<div class="col-sm-5 <?PHP if($q['answer']=='c') echo ' correct'; ?>">
																C) <?= $q['option_c']; ?>
															</div>
															<div class="col-sm-5 <?PHP if($q['answer']=='d') echo ' correct'; ?>">
																D) <?= $q['option_d']; ?>
															</div>
														</div>
														</br>
													<?PHP endforeach; ?>
													
												</div>
											</section>
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
	</section>
</section>
      <!--main content end-->
<?PHP $this->load->view('template/footer'); ?>
