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
									echo "TAMBAH TELAAH (Esselon III, IV dan Staff)";
									break;
								case "kadis":
									echo "TAMBAH TELAAH (Kepala OPD)";
									break;
								case "dprd":
									echo "TAMBAH TELAAH (DPRD)";
									break;
								case "sekda":
									echo "TAMBAH TELAAH (Sekda, Asisten dan Kabag)";
									break;
								case "camat":
									echo "TAMBAH TELAAH (Camat)";
									break;
								case "lurah":
									echo "TAMBAH TELAAH (Lurah)";
									break;
								case "staff_dprd":
									echo "TAMBAH TELAAH (Staff DPRD)";
									break;
								case "staff_camat":
									echo "TAMBAH TELAAH (Staff Camat)";
									break;
								case "staff_lurah":
									echo "TAMBAH TELAAH (Staff Lurah)";
									break;
								case "walikota":
									echo "TAMBAH TELAAH (Walikota)";
									break;
								case "staff_setda":
									echo "TAMBAH TELAAH (Kasubag dan Staff Setda)";
									break;
								case "sekwan":
									echo "TAMBAH TELAAH (Sekwan)";
									break;
								case "kapus":
									echo "TAMBAH TELAAH (Kepala Puskesmas)";
									break;
							}
							?>
						</h3>
					</div>
					<!-- /.box-header -->
					<!-- form start -->
					<?php if ($this->uri->segment(5)) {
						echo form_open_multipart('telaah/list_telaah/create/' . $this->uri->segment(4) . '/' . $this->uri->segment(5), array('id' => 'form-telaah'));
					} else {
						echo form_open_multipart('telaah/list_telaah/create/' . $this->uri->segment(4), array('id' => 'form-telaah'));
					}
					?>
					<div class="table-responsive box-body">
						<?php if (validation_errors()) { ?>
							<div class="alert alert-danger">
								<h4><i class="icon fa fa-ban"></i> Peringatan!</h4>
								<?php echo validation_errors(); ?>
							</div>
						<?php } ?>
						<table class="table table-bordered ">
							<?php
							if (form_error('kop_surat')) {
								echo '<p class="alert alert-danger text-center"><b>' . form_error('kop_surat') . '</b></p>';
							}
							?>
							<tr class="info">
								<th class="col-md-3" colspan="2">
									<center>DATA PERIHAL</center>
								</th>
							</tr>
							<tr>
								<th class="col-md-3">Kepada</th>
								<td><select class="form-control" name="telaah_kepada" style="width:300px;">
										<?php if ($this->uri->segment(4) == 'esselon') { ?>
											<option value="Kepala OPD">Kepala OPD</option>
										<?php } else if ($this->uri->segment(4) == 'kadis') { ?>
											<option value="Walikota">Walikota</option>
											<option value="Kepala OPD">Kepala OPD</option>
										<?php } else if ($this->uri->segment(4) == 'dprd') { ?>
											<option value="Ketua DPRD">Ketua DPRD</option>
										<?php } else if (
											$this->uri->segment(4) == 'sekda'
											|| $this->uri->segment(4) == 'camat'
											|| $this->uri->segment(4) == 'lurah'
											|| $this->uri->segment(4) == 'walikota'
											|| $this->uri->segment(4) == 'sekwan'
										) { ?>
											<option value="Walikota">Walikota</option>
										<?php } else if ($this->uri->segment(4) == 'staff_dprd') { ?>
											<option value="Sekertaris DPRD">Sekertaris DPRD</option>
										<?php } else if ($this->uri->segment(4) == 'staff_camat') { ?>
											<option value="Camat">Camat</option>
										<?php } else if ($this->uri->segment(4) == 'staff_lurah') { ?>
											<option value="Lurah">Lurah</option>
										<?php } else if ($this->uri->segment(4) == 'staff_setda') { ?>
											<option value="Sekretaris Daerah">Sekretaris Daerah</option>
										<?php } else if ($this->uri->segment(4) == 'kapus') { ?>
											<option value="Kepala Puskesmas">Kepala Puskesmas</option>
										<?php } ?>
									</select>
								</td>
							</tr>
							<tr>
								<th class="col-md-3">Perihal (Maksud Perjalanan Dinas)</th>
								<td><?php echo form_error('telaah_perihal'); ?>
									<textarea class="form-control" name="telaah_perihal" rows="4"><?php echo set_value('telaah_perihal'); ?></textarea>
								</td>
							</tr>
							<tr>
								<th class="col-md-3">Persoalan</th>
								<td>
									<?php echo form_error('telaah_persoalan'); ?>
									<textarea class="form-control" name="telaah_persoalan" rows="4"><?php echo set_value('telaah_persoalan'); ?></textarea>
								</td>
							</tr>
							<tr>
								<th class="col-md-3">Fakta yang mempengaruhi</th>
								<td>
									<?php echo form_error('telaah_fakta'); ?>
									<textarea class="form-control" name="telaah_fakta" rows="4"><?php echo set_value('telaah_fakta'); ?></textarea>
								</td>
							</tr>
							<tr>
								<th class="col-md-3">Analisis</th>
								<td>
									<?php echo form_error('telaah_analisis'); ?>
									<textarea class="form-control" name="telaah_analisis" rows="4"><?php echo set_value('telaah_analisis'); ?></textarea>
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
												<option value="Darat" <?php if (set_value('telaah_jenisangkutan') == "Darat") {
																								echo "selected";
																							} ?>>Darat</option>
												<option value="Udara" <?php if (set_value('telaah_jenisangkutan') == "Udara") {
																								echo "selected";
																							} ?>>Udara</option>
												<option value="Air" <?php if (set_value('telaah_jenisangkutan') == "Air") {
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
												<option value="Motor" <?php if (set_value('telaah_angkutan') == "Motor") {
																								echo "selected";
																							} ?>>Motor</option>
												<option value="Mobil" <?php if (set_value('telaah_angkutan') == "Mobil") {
																								echo "selected";
																							} ?>>Mobil</option>
												<option value="Pesawat" <?php if (set_value('telaah_angkutan') == "Pesawat") {
																									echo "selected";
																								} ?>>Pesawat</option>
												<option value="Kapal" <?php if (set_value('telaah_angkutan') == "Kapal") {
																								echo "selected";
																							} ?>>Kapal</option>
												<option value="Kereta" <?php if (set_value('telaah_angkutan') == "Kereta") {
																									echo "selected";
																								} ?>>Kereta</option>
												<option value="Lainnya" <?php if (set_value('telaah_angkutan') == "Lainnya") {
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
												<input id="datepicker" type="text" class="form-control" name="telaah_tanggalberangkat" value="<?php if (set_value('telaah_tanggalberangkat')) {
																																																												echo set_value('telaah_tanggalberangkat');
																																																											} else {
																																																												echo date('d-m-Y');
																																																											}; ?>">
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
												<input id="datepicker2" type="text" class="form-control" name="telaah_tanggalkembali" value="<?php if (set_value('telaah_tanggalkembali')) {
																																																												echo set_value('telaah_tanggalkembali');
																																																											} else {
																																																												echo date('d-m-Y');
																																																											}; ?>">
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
												<input type="number" min="1" value="<?php echo set_value('telaah_hari'); ?>" class="form-control" name="telaah_hari">
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
											<input type="text" class="form-control" value="<?php echo set_value('telaah_tempatberangkat'); ?>" name="telaah_tempatberangkat">
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
												<option value="1" <?php if (set_value('telaah_domainperjalanan') == "1") {
																						echo "selected";
																					} ?>>LUAR DAERAH LUAR PROVINSI (LDLP)</option>
												<option value="2" <?php if (set_value('telaah_domainperjalanan') == "2") {
																						echo "selected";
																					} ?>>LUAR DAERAH DALAM PROVINSI (LDDP)</option>
												<option value="3" <?php if (set_value('telaah_domainperjalanan') == "3") {
																						echo "selected";
																					} ?>>DALAM DAERAH</option>
											</select>
										</div>
									</div>

									<?php
									if ($perjalanan == 1) {
										echo "<div id='ldlp' style='display:block;'>";
									} else {
										echo "<div id='ldlp' style='display:none;'>";
									}

									?>
									<div class="col-md-6">
										<div class="form-group">
											<?php
											if (form_error('telaah_provinsitujuan')) {
												echo form_error('telaah_provinsitujuan');
											} else {
												echo "<label> Provinsi</label>";
											}
											?>
											<select class="form-control" name="telaah_provinsitujuan" id="provinsi_id" selectpicker chzn-select" onChange="tampil_kabupaten()" data-live-search="true" data-live-search-style="begins">
												<option value="">- Pilih Provinsi -</option>
												<?php foreach ($provinsi as $v) {
													if (set_value('telaah_provinsitujuan') == $v->provinsi_id) {
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
											<?php
											if (form_error('telaah_kotatujuan')) {
												echo form_error('telaah_kotatujuan');
											} else {
												echo "<label> Kota Tujuan</label>";
											}
											?>
											<select class="form-control" name="telaah_kotatujuan" id="kabkot_id" selectpicker chzn-select" data-live-search="true" data-live-search-style="begins">
												<option value="">- Pilih Kabupaten/Kota -</option>
												<?php foreach ($kabupaten2 as $v) {
													if (set_value('telaah_kotatujuan') == $v->kabkot_id) {
														echo "<option value='$v->kabkot_id' selected>$v->kabupaten_kota</option>";
													} else {
														echo "<option value='$v->kabkot_id'>$v->kabupaten_kota</option>";
													}
												} ?>
											</select>
										</div>
									</div>
					</div>

					<?php
					if ($perjalanan == 2) {

						echo "<div id='lddp' style='display:block;'>";
					} else {
						echo "<div id='lddp' style='display:none;'>";
					}
					?>
					<div class="col-md-6">
						<div class="form-group">
							<?php
							if (form_error('telaah_kotatujuan2')) {
								echo form_error('telaah_kotatujuan2');
							} else {
								echo "<label> Kota Tujuan</label>";
							}
							?>
							<select class="form-control" name="telaah_kotatujuan2" data-live-search="true" data-live-search-style="begins">
								<option value="">- Pilih Kabupaten/Kota -</option>
								<?php foreach ($kabupaten as $v) {
									if (set_value('telaah_kotatujuan2') == $v->kabkot_id) {
										echo "<option value='$v->kabkot_id' selected>$v->kabupaten_kota</option>";
									} else {
										echo "<option value='$v->kabkot_id'>$v->kabupaten_kota</option>";
									}
								} ?>
							</select>
						</div>
					</div>
				</div>
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
						<input type="text" class="form-control" name="telaah_kantortujuan" value="<?php echo set_value('telaah_kantortujuan'); ?>">
					</div>
				</div><br>
				<div class="col-md-12">
				</div>
				<div class="col-md-12">
					<button type="button" class="btn btn-success btn-sm" id="btn-tambah-form">Tambah Tujuan</button>
					<button type="button" class="btn btn-danger btn-sm" id="btn-reset-form">Reset Tujuan</button><br><br>
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
						<select class="form-control select2" name="telaah_kegiatan" id="anggaran" onChange="tampil_anggaran()">
							<option value="">- Pilih -</option>
							<?php
							foreach ($anggaran as $v) {
								if (set_value('telaah_kegiatan') == $v->id_anggaran) {
									echo '<option value="' . $v->id_anggaran . '" selected>' . $v->nama_program . ' || ' . $v->nama_kegiatan . '</option>';
								} else {
									echo '<option value="' . $v->id_anggaran . '">' . $v->nama_program . ' || ' . $v->nama_kegiatan . '</option>';
								}
							}
							?>
						</select>
						<div id="jumlah_anggaran">
							<?php if (set_value('telaah_kegiatan')) {
								echo "<br>";
								$pagu = $this->m_anggaran->get(set_value('telaah_kegiatan'));
								$rincian_biaya =  $this->m_anggaran->cek_sisa_anggaran_skpd(set_value('telaah_kegiatan'));
								$pengeluaran_rill =  $this->m_anggaran->cek_pengeluaran_rill_skpd(set_value('telaah_kegiatan'));
								$sisa = $pagu[0]['pagu'] - ($rincian_biaya[0]->jumlah + $pengeluaran_rill[0]->jumlah);
								$rincian = $rincian_biaya[0]->jumlah + $pengeluaran_rill[0]->jumlah;
								$total = round(($rincian / $pagu[0]['pagu']) * 100, 2);

								if ($total >= 0 && $total <= 50) {
									echo "	<table class='table table-bordered table-striped'>
											<tr>
												<th class='col-md-2'>Total Anggaran</th>
												<th class='col-md-10'><button type='button' class='btn btn-sm btn-default'><b> Rp. " . number_format($pagu[0]['pagu'], 0, ',', '.') . " </button></th>
											</tr>
											<tr>
												<th class='col-md-2'>Realisasi Anggaran</th>
												<th class='col-md-4'>
												<button type='button' class='btn btn-sm btn-default'><b> Rp. " . number_format($rincian, 0, ',', '.') . " </b></button>
													&nbsp;&nbsp; Persentase : <button type='button' class='btn btn-sm btn-success'><b>$total %</b></button>
												</th>
											</tr>
											<tr>
												<th class='col-md-2'>Anggaran Tersedia</th>
												<th class='col-md-10'><button  type='button' class='btn btn-sm btn-default'><b> Rp. " . number_format($sisa, 0, ',', '.') . "<b></button></th>
											</tr>
										</table>";
								} else if ($total > 50 && $total <= 75) {
									echo "	<table class='table table-bordered table-striped'>
											<tr>
												<th class='col-md-2'>Total Anggaran</th>
												<th class='col-md-10'><button type='button' class='btn btn-sm btn-default'><b> Rp. " . number_format($pagu[0]['pagu'], 0, ',', '.') . " </button></th>
											</tr>
											<tr>
												<th class='col-md-2'>Realisasi Anggaran</th>
												<th class='col-md-4'>
												<button type='button' class='btn btn-sm btn-default'><b> Rp. " . number_format($rincian, 0, ',', '.') . " </b></button>
													&nbsp;&nbsp; Persentase : <button type='button' class='btn btn-sm btn-warning'><b>$total %</b></button>
												</th>
											</tr>
											<tr>
												<th class='col-md-2'>Anggaran Tersedia</th>
												<th class='col-md-10'><button  type='button' class='btn btn-sm btn-default'><b> Rp. " . number_format($sisa, 0, ',', '.') . "<b></button></th>
											</tr>
										</table>";
								} else if ($total > 75 && $total <= 100) {
									echo "	<table class='table table-bordered table-striped'>
											<tr>
												<th class='col-md-2'>Total Anggaran</th>
												<th class='col-md-10'><button type='button' class='btn btn-sm btn-default'><b> Rp. " . number_format($pagu[0]['pagu'], 0, ',', '.') . " </button></th>
											</tr>
											<tr>
												<th class='col-md-2'>Realisasi Anggaran</th>
												<th class='col-md-4'>
												<button type='button' class='btn btn-sm btn-default'><b> Rp. " . number_format($rincian, 0, ',', '.') . " </b></button>
													&nbsp;&nbsp; Persentase : <button type='button' class='btn btn-sm btn-danger'><b>$total %</b></button>
												</th>
											</tr>
											<tr>
												<th class='col-md-2'>Anggaran Tersedia</th>
												<th class='col-md-10'><button  type='button' class='btn btn-sm btn-default'><b> Rp. " . number_format($sisa, 0, ',', '.') . "<b></button></th>
											</tr>
										</table>";
								} else if ($total > 100) {
									echo "	<table class='table table-bordered table-striped'>
											<tr>
												<th class='col-md-2'>Total Anggaran</th>
												<th class='col-md-10'><button type='button' class='btn btn-sm btn-default'><b> Rp. " . number_format($pagu[0]['pagu'], 0, ',', '.') . " </button></th>
											</tr>
											<tr>
												<th class='col-md-2'>Realisasi Anggaran</th>
												<th class='col-md-4'>
												<button type='button' class='btn btn-sm btn-default'><b> Rp. " . number_format($rincian, 0, ',', '.') . " </b></button>
													&nbsp;&nbsp; Persentase : <button type='button' class='btn btn-sm btn-danger'><b>$total %</b></button>
												</th>
											</tr>
											<tr>
												<th class='col-md-2'>Anggaran Yang Tersedia</th>
												<th class='col-md-10'><button  type='button' class='btn btn-sm btn-default'><b> Rp. " . number_format($sisa, 0, ',', '.') . "<b></button></th>
											</tr>
										</table>";
								}
							} ?>
						</div>
					</td>
				</tr>
				<tr>
					<th class="col-md-3">Kategori Perjalanan</th>
					<td>
						<?php echo form_error('telaah_kategoriperjalanan'); ?>
						<select class="form-control" name="telaah_kategoriperjalanan">
							<option value="">- Pilih -</option>
							<option value="1" <?php if (set_value('telaah_kategoriperjalanan') == "1") {
																	echo "selected";
																} ?>>Undangan</option>
							<option value="2" <?php if (set_value('telaah_kategoriperjalanan') == "2") {
																	echo "selected";
																} ?>>Konsultasi/Koordinasi</option>
							<option value="3" <?php if (set_value('telaah_kategoriperjalanan') == "3") {
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
							<option value="0" <?php if (set_value('telaah_kecepatan') == "0") {
																	echo "selected";
																} ?>>Biasa</option>
							<option value="1" <?php if (set_value('telaah_kecepatan') == "1") {
																	echo "selected";
																} ?>>Segera</option>
						</select>
					</td>
				</tr>
				<tr>
					<th class="col-md-3">Dokumen Pendukung</th>
					<td>
						<?php echo form_error('userfile'); ?>
						<input type="file" class="form-control" name="userfile" value="<?php echo set_value('userfile'); ?>">
					</td>
				</tr>
				<tr class="info">
					<th class="col-md-3" colspan="2">
						<center>DATA PELAKSANA DAN PENGIKUT</center>
					</th>
				</tr>
				<?php if ($this->ion_auth->user()->row()->skpd_id == 182) { ?>
					<tr>
						<th class="col-md-3">No. Surat Tugas</th>
						<td>
							<?php echo form_error('telaah_no_surat_tugas'); ?>
							<input type="text" class="form-control" name="telaah_no_surat_tugas" value="<?php if (set_value('telaah_no_surat_tugas')) {
																																														echo set_value('telaah_no_surat_tugas');
																																													} else {
																																														echo "090 /        / ST / INSP./ " . date('Y');
																																													}; ?>">
						</td>
					</tr>
				<?php } ?>
				<tr>
					<th class="col-md-3">Pelaksana Perjalanan Dinas</th>
					<td><b>PERINGATAN : UNTUK MELAKUKAN PERJALANAN DINAS YANG BARU PASTIKAN ANDA TELAH MENGISI LAPORAN PERJALANAN DINAS SEBELUMNYA. JIKA BELUM MENGISI LAPORAN PERJALANAN DINAS, SPPD INI TIDAK DAPAT DI PROSES OLEH SISTEM.</b>
						<br><?php echo form_error('telaah_pelaksana'); ?>

						<?php if ($this->uri->segment(4) == 'kapus') { ?>
							<?php if ($this->uri->segment(5)) { ?>
								<select class="form-control select2" disabled>
									<option value="">- Pilih -</option>
									<?php foreach ($pegawai as $v) { ?>
										<option value="<?php echo $v->pegawai_id ?>"
											<?php
											$status_pelaksana = explode("-", $this->uri->segment(5));
											if ($status_pelaksana[0] == $v->pegawai_id) {
												echo "selected";
											}

											?>><?php echo $v->pegawai_nip ?> || <?php echo $v->pegawai_nama ?></option>
									<?php } ?>
								<?php } else { ?>
									<select class="form-control select2" name="telaah_pelaksana" onchange="showPelaksana(this.value)">
										<option value="">- Pilih -</option>
										<?php
										foreach ($pegawai as $v) { ?>
											<option value="<?php echo $v->pegawai_id; ?>"
												<?php
												$status_pelaksana = explode("-", set_value('telaah_pelaksana'));
												if ($status_pelaksana[0] == $v->pegawai_id) {
													echo "selected";
												}

												?>><?php echo $v->pegawai_nip ?> || <?php echo $v->pegawai_nama ?></option>
										<?php } ?>
									<?php } ?>
									</select>
									<input type="hidden" value="<?php echo $this->uri->segment(5); ?>" name="sppd_lanjutan">
									<?php if ($this->uri->segment(5)) { ?><input type="hidden" value="<?php echo $this->uri->segment(5); ?>" name="telaah_pelaksana"><?php } ?>
								<?php } else { ?>
									<?php if ($this->uri->segment(5)) { ?>
										<!-- DPRD -->
										<?php $selected_id = explode('-', $this->uri->segment(5))[0] ?? ''; ?>

										<select class="form-control select2" disabled id="pelaksana-select">
											<option value="">- Pilih -</option>

											<?php foreach ($pelaksana as $v): ?>
												<option value="<?= $v->pegawai_id ?>" <?= $selected_id == $v->pegawai_id ? 'selected' : '' ?>>
													<?= !empty($v->pegawai_nip) ? (int) $v->pegawai_nip . ' || ' : '' ?> <?= $v->pegawai_nama ?>
												</option>
											<?php endforeach ?>

										</select>
									<?php } else { ?>
										<select class="form-control select2" name="telaah_pelaksana" onchange="showPelaksana(this.value)">
											<option value="">- Pilih -</option>

											<?php foreach ($pelaksana as $v): ?>
												<?php $status_pelaksana = explode("-", set_value('telaah_pelaksana')) ?>

												<option value="<?= $v->pegawai_id ?>"
													<?= ($status_pelaksana[0] == $v->pegawai_id) ? 'selected' : '' ?>>
													<?= !empty($v->pegawai_nip) ? (int) $v->pegawai_nip . ' || ' : '' ?> <?= $v->pegawai_nama ?>
												</option>
											<?php endforeach; ?>

										</select>
									<?php } ?>
									<input type="hidden" value="<?php echo $this->uri->segment(5); ?>" name="sppd_lanjutan">
									<?php if ($this->uri->segment(5)) { ?><input type="hidden" value="<?php echo $this->uri->segment(5); ?>" name="telaah_pelaksana"><?php } ?>
								<?php } ?>
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
					<td><?php
							if ($pengikut_on > 0) {
								for ($i = 0; $i <= $pengikut_on; $i++) {
									echo form_error('telaah_pengikut' . $i);
									echo "<br>";
								}
							} ?>
						<select id="select-meal-type" class="form-control <?= ($this->uri->segment(4) == 'walikota') ? 'select2-ajax' : 'select2'; ?>" name="telaah_pengikut[]" multiple="multiple" onchange="showPengikut(this.value)">
							<option value="">- Pilih -</option>

							<?php foreach ($pengikut as $v): ?>
								<option value="<?= $v->pegawai_id ?>">
									<?= !empty($v->pegawai_nip) ? $v->pegawai_nip . ' || ' : '' ?> <?= $v->pegawai_nama ?>
								</option>
							<?php endforeach; ?>

						</select>
					</td>
				</tr>
				<tr class="info">
					<th class="col-md-3" colspan="2">
						<center>TANGGAL SPPD DAN SPT</center>
					</th>
				</tr>
				<tr>
					<th class="col-md-3">Tanggal SPPD</th>
					<td>
						<?php echo form_error('telaah_tanggalspd'); ?>
						<input type="text" class="form-control" name="telaah_tanggalspd" id="datepicker3" value="<?php if (set_value('telaah_tanggalspd')) {
																																																				echo set_value('telaah_tanggalspd');
																																																			} else {
																																																				echo date('d-m-Y');
																																																			}; ?>">
					</td>
				</tr>
				<tr>
					<th class="col-md-3">Tanggal SPT</th>
					<td>
						<?php echo form_error('telaah_tanggalspt'); ?>
						<input type="text" class="form-control" name="telaah_tanggalspt" id="datepicker4" value="<?php if (set_value('telaah_tanggalspt')) {
																																																				echo set_value('telaah_tanggalspt');
																																																			} else {
																																																				echo date('d-m-Y');
																																																			}; ?>">
					</td>
				</tr>
				</table>
			</div>
			<!-- /.box-body -->
			<div class="box-footer">
				<div class="col-md-6">
					<a class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModalCekAnggaran"><i class="fa fa-save"></i> Buat Dokumen Perjalanan</a>
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

