<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	$data=array('title'=>'Forget Password');
	$this->load->view('template/header',$data);
?>
<title> Forget Password | SAAS </title>
</head>
  <body class="login-img3-body">
    <div class="container" >
		<?PHP $this->load->view('template/alert'); ?>
		<div style=" margin: 10em auto 0;" class="login-form">
		<form class=" form-horizontal " name="studentForm" method="post" action="<?= site_url(); ?>/user_authentication/forget_password_process">
		<div class="login-wrap">
            <p class="login-img"><i class="fa fa-repeat fa-2x" aria-hidden="true"></i></p>
            <div class="input-group">
              <span class="input-group-addon"><i class="icon_profile"></i></span>
              <input type="email" name="userid" class="form-control" placeholder="Enter your Registered E-Mail ID" autofocus required>
			<!--	<span style="color:red" ng-show="studentForm.userid.$dirty && studentForm.userid.$invalid">
				<span ng-show="studentForm.userid.$error.required">Email is required.</span>
				<span ng-show="studentForm.userid.$error.email">Invalid email address.</span>
				</span> -->
			</div>
            
            <button class="btn btn-primary btn-lg btn-block" type="submit">Forget Password</button>
          <!--  <button class="btn btn-primary btn-lg btn-block" type="submit" 
				ng-disabled="studentForm.password.$dirty && studentForm.password.$invalid || 
				studentForm.userid.$dirty && studentForm.userid.$invalid" 
				ng-click="submit()">Login
			</button> -->
           </br>
		   <label class="checkbox">
                <span class="pull-left"><a href="<?PHP echo base_url(); ?>index.php/user_authentication/registration_form_view">Register Here!</a></span>
				<span class="pull-right"> <a href="#"> Forgot Password?</a></span>
            </label>
			<?php echo form_close(); ?>
        </div>
      <!--</form> -->
		</div>
    </div>
		<?PHP $this->load->view('template/footer'); ?>
  </body>
</html>
