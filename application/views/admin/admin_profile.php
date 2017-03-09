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
	$this->load->view('template/admin_menu',$data);
	
	$profile_pic=$this->login_database->profile_pic_url($mail_id);
?>
  
      <!--main content start-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h3 class="page-header"><i class="fa fa-user-md"></i> My Profile</h3>
			</div>
		</div>
		<?PHP $this->load->view('template/alert'); ?>
		<div class="row">
			<!-- profile-widget -->
			<div class="col-lg-12">
				<div class="profile-widget profile-widget-info">
					<div class="panel-body">
						<div class="col-lg-2 col-sm-2">
							<div class="follow-ava" >
								<a href="#myModal-2" data-toggle="modal"><img src="<?= base_url() . $profile_pic; ?>" style="height:150px; width:130px" title="Change Picture" alt="<?= $name; ?>"></a>
							</div>
						</div>
						<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal-2" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
										<h4 class="modal-title">Upload your Profile Picture</h4>
									</div>
									<div class="modal-body">
										<?PHP echo form_open_multipart('admin_home/upload_picture'); ?>
											<div class="error_msg"> Allowed Formats .jpg /.jpeg /.png; Maximum Resolution = 1000*1000; Maximum size = 1 MB </div>
											<div class="form-group">
												<input type="file" name="file" accept=".jpg,.jpeg,.png" class="form-control lg-input" id="exampleInputEmail5" placeholder="Choose File">
											</div>
											<button type="submit" class="btn btn-success">Upload</button>
										<!--</form>--><?PHP echo form_close(); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-sm-4 follow-info"><p></p>
							<h4><?= $name; ?></h4>
							<p><?= $user['mail_id']; ?></p>
							<h6>
								<span><i class="icon_calendar"></i>Last Login : 25.12.2016 11:05 AM </span>
							</h6>
						</div>
						<div class="col-lg-2 col-sm-6 follow-info weather-category">
							<ul>
								<li class="active">
									<i class="fa fa-comments fa-2x"> </i><br>
									<p>your overall Rank </p><h3>56 / 5046</h3>
								</li>
							</ul>
						</div>
						<div class="col-lg-2 col-sm-6 follow-info weather-category">
							<ul>
								<li class="active">
								<i class="fa fa-bell fa-2x"> </i><br>
								<p>your Total points </p><h3>2340 </h3>
								</li>
							</ul>
						</div>
						<div class="col-lg-2 col-sm-6 follow-info weather-category">
							<ul>
								<li class="active">
								<i class="fa fa-tachometer fa-2x"> </i><br>
								<p>Performance </p><h3>Good </h3>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading tab-bg-info">
						<ul class="nav nav-tabs">
							
							<li>
								<a data-toggle="tab" href="#profile">
								<i class="icon-user"></i>
								Profile
								</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#edit-profile">
								<i class="icon-envelope"></i>
								Edit Profile
								</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#change-pwd">
								<i class="icon-envelope"></i>
								Change Password
								</a>
							</li>
						</ul>
					</header>
					<div class="panel-body">
						<div class="tab-content">
							
							<!-- profile -->
							<div id="profile" class="tab-pane active">
								<section class="panel">
									
									<div class="panel-body bio-graph-info">
										<h1>Bio Graph</h1>
										<div class="row">
											<div class="bio-row">
												<p><span>First Name </span>: <?= $user['first_name']; ?> </p>
											</div>
											<div class="bio-row">
												<p><span>Last Name </span>: <?= $user['last_name']; ?></p>
											</div>                                              
											<div class="bio-row">
												<p><span>Birthday</span>: <?= $user['dob']; ?></p>
											</div>
											<div class="bio-row">
												<p><span>Gender </span>: <?= $user['gender']; ?></p>
											</div>
											
											<div class="bio-row">
												<p><span>Email </span>:<?= $user['mail_id']; ?></p>
											</div>
											<div class="bio-row">
												<p><span>Mobile </span>: <?= $user['phone']; ?></p>
											</div>
											
										</div>
									</div>
								</section>
								
							</div>
							<!-- edit-profile -->
							<div id="edit-profile" class="tab-pane">
								<section class="panel">                                          
									<div class="panel-body bio-graph-info">
										<h1> Profile Info</h1>
										<form class="form-horizontal" role="form">                                                  
											<div class="form-group">
												<label class="col-lg-2 control-label">First Name</label>
												<div class="col-lg-6">
													<input type="text" class="form-control" id="f-name" placeholder=" ">
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-2 control-label">Last Name</label>
												<div class="col-lg-6">
													<input type="text" class="form-control" id="l-name" placeholder=" ">
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-2 control-label">About Me</label>
												<div class="col-lg-10">
													<textarea name="" id="" class="form-control" cols="30" rows="5"></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-lg-offset-2 col-lg-10">
													<button type="submit" class="btn btn-primary">Save</button>
													<button type="button" class="btn btn-danger">Cancel</button>
												</div>
											</div>
										</form>
									</div>
								</section>
							</div>
							<!-- change Password -->
							<div id="change-pwd" class="tab-pane">
								<section class="panel">                                          
									<div class="panel-body bio-graph-info">
										<h1> Change password</h1>
										<form class=" form-horizontal " method="post" action="http://localhost/saas/index.php/admin_home/change_password">	
										
											<div class="form-group">
												<label for="inputEmail1" class="col-lg-4 control-label">Enter Old Password</label>
												<div class="col-lg-4">
													<input type="password" name="oldpwd"class="form-control" id="inputEmail4" placeholder="Email">
												</div>
											</div>
											<div class="form-group">
												<label for="inputPassword1" class="col-lg-4 control-label">New Password</label>
												<div class="col-lg-4">
													<input type="password" name="newpwd" class="form-control" id="inputPassword4" placeholder="Password">
												</div>
											</div>
											<div class="form-group">
												<label for="inputPassword1" class="col-lg-4 control-label">Conform Password</label>
												<div class="col-lg-4">
													<input type="password" name="conpwd" class="form-control" id="inputPassword4" placeholder="Password">
												</div>
											</div>
											
											<div class="form-group">
												<div class="col-lg-offset-4 col-lg-6">
													<button type="submit" class="btn btn-info">Change Password</button>
												</div>
											</div>
										</form>
										
									</div>
								</section>
							</div>
						</div>
					</div>
				</section>
			</div>
		</div>
              <!-- page end-->
	</section>
</section>
<script>
//knob
$(".knob").knob();
</script>
<?PHP $this->load->view('template/footer'); ?>
      