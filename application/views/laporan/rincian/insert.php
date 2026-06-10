 <!-- Content Wrapper. Contains page content -->
<style>
    .error {
    color: red;
	font-weight: bold;
}
</style> <!-- Content Wrapper. Contains page content -->
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
              <h3 class="box-title">TAMBAH LAPORAN RINCIAN BIAYA PERJALANAN DINAS</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
			<?php 
				$telaah_id2 = base64_encode($this->encrypt->encode($telaah_id, $this->session->userdata('encrypt_key')));	
			?>
			<?php echo form_open_multipart('telaah/laporan/rincian/create/'.$this->uri->segment(5).'?telaah_id='.$telaah_id2.'=&&pegawai_id='.$pegawai_id.''); ?>
              <div class="table-responsive box-body">
				 <table class="table table-bordered table-striped">
					<tr class="info">
					  <th class="col-md-3" colspan="2"><center>DATA LAPORAN RINCIAN BIAYA PERJALANAN DINAS</center></th>
					</tr>
					
					<input type="hidden" class="form-control" name="telaah_id" value="<?php echo $telaah_id;?>">
					<input type="hidden" class="form-control" name="pegawai_id" value="<?php echo $pegawai_id;?>">
					<input type="hidden" class="form-control" name="posisi" value="<?php echo $posisi;?>">  
					<tr>
					  <th class="col-md-3">
						<?php 
						if(form_error('kategori_biaya')){
							echo form_error('kategori_biaya');
						} else { 
							echo "Kategori Biaya";
						} 
						?>
					  <span class="required">*</span></th>
					  <td>
						<select class="form-control" name="kategori_biaya" onchange=" if (this.selectedIndex==3){ 
 												document.getElementById('ldlp').style.display = 'inline';
 											} else {
 												document.getElementById('ldlp').style.display = 'none'; 
 											} ;">
							<option value="">- Pilih Kategori Biaya -</option>
							<option value="5" <?php if(set_value('kategori_biaya')==5){echo "selected";}?>>Lumsum</option>
							<option value="1" <?php if(set_value('kategori_biaya')==1) { echo "selected";}?> >Penginapan</option>
							<option value="2" <?php if(set_value('kategori_biaya')==2) { echo "selected";}?> >Sewa Kendaraan</option>
							<option value="3" <?php if(set_value('kategori_biaya')==3) { echo "selected";}?> >Transport</option>
							<option value="6" <?php if(set_value('kategori_biaya')==6) {echo "selected";}?>>Representasi</option>
							<option value="4" <?php if(set_value('kategori_biaya')==4) { echo "selected";}?> >Biaya Lainnya</option>
						</select>
						<?php 
							if(set_value('kategori_biaya')==3) { 
								echo "<span id='ldlp' style='display:inline;'>";
							} else {
								echo "<span id='ldlp' style='display:none;'>";
							}
						?>
							<br><input type="text" class="form-control" name="nama_maspakai" class="form-control" placeholder="Nama Maskapai/Kendaraan">
							<br><input type="text" class="form-control" name="no_tiket" class="form-control" placeholder="Nomor Tiket">
						</span>
					  </td>
					</tr>
					<tr>
					  <th class="col-md-3">
						<?php 
						if(form_error('keterangan')){
							echo form_error('keterangan');
						} else { 
							echo "Keterangan";
						} 
						?><span class="required">*</span>
					  </th>
					  <td><input type="text" class="form-control" name="keterangan"></td>
					</tr>
					<tr>
					  <th class="col-md-3">
					  <?php 
						if(form_error('item')){
							echo form_error('item');
						} else { 
							echo "Item";
						} 
						?><span class="required">*</span>
					  </th>
					  <td><input type="text" class="form-control" name="item" value="0" class="form-control" onkeyup="formatRupiah(this, '.')"></td>
					</tr>
					<tr>
					  <th class="col-md-3">
					  <?php 
						if(form_error('tarif')){
							echo form_error('tarif');
						} else { 
							echo "Tarif";
						} 
						?><span class="required">*</span>
					  </th>
					  <td><input type="text" class="form-control" name="tarif" value="0" class="form-control" onkeyup="formatRupiah(this, '.')"></td>
					</tr>
					<tr>
					  <th class="col-md-3">Foto</th>
					  <td><input type="file" class="form-control" name="userfile"></td>
					</tr>
				</table>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
				 <div class="col-md-6">					
					<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan</button>
					<button type="reset" class="btn btn-warning btn-sm" ><i class="fa fa-repeat"></i> Reset</button>
					<a href="<?php echo base_url();?>telaah/laporan/rincian/index/<?php echo $this->uri->segment(5);?>?telaah_id=<?php echo $telaah_id2;?>" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
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
		if(document.getElementById('ckeditor')){
			var ckeditor = CKEDITOR.replace('ckeditor');
		}
  </script>
  <script type="text/javascript">
	   $(function() {
		 $('#datepicker').datepicker({
			 format:'yyyy-mm-dd',
			 autoclose: true
		});
	   });
   </script>