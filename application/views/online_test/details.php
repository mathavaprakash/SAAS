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
	if(! isset($user))
	{
		redirect('user_home/error');
	}
	$this->load->helper('inflector');
	$name=$user['first_name'] . '_' . $user['last_name'];
	$name=humanize($name);
	$data=array('title'=>'Quiz');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/user_menu',$data);
	if(!is_numeric($this->uri->segment(3)))
	{
		$this->load->view('template/404');
	}
	else
	{
		$id = $this->uri->segment(3);
	}
	$test= $this->model_quiz->get_test_details($id);
	if($test==FALSE) redirect('user_home/error');
	if(! isset($test))
	{
		redirect('user_home/error');
	}
	//$questions=NULL;
	$questions=$this->model_quiz->get_questions($id);
	if(! isset($questions))
	{
		redirect('user_home/error');
	}
	$test_status=$this->model_quiz->get_test_status($id);
	if(! isset($test_status))
	{
		redirect('user_home/error');
	}
	$user_status=$this->model_quiz->get_user_test_status($id,$mail_id);
	if(! isset($user_status))
	{
		redirect('user_home/error');
	}
	$attend=$this->model_quiz->check_attended($id,$mail_id);
	if(! isset($attend))
	{
		redirect('user_home/error');
	}
	$ticket=$this->model_quiz->get_ticket_id($id,$mail_id);
	if($ticket!=FALSE)
	{
		$ticket_answer=array();
		if(isset($ticket['answers']))
		{
			$tic_ans=explode('*',$ticket['answers']);
			if(count($tic_ans)>0)
			{
				for($i=0;$i<count($tic_ans);$i++)
				{
					$temp=explode('@',$tic_ans[$i]);
					$ticket_answer[$temp[0]]=$temp[1];
				}
			}
			else
			{
				$ticket_answer=FALSE;
			}
		}
		
		
	}
	$rank_list=$this->model_quiz->test_rank($id);
	if(! isset($rank_list))
	{
		redirect('user_home/error');
	}
	$my_rank=$this->model_quiz->get_my_rank($id,$mail_id);
	if(! isset($my_rank))
	{
		redirect('user_home/error');
	}
	$sno=0;
	$prev=0;
?> 
<!--main Title bar-->

<script type="text/javascript">

function closeWindow() {
window.setTimeout("Tick()", 3000);
}

function Tick() {
	window.close();
}

</script>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="instructions" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title">Instructions</h4>
			</div>
			<div class="modal-body">
				<?PHP	$cls=array('class'=>'form-horizontal','target'=>"_blank");
					echo form_open("online_test/take_test/$id",$cls);
				?>	
					<div style="padding:10px; letter-spacing: 1px;">
						1) Your Test will begin when you click "START TEST" Buttun Shown bellow.</br>
						2) You will Attend This Test Only Once.</br>
						3) You must finish this test with in <?= $test['duration']; ?> Minutes. </br>
						4) If you are not finish your test at given time, your test will automatically submit after finish of time.</br>
						5) You can review Questions & Answers with your Marks when the test was closed.</br>
						<div style="padding:10px;">
							<?PHP if($user_status=='tab1'): ?>
								<button type="submit" onclick="closeWindow()"  class="btn btn-danger btn-lg btn-block">START TEST</button>
							<?PHP endif; ?>
						</div>
					</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
</div>
<?PHP if($test_status=='Finished'): 


