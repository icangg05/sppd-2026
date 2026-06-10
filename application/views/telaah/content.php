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
					<h3 class="box-title">
					<?php 
					switch($this->uri->segment(4)){
						case "esselon" 			: echo "TELAAH STAF (Esselon III, IV dan Staff)"; break;
						case "kadis" 			: echo "TELAAH STAF (Kepala OPD)"; break;
						case "dprd" 			: echo "TELAAH STAF (DPRD)"; break;
						case "sekda" 			: echo "TELAAH STAF (Sekda, Asisten dan Kabag)"; break;
						case "camat" 			: echo "TELAAH STAF (Camat)"; break;
						case "lurah" 			: echo "TELAAH STAF (Lurah)"; break;
						case "staff_dprd" 		: echo "TELAAH STAF (Staff DPRD)"; break;
						case "staff_camat" 		: echo "TELAAH STAF (Staff Camat)"; break;
						case "staff_lurah" 		: echo "TELAAH STAF (Staff Lurah)"; break;
						case "walikota" 		: echo "TELAAH STAF (Walikota)"; break;
						case "staff_setda" 		: echo "TELAAH STAF (Kasubag dan Staff Setda)"; break;
						case "sekwan" 			: echo "TELAAH STAF (Sekwan)"; break;
						case "kapus" 			: echo "TELAAH STAF (Kepala Puskesmas)"; break;
					}
					?> 
					</h3>
  					</div>
  					<div class="box-header with-border">
  						<?php if ($this->ion_auth->user()->row()->id==1){ ?>
  						<?php echo form_open("telaah/list_telaah/search_admin/".$this->uri->segment(4));?>
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
  						<?php echo form_open("list_telaah/esselon/search");?>
  						<div class="col-md-9">
							<?php if ($this->ion_auth->get_users_groups()->row()->id != 9){ ?>
								<a href="<?php echo base_url();?>list_telaah/esselon/create_view" class="btn btn-success btn-flat">Tambah Data</a>
								<a href="<?php echo base_url();?>list_telaah/esselon" class="btn btn-warning btn-flat">Refresh</a>
							<?php } ?>
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
  						<p><center>
							<?php if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id==1){ ?>
								<a href="<?php echo base_url();?>telaah/list_telaah/index_admin/<?php echo $this->uri->segment(4);?>" class="btn btn-flat <?php if($this->uri->segment(3)=="index_admin"){ echo "btn-info";}else{ echo "btn-default";}?>">ALL</a>
								<a href="<?php echo base_url();?>telaah/list_telaah/result_admin/<?php echo $this->uri->segment(4);?>/0" class="btn btn-flat <?php if($this->uri->segment(3)=="result_admin" && $this->uri->segment(5)==0){ echo "btn-primary";}else{ echo "btn-default";}?>">MASUK</a>
								<a href="<?php echo base_url();?>telaah/list_telaah/result_admin/<?php echo $this->uri->segment(4);?>/1" class="btn btn-flat <?php if($this->uri->segment(3)=="result_admin" && $this->uri->segment(5)==1){ echo "btn-warning";}else{ echo "btn-default";}?>">DI PROSES</a>
								<a href="<?php echo base_url();?>telaah/list_telaah/result_admin/<?php echo $this->uri->segment(4);?>/2" class="btn btn-flat <?php if($this->uri->segment(3)=="result_admin" && $this->uri->segment(5)==2){ echo "btn-success";}else{ echo "btn-default";}?>">SELESAI</a>
								<a href="<?php echo base_url();?>telaah/list_telaah/result_admin/<?php echo $this->uri->segment(4);?>/3" class="btn btn-flat <?php if($this->uri->segment(3)=="result_admin" && $this->uri->segment(5)==3){ echo "btn-danger";}else{ echo "btn-default";}?>">TIDAK DITERIMA</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>list_telaah/esselon" class="btn btn-flat <?php if(!$this->uri->segment(3)){ echo "btn-info";}else{ echo "btn-default";}?>">ALL</a>
								<a href="<?php echo base_url();?>list_telaah/esselon/result?status=0" class="btn btn-flat <?php if($this->uri->segment(3) && $this->input->get('status')==0){ echo "btn-primary";}else{ echo "btn-default";}?>">MASUK</a>
								<a href="<?php echo base_url();?>list_telaah/esselon/result?status=1" class="btn btn-flat <?php if($this->uri->segment(3) && $this->input->get('status')==1){ echo "btn-warning";}else{ echo "btn-default";}?>">DI PROSES</a>
								<a href="<?php echo base_url();?>list_telaah/esselon/result?status=2" class="btn btn-flat <?php if($this->uri->segment(3) && $this->input->get('status')==2){ echo "btn-success";}else{ echo "btn-default";}?>">SELESAI</a>
								<a href="<?php echo base_url();?>list_telaah/esselon/result?status=3" class="btn btn-flat <?php if($this->uri->segment(3) && $this->input->get('status')==3){ echo "btn-danger";}else{ echo "btn-default";}?>">TIDAK DITERIMA</a>
							<?php }  ?>
						</center></p>
  						<div class="table-responsive box-body">
  							<table class="table table-bordered table-striped table-hover">
  								<tr class='info'>
  									<th style="width: 5px">No</th>
  									<th style="width: 40px">Tanggal Pengajuan</th>
  									<th style="width: 150px">Perihal (Maksud Perjalanan Dinas)</th>
  									<th style="width: 200px">Pelaksana</th>
  									<th style="width: 40px">Status</th>
									<?php 
									switch($this->uri->segment(4)){
										case "esselon" 			: if($this->ion_auth->user()->row()->jenis_skpd == 10 || 
																	($this->ion_auth->user()->row()->id==1)){
																		echo "<th style='width: 40px'>OPD</th>"; 
																	} break;
										case "kadis" 			: echo "<th style='width: 40px'>OPD</th>";  break;
										case "dprd" 			: echo "<th style='width: 40px'>Kategori</th>"; break;
										case "staff_lurah" 		: echo "<th style='width: 40px'>Kategori</th>"; break;
										case "staff_setda" 		: echo "<th style='width: 40px'>Sub Bagian</th>"; break;
										case "staff_camat" 		: echo "<th style='width: 40px'>Bagian</th>"; break;
										case "staff_lurah" 		: echo "<th style='width: 40px'>Bagian</th>"; break;
									} 
									?>		
  									<th style="width: 100px">Lihat Telaah</th>
  									<th style="width: 100px">Cetak</th>
  								</tr>
  								<?php 
  								$number=$number+1;
  								foreach($data as $v){
  									$telaah_id = base64_encode($this->encrypt->encode($v->telaah_id, $this->session->userdata('encrypt_key')));	
  									?>
  									<tr>
  										<td><?php echo $number;?></td>
  										<td>
											<?php 
												$date = substr($v->telaah_waktuinput, 0, 10);
												$time = substr($v->telaah_waktuinput, 11, 19);
												$telaah_waktuinput =  $this->waktu->date_indo($date);
												echo $telaah_waktuinput.' '.$time;
											?>
										</td>
  										<td><?php echo $v->telaah_perihal?></td>
  										<?php if ($this->uri->segment(4)=="dprd"){ ?>
											<td><?php echo $v->anggotadprd_name?></td>
										<?php } else { ?>
											<td><?php echo $v->pegawai_nama?></td>
										<?php } ?>
  										<td>
  											<?php
  											if($v->telaah_status==0) {
  												echo '<span class="label label-default">Belum Diterima</span>';
  											} else if($v->telaah_status==1) {
												if($v->telaah_perbaikan == 1){
													echo "<span class='label label-info'>Diperbaiki dan Sedang Diproses</span>";
												} else {
													echo '<span class="label label-warning">Dalam Proses Permohonan SPPD</span>';
												}
  											} else if($v->telaah_status==2) {
  												if($v->telaah_tanggalkembali > date('Y-m-d')){
													echo '<span class="label" style="background-color: #75c940;">SPPD Disetujui</span>';
												} else {
													echo '<span class="label label-success">Perjalananan Selesai dan Masukkan Laporan</span>';
												}
												echo '<br><br>';
												
												$rincian = $this->m_telaah->count_rincian_biaya($v->telaah_id);
												$pengeluaran_rill = $this->m_telaah->count_pengeluaran_rill($v->telaah_id);
												if($rincian==0 && $pengeluaran_rill==0){
													echo "<span class='label label-default'>Belum Realisasi</span>";
												} else {
													echo "<span class='label label-warning'>Sudah Realisasi</span>";
												}
												echo '<br>';
												$laporan_perjalanan = $this->m_telaah->count_laporan_perjalanan($v->telaah_id);
												if($laporan_perjalanan == 0){
													echo "<span class='label label-default'>Belum Upload laporan</span>";
												} else {
													echo "<span class='label label-warning'>Sudah Upload laporan</span>";
												}
												
  											} else if($v->telaah_status==3){
  												echo '<span class="label label-danger">Tidak Diterima</span>';
  											} else if($v->telaah_status==5){
  												echo '<span class="label label-primary">Perbaikan</span>';
  											}
  											?>
  										</td>
										
										<?php 
										switch($this->uri->segment(4)){
											case "esselon" 			: if($this->ion_auth->user()->row()->jenis_skpd == 10){	
																		if($v->jenis_skpd == 10){
																				echo "<td><span class='label label-primary'>".$v->skpd_nama."</span></td>";
																			} else { 
																				echo "<td><span class='label label-danger'>".$v->skpd_nama."</span></td>";
																			} 
																		} else if ($this->ion_auth->user()->row()->id==1){
																			echo "<td>".$v->skpd_nama."</td>";
																		}  break;
											case "kadis" 			: echo "<td>".$v->skpd_nama."</td>"; break;
											case "dprd" 			: echo "<td><span class='label label-warning'>Anggota DPRD</span></td>"; break;
											case "staff_lurah" 		: echo "<td><span class='label label-warning'>Staff Kelurahan</span></td>"; break;
											case "staff_camat" 		: echo "<td><span class='label label-warning'>Staff Kecamatan</span></td>"; break;
											case "staff_setda" 		: echo "<td>".$v->nama_subbagian."</td>"; break;
										} 
										?>
										
										<td><a href="<?php echo base_url();?>telaah/list_telaah/detail/<?php echo $this->uri->segment(4);?>?telaah_id=<?php echo $telaah_id?>" class="btn btn-sm btn-block btn-primary"><i class="fa fa-eye"></i> Lihat</a></td>
  										
										<td>
										<!--?php if($v->telaah_status==2){ ?>
											<a href="<!--?php echo base_url();?>list_telaah/esselon/laporan?telaah_id=<!--?php echo $telaah_id?>" class="btn btn-sm btn-block btn-warning"><i class="fa fa-arrow-circle-right"></i> Selanjutnya</a>
  										<!--?php } else { ?>
											<a href="#" class="btn btn-sm btn-block btn-default"><i class="fa fa-arrow-circle-right"></i> Selanjutnya</a>
										<!--?php }?-->
										
  										<a href="<?php echo base_url();?>telaah/list_telaah/cek_timeline/<?php echo $this->uri->segment(4)?>?telaah_id=<?php echo $telaah_id?>" class="btn btn-sm btn-block btn-success"><i class="fa fa-clock-o"></i> Cek Timeline</a>
  										
  										<a href="<?php echo base_url();?>telaah/list_telaah/update_view/<?php echo $this->uri->segment(4)?>?telaah_id=<?php echo $telaah_id?>" class="btn btn-sm btn-block btn-primary"><i class="fa fa-edit"></i> Edit Telaah</a>
										
										<?php if($this->ion_auth->user()->row()->id==1 || $this->ion_auth->get_users_groups()->row()->id == 9){?>
											<button class="btn btn-sm btn-block btn-danger"" data-toggle="modal" data-target="#myModal<?php echo $v->telaah_id?>" ><i class="fa fa-trash-o"></i> Hapus Telaah </button>
										<?php } ?>
  										</td>
										
  								</tr>

							
								<!-- Modal Delete Telaah -->
								<div id="myModal<?php echo $v->telaah_id?>" class="modal fade" role="dialog">
								  <div class="modal-dialog">

									<!-- Modal content-->
									<div class="modal-content">
									  <div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Hapus Telaah <?php echo $v->telaah_id?></h4>
									  </div>
									  <?php 
									  if($this->ion_auth->user()->row()->id==1 ){
										echo form_open("admin/delete_telaah");
									  } else {
										echo form_open("list_telaah/esselon/delete_telaah");  
									  }
									  ?>
									  <div class="modal-body">
										<p>Apakah anda yakin ingin menghapus telaah <?php echo "<b>".$v->pegawai_nama."</b> dengan Perihal : <b>".$v->telaah_perihal."</b> ?"?>.</p>
										<input type="text" name="telaah_id" value="<?php echo $v->telaah_id?>">
										<input type="text" name="url" value="<?php echo $this->uri->segment(4)?>">
									  </div>
									  <div class="modal-footer">
										<button type="submit" class="btn btn-danger">Hapus</button>
									  <?php echo form_close();?>
										<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
									  </div>
									</div>

								  </div>
								</div>





  								<?php 
  								$number++;
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