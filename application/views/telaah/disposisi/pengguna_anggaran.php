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
  						<h3 class="box-title">DATA PENGGUNAAN ANGGARAN</h3>
  					</div>
  					<div class="box-header with-border">
						<div class="col-md-9">
							<?php if($this->uri->segment(3)=="pengguna_anggaran_sekretariat"){ ?>
								<a href="<?php echo base_url();?>telaah/detail_anggaran/sekretariat" class="btn btn-warning btn-sm "><i class="fa fa-close"></i> Kembali</a>
							<?php } else { ?>
								<a href="<?php echo base_url();?>telaah/detail_anggaran" class="btn btn-warning btn-sm "><i class="fa fa-close"></i> Kembali</a>
							<?php } ?>
						  
						</div>
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
  								<th style="width: 40px">Program</th>
  								<!--th style="width: 40px">Kegiatan</th-->
  								<th style="width: 40px">Uraian</th>
  								<th style="width: 40px">Satuan Harga</th>
  								<th style="width: 40px">Sisa Anggaran</th>
  								<th style="width: 40px">Anggaran Terpakai</th>
  							</tr>
  							<?php 
  							$no=1;
  							foreach($telaah_staf as $v){
  								?>
  								<tr>
  									
  									<td><?php echo $no;?></td>
  									<!--td><!--?php echo $v->nama_program?></td-->
  									<td><?php echo $v->nama_program?></td>
  									<td><?php echo $v->uraian?></td>
  									<?php 
									if($this->uri->segment(3)=="pengguna_anggaran_sekretariat"){
										$sisa_anggaran =  $this->m_walikota->cek_sisa_anggaran_sekretariat_bagian($v->id_anggaran, $v->bagian_id);
										$sisa_anggaran2 =  $this->m_walikota->cek_sisa_anggaran_sekretariat_bagian2($v->id_anggaran, $v->bagian_id);
										$sisa_anggaran = $sisa_anggaran[0]->tes + $sisa_anggaran2[0]->jumlah;
									} else {
										$sisa_anggaran =  $this->m_dprd->cek_sisa_anggaran($v->id_anggaran);
										$sisa_anggaran2 =  $this->m_dprd->cek_sisa_anggaran2($v->id_anggaran);
										$sisa_anggaran = $sisa_anggaran[0]->tes + $sisa_anggaran2[0]->jumlah;
									}
									?>
  									<td>Rp. <?php echo number_format($v->pagu, 0, ',', '.'); ?></td>
  									<td>Rp. <?php echo number_format($v->pagu - $sisa_anggaran, 0, ',', '.'); ?></td>
  									<td>Rp. <?php echo number_format($sisa_anggaran, 0, ',', '.'); ?></td>
  									
  								</tr>
  								<?php 
  								$no++;
  							} 
  							?>
  						</table>
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