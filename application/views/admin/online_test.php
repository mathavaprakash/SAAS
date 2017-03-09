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
	$data=array('title'=>'My Profile');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/quiz_menu',$data);
?> 
<!--main Title bar-->
<section id="main-content1">
	<section class="wrapper">
		
		<div class="row">
			<div class="col-lg-3">
				
				<div class="left-panel">
					
						<div class="timer" >
							Time Left : 00:10:40
						</div>
						<div class="numbers">
							<?PHP for($i=1; $i<=10; $i++): ?>
								<div class="qbtn">
									<a data-toggle="tab" href="#q<?= $i ?>" style="color:white;"><?= $i ?></a>
								</div>
							<?PHP endfor; ?>
						</div>
						
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
						</div>
						<div class="submit-btn">
							<a class="btn btn-danger btn-md btn-block" href="" title="">submit Test</a>
							
						</div>
					
				</div>
				
			</div>
			<div class="col-lg-9">
				<div class="right-panel">
					<div class="tab-content">
						<?PHP for($i=1; $i<=10; $i++): ?>
							<div id="q<?= $i ?>" class="tab-pane">
								<div class="question_container" >
									<?= $i ?>)Question </br>Question </br>Question </br>Question </br>
								</div>
							
								<div class="option_container" >
									<div class="row">	
										<div class="col-sm-6 op">
											<input type="radio" name="options" id="option1"> Option1
										</div>
										<div class="col-sm-6 op">
											<input type="radio" name="options" id="option1"> Option2
										</div>
									</div>
									<div class="row">					
										<div class="col-sm-6 op">
											<input type="radio" name="options" id="option1"> Option3
										</div>
										<div class="col-sm-6 op">
											<input type="radio" name="options" id="option1"> Option4
										</div>
									</div>
								</div>
							</div>
						<?PHP endfor; ?>
						
					</div>
					<div class="nxt-btn">
						<div class="row">					
							<div class="col-sm-2">
								<a class="btn btn-primary" href="" title="">Mark</a>
							</div>
							<div class="col-sm-2 ">
								<a class="btn btn-primary" href="" title="">Un Mark</a>
							</div>
							<div class="col-sm-2 col-sm-offset-5">
								<a class="btn btn-primary" href="" title="">Save & Next</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</section>
</section>
      <!--main content end-->
<?PHP $this->load->view('template/footer'); ?>
