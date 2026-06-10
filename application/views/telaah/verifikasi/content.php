<link href="<?php echo base_url();?>assets2/plugins/bootstrap-toggle/bootstrap-toggle.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets2/plugins/bootstrap-toggle/bootstrap-toggle.js"></script>
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
  						<h3 class="box-title">LAPORAN PERJALANAN DINAS</h3>
  					</div>
  					<div class="box-header with-border">
  						<?php echo form_open("telaah/verifikasi/search/".$this->uri->segment(4));?>
  						<div class="col-md-9">
								<a href="<?php echo base_url();?>telaah/verifikasi" class="btn btn-warning btn-flat">Refresh</a>
  						</div>
  						<div class="col-md-3">
  							<div class="input-group">
  								<input type="text" class="form-control" name="data" placeholder="Pelaksana ...">
  								<span class="input-group-btn">
  									<input type="submit" name="submit" class="btn btn-info btn-flat" value="Go">
  								</span>
  							</div>
  						</div>
  						<?php echo form_close();?>
  					</div>
  					<!-- /.box-header -->
  					<div class="table-responsive box-body">
  						<?php
  						$message = $this->session->flashdata('notif');
  						if($message){
  							echo '<p class="alert alert-info text-center"><b>'.$message .'</b></p>';
  						}
  						?>
  						
  						<div class="table-responsive box-body">
  							<table class="table table-bordered table-striped table-hover">
  								<tr class='info'>
  									<th style="width: 5px">No</th>
  									<th style="width: 40px">Tanggal Pengajuan</th>
  									<th style="width: 150px">Perihal (Maksud Perjalanan Dinas)</th>
  									<th style="width: 200px">Pelaksana</th>
  									<th style="width: 40px">Status</th>
									<?php if($this->ion_auth->user()->row()->jenis_skpd == 10 || $this->ion_auth->user()->row()->id==1){?>
											<th style="width: 40px">OPD</th>
  									<?php } ?>		
  									<th style="width: 100px">Lihat Telaah</th>
  									<th style="width: 100px">Rincian</th>
  									<!--th style="width: 100px">Aksi</th-->
  								</tr>
  								<?php 
  								$number=$number+1;
  								foreach($verifikasi as $v){
  									$telaah_id = base64_encode($this->encrypt->encode($v->telaah_id, $this->session->userdata('encrypt_key')));	
  									?>
  									<tr>
  										<td><?php echo $number;?></td>
  										<td><?php echo $v->telaah_waktuinput?></td>
  										<td><?php echo $v->telaah_perihal?></td>
  										<td><?php echo $v->pegawai_nama?></td>
  										<td><?php
												if($v->status_laporan == 0){
													echo "<p id='status".$v->telaah_id."'><span class='label label-default'>Laporan Belum Di Verifikasi</span></p>";
												} else {
													echo "<p id='status".$v->telaah_id."'><span class='label label-success'>Laporan Sudah Di Verifikasi </span></p>";
												}
  											?>
  										</td>
										
										<?php if($this->ion_auth->user()->row()->jenis_skpd == 10){?>	
											<?php if($v->jenis_skpd_id == 10){ ?>
												<td><span class="label label-primary"><?php echo $v->skpd_nama?></span></td>
											<?php } else { ?>
												<td><span class="label label-danger"><?php echo $v->skpd_nama?></span></td>
											<?php } ?>
  										<?php } else if ($this->ion_auth->user()->row()->id==1){?>
												<td><?php echo $v->skpd_nama?></td>
  										<?php } ?>
										
										<td><a href="" data-toggle="modal" data-target="#detail<?php echo $v->telaah_id;?>" class="btn btn-sm btn-block btn-primary"><i class="fa fa-eye"></i> Detail</a></td>
  										
										<td>
											<a href="" data-toggle="modal" data-target="#rincian<?php echo $v->telaah_id;?>" class="btn btn-sm btn-block btn-warning"><i class="fa fa-eye"></i> Rincian</a>
  										</td>
										
										<!--td-->
											<div id="tampilkan_product<?php echo $v->telaah_id;?>">
												<input type="hidden" class="form-control" name="status_laporan" id="status_laporan<?php echo $v->telaah_id?>" value="<?php echo $v->status_laporan?>">
											</div>	
											<!--input id="toggle-event" type="checkbox" data-toggle="toggle" data-onstyle="success" data-on="Verifikasi" data-off="Belum Verifikasi" data-size="small" data-width="120" <!--?php if ($v->status_laporan == '1') echo 'checked'; ?> onchange ="verifikasi_laporan<!--?php echo $v->telaah_id;?>()" -->
										<!--/td-->
										
											
										
										
  								</tr>
  								<?php 
  								$number++;
  							} 
  							?>
  						</table>
						
						
						<?php 
  								$number=$number+1;
  								foreach($verifikasi as $v){ 
								
								?>
								
								<!-- MODAL -->
								<div class="modal fade" id="detail<?php echo $v->telaah_id;?>" role="dialog">
								
								<?php 
								if($v->telaah_kategori==3){
									$entry 		=  $this->m_telaah->get_dprd($v->telaah_id);
									$pengikut 	=  $this->m_pengikut->data_dprd($v->telaah_id);
								} else if($v->telaah_kategori==8){
									$entry 		=  $this->m_telaah->getWalikota($v->telaah_id);
									$pengikut 	=  $this->m_pengikut->data($v->telaah_id);
								} else {
									$entry 		=  $this->m_telaah->get($v->telaah_id);
									$pengikut 	=  $this->m_pengikut->data($v->telaah_id);
								}
								?>
								  <div class="modal-dialog">
									<div class="modal-content">
									  <div class="modal-header">
										<h3 class="modal-title">Detail Perjalanan</h3>
									  </div>
									  <div class="modal-body">
									  <table class="table table-striped table-bordered">
										<tr class="info">
										  <td colspan=2 style="text-align:center"><b>DATA PERIHAL</b></td>
										</tr>
										<tr>
											<th class="col-md-3">Kepada</th>
											<td><?php echo $entry[0]['telaah_kepada']; ?></td> 
										</tr>
										<tr>
											<th class="col-md-3">Dari</th>
											<td><?php 
												echo $entry[0]['pegawai_nama']." || ".$entry[0]['pegawai_namajabatan']." || ".$entry[0]['skpd_nama']; 
											?>
											</td>
										</tr>		
										<tr>
											<th class="col-md-3">Tanggal Pengajuan</th>
											<td>
												<?php 
													$date = substr($entry[0]['telaah_waktuinput'], 0, 10);
													$time = substr($entry[0]['telaah_waktuinput'], 11, 19);
													$telaah_waktuinput =  $this->waktu->date_indo($date);
													echo $telaah_waktuinput.' '.$time;
												?>
											</td>
										</tr>
										<tr>
											<th class="col-md-3">Perihal (Maksud Perjalanan Dinas)</th>
											<td><?php echo "".$entry[0]['telaah_perihal']; ?></td>
										</tr>
										<tr>
											<th class="col-md-3">Persoalan</th>
											<td><?php echo "".$entry[0]['telaah_persoalan']; ?></td>
										</tr>
										<tr>
											<th class="col-md-3">Fakta-fakta yang mempengaruhi</th>
											<td><?php echo "".$entry[0]['telaah_fakta']; ?></td>
										</tr>
										<tr>
											<th class="col-md-3">Analisis</th>
											<td><?php echo "".$entry[0]['telaah_analisis']; ?></td>
										</tr>
										<?php if($entry[0]['telaah_dokumenpendukung']==""){?>
											<tr>
												<th class="col-md-3">Dokumen Pendukung</th>
												<td>Tidak Ada Dokumen Pendukung</td>
											</tr>
										<?php } else{?>
											<tr>
												<th class="col-md-3">Dokumen Pendukung</th>
												<td><a href="<?php echo base_url('upload/telaah/'.$entry[0]['telaah_dokumenpendukung']); ?>" target="_blank">Lihat File</a></td>
											</tr>
										<?php } ?>
										<tr class="info">
											<th class="col-md-3" colspan="2"><center>DATA PERJALANAN</center></th>
										</tr>
										<tr>
											<th class="col-md-3">Tanggal Berangkat</th>
											<td><?php echo date("d-m-Y", strtotime($entry[0]['telaah_tanggalberangkat']));?></td>
										</tr>
										<tr>
											<th class="col-md-3">Tanggal Kembali</th>
											<td><?php echo date("d-m-Y", strtotime($entry[0]['telaah_tanggalkembali']));?></td>
										</tr>
										<tr>
											<th class="col-md-3">Domain Perjalanan</th>
											<td><?php 
												if($entry[0]['telaah_domainperjalanan']==1){
													echo "LUAR DAERAH LUAR PROVINSI (LDLP)";
												} else if ($entry[0]['telaah_domainperjalanan']==2){
													echo "LUAR DAERAH DALAM PROVINSI (LDDP)";
												} else if ($entry[0]['telaah_domainperjalanan']==3){
													echo "DALAM DAERAH";
												} else if ($entry[0]['telaah_domainperjalanan']==4){
													echo "DALAM DAERAH";
												}
												?></td>
											</tr>
											<tr>
												<th class="col-md-3"></th>
												<td><?php 
													echo "Provinsi : ".$entry[0]['provinsi']."<br>";
													echo "Kab/Kota : ".$entry[0]['kabupaten_kota']."<br><br>";
													
													$lokasi_tujuan = $this->m_lokasi_tujuan->get($entry[0]['telaah_id']);
													foreach($lokasi_tujuan as $v){
														echo "Provinsi : ".$v->provinsi."<br>";
														echo "Kab/Kota : ".$v->kabupaten_kota."<br><br>";
														
													} ?>
												</td>
											</tr>
											<tr>
												<th class="col-md-3">Kantor Tujuan</th>
												<td><?php echo "".$entry[0]['telaah_kantortujuan']; ?></td>
											</tr>
											<tr>
												<th class="col-md-3">Kecepatan Telaah</th>
												<td><?php 
													if($entry[0]['telaah_kecepatan']== 0 ) {
														echo "Biasa";	
													} else if($entry[0]['telaah_kecepatan']== 1 ) {
														echo "Segera";	
													}
													?></td>
												</tr>
										</table>
										<table class="table table-striped table-bordered">
										<tr class="info">
											<th class="col-md-12" colspan=2><center>DATA LAPORAN KEUANGAN</center></th>
										</tr>
										<?php if ($this->uri->segment(5)!=3){ ?>
											<tr>
												<td class="col-md-6"><?php echo $entry[0]['pegawai_nama']; ?></td>
												<td class="col-md-6">Rp.
												<?php 
													$rincian = $this->m_verifikasi->rincian($v->telaah_id,$entry[0]['pegawai_id']);
													$pengeluaran_rill = $this->m_verifikasi->pengeluaran_rill($v->telaah_id,$entry[0]['pegawai_id']);
													echo number_format($rincian[0]['total']+$pengeluaran_rill[0]['total'],0,",",".");
												?>
												</td>
											</tr>
											<?php foreach($pengikut as $s){ 
												$pegawai_id = base64_encode($this->encrypt->encode($s->pegawai_id, $this->session->userdata('encrypt_key')));	
												?>
												<tr>
													<td class="col-md-6"><?php echo $s->pegawai_nama; ?></td>
													<td class="col-md-6">Rp. 
													<?php 
														$rincian2 = $this->m_verifikasi->rincian($s->telaah_id,$s->pegawai_id);
														$pengeluaran_rill2 = $this->m_verifikasi->pengeluaran_rill($s->telaah_id,$s->pegawai_id);
														echo number_format($rincian2[0]['total']+$pengeluaran_rill2[0]['total'],0,",",".");
													?>
													</td>
												</tr>
												<?php } ?>
											
										
										<?php } else { ?>	
												<tr>
													<td class="col-md-6"><?php echo $entry[0]['anggotadprd_name']; ?></td>
													<td class="col-md-6">Rp.
													<?php 
													$rincian = $this->m_verifikasi->rincian($v->telaah_id,$entry[0]['anggotadprd_id']);
													$pengeluaran_rill = $this->m_verifikasi->pengeluaran_rill($v->telaah_id,$entry[0]['anggotadprd_id']);
													echo number_format($rincian[0]['total']+$pengeluaran_rill[0]['total'],0,",",".");
												?>
													</td>
												</tr>
												<?php foreach($pengikut as $s){ 
													$anggotadprd_id = base64_encode($this->encrypt->encode($s->anggotadprd_id, $this->session->userdata('encrypt_key')));	
													?>
												<tr>
													<td class="col-md-6"><?php echo $s->anggotadprd_name; ?></td>
													<td class="col-md-6">Rp.
													<?php 
														$rincian2 = $this->m_verifikasi->rincian($s->telaah_id,$s->anggotadprd_id);
														$pengeluaran_rill2 = $this->m_verifikasi->pengeluaran_rill($s->telaah_id,$s->anggotadprd_id);
														echo number_format($rincian2[0]['total']+$pengeluaran_rill2[0]['total'],0,",",".");
													?>
													</td>
												</tr>
												<?php } ?>
										<?php }  ?>
									  </table>
									  </div>
									  <div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									  </div>
									</div>
									
								  </div>
								</div>
								
								
								<!-- MODAL RINCIAN -->
								<div class="modal fade" id="rincian<?php echo $v->telaah_id;?>">
								  <div class="modal-dialog">
									<div class="modal-content">
									  <div class="modal-header">
										<h3 class="modal-title">DATA LAPORAN</h3>
									  </div>
									  <div class="modal-body">
										  <!-- Custom Tabs -->
										  <div class="nav-tabs-custom" id="hasil">
											<ul class="nav nav-tabs">
											  <li class="active"><a href="#tab_1<?php echo $v->telaah_id;?>" data-toggle="tab">Laporan</a></li>
											  <li><a href="#tab_2<?php echo $v->telaah_id;?>" data-toggle="tab">Rincian Biaya</a></li>
											</ul>
											<div class="tab-content">
											  <div class="tab-pane active" id="tab_1<?php echo $v->telaah_id;?>">
												<table class="table table-striped table-bordered">
												<tr>
													<td class="col-md-6">Laporan</td>
													<td class="col-md-6"><a href="<?php echo base_url();?>upload/laporan_perjalanan/<?php echo $v->laporanperjalanan_file; ?>" target=_blank><?php echo $v->laporanperjalanan_file; ?></a></td>
												</tr>
												</table>
											  </div>
											  <div class="tab-pane" id="tab_2<?php echo $v->telaah_id;?>">
												<table class="table table-striped table-bordered">
												<?php if ($this->uri->segment(5)!=3){ ?>
													<tr class="info" >
														<td class="col-md-6" colspan=2><b><?php echo $entry[0]['pegawai_nama']; ?></b></td>
														<?php 
															$all_pengeluaran_rill = $this->m_verifikasi->all_pengeluaran_rill($v->telaah_id,$entry[0]['pegawai_id']);
															$all_rincian = $this->m_verifikasi->all_rincian($v->telaah_id,$entry[0]['pegawai_id']);
														?>
													</tr>
													<?php foreach($all_pengeluaran_rill as $p){ ?>
														<tr>
															<td class="col-md-6"><?php echo $p->uraian; ?></td>
															<td class="col-md-6">Rp. <?php echo number_format($p->tarif,0,",","."); ?></td>
														</tr>
													<?php } ?>
													<?php foreach($all_rincian as $q){ ?>
														<tr>
															<td class="col-md-6">
																<?php 
																	switch($q->kategori_biaya){
																		case 1: echo "Penginapan"; break;
																		case 2: echo "Sewa Kendaraan"; break;
																		case 3: echo "Transport"; break;
																		case 4: echo "Biaya Lainnya"; break;
																		case 5: echo "Lumsum"; break;
																		case 6: echo "Representasi"; break;
																	}
																?>
															</td>
															<td class="col-md-6">
																Rp. <?php echo number_format($q->item*$q->tarif,0,",","."); ?>
															</td>
														</tr>
													<?php } ?>
													
													
													
													
													
													
													<?php foreach($pengikut as $s){ 
														$pegawai_id = base64_encode($this->encrypt->encode($s->pegawai_id, $this->session->userdata('encrypt_key')));	
														?>
														<tr class="info">
															<td class="col-md-6" colspan=2><b><?php echo $s->pegawai_nama; ?></b></td>
															<?php 
																$all_pengeluaran_rill2 = $this->m_verifikasi->all_pengeluaran_rill($s->telaah_id,$s->pegawai_id);
																$all_rincian2 = $this->m_verifikasi->all_rincian($s->telaah_id,$s->pegawai_id);
															?>
														</tr>
														<?php foreach($all_pengeluaran_rill2 as $p){ ?>
															<tr>
																<td class="col-md-6"><?php echo $p->uraian; ?></td>
																<td class="col-md-6">Rp. <?php echo number_format($p->tarif,0,",","."); ?></td>
															</tr>
														<?php } ?>
														<?php foreach($all_rincian2 as $q){ ?>
															<tr>
																<td class="col-md-6">
																	<?php 
																		switch($q->kategori_biaya){
																			case 1: echo "Penginapan"; break;
																			case 2: echo "Sewa Kendaraan"; break;
																			case 3: echo "Transport"; break;
																			case 4: echo "Biaya Lainnya"; break;
																			case 5: echo "Lumsum"; break;
																			case 6: echo "Representasi"; break;
																		}
																	?>
																</td>
																<td class="col-md-6">
																	Rp. <?php echo number_format($q->item*$q->tarif,0,",","."); ?>
																</td>
															</tr>
														<?php } ?>
													
													<?php } ?>
													
												
												
												
												<?php } else { ?>	
														<tr class="info" >
															<td class="col-md-6" colspan=2><?php echo $entry[0]['anggotadprd_name']; ?></td>
															<?php 
															$all_pengeluaran_rill = $this->m_verifikasi->all_pengeluaran_rill($v->telaah_id,$entry[0]['anggotadprd_id']);
															$all_rincian = $this->m_verifikasi->all_rincian($v->telaah_id,$entry[0]['anggotadprd_id']);
															?>
														</tr>
														<?php foreach($all_pengeluaran_rill as $p){ ?>
															<tr>
																<td class="col-md-6"><?php echo $p->uraian; ?></td>
																<td class="col-md-6">Rp. <?php echo number_format($p->tarif,0,",","."); ?></td>
															</tr>
														<?php } ?>
														<?php foreach($all_rincian as $q){ ?>
															<tr>
																<td class="col-md-6">
																	<?php 
																		switch($q->kategori_biaya){
																			case 1: echo "Penginapan"; break;
																			case 2: echo "Sewa Kendaraan"; break;
																			case 3: echo "Transport"; break;
																			case 4: echo "Biaya Lainnya"; break;
																			case 5: echo "Lumsum"; break;
																			case 6: echo "Representasi"; break;
																		}
																	?>
																</td>
																<td class="col-md-6">
																	Rp. <?php echo number_format($q->item*$q->tarif,0,",","."); ?>
																</td>
															</tr>
														<?php } ?>
														
														
														
														<?php foreach($pengikut as $s){ 
															$anggotadprd_id = base64_encode($this->encrypt->encode($s->anggotadprd_id, $this->session->userdata('encrypt_key')));	
															?>
															<tr class="info">
																<td class="col-md-6" colspan=2><?php echo $s->anggotadprd_name; ?></td>
																<?php 
																	$pengeluaran_rill2 = $this->m_verifikasi->pengeluaran_rill($s->telaah_id,$s->anggotadprd_id);
																	$rincian2 = $this->m_verifikasi->rincian($s->telaah_id,$s->anggotadprd_id);
																?>
															</tr>
															<?php foreach($all_pengeluaran_rill2 as $p){ ?>
															<tr>
																<td class="col-md-6"><?php echo $p->uraian; ?></td>
																<td class="col-md-6">Rp. <?php echo number_format($p->tarif,0,",","."); ?></td>
															</tr>
															<?php } ?>
															<?php foreach($all_rincian2 as $q){ ?>
																<tr>
																	<td class="col-md-6">
																		<?php 
																			switch($q->kategori_biaya){
																				case 1: echo "Penginapan"; break;
																				case 2: echo "Sewa Kendaraan"; break;
																				case 3: echo "Transport"; break;
																				case 4: echo "Biaya Lainnya"; break;
																				case 5: echo "Lumsum"; break;
																				case 6: echo "Representasi"; break;
																			}
																		?>
																	</td>
																	<td class="col-md-6">
																		Rp. <?php echo number_format($q->item*$q->tarif,0,",","."); ?>
																	</td>
																</tr>
															<?php } ?>
														<?php } ?>			
												<?php }  ?>
											  </table>
											  </div>
											</div>
										  </div>

									  
									  </div>
									  <div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
										<?php if($this->uri->segment(4)=="sekretaris"){ ?>
										<button class="btn btn-success" onclick ="verifikasi_laporan<?php echo $v->telaah_id;?>()" data-dismiss="modal">Verifikasi Laporan</button>
										<?php } ?>
										
									  </div>
									  
									  <script>
										function verifikasi_laporan<?php echo $v->telaah_id;?>()
										{
											telaah_id = <?php echo $v->telaah_id?>;
											status_laporan = document.getElementById("status_laporan<?php echo $v->telaah_id?>").value;
											$.ajax({
												url:"<?php echo base_url();?>telaah/verifikasi/verifikasi/"+telaah_id+"/"+status_laporan+"",
												success: function(response){
													$("#tampilkan_product<?php echo $v->telaah_id;?>").html(response);
													 if(status_laporan==1){
														 document.getElementById("status<?php echo $v->telaah_id;?>").innerHTML = "<span class='label label-default'>Laporan Belum Di Verifikasi</span>";
													 } else {
														 document.getElementById("status<?php echo $v->telaah_id;?>").innerHTML = "<span class='label label-success'>Laporan Sudah Di Verifikasi</span>";
													}
												}
											});
											return false;
										}
										</script>
									</div>
								  </div>
								</div>
								<!-- END MODAL RINCIAN-->
								
						<?php } ?>		
  					</div>
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