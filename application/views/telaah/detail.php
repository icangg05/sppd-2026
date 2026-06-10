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
 						<h3 class="box-title">
						<?php 
						switch($this->uri->segment(4)){
							case "esselon" 			: echo "DETAIL TELAAH (Esselon III, IV dan Staff)"; break;
							case "kadis" 			: echo "DETAIL TELAAH (Kepala OPD)"; break;
							case "dprd" 			: echo "DETAIL TELAAH (DPRD)"; break;
							case "sekda" 			: echo "DETAIL TELAAH (Sekda, Asisten dan Kabag)"; break;
							case "camat" 			: echo "DETAIL TELAAH (Camat)"; break;
							case "lurah" 			: echo "DETAIL TELAAH (Lurah)"; break;
							case "staff_dprd" 		: echo "DETAIL TELAAH (Staff DPRD)"; break;
							case "staff_camat" 		: echo "DETAIL TELAAH (Staff Camat)"; break;
							case "staff_lurah" 		: echo "DETAIL TELAAH (Staff Lurah)"; break;
							case "walikota" 		: echo "DETAIL TELAAH (Walikota)"; break;
							case "staff_setda" 		: echo "DETAIL TELAAH (Kasubag dan Staff Setda)"; break;
							case "sekwan" 			: echo "DETAIL TELAAH (Sekwan)"; break;
							case "kapus" 			: echo "DETAIL TELAAH (Puskesmas JKN)"; break;
						}
						?> 
						</h3>
 					</div>
 					<!-- /.box-header -->
 					<!-- form start -->
 					
 					<div class="table-responsive box-body">
 						<table class="table table-bordered ">
 							<tr class="info">
 								<th class="col-md-3" colspan="2"><center>DATA PERIHAL</center></th>
 							</tr>
 							<tr>
 								<th class="col-md-3">Kepada</th>
								<?php 
								switch($this->uri->segment(4)){
									case "esselon" 			: if ($entry[0]['jenis_skpd'] == 7 && $entry[0]['telaah_kategori'] == 1){
																  echo "<td>Kepala Dinas Kesehatan</td>";
															  } else {
																  echo "<td>Kepala ".$entry[0]['skpd_nama']."</td>";
															  };
															break;
									case "kadis" 			: echo "<td>".$entry[0]['telaah_kepada']."</td>"; break;
									case "dprd" 			: echo "<td>".$entry[0]['telaah_kepada']."</td>"; break;
									case "sekda" 			: echo "<td>".$entry[0]['telaah_kepada']."</td>"; break;
									case "camat" 			: echo "<td>".$entry[0]['telaah_kepada']."</td>"; break;
									case "lurah" 			: echo "<td>".$entry[0]['telaah_kepada']."</td>"; break;
									case "staff_dprd" 		: echo "<td>".$entry[0]['telaah_kepada']."</td>"; break;
									case "staff_camat" 		: echo "<td>".$entry[0]['telaah_kepada']."</td>"; break;
									case "staff_lurah" 		: echo "<td>".$entry[0]['telaah_kepada']."</td>"; break;
									case "walikota" 		: echo "<td>".$entry[0]['telaah_kepada']."</td>"; break;
									case "staff_setda" 		: echo "<td>".$entry[0]['telaah_kepada']."</td>"; break;
									case "sekwan" 			: echo "<td>".$entry[0]['telaah_kepada']."</td>"; break;
									case "kapus" 			: echo "<td>".$entry[0]['telaah_kepada']."</td>"; break;
								}
								?> 
 							</tr>
 							<tr>
 								<th class="col-md-3">Dari</th>
 								<td><?php 
								if ($this->uri->segment(4)=="dprd"){
									echo $entry[0]['anggotadprd_name']." || ".$entry[0]['anggotadprd_jabatan']." || ".$entry[0]['skpd_nama'];
								} else {
									echo $entry[0]['pegawai_nama']." || ".$entry[0]['pegawai_namajabatan']." || ".$entry[0]['skpd_nama']; 
								} ?>
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
					<?php 
					$telaah_id = base64_encode($this->encrypt->encode($entry[0]['telaah_id'], $this->session->userdata('encrypt_key')));	
					$telaah_kategori = base64_encode($this->encrypt->encode($entry[0]['telaah_kategori'], $this->session->userdata('encrypt_key')));
					?>
					<div class="box-body">
						<table class="table table-bordered ">
							<tr>
								<th class="col-md-3">Nama Pelaksana dan Pengikut</th>
								<?php if($entry[0]['telaah_skpd_id'] == 182){ ?>
									<th class="col-md-3">Jabatan Pada Perjalanan</th>
								<?php } ?>
								<th>History Perjalanan Dinas</td>
								</tr>
								<?php 
								if ($this->uri->segment(4)=="dprd"){
									$pelaksana = base64_encode($this->encrypt->encode($entry[0]['anggotadprd_id'], $this->session->userdata('encrypt_key')));	
								} else {
									$pelaksana = base64_encode($this->encrypt->encode($entry[0]['pegawai_id'], $this->session->userdata('encrypt_key')));
								}
								?>
								<tr>
									<?php 
									if ($this->uri->segment(4)=="dprd"){
										echo "<td class='col-md-3'>".$entry[0]['anggotadprd_name']."</td>";
										echo "<td><a href=".base_url()."telaah/history/index/dprd?pegawai_id=".$pelaksana." target='_blank'>Lihat History</a></td>";
									} else if ($this->uri->segment(4)=="walikota"){
										echo "<td class='col-md-3'>".$entry[0]['pegawai_nama']."</td>";
										echo "<td><a href=".base_url()."telaah/history/index/walikota?pegawai_id=".$pelaksana." target='_blank'>Lihat History</a></td>";
									} else {
										echo "<td class='col-md-3'>".$entry[0]['pegawai_nama']."</td>";
										if($entry[0]['telaah_skpd_id'] == 182){ 
											if($entry[0]['telaah_jabatan_pelaksana']==1){
												echo "<td class='col-md-3'>Penanggung Jawab</td>";
											} else if($entry[0]['telaah_jabatan_pelaksana']==2){
												echo "<td class='col-md-3'>Pembantu Penanggung Jawab</td>";
											} else if($entry[0]['telaah_jabatan_pelaksana']==3){
												echo "<td class='col-md-3'>Pengendali Teknis</td>";
											} else if($entry[0]['telaah_jabatan_pelaksana']==4){
												echo "<td class='col-md-3'>Ketua Tim</td>";
											} else if($entry[0]['telaah_jabatan_pelaksana']==5){
												echo "<td class='col-md-3'>Anggota</td>";
											} else if($entry[0]['telaah_jabatan_pelaksana']==6){
												echo "<td class='col-md-3'>Admin Tim</td>";
											}
										} 
										echo "<td><a href=".base_url()."telaah/history?pegawai_id=".$pelaksana." target='_blank'>Lihat History</a></td>";
									}
									?>
								</tr>
								
								<?php foreach($pengikut as $v){ 
									if ($this->uri->segment(4)=="dprd"){
										$anggotadprd_id = base64_encode($this->encrypt->encode($v->anggotadprd_id, $this->session->userdata('encrypt_key')));
									} else {
										$pegawai_id = base64_encode($this->encrypt->encode($v->pegawai_id, $this->session->userdata('encrypt_key')));	
									}
									?>
									<tr>
										
										<?php 
										if ($this->uri->segment(4)=="dprd"){
											echo "<td class='col-md-3'>".$v->anggotadprd_name."</td>";
											echo "<td><a href=".base_url()."telaah/history/index/dprd?pegawai_id=".$anggotadprd_id." target='_blank'>Lihat History</a></td>";
										} else {
											echo "<td class='col-md-3'>".$v->pegawai_nama."</td>";
											if($entry[0]['telaah_skpd_id'] == 182){ 
												if($v->telaah_jabatan_pengikut==1){
													echo "<td class='col-md-3'>Penanggung Jawab</td>";
												} else if($v->telaah_jabatan_pengikut==2){
													echo "<td class='col-md-3'>Pembantu Penanggung Jawab</td>";
												} else if($v->telaah_jabatan_pengikut==3){
													echo "<td class='col-md-3'>Pengendali Teknis</td>";
												} else if($v->telaah_jabatan_pengikut==4){
													echo "<td class='col-md-3'>Ketua Tim</td>";
												} else if($v->telaah_jabatan_pengikut==5){
													echo "<td class='col-md-3'>Anggota</td>";
												} else if($v->telaah_jabatan_pengikut==6){
													echo "<td class='col-md-3'>Admin Tim</td>";
												}
											} 
											if($v->pegawai_jabatan==1){
												echo "<td><a href=".base_url()."telaah/history/index/walikota?pegawai_id=".$pegawai_id." target='_blank'>Lihat History</a></td>";
											} else {
												echo "<td><a href=".base_url()."telaah/history?pegawai_id=".$pegawai_id." target='_blank'>Lihat History</a></td>";
											}
										}
										?>
										
										
									</tr>
									<?php } ?>
								</table>
							</div>
						</div>
						<!-- /.box -->
					</div>
					<!--/.col (left) -->
					<!-- left column -->
					<div class="col-md-12">
						<!-- general form elements -->
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title">TRACKING TELAAH STAF</h3>
							</div>
							<!-- /.box-header -->
							<!-- form start -->
							<div class="box-body">
							
							<!-- Disposisi 1 -->
							<?php 
							if($disposisi1!=""){
							if($disposisi1==0){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-warning box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi1;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">DIPROSES<br><i class="fa fa-fw fa-refresh text-warning"></i></p>
										</div>
									</div>
								</div>
							<?php } else if($disposisi1==1){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi1;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">OK<br><i class="fa fa-fw fa-check text-success"></i></p>
										</div>
									</div>
								</div>
							<?php } else if($disposisi1==5){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-primary box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi1;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">PERBAIKI<br><i class="fa fa-fw fa-refresh text-primary"></i></p>
										</div>
									</div>
								</div>
							<?php } else if($disposisi1==2){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-danger box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi1;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">DITOLAK<br><i class="fa fa-fw fa-close text-danger"></i></p>
										</div>
									</div>
								</div>
							<?php } } ?>
							
							
							<!-- Disposisi 2 -->
							<?php 
							if($disposisi2!=""){
							if($disposisi2==0){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-warning box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi2;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">DIPROSES<br><i class="fa fa-fw fa-refresh text-warning"></i></p>
										</div>
									</div>
								</div>
							<?php } else if($disposisi2==1){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi2;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">OK<br><i class="fa fa-fw fa-check text-success"></i></p>
										</div>
									</div>
								</div>
							<?php } else if($disposisi2==5){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-primary box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi2;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">PERBAIKI<br><i class="fa fa-fw fa-refresh text-primary"></i></p>
										</div>
									</div>
								</div>
							<?php } else if($disposisi2==2){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-danger box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi2;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">DITOLAK<br><i class="fa fa-fw fa-close text-danger"></i></p>
										</div>
									</div>
								</div>
							<?php } } ?>
							
							
							<!-- Disposisi 3 -->
							<?php 
							if($disposisi3!=""){
							if($disposisi3==0){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-warning box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi3;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">DIPROSES<br><i class="fa fa-fw fa-refresh text-warning"></i></p>
										</div>
									</div>
								</div>
							<?php } else if($disposisi3==1){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi3;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">OK<br><i class="fa fa-fw fa-check text-success"></i></p>
										</div>
									</div>
								</div>
							<?php } else if($disposisi3==5){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-primary box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi3;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">PERBAIKI<br><i class="fa fa-fw fa-refresh text-primary"></i></p>
										</div>
									</div>
								</div>
							<?php } else if($disposisi3==2){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-danger box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi3;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">DITOLAK<br><i class="fa fa-fw fa-close text-danger"></i></p>
										</div>
									</div>
								</div>
							<?php } } ?>
							
							
							<!-- Disposisi 4 -->
							<?php 
							if($disposisi4!=""){
							if($disposisi4==0){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-warning box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi4;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">DIPROSES<br><i class="fa fa-fw fa-refresh text-warning"></i></p>
										</div>
									</div>
								</div>
							<?php } else if($disposisi4==1){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-success box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi4;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">OK<br><i class="fa fa-fw fa-check text-success"></i></p>
										</div>
									</div>
								</div>
							<?php } else if($disposisi4==5){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-primary box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi4;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">PERBAIKI<br><i class="fa fa-fw fa-refresh text-primary"></i></p>
										</div>
									</div>
								</div>
							<?php } else if($disposisi4==2){ ?>
								<div class="col-lg-3 col-xs-12">
									<div class="box box-danger box-solid">
										<div class="box-header with-border">
											<h4><b><center><?php echo $nama_disposisi4;?></center></b></h4>
										</div>
										<div class="box-body">
											<p style="font-size: 130%;text-align:center">DITOLAK<br><i class="fa fa-fw fa-close text-danger"></i></p>
										</div>
									</div>
								</div>
							<?php } } ?>
							
							</div>	
							
							<div class="box-footer">
								<div class="col-md-6">
									<a href="<?php echo base_url();?>telaah/list_telaah/index/<?php echo $this->uri->segment(4);?>" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
								</div>
							</div>
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
