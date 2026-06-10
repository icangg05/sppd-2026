  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
		<div class="row">
			<!-- ./col -->
			<div class="col-lg-12 col-xs-12">
				<div class="box box-success">
					<div class="box-header with-border">
					  <h3 class="box-title">NOTIFIKASI ERROR</h3>
					</div>
					<!-- /.box-header -->
					<div class="table-responsive box-body">
					 <?php
					 $message = $this->session->flashdata('notif');
					 if($message){
					   echo '<p class="alert alert-info text-center"><b>'.$message .'</b></p>';
					 }
					 ?>
					 <table class="table table-bordered table-striped table-hover">
					  <tr class='info'>
						<!--th style="width: 300px">Kode SKPD</th-->
						<th style="width: 200px">TANGGAL</th>
						<th style="width: 200px">JAM</th>
					  </tr>
					  <?php foreach($log as $v){
					   $id_log = base64_encode($this->encrypt->encode($v->id_log, $this->session->userdata('encrypt_key')));	
					   ?>
					   <tr>
						<td><?php echo $v->date;?></td>
						<td><?php echo $v->time;?></td>
						<td><?php echo $v->username;?></td>
						<td><?php echo $v->action;?></td>
						<td><?php echo $v->action_table;?></td>
					  </tr>
					  <?php } ?>
					</table>
				  </div>
				  <!-- /.box-body -->
				  <div class="box-footer clearfix">
				   <?php echo $links?>
				  </div>
				</div>
				<!-- /.box -->
			</div>
			<!-- ./col -->
		</div>
		<!-- /.row -->

	</section>
	<!-- /.content -->
</div>
  <!-- /.content-wrapper -->