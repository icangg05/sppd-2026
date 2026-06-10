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
					<!-- form start -->
 					<?php echo form_open_multipart('telaah/disposisi/disposisi_update');?> 						<div class="table-responsive box-body">
 						<table class="table table-bordered ">
 							<table class="table table-bordered ">
								<tr>
 									<th class="col-md-3">Masukkan Password</th>
									<td><input type="password" id="inputPassword6" class="form-control mx-sm-3" aria-describedby="passwordHelpInline">
									<small id="passwordHelpInline" class="text-muted">
									  Must be 6-8 characters long.
									</small></td>
								</tr>
							</table>
						</table>
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
 	$(function() {
 		$('#datepicker2').datepicker({
 			format:'yyyy-mm-dd',
 			autoclose: true
 		});
 	});
 </script>
