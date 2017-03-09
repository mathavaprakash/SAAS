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
	$data=array('title'=>'Quiz');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/user_menu',$data);
	$table=$this->model_quiz->get_user_table($mail_id);
	$category= $this->model_home->category();
	$my_overall_rank=$this->model_quiz->get_overall_rank($mail_id);
?> 
<!--main Title bar-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="page-header"><i class="fa fa-graduation-cap" aria-hidden="true"></i>Leader Board</h4>
				<?PHP $this->load->view('template/alert'); ?>
			</div>
		</div>
		<!--collapse start-->
	<div class="row">
		<div class="col-md-offset-3 btn-row">
			<div class="btn-group" >
				<button type="button" class="btn btn-primary btn-lg accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#overall">
						Overall Rank List
				</button>
				<?PHP foreach($category as $cat): ?>
					<button type="button" class="btn btn-primary btn-lg accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#<?= $cat['category_id']; ?>">
						<?= $cat['title']; ?>
					</button>
			  <?PHP endforeach; ?>
			  </div>
		</div>
	</div>
	  <div class="row quiz-containerr " id="accordion">
			
			  <div class="panel category-containerr col-md-offset-2 col-md-8 ">
				  
				  <div id="overall" class="panel-collapse collapse in">
						<div class="panel-body" style="border:none;">
							<div class="row scrollbar-container">
								<?PHP $rank_list=$this->model_quiz->get_rank_list();
										?>
										<?PHP if(isset($rank_list)): $i=0; ?>
										
								<table class="table table-striped table-advance table-hover">
									<thead>
										<tr>
											<td>S.NO</td>
											<td>Name</td>
											<td>Points</td>
											<td>Rank</td>
										</tr>
									</thead>		
									<tbody>
										<?PHP foreach($rank_list as $rank): ?>
											<?PHP	 
												$student=$this->model_quiz->get_student_details($rank['mail_id']);
												$sname=$student['first_name'] . '_' . $student['last_name'];
												$sname=humanize($sname);
												$stud_id=$this->login_database->encryptor('encrypt',$rank['mail_id']);
												
											?>
												<tr <?PHP if($rank['mail_id']==$mail_id) echo ' class="danger"';?>>
													<td><?= $i=$i+1; ?></td>
													<td> <?= anchor("user_home/view_student_profile/$stud_id",$sname); ?></td>
													<td><?= $rank['marks']; ?></td>
													<td><?= $rank['rank']; ?></td>
												</tr>
												
											
										<?PHP endforeach; ?>
										
										
									</tbody>
								</table>
								<?PHP else: ?>
									<div class="rank-display">No content Available</div>
								<?PHP endif; ?>
								
							</div>
							<div class="row rank-display">
							<?PHP if($my_overall_rank['rank']>0): ?>
									<div class=" col-lg-12">
										Your Overall Rank is <?= $my_overall_rank['rank']; ?>
									</div>
								<?PHP else: ?>
									<div class="col-lg-12">
										You are not attended any test.
									</div>
								<?PHP endif; ?>
						</div>
						</div>
						
				  </div>
				  <?PHP foreach($category as $cat): 
				$cid=$cat['category_id'];
				$rank_list=$this->model_quiz->get_cat_rank_list($cid);
				
				?>
				  <div id="<?= $cid; ?>" class="panel-collapse collapse">
						<div class="panel-body" style="border:none;">
							<div class="row scrollbar-container">
								<?PHP if($rank_list!=FALSE): $i=0; $my_cat_rank=0; ?>
								<table class="table table-striped table-advance table-hover">
									<thead>
										<tr>
											<td>S.NO</td>
											<td>Name</td>
											<td>Points</td>
											<td>Rank</td>
										</tr>
									</thead>		
									<tbody><?PHP $my_cat_rank=0; ?>
										<?PHP foreach($rank_list as $rank): ?>
											<?PHP	 
												$student=$this->model_quiz->get_student_details($rank['mail_id']);
												$sname=$student['first_name'] . '_' . $student['last_name'];
												$sname=humanize($sname);
												//$stud_id=$this->encryption->encrypt($sname);
												$stud_id=$this->login_database->encryptor('encrypt',$rank['mail_id']);
												if($rank['mail_id']==$mail_id)
												{
													$my_cat_rank=$rank['rank'];
												}
											?>
												<tr <?PHP if($rank['mail_id']==$mail_id){
													echo ' class="danger"';
													
												} ?>>
													<td><?= $i=$i+1; ?></td>
													<td> <?= anchor("user_home/view_student_profile/$stud_id",$sname); ?></td>
													<td><?= $rank['marks']; ?></td>
													<td><?= $rank['rank']; ?></td>
												</tr>
												
											
										<?PHP endforeach; ?>
										
										
									</tbody>
								</table>
								<?PHP if($my_cat_rank>0): ?>
									<div class="rank-display">
										Your Rank is <?= $my_cat_rank; ?>
									</div>
								<?PHP else: ?>
									<div class="rank-display">
										You are not attended any test in this category.
									</div>
								<?PHP endif; ?>
								<?PHP else: ?>
									<div class="rank-display">No content Available</div>
								<?PHP endif; ?>
								
							</div>
						</div>
				  </div>
				 <?PHP endforeach; ?> 
				  
			  </div>
			
	  </div>
	  <!--collapse end-->
		
	</section>
</section>
      <!--main content end-->
<?PHP $this->load->view('template/footer'); ?>
