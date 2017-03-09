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
	$data=array('title'=>'Class Room');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/user_menu',$data);
	$category= $this->model_home->category();
	if(!isset($category))
	{
		$this->session->set_flashdata("message","");
		redirect('user_home/error');
	}
?> 
<!--main Title bar-->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h3 class="page-header"><i class="fa fa fa-bars"></i> Class Room</h3>
				<?PHP $this->load->view('template/alert'); ?>
			</div>
		</div>
		<!--collapse start-->
	<div class="row">
		<div class="col-md-offset-4 btn-row"><?PHP $i=0; ?>
			<div class="btn-group" >
				<?PHP foreach($category as $cat): 
					$str=($i==0)?'active' : '';
					$i=1;
				?>
					<button type="button"  class="btn btn-primary btn-lg accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#<?= $cat['category_id']; ?>">
						<?= $cat['title']; ?>
					</button>
			  <?PHP endforeach; ?>
			  </div>
		</div>
	</div>
	  <div class="row quiz-containerr " id="accordion"><?PHP $i=0; ?>
			<?PHP foreach($category as $cat): 
				$str=($i==0)?'in' : '';
				$i=1;
				$cid=$cat['category_id'];
				$sub_category=$this->model_home->sub_category($cid);
				if(!isset($sub_category))
				{
					redirect('user_home/error');
				}
			?>
			  <div class="panel category-containerr col-md-offset-1 col-md-10 " style="border:none; background:none; ">
				  
				  <div id="<?= $cat['category_id']; ?>" class="panel-collapse collapse <?= $str; ?>">
						<div class="panel-body"  style="border:none; background:none;">
							<?PHP if(count($sub_category)>0): ?>
							<?PHP foreach($sub_category as $sub): ?>
								<div class="col-md-12 category-container hvr-glow">
									<a  href="<?= site_url(); ?>/user_home/read_view/<?= $sub['sub_id']; ?>">
										<div class="row">
											<div class="col-md-8  test-title">
												<i class="fa fa-hand-o-right" aria-hidden="true"></i>
												<?= $sub['title']; ?>
											</div>
											<div class="col-md-4  test-content">
												<i class="fa fa-calendar-o" aria-hidden="true"></i>
												Posted on : <?= $this->model_home->get_days_ago($sub['created_date']); ?>
												
											</div>
										</div>
									</a>
								</div>
							<?PHP endforeach; ?>
							<?PHP else: ?>
								Chapters will available soon
							
							<?PHP endif; ?>
						</div>
				  </div>
			  </div>
			<?PHP endforeach; ?>
		  
	  </div>
	  <!--collapse end-->
		
	</section>
</section>
      <!--main content end-->
<?PHP $this->load->view('template/footer'); ?>
