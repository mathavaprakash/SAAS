<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	$data=array('title'=>'Registration');
	$this->load->view('template/header',$data);
?>

<title> Registration | SAAS </title>
</head>

  <body class="login-img3-body">

    <div class="container">
		<?PHP $this->load->view('template/alert'); ?>
		<div class="login-form">
		<?PHP
			echo form_open('user_authentication/user_registration_process');
			
		?>
        <div class="login-wrap">
            <p class="login-img"><i class="fa fa-user-plus" aria-hidden="true"></i></p>
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-envelope" aria-hidden="true"></i></span>
              <input type="email" name="mail_id" class="form-control" placeholder="E-Mail ID*" autofocus required>
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                <input type="text" name="first_name" class="form-control" placeholder="First Name*" required>
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user-md" aria-hidden="true"></i></span>
                <input type="text" name="last_name" class="form-control" placeholder="Last Name*" required>
            </div>
			<div class="btn-row  btn-group-justified" >
				<div class="btn-group " data-toggle="buttons">
					<label class="btn btn-default active"><i class="fa fa-mars fa-lg" aria-hidden="true"></i>
						<input type="radio" name="gender"  value="Male" checked>Male
					</label>
					<label class="btn btn-default"><i class="fa fa-venus fa-lg" aria-hidden="true"></i>
						<input type="radio" name="gender" value="Female">Female
					</label>
					<label class="btn btn-default"><i class="fa fa-transgender fa-lg" aria-hidden="true"></i>
						<input type="radio" name="gender" value="Transgender">Transgender
					</label>
				</div>
			</div> 
			<div class="input-group">
                <span class="input-group-addon"><i class="fa fa-birthday-cake" aria-hidden="true"></i></span>
                <input type="date" min="1980-01-31" max="<?= date("Y-m-d",now()) ?>" name="dob" class="form-control" placeholder="Date of Birth*" required>
            </div>
			<div class="input-group">
                <span class="input-group-addon"><i class="fa fa-mobile" aria-hidden="true"></i></span>
                <input type="number" name="phone" class="form-control" placeholder="phone Number*" required>
            </div>
            <button class="btn btn-primary btn-lg btn-block" type="submit">Register</button>
           </br>
		   <label class="checkbox">
                <span class="pull-right"><a href="<?PHP echo base_url(); ?>index.php?user_authentication/">Already Have Account? Login Here!</a></span>
				
            </label>
			<?php echo form_close(); ?>
        </div>
      <!--</form> -->
		</div>
    </div>
		<?PHP $this->load->view('template/footer'); ?>
