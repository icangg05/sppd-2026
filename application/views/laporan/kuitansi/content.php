
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
              <h3 class="box-title">KUITANSI </h3>
            </div>
			<div class="box-header with-border">
			<div class="col-md-7">
					<a href="<?php echo base_url();?>telaah/list_telaah/laporan/<?php echo $this->uri->segment(5)?>?telaah_id=<?php echo $this->input->get('telaah_id');?>" class="btn btn-danger btn-sm "><i class="fa fa-close"></i> Kembali</a>
			</div>
			</div>
            <!-- /.box-header -->
            <div class="box-body">
			<?php
				$message = $this->session->flashdata('notif');
						if($message){
							echo '<p class="alert alert-info text-center"><b>'.$message .'</b></p>';
						}
				$error = $this->session->flashdata('error');
				if($error){
					echo $error;
				}
			?>
			<div class="table-responsive box-body">
			<p><b><i style="color: #fd0000;">
			<?php  if(!$bendahara_pengeluaran){
				echo "Tidak ada Bendahara Pengeluaran di data Pegawai..<br>";
			} ?>
			</i></b></p>
              <table class="table table-bordered table-hover">
                <tr>
				<!-- SEKDA-->
				<?php if($pelaksana[0]['telaah_kategori']==3 ) { ?>
					<td style="width: 5px" colspan="6">Pelaksana : <?php echo $pelaksana[0]['anggotadprd_name'] ?></td>
					<th style="width: 40px" colspan="2">
					<?php $kuitansi = $this->m_kuitansi->get_kuitansi_panjar($pelaksana[0]['telaah_id'],$pelaksana[0]['anggotadprd_id']);
							if(count($kuitansi)==0) { 
						?>
							<a href="<?php echo base_url();?>telaah/laporan/kuitansi/create_view/<?php echo $this->uri->segment(5) ?>?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['anggotadprd_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-success btn-sm "> Input Kuitansi Panjar</a>
						<?php } else { ?>
							<a href="<?php echo base_url();?>telaah/laporan/kuitansi/update_view/<?php echo $this->uri->segment(5) ?>?kuitansi_panjar_id=<?php echo $kuitansi[0]['kuitansi_panjar_id'] ?>&&telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['anggotadprd_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit Kuitansi Panjar</a>
							<a href="<?php echo base_url();?>telaah/laporan/laporan/cetak_kuitansi_panjar/pelaksana_dprd?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['anggotadprd_id'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Panjar</a>
						<?php } ?>
						
						<?php if((count($pengeluaran_rill)==0) && (count($rincian)==0)){?>
								<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Rampung</a>
						<?php } else {?>
								<a href="<?php echo base_url();?>telaah/laporan/laporan/cetak_kuitansi_rampung/pelaksana_dprd?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['anggotadprd_id'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Rampung</a>
						<?php } ?>
						 
					</th>
				<?php } else { ?>
					<td style="width: 5px" colspan="6">Pelaksana : <?php echo $pelaksana[0]['pegawai_nama'] ?></td>
					<th style="width: 40px" colspan="2">
					<?php if($pelaksana[0]['telaah_kategori']==8){
								$kuitansi = $this->m_kuitansi->get_kuitansi_panjar_walikota($pelaksana[0]['telaah_id'],$pelaksana[0]['pegawai_id']);
						} else {
								$kuitansi = $this->m_kuitansi->get_kuitansi_panjar($pelaksana[0]['telaah_id'],$pelaksana[0]['pegawai_id']);
						}
							if(count($kuitansi)==0) { 
						?>
							<a href="<?php echo base_url();?>telaah/laporan/kuitansi/create_view/<?php echo $this->uri->segment(5) ?>?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-success btn-sm "> Input Kuitansi Panjar</a>
						<?php } else { ?>
							<?php if($pelaksana[0]['telaah_kategori']==8){ ?>
									<a href="<?php echo base_url();?>telaah/laporan/kuitansi/update_view/<?php echo $this->uri->segment(5) ?>?kuitansi_panjar_id=<?php echo $kuitansi[0]['kuitansi_panjar_id'] ?>&&telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit Kuitansi Panjar</a>
									<a href="<?php echo base_url();?>telaah/laporan/laporan/cetak_kuitansi_panjar/walikota?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Panjar</a>
							<?php } else { ?>
									<a href="<?php echo base_url();?>telaah/laporan/kuitansi/update_view/<?php echo $this->uri->segment(5) ?>?kuitansi_panjar_id=<?php echo $kuitansi[0]['kuitansi_panjar_id'] ?>&&telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit Kuitansi Panjar</a>
									<?php if(!$bendahara_pengeluaran) { ?>
										<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Panjar</a>
									<?php } else { ?>
										<a href="<?php echo base_url();?>telaah/laporan/laporan/cetak_kuitansi_panjar/pelaksana?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Panjar</a>
									<?php } ?>
							<?php } ?>
						<?php } ?>
						
						<?php if((count($pengeluaran_rill)==0) && (count($rincian)==0)){?>
								<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Rampung</a>
						<?php } else {?>
								<?php if($pelaksana[0]['telaah_kategori']==8){ ?>
									<a href="<?php echo base_url();?>telaah/laporan/laporan/cetak_kuitansi_rampung/walikota?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Rampung</a>
								 <?php } else { ?>
									<?php if(!$bendahara_pengeluaran) { ?>
										<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Rampung</a>
									<?php } else { ?>
									<a href="<?php echo base_url();?>telaah/laporan/laporan/cetak_kuitansi_rampung/pelaksana?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Rampung</a>
									<?php } ?>
								 <?php } ?>
						<?php } ?>
						
					</th>
				<?php }  ?>
                </tr>
			<?php
				for($i=0;$i<$jumlah_pengikut;$i++){
			?>
                <tr>
				<?php if($pelaksana[0]['telaah_kategori']==3 ) { ?>
					<td style="width: 5px" colspan="6">Pengikut : <?php echo $pengikut[$i]['anggotadprd_name'] ?></td>
					<th style="width: 40px" colspan="2">
						<?php $kuitansi = $this->m_kuitansi->get_kuitansi_panjar($pengikut[$i]['telaah_id'],$pengikut[$i]['anggotadprd_id']);
							if(count($kuitansi)==0) { 
						?>
							
							<a href="<?php echo base_url();?>telaah/laporan/kuitansi/create_view/<?php echo $this->uri->segment(5) ?>?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pengikut[$i]['anggotadprd_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-success btn-sm "> Input Kuitansi Panjar</a>
						<?php } else { ?>
							<a href="<?php echo base_url();?>telaah/laporan/kuitansi/update_view/<?php echo $this->uri->segment(5) ?>?kuitansi_panjar_id=<?php echo $kuitansi[0]['kuitansi_panjar_id'] ?>&&telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['anggotadprd_id'] ?>&&pengikut_id=<?php echo $pengikut[$i]['anggotadprd_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit Kuitansi Panjar</a>
							<a href="<?php echo base_url();?>telaah/laporan/laporan/cetak_kuitansi_panjar/pengikut_dprd?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['anggotadprd_id'] ?>&&pengikut_id=<?php echo $pengikut[$i]['pegawai_id'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Panjar</a>
						<?php } ?>
						
						<?php if((count($pengeluaran_rill)==0) && (count($rincian)==0)){?>
								<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Rampung</a>
						<?php } else {?>
								 <a href="<?php echo base_url();?>telaah/laporan/laporan/cetak_kuitansi_rampung/pengikut_dprd?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['anggotadprd_id'] ?>&&pengikut_id=<?php echo $pengikut[$i]['pegawai_id'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Rampung</a>
						<?php } ?>
						
					</th>
				<?php } else { ?>
					<td style="width: 5px" colspan="6">Pengikut : <?php echo $pengikut[$i]['pegawai_nama'] ?></td>
					<th style="width: 40px" colspan="2">
						<?php $kuitansi = $this->m_kuitansi->get_kuitansi_panjar($pengikut[$i]['telaah_id'],$pengikut[$i]['pegawai_id']);
							if(count($kuitansi)==0) { 
						?>
							
							<a href="<?php echo base_url();?>telaah/laporan/kuitansi/create_view/<?php echo $this->uri->segment(5) ?>?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pengikut[$i]['pegawai_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-success btn-sm "> Input Kuitansi Panjar</a>
						<?php } else { ?>
							<a href="<?php echo base_url();?>telaah/laporan/kuitansi/update_view/<?php echo $this->uri->segment(5) ?>?kuitansi_panjar_id=<?php echo $kuitansi[0]['kuitansi_panjar_id'] ?>&&telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>&&pengikut_id=<?php echo $pengikut[$i]['pegawai_id'] ?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit Kuitansi Panjar</a>
							<?php if(!$bendahara_pengeluaran) { ?>
								<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Panjar</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>telaah/laporan/laporan/cetak_kuitansi_panjar/pengikut?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>&&pengikut_id=<?php echo $pengikut[$i]['pegawai_id'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Panjar</a>
							<?php } ?>
						<?php } ?>
						
						<?php if((count($pengeluaran_rill)==0) && (count($rincian)==0)){?>
							<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Rampung</a>
						<?php } else {?>
							<?php if(!$bendahara_pengeluaran) { ?>
								<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Rampung</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>telaah/laporan/laporan/cetak_kuitansi_rampung/pengikut?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>&&pengikut_id=<?php echo $pengikut[$i]['pegawai_id'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Kuitansi Rampung</a>
							<?php } ?>
						<?php } ?>
						
					</th>
				<?php } ?>
                </tr>
			<?php
				}
			?>
			  </table>
            </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
			<p><b><i>*Catatan : Untuk mencetak kuitansi rampung Wajib Mengisi data Laporan Pengeluaran Rill dan Rincian Biaya Perjalanan Dinas</i></b></p>
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
  
  <div class="modal fade" id="1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <b>Bendahara</b> Belum dipilih
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <b>Laporan Pengeluaran Rill</b> dan <b>Rincian Biaya Perjalanan Dinas</b> wajib diisi
        </div>
      </div>
    </div>
  </div>