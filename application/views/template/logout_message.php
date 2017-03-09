 <?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	$data=array('title'=>'logout');
	$this->load->view('template/header',$data);
	?>
<body class="error-container logout-bg">
	
	<div class="row">
		<div class="page-404-header">
			You are Logged out Successfully.
		</div>
		<div class="page-404 ">
			<p class=" hvr-pop" style="font-size:40px;">
				<i class="text-404 fa fa-handshake-o fa-5x" aria-hidden="true"></i>
			</p>
		</div>
		<div class="page-404-content" style="padding-top:30px;">
			<a href="<?= site_url(); ?>" class="btn btn-danger btn-lg" style="width:200px;">Login Again</a>
			<a href="<?= site_url(); ?>" class="btn btn-danger btn-lg" style="width:200px;">Goto Home</a>
		</div>
	</div>
  </body>
  </html>
  