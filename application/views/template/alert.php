<?PHP if($this->session->flashdata('message')): ?>
	<div class="panel-body" >
			<div class="alert alert-success fade in">
				<button data-dismiss="alert" class="close close-sm" type="button">
					<i class="fa fa-times-circle fa-lg" aria-hidden="true"></i>
				</button>
				<strong><?PHP echo $this->session->flashdata('message'); ?></strong>
			</div> 
		</div>
<?PHP endif; ?>
