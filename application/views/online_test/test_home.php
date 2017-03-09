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
		redirect('user_home/logout');
	}
	$user=$this->login_database->read_user_info($mail_id);
	$this->load->helper('inflector');
	$name=$user['first_name'] . '_' . $user['last_name'];
	$name=humanize($name);
	$data=array('title'=>'Quiz');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/user_menu',$data);
	$table=$this->model_quiz->get_user_table($mail_id);
	$category= $this->model_home->category();
	
?> 
<!--main Title bar-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="page-header"><i class="fa fa fa-desktop"></i> Online Test</h4>
				<?PHP $this->load->view('template/alert'); ?>
			</div>
		</div>
		<!--collapse start-->
	<div class="row">
		<div class="col-md-offset-4 btn-row"><?PHP $i=0; ?>
			<div class="btn-group" >
				<?PHP foreach($category as $cat): 
					$str=($i==0)?'active' : '';
					$i=1;
				?>
					<button type="button" class="btn btn-primary btn-lg accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#<?= $cat['category_id']; ?>">
						<?= $cat['title']; ?>
					</button>
			  <?PHP endforeach; ?>
			  </div>
		</div>
	</div>
	  <div class="row quiz-containerr " id="accordion"><?PHP $i=0; ?>
			<?PHP foreach($category as $cat): 
				$str=($i==0)?'in' : '';
				$i=1;
				$cid=$cat['category_id'];
				$sub_category=$this->model_quiz->sub_category($cid); 
			?>
			  <div class="panel category-containerr col-md-offset-1 col-md-10 ">
				  
				  <div id="<?= $cat['category_id']; ?>" class="panel-collapse collapse <?= $str; ?>">
						<div class="panel-body" style="border:none;">
						<?PHP if(count($sub_category)>0): ?>
							<?PHP foreach($sub_category as $test): ?>
								<?PHP 
									$status=$this->model_quiz->get_test_status($test['test_id']);
									$attend=$this->model_quiz->check_attended($test['test_id'],$mail_id);
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
								<div class="col-md-12 category-container hvr-glow">
									<a  href="<?= site_url(); ?>/online_test/detail_view/<?= $test['test_id']; ?>">
										<div class="row test-row">
											<div class="col-md-8 test-title">
												<?PHP if($attend): ?>
													<i class="fa fa-check" aria-hidden="true"></i>
												<?PHP else: ?>
													<i class="fa fa-hand-o-right" aria-hidden="true"></i>
												<?PHP endif; ?>
												<?= $test['title']; ?>
											</div>
											<div class="col-md-4 test-content">
												<i class="fa fa-calendar-o" aria-hidden="true"></i>
												<?= $cmt; ?> : <?= $this->model_quiz->cdate($date); ?>
												
											</div>
										</div>
										<div class="row test-row">
											<div class="col-md-offset-1 col-md-2 test-content">
												<i class="fa fa-question-circle" aria-hidden="true"></i>
												<?= $test['no_of_questions']; ?> Questions
												
											</div>
											<div class="col-md-2 test-content">
												<i class="fa fa-hourglass-half" aria-hidden="true"></i>
												<?= $test['duration']; ?> Mins	
											</div>
										</div>
									</a>
								</div>
							<?PHP endforeach; ?>
						<?PHP else: ?>
								Tests will available soon
							
						<?PHP endif; ?>
						</div>
				  </div>
			  </div>
			<?PHP endforeach; ?>
		  
	  </div>
	  <!--collapse end-->
		
	</section>
</section>
      <!--main content end-->
<?PHP $this->load->view('template/footer'); ?>
