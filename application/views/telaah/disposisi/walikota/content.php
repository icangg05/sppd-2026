  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  	<!-- Content Header (Page header) -->
  	<!-- Main content -->
  	<section class="content">
  		<!-- Small boxes (Stat box) -->
		<?php if($this->uri->segment(4)==8){ ?>
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
					<div id="container" style="min-width: 190px; max-width: 500px; height: 420px; margin: 0 auto"></div>
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
  			<!-- ./col -->
  			<div class="col-lg-12 col-xs-12">
  				<div class="box box-success">
  					<div class="box-header with-border">
  					<h3 class="box-title">
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
  					<div class="box-header with-border">
					<?php echo form_open("walikota/list_telaah/search_result/".$this->uri->segment(4));?>
  						<div class="col-md-8">
								<a href="<?php echo base_url();?>beranda" class="btn btn-warning btn-flat">Kembali</a>
  						</div>
						<?php if ($this->uri->segment(4)==1 || $this->uri->segment(4)==2 
								|| $this->uri->segment(4)==5 || $this->uri->segment(4)==7 || $this->uri->segment(4)==11) {?>
  						<div class="col-md-4">
						  <div class="input-group">
							<select class="form-control select2" name="data">
							
							<?php if ($this->uri->segment(4)==1 || $this->uri->segment(4)==2) {?>
								<option value="">- Pilih OPD-</option>
							<?php } else if ($this->uri->segment(4)==5 || $this->uri->segment(4)==7 ) {?>
								<option value="">- Pilih Kecamatan/Kelurahan -</option>
							<?php } else if ($this->uri->segment(4)==11 ) {?>
								<option value="">- Pilih Puskesmas -</option>
							<?php } ?>
							
							<?php
							  foreach ($skpd as $v) {
								echo '<option value="'.$v->skpd_id.'">'.$v->skpd_nama.'</option>';
							  }
							?>
							</select>
							<span class="input-group-btn">
							  <input type="submit" name="submit" class="btn btn-info btn-flat" value="Go">
							</span>
						  </div>
						</div>
						<?php } ?>
  						<?php echo form_close();?>
  					</div>
  					<!-- /.box-header -->
  					<div class="table-responsive box-body">
  						<p class="alert alert-default text-center"><b>Total Data SPPD: <?php echo $jumlah_data;?></b></p>
  						<p><center>
							<a href="<?php echo base_url();?>walikota/list_telaah/data/<?php echo $this->uri->segment(4);?>" class="btn btn-flat <?php if($this->uri->segment(3)=="data"){ echo "btn-info";}else{ echo "btn-default";}?>">ALL</a>
							<a href="<?php echo base_url();?>walikota/list_telaah/result/<?php echo $this->uri->segment(4);?>/0" class="btn btn-flat <?php if($this->uri->segment(3)=="result" && $this->uri->segment(5)==0){ echo "btn-primary";}else{ echo "btn-default";}?>">MASUK</a>
							<a href="<?php echo base_url();?>walikota/list_telaah/result/<?php echo $this->uri->segment(4);?>/1" class="btn btn-flat <?php if($this->uri->segment(3)=="result" && $this->uri->segment(5)==1){ echo "btn-warning";}else{ echo "btn-default";}?>">DI PROSES</a>
							<a href="<?php echo base_url();?>walikota/list_telaah/result/<?php echo $this->uri->segment(4);?>/2" class="btn btn-flat <?php if($this->uri->segment(3)=="result" && $this->uri->segment(5)==2){ echo "btn-success";}else{ echo "btn-default";}?>">SELESAI</a>
							<a href="<?php echo base_url();?>walikota/list_telaah/result/<?php echo $this->uri->segment(4);?>/3" class="btn btn-flat <?php if($this->uri->segment(3)=="result" && $this->uri->segment(5)==3){ echo "btn-danger";}else{ echo "btn-default";}?>">TIDAK DITERIMA</a>
						</center></p>
  						<div class="table-responsive box-body">
  							<table class="table table-bordered table-striped table-hover">
  								<tr class='info'>
  									<th style="width: 5px">No</th>
  									<th style="width: 40px">Pelaksana</th>
  									<th style="width: 150px">Perihal (Maksud Perjalanan Dinas)</th>
  									<th style="width: 40px">Status</th>
  									<th style="width: 10px">Berangkat</th>
  									<th style="width: 10px">Kembali</th>
  									<th style="width: 100px">Lihat Telaah</th>
  								</tr>
  								<?php 
								if($this->uri->segment(4)==8){
								} else {
									$number=$number+1;
								}
  								foreach($data as $v){
  									$telaah_id = base64_encode($this->encrypt->encode($v->telaah_id, $this->session->userdata('encrypt_key')));	
  									?>
  									<tr>
  										<td><?php echo $number;?></td>
										<?php if ($this->uri->segment(4)==3){ ?>
											<td><?php echo $v->anggotadprd_name?></td>
										<?php } else if ($this->uri->segment(4)==9){ ?>
											<td><?php echo $v->pegawai_nama." <br><br> <b>".$v->nama_subbagian."</b>"?></td>
										<?php } else if ($this->uri->segment(4)==8 || $this->uri->segment(4)==10){ ?>
											<td><?php echo $v->pegawai_nama?></td>
										<?php } else { ?>
											<td><?php echo $v->pegawai_nama." <br><br> <b>".$v->skpd_nama."</b>"?></td>
										<?php } ?>
										
  										<td><?php echo $v->telaah_perihal?></td>
  										
  										<td>
  											<?php
  											if($v->telaah_status==0) {
  												echo '<span class="label label-default">Belum Diterima</span>';
  											} else if($v->telaah_status==1) {
												echo '<span class="label label-warning">Dalam Proses</span>';
  											} else if($v->telaah_status==2) {
  												echo '<span class="label label-success">Selesai</span>';
  											} else if($v->telaah_status==3){
  												echo '<span class="label label-danger">Tidak Diterima</span>';
  											} else if($v->telaah_status==5){
  												echo '<span class="label label-primary">Perbaikan</span>';
  											}
  											?>
											
  										</td>
										<td><?php echo $v->telaah_tanggalberangkat;?></td>
										<td><?php echo $v->telaah_tanggalkembali;?></td>
										<td><a href="<?php echo base_url();?>telaah/disposisi/detail2/<?php echo $this->uri->segment(4)?>?telaah_id=<?php echo $telaah_id?>" class="btn btn-sm btn-block btn-primary"><i class="fa fa-eye"></i> Lihat</a></td>
										
  								</tr>
								
  								<?php 
  								if($this->uri->segment(4)==8){
									$number--;
								} else {
									$number++;
								}
  							} 
  							?>
  						</table>
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
  