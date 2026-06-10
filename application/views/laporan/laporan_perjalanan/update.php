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
            <h3 class="box-title">TAMBAH LAPORAN PERJALANAN DINAS</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <?php echo form_open_multipart('telaah/laporan/laporan_perjalanan/laporan/update'); ?>
          <div class="table-responsive box-body">
            <?php if(validation_errors()){?>
            <div class="alert alert-danger text-center">
              <?php echo validation_errors(); ?>
            </div>
            <?php }?>
            <table class="table table-bordered table-striped">
              <tr class="info">
                <th class="col-md-3" colspan="2">
                  <center>DATA LAPORAN PERJALANAN DINAS</center>
                </th>
              </tr>
              <?php $telaah_id = base64_encode($this->encrypt->encode($entry[0]['telaah_id'], $this->session->userdata('encrypt_key')));	?>
              <input type="hidden" class="form-control" name="laporanperjalanan_id" value="<?php echo $entry[0]['laporanperjalanan_id'];?>">
              <input type="hidden" class="form-control" name="telaah_id" value="<?php echo $entry[0]['telaah_id'];?>">
              <input type="hidden" class="form-control" name="laporanperjalanan_file" value="<?php echo $entry[0]['laporanperjalanan_file'];?>">
              <input type="hidden" class="form-control" name="posisi" value="<?php echo $posisi;?>">  
              <!--<tr>
                <th class="col-md-3">Nama Laporan</th>
                <td><input type="text" class="form-control" name="laporanperjalanan_name" placeholder="Nama Laporan" value="<-?php echo $entry[0]['laporanperjalanan_name']?>"></td>
              </tr>-->
              <tr>
                <th class="col-md-4">Isi laporan</th>
                <td><textarea id="editor" name="laporanperjalanan_desc" class="form-control"><?php echo $entry[0]['laporanperjalanan_desc']?></textarea></td>
              </tr>
              <tr>
                <th class="col-md-3">Tanggal Laporan</th>
                <td><input type="date" class="form-control" name="laporanperjalanan_date" placeholder="Tanggal Laporan" value="<?php echo $entry[0]['laporanperjalanan_date']?>"></td>
              </tr>
              <tr>
                <th class="col-md-3">Foto/File</th>
                <td><input type="file" class="form-control" name="userfile" multiple>
                  <br><a href="<?php echo base_url();?>upload/laporan_perjalanan/<?php echo $entry[0]['laporanperjalanan_file']?>"><?php echo $entry[0]['laporanperjalanan_file']?></a>
                </td>
              </tr>
            </table>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <div class="col-md-6">					
              <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-save"></i> Simpan</button>
              <button type="reset" class="btn btn-warning btn-sm" ><i class="fa fa-repeat"></i> Reset</button>
              <a href="<?php echo base_url();?>telaah/laporan/laporan_perjalanan/laporan/index/<?php echo $this->uri->segment(6);?>?telaah_id=<?php echo $telaah_id;?>&&posisi=<?php echo $posisi;?>" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
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
 CKEDITOR.replace('editor' ,{
        filebrowserImageBrowseUrl : '<?php echo base_url('assets/plugin_new/ckeditor');?>'
    });
</script>