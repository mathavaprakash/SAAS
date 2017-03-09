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
	$data=array('title'=>'Class Room');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/admin_menu',$data);
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
			<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
				<a href="#myModal-2" data-toggle="modal">
					<div class="box green-bg" style="padding-top:40px;">
						<div class="title">Add New Topic </div>
					</div>
				</a>
			</div>
			<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal-2" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
							<h4 class="modal-title">Add New Topic</h4>
						</div>
						<div class="modal-body">
							
							<form class=" form-horizontal " method="post" action="<?= site_url(); ?>/admin_home/add_sub_category/<?= $str; ?>">	
								<div class="form-group">
									<label for="inputEmail1" class="col-lg-4 control-label">Sub Category Name</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" name="category" placeholder="Category Name" autofocus required>
									</div>
								</div>
								<div class="form-group">
									<div class="col-lg-offset-4 col-lg-8">
										<button type="submit" class="btn btn-info">Add Sub Category</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<?PHP foreach ($titles as $item): ?>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<div class="box blue-bg">
						<div style="float:right;">
							<a href="<?= site_url(); ?>/admin_home/delete_topic/<?= $str; ?>/<?= $item['sub_id']; ?>" title="Delete" style="color: white;">
								<i class="fa fa-trash"  aria-hidden="true"></i>
							</a>
						</div>
						<div class="title"  style="padding-top:20px;">
							<a href="<?= site_url(); ?>/admin_home/read_view/<?= $str; ?>/<?= $item['sub_id']; ?>" style="color: white;">
								<?= $item['title']; ?>
							</a>
						</div>
					</div>						
				</div>
			<?PHP endforeach; ?>
		</div>
	</section>
</section>
      <!--main content end-->
<?PHP $this->load->view('template/footer'); ?>
