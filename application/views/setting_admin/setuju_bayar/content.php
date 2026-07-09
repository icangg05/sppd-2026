<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-md-8">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Setuju Bayar / Mengetahui (SETDA)</h3>
					</div>
					<!-- /.box-header -->

					<?php echo form_open('setting_admin/setuju_bayar/update'); ?>
					<div class="box-body">

						<?php
							$message = $this->session->flashdata('notif');
							if ($message) {
								echo '<div class="alert alert-info text-center"><b>' . $message . '</b></div>';
							}
							$error = $this->session->flashdata('error');
							if ($error) {
								echo $error;
							}
						?>
						<?php if (validation_errors()) { ?>
							<div class="alert alert-danger">
								<?php echo validation_errors(); ?>
							</div>
						<?php } ?>

						<p class="text-muted">
							Nilai ini dipakai pada cetak
							<b>Kuitansi Rampung</b>, <b>Rincian Biaya Perjalanan Dinas</b>, dan
							<b>Daftar Pengeluaran Rill</b>.
							<br>Cukup isi <b>Label</b> dan pilih <b>Nama Penandatangan</b>.
						</p>

						<table class="table table-bordered table-striped">
							<tr class="info">
								<th class="col-md-3" colspan="2"><center>DATA PENANDATANGAN</center></th>
							</tr>
							<tr>
								<th class="col-md-3">Label / Jabatan</th>
								<td>
									<input type="text" class="form-control" name="label" placeholder="Contoh: PENGGUNA ANGGARAN" value="<?php echo set_value('label', $setuju_bayar['label']); ?>">
								</td>
							</tr>
							<tr>
								<th class="col-md-3">Nama Penandatangan</th>
								<td>
									<select class="form-control select2" name="pegawai_id" style="width:100%">
										<option value="">- Pilih Pegawai -</option>
										<?php foreach ($pegawai as $p) { ?>
											<option value="<?php echo $p->pegawai_id; ?>" <?php echo (set_value('pegawai_id', $setuju_bayar['pegawai_id']) == $p->pegawai_id) ? 'selected' : ''; ?>>
												<?php echo $p->pegawai_nama; ?><?php echo ($p->pegawai_nip) ? ' - NIP. ' . $p->pegawai_nip : ''; ?>
											</option>
										<?php } ?>
									</select>
								</td>
							</tr>
						</table>
					</div>
					<!-- /.box-body -->

					<div class="box-footer">
						<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan</button>
						<button type="reset" class="btn btn-warning btn-sm"><i class="fa fa-repeat"></i> Reset</button>
					</div>
					<?php echo form_close(); ?>
				</div>
				<!-- /.box -->
			</div>
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
	$(function () {
		$(".select2").select2();
	});
</script>
