 <!-- Content Wrapper. Contains page content -->
<style>
    .error {
    color: red;
	font-weight: bold;
}
 </style>
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
 						<h3 class="box-title">TAMBAH TANDA TANGAN</h3>
 					</div>
 					<!-- /.box-header -->
 					<!-- form start -->
 					
 					<?php echo form_open_multipart('setting_admin/tanda_tangan/create'); ?>
 					<div class="table-responsive box-body">
 						<?php if(validation_errors()){?>
 						<div class="alert alert-danger text-center">
 							<!--?php echo validation_errors(); ?-->
							Data Belum Lengkap !!!
 						</div>
 						<?php } ?>
				<div class="col-md-6">
					<div class="form-group">
						<?php 
							if(form_error('jabatan_id')){
								echo form_error('jabatan_id');
							} else { 
								echo "<label> Jabatan</label>";
							} 
						?>
						<select class="form-control" name="jabatan_id">
							<option value="">- Pilih Jabatan -</option>
							<?php foreach($jabatan as $v){ ?>
								<option value="<?php echo $v->jabatan_id ?>" <?php if (set_value('jabatan_id')==$v->jabatan_id){ echo "selected";} ?>  ><?php echo $v->nama_jabatan ?></option> ;
							<?php }?>
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label>Tanda Tangan</label>
						<input type="file" class="form-control" name="userfile" >
					</div>
				</div> 
				<div class="col-md-6">
					<div class="form-group">
						<?php 
							if(form_error('status')){
								echo form_error('status');
							} else { 
								echo "<label> Status</label>";
							} 
						?>
						<select class="form-control" name="status">
							<option value="1" <?php if (set_value('status')=="1"){ echo "selected";} ?> >Aktif</option>
							<option value="0" <?php if (set_value('status')=="0"){ echo "selected";} ?> >Tidak Aktif</option>
						</select>
					</div>
				</div>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<div class="col-md-6">					
					<button type="submit" class="btn btn-sm  btn-success btn-flat">Simpan</button>
					<button type="reset" class="btn btn-sm btn-danger btn-flat">Reset</button>
					<a href="<?php echo base_url();?>setting_admin/tanda_tangan" class="btn btn-sm btn-warning btn-flat">Kembali</a>
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
<script>
	$(function () {
		//Initialize Select2 Elements
		$(".select2").select2();
	});
</script>