<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	$data=array('title'=>'Login');
	$this->load->view('template/header',$data);
	
?>
<body class="login-img3-body">
	<div class="container" >
		<?PHP $this->load->view('template/alert'); ?>
		<div style=" margin: 10em auto 0;" class="login-form">
		<?php echo validation_errors(); ?>
		<?PHP
			$cls=array('class'=>'form-horizontal form-validate');
			echo form_open('user_authentication/user_login_process',$cls);
			
			//echo form_close();
		?>
      <!--<form class="login-form" action="#" method="POST">       --> 
        <div class="login-wrap">
            <p class="login-img"><i class="fa fa-sign-in fa-2x" aria-hidden="true"></i></p>
            <div class="input-group">
              <span class="input-group-addon"><i class="icon_profile"></i></span>
              <input type="email" name="userid" class="form-control" placeholder="E-Mail ID" autofocus>
			  <?= form_error('userid'); ?>
			<!--	<span style="color:red" ng-show="studentForm.userid.$dirty && studentForm.userid.$invalid">
				<span ng-show="studentForm.userid.$error.required">Email is required.</span>
				<span ng-show="studentForm.userid.$error.email">Invalid email address.</span>
				</span> -->
			</div>
            <div class="input-group">
                <span class="input-group-addon"><i class="icon_key_alt"></i></span>
                <input type="password" name="password"  class="form-control" placeholder="Password">
				<?= form_error('password'); ?>
			<!--	<span style="color:red" ng-show="studentForm.password.$dirty && studentForm.password.$invalid">
				<span ng-show="studentForm.password.$error.required">First Name is required.</span>
				</span> -->
		   </div>
            <button class="btn btn-primary btn-lg btn-block hvr-buzz-out" type="submit">Login</button>
          <!--  <button class="btn btn-primary btn-lg btn-block" type="submit" 
				ng-disabled="studentForm.password.$dirty && studentForm.password.$invalid || 
				studentForm.userid.$dirty && studentForm.userid.$invalid" 
				ng-click="submit()">Login
			</button> -->
           </br>
		   <label class="checkbox">
                <span class="pull-left"><a href="<?PHP echo base_url(); ?>index.php/user_authentication/registration_form_view">Register Here!</a></span>
				<span class="pull-right"> <a href="<?PHP echo base_url(); ?>index.php/user_authentication/forget_form_view"> Forgot Password?</a></span>
            </label>
			<?php echo form_close(); ?>
        </div>
      <!--</form> -->
		</div>
    </div>
		<?PHP $this->load->view('template/footer'); ?>
