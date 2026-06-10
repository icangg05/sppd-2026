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
					<a href="<?php echo site_url('telaah/detail_anggaran')?>">
					  <div class="small-box bg-aqua">
						<div class="inner">                                        
						  <p>TOTAL ANGGARAN</p>
						  <h2><p>Rp. <?php echo number_format($total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'], 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
					<!-- small box -->                                  
					<a href="<?php echo site_url('telaah/detail_anggaran')?>">
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
					<a href="<?php echo site_url('telaah/detail_anggaran')?>">
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
					<div class="box-body">
					<?php
					 $message = $this->session->flashdata('notif');
					 if($message){
					   echo '<p class="alert alert-danger text-center"><b>'.$message .'</b></p>';
					 }
					?>
						<div class="col-md-8">
						  <!-- Custom Tabs -->
						  <div class="nav-tabs-custom" id="hasil">
							<ul class="nav nav-tabs">
							  <li class="active"><a href="#tab_1" data-toggle="tab">SPT</a></li>
							  <li><a href="#tab_2" data-toggle="tab">SPPD</a></li>
							</ul>
							<div class="tab-content">
							<div class="pull-right"><a href="<?php echo base_url();?>telaah/disposisi/detail/<?php echo $this->uri->segment(4)?>/<?php echo $this->uri->segment(5)?>?telaah_id=<?php echo $this->input->get('telaah_id')?>" class="btn btn-primary"> Lihat Detail Perjalanan</a></div><br><br>
							  <div class="tab-pane active" id="tab_1">
								<object data="<?php echo base_url();?>upload/doc_perjalanan/SPT-<?php echo $telaah_id;?>-<?php echo $entry[0]['pegawai_id']; ?>.pdf" type="application/pdf" width="100%" height="700px">
									<p>Preview dokumen tidak tersedia. Silakan <a href="<?php echo base_url();?>upload/doc_perjalanan/SPT-<?php echo $telaah_id;?>-<?php echo $entry[0]['pegawai_id']; ?>.pdf" target="_blank">Download Dokumen SPT</a>.</p>
								</object>
							  </div>
							  <!-- /.tab-pane -->
							  <div class="tab-pane" id="tab_2">
								<object data="<?php echo base_url();?>upload/doc_perjalanan/SPPD-<?php echo $telaah_id;?>-<?php echo $entry[0]['pegawai_id']; ?>.pdf" type="application/pdf" width="100%" height="700px">
									<p>Preview dokumen tidak tersedia. Silakan <a href="<?php echo base_url();?>upload/doc_perjalanan/SPPD-<?php echo $telaah_id;?>-<?php echo $entry[0]['pegawai_id']; ?>.pdf" target="_blank">Download Dokumen SPPD</a>.</p>
								</object>
							  </div>
							</div>
						  </div>
						</div><br><br><br>
						<div class="col-md-4">
						  <table class="table table-bordered ">
								<tr class="info">
 									<th class="col-md-12">Pelaksana</th>
 								</tr>
								<tr>
 									<td> 
										<?php 
										$posisi = $this->uri->segment(4);
										$kategori = $this->uri->segment(5);
										$pelaksana = $entry[0]['pegawai_id'];
										$telaah_id = $entry[0]['telaah_id'];
										$telaah_id2 = $this->input->get('telaah_id');
										if($this->uri->segment(5)==3){
											echo '<div class="col-md-8">';
											echo $entry[0]['anggotadprd_name'];
											echo '</div>';
											echo '<div class="col-md-4">';
											echo "<button class='btn btn-warning btn-sm' onclick='tampilkan_nama()'>Lihat Laporan</button><br>";
											echo '</div>';
											echo "<input type='hidden' class'form-control' id='posisi' value='$posisi'>";
											echo "<input type='hidden' class'form-control' id='kategori' value='$kategori'>";
											echo "<input type='hidden' class'form-control' id='telaah' value='$telaah_id'>";
											echo "<input type='hidden' class'form-control' id='pegawai' value='$pelaksana'>";
											echo "<input type='hidden' class'form-control' id='pelaksana' value='$pelaksana'>";
										} else {
											echo '<div class="col-md-8">';
											echo $entry[0]['pegawai_nama'];
											echo '</div>';
											echo '<div class="col-md-4">';
											echo "<button class='btn btn-warning btn-sm' onclick='tampilkan_nama()'>Lihat Laporan</button><br>";
											echo '</div>';
											echo "<input type='hidden' class'form-control' id='posisi' value='$posisi'>";
											echo "<input type='hidden' class'form-control' id='kategori' value='$kategori'>";
											echo "<input type='hidden' class'form-control' id='telaah' value='$telaah_id'>";
											echo "<input type='hidden' class'form-control' id='pegawai' value='$pelaksana'>";
											echo "<input type='hidden' class'form-control' id='pelaksana' value='$pelaksana'>";
										}
										 ?>
										<script>
											function tampilkan_nama(){
												posisi = document.getElementById("posisi").value;
												kategori = document.getElementById("kategori").value;
												telaah = document.getElementById("telaah").value;
												pegawai = document.getElementById("pegawai").value;
												pelaksana = document.getElementById("pelaksana").value;
												 $.ajax({
													 url:"<?php echo base_url();?>telaah/disposisi/tampilkan_laporan_sementara/"+posisi+"/"+kategori+"/"+telaah+"/"+pelaksana+"/"+pegawai+"",
													success: function(response){
														$("#hasil").html(response);
													}
												 });
												return false;
											}
										</script>
									</td>
 								</tr>
						  </table>
						  <?php if(count($pengikut)>0) { ?>
						  <table class="table table-bordered ">
								<tr class="info">
 									<th class="col-md-12">Pengikut</th>
 								</tr>
								<tr>
 									<td><?php $no = 1;
											foreach($pengikut as $v): 
											
											if($this->uri->segment(5)==3){
												echo '<div class="col-md-8">';
												echo $no++.". ";
												echo $v->anggotadprd_name;
												echo '</div>';
												echo '<div class="col-md-4">';
												echo "<button class='btn btn-warning btn-sm' onclick='tampilkan_nama$no()'>Lihat Laporan</button><br><br>";
												echo '</div>';
												echo "<input type='hidden' class'form-control' id='posisi$no' value='$posisi'>";
												echo "<input type='hidden' class'form-control' id='kategori$no' value='$kategori'>";
												echo "<input type='hidden' class'form-control' id='telaah$no' value='$v->telaah_id'>";
												echo "<input type='hidden' class'form-control' id='pegawai$no' value='$v->pegawai_id'>";
												echo "<input type='hidden' class'form-control' id='pelaksana$no' value='$pelaksana'>";
											} else {
												echo '<div class="col-md-8">';
												echo $no++.". ";
												echo $v->pegawai_nama;
												echo '</div>';
												echo '<div class="col-md-4">';
												echo "<button class='btn btn-warning btn-sm' onclick='tampilkan_nama$no()'>Lihat Laporan</button><br><br>";
												echo '</div>';
												echo "<input type='hidden' class'form-control' id='posisi$no' value='$posisi'>";
												echo "<input type='hidden' class'form-control' id='kategori$no' value='$kategori'>";
												echo "<input type='hidden' class'form-control' id='telaah$no' value='$v->telaah_id'>";
												echo "<input type='hidden' class'form-control' id='pegawai$no' value='$v->pegawai_id'>";
												echo "<input type='hidden' class'form-control' id='pelaksana$no' value='$pelaksana'>";
											}
										?>
										<script>
											function tampilkan_nama<?= $no ?>(){
												posisi = document.getElementById("posisi<?php echo $no?>").value;
												kategori = document.getElementById("kategori<?php echo $no?>").value;
												telaah = document.getElementById("telaah<?php echo $no?>").value;
												pegawai = document.getElementById("pegawai<?php echo $no?>").value;
												pelaksana = document.getElementById("pelaksana<?php echo $no?>").value;
												 $.ajax({
													 url:"<?php echo base_url();?>telaah/disposisi/tampilkan_laporan_sementara/"+posisi+"/"+kategori+"/"+telaah+"/"+pelaksana+"/"+pegawai+"",
													success: function(response){
														$("#hasil").html(response);
													}
												 });
												return false;
											}
										</script>
										<?php endforeach; ?>
									</td>
 								</tr>
						  </table>
						  <?php } ?>
						  <?php if(($this->input->get('telaah_disetujui')==0) && ($this->input->get('telaah_ditolak')==0)) { ?>
						  <table class="table table-bordered ">
								<tr class="">
 									<th class="col-md-12">PERSETUJUAN</th>
 								</tr>
								<tr>
 									<th class="col-md-12">
										<?php 
										if(	($this->uri->segment(5)=="1" && $this->uri->segment(4)=="kadis") ||
											($this->uri->segment(5)=="2" && $this->uri->segment(4)=="kadis") ||
											($this->uri->segment(5)=="2" && $this->uri->segment(4)=="walikota" && $entry[0]['telaah_domainperjalanan']!=3) ||
											($this->uri->segment(5)=="3" && $this->uri->segment(4)=="sekwan")||
											($this->uri->segment(5)=="3" && $this->uri->segment(4)=="kadprd")||
											($this->uri->segment(5)=="4" && $this->uri->segment(4)=="sekda")||
											($this->uri->segment(5)=="4" && $this->uri->segment(4)=="walikota")||
											($this->uri->segment(5)=="5" && $this->uri->segment(4)=="camat")||
											// ($this->uri->segment(5)=="5" && $entry[0]['jenis_skpd']==6 && $this->uri->segment(4)=="walikota")||
											($this->uri->segment(5)=="5" && $this->uri->segment(4)=="sekda")||
											($this->uri->segment(5)=="6" && $this->uri->segment(4)=="sekwan")||
											// ($this->uri->segment(5)=="7" && $entry[0]['jenis_skpd']==4 && $this->uri->segment(4)=="camat")||
											($this->uri->segment(5)=="7" && $this->uri->segment(4)=="camat")||
											// ($this->uri->segment(5)=="7" && $this->uri->segment(4)=="lurah")||
											($this->uri->segment(5)=="8" && $this->ion_auth->get_users_groups()->row()->id == 6)||
											($this->uri->segment(5)=="8" && $this->ion_auth->get_users_groups()->row()->id == 8)||
											($this->uri->segment(5)=="9" && $this->uri->segment(4)=="sekda")||
											($this->uri->segment(5)=="10" && $this->uri->segment(4)=="sekwan")||
											($this->uri->segment(5)=="10" && $this->uri->segment(4)=="walikota")||
											($this->uri->segment(5)=="11" && $this->uri->segment(4)=="kapus")
											){
											echo "<a href='#' class='btn btn-success btn-lg btn-block' data-toggle='modal' data-target='#passphrase'>TERIMA</a>";
										} else {
											echo "<a href='' class='btn btn-success btn-lg btn-block' data-toggle='modal' data-target='#terima'>TERIMA</a>";
										}
										?>
										<a href="" class="btn btn-danger btn-lg btn-block" data-toggle="modal" data-target="#tolak">TOLAK</a>
										<a href="" class="btn btn-warning btn-lg btn-block" data-toggle="modal" data-target="#perbaiki">PERBAIKI</a>
									</th>
 								</tr>
								
								<!-- Modal -->
								<?php $this->load->view('telaah/disposisi/lihat_laporan/modal_terima');?>
								<?php $this->load->view('telaah/disposisi/lihat_laporan/modal_tolak');?>
								<?php $this->load->view('telaah/disposisi/lihat_laporan/modal_perbaikan');?>
								<?php $this->load->view('telaah/disposisi/lihat_laporan/modal_passphrase');?>
								<!-- Modal -->
						  </table>
						  <?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<!-- /.content-wrapper -->
<script>
	if(document.getElementById('ckeditor')){
		var ckeditor = CKEDITOR.replace('ckeditor');
	}
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
if (typeof Highcharts !== 'undefined' && document.getElementById('container')) {
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
}
</script>
