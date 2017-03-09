<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	if($this->session->tempdata('mail_id'))
	{
		$aa=$this->session->tempdata('mail_id');
		$mail_id=$this->encryption->decrypt($aa);
		$profile_pic=$this->login_database->profile_pic_url($mail_id);
	}
	else
	{
		redirect('user_home/error');
		
	}
	//$category=$this->model_home->category();
	
?>	
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
<body >
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
