<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  	<!-- Content Header (Page header) -->
  	<!-- Main content -->
  	<section class="content">
	<?php if($this->uri->segment(4)=="kadis") { ?>
		<div class="row">
		<!-- ./col -->                               
		  <div class="col-lg-12 col-xs-12">
			<div class="box box-warning">
				<!-- /.box-header -->
				<div class="box-body">
				  <script src="https://code.highcharts.com/highcharts.js"></script>
				  <script src="https://code.highcharts.com/modules/exporting.js"></script>
				  <script src="https://code.highcharts.com/modules/export-data.js"></script>
				  <div class="col-lg-8 col-xs-8">
					<div id="container5" style="min-width: 190px; max-width: 600px; height: 420px; margin: 0 auto"></div>
				  </div>
				   <!-- ./col -->                                
				  <div class="col-lg-4 col-xs-4">
					<!-- small box -->                                  
					<a href="<?php echo site_url('kadis/detail_anggaran')?>">
					  <div class="small-box bg-aqua">
						<div class="inner">                                 
						  <p>TOTAL ANGGARAN</p>
						  <h2><p>Rp. <?php  echo number_format($sum_all_anggaran_skpd, 0, ',', '.'); ?></p></h2>      
						</div>
					  </div>
					</a>
					<!-- small box -->                                  
					<a href="<?php echo site_url('kadis/detail_anggaran')?>">
					  <div class="small-box bg-red">
						<div class="inner">
						  <p>REALISASI ANGGARAN
						  <b>(<?php echo round($anggaran_terpakai,1)?> %)</p></b>
				  		  <h2><p>Rp. <?php  echo number_format($sum_all_rincian_skpd, 0, ',', '.'); ?></p></h2>
						</div>
					  </div>
					</a>
					<!-- small box -->                                  
					<a href="<?php echo site_url('kadis/detail_anggaran')?>">
					  <div class="small-box bg-green">
						<div class="inner">                                   
						  <p>SISA ANGGARAN
						  <b> ( <?php echo round($anggaran_tersedia,1)?> %)</b>
				  </p>
				  <h2><p>Rp. <?php  echo number_format($sum_all_anggaran_skpd-$sum_all_rincian_skpd, 0, ',', '.'); ?></p></h2>
						</div>
					  </div>
					</a>
				  </div>
				</div>
				<!-- /.box-body -->
			</div>
          <!-- /.box -->
		  </div>                          
		</div>
  		<!-- Small boxes (Stat box) -->	
	<?php } elseif($this->uri->segment(4)=="walikota"){ ?>
		<div class="row">
		  <!-- ./col -->                               
		  <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
			<div class="box box-warning">
				<!-- /.box-header -->
				<div class="box-body">
				  <div class="col-lg-6 col-md-7 col-sm-6 col-xs-6">
					<center><div id="container" style="height: 300px"></div></center>
				  </div>
				   <!-- ./col -->   
				   
				  <!-- ANGGARAN KESELURUHAN -->  
				  <div class="col-lg-6 col-md-5 col-sm-6 col-xs-6">
					<!-- small box -->                                  
					<a href="<?php echo site_url('telaah/detail_anggaran')?>">
					  <div class="small-box bg-aqua">
						<div class="inner">        
						 <p>TOTAL</p> 
						 <h2><p>Rp. <?php echo number_format($total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'], 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
					<!-- small box -->                                  
					<a href="<?php echo site_url('telaah/detail_anggaran')?>">
					  <div class="small-box bg-red">
						<div class="inner">
						  <p>REALISASI
						  <b><?php 
						  $hasil = ($anggaran_terpakai/$total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan']) * 100;
						  echo " (".round($hasil,1)."%)";?></p></b>
						  <h2><p>Rp. <?php echo number_format($anggaran_terpakai, 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
					<!-- small box -->                                  
					<a href="<?php echo site_url('telaah/detail_anggaran')?>">
					  <div class="small-box bg-green">
						<div class="inner">                                   
						  <p>SISA
						  <b><?php 
						  $hasil = (($total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'] - $anggaran_terpakai)/$total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan']) * 100;
						  echo " (".round($hasil,1)."%)";?></p></b>
						  <h2><p>Rp. <?php echo number_format($total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'] - $anggaran_terpakai, 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
				  </div>
				  
				  
				</div>
				<!-- /.box-body -->
			</div>
          <!-- /.box -->
		  </div>  


		  <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
			<div class="box box-warning">
				<!-- /.box-header -->
				<div class="box-body">
				  <div class="col-lg-6 col-md-7 col-sm-6 col-xs-6">
					<center><div id="container3" style="height: 300px"></div></center>
				  </div>
				   <!-- ./col -->                                
				  <!-- ANGGARAN KELUAR DAERAH -->                         
				  <div class="col-lg-6 col-md-5 col-sm-6 col-xs-6">
					<!-- small box -->                                  
					<a href="<?php echo site_url('telaah/detail_anggaran/index2/2')?>">
					  <div class="small-box bg-aqua">
						<div class="inner">              
						 <p>TOTAL</p> 
						 <h2><p>Rp. <?php echo number_format($total_anggaran_luar_daerah[0]['total_anggaran'], 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
					<!-- small box -->                                  
					<a href="<?php echo site_url('telaah/detail_anggaran/index2/2')?>">
					  <div class="small-box bg-red">
						<div class="inner">
						  <p>REALISASI
						  <b><?php 
						  $hasil = ($realisasi_anggaran_luar_daerah/$total_anggaran_luar_daerah[0]['total_anggaran']) * 100;
						  echo " (".round($hasil,1)."%)";?></b> </p>
						  <h2><p>Rp. <?php echo number_format($realisasi_anggaran_luar_daerah, 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
					<!-- small box -->                                  
					<a href="<?php echo site_url('telaah/detail_anggaran/index2/2')?>">
					  <div class="small-box bg-green">
						<div class="inner">                                   
						  <p>SISA
						  <b><?php 
						  $hasil = (($total_anggaran_luar_daerah[0]['total_anggaran'] - $realisasi_anggaran_luar_daerah)/$total_anggaran_luar_daerah[0]['total_anggaran']) * 100;
						  echo " (".round($hasil,1)."%)";?></b></p>
						  <h2><p>Rp. <?php echo number_format($total_anggaran_luar_daerah[0]['total_anggaran'] - $realisasi_anggaran_luar_daerah, 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
				  </div>
				  
				</div>
				<!-- /.box-body -->
			</div>
          <!-- /.box -->
		  </div> 
		  
		  
		  <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
			<div class="box box-warning">
				<!-- /.box-header -->
				<div class="box-body">
				  <div class="col-lg-6 col-md-7 col-sm-6 col-xs-6">
					<!-- <center><div id="container2" style="height: 300px"></div></center> -->
				  </div>
				  
				  <!-- ANGGARAN DALAM DAERAH -->                
				  <div class="col-lg-6 col-md-5 col-sm-6 col-xs-6">
					<!-- small box -->                                  
					<a href="<?php echo site_url('telaah/detail_anggaran/index2/1')?>">
					  <div class="small-box bg-aqua">
						<div class="inner">                            
						 <p>TOTAL</p> 
						 <h2><p>Rp. <?php echo number_format($total_anggaran_dalam_daerah[0]['total_anggaran'], 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
					<!-- small box -->                                  
					<a href="<?php echo site_url('telaah/detail_anggaran/index2/1')?>">
					  <div class="small-box bg-red">
						<div class="inner">
						  <p>REALISASI
						  <b><?php 
						  $hasil = ($realisasi_anggaran_dalam_daerah/$total_anggaran_dalam_daerah[0]['total_anggaran']) * 100;
						  echo " (".round($hasil,1)."%)";?></p></b>
						  <h2><p>Rp. <?php echo number_format($realisasi_anggaran_dalam_daerah, 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
					<!-- small box -->                                  
					<a href="<?php echo site_url('telaah/detail_anggaran/index2/1')?>">
					  <div class="small-box bg-green">
						<div class="inner">                                   
						  <p>SISA
						  <b><?php 
						  $hasil = (($total_anggaran_dalam_daerah[0]['total_anggaran'] - $realisasi_anggaran_dalam_daerah)/$total_anggaran_dalam_daerah[0]['total_anggaran']) * 100;
						  echo " (".round($hasil,1)."%)";?></p></b>
						  <h2><p>Rp. <?php echo number_format($total_anggaran_dalam_daerah[0]['total_anggaran'] - $realisasi_anggaran_dalam_daerah, 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
				  </div>
				  
				</div>
				<!-- /.box-body -->
			</div>
          <!-- /.box -->
		  </div>
		  
		  
		  <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
			<div class="box box-warning">
				<!-- /.box-header -->
				<div class="box-body">
				  <div class="col-lg-6 col-md-7 col-sm-6 col-xs-6">
					<center><div id="container4" style="height: 300px"></div></center>
				  </div>
				  
				  <!-- ANGGARAN BIMTEK -->                                
				  <div class="col-lg-6 col-md-5 col-sm-6 col-xs-6">
					<!-- small box -->                                  
					<a href="<?php echo site_url('telaah/detail_anggaran/index2/3')?>">
					  <div class="small-box bg-aqua">
						<div class="inner">                                 
						  <p>TOTAL</p> 
						 <h2><p>Rp. <?php echo number_format($total_anggaran_bimtek[0]['total_anggaran'], 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
					<!-- small box -->                                  
					<a href="<?php echo site_url('telaah/detail_anggaran/index2/3')?>">
					  <div class="small-box bg-red">
						<div class="inner">
						  <p>REALISASI
						  <b><?php 
						  $hasil = ($realisasi_anggaran_bimtek/$total_anggaran_bimtek[0]['total_anggaran']) * 100;
						  echo " (".round($hasil,1)."%)";?></p></b>
						  <h2><p>Rp. <?php echo number_format($realisasi_anggaran_bimtek, 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
					<!-- small box -->                                  
					<a href="<?php echo site_url('telaah/detail_anggaran/index2/3')?>">
					  <div class="small-box bg-green">
						<div class="inner">                                   
						  <p>SISA
						  <b><?php 
						  $hasil = (($total_anggaran_bimtek[0]['total_anggaran'] - $realisasi_anggaran_bimtek)/$total_anggaran_bimtek[0]['total_anggaran']) * 100;
						  echo " (".round($hasil,1)."%)";?></p></b>
						  <h2><p>Rp. <?php echo number_format($total_anggaran_bimtek[0]['total_anggaran'] - $realisasi_anggaran_bimtek, 0, ',', '.');?></p></h2>
						</div>
					  </div>
					</a>
				  </div>
				  
				</div>
				<!-- /.box-body -->
			</div>
          <!-- /.box -->
		  </div>     
		  
		   
		</div>
	<?php } ?>
	
  		<div class="row">
  			<!-- ./col -->
  			<div class="col-lg-12 col-xs-12">
  				<div class="box box-success">
  					<div class="box-header with-border">
  						<h3 class="box-title">LIST TELAAH MASUK</h3>
  					</div>
  					<div class="box-header with-border">
  						<?php echo form_open('telaah/disposisi/search/'.$this->uri->segment(4)); ?>
  						<div class="col-md-9">
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
  						<table class="table table-bordered table-striped table-hover">
  							<tr class='info'>
  								<th style="width: 5px">No</th>
  								<th style="width: 40px">Tanggal Pengajuan</th>
  								<th style="width: 200px">Pelaksana Perjalanan Dinas</th>
  								<th style="width: 300px">Jabatan</th>
  								<th style="width: 300px">Perihal (Maksud Perjalanan Dinas)</th>
								<?php if(($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 3 && $this->uri->segment(4)!='walikota2') 
									|| $this->uri->segment(4)=='asisten' 
									|| ($this->uri->segment(4)=='sekdis' && $this->ion_auth->user()->row()->jenis_skpd != 10)
									|| ($this->uri->segment(4)=='kadis' && $this->ion_auth->user()->row()->jenis_skpd != 10)){
									echo "<th style='width: 40px'>Bagian</th>";
								} if($this->uri->segment(4)=='walikota' 
									|| $this->uri->segment(4)=='sekda' 
									|| $this->uri->segment(4)=='sekcam' 
									|| $this->uri->segment(4)=='camat' 
									|| ($this->uri->segment(4)=='kabid' && $this->ion_auth->user()->row()->jenis_skpd == 10)
									|| ($this->uri->segment(4)=='sekdis' && $this->ion_auth->user()->row()->jenis_skpd == 10)
									|| ($this->uri->segment(4)=='kadis' && $this->ion_auth->user()->row()->jenis_skpd == 10)){
									echo "<th style='width: 40px'>OPD</th>";
								}?>
  								<th style="width: 100px">Status</th>
  								<th style="width: 20px">Aksi</th>
  							</tr>
  							<?php 
  							$number=$number+1;
					//var_dump($telaah_staf);
  							foreach($telaah_staf as $v){
  								$telaah_id = base64_encode($this->encrypt->encode($v->telaah_id, $this->session->userdata('encrypt_key')));	
  								$telaah_kategori = base64_encode($this->encrypt->encode($v->telaah_kategori, $this->session->userdata('encrypt_key')));	
  								?>
  								<tr>

  									<td><?php echo $number?></td>
  									<td><?php echo $v->telaah_waktuinput; ?></td>
  									<?php if ($this->ion_auth->user()->row()->jenis_skpd==2){?>
										<?php if($v->anggotadprd_name==""){?>
											<td><?php echo $v->pegawai_nama; ?></td>
											<td><?php echo $v->pegawai_namajabatan; ?></td>
										<?php }else{ ?>
											<td><?php echo $v->anggotadprd_name; ?></td>
											<td><?php echo $v->anggotadprd_jabatan; ?></td>
										<?php } ?>
  									<?php } else {?>
										<td><?php echo $v->pegawai_nama; ?></td>
										<?php if($this->ion_auth->user()->row()->skpd_id == 182){ ?>
											<?php if($v->telaah_jabatan_pelaksana==1){
												echo "<td class='col-md-3'>Penanggung Jawab</td>";
											} else if($v->telaah_jabatan_pelaksana==2){
												echo "<td class='col-md-3'>Pembantu Penanggung Jawab</td>";
											} else if($v->telaah_jabatan_pelaksana==3){
												echo "<td class='col-md-3'>Pengendali Teknis</td>";
											} else if($v->telaah_jabatan_pelaksana==4){
												echo "<td class='col-md-3'>Ketua Tim</td>";
											} else if($v->telaah_jabatan_pelaksana==5){
												echo "<td class='col-md-3'>Anggota</td>";
											} else if($v->telaah_jabatan_pelaksana==6){
												echo "<td class='col-md-3'>Admin Tim</td>";
											}
											?>
										<?php } else { ?>
											<td><?php echo $v->pegawai_namajabatan; ?></td>
										<?php } ?>
  									<?php } ?>
  									<td><?php echo $v->telaah_perihal?></td>
									
									<!--###-->
									<?php if(($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 3) 
											|| $this->uri->segment(4)=='asisten'){ ?>
									
										<?php 
										if($v->telaah_kategori==4){
											echo "<td><span class='label label-danger'>Sekda, Asisten & Kabag</span></td>";
										} else if($v->telaah_kategori==8){
											echo "<td><span class='label label-success'>WALIKOTA / WAKIL WALIKOTA</span></td>";
										} else {
											echo "<td><span class='label label-warning'>Kasubag & Staf</span><br>";
											echo $v->nama_subbagian."</td>";
										} ?>
									
									<?php } ?>
									
									<!--###-->
									<?php if(($this->uri->segment(4)=='kadis' && $this->ion_auth->user()->row()->jenis_skpd != 10) 
											|| ($this->uri->segment(4)=='sekdis' && $this->ion_auth->user()->row()->jenis_skpd != 10)){
										if($v->telaah_kategori==2){
											echo "<td><span class='label label-danger'>Kepala OPD</span></td>";
										} else {
											echo "<td><span class='label label-warning'>Esselon III, IV dan Staff</span></td>";
										}
									}?>
									
									<!--###-->
									<?php if(($this->uri->segment(4)=='kadis' && $this->ion_auth->user()->row()->jenis_skpd == 10)
											|| ($this->uri->segment(4)=='sekdis' && $this->ion_auth->user()->row()->jenis_skpd == 10)
											|| ($this->uri->segment(4)=='kabid' && $this->ion_auth->user()->row()->jenis_skpd == 10)){
										if($v->jenis_skpd==7){
											echo "<td><span class='label label-warning'>".$v->skpd_nama."</span></td>";
										} else {
											echo "<td><span class='label label-primary'>".$v->skpd_nama."</span></td>";
										}
									}?>
									
									<!--###-->
									<?php if($this->uri->segment(4)=='sekcam' || $this->uri->segment(4)=='camat'){
										if($v->jenis_skpd==5){
											echo "<td><span class='label label-warning'>".$v->skpd_nama."</span></td>";
										} else {
											echo "<td><span class='label label-primary'>".$v->skpd_nama."</span></td>";
										}
									}?>
									
									<!--###-->
									<?php if($this->uri->segment(4)=='sekda' || $this->uri->segment(4)=='walikota'){
										if($v->telaah_kategori == 2){
											echo "<td><span class='label label-primary'>".$v->skpd_nama."</span></td>";
										} else if($v->telaah_kategori == 4){
											echo "<td><span class='label label-warning'>Sekda, Asisten & Kabag</span></td>";
										} else if($v->telaah_kategori == 5){
											echo "<td><span class='label label-success'>".$v->skpd_nama."</span></td>";
										} else if($v->telaah_kategori==8){
											echo "<td><span class='label label-success'>WALIKOTA / WAKIL WALIKOTA</span></td>";
										} else if($v->telaah_kategori == 9){
											echo "<td><span class='label label-danger'>Kasubag & Staf</span></td>";
										} else if($v->telaah_kategori == 10){
											echo "<td><span class='label label-info'>".$v->skpd_nama."</span></td>";
										}
									}?>
									
  									<td>
									<?php if($v->telaah_perbaikan == 1){
										echo "<span class='label label-info'>Laporan Diperbaiki</span>";
									} else {
										if($v->telaah_status == 5){
											echo "<span class='label label-primary'>Perbaikan</span>";
										} else {
											echo "<span class='label label-danger'>Laporan belum dientrikan</span>";
										}
									}?>
									</td>
  									<td><a href="<?php echo base_url();?>telaah/disposisi/lihat_laporan/<?php echo $this->uri->segment(4)?>/<?php echo $v->telaah_kategori?>?telaah_id=<?php echo $telaah_id?>" class="btn btn-sm btn-block btn-primary">Detail Telaah Staff</a></td>
  								</tr>
  								<?php 
  								$number++;
  							} 
  							?>
  						</table>
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
  
<?php if($this->uri->segment(4)=="walikota"){ ?>
  <script>
Highcharts.chart('container', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Anggaran Keseluruhan'
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
	credits: {
		enabled: false
	},
	exporting: { 
		enabled: false 
	},
    series: [{
        name: 'Anggaran',
        colorByPoint: true,
        data: [{
            name: '',
            y: 	<?php 
				  $hasil = (($total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan'] - $anggaran_terpakai)/$total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan']) * 100;
				  echo round($hasil,2);?>,
			color: '#00a65a',
            sliced: true,
            selected: true
        }, {
            name: '',
            y: <?php 
				  $hasil = ($anggaran_terpakai/$total_anggaran_keseluruhan[0]['total_anggaran_keseluruhan']) * 100;
				  echo round($hasil,2);?>,
			color: '#dd4b39',
        }]
    }]
});
</script>
<script>
Highcharts.chart('container2', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Anggaran Dalam Daerah'
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
	credits: {
		enabled: false
	},
	exporting: { 
		enabled: false 
	},
    series: [{
        name: 'Anggaran',
        colorByPoint: true,
        data: [{
            name: '',
            y: 	<?php 
				  $hasil = (($total_anggaran_dalam_daerah[0]['total_anggaran'] - $realisasi_anggaran_dalam_daerah)/$total_anggaran_dalam_daerah[0]['total_anggaran']) * 100;
				  echo round($hasil,2);?>,
			color: '#00a65a',
            sliced: true,
            selected: true
        }, {
            name: '',
            y: <?php 
				  $hasil = ($realisasi_anggaran_dalam_daerah/$total_anggaran_dalam_daerah[0]['total_anggaran']) * 100;
				  echo round($hasil,2);?>,
			color: '#dd4b39',
        }]
    }]
});
</script>
<script>
Highcharts.chart('container3', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Anggaran Luar Daerah'
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
	credits: {
		enabled: false
	},
	exporting: { 
		enabled: false 
	},
    series: [{
        name: 'Anggaran',
        colorByPoint: true,
        data: [{
            name: '',
            y: 	<?php 
				  $hasil = (($total_anggaran_luar_daerah[0]['total_anggaran'] - $realisasi_anggaran_luar_daerah)/$total_anggaran_luar_daerah[0]['total_anggaran']) * 100;
				  echo round($hasil,2);?>,
			color: '#00a65a',
            sliced: true,
            selected: true
        }, {
            name: '',
            y: <?php 
				  $hasil = ($realisasi_anggaran_luar_daerah/$total_anggaran_luar_daerah[0]['total_anggaran']) * 100;
				  echo round($hasil,2);?>,
			color: '#dd4b39',
        }]
    }]
});
</script>
<script>
Highcharts.chart('container4', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Anggaran Bimtek'
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
	credits: {
		enabled: false
	},
	exporting: { 
		enabled: false 
	},
    series: [{
        name: 'Anggaran',
        colorByPoint: true,
        data: [{
            name: '',
            y: 	<?php 
				  $hasil = (($total_anggaran_bimtek[0]['total_anggaran'] - $realisasi_anggaran_bimtek)/$total_anggaran_bimtek[0]['total_anggaran']) * 100;
				  echo round($hasil,2);?>,
			color: '#00a65a',
            sliced: true,
            selected: true
        }, {
            name: '',
            y: <?php 
				  $hasil = ($realisasi_anggaran_bimtek/$total_anggaran_bimtek[0]['total_anggaran']) * 100;
				  echo round($hasil,2);?>,
			color: '#dd4b39',
        }]
    }]
});
</script>

<?php } ?>
<?php if($this->uri->segment(4)=="kadis"){ ?>
<script>
Highcharts.chart('container5', {
    chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
    },
    title: {
        text: 'Anggaran Keseluruhan'
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
	credits: {
		enabled: false
	},
	exporting: { 
		enabled: false 
	},
    series: [{
        name: 'Anggaran',
        colorByPoint: true,
        data: [{
            name: '',
            y: 	<?php echo $anggaran_tersedia;?>,
			color: '#00a65a',
            sliced: true,
            selected: true
        }, {
            name: '',
            y: <?php echo $anggaran_terpakai;?>,
			color: '#dd4b39',
        }]
    }]
});
</script>
<?php } ?>