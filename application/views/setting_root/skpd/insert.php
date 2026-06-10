<?php
$is_superadmin = ($this->ion_auth->get_users_groups()->row()->id == 100 || ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->skpd_id == ""));
?>
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
 						<h3 class="box-title">TAMBAH OPD</h3>
 					</div>
 					<!-- /.box-header -->
 					<!-- form start -->

 					<?php echo form_open_multipart('setting_root/skpd/create'); ?>
 					<div class="table-responsive box-body">
 						<?php if (validation_errors()) { ?>
 							<div class="alert alert-danger text-center">
 								<?php echo validation_errors(); ?>
 							</div>
 						<?php } ?>
 						<?php
							$error = $this->session->flashdata('error');
							if ($error) {
								echo '<div class="alert alert-danger text-center"><b>' . $error . '</b></div>';
							}
							?>
 						<!--div class="col-md-6">
					<div class="form-group">
					  <label>Kode SKPD</label>
					  <input type="text" class="form-control" name="skpd_kode">
					</div>
				</div-->
 						<div class="col-md-6">
 							<div class="form-group">
 								<label>Kode OPD</label>
 								<input placeholder="Masukkan kode OPD..." type="text" class="form-control" name="skpd_kode">
 							</div>
 						</div>
 						<div class="col-md-6">
 							<div class="form-group">
 								<label>Nama OPD</label>
 								<input type="text" class="form-control" name="skpd_nama">
 							</div>
 						</div>
 						<div class="col-md-6 col-md-offset-6">
 						</div>
 						<div class="col-md-6">
 							<div class="form-group">
 								<label>Nama Pimpinan</label>
 								<input type="text" class="form-control" name="kepala">
 							</div>
 						</div>
 						<div class="col-md-6">
 							<div class="form-group">
 								<label>NIP Pimpinan</label>
 								<input type="number" class="form-control" name="nip">
 							</div>
 						</div>
 						<div class="col-md-6">
 							<div class="form-group">
 								<label>KOP Surat</label>
 								<input type="file" class="form-control" name="userfile">
 							</div>
 						</div>
 						<div class="col-md-6">
 							<div class="form-group">
 								<label>Jenis OPD</label>
 								<select class="form-control" name="jenis_skpd"
 									onchange=" if (this.selectedIndex==5){ 
							document.getElementById('kelurahan').style.display = 'inline'; 
						} else {
							document.getElementById('kelurahan').style.display = 'none'; 
						} ;"
									<?php if (!$is_superadmin) echo 'disabled'; ?>>
 									<option value="">- Pilih Jenis OPD-</option>
 									<?php foreach ($jenis_skpd as $v) { ?>
 										<option value="<?php echo $v->jenis_skpd_id; ?>"><?php echo $v->nama_jenis_skpd; ?></option>
 									<?php } ?>
 								</select>
								<?php if (!$is_superadmin) { ?>
									<input type="hidden" name="jenis_skpd" value="">
								<?php } ?>
 							</div>
 						</div>
 						<div class="col-md-6">
 							<div class="form-group">
 								<label>Tanda Tangan</label>
 								<input type="file" class="form-control" name="imagefile">
 							</div>
 						</div>
 						<span id="kelurahan" style="display:none;">
 							<div class="col-md-6">
 								<div class="form-group">
 									<label>Kecamatan</label>
 									<select class="form-control" name="id_kecamatan" id="provinsi_id">
 										<option value="">- Pilih Kecamatan -</option>
 										<?php foreach ($skpd as $v) {
												echo "<option value='$v->skpd_id'>$v->skpd_nama</option>";
											} ?>
 									</select>
 								</div>
 							</div>
 					</div>
 					<!-- /.box-body -->
 					<div class="box-footer">
 						<div class="col-md-6">
 							<button type="submit" class="btn btn-sm  btn-success btn-flat">Simpan</button>
 							<button type="reset" class="btn btn-sm btn-danger btn-flat">Reset</button>
 							<a href="<?php echo base_url(); ?>setting_root/skpd" class="btn btn-sm btn-warning btn-flat">Kembali</a>
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
 			format: 'yyyy-mm-dd',
 			autoclose: true
 		});
 	});
 </script>
 <script>
 	$(function() {
 		//Initialize Select2 Elements
 		$(".select2").select2();
 	});
 </script>