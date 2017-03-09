<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$data=array('title'=>'404 Error');
	$this->load->view('template/header',$data);
	?>

<body class="error-container err-bg">
	<div class="row">
		<?PHP if($this->session->tempdata()): ?>
			<div class="page-404-header">
				OOPS.. ! Something went wrong.
			</div>
			<div class="page-404-header">
				<?PHP echo $this->session->flashdata('message'); ?>
			</div>
		
			<div class="row">
				
				<div class="page-404 ">
					<p class="text-404 hvr-pop">404 !</p>
				</div>
			</div>
			<div class="page-404-content">
				<a href="<?= site_url(); ?>" class="btn btn-danger btn-lg" style="width:200px;">Return Home</a>
			</div>
			
		<?PHP else: ?>
			<div class="page-404-header">
				Your Session Expired. Please login.
			</div>
			<div class="page-404-header">
				<?PHP echo $this->session->flashdata('message'); ?>
			</div>
			<div class="page-404">
				<p class="text-404 hvr-pop">404 !</p>
			</div>
			<div class="page-404-content">
				 <a class="btn btn-danger btn-lg" style="width:200px;" href="<?= site_url(); ?>">Login</a>
			</div>
			
		<?PHP endif; ?>
	</div>
	<?PHP $this->load->view('template/footer'); ?>
