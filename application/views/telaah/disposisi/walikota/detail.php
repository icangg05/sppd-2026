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
 						<h3 class="box-title">DETAIL TELAAH STAF 
 							<?php 
							switch($this->uri->segment(4)){
								case "1" 				: echo "TELAAH STAF (Esselon III, IV dan Staff)"; break;
								case "2" 				: echo "TELAAH STAF (Kepala OPD)"; break;
								case "3" 				: echo "TELAAH STAF (DPRD)"; break;
								case "4" 				: echo "TELAAH STAF (Sekda, Asisten dan Kabag)"; break;
								case "5" 				: echo "TELAAH STAF (Camat & Lurah)"; break;
								case "staff_dprd" 		: echo "TELAAH STAF (Staff DPRD)"; break;
								case "staff_camat" 		: echo "TELAAH STAF (Staff Camat)"; break;
								case "staff_lurah" 		: echo "TELAAH STAF (Staff Lurah)"; break;
								case "8" 				: echo "TELAAH STAF (Walikota)"; break;
								case "staff_setda" 		: echo "TELAAH STAF (Kasubag dan Staff Setda)"; break;
								case "sekwan" 			: echo "TELAAH STAF (Sekwan)"; break;
								case "kapus" 			: echo "TELAAH STAF (Kepala Puskesmas)"; break;
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
 									<td><?php echo $entry[0]['telaah_kepada']; ?></td>
 								</tr>
 								<tr>
 									<th class="col-md-3">Dari</th>
 									<td><?php 
 										if($this->uri->segment(4)==6){
 											echo $entry[0]['pegawai_namajabatan'];
 										}else if($this->uri->segment(4)==3){
 											echo "SEKRETARIAT DPRD"; 
 										}else{
 											echo "".$entry[0]['pegawai_nama']." || ".$entry[0]['pegawai_namajabatan']." || ".$entry[0]['skpd_nama']; ; 
 										}
 										
 										?></td>
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
 								<!--<tr>
 									<th class="col-md-3">Saran</th>
 									<td>Saran</td>
 								</tr>
 								<tr>
 									<th class="col-md-3">Biaya yang ditimbulkan dibebankan kepada</th>
 									<td></td>
 								</tr>-->
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
								<td><a href="<?php echo base_url();?>walikota/list_telaah/history?pegawai_id=<?php echo $pelaksana; ?>" target="_blank">Lihat History</a></td>
							</tr>
							<?php foreach($pengikut as $v){ 
								$pegawai_id = base64_encode($this->encrypt->encode($v->pegawai_id, $this->session->userdata('encrypt_key')));	
								?>
								<tr>
									<td class="col-md-3"><?php echo $v->pegawai_nama; ?></td>
									<td><a href="<?php echo base_url();?>walikota/list_telaah/history?pegawai_id=<?php echo $pegawai_id; ?>" target="_blank">Lihat History</a></td>
								</tr>
								<?php } ?>
							</table>
						</div>
						<!-- /.box-body -->
					</div>
					<!-- /.box -->
				</div>
				<!--/.col (left) -->
				<!-- left column -->
				<div class="col-md-12">
					<a href="<?php echo base_url();?>telaah/disposisi/data/<?php echo $this->uri->segment(4);?>" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
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
