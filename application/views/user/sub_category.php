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
		$this->session->set_flashdata("message","Time Out. Login Again.");
		redirect('user_home/logout');
	}
	$user=$this->login_database->read_user_info($mail_id);
	$this->load->helper('inflector');
	$name=$user['first_name'] . '_' . $user['last_name'];
	$name=humanize($name);
	$data=array('title'=>'Class Room');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/user_menu',$data);
	$str=$this->uri->segment(3);
	$titles= $this->model_home->sub_category($str);
?> 
<!--main Title bar-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h3 class="page-header"><i class="fa fa fa-bars"></i> Sub Topics</h3>
				<?PHP $this->load->view('template/alert'); ?>
			</div>
		</div>
		
		<div class="row">
			
			<?PHP foreach ($titles as $item): ?>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<a href="<?= site_url(); ?>/user_home/read_view/<?= $item['sub_id']; ?>">
						<div class="box blue-bg">
							<i class="fa fa-cloud-download"></i>
							<div class="title"><?= $item['title']; ?></div>
						</div>		
					</a>
				</div>
			<?PHP endforeach; ?>
		</div>
	</section>
</section>
      <!--main content end-->
<?PHP $this->load->view('template/footer'); ?>
