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
					<?php if($this->uri->segment(3)=="sekretariat"){	?>
  						<h3 class="box-title">DATA ANGGARAN PER-BAGIAN SEKRETARIAT</h3>
						<?php } else {?>
  						<h3 class="box-title">DATA ANGGARAN PER-SKPD</h3>
						<?php } ?>
  					</div>
  					<div class="box-header with-border">
						<?php if($this->uri->segment(3)=="sekretariat"){	?>
							<div class="col-md-9">
								<a href="<?php echo base_url();?>telaah/detail_anggaran" class="btn btn-warning btn-sm "><i class="fa fa-close"></i> Kembali</a>
							</div>
						<?php } else {?>
							<div class="col-md-9">
								<a href="<?php echo base_url();?>telaah/disposisi/index/walikota" class="btn btn-warning btn-sm "><i class="fa fa-close"></i> Kembali</a>
							</div>
							<?php echo form_open("telaah/detail_anggaran/search");?>
							<div class="col-md-3">
								<div class="input-group">
									<select class="form-control" name="data">
										<option value="">- Pilih SKPD -</option>
										<?php foreach($skpd as $v){
											echo "<option value='$v->skpd_id'>$v->skpd_nama</option>" ;
										}?>
									</select>
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
								<?php if($this->uri->segment(3)=="sekretariat"){?>
									<th style="width: 200px">BAGIAN</th>
								<?php } else { ?>
									<th style="width: 200px">SKPD</th>
								<?php } ?>
  								<th style="width: 50px">Total anggaran</th>
  								<th style="width: 50px">Sisa Anggaran</th>
  								<th style="width: 50px">Anggaran Terpakai</th>
  								<th style="width: 10px">Aksi</th>
  							</tr>
  							<?php 
  							$number=$number+1;
  							foreach($telaah_staf as $v){
  								?>
  								<tr>
  									
  									<td><?php echo $number;?></td>
  									<td><?php echo $v->skpd_nama?></td>
  									<td>
									<?php 
										if($this->uri->segment(3)=="sekretariat"){
											echo "Rp. ".number_format($v->pagu, 0, ',', '.');
										} else {
											$pagu = $this->m_walikota->total_anggaran_skpd($v->skpd_id);
											echo "Rp. ".number_format($pagu[0]['total_anggaran_keseluruhan'], 0, ',', '.');
										}
									?>
  									<?php 
									if($jenis_anggaran == 1){
										$rincian_belanja_dalam_daerah = $this->m_walikota->rincian_belanja_dalam_daerah($v->skpd_id);
										$pengeluaran_rill_dalam_daerah = $this->m_walikota->pengeluaran_rill_dalam_daerah($v->skpd_id);
										$sisa_anggaran = $rincian_belanja_dalam_daerah[0]['jumlah'] + $pengeluaran_rill_dalam_daerah[0]['jumlah'];
									} else if($jenis_anggaran == 2){
										$rincian_belanja_dalam_daerah = $this->m_walikota->rincian_belanja_luar_daerah($v->skpd_id);
										$pengeluaran_rill_dalam_daerah = $this->m_walikota->pengeluaran_rill_luar_daerah($v->skpd_id);
										$sisa_anggaran = $rincian_belanja_dalam_daerah[0]['jumlah'] + $pengeluaran_rill_dalam_daerah[0]['jumlah'];
									} else {
										if(($v->skpd_id==3) && ($this->uri->segment(3)=="sekretariat")){
											$sisa_anggaran =  $this->m_walikota->cek_sisa_anggaran_sekretariat($v->bagian_id);
											$sisa_anggaran =  $sisa_anggaran[0]->tes;
										} else {
											$sisa_anggaran =  $this->m_dprd->cek_sisa_anggaran_skpd($v->skpd_id);
											$sisa_anggaran =  $sisa_anggaran[0]->tes;
										}
									}
									?>
  									<td>
										<?php 
											if($this->uri->segment(3)=="sekretariat"){
												echo "Rp. ".number_format($v->pagu - $sisa_anggaran, 0, ',', '.');
											} else {
												echo "Rp. ".number_format($pagu[0]['total_anggaran_keseluruhan']- $sisa_anggaran, 0, ',', '.');
											}
										?>
									</td>
  									<td>Rp. <?php echo number_format($sisa_anggaran, 0, ',', '.'); ?></td>	
  									<td>
									
									<?php if($jenis_anggaran){ ?>
										<?php if(($v->skpd_id==3) && ($this->uri->segment(3)!="sekretariat")){?>
											<a href="<?php echo base_url(); ?>telaah/detail_anggaran/sekretariat/<?php echo $jenis_anggaran ?>" class="btn btn-info btn-sm"><i class="fa fa-list"></i> Detail</a>
										<?php } else if(($v->skpd_id==3) && ($this->uri->segment(3)=="sekretariat")){?>
											<a href="<?php echo base_url(); ?>telaah/detail_anggaran/pengguna_anggaran_sekretariat/<?php echo $v->bagian_id?>/<?php echo $jenis_anggaran ?>" class="btn btn-info btn-sm"><i class="fa fa-list"></i> Detail</a>
										<?php } else { ?>
											<a href="<?php echo base_url(); ?>telaah/detail_anggaran/pengguna_anggaran/<?php echo $v->skpd_id?>/<?php echo $jenis_anggaran ?>" class="btn btn-info btn-sm"><i class="fa fa-list"></i> Detail</a>
										<?php } ?>
									<?php } else { ?>
										<?php if(($v->skpd_id==3) && ($this->uri->segment(3)!="sekretariat")){?>
											<a href="<?php echo base_url(); ?>telaah/detail_anggaran/sekretariat" class="btn btn-info btn-sm"><i class="fa fa-list"></i> Detail</a>
										<?php } else if(($v->skpd_id==3) && ($this->uri->segment(3)=="sekretariat")){?>
											<a href="<?php echo base_url(); ?>telaah/detail_anggaran/pengguna_anggaran_sekretariat/<?php echo $v->bagian_id?>" class="btn btn-info btn-sm"><i class="fa fa-list"></i> Detail</a>
										<?php } else { ?>
											<a href="<?php echo base_url(); ?>telaah/detail_anggaran/pengguna_anggaran/<?php echo $v->skpd_id?>" class="btn btn-info btn-sm"><i class="fa fa-list"></i> Detail</a>
										<?php } ?>
									<?php } ?>
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