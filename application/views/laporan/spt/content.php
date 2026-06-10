
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
              <h3 class="box-title">SPT</h3>
            </div>
			<div class="box-header with-border">
			<div class="col-md-7">
				  <a href="<?php echo base_url();?>/telaah/list_telaah/laporan/<?php echo $this->uri->segment(5)?>?telaah_id=<?php echo $this->input->get('telaah_id');?>" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
				</div>
			</div>
            <!-- /.box-header -->
            <div class="box-body">
			<?php
				$message = $this->session->flashdata('notif');
						if($message){
							echo '<p class="alert alert-info text-center"><b>'.$message .'</b></p>';
						}
			?>
			<div class="table-responsive box-body">
			<div class="col-lg-8 col-xs-12">
				<p><i style="color: #fd0000;">
				Catatan : <br>
				Klik Tombol "<b>Reset TTE</b>" jika file SPT terdapat kesalahan (Tidak dapat didownload atau tidak terdapat barcode pada SPT)
				</i></p>
			</div>
			
			<div class="col-lg-4 col-xs-12">
			<table class="table">
				<tr>
					<td style="width: 10px">Tanggal SPT</td>
					<td style="width: 20px"> : <b><?php if($spt[0]['telaah_tanggalspt']=="0000-00-00" || !$spt[0]['telaah_tanggalspt']){} else { echo date("d-m-Y", strtotime($spt[0]['telaah_tanggalspt'])); }?></b></td>
				</tr>
			</table>
			
			</div>
			  <?php if($spt[0]['telaah_reset_tte2']==0){ ?>
					<a href="<?php echo base_url() ?>telaah/laporan/spt/reset_tte/<?php echo $this->input->get('telaah_id') ?>/<?php echo $this->uri->segment(5) ?>" class="btn btn-success btn-sm " onclick="return confirm('Anda Yakin ?');"><i class="fa fa-reload"></i> Reset TTE</a><br><br>
              <?php } else { ?>
					<a href="<?php echo base_url() ?>telaah/laporan/spt/reset_tte/<?php echo $this->input->get('telaah_id') ?>/<?php echo $this->uri->segment(5) ?>" class="btn btn-success btn-sm " onclick="return confirm('Anda Yakin ?');"><i class="fa fa-reload"></i> Reset TTE</a><br><br>
					<p><b><i style='color: #fd0000;'>Menunggu Di TTE Kembali</i></b></p>
			  <?php } ?>
              <table class="table table-bordered table-hover">
                <tr>
				<?php if($spt[0]['telaah_kategori']==3) { ?>
						<td style="width: 5px" colspan="6">Pelaksana : <?php echo $pelaksana[0]['anggotadprd_name'] ?></td>
				<?php } else { ?>
						<td style="width: 5px" colspan="6">Pelaksana : <?php echo $pelaksana[0]['pegawai_nama'] ?></td>
				<?php } ?>
				
					<th style="width: 40px" colspan="2">
					
					
					
					<?php if($spt[0]['jenis_skpd'] == 1 ) { ?>
						<?php if($spt[0]['telaah_kategori']==2) { ?>
						
								<!-- KADIS -->
								<?php if($spt[0]['telaah_tte2']=='') { ?>
									<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } else { ?>
									<?php if($data[0]['telaah_domainperjalanan']==3 || $data[0]['telaah_domainperjalanan']==4 ) { ?>
										<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spt[0]['telaah_tte2'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
									<?php } else { ?>
										<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spt[0]['telaah_tte2'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
									<?php } ?>
								<?php } ?>
								
						<?php } else { ?>
								<!-- ESSELON -->
								<?php if($spt[0]['telaah_tte2']=='') { ?>
									<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } else { ?>
									<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spt[0]['telaah_tte2'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } ?>
								
						<?php } ?>
						
						
						
						
						
					<?php } else if($spt[0]['jenis_skpd'] == 2 ){ ?>
					
						<!-- DPRD -->
						<?php 	if($spt[0]['telaah_kategori']==3) { ?>
								<?php if($spt[0]['telaah_tte2']=='') { ?>
									<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } else { ?>
									<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spt[0]['telaah_tte2'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } ?>
								
						<!-- SEKWAN -->		
						<?php }  else if($spt[0]['telaah_kategori']==10) { ?>
								<?php if($spt[0]['telaah_tte2']=='') { ?>
									<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } else { ?>
									<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spt[0]['telaah_tte2'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } ?>
								
						<!-- STAFF DPRD -->
						<?php } else { ?>
								<?php if($spt[0]['telaah_tte2']=='') { ?>
									<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } else { ?>
									<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spt[0]['telaah_tte2'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } ?>
								
						<?php } ?>
						
					<?php } else if($spt[0]['jenis_skpd'] == 3 ){ ?>

						<!-- STAFF SEKDA -->		
						<?php if($spt[0]['telaah_kategori']==9){ ?>
								<?php if($spt[0]['telaah_tte2']=='') { ?>
									<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } else { ?>
									<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spt[0]['telaah_tte2'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } ?>
								
						<!-- WALIKOTA, SEKDA -->						
						<?php } else if($spt[0]['telaah_kategori']==4 || $spt[0]['telaah_kategori']==8){ ?>
								<?php if($spt[0]['telaah_tte2']=='') { ?>
									<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } else { ?>
									<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spt[0]['telaah_tte2'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } ?>
								
						<?php } ?>

					<?php } else if($spt[0]['jenis_skpd'] == 4 || $spt[0]['jenis_skpd'] == 5){ ?>
						
						
						<!-- CAMAT -->
						<?php if($spt[0]['telaah_kategori']==5) { ?>	
							<?php if($spt[0]['telaah_tte2']=='') { ?>
								<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spt[0]['telaah_tte2'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
							<?php } ?>
						
						<?php } else if($spt[0]['telaah_kategori']==7){ ?>
							<?php if($spt[0]['telaah_tte2']=='') { ?>
								<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spt[0]['telaah_tte2'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
							<?php } ?>
						
						<?php } ?>
							
					<?php } else if($spt[0]['jenis_skpd'] == 7 ){ ?>

						<!-- PUSKESMAS -->
						<?php if($spt[0]['telaah_kategori']==11) { ?>	
							<?php if($spt[0]['telaah_tte2']=='') { ?>
								<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spt[0]['telaah_tte2'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
							<?php } ?>
						
						<?php } else { ?>
							<?php if($spt[0]['telaah_tte2']=='') { ?>
								<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spt[0]['telaah_tte2'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
							<?php } ?>
						
						<?php } ?>
								
					<?php } else if($spt[0]['jenis_skpd'] == 10 ){ ?>

						<!-- PUSKESMAS -->
						<?php if($spt[0]['telaah_kategori']==1) { ?>	
							<?php if($spt[0]['telaah_tte2']=='') { ?>
								<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spt[0]['telaah_tte2'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
							<?php } ?>
						
						<?php } else { ?>
							<?php if($spt[0]['telaah_tte2']=='') { ?>
								<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spt[0]['telaah_tte2'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
							<?php } ?>
						
						<?php } ?>
								
					<?php } ?>
					</th>
                </tr>
			<?php
				for($i=0;$i<$jumlah_pengikut;$i++){
			?>
			<?php if($spt[0]['telaah_kategori']==1||$spt[0]['telaah_kategori']==3||$spt[0]['telaah_kategori']==4||$spt[0]['telaah_kategori']==5||$spt[0]['telaah_kategori']==6||$spt[0]['telaah_kategori']==7||$spt[0]['telaah_kategori']==8||$spt[0]['telaah_kategori']==9||$spt[0]['telaah_kategori']==10||$spt[0]['telaah_kategori']==11) { ?>
			<?php } else { ?>
                <tr>
					<?php if ($spt[0]['telaah_kategori']!=2) { ?>
						<td style="width: 5px" colspan="6">Pengikut : <?php echo $pengikut[$i]['pegawai_nama'] ?></td>
					<?php }  ?>
					
					<th style="width: 40px" colspan="2">
					<?php if($spt[0]['jenis_skpd']== 1 ) { ?>
						<?php if ($spt[0]['telaah_kategori']!=2) { ?>
								<?php if($spt[0]['telaah_tte2']=='') { ?>
									<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } else { ?>
									<a href="<?php echo base_url();?>telaah/laporan/laporan/cetak_spt/kadis/1?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>&&pengikut_id=<?php echo $pengikut[$i]['pegawai_id'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } ?>
						<?php }  ?>
						
					<?php } else if($spt[0]['jenis_skpd'] == 2){ ?>
							  <?php if(count($spt)==0) { ?>
									<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
							  <?php } else { ?>
									<?php if($spt[0]['telaah_ttdspt']) { ?>
										<a href="<?php echo base_url();?>telaah/laporan/laporan_dprd/cetak_spt_dprd2?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>&&pengikut_id=<?php echo $pengikut[$i]['pegawai_id'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
									<?php } else { ?>
										<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
									<?php } ?>
							  <?php }  ?>
						
					<?php } else if($spt[0]['jenis_skpd'] == 3){ ?>
							<?php if($spt[0]['telaah_kategori']!=9 || $spt[0]['telaah_kategori']!=8){ ?>
								<?php if($spt[0]['telaah_tte2']=='') { ?>
									<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } else { ?>
									<a href="<?php echo base_url();?>telaah/laporan/laporan/cetak_spt/sekda/1?telaah_id=<?php echo $this->input->get('telaah_id');?>&&pegawai_id=<?php echo $pelaksana[0]['pegawai_id'] ?>&&pengikut_id=<?php echo $pengikut[$i]['pegawai_id'] ?>" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Cetak SPT</a>
								<?php } ?>
							<?php } ?>
						
					<?php } ?>
					</th>
                </tr>
			<?php
			}
				}
			?>
			
			<?php 
			
			$get_spt = str_replace(" ","%20",$spt[0]['telaah_tte2']);
			
			$a = base_url().'upload/doc_TTE/'.$get_spt;
			$file = file($a);
			$endfile= trim($file[count($file) - 1]);
			$n="%%EOF";

			if ($endfile === $n) {
			} else {
				$x = "corrupted";
			}
			
			$a = count($x);
			// if($a>0){
				// if($spt[0]['telaah_reset_tte2']==0){
					// echo '<a href="'.base_url().'telaah/laporan/spt/reset_tte/'.$this->input->get('telaah_id').'/'.$this->uri->segment(5).'" class="btn btn-success btn-sm "><i class="fa fa-reload"></i> Reset TTE</a><br><br>';
				// } else {
					// echo "<p><b><i style='color: #fd0000;'>Menunggu Di TTE Kembali</i></b></p>";
				// }
			// }
			?>
			  </table>
            </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix">
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