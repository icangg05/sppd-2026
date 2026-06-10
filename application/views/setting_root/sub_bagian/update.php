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
 						<h3 class="box-title">EDIT SUB BAGIAN</h3>
 					</div>
 					<!-- /.box-header -->
 					<!-- form start -->

 					<?php echo form_open_multipart('setting_root/sub_bagian/update'); ?>
 					<div class="table-responsive box-body">
 						<?php if(validation_errors()){?>
 						<div class="alert alert-danger text-center">
 							<?php echo validation_errors(); ?>
 						</div>
 						<?php }?>
 						<input type="hidden" class="form-control" name="subbagian_id" value="<?php echo $entry[0]['subbagian_id'];?>">
 						<table class="table table-bordered table-striped">
							<tr>
								<th class="col-md-3">Bagian</th>
								<td><select class="form-control" name="bagian_id">
									<option value="">- Pilih Bagian -</option>
									<?php foreach($bagian as $v){
										if($entry[0]['bagian_id']== $v->bagian_id){
											echo "<option value='$v->bagian_id' selected>$v->nama_bagian</option>" ;
										} else {
											echo "<option value='$v->bagian_id'>$v->nama_bagian</option>" ;  
										}
									}?>
								</select></td>
							</tr>
 							<tr>
 								<th class="col-md-3">Nama Sub Bagian</th>
 								<td><input type="text" class="form-control" name="nama_subbagian" value="<?php echo $entry[0]['nama_subbagian'];?>"></td>
 							</tr>
 						</table>
 							</div>
 							<!-- /.box-body -->
 							<div class="box-footer">
 								<div class="col-md-6">					
 									<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan</button>
 									<button type="reset" class="btn btn-warning btn-sm" ><i class="fa fa-repeat"></i> Reset</button>
 									<a href="<?php echo base_url();?>setting_root/sub_bagian" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
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