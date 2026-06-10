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
 						<h3 class="box-title">EDIT KABUPATEN/KOTA</h3>
 					</div>
 					<!-- /.box-header -->
 					<!-- form start -->

 					<?php echo form_open_multipart('setting_root/kabupaten/update'); ?>
 					<div class="table-responsive box-body">
 						<?php if(validation_errors()){?>
 						<div class="alert alert-danger text-center">
 							<?php echo validation_errors(); ?>
 						</div>
 						<?php }?>
 						<input type="hidden" class="form-control" name="kabkot_id" value="<?php echo $entry[0]['kabkot_id'];?>">
 						<table class="table table-bordered table-striped">
 							<tr>
 								<th class="col-md-3">Nama Kabupaten/Kota</th>
 								<td><input type="text" class="form-control" name="kabupaten_kota" value="<?php echo $entry[0]['kabupaten_kota'];?>"></td>
 							</tr>
 							<tr>
 								<th class="col-md-3">Latitude</th>
 								<td>
 									<input type="text" name="latitude" class="form-control" value="<?php echo $entry[0]['latitude'];?>"/>
								</td>
 							</tr>
 							<tr>
 								<th class="col-md-3">Langitude</th>
 								<td>
 									<input type="text" name="longitude" class="form-control" value="<?php echo $entry[0]['longitude'];?>"/>
								</td>
 							</tr>
							<tr>
								<th class="col-md-3">Provinsi</th>
								<td><select class="form-control" name="provinsi_id">
									<option value="">- Pilih Provinsi -</option>
									<?php foreach($provinsi as $v){
										if($entry[0]['provinsi_id']== $v->provinsi_id){
											echo "<option value='$v->provinsi_id' selected>$v->provinsi</option>" ;
										} else {
											echo "<option value='$v->provinsi_id'>$v->provinsi</option>" ;  
										}
									}?>
								</select></td>
							</tr>
 						</table>
 							</div>
 							<!-- /.box-body -->
 							<div class="box-footer">
 								<div class="col-md-6">					
 									<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan</button>
 									<button type="reset" class="btn btn-warning btn-sm" ><i class="fa fa-repeat"></i> Reset</button>
 									<a href="<?php echo base_url();?>setting_root/kabupaten" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
 								</div>
 							</div>
 							<?php echo form_close(); ?>
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
 		<script>
 			var ckeditor = CKEDITOR.replace('ckeditor');
 		</script>
 		<script type="text/javascript">
 			$(function() {
 				$('#datepicker').datepicker({
 					format:'yyyy-mm-dd',
 					autoclose: true
 				});
 			});
 		</script>