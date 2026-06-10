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
              <h3 class="box-title">SPPD </h3>
            </div>
			<div class="box-header with-border">
			<div class="col-md-7">
				  <a href="<?php echo base_url();?>telaah/list_telaah/laporan/<?php echo $this->uri->segment(5);?>?telaah_id=<?php echo $this->input->get('telaah_id');?>" class="btn btn-danger btn-sm "><i class="fa fa-close"></i> Kembali</a>
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
				Klik Tombol "<b>Reset TTE</b>" jika file SPPD terdapat kesalahan (Tidak dapat didownload atau tidak terdapat barcode pada SPPD)
				</i></p>
			</div>
			
			<div class="col-lg-4 col-xs-12">
			<table class="table">
				<tr>
					<td style="width: 10px">Tanggal SPPD</td>
					<td style="width: 20px"> : <b><?php if($spd[0]['telaah_tanggalspd']=="0000-00-00" || !$spd[0]['telaah_tanggalspd']){} else { echo date("d-m-Y", strtotime($spd[0]['telaah_tanggalspd'])); }?></b></td>
				</tr>
				<!--tr>
					<td style="width: 10px">Penanda Tangan SPPD</td>
					<td style="width: 20px"> : <b><!--?php echo $tanda_tangan_spd[0]['pegawai_nama']; ?></b></td>
				</tr-->
			</table>
			
			</div>
			  <?php if($spd[0]['telaah_reset_tte']==0){ ?>
					<a href="<?php echo base_url() ?>telaah/laporan/spd/reset_tte/<?php echo $this->input->get('telaah_id') ?>/<?php echo $this->uri->segment(5) ?>" class="btn btn-success btn-sm " onclick="return confirm('Anda Yakin ?');"><i class="fa fa-reload"></i> Reset TTE</a><br><br>
			  <?php } else { ?>
					<a href="<?php echo base_url() ?>telaah/laporan/spd/reset_tte/<?php echo $this->input->get('telaah_id') ?>/<?php echo $this->uri->segment(5) ?>" class="btn btn-success btn-sm " onclick="return confirm('Anda Yakin ?');"><i class="fa fa-reload"></i> Reset TTE</a><br><br>
					<p><b><i style='color: #fd0000;'>Menunggu Di TTE Kembali</i></b></p>
			  <?php } ?>
			
              <table class="table table-bordered table-hover">
                <tr>
          <?php if($spd[0]['telaah_kategori']==3){ ?>
            <td style="width: 5px" colspan="6">Pelaksana : <?php echo $pelaksana[0]['anggotadprd_name'] ?></td>
          <?php }else{ ?>
            <td style="width: 5px" colspan="6">Pelaksana : <?php echo $pelaksana[0]['pegawai_nama'] ?></td>
          <?php } ?>
					
		<th style="width: 40px" colspan="2">
          <?php if($spd[0]['telaah_kategori']==8) { ?>
		  
				<?php if($spd[0]['telaah_tte']=="") { ?>
					<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPPD</a>
				<?php } else { ?>
					<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spd[0]['telaah_tte'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPPD</a>
				<?php } ?>

		  <?php  } else if($spd[0]['telaah_kategori']==3) { ?>
          
				<!-- DPRD -->
				<?php if($spd[0]['telaah_tte']=="") { ?>
					<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPPD</a>
				<?php } else { ?>
					<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spd[0]['telaah_tte'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPPD</a>
				<?php } ?>
		  
			
          <?php }  else { ?>
		  
				<!-- ESSELON, SEKDA -->
				<?php if($spd[0]['telaah_tte']=="") { ?>
					<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPPD</a>
				<?php } else { ?>
					<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo $spd[0]['telaah_tte'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPPD</a>
				<?php } ?>
				
            <?php
              }
            ?>
			
			
			<?php 
			$get_spd = str_replace(" ","%20",$spd[0]['telaah_tte']);
			$a = base_url().'upload/doc_TTE/'.$get_spd;
			$file = file($a);
			$endfile= trim($file[count($file) - 1]);
			$n="%%EOF";

			if ($endfile === $n) {
			} else {
				$x = "corrupted";
			}
			?>
			
					</th>
                </tr>
			<?php
				for($i=0;$i<$jumlah_pengikut;$i++){
			?>
			<!--?php// if($t->telaah_kategori==1 ) { ?-->
			<!--?php// } else { ?-->
                <tr>
				
						<td style="width: 5px" colspan="6">Pengikut : <?php echo $pengikut[$i]['pegawai_nama'] ?></td>
						
						<!-- DPRD -->
						<?php if($spd[0]['telaah_kategori']==3){ ?>
						<th style="width: 40px" colspan="2">
							<?php if($pengikut[$i]['telaah_tte']=="") { ?>
								<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPPD</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo  $pengikut[$i]['telaah_tte'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPPD</a>
							<?php } ?>	 
						</th>
						
						<!-- ESSELON -->
						<?php } else { ?>
						<th style="width: 40px" colspan="2">
							<?php if($pengikut[$i]['telaah_tte']=="") { ?>
								<a href="#" class="btn btn-default btn-sm "><i class="fa fa-print"></i> Cetak SPPD</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>upload/doc_TTE/<?php echo  $pengikut[$i]['telaah_tte'] ?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak SPPD</a>
							<?php } ?>
						</th>
						<?php } ?>
						
						<?php 
						$get_spd = str_replace(" ","%20",$pengikut[$i]['telaah_tte']);
						$a = base_url().'upload/doc_TTE/'.$get_spd;
						$file = file($a);
						$endfile= trim($file[count($file) - 1]);
						$n="%%EOF";

						if ($endfile === $n) {
						} else {
							$x2[$i] = "corrupted";
						}
						?>
					
                </tr>
			<?php
				}
				
						$a = count($x);
						$b = count($x2);
						$c = $a+$b;
						// if($c>0){
							// if($spd[0]['telaah_reset_tte']==0){
								// echo '<a href="'.base_url().'telaah/laporan/spd/reset_tte/'.$this->input->get('telaah_id').'/'.$this->uri->segment(5).'" class="btn btn-success btn-sm "><i class="fa fa-reload"></i> Reset TTE</a><br><br>';
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