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
						<h3 class="box-title">TAMBAH USER</h3>
					</div>
					<!-- /.box-header -->
					<!-- form start -->
					
					<form action="<?php echo base_url();?>setting_root/user/create" method="post" accept-charset="utf-8" id="form1" name="form1">
						<div class="table-responsive box-body">
							<?php if(validation_errors()){?>
							<div class="alert alert-danger text-center">
								<?php echo validation_errors(); ?>
							</div>
							<?php }?>
							<div class="col-md-12">
								<div class="form-group">
									<label>Nama Depan</label>
									<input type="text" class="form-control" name="first_name">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Nama Belakang</label>
									<input type="text" class="form-control" name="last_name">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Telepon</label>
									<input type="text" class="form-control" name="phone">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>User Name</label>
									<input type="text" class="form-control" name="username">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Email</label>
									<input type="text" class="form-control" name="email">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Password</label>
									<input type="password" class="form-control" name="password">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Ulangi Password</label>
									<input type="password" class="form-control" name="password_confirm">
								</div>
							</div>
							
							<div class="col-md-12">
								<div class="form-group">
									<label>SKPD</label>
									<select class="form-control select2" name="skpd_id" id="skpd" onchange="tampilkan()">
										<option >- Pilih -</option>
										<?php
										foreach ($skpd as $s) {
											echo '<option value="'.$s->skpd_id.'">'.$s->skpd_nama.'</option>';
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Pilih Group</label>
									<select class="form-control" name="groups[]" id="group" onchange="tampilkan()">
										<option value="">- Pilih Group -</option>
										<?php foreach($groups as $v){
											echo "<option value='$v->id'>$v->name</option>" ;
										}?>
									</select>
								</div>
							</div>
							<span id="1" style="display:none;">
								<div class="col-md-12">
									<div class="form-group">
										<label>Pilih Sub Bagian</label>
										<select class="form-control" name="subbagian">
											<option value="">- Pilih Sub Bagian -</option>
											<?php
											foreach ($subbagian as $s) {
												echo '<option value="'.$s->subbagian_id.'">'.$s->nama_subbagian.'</option>';
											}
											?>
										</select>
									</div>
								</div>
							</span>
							<span id="2" style="display:none;">
								<div class="col-md-12">
									<div class="form-group">
										<label>Pilih Bagian</label>
										<select class="form-control" name="bagian">
											<option value="">- Pilih Bagian -</option>
											<?php
											foreach ($bagian as $s) {
												echo '<option value="'.$s->bagian_id.'">'.$s->nama_bagian.'</option>';
											}
											?>
										</select>
									</div>
								</div>
							</span>
							<span id="3" style="display:none;">
								<div class="col-md-12">
									<div class="form-group">
										<label>Pilih Asisten</label>
										<select class="form-control" name="asisten">
											<option value="">- Pilih Asisten -</option>
											<?php
											foreach ($asisten as $s) {
												echo '<option value="'.$s->asisten_id.'">'.$s->nama_asisten.'</option>';
											}
											?>
										</select>
									</div>
								</div>
							</span>
						</div>
						<!-- /.box-body -->
						<div class="box-footer">
							<div class="col-md-6">					
								<button type="submit" class="btn btn-success btn-flat">Simpan</button>
								<a href="<?php echo base_url();?>setting_root/user" class="btn btn-warning btn-flat">Kembali</a>
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
	<script>
		function tampilkan(){
			var id_user=document.getElementById("form1").skpd.value;
			var group=document.getElementById("form1").group.value;
			if (id_user=="3" && group=="1")
			{
				document.getElementById('1').style.display = 'inline'; 
				document.getElementById('2').style.display = 'none'; 
				document.getElementById('3').style.display = 'none'; 
			}
			else if (id_user=="3" && group=="2")
			{
				document.getElementById('1').style.display = 'none'; 
				document.getElementById('2').style.display = 'inline'; 
				document.getElementById('3').style.display = 'none'; 
			}
			else if (id_user=="3" && group=="9")
			{
				document.getElementById('1').style.display = 'none'; 
				document.getElementById('2').style.display = 'inline'; 
				document.getElementById('3').style.display = 'none'; 
			}
			else if (id_user=="3" && group=="5")
			{
				document.getElementById('1').style.display = 'none'; 
				document.getElementById('2').style.display = 'none'; 
				document.getElementById('3').style.display = 'inline'; 
			}
			else 
			{
				document.getElementById('1').style.display = 'none'; 
				document.getElementById('2').style.display = 'none'; 
				document.getElementById('3').style.display = 'none'; 
			}
		}
	</script>
<script>
	$(function () {
		//Initialize Select2 Elements
		$(".select2").select2();
	});
</script>