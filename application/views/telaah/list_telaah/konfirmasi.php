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
              <h3 class="box-title">KONFIRMASI PELAKSANA</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
              <div class="box-body">
				<div class="col-md-6">
					<div class="form-group">
					  <label>Silahkan konfirmasi ulang pelaksana SPPD</label><br>
						  <input type="radio" name="gender" value="1"> AHMAD FEBRIANSYAH, SE<br>
						  <input type="radio" name="gender" value="2"> FITRIYANI, SE<br>
						  <input type="radio" name="gender" value="3"> AFRIYADIN, ST
					</div>
				 </div>
			  </div>
              <!-- /.box-body -->
              <div class="box-footer">
				 <div class="col-md-6">					
					<a href="<?php echo base_url();?>telaah_staf/berkas" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Konfirmasi</a>
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