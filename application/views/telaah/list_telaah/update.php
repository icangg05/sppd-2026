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
						<h3 class="box-title">
							<?php
							switch ($this->uri->segment(4)) {
								case "esselon":
									echo "EDIT TELAAH (Esselon III, IV dan Staff)";
									break;
								case "kadis":
									echo "EDIT TELAAH (Kepala OPD)";
									break;
								case "dprd":
									echo "EDIT TELAAH (DPRD)";
									break;
								case "sekda":
									echo "EDIT TELAAH (Sekda, Asisten dan Kabag)";
									break;
								case "camat":
									echo "EDIT TELAAH (Camat)";
									break;
								case "lurah":
									echo "EDIT TELAAH (Lurah)";
									break;
								case "staff_dprd":
									echo "EDIT TELAAH (Staff DPRD)";
									break;
								case "staff_camat":
									echo "EDIT TELAAH (Staff Camat)";
									break;
								case "staff_lurah":
									echo "EDIT TELAAH (Staff Lurah)";
									break;
								case "walikota":
									echo "EDIT TELAAH (Walikota)";
									break;
								case "staff_setda":
									echo "EDIT TELAAH (Kasubag dan Staff Setda)";
									break;
								case "sekwan":
									echo "EDIT TELAAH (Sekwan)";
									break;
								case "kapus":
									echo "EDIT TELAAH (Kepala Puskesmas)";
									break;
							}
							?>
						</h3>
					</div>
					<!-- /.box-header -->
					<!-- form start -->
					<?php echo form_open_multipart('telaah/list_telaah/update/' . $this->uri->segment(4)); ?>
					<div class="table-responsive box-body">
						<?php
						$message = $this->session->flashdata('notif');
						if ($message) {
							echo '<p class="alert alert-success text-center"><b>' . $message . '</b></p>';
						}
						$message2 = $this->session->flashdata('notif2');
						if ($message2) {
							echo '<p class="alert alert-danger text-center"><b>' . $message2 . '</b></p>';
						}
						?>
						<table class="table table-bordered ">
							<input type="hidden" class="form-control" name="telaah_id" value="<?php echo $entry[0]['telaah_id'] ?>">
							<input type="hidden" class="form-control" name="telaah_status" value="<?php echo $entry[0]['telaah_status'] ?>">
							<input type="hidden" class="form-control" name="telaah_kategori" value="<?php echo $entry[0]['telaah_kategori'] ?>">
							<input type="hidden" class="form-control" name="telaah_disetujui" value="<?php echo $this->input->get('telaah_disetujui') ?>">
							<input type="hidden" class="form-control" name="telaah_ditolak" value="<?php echo $this->input->get('telaah_ditolak') ?>">
							<tr class="info">
								<th class="col-md-3" colspan="2">
									<center>DATA PERIHAL</center>
								</th>
							</tr>
							<tr>
								<th class="col-md-3">Perihal (Maksud Perjalanan Dinas)</th>
								<td><?php echo form_error('telaah_perihal'); ?>
									<textarea class="form-control" name="telaah_perihal" rows="4"><?php echo $entry[0]['telaah_perihal'] ?></textarea>
								</td>
							</tr>
							<tr>
								<th class="col-md-3">Persoalan</th>
								<td>
									<?php echo form_error('telaah_persoalan'); ?>
									<textarea class="form-control" name="telaah_persoalan" rows="4"><?php echo $entry[0]['telaah_persoalan'] ?></textarea>
								</td>
							</tr>
							<tr>
								<th class="col-md-3">Fakta yang mempengaruhi</th>
								<td>
									<?php echo form_error('telaah_fakta'); ?>
									<textarea class="form-control" name="telaah_fakta" rows="4"><?php echo $entry[0]['telaah_fakta'] ?></textarea>
								</td>
							</tr>
							<tr>
								<th class="col-md-3">Analisis</th>
								<td>
									<?php echo form_error('telaah_analisis'); ?>
									<textarea class="form-control" name="telaah_analisis" rows="4"><?php echo $entry[0]['telaah_analisis'] ?></textarea>
								</td>
							</tr>
							<tr class="info">
								<th class="col-md-3" colspan="2">
									<center>DATA PERJALANAN</center>
								</th>
							</tr>
							<tr>
								<td class="col-md-3" colspan="2">
									<div class="col-md-6">
										<div class="form-group">
											<?php
											if (form_error('telaah_jenisangkutan')) {
												echo form_error('telaah_jenisangkutan');
											} else {
												echo "<label> Jenis Angkutan</label>";
											}
											?>
											<select class="form-control" name="telaah_jenisangkutan">
												<option value="">- Pilih -</option>
												<option value="Darat" <?php if ($entry[0]['telaah_jenisangkutan'] == "Darat") {
																								echo "selected";
																							} ?>>Darat</option>
												<option value="Udara" <?php if ($entry[0]['telaah_jenisangkutan'] == "Udara") {
																								echo "selected";
																							} ?>>Udara</option>
												<option value="Air" <?php if ($entry[0]['telaah_jenisangkutan'] == "Air") {
																							echo "selected";
																						} ?>>Air</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php
											if (form_error('telaah_angkutan')) {
												echo form_error('telaah_angkutan');
											} else {
												echo "<label> Angkutan</label>";
											}
											?>
											<select class="form-control" name="telaah_angkutan">
												<option value="">- Pilih -</option>
												<option value="Mobil" <?php if ($entry[0]['telaah_angkutan'] == "Mobil") {
																								echo "selected";
																							} ?>>Mobil</option>
												<option value="Pesawat" <?php if ($entry[0]['telaah_angkutan'] == "Pesawat") {
																									echo "selected";
																								} ?>>Pesawat</option>
												<option value="Kapal" <?php if ($entry[0]['telaah_angkutan'] == "Kapal") {
																								echo "selected";
																							} ?>>Kapal</option>
												<option value="Kereta" <?php if ($entry[0]['telaah_angkutan'] == "Kereta") {
																									echo "selected";
																								} ?>>Kereta</option>
												<option value="Lainnya" <?php if ($entry[0]['telaah_angkutan'] == "Lainnya") {
																									echo "selected";
																								} ?>>Lainnya</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php
											if (form_error('telaah_tanggalberangkat')) {
												echo form_error('telaah_tanggalberangkat');
											} else {
												echo "<label> Tanggal Berangkat</label>";
											}
											?>
											<div class="input-group ">
												<input id="datepicker" type="text" class="form-control" name="telaah_tanggalberangkat" value="<?php echo $entry[0]['telaah_tanggalberangkat'] ?>">
												<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php
											if (form_error('telaah_tanggalkembali')) {
												echo form_error('telaah_tanggalkembali');
											} else {
												echo "<label> Tanggal Kembali</label>";
											}
											?>
											<div class="input-group ">
												<input id="datepicker2" type="text" class="form-control" name="telaah_tanggalkembali" value="<?php echo $entry[0]['telaah_tanggalkembali']; ?>">
												<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php
											if (form_error('telaah_hari')) {
												echo form_error('telaah_hari');
											} else {
												echo "<label> Lama Perjalanan (Hari)</label>";
											}
											?>
											<div class="input-group ">
												<input type="text" value="<?php echo $entry[0]['telaah_hari']; ?>" class="form-control" onkeyup="formatRupiah(this, '.')" name="telaah_hari">
												<span class="input-group-addon">Hari</span>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php
											if (form_error('telaah_tempatberangkat')) {
												echo form_error('telaah_tempatberangkat');
											} else {
												echo "<label> Tempat Berangkat</label>";
											}
											?>
											<input type="text" class="form-control" value="<?php echo $entry[0]['telaah_tempatberangkat']; ?>" name="telaah_tempatberangkat">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<?php
											if (form_error('telaah_domainperjalanan')) {
												echo form_error('telaah_domainperjalanan');
											} else {
												echo "<label> Domain Perjalanan</label>";
											}
											?>
											<select class="form-control" name="telaah_domainperjalanan"
												onchange=" if (this.selectedIndex==1){ 
											document.getElementById('ldlp').style.display = 'inline'; 
											document.getElementById('lddp').style.display = 'none'; 
										} else if (this.selectedIndex==2){
											document.getElementById('ldlp').style.display = 'none'; 
											document.getElementById('lddp').style.display = 'inline';
										} else {
											document.getElementById('ldlp').style.display = 'none'; 
											document.getElementById('lddp').style.display = 'none';
										} ;">
												<option value="">- Pilih -</option>
												<option value="1" <?php if ($entry[0]['telaah_domainperjalanan'] == "1") {
																						echo "selected";
																					} ?>>LUAR DAERAH LUAR PROVINSI (LDLP)</option>
												<option value="2" <?php if ($entry[0]['telaah_domainperjalanan'] == "2") {
																						echo "selected";
																					} ?>>LUAR DAERAH DALAM PROVINSI (LDDP)</option>
												<option value="3" <?php if ($entry[0]['telaah_domainperjalanan'] == "3") {
																						echo "selected";
																					} ?>>DALAM DAERAH</option>
											</select>
										</div>
									</div>

									<div class="col-md-6 col-md-offset-6">
									</div>
									<?php if ($entry[0]['telaah_domainperjalanan'] == "1") { ?>
										<span id="ldlp">
										<?php } else { ?>
											<span id="ldlp" style="display:none;">
											<?php }  ?>
											<div class="col-md-6">
												<div class="form-group">
													<label>Provinsi</label>
													<select class="form-control" name="telaah_provinsitujuan" id="provinsi_id" selectpicker chzn-select" onChange="tampil_kabupaten()" data-live-search="true" data-live-search-style="begins">
														<option value="">- Pilih Provinsi -</option>
														<?php foreach ($provinsi as $v) {
															if ($entry[0]['telaah_provinsitujuan'] == $v->provinsi_id) {
																echo "<option value='$v->provinsi_id' selected>$v->provinsi</option>";
															} else {
																echo "<option value='$v->provinsi_id'>$v->provinsi</option>";
															}
														} ?>
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Kota Tujuan</label>
													<select class="form-control" name="telaah_kotatujuan" id="kabkot_id" selectpicker chzn-select" data-live-search="true" data-live-search-style="begins">
														<option value="">- Pilih Kabupaten/Kota -</option>
														<?php
														if ($entry[0]['telaah_domainperjalanan'] == "1") {
															$kabupaten = $this->m_telaah->get_kabupaten($entry[0]['telaah_provinsitujuan']);
															foreach ($kabupaten as $v) {
																if ($entry[0]['telaah_kotatujuan'] == $v->kabkot_id) {
																	echo "<option value='$v->kabkot_id' selected>$v->kabupaten_kota</option>";
																} else {
																	echo "<option value='$v->kabkot_id'>$v->kabupaten_kota</option>";
																}
															}
														} ?>
													</select>
												</div>
											</div>
											</span>
											<?php if ($entry[0]['telaah_domainperjalanan'] == "2") { ?>
												<span id="lddp">
												<?php } else { ?>
													<span id="lddp" style="display:none;">
													<?php }  ?>
													<div class="col-md-6">
														<div class="form-group">
															<label>Kota Tujuan</label>
															<select class="form-control" name="telaah_kotatujuan2" data-live-search="true" data-live-search-style="begins">
																<option value="">- Pilih Kabupaten/Kota -</option>
																<?php
																$kabupaten = $this->m_telaah->get_kabupaten(74);
																foreach ($kabupaten as $v) {
																	if ($entry[0]['telaah_kotatujuan'] == $v->kabkot_id) {
																		echo "<option value='$v->kabkot_id' selected>$v->kabupaten_kota</option>";
																	} else {
																		echo "<option value='$v->kabkot_id'>$v->kabupaten_kota</option>";
																	}
																} ?>
															</select>
														</div>
													</div>
													</span>
													<div class="col-md-6">
														<div class="form-group">
															<?php
															if (form_error('telaah_kantortujuan')) {
																echo form_error('telaah_kantortujuan');
															} else {
																echo "<label> Tempat Tujuan(Contoh : Kantor, Gedung, Hotel, Dll)</label>";
															}
															?>
															<label></label>
															<input type="text" class="form-control" name="telaah_kantortujuan" value="<?php echo $entry[0]['telaah_kantortujuan']; ?>">
														</div>
													</div><br>
													<div class="col-md-12">
													</div>
													<div class="col-md-12">
														<button type="button" class="btn btn-success btn-sm" id="btn-tambah-form">Tambah Tujuan</button>
														<button type="button" class="btn btn-danger btn-sm" id="btn-reset-form">Reset Tujuan</button><br><br>
													</div>
													<div class="col-md-12">
														<table class="table table-bordered table-striped table-hover">
															<tr class='info'>
																<th style="col-md-5">Provinsi</th>
																<th style="col-md-6">Kota</th>
																<th style="col-md-1">Aksi</th>
															</tr>
															<?php
															$lokasi_tujuan = $this->m_lokasi_tujuan->get($entry[0]['telaah_id']);
															foreach ($lokasi_tujuan as $v) {
																$telaah_id = base64_encode($this->encrypt->encode($v->telaah_id, $this->session->userdata('encrypt_key')));
																$lokasi_tujuan_id = base64_encode($this->encrypt->encode($v->lokasi_tujuan_id, $this->session->userdata('encrypt_key')));
															?>
																<tr>
																	<td><?php echo $v->provinsi; ?></td>
																	<td><?php echo $v->kabupaten_kota; ?></td>
																	<td><a href="<?php echo base_url(); ?>telaah/list_telaah/delete_location/<?php echo $this->uri->segment(4) ?>?telaah_id=<?php echo $telaah_id ?>&&lokasi_tujuan_id=<?php echo $lokasi_tujuan_id ?>" class="btn btn-sm btn-flat btn-danger" onclick="return confirm('Anda Yakin ?');"><i class="fa fa-close"></i></a></td>
																</tr>
															<?php } ?>
														</table>
													</div>
													<div id="insert-form"></div>
													<input type="hidden" id="jumlah-form" value="1">
								</td>
							</tr>
							<tr class="info">
								<th class="col-md-3" colspan="2">
									<center>DATA KEGIATAN</center>
								</th>
							</tr>
							<tr>
								<th class="col-md-3">Kegiatan</th>
								<td>
									<?php echo form_error('telaah_kegiatan'); ?>
									<select class="form-control" name="telaah_kegiatan" id="anggaran" onChange="tampil_anggaran()">
										<option value="">- Pilih -</option>
										<?php
										foreach ($anggaran as $v) {
											if ($entry[0]['telaah_kegiatan'] == $v->id_anggaran) {
												echo '<option value="' . $v->id_anggaran . '" selected>' . $v->nama_program . ' || ' . $v->nama_kegiatan . '</option>';
											} else {
												echo '<option value="' . $v->id_anggaran . '">' . $v->nama_program . ' || ' . $v->nama_kegiatan . '</option>';
											}
										}
										?>
									</select>
									<div id="jumlah_anggaran">
									</div>
								</td>
							</tr>
							<tr>
								<th class="col-md-3">Kategori Perjalanan</th>
								<td>
									<?php echo form_error('telaah_kategoriperjalanan'); ?>
									<select class="form-control" name="telaah_kategoriperjalanan">
										<option value="">- Pilih -</option>
										<option value="1" <?php if ($entry[0]['telaah_kategoriperjalanan'] == "1") {
																				echo "selected";
																			} ?>>Undangan</option>
										<option value="2" <?php if ($entry[0]['telaah_kategoriperjalanan'] == "2") {
																				echo "selected";
																			} ?>>Konsultasi/Koordinasi</option>
										<option value="3" <?php if ($entry[0]['telaah_kategoriperjalanan'] == "3") {
																				echo "selected";
																			} ?>>Studi Banding</option>
									</select>
								</td>
							</tr>
							<tr>
								<th class="col-md-3">Kecepatan Telaah</th>
								<td>
									<?php echo form_error('telaah_kecepatan'); ?>
									<select class="form-control" name="telaah_kecepatan">
										<option value="">- Pilih -</option>
										<option value="0" <?php if ($entry[0]['telaah_kecepatan'] == "0") {
																				echo "selected";
																			} ?>>Biasa</option>
										<option value="1" <?php if ($entry[0]['telaah_kecepatan'] == "1") {
																				echo "selected";
																			} ?>>Segera</option>
									</select>
								</td>
							</tr>
							<tr>
								<th class="col-md-3">Dokumen Pendukung</th>
								<?php echo form_error('userfile');
								if ($entry[0]['telaah_dokumenpendukung']) {
								?>
									<td><input type="file" name="userfile"><a href="<?php echo base_url('upload/telaah/' . $entry[0]['telaah_dokumenpendukung']); ?>" target="_blank">Lihat File</a></td>
								<?php } else { ?>
									<td><input type="file" name="userfile"></td>
								<?php } ?>
							</tr>
							<tr>
								<th class="col-md-3">Pelaksana Perjalanan Dinas</th>
								<td>
									<?php echo form_error('telaah_pelaksana'); ?>
									<select class="form-control select2" name="telaah_pelaksana">
										<option value="">- Pilih -</option>
										<?php foreach ($pelaksana as $v) : ?>
											<?php $selected = ($entry[0]['telaah_pelaksana'] == $v->pegawai_id) ? 'selected' : ''; ?>

											<option value="<?= $v->pegawai_id ?>" <?= $selected ?>>
												<?= !empty($v->pegawai_nip) ? (int) $v->pegawai_nip . ' || ' : '' ?> <?= $v->pegawai_nama ?>
											</option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<?php if ($this->uri->segment(4) == 'esselon') { ?>
								<tr>
									<th class="col-md-3"></th>
									<td>
										<div class="checkbox">
											<label>
												<input type="checkbox" name="telaah_sekretariat" value="1"> Pegawai Sekretariat
											</label>
										</div>
									</td>
								</tr>
							<?php } ?>
							<tr>
								<th class="col-md-3">Pengikut</th>
								<td>
									<select class="form-control select2" name="telaah_pengikut[]" multiple="multiple">
										<option value="">- Pilih -</option>
										<?php foreach ($pengikut as $v): ?>
											<?php
											$dataPengikut = $this->m_pengikut->get($entry[0]['telaah_id'], $v->pegawai_id);
											$isSelected   = (!empty($dataPengikut) && $v->pegawai_id == $dataPengikut[0]['pegawai_id']);
											?>

											<option value="<?= $v->pegawai_id; ?>" <?= $isSelected ? 'selected' : ''; ?>>
												<?= !empty($v->pegawai_nip) ? (int) $v->pegawai_nip . ' || ' : '' ?> <?= $v->pegawai_nama ?>
											</option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
						</table>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
						<div class="col-md-6">
							<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan</button>
							<button type="reset" class="btn btn-warning btn-sm"><i class="fa fa-repeat"></i> Reset</button>
							<a href="<?php echo base_url(); ?>telaah/list_telaah/index/<?php echo $this->uri->segment(4) ?>" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
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
	if (document.getElementById('ckeditor')) {
		var ckeditor = CKEDITOR.replace('ckeditor');
	}
