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
		redirect('user_home/error');
	}
	$user=$this->login_database->read_user_info($mail_id);
	$this->load->helper('inflector');
	$name=$user['first_name'] . '_' . $user['last_name'];
	$name=humanize($name);
	
	if(!is_numeric($this->uri->segment(3)))
	{
		$this->load->view('template/404');
	}
	else
	{
		$str = $this->uri->segment(3);
	}
	$sub_id=$this->login_database->encryptor('encrypt',$this->uri->segment(3));
	if(!isset($str)) redirect('user_home/error');
	$titles= $this->model_home->content($str);
	if($titles==FALSE)
	{
		redirect('user_home/error');
	}
	$cat=$titles['category_id'];
	$file= base_url() . 'attachment/' . $titles['attachment'];
	$exist=file_exists($file);
	$this->load->helper('file');
	$data=array('title'=>$titles['title']);
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/admin_menu',$data);
	
	$author=$this->login_database->read_user_info($titles['mail_id']);
	$aname=$author['first_name'] . '_' . $author['last_name'];
	$aname=humanize($aname);
	$stud_id=$this->login_database->encryptor('encrypt',$titles['mail_id']);
		
?> 
<!--main Title bar-->
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="edit_title" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title">Update Title</h4>
			</div>
			<div class="modal-body">
				<?PHP	$cls=array('class'=>'form-horizontal');
					echo form_open("question_bank/edit_chapter_title/$str",$cls);
				?>	
					<div class="form-group">
						<label class="col-lg-4 control-label">Enter Test Title</label>
						<div class="col-lg-8">
							<input type="text" name="title" class="form-control" value="<?= $titles['title']; ?>" placeholder="Test title" required autofocus>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-4 col-lg-6">
							<button type="submit" class="btn btn-info">Update</button>
						</div>
					</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
</div>
<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="add_attachment" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="modal-title">Update Title</h4>
			</div>
			<div class="modal-body">
				<?PHP	$cls=array('class'=>'form-horizontal');
					echo form_open_multipart("question_bank/add_attachment/$str",$cls);
				?>	
					<div class="form-group">
						<label class="col-lg-4 control-label">Attach Your File </label>
						<div class="col-lg-8">
							<input type="file" name="file" accept=".pdf,.doc,.docx,.ppt.,pptx" class="form-control lg-input" id="exampleInputEmail5" placeholder=".pdf,.doc,.docx,.ppt.,pptx">
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-4 col-lg-6">
							<button type="submit" class="btn btn-info">Upload</button>
						</div>
					</div>
				<?= form_close(); ?>
			</div>
		</div>
	</div>
