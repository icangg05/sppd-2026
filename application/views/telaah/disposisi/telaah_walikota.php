  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  	<!-- Content Header (Page header) -->
  	<!-- Main content -->
  	<section class="content">
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
  								<th style="width: 100px">Status</th>
  								<th style="width: 20px">Aksi</th>
  							</tr>
  							<?php 
  							$number=$number+1;
  							foreach($telaah_staf as $v){
  								$telaah_id = base64_encode($this->encrypt->encode($v->telaah_id, $this->session->userdata('encrypt_key')));	
  								$telaah_kategori = base64_encode($this->encrypt->encode($v->telaah_kategori, $this->session->userdata('encrypt_key')));	
  								?>
  								<tr>

  									<td><?php echo $number?></td>
  									<td><?php echo $v->telaah_waktuinput; ?></td>
									<td><?php echo $v->pegawai_nama; ?></td>
									<td><?php echo $v->pegawai_namajabatan; ?></td>
  									<td><?php echo $v->telaah_perihal?></td>
  									<td>
									<?php 
									$status_perjalanan_walikota = $this->m_walikota->status_perjalanan_walikota($v->telaah_id);
									if($this->ion_auth->get_users_groups()->row()->id==2){
										if($status_perjalanan_walikota[0]['timeline_kabag_id']==0){
											echo "<span class='label label-danger'>Laporan belum dientrikan</span>";
										} else if($status_perjalanan_walikota[0]['timeline_kabag_id']==5){
											echo "<span class='label label-primary'>Perbaikan</span>";
										} else if($status_perjalanan_walikota[0]['timeline_kabag_id']==1){
											echo "<span class='label label-success'>ACC</span>";
										} 
									} else if($this->ion_auth->get_users_groups()->row()->id==6){
										if($status_perjalanan_walikota[0]['timeline_sekda_id']==0){
											echo "<span class='label label-danger'>Laporan belum dientrikan</span>";
										} else if($status_perjalanan_walikota[0]['timeline_sekda_id']==5){
											echo "<span class='label label-primary'>Perbaikan</span>";
										} else if($status_perjalanan_walikota[0]['timeline_sekda_id']==1){
											echo "<span class='label label-success'>ACC</span>";
										} 
									} else if($this->ion_auth->get_users_groups()->row()->id=8){
										if($status_perjalanan_walikota[0]['timeline_walikota_id']==0){
											echo "<span class='label label-danger'>Laporan belum dientrikan</span>";
										} else if($status_perjalanan_walikota[0]['timeline_walikota_id']==5){
											echo "<span class='label label-primary'>Perbaikan</span>";
										} else if($status_perjalanan_walikota[0]['timeline_walikota_id']==1){
											echo "<span class='label label-success'>ACC</span>";
										} 
									}
									/*if($v->telaah_perbaikan == 1){
										echo "<span class='label label-info'>Laporan Diperbaiki</span>";
									} else {
										if($v->telaah_status == 5){
											echo "<span class='label label-primary'>Perbaikan</span>";
										} else {
											echo "<span class='label label-danger'>Laporan belum dientrikan</span>";
										}
									}*/
									?>
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