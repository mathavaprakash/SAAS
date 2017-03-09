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
	
	$data=array('name'=>$name);
	$this->load->view('template/user_menu',$data);
	if(!is_numeric($this->uri->segment(3)))
	{
		$this->load->view('template/404');
	}
	else
	{
		$str = $this->uri->segment(3);
	}
	if(!isset($str)) redirect('user_home/error');
	$titles= $this->model_home->content($str);
	if($titles==FALSE)
	{
		redirect('user_home/error');
	}
	$file= base_url() . 'attachment/' . $titles['attachment'];
	$exist=file_exists($file);
	$this->load->helper('file');
	$data=array('title'=>$titles['title']);
	$this->load->view('template/header',$data);
	
?> 
<!--main Title bar-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			
			<?PHP $this->load->view('template/alert'); ?>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="room-container">
					<div class="room-header">
						<?= $titles['title']; ?>
					</div>
					<div class="row">
						<div class=" col-md-5 row">
							<div class="">
								<i class="fa fa fa-calendar-o"></i>
								Posted On : <?= $this->model_quiz->cdate($titles['created_date']); ?>
							</div>
						</div>	
						<div class="col-md-3 row">
							<div class="">
									Domain : <?= $this->model_home->id_to_cat($titles['category_id']); ?>
							</div>
						</div>
						<div class="col-md-offset-1 col-md-3 row">
								<?PHP if(	!$titles['attachment']==NULL): 
									$file= base_url() . 'attachment/' . $titles['attachment'];
								?>
									<i class="fa fa-paperclip fa-lg" aria-hidden="true"></i>
										<a style="color:#fff;" href="<?= $file; ?>"  target="_blank">Attachment</a>
								<?PHP endif; ?>
									
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="room-container1  col-md-offset-1 col-md-10">
					<div class="row">
						<div class="col-md-offset-1 col-md-10 room-content">
							<?= html_entity_decode($titles['contents']); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</section>
      <!--main content end-->

<!-- ck editor -->
    <script type="text/javascript" src="<?= base_url(); ?>assets/ckeditor/ckeditor.js"></script>
<?PHP $this->load->view('template/footer'); ?>
