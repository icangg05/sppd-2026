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
            <h3 class="box-title">TAMBAH PEGAWAI
            </h3> 					
          </div> 					
          <!-- /.box-header --> 					
          <!-- form start --> 					 					
          <?php echo form_open_multipart('setting_admin/pegawai/create'); ?> 					
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
                <th class="col-md-3">NIP
                </th> 								
                <td>
                  <input type="text" class="form-control" name="pegawai_nip">
                </td> 							
              </tr> 							
              <tr> 								
                <th class="col-md-3">NIK
                </th> 								
                <td>
                  <input type="text" class="form-control" name="pegawai_nik">
                </td> 							
              </tr> 							
              <tr> 								
                <th class="col-md-3">Nama Pegawai
                </th> 								
                <td>
                  <input type="text" class="form-control" name="pegawai_nama">
                </td> 							
              </tr>							
              <tr class="info">								
                <th class="col-md-3" colspan="2">
                  <center>KEPEGAWAIAN
                  </center>
                </th>							
              </tr>							
              <tr>								
                <th class="col-md-3">Golongan
                </th>								
                <td>
                  <select class="form-control" name="pegawai_golongan">									
                    <option value="">- Pilih Golongan -
                    </option>									
                    <?php foreach($golongan as $v){										
						echo "<option value='$v->golongan'>$v->golongan</option>" ;									}?>									
                  </select>								
                </td>							
              </tr>							
              <tr>								
                <th class="col-md-3">Jabatan
                </th>								
                <td>
                  <select class="form-control" name="pegawai_jabatan">									
                    <option value="">- Pilih Jabatan -
                    </option>									
                    <?php foreach($jabatan as $v){										
						echo "<option value='$v->jabatan_id'>$v->nama_jabatan</option>" ;
					}?>								
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
              <?php  if ($this->ion_auth->user()->row()->jenis_skpd == 3) { ?> 							
              <tr>							  
                <th class="col-md-3">Bagian
                </th>							  
                <td>
                  <select class="form-control" name="bagian_id">									
                    <?php  if ($staff_sekda) { ?> 									  
                    <?php foreach($bagian as $v){											
						echo "<option value='$v->bagian_id'>$v->nama_bagian</option>" ;
					}?>									
                    <?php } else { ?> 									
                    <option value="">- Pilih bagian -</option>									  
                    <?php foreach($bagian as $v){											
						echo "<option value='$v->bagian_id'>$v->nama_bagian</option>" ; 
					}?>									
                    <?php } ?> 								  
                  </select>							  
                </td>							
              </tr>						
              <?php } ?> 							
              <!--tr>							  <th class="col-md-3">Tanggal Awal Menjabat</th>							  <td>								 <div class="input-group col-sm-3">									 <input id="datepicker" type="text" class="form-control" name="pegawai_tanggalmenjabat">									 <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>								 </div>							  </td>							</tr-->							
              <tr>							  
                <th class="col-md-3">Tanda Tangan
                </th>							  
                <td>
                  <input type="file" class="form-control" name="userfile" >
                </td>							
              </tr>	
			  <tr>
				<th class="col-md-3">Status Tanda Tangan</th>
				<td>
					<select class="form-control" name="status_tandatangan">
						<option value="1" <?php if (set_value('status_tandatangan')=="1"){ echo "selected";} ?> >Aktif</option>
						<option value="0" <?php if (set_value('status_tandatangan')=="0"){ echo "selected";} ?> >Tidak Aktif</option>
					</select>
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
              <a href="<?php echo base_url();?>setting_admin/pegawai" class="btn btn-danger btn-sm">
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
