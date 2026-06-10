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
              <h3 class="box-title">UBAH LAPORAN PENGELUARAN RILL</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
			<?php $telaah_id = base64_encode($this->encrypt->encode($entry[0]['telaah_id'], $this->session->userdata('encrypt_key')));	?>
			<?php echo form_open_multipart('telaah/laporan/pengeluaran_rill/update/'.$this->uri->segment(5).'?telaah_id='.$telaah_id.'=&&pegawai_id='.$entry[0]['pegawai_id'].''); ?>
              <div class="table-responsive box-body">
				 <table class="table table-bordered table-striped">
					<tr class="info">
					  <th class="col-md-3" colspan="2"><center>DATA LAPORAN PENGELUARAN RILL</center></th>
					</tr>
					<input type="hidden" class="form-control" name="telaah_id" value="<?php echo $entry[0]['telaah_id'];?>">
					<input type="hidden" class="form-control" name="pegawai_id" value="<?php echo $entry[0]['pegawai_id'];?>">
					<input type="hidden" class="form-control" name="pengeluaran_rill_id" value="<?php echo $entry[0]['pengeluaran_rill_id']; ?>">  
					<input type="hidden" class="form-control" name="posisi" value="<?php echo $posisi;?>">  
					<tr>
					  <th class="col-md-3">
						<?php 
						if(form_error('uraian')){
							echo form_error('uraian');
						} else { 
							echo "Uraian";
						} 
						?>
					  <span class="required">*</span></th>
					  <td><input type="text" class="form-control" name="uraian"  value="<?php if(set_value('uraian')){
										echo set_value('uraian');
									}else {
										echo $entry[0]['uraian'];
									} ; ?>"></td>
					</tr>
					<tr>
					  <th class="col-md-3">
						<?php 
						if(form_error('tarif')){
							echo form_error('tarif');
						} else { 
							echo "Tarif";
						} 
						?>
					  <span class="required">*</span></th>
					  <td><input type="text" class="form-control" name="tarif" class="form-control" onkeyup="formatRupiah(this, '.')" value="<?php if(set_value('tarif')){
										echo set_value('tarif');
									}else {
										echo number_format($entry[0]['tarif'], 0, ',', '.');
									} ; ?>"></td>
					
					</tr>
				</table>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
				 <div class="col-md-6">					
					<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan</button>
					<button type="reset" class="btn btn-warning btn-sm" ><i class="fa fa-repeat"></i> Reset</button>
					<a href="<?php echo base_url();?>telaah/laporan/pengeluaran_rill/index/<?php echo $this->uri->segment(5);?>?telaah_id=<?php echo $telaah_id;?>" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
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