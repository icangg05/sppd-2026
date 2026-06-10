<!-- Content Wrapper. Contains page content -->  
<div class="content-wrapper">    
  <!-- Content Header (Page header) -->    
  <!-- Main content -->    
  <section class="content">      
    <!-- Small boxes (Stat box) -->      
    <div class="row">        
      <!-- ./col -->        
      <div class="col-lg-12 col-xs-12">          
        <div class="box box-success">            
          <div class="box-header with-border">              
            <h3 class="box-title">Walikota
            </h3>            
          </div>            
          <div class="box-header with-border">             
            <?php echo form_open("setting_admin/walikota/search");?>             
            <div class="col-md-7">              
              <a href="<?php echo base_url();?>setting_admin/walikota/create_view" class="btn btn-success btn-flat">Tambah Data
              </a>              
              <a href="<?php echo base_url();?>setting_admin/walikota" class="btn btn-warning btn-flat">Refresh
              </a>              
              <!--a href="<!--?php echo base_url();?>walikota/import_view" class="btn btn-primary btn-flat">Import Data walikota</a-->            
            </div>				            
            <div class="col-md-2">             
              <select class="form-control" name="column">              
                <option value="pegawai_nik">NIK
                </option>              
                <option value="pegawai_nama">Nama
                </option>            
              </select>          
            </div>          
            <div class="col-md-3">            
              <div class="input-group">              
                <input type="text" class="form-control" name="data" placeholder="Cari ...">              
                <span class="input-group-btn">                
                  <input type="submit" name="submit" class="btn btn-info btn-flat" value="Go">              
                </span>            
              </div>          
            </div>          
            <?php echo form_close();?>        
          </div>        
          <!-- /.box-header -->        
          <div class="table-responsive box-body">         
            <?php         $message = $this->session->flashdata('notif');         if($message){           echo '<p class="alert alert-info text-center"><b>'.$message .'</b></p>';         }         ?>         
            <table class="table table-bordered table-striped table-hover">          
              <tr class='info'>            
                <th style="width: 20%">NIK
                </th>              
                <th style="width: 30%">Nama
                </th>            
                <th style="width: 30%">Jabatan
                </th>            
                <th style="width: 20%">Aksi
                </th>          
              </tr>          
              <?php foreach($walikota as $v){           
                $walikota_id = base64_encode($this->encrypt->encode($v->pegawai_id, $this->session->userdata('encrypt_key')));	           ?>           
              <tr>            
                <td>             
                  <b><?php echo $v->pegawai_nik;?></b>
                </td>          
                <td>             
                  <b><?php echo $v->pegawai_nama;?></b>
                </td>           
                <td>
                  <?php echo $v->pegawai_namajabatan;?>
                </td>  
                <td>
                  <a href="<?php echo base_url();?>setting_admin/walikota/update_view?walikota_id=<?php echo $walikota_id?>" class="btn btn-sm btn-flat btn-warning">
                    <i class="fa fa-edit">
                    </i> EDIT
                  </a>             
                  <a href="<?php echo base_url();?>setting_admin/walikota/delete?walikota_id=<?php echo $walikota_id?>" class="btn btn-sm btn-flat btn-danger" onclick="return confirm('Anda Yakin ?');">
                    <i class="fa fa-trash">
                    </i> HAPUS
                  </a>
                </td>           
              </tr>           
              <?php } ?>         
            </table>       
          </div>       
          <!-- /.box-body -->       
          <div class="box-footer clearfix">         
            <?php echo $links?>       
          </div>     
        </div>     
        <!-- /.box -->   
      </div>   
      <!-- ./col --> 
    </div> 
    <!-- /.row --> 
  </section>
  <!-- /.content -->
</div>  
<!-- /.content-wrapper -->