</div>
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			
			<?PHP $this->load->view('template/alert'); ?>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="room-container">
					<div class="row">
						<div class="col-md-2">
							<div class="dash-calendar  col-md-offset-1 col-xs-7 ">
								<div class="dash-date">
									Posted On <?PHP $date=$titles['created_date']; ?>
								</div>
								<div class="dash-year">
									<?= date("h:i A",strtotime($date)); ?>
								</div>
								<div class="dash-month">
									<?= date("M",strtotime($date)); ?>
								</div>
								<div class="dash-month">
									<?= date("d",strtotime($date)); ?>
								</div>
								<div class="dash-year">
									<?= date("Y",strtotime($date)); ?>
								</div>
								
							</div>
						</div>
						<div class="col-md-8">
							<div class="test-header">
								<?= $titles['title']; ?>&nbsp; <a href="#edit_title" style="font-size:16px; color:white;" data-toggle="modal"><i class="fa fa-pencil" aria-hidden="true"></i></a>
							</div>
							<div class="test-category">
								<?= $this->model_home->id_to_cat($titles['category_id']); ?>
							</div>
							<div class="test-category">
								<?PHP $cls='style="color:#023;"'; ?>
								Posted By : <?= anchor("user_home/view_student_profile/$stud_id",$aname,$cls); ?>
							</div>
							<div class="test-category">
								<?PHP if(	!$titles['attachment']==NULL): 
									$file= base_url() . 'attachment/' . $titles['attachment'];
								?>
									<i class="fa fa-paperclip fa-lg" aria-hidden="true"></i>
										<a style="color:#fff;" href="<?= $file; ?>"  target="_blank">View Attachment</a>
										&nbsp; <a href="<?= site_url(); ?>/question_bank/delete_attachment/<?= $str; ?>" style="font-size:16px; color:white;" data-toggle="modal"><i class="fa fa-trash" aria-hidden="true"></i></a>
								
								<?PHP else: ?>
									<a href="#add_attachment" style="font-size:16px; color:white;" data-toggle="modal">Add Attachment<i class="fa fa-pencil" aria-hidden="true"></i></a><?PHP endif; ?>
							</div>
						</div>
						<div class="col-md-2">
							<div class="dash-calendar  col-md-offset-1 col-xs-7 ">
								<div class="dash-date">
									<a class="btn-danger" style="float:right; border-radius:10px;" href="<?= site_url(); ?>/question_bank/home/<?= $sub_id; ?>">Question Bank</a>
				
									<?PHP $questions=$this->model_qbank->get_questions($str); ?>
								</div>
								<div class="dash-month">
									<?= count($questions); ?>
								</div>
								<div class="dash-year">
									Questions
								</div>
								
							</div>
						</div>
					</div>
					
				</div>
				
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12">
				<?PHP if($titles['status']==0): ?>
					<a class="btn btn-success btn-md" href="<?= site_url(); ?>/user_home/approve_chapter/<?= $sub_id; ?>">Approve</a>
				<?PHP endif; ?>
					<a class="btn btn-danger btn-md" href="<?= site_url(); ?>/user_home/delete_chapter/<?= $sub_id; ?>">Delete</a>
				<a class="btn btn-success btn-md" style="float:right;" href="<?= site_url(); ?>/question_bank/home/<?= $sub_id; ?>">Question Bank</a>
				
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12" style="margin-bottom:20px;">
				<div class="room-container1  col-md-offset-1 col-md-10">
					<div class="row">
						<div class="col-md-offset-1 col-md-10 room-content">
							<?= $this->model_qbank->closetags(html_entity_decode($titles['contents'])); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<table>
			<tbody>
			<?PHP if($discussions=$this->model_home->get_discussions($str)): ?>
				<?PHP foreach($discussions as $dis): ?>
							<div class="panel panel-default">
								<div class="panel-heading">
									<div class="pull-left">Discussion</div>
										<div class="widget-icons pull-right">
									</div>  
									<div class="clearfix"></div>
								</div>
								<div class="panel-body">
								  <!-- Widget content -->
									<div class="padd sscroll">
									
										<ul class="chats">
											<li class="by-me">
												<div class="avatar pull-left">
													<?php 
													$id=$dis['creator_id'];
													$profile_pic=$this->login_database->profile_pic_url($id);
													$student=$this->model_quiz->get_student_details($id);
													$sname=$student['first_name'] . '_' . $student['last_name'];
													$sname=humanize($sname);
										
													$did= $dis['discussion_id']; 
													?>
													<img width=35 height=40 src="<?= base_url() . $profile_pic; ?>" alt=""/>
												</div>
												<div class="chat-content">

													<div class="chat-meta"><?= $sname; ?><span class="pull-right"><?= $this->model_home->get_days_ago($dis['creation_date']); ?>
																				</span></div>
														  <?= $dis['question']; ?>
														  
														  <div class="clearfix"></div>
														  <!-- delete discussion -->
														  
														  <div style="float:right;">
															<a href="<?= site_url(); ?>/discussions/delete_discussion_admin/<?= $cat ?>/<?= $str ?>/<?= $did; ?>" title="Delete Discussion" style="color: red; ">
																<i class="fa fa-trash"  aria-hidden="true" ></i>
															</a>
														</div>
													</div>
													<!-- Reply -->
													<?PHP if($comments=$this->model_home->get_comments($did)): 
														foreach($comments as $coms): ?>
																
														<li class="by-other">
														<div class="avatar pull-right">
															<?php 
															$cid=$coms['mail_id'];
															
															$profile_pic1=$this->login_database->profile_pic_url($cid);
															$student1=$this->model_quiz->get_student_details($cid);
															$sname1=$student1['first_name'] . '_' . $student1['last_name'];
															$sname1=humanize($sname1);
															$com_id=$coms['comment_id'];
															?>
														   <img width=35 height=40 src="<?= base_url() . $profile_pic1; ?>" alt=""/>
														</div>

														<div class="chat-content">
														  <!-- In the chat meta, first include "time" then "name" -->
														  <div class="chat-meta"> <?= $this->model_home->get_days_ago($coms['comment_date']); ?> <span class="pull-right"> <?= $sname1; ?></span></div>
														   <?= $coms['comment']; ?>
															<div class="clearfix"></div>
														 <!-- delete comment-->
														 
															<div style="float:right;">
																<a href="<?= site_url(); ?>/discussions/delete_comment_admin/<?= $cat ?>/<?= $str ?>/<?= $com_id; ?>" title="Delete Comment" style="color: red; ">
																	<i class="fa fa-trash"  aria-hidden="true" ></i>
																</a>
															</div>
		 
														</div>
													</li>
												<?PHP endforeach;?>
											<?PHP endif; ?>
			
	<!-- Widget footer -->
											  <div class="widget-foot">
												  <form class="form-inline" method="post" action="<?= site_url(); ?>/discussions/add_comment_admin/<?= $cat ?>/<?= $str ?>/<?= $did?>">
													<div class="form-group">
														<input type="text" class="form-control" name="reply" placeholder="Type your Comment here...">
													</div>
													<button type="submit" class="btn btn-info">Send</button>
													</form>
											  </div>
											  </li>                                                                                           
											</ul>

									</div>
							  <!-- Widget footer -->
								</div>
							</div>										
																
						<?PHP endforeach; ?>
					
					
			
			<?PHP endif; ?>
			<div class="col-lg-3 " style="float:right;">
				<a href="#myModal-2" data-toggle="modal">
					<div class="btn btn-primary">
						
						<div class="title">Start New Discussion </div>
					</div>
				</a>
			</div>
		</tbody>
		</table>
		
		<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal-2" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
						<h5 class="modal-title">Comment</h5>
					</div>
					<div class="modal-body">
						
						<form class=" form-horizontal " method="post" action="<?= site_url(); ?>/discussions/add_discussion_admin/<?= $cat ?>/<?= $str ?>">	
							
								<div class="col-lg-12">	                         
											  <div class="col-sm-12">
												  <textarea class="form-control ckeditor" name="comments" rows="3"></textarea>
													<br><button type="submit" class="btn btn-info">Post</button>
												</div>												  
								</div>							
						</form>
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
