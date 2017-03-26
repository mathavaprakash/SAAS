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
	if(!isset($user))
	{
		$this->session->set_flashdata("message","Invalid User");
		redirect('user_home/error');
	}
	$this->load->helper('inflector');
	$name=$user['first_name'] . '_' . $user['last_name'];
	$name=humanize($name);
	$data=array('title'=>'Create Chapter');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/user_menu',$data);
	$category=$this->model_quiz->get_category();
	if(!isset($category))
	{
		$this->session->set_flashdata("message","");
		redirect('user_home/error');
	}
?> 
<!--main Title bar-->
<section id="main-content" >
	<section class="wrapper" style="width:99%;">
		<div class="row">
			<div class="col-lg-12">
				<div class="page-header"><i class="fa fa-book" aria-hidden="true"></i>
				 Create Chapter</div>
				<?PHP $this->load->view('template/alert'); ?>
			</div>
		</div>
		<div class="row">
			<?PHP
				$mid=$this->login_database->encryptor('encrypt',$mail_id);
				$cls=array(
					'class'=>'form-horizontal'
				);
				echo form_open_multipart("user_home/add_chapter/$mid",$cls);
			?>
				<div class="form-group">
					<label class="col-lg-4 control-label">Enter Chapter Title</label>
					<div class="col-lg-4">
						<input type="text" name="title" class="form-control"  placeholder="Enter Chapter Title" required autofocus>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label">Select Domain</label>
					<div class="col-lg-4">
						<?PHP 
							$cls= 'class="form-control"  placeholder="Test title"';
						
							echo form_dropdown('category', $category, 'large',$cls); 
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label">Attach Your File (Optional)</label>
					<div class="col-lg-4">
						<input type="file" name="file" accept=".pdf,.doc,.docx,.ppt.,pptx" class="form-control lg-input" id="exampleInputEmail5" placeholder=".pdf,.doc,.docx,.ppt.,pptx">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">Enter Your Content</label>
					<div class="col-lg-offset-1 col-lg-10">
						<textarea class="form-control ckeditor" name="content"></textarea>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-lg-offset-5 col-lg-2">
						<button type="submit" class="btn btn-info">Create New Chapter</button>
					</div>
				</div>
			<?= form_close(); ?>
										
		</div>
	  
	</section>
</section>
      <!--main content end-->
	  <script type="text/javascript" src="<?= base_url(); ?>assets/ckeditor/ckeditor.js"></script>

<?PHP $this->load->view('template/footer'); ?>