?>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="1view_answer" class="modal fade">
	<div class="modal-dialog" style="width:80% ">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title"><?= $test['title']; ?></h4>
			</div>
			<div class="modal-body">
					
					<div style="padding:10px; letter-spacing: 1px;">
						<?PHP $i=0; foreach ($questions as $q): 
							$correct_answer=$q['answer'];
							$qid=$q['question_id'];
							$icon=array(
								'a'=>'&nbsp;&nbsp;&nbsp;',
								'b'=>'&nbsp;&nbsp;&nbsp;',
								'c'=>'&nbsp;&nbsp;&nbsp;',
								'd'=>'&nbsp;&nbsp;&nbsp;'
							);
							$ans_class=array(
								'a'=>'',
								'b'=>'',
								'c'=>'',
								'd'=>''
							);
							$ans_class[$correct_answer]='correct';
							if(isset($ticket_answer[$qid]))
							{
								$stud_answer=$ticket_answer["$qid"];
								if($correct_answer==$stud_answer)
								{
									$icon[$stud_answer]='<i class="fa fa-check correct" aria-hidden="true"></i>';
									
								}
								else
								{
									$icon[$stud_answer]='<i class="fa fa-close wrong" aria-hidden="true"></i>';
								}
								
							}
							
						?>
							<div class="col-lg-12">
								<?= $i=$i+1; ?> ) <?= $q['question']; ?>
							</div>
							<div class="row col-sm-offset-1" >
								<div class="col-sm-5 <?= $ans_class['a']; ?>">
									<?= $icon['a']; ?>
									A) <?= $q['option_a']; ?>
								</div>
								<div class="col-sm-5 <?= $ans_class['b']; ?>">
									<?= $icon['b']; ?>
									B) <?= $q['option_b']; ?>
								</div>
								<div class="col-sm-5 <?= $ans_class['c']; ?>">
									<?= $icon['c']; ?>
									C) <?= $q['option_c']; ?>
								</div>
								<div class="col-sm-5 <?= $ans_class['d']; ?>">
									<?= $icon['d']; ?>
									D) <?= $q['option_d']; ?>
								</div>
							</div>
							</br>
						<?PHP endforeach; ?>
					</div>
			</div>
		</div>
	</div>
