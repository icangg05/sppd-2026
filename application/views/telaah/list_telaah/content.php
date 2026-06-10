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
  						<h3 class="box-title">
  							<?php
								switch ($this->uri->segment(4)) {
									case "esselon":
										echo "TELAAH STAF (Esselon III, IV dan Staff)";
										break;
									case "puskesmas":
										echo "TELAAH STAF (Puskesmas BOK)";
										break;
									case "kadis":
										echo "TELAAH STAF (Kepala OPD)";
										break;
									case "dprd":
										echo "TELAAH STAF (DPRD)";
										break;
									case "sekda":
										echo "TELAAH STAF (Sekda, Asisten dan Kabag)";
										break;
									case "camat":
										echo "TELAAH STAF (Camat)";
										break;
									case "lurah":
										echo "TELAAH STAF (Lurah)";
										break;
									case "staff_dprd":
										echo "TELAAH STAF (Staff DPRD)";
										break;
									case "staff_camat":
										echo "TELAAH STAF (Staff Camat)";
										break;
									case "staff_lurah":
										echo "TELAAH STAF (Staff Lurah)";
										break;
									case "walikota":
										echo "TELAAH STAF (Walikota)";
										break;
									case "staff_setda":
										echo "TELAAH STAF (Kasubag dan Staff Setda)";
										break;
									case "sekwan":
										echo "TELAAH STAF (Sekwan)";
										break;
									case "kapus":
										echo "TELAAH STAF (Kepala Puskesmas)";
										break;
								}
								?>
  						</h3>
  					</div>
  					<div class="box-header with-border">
  						<?php echo form_open("telaah/list_telaah/search/" . $this->uri->segment(4)); ?>
  						<div class="col-md-9">
  							<?php if (($this->ion_auth->user()->row()->id != 1) && ($this->ion_auth->get_users_groups()->row()->id != 100) && ($this->ion_auth->get_users_groups()->row()->id != 9)) { ?>
  								<?php if ($this->uri->segment(4) !== 'puskesmas') { ?>
  									<a href="<?php echo base_url(); ?>telaah/list_telaah/create_view/<?php echo $this->uri->segment(4); ?>" class="btn btn-success btn-flat">Tambah Data</a>
  								<?php } ?>
  								<a href="<?php echo base_url(); ?>telaah/list_telaah/index/<?php echo $this->uri->segment(4); ?>" class="btn btn-warning btn-flat">Refresh</a>

  								<?php if ($this->uri->segment(4) == 'dprd') { ?>
  									<a href="<?php echo base_url(); ?>telaah/list_telaah/data_perihal_dprd" class="btn btn-info btn-flat">Cari Perihal</a>
  								<?php } else if ($this->uri->segment(4) == 'staff_dprd') { ?>
  									<a href="<?php echo base_url(); ?>telaah/list_telaah/data_perihal_staff_dprd" class="btn btn-info btn-flat">Cari Perihal</a>
  								<?php } ?>

  							<?php } ?>
  						</div>
  						<div class="col-md-3">
  							<div class="input-group">
  								<input type="text" class="form-control" name="data" placeholder="Pelaksana ...">
  								<span class="input-group-btn">
  									<input type="submit" name="submit" class="btn btn-info btn-flat" value="Go">
  								</span>
  							</div>
  						</div>
  						<?php echo form_close(); ?>
  					</div>
  					<!-- /.box-header -->
  					<div class="table-responsive box-body">
  						<?php
							$message = $this->session->flashdata('notif');
							if ($message) {
								echo '<p class="alert alert-info text-center"><b>' . $message . '</b></p>';
							}
							?>
  						<p>
  							<center>
  								<a href="<?php echo base_url(); ?>telaah/list_telaah/index/<?php echo $this->uri->segment(4); ?>" class="btn btn-flat <?php if ($this->uri->segment(3) == "index") {
																																																																					echo "btn-info";
																																																																				} else {
																																																																					echo "btn-default";
																																																																				} ?>">ALL</a>
  								<a href="<?php echo base_url(); ?>telaah/list_telaah/result/<?php echo $this->uri->segment(4); ?>/0" class="btn btn-flat <?php if ($this->uri->segment(3) == "result" && $this->uri->segment(5) == 0) {
																																																																							echo "btn-primary";
																																																																						} else {
																																																																							echo "btn-default";
																																																																						} ?>">MASUK</a>
  								<a href="<?php echo base_url(); ?>telaah/list_telaah/result/<?php echo $this->uri->segment(4); ?>/1" class="btn btn-flat <?php if ($this->uri->segment(3) == "result" && $this->uri->segment(5) == 1) {
																																																																							echo "btn-warning";
																																																																						} else {
																																																																							echo "btn-default";
																																																																						} ?>">DI PROSES</a>
  								<a href="<?php echo base_url(); ?>telaah/list_telaah/result/<?php echo $this->uri->segment(4); ?>/2" class="btn btn-flat <?php if ($this->uri->segment(3) == "result" && $this->uri->segment(5) == 2) {
																																																																							echo "btn-success";
																																																																						} else {
																																																																							echo "btn-default";
																																																																						} ?>">SELESAI</a>
  								<a href="<?php echo base_url(); ?>telaah/list_telaah/result/<?php echo $this->uri->segment(4); ?>/5" class="btn btn-flat <?php if ($this->uri->segment(3) == "result" && $this->uri->segment(5) == 5) {
																																																																							echo "btn-info";
																																																																						} else {
																																																																							echo "btn-default";
																																																																						} ?>">PERBAIKAN</a>
  								<a href="<?php echo base_url(); ?>telaah/list_telaah/result/<?php echo $this->uri->segment(4); ?>/3" class="btn btn-flat <?php if ($this->uri->segment(3) == "result" && $this->uri->segment(5) == 3) {
																																																																							echo "btn-danger";
																																																																						} else {
																																																																							echo "btn-default";
																																																																						} ?>">TIDAK DITERIMA</a><br><br>
  								<a href="<?php echo base_url(); ?>telaah/list_telaah/sudah_upload_laporan/<?php echo $this->uri->segment(4); ?>" class="btn btn-flat <?php if ($this->uri->segment(3) == "sudah_upload_laporan") {
																																																																													echo "btn-success";
																																																																												} else {
																																																																													echo "btn-default";
																																																																												} ?>">SUDAH UPLOAD LAPORAN</a>
  								<a href="<?php echo base_url(); ?>telaah/list_telaah/belum_upload_laporan/<?php echo $this->uri->segment(4); ?>" class="btn btn-flat <?php if ($this->uri->segment(3) == "belum_upload_laporan") {
																																																																													echo "btn-warning";
																																																																												} else {
																																																																													echo "btn-default";
																																																																												} ?>">BELUM UPLOAD LAPORAN</a>
  							</center>
  						</p>
  						<div class="table-responsive box-body">
  							<?php if ($this->uri->segment(3) == 'sudah_upload_laporan') { ?>
  								<a href="<?php echo base_url(); ?>telaah/list_telaah/cetak_sudah_upload_laporan/<?php echo $this->uri->segment(4); ?>" class="btn btn-flat btn-primary">CETAK LAPORAN</a><br><br>
  							<?php } else if ($this->uri->segment(3) == 'belum_upload_laporan') { ?>
  								<a href="<?php echo base_url(); ?>telaah/list_telaah/cetak_belum_upload_laporan/<?php echo $this->uri->segment(4); ?>" class="btn btn-flat btn-primary">CETAK LAPORAN</a><br><br>
  							<?php } ?>
  							<table class="table table-bordered table-striped table-hover">
  								<tr class='info'>
  									<th style="width: 5px">No</th>
  									<th style="width: 40px">Tanggal Pengajuan</th>
  									<th style="width: 150px">Perihal (Maksud Perjalanan Dinas)</th>
  									<th style="width: 200px">Pelaksana</th>
  									<th style="width: 40px">Status</th>
  									<?php
										switch ($this->uri->segment(4)) {
											case "puskesmas":
												echo "<th style='width: 40px'>OPD</th>";
												break;
											case "dprd":
												echo "<th style='width: 40px'>Kategori</th>";
												break;
											case "staff_lurah":
												echo "<th style='width: 40px'>Kategori</th>";
												break;
											case "staff_camat":
												echo "<th style='width: 40px'>Kategori</th>";
												break;
											case "staff_setda":
												echo "<th style='width: 40px'>Sub Bagian</th>";
												break;
										}
										?>
  									<th style="width: 100px">Lihat Telaah</th>
  									<th style="width: 100px">Cetak</th>
  								</tr>
  								<?php
									$number = $number + 1;
									foreach ($data as $v) {
										$telaah_id = base64_encode($this->encrypt->encode($v->telaah_id, $this->session->userdata('encrypt_key')));
									?>
  									<tr>
  										<td><?php echo $number; ?></td>
  										<td>
  											<?php
												$date = substr($v->telaah_waktuinput, 0, 10);
												$time = substr($v->telaah_waktuinput, 11, 19);
												$telaah_waktuinput =  $this->waktu->date_indo($date);
												echo $telaah_waktuinput . ' ' . $time;
												?>
  										</td>
  										<td><?php echo $v->telaah_perihal ?></td>
  										<?php if ($this->uri->segment(4) == "dprd") { ?>
  											<td><?php echo $v->anggotadprd_name ?></td>
  										<?php } else { ?>
  											<td><?php echo $v->pegawai_nama ?></td>
  										<?php } ?>
  										<td>
  											<?php
												if ($v->telaah_status == 0) {
													echo '<span class="label label-default">Belum Diterima</span>';
												} else if ($v->telaah_status == 1) {
													if ($v->telaah_perbaikan == 1) {
														echo "<span class='label label-info'>Diperbaiki dan Sedang Diproses</span>";
													} else {
														echo '<span class="label label-warning">Dalam Proses Permohonan SPPD</span>';
													}
												} else if ($v->telaah_status == 2) {
													if ($v->telaah_tanggalkembali > date('Y-m-d')) {
														echo '<span class="label" style="background-color: #75c940;">SPPD Disetujui</span>';
													} else {
														echo '<span class="label label-success">Perjalananan Selesai dan Masukkan Laporan</span>';
													}
													echo '<br><br>';

													$rincian = $this->m_telaah->count_rincian_biaya($v->telaah_id);
													$pengeluaran_rill = $this->m_telaah->count_pengeluaran_rill($v->telaah_id);
													if ($rincian == 0 && $pengeluaran_rill == 0) {
														echo "<span class='label label-default'>Belum Realisasi</span>";
													} else {
														echo "<span class='label label-warning'>Sudah Realisasi</span>";
													}
													echo '<br>';
													$laporan_perjalanan = $this->m_telaah->count_laporan_perjalanan($v->telaah_id);
													if ($laporan_perjalanan == 0) {
														echo "<span class='label label-default'>Belum Upload laporan</span>";
													} else {
														echo "<span class='label label-warning'>Sudah Upload laporan</span>";
													}
												} else if ($v->telaah_status == 3) {
													echo '<span class="label label-danger">Tidak Diterima</span>';
												} else if ($v->telaah_status == 5) {
													echo '<span class="label label-primary">Perbaikan</span><br><br><b>Pesan :</b><br>';
													// Get TimeLine
													switch ($v->telaah_kategori) {
														case "1":
															$timeline =  $this->m_telaah->getTimeline1($v->telaah_id);
															if ($v->telaah_sekretariat == 1) {
																if ($timeline[0]['timeline_sekdis_id'] == 5) {
																	echo $timeline[0]['timeline_sekdis_disposisi'];
																}
																if ($timeline[0]['timeline_kadis_id'] == 5) {
																	echo $timeline[0]['timeline_kadis_disposisi'];
																}
															} else {
																if ($timeline[0]['timeline_kabid_id'] == 5) {
																	echo $timeline[0]['timeline_kabid_disposisi'];
																}
																if ($timeline[0]['timeline_sekdis_id'] == 5) {
																	echo $timeline[0]['timeline_sekdis_disposisi'];
																}
																if ($timeline[0]['timeline_kadis_id'] == 5) {
																	echo $timeline[0]['timeline_kadis_disposisi'];
																}
															}
															break;
														case "2":
															$timeline =  $this->m_telaah->getTimeline2($v->telaah_id);
															if ($v->telaah_domainperjalanan == 3 || $v->telaah_domainperjalanan == 4) {
																if ($timeline[0]['timeline_sekdis_id'] == 5) {
																	echo $timeline[0]['timeline_sekdis_disposisi'];
																}
																if ($timeline[0]['timeline_kadis_id'] == 5) {
																	echo $timeline[0]['timeline_kadis_disposisi'];
																}
															} else {
																if ($timeline[0]['timeline_sekdis_id'] == 5) {
																	echo $timeline[0]['timeline_sekdis_disposisi'];
																}
																if ($timeline[0]['timeline_kadis_id'] == 5) {
																	echo $timeline[0]['timeline_kadis_disposisi'];
																}
																if ($timeline[0]['timeline_sekda_id'] == 5) {
																	echo $timeline[0]['timeline_sekda_disposisi'];
																}
																if ($timeline[0]['timeline_walikota_id'] == 5) {
																	echo $timeline[0]['timeline_walikota_disposisi'];
																}
															}
															break;
														case "3":
															$timeline =  $this->m_telaah->getTimeline3($v->telaah_id);
															if ($timeline[0]['timeline_kasubid_id'] == 5) {
																echo $timeline[0]['timeline_kasubid_disposisi'];
															}
															if ($timeline[0]['timeline_sekwan_id'] == 5) {
																echo $timeline[0]['timeline_sekwan_disposisi'];
															}
															if ($timeline[0]['timeline_kadprd_id'] == 5) {
																echo $timeline[0]['timeline_kadprd_disposisi'];
															}
															break;
														case "4":
															$timeline =  $this->m_telaah->getTimeline4($v->telaah_id);
															if ($timeline[0]['timeline_kabag_id'] == 5) {
																echo $timeline[0]['timeline_kabag_disposisi'];
															}
															if ($timeline[0]['timeline_asisten_id'] == 5) {
																echo $timeline[0]['timeline_asisten_disposisi'];
															}
															if ($timeline[0]['timeline_sekda_id'] == 5) {
																echo $timeline[0]['timeline_sekda_disposisi'];
															}
															if ($timeline[0]['timeline_walikota_id'] == 5) {
																echo $timeline[0]['timeline_walikota_disposisi'];
															}
															break;
														case "5":
															$timeline =  $this->m_telaah->getTimeline5($v->telaah_id);
															if ($timeline[0]['timeline_sekcam_id'] == 5) {
																echo $timeline[0]['timeline_sekcam_disposisi'];
															}
															if ($timeline[0]['timeline_camat_id'] == 5) {
																echo $timeline[0]['timeline_camat_disposisi'];
															}
															if ($timeline[0]['timeline_sekda_id'] == 5) {
																echo $timeline[0]['timeline_sekda_disposisi'];
															}
															if ($timeline[0]['timeline_walikota_id'] == 5) {
																echo $timeline[0]['timeline_walikota_disposisi'];
															}
															break;
														case "6":
															$timeline =  $this->m_telaah->getTimeline6($v->telaah_id);
															if ($timeline[0]['timeline_kabag_id'] == 5) {
																echo $timeline[0]['timeline_kabag_disposisi'];
															}
															if ($timeline[0]['timeline_sekwan_id'] == 5) {
																echo $timeline[0]['timeline_sekwan_disposisi'];
															}
															break;
														case "7":
															$timeline =  $this->m_telaah->getTimeline7($v->telaah_id);
															if ($timeline[0]['timeline_lurah_id'] == 5) {
																echo $timeline[0]['timeline_lurah_disposisi'];
															}
															if ($timeline[0]['timeline_sekcam_id'] == 5) {
																echo $timeline[0]['timeline_sekcam_disposisi'];
															}
															if ($timeline[0]['timeline_camat_id'] == 5) {
																echo $timeline[0]['timeline_camat_disposisi'];
															}
															break;
														case "8":
															$timeline =  $this->m_telaah->getTimeline8($v->telaah_id);
															if ($timeline[0]['timeline_kabag_id'] == 5) {
																echo $timeline[0]['timeline_kabag_disposisi'];
															}
															if ($timeline[0]['timeline_sekda_id'] == 5) {
																echo $timeline[0]['timeline_sekda_disposisi'];
															}
															if ($timeline[0]['timeline_walikota_id'] == 5) {
																echo $timeline[0]['timeline_walikota_disposisi'];
															}
															break;
														case "9":
															$timeline =  $this->m_telaah->getTimeline9($v->telaah_id);
															if ($timeline[0]['timeline_kabag_id'] == 5) {
																echo $timeline[0]['timeline_kabag_disposisi'];
															}
															if ($timeline[0]['timeline_asisten_id'] == 5) {
																echo $timeline[0]['timeline_asisten_disposisi'];
															}
															if ($timeline[0]['timeline_sekda_id'] == 5) {
																echo $timeline[0]['timeline_sekda_disposisi'];
															}
															break;
														case "10":
															$timeline =  $this->m_telaah->getTimeline10($v->telaah_id);
															if ($timeline[0]['timeline_kabag_id'] == 5) {
																echo $timeline[0]['timeline_kabag_disposisi'];
															}
															if ($timeline[0]['timeline_sekwan_id'] == 5) {
																echo $timeline[0]['timeline_sekwan_disposisi'];
															}
															if ($timeline[0]['timeline_sekda_id'] == 5) {
																echo $timeline[0]['timeline_sekda_disposisi'];
															}
															if ($timeline[0]['timeline_walikota_id'] == 5) {
																echo $timeline[0]['timeline_walikota_disposisi'];
															}
															break;
														case "11":
															$timeline =  $this->m_telaah->getTimeline11($v->telaah_id);
															$this->data['disposisi1'] = $timeline[0]['timeline_kapus_id'];
															$this->data['isi1'] = $timeline[0]['timeline_kapus_disposisi'];
															if ($timeline[0]['timeline_kapus_id'] == 5) {
																echo $timeline[0]['timeline_kapus_disposisi'];
															}
															break;
													}
												}
												?>
  										</td>

  										<?php
											switch ($this->uri->segment(4)) {
												case "puskesmas":
													echo "<td><span class='label label-danger'>" . $v->skpd_nama . "</span></td>";
													break;
												case "dprd":
													echo "<td><span class='label label-warning'>Anggota DPRD</span></td>";
													break;
												case "staff_lurah":
													echo "<td><span class='label label-warning'>Staff Kelurahan</span></td>";
													break;
												case "staff_camat":
													echo "<td><span class='label label-warning'>Staff Kecamatan</span></td>";
													break;
												case "staff_setda":
													echo "<td>" . $v->nama_subbagian . "</td>";
													break;
											}
											?>

  										<td><a href="<?php echo base_url(); ?>telaah/list_telaah/detail/<?php echo $this->uri->segment(4) ?>?telaah_id=<?php echo $telaah_id ?>" class="btn btn-sm btn-block btn-primary"><i class="fa fa-eye"></i> Lihat</a></td>

  										<td>
  											<?php if ($v->telaah_status == 1 || $v->telaah_status == 2) { ?>
  												<a href="<?php echo base_url(); ?>telaah/list_telaah/laporan/<?php echo $this->uri->segment(4) ?>?telaah_id=<?php echo $telaah_id ?>" class="btn btn-sm btn-block btn-warning"><i class="fa fa-arrow-circle-right"></i> Selanjutnya</a>
  											<?php } else { ?>
  												<a href="#" class="btn btn-sm btn-block btn-default"><i class="fa fa-arrow-circle-right"></i> Selanjutnya</a>
  											<?php } ?>

  											<?php if ($v->telaah_kecepatan == 1) {
												?>
  												<a href="<?php echo base_url(); ?>telaah/list_telaah/cek_timeline/<?php echo $this->uri->segment(4) ?>?telaah_id=<?php echo $telaah_id ?>" class="btn btn-sm btn-block btn-success"><i class="fa fa-clock-o"></i> Cek Timeline</a>
  											<?php } ?>

  											<?php if ($v->telaah_status == 2 && $v->telaah_tanggalberangkat) { ?>
  												<a href="<?php echo base_url(); ?>telaah/list_telaah/create_view/<?php echo $this->uri->segment(4); ?>/<?php echo $v->telaah_pelaksana ?>" class="btn btn-sm btn-block btn-primary"><i class="fa fa-plus"></i> SPPD Lanjutan</a>
  											<?php } ?>

  											<?php if ($v->telaah_status == 5) { ?>
  												<a href="<?php echo base_url(); ?>telaah/list_telaah/update_view/<?php echo $this->uri->segment(4) ?>?telaah_id=<?php echo $telaah_id ?>" class="btn btn-sm btn-block btn-primary"><i class="fa fa-edit"></i> Edit Telaah</a>
  											<?php } ?>

  											<?php if ($this->ion_auth->user()->row()->id == 1 || $this->ion_auth->get_users_groups()->row()->id == 9) { ?>
  												<button class="btn btn-sm btn-block btn-danger" data-toggle="modal" data-target="#myModal<?php echo $v->telaah_id ?>"><i class="fa fa-trash-o"></i> Hapus Telaah </button>
  											<?php } ?>
  										</td>

  									</tr>


  									<!-- Modal Delete Telaah -->
  									<div id="myModal<?php echo $v->telaah_id ?>" class="modal fade" role="dialog">
  										<div class="modal-dialog">

  											<!-- Modal content-->
  											<div class="modal-content">
  												<div class="modal-header">
  													<button type="button" class="close" data-dismiss="modal">&times;</button>
  													<h4 class="modal-title">Hapus Telaah</h4>
  												</div>
  												<?php
													if ($this->ion_auth->user()->row()->id == 1) {
														echo form_open("admin/delete_telaah");
													} else {
														echo form_open("telaah/list_telaah/delete_telaah");
													}
													?>
  												<div class="modal-body">
  													<p>
  														<?php $nama = !empty($v->pegawai_nama)
																? $v->pegawai_nama
																: (!empty($v->anggotadprd_name) ? $v->anggotadprd_name : '') ?>

  														Apakah Anda yakin ingin menghapus telaah <b><?= $nama ?></b>
  														dengan perihal :
  													</p>
  													<ul>
  														<li><b><?= $v->telaah_perihal ?></b></li>
  													</ul>
  													<input type="hidden" name="telaah_id" value="<?php echo $v->telaah_id ?>">
  													<input type="hidden" name="url" value="<?php echo $this->uri->segment(4) ?>">
  												</div>
  												<div class="modal-footer">
  													<button type="submit" class="btn btn-danger">Hapus</button>
  													<?php echo form_close(); ?>
  													<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
  												</div>
  											</div>

  										</div>
  									</div>

  								<?php
										$number++;
									}
									?>
  							</table>
  						</div>
  					</div>
  					<!-- /.box-body -->
  					<div class="box-footer clearfix">
  						<?php echo $links ?>
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