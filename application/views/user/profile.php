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
	$data=array('title'=>'My Profile');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/user_menu',$data);
	$rank=$this->model_quiz->get_overall_rank($mail_id);
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
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal-2" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
						<h4 class="modal-title">Upload your Profile Picture</h4>
					</div>
					<div class="modal-body">
						<?PHP echo form_open_multipart('user_home/upload_picture'); ?>
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
		<div class="row">
			<!-- profile-widget -->
			<div class="col-lg-12">
				<div class="profile-widget profile-widget-info">
					<div class="panel-body" style="background:#00b36b;">
						<div class="col-lg-2 col-sm-2">
							<div class="follow-ava hvr-bounce-in" >
								<a href="#myModal-2" data-toggle="modal"><img src="<?= base_url() . $profile_pic; ?>" style="height:150px; width:130px" title="Change Picture" alt="<?= $name; ?>"></a>
							</div>
						</div>
						
						<div class="col-lg-4 col-sm-4 follow-info">
							<div class="col-xs-12 profile-name"><?= $name; ?></div>
							<div class="col-xs-12 profile-mail-id"><?= $user['mail_id']; ?></div>
							
						</div>
						<div class="col-lg-2 col-sm-6 profile-box">
							<div class="col-xs-12">
								<i class="fa fa-balance-scale fa-2x" aria-hidden="true"></i>
							</div>
							<div class="col-xs-12 profile-mail-id">
								your overall Rank
							</div>
							<div class="col-xs-12 profile-name">
								<?= $rank['rank']; ?> / <?= $rank['total']; ?>
							</div>
						</div>
						<div class="col-lg-2 col-sm-6 profile-box">
							<div class="col-xs-12">
								<i class="fa fa-star fa-2x" aria-hidden="true"></i>
							</div>
							<div class="col-xs-12 profile-mail-id">
								your Total points
							</div>
							<div class="col-xs-12 profile-name">
								<?= $user['points']; ?> 
							</div>
						</div>
						<div class="col-lg-2 col-sm-6 profile-box">
							<div class="col-xs-12">
								<i class="fa fa-thermometer-half fa-2x" aria-hidden="true"></i>
							</div>
							<div class="col-xs-12 profile-mail-id">
								Performance 
							</div>
							<div class="col-xs-12 profile-name">
								<?PHP for($i=0;$i<$rank['star'];$i++):  ?> 
									<i class="fa fa-star" aria-hidden="true"></i>
								<?PHP endfor; ?>
								<?PHP for($i=$rank['star'];$i<5;$i++):  ?> 
									<i class="fa fa-star-o" aria-hidden="true"></i>
								<?PHP endfor; ?> 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- page start-->
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading tab-bg-info" style="background:#00b36b; border:none;">
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
					<div class="panel-body" style="color:black;  border:none;">
						<div class="tab-content">
							
							<!-- profile -->
							<div id="profile" class="tab-pane active">
								<section class="panel">
									
									<div class="panel-body bio-graph-info">
										<h1>Bio Graph</h1>
										<div class="row">
											<div class="col-md-offset-2 col-md-5">
												<div class="col-xs-12">
													<div class="col-xs-4 pro-label">
														First Name :
													</div>
													<div class="col-xs-8 pro-content">
														<?= $user['first_name']; ?>
													</div>
												</div>
											</div>
											<div class=" col-md-5">
												<div class="col-xs-12">
													<div class="col-xs-4  pro-label">
														Last Name :
													</div>
													<div class="col-xs-8 pro-content">
														<?= $user['last_name']; ?>
													</div>
												</div>
											</div>
											<div class="col-md-offset-2 col-md-5">
												<div class="col-xs-12">
													<div class="col-xs-4  pro-label">
														Birthday :
													</div>
													<div class="col-xs-8 pro-content">
														<?= $user['dob']; ?>
													</div>
												</div>
											</div>
											<div class=" col-md-5">
												<div class="col-xs-12">
													<div class="col-xs-4  pro-label">
														Gender :
													</div>
													<div class="col-xs-8 pro-content">
														<?= $user['gender']; ?>
													</div>
												</div>
											</div>
											<div class="col-md-offset-2 col-md-5">
												<div class="col-xs-12">
													<div class="col-xs-4  pro-label">
														Email  :
													</div>
													<div class="col-xs-8 pro-content">
														<?= $user['mail_id']; ?>
													</div>
												</div>
											</div>
											<div class=" col-md-5">
												<div class="col-xs-12">
													<div class="col-xs-4  pro-label">
														Mobile :
													</div>
													<div class="col-xs-8 pro-content">
														<?= $user['phone']; ?>
													</div>
												</div>
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
										<?PHP 
											$data=array(
												'class'=>'form-horizontal'
											);
											echo form_open('user_home/update_profile',$data);
										
										?>
										
											<div class="form-group">
												<label for="inputEmail1" class="col-lg-4 control-label">First Name</label>
												<div class="col-lg-4">
													<input type="text" name="first_name"class="form-control" id="inputEmail4" placeholder="First Name" value="<?= $user['first_name']; ?>" required>
												</div>
											</div>
											<div class="form-group">
												<label for="inputPassword1" class="col-lg-4 control-label">Last Name</label>
												<div class="col-lg-4">
													<input type="text" name="last_name" class="form-control" id="inputPassword4" placeholder="Last Name" value="<?= $user['last_name']; ?>" required >
												</div>
											</div>
										<!--	<div class="form-group">
												<label for="inputPassword1" class="col-lg-4 control-label">Gender</label>
												<div class="col-lg-4">
											<div class="btn-row" >
												<div class="btn-group " data-toggle="buttons">
													<label class="btn btn-default active"><i class="fa fa-mars fa-lg" aria-hidden="true"></i>
														<input type="radio" name="gender"  value="Male" <?PHP if($user['gender']=='Male'): ?>  <?PHP endif; ?> >  Male
													</label>
													<label class="btn btn-default"><span><i class="fa fa-venus fa-lg" aria-hidden="true"></i></span>
														<input type="radio" name="gender" value="Female" <?PHP if($user['gender']=='Female'): ?> checked <?PHP endif; ?>> &nbsp;  Female
													</label>
													<label class="btn btn-default"><i class="fa fa-transgender fa-lg" aria-hidden="true"></i>
														<input type="radio" name="gender" value="Transgender" <?PHP if($user['gender']=='Transgender'): ?> checked <?PHP endif; ?>>  Transgender
													</label>
												</div>
											</div>
											</div>
											</div>
											<div class="form-group">
												<label for="inputPassword1" class="col-lg-4 control-label">Date Of Birth</label>
												<div class="col-lg-4">
													<input type="date" name="dob" class="form-control" id="inputPassword4" placeholder="DOB" value="<?= $user['dob']; ?>" required >
												</div>
											</div> -->
											<div class="form-group">
												<label for="inputPassword1" class="col-lg-4 control-label">Phone  Number</label>
												<div class="col-lg-4">
											<div class="input-group">
												<span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
												<input type="number" name="phone" class="form-control" placeholder="phone Number" value="<?= $user['phone']; ?>" required>
											</div>
											</div>
											</div>
											<div class="form-group">
												<div class="col-lg-offset-4 col-lg-6">
													<button type="submit" class="btn btn-info">Update Profile</button>
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
										<?PHP
											$cls=array(
												'class'=>" form-horizontal "
											);
											echo form_open('user_home/change_password',$cls);
										?>
											<div class="form-group">
												<label for="inputEmail1" class="col-lg-4 control-label">Enter Old Password</label>
												<div class="col-lg-4">
													<input type="password" name="oldpwd"class="form-control" id="inputEmail4" placeholder="Old Password">
												</div>
											</div>
											<div class="form-group">
												<label for="inputPassword1" class="col-lg-4 control-label">New Password</label>
												<div class="col-lg-4">
													<input type="password" name="newpwd" class="form-control" id="inputPassword4" placeholder="New Password">
												</div>
											</div>
											<div class="form-group">
												<label for="inputPassword1" class="col-lg-4 control-label">Conform Password</label>
												<div class="col-lg-4">
													<input type="password" name="conpwd" class="form-control" id="inputPassword4" placeholder="Conform Password">
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
      