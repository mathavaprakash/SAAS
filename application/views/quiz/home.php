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
	$table=$this->model_quiz->get_table();
	$category=$this->model_quiz->get_category();
?> 
<!--main Title bar-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h3 class="page-header"><i class="fa fa fa-bars"></i> Quiz</h3>
				
				<?PHP $this->load->view('template/alert'); ?>
				<!--tab nav start-->
					<section class="panel">
						<header class="panel-heading tab-bg-primary ">
							<ul class="nav nav-tabs">
								<li class="active">
									<a data-toggle="tab" href="#all">All</a>
								</li>
								<li class="">
									<a data-toggle="tab" href="#active">Active</a>
								</li>
								<li class="">
									<a data-toggle="tab" href="#incomplete">In-Complete</a>
								</li>
								<li class="">
									<a data-toggle="tab" href="#finished">Finished</a>
								</li>
								<li class="">
									<a data-toggle="tab" href="#closed">Closed</a>
								</li>
								<li class="">
									<a data-toggle="tab" href="#create">Create Test</a>
								</li>
							</ul>
						</header>
						<div class="panel-body">
							<div class="tab-content">
								<div id="all" class="tab-pane active">
								<?PHP
									$template = array(
									'table_open' => '<table class="table table-striped table-advance table-hover">'
									);
									$this->table->set_template($template);
									$this->table->set_caption('All Quiz List');
									$this->table->set_heading('Title', 'Category', 'Start Date', 'End Date','Actions');
									echo $this->table->generate($table['All']);
								?>
								</div>
								<div id="active" class="tab-pane">
									<?PHP
									$template = array(
									'table_open' => '<table class="table table-striped table-advance table-hover">'
									);
									$this->table->set_template($template);
									$this->table->set_caption('Active Quiz List');
									$this->table->set_heading('Title', 'Category', 'Start Date', 'End Date','Actions');
									echo $this->table->generate($table['Active']);
									?>
								</div>
								<div id="incomplete" class="tab-pane">
									<?PHP
									$template = array(
									'table_open' => '<table class="table table-striped table-advance table-hover">'
									);
									$this->table->set_template($template);
									$this->table->set_caption('In-Complete Quiz List');
									$this->table->set_heading('Title', 'Category', 'Start Date', 'End Date','Actions');
									echo $this->table->generate($table['Incomplete']);
									?>
								</div>
								<div id="finished" class="tab-pane">
									<?PHP
									$template = array(
									'table_open' => '<table class="table table-striped table-advance table-hover">'
									);
									$this->table->set_template($template);
									$this->table->set_caption('Finished Quiz List');
									$this->table->set_heading('Title', 'Category', 'Start Date', 'End Date','Actions');
									echo $this->table->generate($table['Finished']);
									?>
								</div>
								<div id="closed" class="tab-pane">
									<?PHP
									$template = array(
									'table_open' => '<table class="table table-striped table-advance table-hover">'
									);
									$this->table->set_template($template);
									$this->table->set_caption('Closed Quiz List');
									$this->table->set_heading('Title', 'Category', 'Start Date', 'End Date','Actions');
									echo $this->table->generate($table['Closed']);
									?>
								</div>
								<div id="create" class="tab-pane">
									<section class="panel">                                          
										<div class="panel-body bio-graph-info">
											<h1> Create New Test</h1>
											<?PHP
												$cls=array(
													'class'=>'form-horizontal'
												);
												echo form_open('quiz/add_test',$cls);
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
								</div>
							</div>
						</div>
					</section>
					<!--tab nav start-->
			</div>
		</div>
	</section>
</section>
      <!--main content end-->
<?PHP $this->load->view('template/footer'); ?>
