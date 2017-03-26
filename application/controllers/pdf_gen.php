<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf_gen extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Pdf');
	}
	public function index()
	{
		
		
	}
	
	public function print_ex()
	{
		
		$this->load->view('print_pdf/example');
			
	}
	public function chapter_pdf()
	{
		
		$this->load->view('print_pdf/chapter');
			
	}
	
}