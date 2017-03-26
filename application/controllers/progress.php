<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Progress extends CI_Controller{
    
    public function __construct()
	{
		parent::__construct();
		$this->load->model('login_database');
		$this->load->model('model_home');
		$this->load->model('model_progress');
		
	}
	
        public function index()
        {
            //$this->output->cache(5);
			//$aa=$this->session->tempdata('mail_id');
			//$mail_id=$this->encryption->decrypt($aa);
            $this->load->view('progress_view');
	
        }
		 public function pie()
        {
            //$this->output->cache(5);
            $this->load->view('pie');
	
        }
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