<div class="modal fade" id="myModalCekAnggaran">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">KONFIRMASI</h4>
			</div>
			<div class="modal-body">
				<p style='font-size:20px;text-align:center'>
					Apakah Anda Yakin Akan Melakukan Perjalanan Dinas?
				</p>
				<table class='table table-bordered table-striped'>
					<tr class='info'>
						<th colspan=2>
							<center>PELAKSANA PERJALANAN</center>
						</th>
					</tr>
					<tr>
						<th class='col-md-3'>PELAKSANA</th>
						<td id="pelaksana">: </td>
					<tr>
					<tr>
						<th class='col-md-3'>PENGIKUT</th>
						<td id="pengikut">: </td>
					<tr>
				</table>
				<div id="ModalKonfirmasi">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
				<button type="button" id="btn-submit-confirm" class="btn btn-success">Ya</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>

<script>
	// var ckeditor = CKEDITOR.replace('ckeditor');
</script>

<script>
	$(document).ready(function() {

		$('#myModalCekAnggaran').on('show.bs.modal', function() {
			var text = $('#pelaksana-select option:selected').text().trim();

			if (!text || text === '- Pilih -') {
				$('#pelaksana').html(': -');
				return;
			}

			// Cek apakah ada "||"
			if (text.indexOf('||') !== -1) {
				var parts = text.split('||');
				text = parts[parts.length - 1].trim();
			}

			$('#pelaksana').html(': ' + text);
		});

	});
