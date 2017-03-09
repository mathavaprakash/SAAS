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
	$cat=$this->uri->segment(3);
	$str=$this->uri->segment(4);
	$titles= $this->model_home->content($str);
?> 
<!--main Title bar-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h3 class="page-header"><i class="fa fa fa-bars"></i> Dashboard</h3>
				<?PHP $this->load->view('template/alert'); ?>
			</div>
			<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="change" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
							<h4 class="modal-title">Upload your File</h4>
						</div>
						<div class="modal-body">
							<?PHP echo form_open_multipart('admin_home/upload_file/' . $cat . '/' . $str); ?>
							<!--<form class=" form-horizontal " method="post" action="http://localhost/saas/index.php/admin_home/upload_picture">	
								--><div class="form-group">
									<input type="file" name="file" accept=".pdf,.doc,.docx,.ppt.,pptx" class="form-control lg-input" id="exampleInputEmail5" placeholder="Choose File">
								</div>
								<button type="submit" class="btn btn-success">Upload</button>
							<!--</form>--><?PHP echo form_close(); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-12">
				<!--tab nav start-->
				<section class="panel">
					<header class="panel-heading tab-bg-primary tab-right ">
						<ul class="nav nav-tabs pull-right">
							<li class="active">
								<a data-toggle="tab" href="#home-3">
									<i class="icon-home"></i>
									Content View
								</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#edit">
									<i class="icon-home"></i>
									Edit Content
								</a>
							</li>
							<li class="">
								<a data-toggle="tab" href="#about-3">
									<i class="icon-user"></i>
									Attachment
								</a>
							</li>
							
						</ul>
						<span class="hidden-sm wht-color">
							<a href="<?= site_url(); ?>/admin_home/delete_topic/<?= $cat; ?>/<?= $str; ?>" title="Delete" style="color: white;">
								<i class="fa fa-trash" aria-hidden="true"></i>
							</a>
								<?= '  ' . $titles['title']; ?>
						</span>						
					</header>
					<div class="panel-body" >
						<div class="tab-content">
							<div id="home-3" class="tab-pane active" style="height:auto;">
								<?= html_entity_decode($titles['contents']); ?>
							</div>
							<div id="edit" class="tab-pane">
							<div class="form">
								<form class=" form-horizontal " method="post" action="<?= site_url(); ?>/admin_home/edit_content/<?= $cat; ?>/<?= $str; ?>">
										<header class="panel-heading" >
											<div style="display: inline; margin-right: 10px;">
												<div class="col-lg-4">
												<input type="text" name="title" class="form-control" placeholder="Enter Title " value="<?= $titles['title']; ?>" required>
												</div>
											</div>
											<div style="display: inline; float:right;margin-right: 10px;">
												<button type="submit" class="btn btn-primary">Submit</button>
											</div>
										</header>
										<div class="panel-body">
											
												<div class="form-group">
													<div class="panel-body" >
														<textarea class="form-control ckeditor" name="content" rows="20"><?= $titles['contents']; ?></textarea>
													</div>
												</div>
												
										</div>
									<?PHP echo form_close(); ?>
								
								</div>
							</div>
							
							<div id="about-3" class="tab-pane">
								<?PHP if(	!$titles['attachment']==NULL): 
									$file= base_url() . 'attachment/' . $titles['attachment'];
								?>
								<section class="panel">
									<header class="panel-heading" >
										<div style="display: inline; margin-right: 10px;">
											<?PHP echo anchor($file,$titles['attachment']); ?>
										</div>
										<div style="display: inline; float:right;margin-right: 10px;">
											<i class="fa fa-exchange" aria-hidden="true"></i>
											<a href="#change" data-toggle="modal" class="btn">Change</a>
											
											<i class="fa fa-trash" aria-hidden="true"></i>
											<?PHP echo anchor('admin_home/delete_attachment/' . $cat . '/' . $str,'Delete'); ?>
										</div>
									</header>
									<div class="panel-body">
										<embed width="100%" height="700px" src="<?= $file; ?>" type="application/pdf">&nbsp; </embed>
									</div>
								</section>
								<?PHP else: ?>
								<section class="panel">
									<header class="panel-heading" >
										<div style="display: inline; margin-right: 10px;">
											<?= $titles['title']; ?>
										</div>
										<div style="display: inline; float:right;margin-right: 10px;">
											<a href="#change" data-toggle="modal" class="btn">Upload New File</a>							
										</div>
									</header>
									<div class="panel-body">Document Not Available. Please Upload Document!
									<a href="#change" data-toggle="modal" class="btn">Upload New File</a>
									</div>
								</section>
								<?PHP endif; ?>
							</div>
						</div>
					</div>
				</section>
			</div>
			
		</div>
	</section>
</section>
<!-- ck editor -->
<script type="text/javascript" src="<?= base_url(); ?>assets/ckeditor/ckeditor.js"></script>
 <?PHP $this->load->view('template/footer'); ?>
