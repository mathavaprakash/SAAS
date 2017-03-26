<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	if($this->session->tempdata('mail_id'))
	{
		$aa=$this->session->tempdata('mail_id');
		$mail_id=$this->encryption->decrypt($aa);
		$this->session->set_tempdata('mail_id',$aa,TIMEOUT);
	}
	else if($this->session->tempdata('email_id'))
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
	if(!isset($user))
	{
		$this->session->set_flashdata("message","Invalid User");
		redirect('user_home/error');
	}
	$this->load->helper('inflector');
	$name=$user['first_name'] . '_' . $user['last_name'];
	$name=humanize($name);
	$html = $name;
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
	$cat=$titles['category_id'];
	$data=array('name'=>'maddy','title'=>$titles['title'],'html'=>$titles['contents']); 
	$this->load->view('template/pdf_template',$data);

	//$mid=$this->login_database->encryptor('encrypt',$mail_id);
?> 