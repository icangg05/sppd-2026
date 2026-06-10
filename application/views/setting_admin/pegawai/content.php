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
            <h3 class="box-title">PEGAWAI
            </h3>            
          </div>            
          <div class="box-header with-border">             
            <?php echo form_open("setting_admin/pegawai/search");?>             
            <div class="col-md-7">              
              <a href="<?php echo base_url();?>setting_admin/pegawai/create_view" class="btn btn-success btn-flat">Tambah Data
              </a>              
              <a href="<?php echo base_url();?>setting_admin/pegawai" class="btn btn-warning btn-flat">Refresh
              </a>              
              <!--a href="<!--?php echo base_url();?>pegawai/import_view" class="btn btn-primary btn-flat">Import Data Pegawai</a-->            
            </div>				            
            <div class="col-md-2">             
              <select class="form-control" name="column">              
                <option value="pegawai_nip">NIP
                </option>              
                <option value="pegawai_nama">Nama Pegawai
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
                <th style="width: 100px">Nama pegawai
                </th>            
                <th style="width: 50px">Jabatan
                </th>            
                <th style="width: 50px">Pangkat
                </th>         
				<th style="width: 200px">Tanda Tangan
				</th>
				<th style="width: 50px">Status Tanda Tangan
				</th>				
                <th style="width: 100px">Aksi
                </th>          
              </tr>          
              <?php foreach($pegawai as $v){           $pegawai_id = base64_encode($this->encrypt->encode($v->pegawai_id, $this->session->userdata('encrypt_key')));	           ?>           
              <tr>            
                <td>             
                  <b>
                    <?php echo $v->pegawai_nama;?>
                  </b>
                  <br>             NIP : 
                  <?php echo $v->pegawai_nip;?>
                  <br>           
                </td>           
                <td>
                  <?php echo $v->pegawai_namajabatan;?>
                </td>           
                <td>
                  <?php echo $v->pangkat;?>
                </td>  
				<td>
					<?php if($v->pegawai_tandatangan){?>
						<img src="<?php echo base_url();?>upload/tanda_tangan/<?php echo $v->pegawai_tandatangan;?>" width="100" height="80">
					<?php }?>
				</td>
				<td>
					<?php if($v->pegawai_tandatangan){?>
						<?php
							if($v->status_tandatangan==0) {
								echo '<span class="label label-danger">Tidak Aktif</span></td>';
							} else if($v->status_tandatangan==1) {
								echo '<span class="label label-success">Aktif</span></td>';
							} 
						?>
					<?php } ?>
				</td>
                <td>
                  <a href="<?php echo base_url();?>setting_admin/pegawai/update_view?pegawai_id=<?php echo $pegawai_id?>" class="btn btn-sm btn-flat btn-warning">
                    <i class="fa fa-edit">
                    </i> EDIT
                  </a>             
                  <a href="<?php echo base_url();?>setting_admin/pegawai/delete?pegawai_id=<?php echo $pegawai_id?>" class="btn btn-sm btn-flat btn-danger" onclick="return confirm('Anda Yakin ?');">
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
