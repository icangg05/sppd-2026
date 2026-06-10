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
  						<h3 class="box-title">TELAAH STAF</h3>
  					</div>
  					<!-- /.box-header -->
  					<div class="table-responsive box-body">
  						<?php
  						$message = $this->session->flashdata('notif');
  						if($message){
  							echo '<p class="alert alert-info text-center"><b>'.$message .'</b></p>';
  						}
  						?>
  						<div class="table-responsive box-body">
  							<table class="table table-bordered table-striped table-hover">
  								<tr class='info'>
  									<th style="width: 5px">No</th>
  									<th style="width: 40px">Tanggal Pengajuan</th>
  									<th style="width: 150px">Perihal (Maksud Perjalanan Dinas)</th>
  									<th style="width: 200px">Pelaksana</th>
									<th style="width: 125px">Anggaran Terpakai</th>
                    <?php if($this->ion_auth->user()->row()->id==1){ ?>
                    <th style="width: 100px">#</th>
                    <?php } ?>
  								</tr>
  								<?php 
  								$number=$number+1;
  								foreach($telaah as $v){	
  									?>
  									<tr>
  										<td><?php echo $number;?></td>
  										<td><?php echo $v->telaah_waktuinput?></td>
  										<td><?php echo $v->telaah_perihal?></td>
  										<td><?php echo $v->pegawai_nama?></td>
										<?php 
											$rincian_biaya =  $this->m_rincian->get3($v->telaah_id,$v->pegawai_id);
											$pengeluaran_rill =  $this->m_pengeluaran_rill->get3($v->telaah_id,$v->pegawai_id);
										?>
										<td>Rp. <?php echo number_format($rincian_biaya[0]['total']+$pengeluaran_rill[0]['total'], 0, ',', '.'); ?></td>
									</tr>
                  
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