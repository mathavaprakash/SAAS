<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	if($this->session->tempdata('mail'))
	{
		$aa=$this->session->tempdata('mail');
		$aa1=$this->session->tempdata('ticket_id');
		$mail_id=$this->encryption->decrypt($aa);
		$ticket_id=$this->encryption->decrypt($aa1);
		$profile_pic=$this->login_database->profile_pic_url($mail_id);
	}
	else
	{
		redirect('user_home/error');
	}
	$ticket=$this->model_quiz->get_ticket_details($ticket_id);
	if(!isset($ticket))
	{
		$this->session->set_flashdata("message","ticket not found");
		redirect('user_home/error');
	}
	$test_id=$ticket['test_id'];
	$test=$this->model_quiz->get_test_details($test_id);
	if(!isset($test))
	{
		$this->session->set_flashdata("message","test not found");
		redirect('user_home/error');
	}
	$st_time=$test['start_date'];
	$duration=$test['duration'];
	$no_of_questions=$test['no_of_questions'];
	$start_date=$ticket['start_date'];
	
	$questions=$this->model_quiz->get_questions($test_id);
	if(!isset($questions))
	{
		$this->session->set_flashdata("message","questions not found");
		redirect('user_home/error');
	}
	$user=$this->login_database->read_user_info($mail_id);
	$this->load->helper('inflector');
	$name=$user['first_name'] . '_' . $user['last_name'];
	$name=humanize($name);
	$data=array('title'=>'Online Test');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	//$this->load->view('template/quiz_menu',$data);
	$remain=((int)(strtotime($start_date))+($duration * 60))-(int)now();
	$seconds=$remain;							
?> 
<!--main Title bar-->
<style type="text/css">
	.black{
		background:black;
		color:white;
	}
	.red{
		background:red;
		color:white;
	}
	.green{
		background:green;
		color:white;
	}
	.green{
		background:blue;
		color:white;
	}
</style>
<script>

var Timer;
var TotalSeconds;


function CreateTimer(TimerID, Time) {
Timer = document.getElementById(TimerID);
TotalSeconds = Time;
UpdateTimer()
window.setTimeout("Tick()", 1000);
}

function Tick() {
if (TotalSeconds <= 0) {
	window.setTimeout("Tick1()", 4000);
document.forms["quiz"].submit();
//alert("Time's up!")
return;
}

TotalSeconds -= 1;
UpdateTimer()
window.setTimeout("Tick()", 1000);
}

function UpdateTimer() {
var Seconds = TotalSeconds;

var Days = Math.floor(Seconds / 86400);
Seconds -= Days * 86400;

var Hours = Math.floor(Seconds / 3600);
Seconds -= Hours * (3600);

var Minutes = Math.floor(Seconds / 60);
Seconds -= Minutes * (60);


var TimeStr = ((Days > 0) ? Days + " days " : "") + LeadingZero(Hours) + ":" + LeadingZero(Minutes) + ":" + LeadingZero(Seconds)


Timer.innerHTML = TimeStr;
}


function LeadingZero(Time) {

return (Time < 10) ? "0" + Time : + Time;

}

//var myCountdown1 = new Countdown({time:<?php echo $seconds;?>, rangeHi:"hour", rangeLo:"second"});
//setTimeout(submitform,'<?= ($seconds * 1000); ?>');
//function submitform(){
//alert('Time Over');
//document.forms["quiz"].submit();
//window.location="<?php echo site_url("online_test/submit_test/$ticket_id");?>";
//}

 function closeWindow() {
window.setTimeout("Tick1()", 1000);
}

function Tick1() {
	window.close();
}

</script>



<body  onload="('timer', <?php echo $seconds;?>)">
	<section id="container" class="">
		<header class="header dark-bg">
			<a href="" class="logo"> <span class="lite">SAAS</span></a>
			<div class="top-nav notification-row">                
				<ul class="nav pull-right top-menu">
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">
							<span class="profile-ava">
								<img width=35 height=40 alt="" src="<?= base_url() . $profile_pic; ?>">
							</span>
							<span class="username">Welcome! <?PHP print ' ' .$name; ?> </span>
							
						</a>
					</li>
				</ul>
			</div>
		</header>      

