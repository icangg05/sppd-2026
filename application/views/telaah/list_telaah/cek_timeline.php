 <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
 	<!-- Content Header (Page header) -->
 	<!-- Main content -->
 	<section class="content">
 		<div class="row">
 			<!-- left column -->
 			<div class="col-md-12">
 				<!-- general form elements -->
 				<div class="box box-primary">
 					<div class="box-header with-border">
 						<h3 class="box-title">
						<?php 
					switch($this->uri->segment(4)){
						case "esselon" 			: echo "Data Timeline (Esselon III, IV dan Staff)"; break;
						case "kadis" 			: echo "Data Timeline (Kepala OPD)"; break;
						case "dprd" 			: echo "Data Timeline (DPRD)"; break;
						case "sekda" 			: echo "Data Timeline (Sekda, Asisten dan Kabag)"; break;
						case "camat" 			: echo "Data Timeline (Camat)"; break;
						case "lurah" 			: echo "Data Timeline (Lurah)"; break;
						case "staff_dprd" 		: echo "Data Timeline (Staff DPRD)"; break;
						case "staff_camat" 		: echo "Data Timeline (Staff Camat)"; break;
						case "staff_lurah" 		: echo "Data Timeline (Staff Lurah)"; break;
						case "walikota" 		: echo "Data Timeline (Walikota)"; break;
						case "staff_setda" 		: echo "Data Timeline (Kasubag dan Staff Setda)"; break;
						case "sekwan" 			: echo "Data Timeline (Sekwan)"; break;
						case "kapus" 			: echo "Data Timeline (Kepala Puskesmas)"; break;
					}
					?> 
					</h3> 
						</h3>
 					</div>
 					<!-- /.box-header -->
 					<!-- form start -->
 					
 					<div class="table-responsive box-body">
 						<?php if(validation_errors()){?>
 						<div class="alert alert-danger text-center">
 							<?php echo validation_errors(); ?>
 						</div>
 						<?php }?>
 						<!-- <?php var_dump($data_telaah) ?> -->
 						<div class="table-responsive box-body">
 							Waktu Input Telaah: <b><?php echo $data_telaah[0]['telaah_waktuinput']?></b>
 							<table class="table table-bordered ">
 								<tr class="info">
 									<th class="col-md-3" colspan="4"><center>DATA TIMELINE</center></th>
 								</tr>
 								
								<?php if($label_nama1!=""){?>
 								<tr>
 									<?php echo form_open_multipart('list_telaah/esselon/update_timeline'); ?>
 									<th class="col-md-3"><?php echo $label_nama1?><br><?php echo $nama1?></th>
 									<td>
 										<?php 
 										if($tanggal1!='0000-00-00 00:00:00'){
 											echo $tanggal1;
 										}else{
 											$newtimestamp = strtotime($data_telaah[0]['telaah_waktuinput'].' + 15 minute');
 											echo date('Y-m-d H:i:s', $newtimestamp);
 										}
 										?>
 									</td>
 									<td> 
 										<input type="text" placeholder="Perintah Disposisi" class="form-control" name="<?php echo $input_nama1 ?>" value="<?php echo $timeline1?>">
 										<input type="hidden" name="telaah_id" class="form-control" value="<?php echo $data_telaah[0]['telaah_id']?>">
 										<input type="hidden" name="job" class="form-control" value="kabid">
 									</td>
 									<td> <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Update</button></td>
 									<?php echo form_close(); ?>
 								</tr>
								
								
								<?php } if($label_nama2!=""){?>
 								<tr>
 									<?php echo form_open_multipart('list_telaah/esselon/update_timeline'); ?>
 									<th class="col-md-3"><?php echo $label_nama2?><br><?php echo $nama2?></th>
 									<td> <?php 
 										if($tanggal2!='0000-00-00 00:00:00'){
 											echo $tanggal2;
 										}else{
 											$newtimestamp = strtotime($data_telaah[0]['telaah_waktuinput'].' + 30 minute');
 											echo date('Y-m-d H:i:s', $newtimestamp);
 										}
 										?>
 									</td>
 									<td> 
 										<input type="text" placeholder="Perintah Disposisi" name="<?php echo $input_nama2 ?>" class="form-control" value="<?php echo $timeline2 ?>">
 										<input type="hidden" name="telaah_id" class="form-control" value="<?php echo $data_telaah[0]['telaah_id']?>">
 										<input type="hidden" name="job" class="form-control" value="sekdis">
 									</td>
 									<td> <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Update</button></td>
 									<?php echo form_close(); ?>
 								</tr>
								
								
								<?php } if($label_nama3!=""){?>
 								<tr>
 									<?php echo form_open_multipart('list_telaah/esselon/update_timeline'); ?>
 									<th class="col-md-3"><?php echo $label_nama3?><br><?php echo $nama3?></th>
 									<td> 
 										<?php 
 										if($tanggal3!='0000-00-00 00:00:00'){
 											echo $tanggal3;
 										}else{
 											$newtimestamp = strtotime($data_telaah[0]['telaah_waktuinput'].' + 45 minute');
 											echo date('Y-m-d H:i:s', $newtimestamp);
 											
 										}
 										?>
 									</td>
 									<td> 
 										<input type="text" placeholder="Perintah Disposisi" class="form-control" name="<?php echo $input_nama3 ?>" value="<?php echo $timeline3?>">
 										<input type="hidden" name="telaah_id" class="form-control" value="<?php echo $data_telaah[0]['telaah_id']?>">
 										<input type="hidden" name="job" class="form-control" value="kadis">
 									</td>
 									<td> <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Update</button></td>
 									<?php echo form_close(); ?>
 								</tr>
 								
								<?php } ?>
 								
 							</table>
 						</div>
 					</div>
 					<!-- /.box-body -->
 					<div class="box-footer">
 						<div class="col-md-6">
							<?php if($this->ion_auth->user()->row()->id==1){?>
								<a href="<?php echo base_url();?>telaah/list_telaah/index_admin/<?php echo $this->uri->segment(4)?>" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
							<?php } else { ?>				
								<a href="<?php echo base_url();?>telaah/list_telaah/index/<?php echo $this->uri->segment(4)?>" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
							<?php } ?>		 
						</div>
					</div>
			
		</div>
		<!-- /.box -->
		
	</div>
	<!--/.col (left) -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
