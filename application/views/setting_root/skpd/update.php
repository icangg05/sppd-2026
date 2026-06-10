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
						<h3 class="box-title">EDIT OPD</h3>
					</div>
					<!-- /.box-header -->
					<!-- form start -->


					<?php echo form_open_multipart('setting_root/skpd/update'); ?>
					<div class="box-body">
						<?php if (validation_errors()) { ?>
							<div class="alert alert-danger text-center">
								<?php echo validation_errors(); ?>
							</div>
						<?php } ?>
						<?php
						$message = $this->session->flashdata('notif');
						if ($message) {
							echo '<div class="alert alert-success text-center"><b>' . $message . '</b></div>';
						}
						$error = $this->session->flashdata('error');
						if ($error) {
							echo '<div class="alert alert-danger text-center"><b>' . $error . '</b></div>';
						}
						?>
						<input type="hidden" class="form-control" name="skpd_id" value="<?php echo $entry[0]['skpd_id'] ?>">
						<!--div class="col-md-6">
					<div class="form-group">
					  <label>Kode SKPD</label>
					  <input type="text" class="form-control" name="skpd_kode" value="<?php //echo $entry[0]['skpd_kode']
																																						?>">
					</div>
				 </div-->
						<div class="col-md-6">
							<div class="form-group">
								<label>Kode OPD</label>
								<input placeholder="Masukkan kode OPD..." type="text" class="form-control" name="skpd_kode" value="<?php echo $entry[0]['skpd_kode'] ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Nama OPD</label>
								<input type="text" class="form-control" name="skpd_nama" value="<?php echo $entry[0]['skpd_nama'] ?>">
							</div>
						</div>
						<div class="col-md-6 col-md-offset-6">
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Nama Pimpinan</label>
								<input type="text" class="form-control" name="kepala" value="<?php echo $entry[0]['kepala'] ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>NIP Pimpinan</label>
								<input type="number" class="form-control" name="nip" value="<?php echo $entry[0]['nip'] ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>KOP Surat</label>
								<input type="file" class="form-control" name="userfile" value="<?php echo $entry[0]['kop_surat']; ?>">
								<?php if ($entry[0]['kop_surat']) { ?>
									<img src="<?php echo cek_file('upload/kop_surat/' . $entry[0]['kop_surat'], 'assets/img/works/1big-1.jpg'); ?>" width="500">
								<?php } ?>
							</div>
						</div>

						<?php if ($this->ion_auth->user()->row()->jenis_skpd == 2) { ?>
							<div class="col-md-6">
								<div class="form-group">
									<label>KOP Surat Anggota DPRD</label>
									<input type="file" class="form-control" name="userfile2" value="<?php echo $entry[0]['kop_surat2']; ?>">
									<?php if ($entry[0]['kop_surat2']) { ?>
										<img src="<?php echo cek_file('upload/kop_surat/' . $entry[0]['kop_surat2'], 'assets/img/works/1big-1.jpg'); ?>" width="500">
									<?php } ?>
								</div>
							</div>
						<?php } ?>

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
									<?php foreach ($jenis_skpd as $v) {
										if ($entry[0]['jenis_skpd'] == $v->jenis_skpd_id) {
											echo "<option value='$v->jenis_skpd_id' selected>$v->nama_jenis_skpd</option>";
										} else {
											echo "<option value='$v->jenis_skpd_id'>$v->nama_jenis_skpd</option>";
										}
									} ?>
								</select>
								<?php if (!$is_superadmin) { ?>
									<input type="hidden" name="jenis_skpd" value="<?php echo $entry[0]['jenis_skpd']; ?>">
								<?php } ?>
							</div>
						</div>
						<?php if ($entry[0]['relasi_id']) { ?>
							<span id="kelurahan" style="display:inline;">
							<?php } else { ?>
								<span id="kelurahan" style="display:none;">
								<?php } ?>
								<div class="col-md-6">
									<div class="form-group">
										<label>Kecamatan</label>
										<select class="form-control" name="id_kecamatan">
											<option value="">- Pilih Kecamatan -</option>
											<?php foreach ($skpd as $v) {
												if ($entry[0]['id_kecamatan'] == $v->skpd_id) {
													echo "<option value='$v->skpd_id' selected>$v->skpd_nama</option>";
												} else {
													echo "<option value='$v->skpd_id'>$v->skpd_nama</option>";
												}
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