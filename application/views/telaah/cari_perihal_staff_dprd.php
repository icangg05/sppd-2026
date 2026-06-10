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
  						<h3 class="box-title">CARI PERIHAL (STAFF DPRD)</h3>
  					</div>
  					<div class="box-header with-border">
  						<?php if ($this->ion_auth->user()->row()->id==1){ ?>
  						<?php echo form_open("admin/search_dprd");?>
  						<div class="col-md-9"></div>
  						<div class="col-md-3">
  							<div class="input-group">
  								<input type="text" class="form-control" name="data" placeholder="Pelaksana ...">
  								<span class="input-group-btn">
  									<input type="submit" name="submit" class="btn btn-info btn-flat" value="Go">
  								</span>
  							</div>
  						</div>
  						<?php echo form_close();?>
  						<?php } else { ?>
  						<?php echo form_open("telaah/list_telaah/search_perihal_staff_dprd");?>
  						<div class="col-md-9">
							 <a href="<?php echo base_url();?>telaah/list_telaah/index/staff_dprd" class="btn btn-warning btn-flat">Kembali</a>
  						</div>
  						<div class="col-md-3">
  							<div class="input-group">
  								<input type="text" class="form-control" name="data" placeholder="Perihal ..." required>
  								<span class="input-group-btn">
  									<input type="submit" name="submit" class="btn btn-info btn-flat" value="Go">
  								</span>
  							</div>
  						</div>
  						<?php echo form_close();?>
  						<?php } ?>
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
  								<th style="width: 150px">Perihal (Maksud Perjalanan Dinas)</th>
  								<th style="width: 200px">Pelaksana</th>
  								<th style="width: 40px">Status</th>
  							</tr>
  							<?php 
  							$number=$number+1;
  							foreach($staff_dprd as $v){
  								$telaah_id = base64_encode($this->encrypt->encode($v->telaah_id, $this->session->userdata('encrypt_key')));	
  								?>
  								<tr>
  									<td><?php echo $number;?></td>
  									<td><?php echo $v->telaah_waktuinput?></td>
  									<td><?php 	$keyword    = $keyword;
												$pattern    = preg_replace('/\s|\t|\r|\n/', '|', $keyword);
												$search     = preg_replace("/$pattern/i", '<b>\0</b>', $v->telaah_perihal);
												echo $search;
										?>
									</td>
  									<td><?php echo $v->pegawai_nama?></td>
									<td>
  										<?php
  										if($v->telaah_status==0) {
  											echo '<span class="label label-default">Belum Diterima</span></td>';
  										} else if($v->telaah_status==1) {
  											echo '<span class="label label-warning">Dalam Proses</span></td>';
  										} else if($v->telaah_status==2) {
  											echo '<span class="label label-success">Selesai</span></td>';
  										} else if($v->telaah_status==3){
  											echo '<span class="label label-danger">Tidak Diterima</span>';
  										}
  										?>
  									</td>
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