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
<!--<body onload="myFunction() ">-->
	<body>
	<section id="container">
		<header class="header dark-bg">
			<div class="toggle-nav">
				<div class="icon-reorder tooltips" data-original-title="Toggle Navigation" data-placement="bottom"><i class="icon_menu"></i></div>
            </div>
			<a href="<?PHP echo site_url(); ?>/user_home/index" class="logo"> <span class="lite">SAAS</span></a>
			<div class="top-nav notification-row">                
				<ul class="nav pull-right top-menu">
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">
							<span class="profile-ava">
								<img width=35 height=40 alt="" src="<?= base_url() . $profile_pic; ?>">
							</span>
							<span class="username">Welcome! <?PHP print ' ' .$name; ?> </span>
							<b class="caret"></b>
						</a>
						<ul class="dropdown-menu extended logout">
							<div class="log-arrow-up"></div>
							<li class="eborder-top">
								<a href="<?PHP echo base_url(); ?>index.php/user_home/profile_view"><i class="fa fa-user" aria-hidden="true"></i> My Profile</a>
							</li>
							<li>
								<a href="<?PHP echo base_url(); ?>index.php/user_home/logout"><i class="fa fa-sign-out" aria-hidden="true"></i> Log Out</a>
							</li>
						</ul>
					</li>
				</ul>
			</div>
		</header>  

		<!--
		<div id="myDiv" style="display:none;" class="animate-bottom">	
		<div class="loader" id="loader">
		<div class="dot"></div>
		  <div class="dot"></div>
		  <div class="dot"></div>
		  <div class="dot"></div>
		  <div class="dot"></div>
	</div>
		-->

<aside>
	<div id="sidebar"  class="nav-collapse " style="background:#003366;">
		<ul class="sidebar-menu">                
		<li class="">
			<a class="" href="<?PHP echo site_url(); ?>/user_home/index">
				<i class="fa fa-tachometer" aria-hidden="true"></i>
				<span>Dashboard</span>
			</a>
		</li>
		<li>
			<a class="" href="<?PHP echo site_url(); ?>/user_home/category_view">
				<i class="fa fa-book" aria-hidden="true"></i>
				<span>Class Room</span>
			</a>
		</li>
		<li>
			<a class="" href="<?PHP echo site_url(); ?>/online_test">
				<i class="fa fa-desktop" aria-hidden="true"></i>
				<span>Online Test</span>
			</a>
		</li>
		<li>                     
			<a class="" href="<?PHP echo site_url(); ?>/progress/">
				<i class="fa fa-line-chart" aria-hidden="true"></i>
				<span>Progress</span>
			</a>              
		</li>
		<li>                     
			<a class="" href="<?PHP echo base_url(); ?>index.php/user_home/leader_board">
				<i class="fa fa-graduation-cap" aria-hidden="true"></i>
				<span>Leader Board</span>
			</a>              
		</li>       
		<li>                     
			<a class="" href="<?PHP echo base_url(); ?>index.php/user_home/profile_view">
				<i class="fa fa-user" aria-hidden="true"></i>
				<span>My Profile</span>
			</a>              
		</li>
		<li>                     
			<a class="" href="<?PHP echo base_url(); ?>index.php/user_home/logout">
				<i class="fa fa-sign-out" aria-hidden="true"></i>
				<span>Logout</span>
			</a>              
		</li> 
		</ul>
	</div>
</aside>
