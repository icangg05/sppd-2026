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
  						<h3 class="box-title">HISTORY</h3>
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
  								<th style="width: 200px">Nama Pegawai</th>
  								<th style="width: 300px">Jabatan</th>
  								<th style="width: 300px">Perihal (Maksud Perjalanan Dinas)</th>
  								<th style="width: 20px">Aksi</th>
  							</tr>
  							<?php 
  							$no = $number + 1;
  							foreach($telaah_staf as $v){
  								$telaah_id = base64_encode($this->encrypt->encode($v->telaah_id, $this->session->userdata('encrypt_key')));	
  								$telaah_pelaksana = base64_encode($this->encrypt->encode($v->telaah_pelaksana, $this->session->userdata('encrypt_key')));	
  								$telaah_kategori = base64_encode($this->encrypt->encode($v->telaah_kategori, $this->session->userdata('encrypt_key')));	
  								?>
  								<tr>

  									<td><?php echo $no?></td>
  									<td><?php echo $v->telaah_waktuinput; ?></td>
									
  									<?php if ($v->telaah_kategori==3){?>
  									<?php if($v->anggotadprd_name==""){?>
  									<td><?php echo $v->pegawai_nama; ?></td>
  									<td><?php echo $v->pegawai_namajabatan; ?></td>
  									<?php }else{ ?>
  									<td><?php echo $v->anggotadprd_name; ?></td>
  									<td><?php echo $v->anggotadprd_jabatan; ?></td>
  									<?php } ?>

  									<?php } else {?>
  									<td><?php echo $v->pegawai_nama; ?></td>
  									<td><?php echo $v->pegawai_namajabatan; ?></td>


  									<?php } ?>
  									<td><?php echo $v->telaah_perihal?></td>
  									<td>
  										<a href="<?php echo base_url();?>telaah/history/detail?telaah_id=<?php echo $telaah_id?>&&telaah_kategori=<?php echo $telaah_kategori?>" target="blank" class="btn btn-sm btn-block btn-primary">Lihat</a>
  									</td>
  								</tr>
  								<?php 
  								$no++;
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