<section id="main-content1">
	<section class="wrapper">
		
		<div class="row">
			<?PHP	$cls=array('class'=>'form-horizontal','name'=>'quiz','target'=>"_blank");
							echo form_open("online_test/submit_test/$ticket_id",$cls);
						?>
			<div class="col-lg-3">
				
				<div class="left-panel">
					
						<div class="timer">Time left: <span id='timer' ><script type="text/javascript">window.onload = CreateTimer("timer", <?php echo $seconds;?>);</script>
						</div>
						<div class="numbers">
							<?PHP $i=0; foreach ($questions as $q): ?>
								<a data-toggle="tab"  href="#q<?= $q['question_id']; ?>" style="color:white;">
							<!--	<div class="qbtn" ng-click="visitQuestion()" ng-class="class" id="<?= $q['question_id']; ?>">
							-->	<div class="qbtn" id="<?= $q['question_id']; ?>">
									<?= $i=$i+1; ?>
								</div></a>
							
							<?PHP endforeach; ?>
						</div>
					<!--	
						<div class="info">
							<div class="col-sm-6">
								<div class="qbx" style="background:black"></div> 
								<div class="info-text">	Not Attended</div>
							</div>
							<div class="col-sm-6">
								<div class="qbx" style="background:green"></div> 
								<div class="info-text">	Answered</div>
							</div>
						</div>
						<div class="info">
							<div class="col-sm-6">
								<div class="qbx" style="background:red"></div> 
								<div class="info-text">	Not Answered</div>
							</div>
							<div class="col-sm-6">
								<div class="qbx" style="background:blue"></div> 
								<div class="info-text">	Marked</div>
							</div>
						</div>  -->
						<div class="submit-btn">
							<button class="btn btn-danger btn-lg btn-block" onclick="closeWindow()" type="submit">Submit Test</button>
						</div>
					
				</div>
				
			</div>
			<div class="col-lg-9">
				<div class="right-panel">
					<div class="tab-content">
						<?PHP $i=0; foreach ($questions as $q): 
							$str=($i==0)?'active' : '';
						?>
							<div id="q<?= $q['question_id']; ?>" class="tab-pane <?= $str; ?>">
								<div class="question_container" >
									<?= $i=$i+1; ?>) <?= $q['question']; ?>
								</div>
							
								<div class="option_container" >
									<div class="row">	
										<div class="col-sm-6 op">
											<input type="radio"  ng-click="answerQuestion()" name="<?= $q['question_id']; ?>" value="a"> <?= $q['option_a']; ?>
										</div>
										<div class="col-sm-6 op">
											<input type="radio"  ng-click="answerQuestion()" name="<?= $q['question_id']; ?>" value="b"> <?= $q['option_b']; ?>
										</div>
									</div>
									<div class="row">					
										<div class="col-sm-6 op">
											<input type="radio"  ng-click="answerQuestion()" name="<?= $q['question_id']; ?>" value="c"> <?= $q['option_c']; ?>
										</div>
										<div class="col-sm-6 op">
											<input type="radio"  ng-click="answerQuestion()" name="<?= $q['question_id']; ?>" value="d"> <?= $q['option_d']; ?>
										</div>
									</div>
								</div>
							</div>
						<?PHP endforeach; ?>
						
					</div>
				<!--	<div class="nxt-btn">
						<div class="row">					
							<div class="col-sm-2" >
								<a class="btn btn-primary" id="aaa"  title="">Mark</a>
							</div>
							<div class="col-sm-2 ">
								<a class="btn btn-primary" id="bbb"  title="">Un Mark</a>
							</div>
							<div class="col-sm-2 col-sm-offset-5">
								<a class="btn btn-primary" href="" title="">Save & Next</a>
							</div>
						</div>
					</div> -->
				</div>
			</div>
			<?= form_close(); ?>
		</div>
		
	</section>
</section>
<script type="text/javascript" src=" <?= base_url(); ?>js/basic.js"></script>
<script type="text/javascript">
var app = angular.module("app",[]);

  app.controller("con",function($scope){

    $scope.class = "black";
	
    $scope.visitQuestion = function(){
	var ele = angular.element( document.querySelector( '#id' ) );
      if ($scope.class === "black")
        $scope.class = "red";
      
    };
	$scope.answerQuestion = function($scope){
      if ($scope.class === "red" || $scope.class === "black")
        $scope.class = "green";
      
    };
	$scope.nextQuestion = function($scope){
      if ($scope.class === "red" || $scope.class === "black")
        $scope.class = "green";
      
    };
	$scope.markQuestion = function($scope){
       $scope.class = "green";
      
    };
  });
</script>

<!--   ---- prevent reload page  ------
<script type="text/javascript">
    window.onbeforeunload = function() {
        return "Dude, are you sure you want to leave?";
    }
</script>

-->
      <!--main content end-->
<?PHP $this->load->view('template/footer'); ?>
