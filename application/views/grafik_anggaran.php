<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
  <div class="box box-primary">
	<div class="box-header with-border">
	  <h3 class="box-title">Anggaran Keseluruhan</h3>
	</div>
	<!-- /.box-header -->
	<!-- form start -->
	  <div class="box-body">
		<div class="row">
		  <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
			<div id="admin_opd" style="height: 400px"></div>
		  </div>
		  
		  <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
			<br class="visible-sm visible-xs">
			<!-- small box -->   
			
			<a href="<?php if($this->ion_auth->get_users_groups()->row()->id == 4 ) { ?>
					#
					<?php } else { ?>
						<?php echo base_url();?>setting_admin/anggaran
					<?php } ?> ">
			  <div class="small-box bg-aqua">
				<div class="inner">                                        
				  <p>TOTAL ANGGARAN</p>
				  <h2>Rp. <?php  echo number_format($sum_all_anggaran_skpd, 0, ',', '.'); ?></h2>
				</div>
			  </div>
			 </a>
			<!-- small box -->        
			<a href="<?php if($this->ion_auth->get_users_groups()->row()->id == 4 ) { ?>
					#
					<?php } else { ?>
						<?php echo base_url();?>setting_admin/anggaran
					<?php } ?> ">                          
			  <div class="small-box bg-red">
				<div class="inner">                                      
				  <p>REALISASI ANGGARAN 
				  <b>(<?php echo round($anggaran_terpakai,1)?> %)</b></p>
				  <h2>Rp. <?php  echo number_format($sum_all_rincian_skpd, 0, ',', '.'); ?></h2>
				</div>
			  </div>
			 </a>
			<!-- small box -->          
			<a href="<?php if($this->ion_auth->get_users_groups()->row()->id == 4 ) { ?>
					#
					<?php } else { ?>
						<?php echo base_url();?>setting_admin/anggaran
					<?php } ?> ">                        
			  <div class="small-box bg-green">
				<div class="inner">                                       
				  <p>SISA ANGGARAN
				  <b> ( <?php echo round($anggaran_tersedia,1)?> %)</b>
				  </p>
				  <h2>Rp. <?php  echo number_format($sum_all_anggaran_skpd-$sum_all_rincian_skpd, 0, ',', '.'); ?></h2>
				</div>
			  </div>
			 </a>
		  </div>
		</div>
		<!-- /.box-body -->
	  </div>
	<br> <!-- /.box -->
  </div>
</div>

<script>
  Highcharts.chart('admin_opd', {
	chart: {
		plotBackgroundColor: null,
		plotBorderWidth: null,
		plotShadow: false,
		type: 'pie'
	},
	title: {
		text: ''
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
				name: 'Anggaran Tersedia',
				y: 	<?php echo $anggaran_tersedia;?>,
				color: '#00a65a',
				sliced: true,
				selected: true
		  }, {
				name: 'Anggaran Terpakai',
				y: <?php echo $anggaran_terpakai;?>,
				color: '#dd4b39',
		  }]
	  }]
  });
  </script>


