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
              <h3 class="box-title">CEK DOKUMEN</h3>
            </div>
            <div class="box-header with-border">
            <?php echo form_open_multipart("setting_admin/dokumen/cek", 
            "class='form-horizontal' row-border")?> 
            <div class="col-lg-3 col-xs-12">
              <div class="form-group">
                <input class="form-control" type="file" name="userfile">
              </div> 
            </div>
            <div class="col-lg-3 col-xs-12">
              <button class="btn btn-success">Cek Dokumen</button>
          </div>
            <div class="col-lg-12 col-xs-12">
			<?php if(($hasil!="xxx")&&$hasil){
				echo"<span class='label label-success'>$hasil</span>";
				echo "<br><br>Nama Dokumen :".$nama_dokumen;
				echo "<br>Jumlah Signature :".$jumlah_signature;
				echo "<br>Catatan :".$notes;
			}else if($hasil=="xxx"){
			}else {
				echo"<span class='label label-danger'>DOCUMENT NOT VALID</span>";
				echo "<br><br>Nama Dokumen :".$nama_dokumen;
				echo "<br>Jumlah Signature :".$jumlah_signature;
			}
			
			
			
			?>
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