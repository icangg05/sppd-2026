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
					  <h3 class="box-title">EXPORT LAPORAN PERJALANAN</h3>
					</div>
					<div class="box-header with-border">
						<?php if ($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->id == 1) { ?>
							<!--div class="col-lg-6 col-xs-12">
							  <a href="<?php echo base_url(); ?>export/cetak_lpdd"><button class="btn btn-success"><span class="fa fa-file-excel-o"></span> Laporan Perjalanan Dalam Daerah</button></a>
							  <a href="<?php echo base_url(); ?>export/cetak_lpld"><button class="btn btn-info"><span class="fa fa-file-excel-o"></span> Laporan Perjalanan Luar Daerah</button></a>
							</div-->
						<?php } else { ?>
							<div class="col-lg-12 col-xs-12">
							  <a href="<?php echo base_url(); ?>setting_root/export/cetak_lpdd"><button class="btn btn-success"><span class="fa fa-file-excel-o"></span> Laporan Perjalanan Dalam Daerah</button></a>
							  <a href="<?php echo base_url(); ?>setting_root/export/cetak_lpld"><button class="btn btn-info"><span class="fa fa-file-excel-o"></span> Laporan Perjalanan Luar Daerah</button></a>
							  <a href="<?php echo base_url(); ?>setting_root/export/cetak_laporan"><button class="btn btn-warning"><span class="fa fa-file-excel-o"></span> Rekap Laporan Perjalanan</button></a>
							</div>
						<?php } ?>
						<br><br><br><br>
						<?php echo form_open("setting_root/export/search");?>             
							<div class="col-md-7">              </div>				            
							<div class="col-md-2">             
							<select class="form-control" name="column">              
								<option value="pegawai_nip">NIP
								</option>              
								<option value="pegawai_nama">Nama Pegawai
								</option>            
							</select>          
							</div>          
							<div class="col-md-3">            
							<div class="input-group">              
								<input type="text" class="form-control" name="data" placeholder="Cari ...">              
								<span class="input-group-btn">                
								<input type="submit" name="submit" class="btn btn-info btn-flat" value="Go">              
								</span>            
							</div>          
							</div>          
						<?php echo form_close();?>        
					</div>
					<div class="table-responsive box-body">         
					<?php
						$message = $this->session->flashdata('notif');
						if ($message) {
							echo '<p class="alert alert-info text-center"><b>' .
								$message .
								'</b></p>';
						}
					?>         
					<table class="table table-bordered table-striped table-hover">          
						<tr class='info'>            
							<th style="width: 40%">Nama pegawai</th>            
							<th style="width: 20%">Jabatan</th>            
							<th style="width: 20%">Pangkat</th>         		
							<th style="width: 10%">Aksi</th>          
						</tr>          
					<?php foreach ($pegawai as $v) {
						$pegawai_id = base64_encode($this->encrypt->encode($v->pegawai_id,$this->session->userdata('encrypt_key')) ); ?>           
					<tr>            
						<td>             
							<b>
								<?php echo $v->pegawai_nama; ?>
							</b>
							<br>             NIP : 
							<?php echo $v->pegawai_nip; ?>
							<br>           
						</td>           
						<td>
							<?php echo $v->pegawai_namajabatan; ?>
						</td>           
						<td>
							<?php echo $v->pangkat; ?>
						</td> 
						<td>
							<a href="<?php echo base_url(); ?>setting_root/export/laporan_perjalanan/<?php echo $pegawai_id; ?>" class="btn btn-sm btn-flat btn-info">
								<i class="fa fa-list">
								</i> Lihat Laporan Perjalanan Dinas
							</a>          
						</td>           
					</tr>           
					<?php
					} ?>         
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