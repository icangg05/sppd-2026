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
 						<h3 class="box-title">TAMBAH ANGGARAN</h3>
 					</div>
 					<!-- /.box-header -->
 					<!-- form start -->

 					<?php echo form_open_multipart('anggaran/create'); ?>
 					<div class="table-responsive box-body">
 						<?php if(validation_errors()){?>
 						<div class="alert alert-danger text-center">
 							<?php echo validation_errors(); ?>
 						</div>
 						<?php }?>
 						<table class="table table-bordered table-striped">
							<tr>
 								<th class="col-md-3">Tahun</th>
 								<td><select class="form-control" name="tahun">
 									<option value="">- Pilih tahun -</option>
									<?php
										for($i=2018;$i<=date('Y');$i++){
											if(date('Y')==$i){	
												echo "<option value=".$i." selected>".$i."</option>";
											} else {
												echo "<option value=".$i.">".$i."</option>";
											}
										}
									?>
									</select>
								</td>
 							</tr>
 							<tr>
 								<th class="col-md-3">Jenis Anggaran</th>
 								<td>
									<select class="form-control" name="jenis_anggaran">
										<option value="">- Pilih Jenis Perjalanan -</option>
										<option value="1">Perjalanan Dinas Dalam Daerah</option>
										<option value="2">Perjalanan Dinas Luar Daerah</option>
										<option value="3">Bimtek</option>
										<option value="4">Perjalanan Lainnya</option>
									</select>
								</td>
 							</tr>
 							<tr>
 								<th class="col-md-3">Program</th>
 								<td><input type="text" class="form-control" name="nama_program"></td>
 							</tr>
 							<tr>
 								<th class="col-md-3">Kegiatan</th>
 								<td><input type="text" class="form-control" name="nama_kegiatan"></td>
 							</tr>
 							<tr>
 								<th class="col-md-3">Kode Rekening</th>
 								<td><input type="text" class="form-control" name="no_rekening"></td>
 							</tr>
 							<tr>
 								<th class="col-md-3">Uraian</th>
 								<td><textarea class="form-control" name="uraian"></textarea></td>
 							</tr>
 							<tr>
 								<th class="col-md-3">Satuan Harga (Rp.)</th>
 								<td><input type="text" name="pagu" value="0" id="pagu" class="form-control" onkeyup="formatRupiah(this, '.')" placeholder="Pagu"/></td>
 							</tr>
 							<tr>
 								<th class="col-md-3">Mata Anggaran</th>
 								<td><select class="form-control" name="mata_anggaran">
										<option value="">- Pilih Mata Anggaran -</option>
										<option value="APBD">APBD</option>
										<option value="APBD-P">APBD-P</option>
									</select>
								</td>
 							</tr>
 						</table>
 					</div>
 					<!-- /.box-body -->
 					<div class="box-footer">
 						<div class="col-md-6">					
 							<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan</button>
 							<button type="reset" class="btn btn-warning btn-sm" ><i class="fa fa-repeat"></i> Reset</button>
 							<a href="<?php echo base_url();?>anggaran" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
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