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
              <h3 class="box-title">BACKUP DAN IMPORT DATABASE</h3>
            </div>
            <div class="box-header with-border">
             <div class="col-lg-6 col-xs-12">
              <a href="<?php echo base_url();?>setting_root/utilitas/backupdb"><button class="btn btn-primary"><span class="glyphicon glyphicon-hdd"></span> Backup Database</button></a>
            </div>
            <?php echo form_open("setting_root/utilitas/restore", 
            "class='form-horizontal' row-border")?> 
            <div class="col-lg-3 col-xs-12">
              <div class="form-group">
                <input class="form-control" type="file" name="userfile" required>
              </div> 
            </div>
            <div class="col-lg-3 col-xs-12">
             <div class="form-group">
              <button class="btn btn-success"><span class="glyphicon glyphicon-hdd"></span> Restore Database</button>
            </div>
          </div>
          <?php echo form_close();?> 
            <?php echo form_open_multipart("setting_root/utilitas/update", 
            "class='form-horizontal' row-border")?> 
            <div class="col-lg-3 col-xs-12">
              <div class="form-group">
                <input class="form-control" type="file" name="filename" required>
              </div> 
            </div>
            <div class="col-lg-3 col-xs-12">
             <div class="form-group">
              <button class="btn btn-success"><span class="glyphicon glyphicon-hdd"></span> Update Database</button>
            </div>
          </div>
          <?php echo form_close();?> 
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