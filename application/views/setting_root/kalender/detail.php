 
 	<!-- Content Header (Page header) -->
 	<!-- Main content -->
 	<section class="content">
 		<div class="row">
 			<!-- left column -->
 			<div class="col-md-12">
 				<!-- general form elements -->
 				<div class="box box-primary">
 					<div class="box-header with-border">
 						<h3 class="box-title">DETAIL TELAAH PERJALANAN DINAS</h3>
 					</div>
 					<!-- /.box-header -->
 					<!-- form start -->
 					<div class="table-responsive box-body">
 						<table class="table table-bordered ">
 							<tr class="info">
 								<th class="col-md-3" colspan="2"><center>DATA PERIHAL</center></th>
 							</tr>
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
									} else if ($this->uri->segment(4)=="walikota"){
										echo "<td class='col-md-3'>".$entry[0]['pegawai_nama']."</td>";
									} else {
										echo "<td class='col-md-3'>".$entry[0]['pegawai_nama']."</td>";
										if($entry[0]['telaah_skpd_id'] == 182){ 
											echo "<td class='col-md-3'>".$entry[0]['telaah_jabatan_pelaksana']."</td>";
										} 
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
										} else {
											echo "<td class='col-md-3'>".$v->pegawai_nama."</td>";
											if($entry[0]['telaah_skpd_id'] == 182){ 
												echo "<td class='col-md-3'>".$v->telaah_jabatan_pengikut."</td>";
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