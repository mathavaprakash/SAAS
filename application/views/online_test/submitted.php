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
	//$this->load->view('template/user_menu',$data);
	if(!is_numeric($this->uri->segment(3)))
	{
		$this->load->view('template/404');
	}
	else
	{
		$id = $this->uri->segment(3);
	}
	$test= $this->model_quiz->get_test_details($id);
	//$questions=NULL;
	$questions=$this->model_quiz->get_questions($id);
	$test_status=$this->model_quiz->get_test_status($id);
	$user_status=$this->model_quiz->get_user_test_status($id,$mail_id);
	$attend=$this->model_quiz->check_attended($id,$mail_id);
	$rank_list=$this->model_quiz->test_rank($id);
	$my_rank=$this->model_quiz->get_my_rank($id,$mail_id);
	$sno=0;
	$prev=0;
?> 
<!--main Title bar-->

<script type="text/javascript">
function closeWindow() {
window.setTimeout("Tick()", 3000);
}

function Tick() {
	//window.location="<?= site_url(); ?>";
	window.close();
}

</script>
<body class="error-container err-bg">
	<div class="row">
		<?PHP if($attend): ?>
			<?PHP if($attend['status']==2): ?>
				<div class="page-404-header">
					Congratulations <?= $name; ?>..!  You have Completed This Quiz.
				</div>
				<div class="row">
					
					<div class="page-404 ">
						<p class=" hvr-pop"><i class=" text-404 fa fa fa-smile-o fa-5x"></i></p>
					</div>
				</div>
			<?PHP else: ?>
				<div class="page-404-header">
				Sorry <?= $name; ?>..!  You Lose This Quiz.
			</div>
			<div class="row">
				
				<div class="page-404 ">
					<p class=" hvr-pop"><i class=" text-404 fa fa fa-frown-o fa-5x"></i></p>
				</div>
			</di
			<?PHP endif; ?>
			<div class=" row test-star">
					<?PHP 
					//$star=$this->model_quiz->get_star(0,500);
					$tot_points=$test['no_of_questions']*100;
					$star=$this->model_quiz->get_star($attend['marks'],$tot_points);
					$star=5-$star; ?>
					<?PHP for($i=0;$i<$star;$i++):  ?> 
						<i class="fa fa-star" aria-hidden="true"></i>
					<?PHP endfor; ?>
					<?PHP for($i=$star;$i<5;$i++):  ?> 
						<i class="fa fa-star-o" aria-hidden="true"></i>
					<?PHP endfor; ?>
					
			</div>
			<div class="row test-header2">
				<?= $attend['marks']; ?> / <?= $tot_points; ?>  
			</div>
			<div class="success-content">
				<a href="<?= site_url(); ?>/user_home/index" target="_blank" onclick="closeWindow()"  class="btn btn-danger btn-lg">GOTO HOME</a>
			</div>
				
		<?PHP endif; ?>
	</div>
	<?PHP $this->load->view('template/footer'); ?>

