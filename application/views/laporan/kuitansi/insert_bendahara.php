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
              <h3 class="box-title">PILIH BENDAHARA</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            

			<?php echo form_open_multipart('telaah/laporan/kuitansi/create_bendahara'); ?>
              <div class="box-body">
			 <?php if(validation_errors()){?>
				  <div class="alert alert-danger text-center">
					<?php echo validation_errors(); ?>
				  </div>
			 <?php }?>
				 <table class="table table-bordered table-striped">
					<tr class="info">
					  <th class="col-md-3" colspan="2"><center>DATA BENDAHARA</center></th>
					</tr>
					<?php 
						$telaah_id2 = base64_encode($this->encrypt->encode($telaah_id, $this->session->userdata('encrypt_key')));	
					?>
					<input type="hidden" class="form-control" name="telaah_id" value="<?php echo $telaah_id;?>">
					<input type="hidden" class="form-control" name="pegawai_id" value="<?php echo $pegawai_id;?>">
					<input type="hidden" class="form-control" name="posisi" value="<?php echo $posisi;?>">  
					
					<tr>
					  <th class="col-md-3">Bendahara</th>
					  <td>
						<select class="form-control select2" name="telaah_ttdbendahara">
							<option value="">- Pilih-</option>
							<?php foreach ($bendahara as $v){ ?>
								<option value="<?php echo $v->pegawai_id?>"><?php echo $v->pegawai_nama;?></option>
							<?php } ?>
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
					<a href="<?php echo base_url();?>telaah/laporan/kuitansi/index/<?php echo $this->uri->segment(5);?>?telaah_id=<?php echo $telaah_id2;?>" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
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
<script>
	$(function () {
		//Initialize Select2 Elements
		$(".select2").select2();
	});
</script>