</script>
<script type="text/javascript">
	$(function() {
		$('#datepicker').datepicker({
			format: 'yyyy-mm-dd',
			autoclose: true
		});
	});
	$(function() {
		$('#datepicker2').datepicker({
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

<script>
	$(document).ready(function() { // Ketika halaman sudah diload dan siap
		$("#btn-tambah-form").click(function() { // Ketika tombol Tambah Data Form di klik
			var jumlah = parseInt($("#jumlah-form").val()); // Ambil jumlah data form pada textbox jumlah-form
			var nextform = jumlah + 1; // Tambah 1 untuk jumlah form nya

			// Kita akan menambahkan form dengan menggunakan append
			// pada sebuah tag div yg kita beri id insert-form
			$("#insert-form").append(
				"<div class='col-md-6'>" +
				"<div class='form-group'>" +
				"<label>Provinsi</label>" +
				"<select class='form-control' name='telaah_provinsitujuan2[]' id='provinsi_id" + jumlah + "' selectpicker chzn-select' onChange='tampil_kabupaten" + jumlah + "()' data-live-search='true' data-live-search-style='begins'>" +
				"<option value=''>- Pilih Provinsi -</option>" +
				"<?php foreach ($provinsi as $v) {
						echo "<option value='$v->provinsi_id'>$v->provinsi</option>";
					} ?>" +
				"</select>" +
				"</div>" +
				"</div>" +

				"<div class='col-md-6'>" +
				"<div class='form-group'>" +
				"<label>Kota Tujuan</label>" +
				"<select class='form-control' name='telaah_kotatujuan3[]' id='kabkot_id" + jumlah + "' selectpicker chzn-select' data-live-search='true' data-live-search-style='begins'>" +
				"<option value=''>- Pilih Kabupaten/Kota -</option>" +
				"</select>" +
				"</div>" +
				"</div>");


			$("#jumlah-form").val(nextform); // Ubah value textbox jumlah-form dengan variabel nextform
		});

		// Buat fungsi untuk mereset form ke semula
		$("#btn-reset-form").click(function() {
			$("#insert-form").html(""); // Kita kosongkan isi dari div insert-form
			$("#jumlah-form").val("1"); // Ubah kembali value jumlah form menjadi 1
		});
	});
</script>
<script>
	function tampil_kabupaten() {
		provinsi_id = document.getElementById("provinsi_id").value;
		$.ajax({
			url: "<?php echo base_url(); ?>beranda/get/" + provinsi_id + "",
			success: function(response) {
				$("#kabkot_id").html(response);
			}
		});
		return false;
	}
</script>
<script>
	function tampil_kabupaten1() {
		provinsi_id1 = document.getElementById("provinsi_id1").value;
		$.ajax({
			url: "<?php echo base_url(); ?>beranda/get/" + provinsi_id1 + "",
			success: function(response) {
				$("#kabkot_id1").html(response);
			}
		});
		return false;
	}
</script>
<script>
	function tampil_kabupaten2() {
		provinsi_id2 = document.getElementById("provinsi_id2").value;
		$.ajax({
			url: "<?php echo base_url(); ?>beranda/get/" + provinsi_id2 + "",
			success: function(response) {
				$("#kabkot_id2").html(response);
			}
		});
		return false;
	}
</script>
<script>
	function tampil_kabupaten3() {
		provinsi_id3 = document.getElementById("provinsi_id3").value;
		$.ajax({
			url: "<?php echo base_url(); ?>beranda/get/" + provinsi_id3 + "",
			success: function(response) {
				$("#kabkot_id3").html(response);
			}
		});
		return false;
	}
</script>
<script>
	function tampil_kabupaten4() {
		provinsi_id4 = document.getElementById("provinsi_id4").value;
		$.ajax({
			url: "<?php echo base_url(); ?>beranda/get/" + provinsi_id4 + "",
			success: function(response) {
				$("#kabkot_id4").html(response);
			}
		});
		return false;
	}
</script>
<script>
	function tampil_kabupaten5() {
		provinsi_id5 = document.getElementById("provinsi_id5").value;
		$.ajax({
			url: "<?php echo base_url(); ?>beranda/get/" + provinsi_id5 + "",
			success: function(response) {
				$("#kabkot_id5").html(response);
			}
		});
		return false;
	}
</script>
<script>
	function tampil_anggaran() {
		anggaran = document.getElementById("anggaran").value;
		$.ajax({
			url: "<?php echo base_url(); ?>beranda/get_anggaran/" + anggaran + "",
			success: function(response) {
				$("#jumlah_anggaran").html(response);
			}
		});
		return false;
	}
</script>