</div>
<?PHP endif; ?>
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h3 class="page-header"><i class="fa fa fa-bars"></i> Quiz</h3>
				<?PHP $this->load->view('template/alert'); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="test-container">
					<div class="row">
						<div class="col-md-2">
							<div class="dash-calendar  col-md-offset-1 col-xs-7 ">
								<div class="dash-date">
									Starts at <?PHP $date=$test['start_date']; ?>
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
								<?= $test['title']; ?>
							</div>
							<div class="test-category">
								<?= $this->model_quiz->id_to_cat($test['category_id']); ?>
							</div>
						</div>
						<div class="col-md-2">
							<div class="dash-calendar  col-md-offset-1 col-xs-7 ">
								<div class="dash-date">
									Ends at <?PHP $date=$test['end_date']; ?>
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
					</div>
					<div class="row">
						<div class="col-md-3 row">
							<div class="test-nos">
								<i class="fa fa fa-question-circle-o"></i>
								Questions : &nbsp;&nbsp;<?= $test['no_of_questions']; ?>
							</div>
							<div class="test-nos">
								<i class="fa fa fa-hourglass-2"></i>
								Duration &nbsp;&nbsp;: &nbsp;&nbsp;<?= $test['duration']; ?> Mins
							</div>
						</div>
						
						<div class="col-sm-offset-1 col-md-5 row">
							<div class="test-button">
								<?PHP if($test_status=='Ready'): ?>
									<button class="btn btn-danger btn-lg btn-block" disabled>Test will Available Soon.</button>
								<?PHP elseif($test_status=='Active'  && !$attend): ?>
									<a class="btn btn-danger btn-lg btn-block"  data-toggle="modal" href="#instructions">Take Test</a>
								<?PHP elseif($test_status=='Active'  && $attend): ?>
									<button class="btn btn-danger btn-lg btn-block" disabled>Answers will Available Soon.</button>
								<?PHP elseif($test_status=='Finished'): ?>
									<a class="btn btn-primary btn-lg btn-block accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#view_answer">View Answers</a>
									
								<?PHP endif; ?>
							</div>
						</div>
						<div class="col-md-3 row">
							<div class="test-quiz-takers">
								<i class="fa fa fa-trophy"></i>
								Your Rank&nbsp; :&nbsp; <?= $my_rank['rank']; ?>
							</div>
							<div class="test-quiz-takers">
								<i class="fa fa fa-users"></i>
								Quiz Takers&nbsp; :&nbsp; <?= $my_rank['takers']; ?>
							</div>
						</div>	
					</div>
				</div>
			</div>
		</div>
		<?PHP if($attend): ?>
			<div class="row quiz-containerr " id="accordion">
			
				<div class="category-containerr col-md-offset-1 col-md-10 " style="padding-bottom:0px;">
				  
					<div id="view_answer" class="panel-collapse collapse">
						<div style="border:none;">
							<div class="col-xs-12 ans-instructions ">
								<i class="fa fa-check correct" aria-hidden="true"></i> - Your Answer is Correct &nbsp;&nbsp;
								<i class="fa fa-close wrong" aria-hidden="true"></i> - Your Answer is Wrong &nbsp;&nbsp;
								<div class="correct" style="display:inline;"> correct answer in green color </div>
							</div>
							<div class="row ">
								<div style="padding:10px; letter-spacing: 1px;" id="accordion1">
									<?PHP $i=0; foreach ($questions as $q): 
										$correct_answer=$q['answer'];
										$qid=$q['question_id'];
										$icon=array(
											'a'=>'',
											'b'=>'',
											'c'=>'',
											'd'=>''
										);
										$ans_class=array(
											'a'=>'',
											'b'=>'',
											'c'=>'',
											'd'=>''
										);
										$ans_class[$correct_answer]='correct';
										if(isset($ticket_answer[$qid]))
										{
											$stud_answer=$ticket_answer["$qid"];
											if($correct_answer==$stud_answer)
											{
												$icon[$stud_answer]='<i class="fa fa-check correct" aria-hidden="true"></i>';
												
											}
											else
											{
												$icon[$stud_answer]='<i class="fa fa-close wrong" aria-hidden="true"></i>';
											}
											
										}
										else
										{
											$icon[$correct_answer]='<i class="fa fa-cirle" aria-hidden="true"></i>';
										}
										
									?>
										<div class="col-lg-12 ans-question">
											<?= $i=$i+1; ?>) <?= $q['question']; ?>
										</div>
										<div class="row col-sm-offset-1" >
											<div class="row col-sm-12 ans-option" >
												<div class="col-sm-6 <?= $ans_class['a']; ?>">
													<div class="col-xs-1"><?= $icon['a']; ?></div>
													<div class="col-xs-11">A) <?= $q['option_a']; ?></div>												
												</div>
												<div class="col-sm-6 <?= $ans_class['b']; ?>">
													<div class="col-xs-1"><?= $icon['b']; ?></div>
													<div class="col-xs-11">B) <?= $q['option_b']; ?></div>												
												</div>
												<div class="col-sm-6 <?= $ans_class['c']; ?>">
													<div class="col-xs-1"><?= $icon['c']; ?></div>
													<div class="col-xs-11">C) <?= $q['option_c']; ?></div>												
												</div>
												<div class="col-sm-6 <?= $ans_class['d']; ?>">
													<div class="col-xs-1"><?= $icon['d']; ?></div>
													<div class="col-xs-11">D) <?= $q['option_d']; ?></div>												
												</div>
											</div>
										</div>
										<?PHP if(($q['explanation']!=NULL)): ?>
											<div class="row col-sm-offset-1 ans-explain" >
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
			<div class="row">
				<div class="col-xs-12">
					<div class="test-container1">
						<?PHP if($attend['status']==2): ?>
							<div class="row test-header1">
								Congratulations <?= $name; ?>..!  You have Completed This Quiz.
							</div>
							<div class=" row test-smily">
								<i class="fa fa fa-smile-o fa-5x"></i>
							</div>
							<div class=" row test-star1">
									<?PHP 
									//$star=$this->model_quiz->get_star(0,500);
									$tot_points=$test['no_of_questions']*100;
									$star=$this->model_quiz->get_star($attend['marks'],$tot_points);
									$star=5-$star;
									for($i=0;$i<$star;$i++):  ?> 
										<i class="fa fa-star" aria-hidden="true"></i>
									<?PHP endfor; ?>
									<?PHP for($i=$star;$i<5;$i++):  ?> 
										<i class="fa fa-star-o" aria-hidden="true"></i>
									<?PHP endfor; ?>
									
							</div>
							<div class="row test-header2">
								<?= $attend['marks']; ?> / <?= $tot_points; ?>  
							</div>
							<div class="row test-content1">
								<div class="col-md-offset-1 col-md-4">
									<i class="fa fa fa-calendar"></i>
									Attended on : <?= $this->model_quiz->cdate($attend['start_date']); ?>
								</div>
								<div class="col-md-4">
									<i class="fa fa fa-calendar"></i>
									Submited on : <?= $this->model_quiz->cdate($attend['end_date']); ?>
								</div>
								<div class="col-md-2">
									<i class="fa fa fa-hourglass-3"></i>
									Time Taken : <?PHP
										$rem=strtotime($attend['end_date']) - strtotime($attend['start_date']);
										if($rem>0 && $rem<3600)
											echo date('i:s',$rem);
										elseif($rem>3600)
											echo date('h:i:s',$rem);
										
									?>
								</div>
							</div>
						<?PHP else: ?>
							<div class="row test-header1">
								Sorry <?= $name; ?>..!  You Lose This Quiz.
							</div>
							<div class=" row test-smily" style="color:red;">
								<i class="fa fa fa-frown-o fa-5x"></i>
							</div>
							<div class=" row test-star">
									<?PHP 
									//$star=$this->model_quiz->get_star(0,500);
									$tot_points=$test['no_of_questions']*100;
									$star=$this->model_quiz->get_star($attend['marks'],$tot_points);
									$star=5-$star;
									for($i=0;$i<$star;$i++):  ?> 
										<i class="fa fa-star" aria-hidden="true"></i>
									<?PHP endfor; ?>
									<?PHP for($i=$star;$i<5;$i++):  ?> 
										<i class="fa fa-star-o" aria-hidden="true"></i>
									<?PHP endfor; ?>
									
							</div>
							<div class="row test-header2">
								<?= $attend['marks']; ?> / <?= $tot_points; ?>  
							</div>
							<div class="row test-content1">
								<div class="col-md-offset-4 col-md-6">
										Your Test was Not Submitted well/Incomplete. You Lose This Test.
								</div>
								
							</div>
						<?PHP endif; ?>
					</div>
				</div>
			</div>
			
			
		<?PHP endif; ?>
		
		
		<div class="row">
			<div class="col-lg-7">
				<section class="panel">
					<header class="panel-heading tab-bg-primary ">
						<div class="col-lg-offset-5" style="color:#fff;">Stats</div>
					</header>
					<div class="panel-body">
						<div class="col-lg-12">
							
						</div>
					</div>
								
				</section>
			</div>
			
			<div class="col-lg-5">
			<?PHP if(isset($rank_list)): ?>
				<section class="panel">
					<header class="panel-heading tab-bg-primary ">
						<div class="col-lg-offset-5" style="color:#fff;">Rank List</div>
					</header>
					<div class="panel-body">
						<table class="table table-striped table-advance table-hover">
							<thead>
								<tr>
									<td>Name</td>
									<td>Points</td>
									<td>Rank</td>
								</tr>
							</thead>		
							<tbody>
								<?PHP for($i=0;$i<10;$i++): ?>
									<?PHP	if(isset($rank_list[$i])): 
										$rank=$rank_list[$i];
										$student=$this->model_quiz->get_student_details($rank['mail_id']);
										$sname=$student['first_name'] . '_' . $student['last_name'];
										$sname=humanize($sname);
										$stud_id=$this->login_database->encryptor('encrypt',$rank['mail_id']);
												
									?>
										<tr <?PHP if($rank['mail_id']==$mail_id) echo ' class="danger"';?>>
											<td> <?= anchor("user_home/view_student_profile/$stud_id",$sname); ?></td>
											<td><?= $rank['marks']; ?></td>
											<td><?= $rank['rank']; ?></td>
										</tr>
										
									<?PHP endif;?> 
								<?PHP endfor; ?>
							</tbody>
						</table>
						<?PHP if($my_rank['rank']>0): ?>
							<div class="rank-display">
								Your Rank is <?= $my_rank['rank']; ?>
							</div>
						<?PHP else: ?>
							<div class="rank-display">
								You are not attended this test.
							</div>
						<?PHP endif; ?>
					</div>
				</section>
			<?PHP endif; ?>
			</div>
		</div>
	</section>
</section>
      <!--main content end-->
<?PHP $this->load->view('template/footer'); ?>
