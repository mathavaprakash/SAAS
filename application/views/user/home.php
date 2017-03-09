<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	if($this->session->tempdata('mail_id'))
	{
		$aa=$this->session->tempdata('mail_id');
		$mail_id=$this->encryption->decrypt($aa);
		$this->session->set_tempdata('mail_id',$aa,TIMEOUT);
	}
	else
	{	
		redirect('user_home/error');
	}
	$user=$this->login_database->read_user_info($mail_id);
	
	$this->load->helper('inflector');
	$name=$user['first_name'] . '_' . $user['last_name'];
	$name=humanize($name);
	$data=array('title'=>"$name");
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/user_menu',$data);
	$online_test=$this->model_quiz->get_online_test();
	if(!isset($online_test))
	{
		redirect('user_home/error');
	}
	$topics=$this->model_home->sub_category2();
	if(!isset($topics))
	{
		redirect('user_home/error');
	}
	//$user_status=$this->model_quiz->get_user_test_status($id,$mail_id);
	
?> 
<!--main Title bar-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<div class="page-header"><i class="fa fa-tachometer" aria-hidden="true"></i>
				 Dashboard</div>
				<?PHP $this->load->view('template/alert'); ?>
				<div class="dash-view">
						<div class="row" style="padding-right:20px;">
							<div class="col-md-8 dash-title">
								<i class="fa fa-book" aria-hidden="true"></i> Recently Added Chapters
							</div>
							<div class="col-lg-offset-2 col-md-1 viewall">
								<a class="btn btn-default btn-md" href="<?= site_url(); ?>/user_home/category_view">View All</a>
							</div>
						</div>
						<div class="row">
							<?PHP foreach($topics as $top): ?>
								<a href="<?= site_url(); ?>/user_home/read_view/<?= $top['sub_id']; ?>">
									<div class="quiz-containerr col-lg-4 col-xs-12">
										<div class="col-xs-12  quiz-container  hvr-float-shadow">	
											<div class="topic-title">
												<?= $top['title']; ?>
												
											</div>
											<div class="topic-category">
													<?= $this->model_quiz->id_to_cat($top['category_id']); ?>		
												</div>	
											<div class="dash-body">
												<i class="fa fa-calendar-o" aria-hidden="true"></i>
												<?= $this->model_home->get_days_ago($top['created_date']); ?>
												
											</div>
											
										</div>
									</div>
								</a>
							
							<?PHP endforeach; ?>
						</div>
				</div>
				
				<?PHP if(isset($online_test)): ?>
					
					<div class="dash-view">
						<div class="row" style="padding-right:20px;">
							<div class="col-md-8 dash-title">
								<i class="fa fa-desktop" aria-hidden="true"></i> Recently Added Quiz
							</div>
							<div class="col-lg-offset-2 col-md-1 viewall">
								<a class="btn btn-default btn-md" href="<?= site_url(); ?>/online_test/">View All</a>
							</div>
						</div>
						<div class="row">
						<?PHP for($i=0; $i<3; $i++): 
							
							if(isset($online_test[$i])):
							$test=$online_test[$i];
							$test_status=$this->model_quiz->get_user_test_status($test['test_id'],$mail_id);
							$status=$this->model_quiz->get_test_status($test['test_id']);
						?>
							<a href="<?= site_url(); ?>/online_test/detail_view/<?= $test['test_id']; ?>">
									<div class="quiz-containerr col-md-4 col-xs-12">
										<div class="col-xs-12  quiz-container  hvr-float-shadow">	
											<div class="dash-head">
												<?= $test['title']; ?>
												<div class="dash-head-category">
													<?= $this->model_quiz->id_to_cat($test['category_id']); ?>
												</div>
											</div>
											
											<div class="dash-body">
												<?PHP 
													if($status=='Ready')
													{
														$date=$test['start_date'];
														$cmt='Starts At';
													}
													else
													{
														$date=$test['end_date'];
														$cmt='Ends At';
													}
												?>
												<div class=" col-md-offset-1 col-xs-3 dash-calendar">
													<div class="dash-date">
														<?= $cmt; ?>
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
												<div class=" col-xs-8 dash-content">
													<div class="dash-body-label">
														<i class="fa fa-calendar-o" aria-hidden="true"></i>
														<?= date("h:i:s A",strtotime($date)); ?>									
													</div>
													<div class="dash-body-label">
														<i class="fa fa-question-circle" aria-hidden="true"></i>
														<?= $test['no_of_questions']; ?> Questions									
													</div>
													<div class="dash-body-label">
														<i class="fa fa-hourglass-half" aria-hidden="true"></i>
														<?= $test['duration']; ?> Mins								
													</div>
												</div>
											</div>
										</div>	
									</div>
								</a>
							<?PHP endif; ?>
						<?PHP endfor; ?>
						</div>
					</div>
				<?PHP endif; ?>
					<div class="dash-view">
						<div class="row" style="padding-right:20px;">
							<div class="col-md-8 dash-title">
								<i class="fa fa-desktop" aria-hidden="true"></i> Overall Progress
							</div>
							<div class="col-lg-offset-2 col-md-1 viewall">
								<a class="btn btn-default btn-md" href="<?= site_url(); ?>/online_test/">View All</a>
							</div>
						</div>
						<div class="row ">
							Progress Content
						</div>
					
					</div>
			
		</div>
	</section>
</section>
      <!--main content end-->
<?PHP $this->load->view('template/footer'); ?>
