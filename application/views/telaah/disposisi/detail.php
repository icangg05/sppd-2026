 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<!-- Main content -->
 	<section class="content">
	<?php if ($this->uri->segment(4)=="walikota") { ?>
		<div class="row">
		  <!-- ./col -->                               
		  <div class="col-lg-12 col-xs-12">
			<div class="box box-warning">
				<!-- /.box-header -->
				<div class="box-body">
				  <script src="https://code.highcharts.com/highcharts.js"></script>
				  <script src="https://code.highcharts.com/modules/exporting.js"></script>
				  <script src="https://code.highcharts.com/modules/export-data.js"></script>
				  <div class="col-lg-8 col-xs-7">
					<div id="container" style="min-width: 140px; max-width: 500px; height: 420px; margin: 0 auto"></div>
				  </div>
				  <div class="col-lg-4 col-xs-5">
					<!-- small box -->                                  
					<a href="<?php echo site_url('walikota/detail_anggaran')?>">
					  <div class="small-box bg-aqua">
						<div class="inner">                                        
						  <p>TOTAL ANGGARAN</p>
						  <h2><p>Rp. <?php echo number_format($total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'], 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
					<!-- small box -->                                  
					<a href="<?php echo site_url('walikota/detail_anggaran')?>">
					  <div class="small-box bg-red">
						<div class="inner">                                      
						  <p>REALISASI ANGGARAN 
						  <b><?php 
						  $hasil = ($sisa_anggaran[0]->tes/$total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan']) * 100;
						  echo " (".round($hasil,1)."%)";?></p></b>
						  <h2><p>Rp. <?php echo number_format($sisa_anggaran[0]->tes, 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
					<!-- small box -->                                  
					<a href="<?php echo site_url('walikota/detail_anggaran')?>">
					  <div class="small-box bg-green">
						<div class="inner">                                       
						  <p>SISA ANGGARAN
						  <b><?php 
						  $hasil = (($total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'] - $sisa_anggaran[0]->tes)/$total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan']) * 100;
						  echo " (".round($hasil,1)."%)";?></b>
						  </p>
						  <h2><p>Rp. <?php echo number_format($total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'] - $sisa_anggaran[0]->tes, 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
				  </div>  
				</div>
				<!-- /.box-body -->
				
			</div>
          <!-- /.box -->
		  </div> 
		  <!-- ./col -->                                           
		              
		</div>
  		<!-- Small boxes (Stat box) -->
	<?php } ?>
	
 		<div class="row">
 			<!-- left column -->
 			<div class="col-md-12">
 				<!-- general form elements -->
 				<div class="box box-primary">
 					<div class="box-header with-border">
 						<h3 class="box-title">
						<?php 
						switch($this->uri->segment(5)){
							case "1" 		: echo "DETAIL TELAAH (Esselon III, IV dan Staff)"; break;
							case "2" 		: echo "DETAIL TELAAH (Kepala OPD)"; break;
							case "3" 		: echo "DETAIL TELAAH (DPRD)"; break;
							case "4" 		: echo "DETAIL TELAAH (Sekda, Asisten dan Kabag)"; break;
							case "5" 		: echo "DETAIL TELAAH (Camat/Lurah)"; break;
							case "6" 		: echo "DETAIL TELAAH (Staff DPRD)"; break;
							case "7" 		: echo "DETAIL TELAAH (Staff Camat/Staff Lurah)"; break;
							case "8" 		: echo "DETAIL TELAAH (Walikota)"; break;
							case "9" 		: echo "DETAIL TELAAH (Kasubag dan Staff Setda)"; break;
							case "10" 		: echo "DETAIL TELAAH (Sekwan)"; break;
							case "11" 		: echo "DETAIL TELAAH (Puskesmas JKN)"; break;
						}
						?> 
						</h3>
 					</div>
 						<!-- /.box-header -->
 						<!-- form start -->
 						<div class="table-responsive box-body">
 							<table class="table table-bordered ">
 								<tr>
 									<th class="col-md-3">Kepada</th>
 									<td><?php if($this->uri->segment(5)==1){
 										echo "Kepala ".$entry[0]['skpd_nama']; 
 									} else {
 										echo $entry[0]['telaah_kepada']; 
 									} 
 									?></td>
 								</tr>
 								<tr>
 									<th class="col-md-3">Dari</th>
 									<td><?php 
 										if($this->uri->segment(5)==3){
 											echo $entry[0]['anggotadprd_name']." || ".$entry[0]['anggotadprd_jabatan']." || SEKRETARIAT DPRD"; 
 										}else{
 											echo $entry[0]['pegawai_nama']." || ".$entry[0]['pegawai_namajabatan']." || ".$entry[0]['skpd_nama'];
 										}
 										
 										?>
									</td>
 								</tr>
 								<tr>
 									<th class="col-md-3">Tanggal Pengajuan</th>
 									<td><?php echo "".$entry[0]['telaah_waktuinput']; ?></td>
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
 								<!-- <tr>
 									<th class="col-md-3">Saran</th>
 									<td>Saran</td>
 								</tr> -->
 								<!-- <tr>
 									<th class="col-md-3">Biaya yang ditimbulkan dibebankan kepada</th>
 									<td></td>
 								</tr> -->
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
					<!--tr>
					  <th class="col-md-3">Saran</th>
					  <td>Saran</td>
					</tr>
					<tr>
					  <th class="col-md-3">Biaya yang ditimbulkan dibebankan kepada</th>
					  <td></td>
					</tr-->
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
					<tr class="info">
						<th class="col-md-3" colspan="2"><center>DATA ANGGARAN</center></th>
					</tr>
					<tr>
						<th class="col-md-3">No. Rekening Anggaran</th>
						<td><?php echo $entry[0]['no_rekening'];?></td>
					</tr>
					<tr>
						<th class="col-md-3">Program</th>
						<td><?php echo $entry[0]['nama_program'];?></td>
					</tr>
					<tr>
						<th class="col-md-3">Kegiatan</th>
						<td><?php echo $entry[0]['nama_kegiatan'];?></td>
					</tr>
					<tr>
						<th class="col-md-3">Anggaran Tersedia</th>
						<td>Rp. <?php echo number_format($entry[0]['sisa_pagu'],0,",",".")?></td>
					</tr>
					</table>
				</div>
				<!-- /.box-body -->
			</div>
			<!-- /.box -->
		</div>
		<!--/.col (left) -->
		<!-- left column -->
		<div class="col-md-12">
			<!-- general form elements -->
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">History Perjalanan Pelaksana dan Pengikut</h3>
				</div>
				<!-- /.box-header -->
				<!-- form start -->
				<?php echo form_open_multipart('telaah_staf/create_view'); 

				$telaah_id = base64_encode($this->encrypt->encode($entry[0]['telaah_id'], $this->session->userdata('encrypt_key')));	
				$telaah_kategori = base64_encode($this->encrypt->encode($entry[0]['telaah_kategori'], $this->session->userdata('encrypt_key')));
				?>
				<div class="box-body">
					<?php if ($this->uri->segment(5)!=3){ ?>
				<table class="table table-bordered ">
						<tr>
							<th class="col-md-3">Nama Pelaksana dan Pengikut</th>
							<th>History Perjalanan Dinas</td>
							</tr>
							<?php 
							$pelaksana = base64_encode($this->encrypt->encode($entry[0]['pegawai_id'], $this->session->userdata('encrypt_key')));	
							?>
							<tr>
								<td class="col-md-3"><?php echo $entry[0]['pegawai_nama']; ?></td>
								<?php if($entry[0]['pegawai_jabatan']==1){ ?>
									<td><a href="<?php echo base_url();?>telaah/history/index/walikota?pegawai_id=<?php echo $pelaksana; ?>" target="_blank">Lihat History</a></td>
								<?php } else { ?>
									<td><a href="<?php echo base_url();?>telaah/history?pegawai_id=<?php echo $pelaksana; ?>" target="_blank">Lihat History</a></td>
								<?php } ?>
							</tr>
							<?php foreach($pengikut as $v){ 
								$pegawai_id = base64_encode($this->encrypt->encode($v->pegawai_id, $this->session->userdata('encrypt_key')));	
								?>
								<tr>
									<td class="col-md-3"><?php echo $v->pegawai_nama; ?></td>
									<?php if($v->pegawai_jabatan==1){ ?>
										<td><a href="<?php echo base_url();?>telaah/history/index/walikota?pegawai_id=<?php echo $pegawai_id; ?>" target="_blank">Lihat History</a></td>
									<?php } else { ?>
										<td><a href="<?php echo base_url();?>telaah/history?pegawai_id=<?php echo $pegawai_id; ?>" target="_blank">Lihat History</a></td>
									<?php } ?>
								</tr>
								<?php } ?>
							</table>
					
				
				<?php } else { ?>			
					<table class="table table-bordered ">
						<tr>
							<th class="col-md-3">Nama Pelaksana dan Pengikut</th>
							<th>History Perjalanan Dinas</td>
							</tr>
							<?php 
							$pelaksana = base64_encode($this->encrypt->encode($entry[0]['anggotadprd_id'], $this->session->userdata('encrypt_key')));	
							?>
							<tr>
								<td class="col-md-3"><?php echo $entry[0]['anggotadprd_name']; ?></td>
								<td><a href="<?php echo base_url();?>telaah/history/index/dprd?pegawai_id=<?php echo $pelaksana; ?>" target="_blank">Lihat History</a></td>
							</tr>
							<?php foreach($pengikut as $v){ 
								$anggotadprd_id = base64_encode($this->encrypt->encode($v->anggotadprd_id, $this->session->userdata('encrypt_key')));	
								?>
								<tr>
									<td class="col-md-3"><?php echo $v->anggotadprd_name; ?></td>
									<td><a href="<?php echo base_url();?>telaah/history/index/dprd?pegawai_id=<?php echo $anggotadprd_id; ?>" target="_blank">Lihat History</a></td>
								</tr>
								<?php } ?>
							</table>
							
				<?php }  ?>
						</div>
						<!-- /.box -->
					</div>
					<!-- /.box -->
				</div>
				<!--/.col (left) -->
				<!-- left column -->
				<div class="col-md-12">
					<!-- general form elements -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Disposisi</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						<div class="box-body">
							<table class="table table-bordered ">
								<tr>
									<th class="col-md-3">Pejabat</th>
									<th>Pesan Disposisi</td>
										<th>Status</td>
										</tr>
										<?php 
										if($disposisi1!=""){ ?>
											<tr>
												<td class="col-md-3"><?php echo $nama_disposisi1;?></td>
												<td><?php echo $isi1; ?></td>
												<td><?php if($disposisi1==0){
													echo '<span class="label label-warning">Dalam Proses</span>';
												}else if($disposisi1==1){
													echo '<span class="label label-success">ACC</span>';
												}else if($disposisi1==2){
													echo '<span class="label label-danger">Batal</span>';
												} else if($disposisi1==5){
													echo '<span class="label label-primary">Perbaikan</span>';
												}
												?></td>
											</tr>
										<?php } 
										if($disposisi2!=""){ ?>
											<tr>
												<td class="col-md-3"><?php echo $nama_disposisi2;?></td>
												<td><?php echo $isi2; ?></td>
												<td><?php if($disposisi2==0){
													echo '<span class="label label-warning">Dalam Proses</span>';
												}else if($disposisi2==1){
													echo '<span class="label label-success">ACC</span>';
												}else if($disposisi2==2){
													echo '<span class="label label-danger">Batal</span>';
												} else if($disposisi2==5){
													echo '<span class="label label-primary">Perbaikan</span>';
												}
												?></td>
											</tr>
										<?php }  
										if($disposisi3!=""){ ?>
											<tr>
												<td class="col-md-3"><?php echo $nama_disposisi3;?></td>
												<td><?php echo $isi3; ?></td>
												<td><?php if($disposisi3==0){
													echo '<span class="label label-warning">Dalam Proses</span>';
												}else if($disposisi3==1){
													echo '<span class="label label-success">ACC</span>';
												}else if($disposisi3==2){
													echo '<span class="label label-danger">Batal</span>';
												} else if($disposisi3==5){
													echo '<span class="label label-primary">Perbaikan</span>';
												}
												?></td>
											</tr>
										<?php } 
										if($disposisi4!=""){ ?>
											<tr>
												<td class="col-md-3"><?php echo $nama_disposisi4;?></td>
												<td><?php echo $isi4; ?></td>
												<td><?php if($disposisi4==0){
													echo '<span class="label label-warning">Dalam Proses</span>';
												}else if($disposisi4==1){
													echo '<span class="label label-success">ACC</span>';
												}else if($disposisi4==2){
													echo '<span class="label label-danger">Batal</span>';
												} else if($disposisi4==5){
													echo '<span class="label label-primary">Perbaikan</span>';
												}
												?></td>
											</tr>
										<?php } ?>
									</table>
								</div>
								<!-- /.box-body -->
								<div class="box-footer">
									<div class="col-md-12">
										<?php 	if ($telaah_disetujui==1){ ?>	
										<a href="<?php echo base_url();?>telaah/disposisi/lihat_laporan/<?php echo $this->uri->segment(4)?>/<?php echo $this->uri->segment(5)?>?telaah_id=<?php echo $this->input->get('telaah_id')?>&&telaah_disetujui=1" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
										<?php } else if ($telaah_ditolak==1){ ?>
										<a href="<?php echo base_url();?>telaah/disposisi/lihat_laporan/<?php echo $this->uri->segment(4)?>/<?php echo $this->uri->segment(5)?>?telaah_id=<?php echo $this->input->get('telaah_id')?>&&telaah_ditolak=1" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
										<?php } else { ?>
										<a href="<?php echo base_url();?>telaah/disposisi/lihat_laporan/<?php echo $this->uri->segment(4)?>/<?php echo $this->uri->segment(5)?>?telaah_id=<?php echo $this->input->get('telaah_id')?>" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
										<?php } ?>

									</div>
								</div>
							</div>
							<!-- /.box -->
						</div>
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
			$(function() {
				$('#datepicker2').datepicker({
					format:'yyyy-mm-dd',
					autoclose: true
				});
			});
		</script>
		<script>
		Highcharts.chart('container', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
			title: {
				text: 'Diagram Anggaran'
			}, 
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: '<b><center>{point.name}</center></b><br>{point.percentage:.1f} %',
						distance: -50,
					}
				}
			},
			series: [{
				name: 'Anggaran',
				colorByPoint: true,
				data: [{
					name: 'Sisa Anggaran',
					y: 	<?php 
						  $hasil = (($total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'] - $sisa_anggaran[0]->tes)/$total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan']) * 100;
						  echo round($hasil,2);?>,
					color: '#00a65a',
					sliced: true,
					selected: true
				}, {
					name: 'Anggaran terpakai',
					y: <?php 
						  $hasil = ($sisa_anggaran[0]->tes/$total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan']) * 100;
						  echo round($hasil,2);?>,
					color: '#dd4b39',
				}]
			}]
		});	
	</script>