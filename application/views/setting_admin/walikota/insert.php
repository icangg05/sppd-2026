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
            <h3 class="box-title">TAMBAH PIMPINAN
            </h3> 					
          </div> 					
          <!-- /.box-header --> 					
          <!-- form start --> 					 					
          <?php echo form_open_multipart('setting_admin/walikota/create'); ?> 					
          <div class="table-responsive box-body"> 						
            <?php if(validation_errors()){?> 						
            <div class="alert alert-danger text-center"> 							
              <?php echo validation_errors(); ?> 						
            </div> 						
            <?php }?> 						
            <table class="table table-bordered table-striped"> 							
              <tr class="info"> 								
                <th class="col-md-3" colspan="2">
                  <center>DATA DIRI
                  </center>
                </th> 							
              </tr>							
              <tr> 								
                <th class="col-md-3">NIK
                </th> 								
                <td>
                  <input type="text" class="form-control" name="pegawai_nik">
                </td> 							
              </tr> 							
              <tr> 								
                <th class="col-md-3">Nama Walikota/Wakil Walikota
                </th> 								
                <td>
                  <input type="text" class="form-control" name="pegawai_nama">
                </td> 							
              </tr>											
              <tr>								
                <th class="col-md-3">Jabatan
                </th>								
                <td>
                  <select class="form-control" name="pegawai_jabatan">									
                    <option value="">- Pilih Jabatan -
                    </option>									
                    <option value='1'>WALIKOTA</option>
                    <option value='14'>WAKIL WALIKOTA</option>
                    <option value='16'>LAINNYA</option>		
                  </select>
                </td>							
              </tr>							
              <tr>								
                <th class="col-md-3">Nama jabatan
                </th>								
                <td>
                  <input type="text" class="form-control" name="pegawai_namajabatan">
                </td>							
              </tr>		
            </table>			
          </div>			
          <!-- /.box-body -->			
          <div class="box-footer">				
            <div class="col-md-6">										
              <button type="submit" class="btn btn-success btn-sm">
                <i class="fa fa-save">
                </i> Simpan
              </button>					
              <button type="reset" class="btn btn-warning btn-sm" >
                <i class="fa fa-repeat">
                </i> Reset
              </button>					
              <a href="<?php echo base_url();?>setting_admin/walikota" class="btn btn-danger btn-sm">
                <i class="fa fa-close">
                </i> Kembali
              </a>				
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
<script>	var ckeditor = CKEDITOR.replace('ckeditor');
</script>
<script type="text/javascript">	$(function() {
    $('#datepicker').datepicker({
      format:'yyyy-mm-dd',			autoclose: true		}
                               );
  }
                                 );
</script>
