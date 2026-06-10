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
  						<h3 class="box-title">DEMO TELAAH STAF (Kepala OPD)</h3>
  					</div>
  					<div class="box-header with-border">
  						<?php if ($this->ion_auth->user()->row()->id==1){ ?>
  						<?php echo form_open("admin/search_kadis");?>
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
  						<?php echo form_open("list_telaah/kadis/search");?>
  						<div class="col-md-9">
							<?php if ($this->ion_auth->get_users_groups()->row()->id != 9){ ?>
								<a href="<?php echo base_url();?>list_telaah/kadis/create_view" class="btn btn-success btn-flat">Tambah Data</a>
								<a href="<?php echo base_url();?>list_telaah/kadis" class="btn btn-warning btn-flat">Refresh</a>
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
								<a href="<?php echo base_url();?>admin/index_kadis" class="btn btn-flat <?php if(!$this->uri->segment(2)){ echo "btn-info";}else{ echo "btn-default";}?>">ALL</a>
								<a href="<?php echo base_url();?>admin/result_kadis?status=0" class="btn btn-flat <?php if($this->uri->segment(2) && $this->input->get('status')==0){ echo "btn-primary";}else{ echo "btn-default";}?>">MASUK</a>
								<a href="<?php echo base_url();?>admin/result_kadis?status=1" class="btn btn-flat <?php if($this->uri->segment(2) && $this->input->get('status')==1){ echo "btn-warning";}else{ echo "btn-default";}?>">DI PROSES</a>
								<a href="<?php echo base_url();?>admin/result_kadis?status=2" class="btn btn-flat <?php if($this->uri->segment(2) && $this->input->get('status')==2){ echo "btn-success";}else{ echo "btn-default";}?>">SELESAI</a>
								<a href="<?php echo base_url();?>admin/result_kadis?status=3" class="btn btn-flat <?php if($this->uri->segment(2) && $this->input->get('status')==3){ echo "btn-danger";}else{ echo "btn-default";}?>">TIDAK DITERIMA</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>list_telaah/kadis" class="btn btn-flat <?php if(!$this->uri->segment(3)){ echo "btn-info";}else{ echo "btn-default";}?>">ALL</a>
								<a href="<?php echo base_url();?>list_telaah/kadis/result?status=0" class="btn btn-flat <?php if($this->uri->segment(3) && $this->input->get('status')==0){ echo "btn-primary";}else{ echo "btn-default";}?>">MASUK</a>
								<a href="<?php echo base_url();?>list_telaah/kadis/result?status=1" class="btn btn-flat <?php if($this->uri->segment(3) && $this->input->get('status')==1){ echo "btn-warning";}else{ echo "btn-default";}?>">DI PROSES</a>
								<a href="<?php echo base_url();?>list_telaah/kadis/result?status=2" class="btn btn-flat <?php if($this->uri->segment(3) && $this->input->get('status')==2){ echo "btn-success";}else{ echo "btn-default";}?>">SELESAI</a>
								<a href="<?php echo base_url();?>list_telaah/kadis/result?status=3" class="btn btn-flat <?php if($this->uri->segment(3) && $this->input->get('status')==3){ echo "btn-danger";}else{ echo "btn-default";}?>">TIDAK DITERIMA</a>
							<?php }  ?>
						</center></p>
  						<table class="table table-bordered table-striped table-hover">
  							<tr class='info'>
  								<th style="width: 5px">No</th>
  								<th style="width: 40px">Tanggal Pengajuan</th>
  								<th style="width: 150px">Perihal (Maksud Perjalanan Dinas)</th>
  								<th style="width: 200px">Pelaksana</th>
  								<th style="width: 40px">Status</th>
  								<th style="width: 100px">Lihat Telaah</th>
  								<th style="width: 100px">Cetak</th>
                  <?php if($this->ion_auth->user()->row()->id==1){ ?>
                  <th style="width: 100px">#</th>
                  <?php } ?>
  							</tr>
  							<?php 
  							$number=$number+1;
  							foreach($kadis as $v){
  								$telaah_id = base64_encode($this->encrypt->encode($v->telaah_id, $this->session->userdata('encrypt_key')));	
  								?>
  								<tr>
  									<td><?php echo $number;?></td>
  									<td><?php echo $v->telaah_waktuinput?></td>
  									<td><?php echo $v->telaah_perihal?></td>
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
  										} else if($v->telaah_status==5){
  											echo '<span class="label label-primary">Perbaikan</span>';
  										}
  										?>
  									</td>
  									<td><a href="<?php echo base_url();?>list_telaah/kadis/detail?telaah_id=<?php echo $telaah_id?>" class="btn btn-sm btn-block btn-primary"><i class="fa fa-eye"></i> Lihat</a></td>
  									<?php 
  									if($v->telaah_status==2) { ?>
  									<td><a href="<?php echo base_url();?>list_telaah/kadis/laporan?telaah_id=<?php echo $telaah_id?>" class="btn btn-sm btn-block btn-warning"><i class="fa fa-arrow-circle-right"></i> Selanjutnya</a>
  										<?php if($v->telaah_kecepatan==1){ ?> 
  										<a href="<?php echo base_url();?>list_telaah/kadis/cek_timeline?telaah_id=<?php echo $telaah_id?>" class="btn btn-sm btn-block btn-success"><i class="fa fa-clock-o"></i> Cek Timline</a>
  										<?php	} ?>
  									</td>
  									<?php 	} else{ ?>
  									<td><button class="btn btn-sm btn-block btn-default"><i class="fa fa-arrow-circle-right"></i> Selanjutnya</button>
  										<?php if($v->telaah_kecepatan==1){ ?> 
  										<a href="<?php echo base_url();?>list_telaah/kadis/cek_timeline?telaah_id=<?php echo $telaah_id?>" class="btn btn-sm btn-block btn-success"><i class="fa fa-clock-o"></i> Cek Timline</a>
  										<?php	} ?>
										<?php if($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->skpd_id!=""){?>
												<?php if($v->telaah_status==0){?>
													<button class="btn btn-sm btn-block btn-danger"" data-toggle="modal" data-target="#myModal<?php echo $v->telaah_id?>" >Hapus Telaah </button>
												<?php } ?>
											<?php }?>
  									</td>
  									<?php
  								}
  								?>
                    <?php if($this->ion_auth->user()->row()->id==1){ ?>
                    <td><button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#myModal<?php echo $v->telaah_id?>" >Hapus Telaah </button></td>
                    <?php } ?>
  							</tr>
			<?php if($this->ion_auth->user()->row()->id==1){ ?>
                <!-- Modal Delete Telaah -->
                <div id="myModal<?php echo $v->telaah_id?>" class="modal fade" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Hapus Telaah <?php echo $v->telaah_id?></h4>
                      </div>
                      <?php echo form_open("admin/delete_telaah");?>
                      <div class="modal-body">
                        <p>Apakah anda yakin ingin menghapus telaah <?php echo "<b>".$v->pegawai_nama."</b> dengan Perihal : <b>".$v->telaah_perihal."</b> ?"?>.</p>
                        <input type="hidden" name="telaah_id" value="<?php echo $v->telaah_id?>">
                        <input type="hidden" name="url" value="<?php echo $this->uri->segment(2)?>">
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                      <?php echo form_close();?>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                      </div>
                    </div>

                  </div>
                </div>
			<?php } elseif($this->ion_auth->user()->row()->jenis_skpd == 1){ ?>
				<!-- Modal Delete Telaah -->
                <div id="myModal<?php echo $v->telaah_id?>" class="modal fade" role="dialog">
                  <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Hapus Telaah <?php echo $v->telaah_id?></h4>
                      </div>
                      <?php echo form_open("list_telaah/kadis/delete_telaah");?>
                      <div class="modal-body">
                        <p>Apakah anda yakin ingin menghapus telaah <?php echo "<b>".$v->pegawai_nama."</b> dengan Perihal : <b>".$v->telaah_perihal."</b> ?"?>.</p>
                        <input type="hidden" name="telaah_id" value="<?php echo $v->telaah_id?>">
                        <input type="hidden" name="url" value="<?php echo $this->uri->segment(2)?>">
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                      <?php echo form_close();?>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                      </div>
                    </div>

                  </div>
                </div>
			<?php } ?>

                
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