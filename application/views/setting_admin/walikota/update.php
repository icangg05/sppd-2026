 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<!-- Main content -->
 	<section class="content">
 		<div class="row">
 			<!-- left column -->
 			<div class="col-md-12">
 				<!-- general form elements -->
 				<div class="box box-primary">
 					<div class="box-header with-border">
 						<h3 class="box-title">EDIT PEGAWAI</h3>
 					</div>
 					<!-- /.box-header -->
 					<!-- form start -->
 					
 					<?php echo form_open_multipart('setting_admin/walikota/update'); ?>
 					<div class="table-responsive box-body">
 						<?php if(validation_errors()){?>
 						<div class="alert alert-danger text-center">
 							<?php echo validation_errors(); ?>
 						</div>
 						<?php }?>
						<?php
						 $message = $this->session->flashdata('notif');
						 if($message){
						   echo '<p class="alert alert-info text-center"><b>'.$message .'</b></p>';
						 }
						?>
 						<input type="hidden" class="form-control" name="pegawai_id" value="<?php echo $entry[0]['pegawai_id'];?>">
 						<table class="table table-bordered table-striped">
 							<tr class="info">
 								<th class="col-md-3" colspan="2"><center>DATA DIRI</center></th>
 							</tr>
 							<tr>
 								<th class="col-md-3">NIK</th>
 								<td><input type="text" class="form-control" name="pegawai_nik" value="<?php echo $entry[0]['pegawai_nik'];?>"></td>
 							</tr>						
							<tr> 								
								<th class="col-md-3">Nama Walikota/Wakil Walikota</th> 								
								<td><input type="text" class="form-control" name="pegawai_nama" value="<?php echo $entry[0]['pegawai_nama'];?>"></td> 							
							</tr>	
							<tr>								
								<th class="col-md-3">Jabatan</th>								
								<td>
									<select class="form-control" name="pegawai_jabatan">									
									<option value="">- Pilih Jabatan -</option>									
									<option value='1' <?php if($entry[0]['pegawai_jabatan']==1){ ?> selected <?php } ?>>WALIKOTA</option>
									<option value='14'<?php if($entry[0]['pegawai_jabatan']==14){ ?> selected <?php } ?>>WAKIL WALIKOTA</option>
									<option value='16'<?php if($entry[0]['pegawai_jabatan']==16){ ?> selected <?php } ?>>LAINNYA</option>		
									</select>
								</td>							
							</tr>							
							<tr>								
								<th class="col-md-3">Nama Jabatan</th>								
								<td><input type="text" class="form-control" name="pegawai_namajabatan" value="<?php echo $entry[0]['pegawai_namajabatan'];?>"></td>							
							</tr>				
					
							<tr>
								<th class="col-md-3">Status Perjalanan</th>
								<td>
									<select class="form-control" name="status">
										<option value="1" <?php if ($entry[0]['status']==1) { echo "selected"; } ?> >Dalam Perjalanan</option>
										<option value="0" <?php if ($entry[0]['status']==0) { echo "selected"; } ?> >Tidak Dalam Perjalanan</option>
									</select>
								</td>
							</tr>
				</table>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<div class="col-md-6">					
					<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan</button>
					<button type="reset" class="btn btn-warning btn-sm" ><i class="fa fa-repeat"></i> Reset</button>
					<a href="<?php echo base_url();?>setting_admin/walikota" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
				</div>
			</div>
			<?php echo form_close(); ?>
		</div>
		<!-- /.box -->
		
	</div>
	<!--/.col (left) -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
	var ckeditor = CKEDITOR.replace('ckeditor');
</script>
<script type="text/javascript">
	$(function() {
		$('#datepicker').datepicker({
			format:'yyyy-mm-dd',
			autoclose: true
		});
	});
</script>