</script>

<script type="text/javascript">
	$(function() {
		$("#btn-submit-confirm").click(function() {
			$('#form-telaah').submit();
		});

		$('#datepicker').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true
		});
	});
	$(function() {
		$('#datepicker2').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true
		});
	});
	$(function() {
		$('#datepicker3').datepicker({
			format: 'dd-mm-yyyy',
			autoclose: true
		});
	});
	$(function() {
		$('#datepicker4').datepicker({
			format: 'dd-mm-yyyy',
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
	$(document).ready(function() {
		<?php if ($this->uri->segment(4) == 'walikota'): ?>
			$('.select2-ajax').select2({
				ajax: {
					url: '<?php echo base_url("telaah/list_telaah/search_pegawai_ajax"); ?>',
					dataType: 'json',
					delay: 250,
					data: function(params) {
						return {
							q: params.term // search term
						};
					},
					processResults: function(data) {
						return {
							results: data.items
						};
					},
					cache: true
				},
				placeholder: '- Cari Pegawai (Ketik Nama/NIP) -',
				minimumInputLength: 1
			});

			// Bind change event to call showPengikut because onchange attribute might not trigger with Select2
			$('.select2-ajax').on('change', function() {
				showPengikut($(this).val());
			});
		<?php endif; ?>
	});
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
		// Try to get pelaksana from select
		var pelaksana = $('select[name="telaah_pelaksana"]').val();
		if (!pelaksana) pelaksana = 0;

		$.ajax({
			url: "<?php echo base_url(); ?>beranda/get_anggaran/1/" + anggaran + "",
			success: function(response) {
				$("#jumlah_anggaran").html(response);
			}
		});
		$.ajax({
			url: "<?php echo base_url(); ?>beranda/get_anggaran/2/" + anggaran + "/" + pelaksana,
			success: function(response) {
				$("#ModalKonfirmasi").html(response);
			}
		});
		return false;
	}
</script>

<script>
	function showPelaksana(str) {
		var xhttp;
		if (str == "") {
			document.getElementById("pelaksana").innerHTML = "";
			return;
		}
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("pelaksana").innerHTML = this.responseText;
			}
		};
		<?php if ($this->uri->segment(4) == "walikota") { ?>
			xhttp.open("GET", "<?php echo base_url(); ?>setting_admin/pegawai/get_pelaksana/walikota/" + str, true);
		<?php } else if ($this->uri->segment(4) == "dprd") { ?>
			xhttp.open("GET", "<?php echo base_url(); ?>setting_admin/pegawai/get_pelaksana/dprd/" + str, true);
		<?php } else { ?>
			xhttp.open("GET", "<?php echo base_url(); ?>setting_admin/pegawai/get_pelaksana/pegawai/" + str, true);
		<?php } ?>
		xhttp.send();
	}
</script>

<script>
	function showPengikut(str) {
		var multipleValues = $("#select-meal-type").val() || [];
		$.ajax({
			<?php if ($this->uri->segment(4) == "dprd") { ?>
				url: "<?php echo base_url(); ?>setting_admin/pegawai/get_pengikut/dprd/" + multipleValues.join("-") + "",
			<?php } else { ?>
				url: "<?php echo base_url(); ?>setting_admin/pegawai/get_pengikut/pegawai/" + multipleValues.join("-") + "",
			<?php } ?>

			success: function(response) {
				$("#pengikut").html(response);
			}
		});
	}
</script>