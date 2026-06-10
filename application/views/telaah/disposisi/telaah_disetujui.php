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
  						<h3 class="box-title">LIST TELAAH DISETUJUI</h3>
  					</div>
  					<div class="box-header with-border">
  						<?php echo form_open('telaah/disposisi/search_telaah_disetujui/'.$this->uri->segment(4)); ?>
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
  							echo $message;
  						}
  						?>
  						<table class="table table-bordered table-striped table-hover">
  							<tr class='info'>
  								<th style="width: 5px">No</th>
  								<th style="width: 40px">Tanggal Pengajuan</th>
  								<th style="width: 200px">Pelaksana Perjalanan Dinas</th>
  								<th style="width: 300px">Jabatan</th>
  								<th style="width: 300px">Perihal (Maksud Perjalanan Dinas)</th>
								<?php if(($this->ion_auth->get_users_groups()->row()->id == 2 && $this->ion_auth->user()->row()->jenis_skpd == 3) 
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
  							foreach($telaah_disetujui as $v){
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
											<?php if($v->telaah_jabatan_pelaksana > 0 ){
												if($v->telaah_jabatan_pelaksana==1){
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
											} else {
												echo "<td class='col-md-3'>".$v->pegawai_namajabatan."</td>";
											}
										 } else { ?>
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
									
  									<td><span class='label label-success'>OK</span></td>
  									<td><a href="<?php echo base_url();?>telaah/disposisi/lihat_laporan/<?php echo $this->uri->segment(4)?>/<?php echo $v->telaah_kategori?>?telaah_id=<?php echo $telaah_id?>&&telaah_disetujui=1" class="btn btn-sm btn-block btn-primary">Detail Telaah Staff</a></td>
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