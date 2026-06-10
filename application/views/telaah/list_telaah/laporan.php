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
              <h3 class="box-title">DOKUMEN SEBELUM PERJALANAN</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
            <div class="box-body">
				<!-- /.col -->
				<div class="col-md-6 col-sm-6 col-xs-12">
				<a href="<?php echo base_url();?>telaah/laporan/spd/index/<?php echo $this->uri->segment(4);?>?telaah_id=<?php echo $this->input->get('telaah_id');?>">
				  <div class="info-box">
					<span class="info-box-icon bg-yellow"><i class="fa fa-file-alt"></i></span>
					<div class="info-box-content">
					  <span class="info-box-number">SURAT PERINTAH PERJALANAN DINAS</span>
					</div>
					<!-- /.info-box-content -->
				  </div>
				  <!-- /.info-box -->
				</a>
				</div>
				<!-- /.col -->
				<div class="col-md-6 col-sm-6 col-xs-12">
				<a href="<?php echo base_url();?>telaah/laporan/spt/index/<?php echo $this->uri->segment(4);?>?telaah_id=<?php echo $this->input->get('telaah_id');?>">
				  <div class="info-box">
					<span class="info-box-icon bg-yellow"><i class="fa fa-file-alt"></i></span>
					<div class="info-box-content">
					  <span class="info-box-number">SURAT PERINTAH TUGAS</span>
					</div>
					<!-- /.info-box-content -->
				  </div>
				  <!-- /.info-box -->
				</a>
				</div>
				<!-- /.col -->
				<!-- /.col -->
				<div class="col-md-6 col-sm-6 col-xs-12">
				<!--?php if($data[0]['telaah_ttdspd']==0 || $data[0]['telaah_ttdspt']==0){ ?>
					<a href="#" data-toggle="modal" data-target="#1">
					  <div class="info-box">
						<span class="info-box-icon bg-default"><i class="fa fa-file-alt"></i></span>
						<div class="info-box-content">
						  <span class="info-box-number">KUITANSI</span>
						</div>
						<!-- /.info-box-content -->
					  <!--/div>
					  <!-- /.info-box -->
					<!--/a>
				<!--?php } else { ?-->
					<a href="<?php echo base_url();?>telaah/laporan/kuitansi/index/<?php echo $this->uri->segment(4);?>?telaah_id=<?php echo $this->input->get('telaah_id');?>">
					  <div class="info-box">
						<span class="info-box-icon bg-yellow"><i class="fa fa-file-alt"></i></span>
						<div class="info-box-content">
						  <span class="info-box-number">KUITANSI</span>
						</div>
						<!-- /.info-box-content -->
					  </div>
					  <!-- /.info-box -->
					</a>
				<!--?php } ?-->
				</div>
				<!-- /.col -->
			</div>
              <!-- /.box-body -->
              <div class="box-footer">
              </div>
          </div>
          <!-- /.box -->
		  
        </div>
        <!--/.col (left) -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">DOKUMEN SESUDAH PERJALANAN</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            
            <div class="box-body">
				
				<div class="col-md-6 col-sm-6 col-xs-12">
				<!--?php if($data[0]['telaah_ttdspd']==0 || $data[0]['telaah_ttdspt']==0){ ?>
					<a href="#" data-toggle="modal" data-target="#1">
					  <div class="info-box">
						<span class="info-box-icon bg-default"><i class="fa fa-file-alt"></i></span>
						<div class="info-box-content">
						  <span class="info-box-number">LAPORAN PENGELUARAN RILL</span>
						</div>
						<!-- /.info-box-content -->
					  <!--/div>
					  <!-- /.info-box -->
					<!--/a>
				<!--?php } else { ?-->
					<a href="<?php echo base_url();?>telaah/laporan/pengeluaran_rill/index/<?php echo $this->uri->segment(4);?>?telaah_id=<?php echo $this->input->get('telaah_id');?>">
					  <div class="info-box">
						<span class="info-box-icon bg-yellow"><i class="fa fa-file-alt"></i></span>
						<div class="info-box-content">
						  <span class="info-box-number">LAPORAN PENGELUARAN RILL</span>
						</div>
						<!-- /.info-box-content -->
					  </div>
					  <!-- /.info-box -->
					</a>
				<!--?php } ?-->
				
				</div>
				<!-- /.col -->
				
				<div class="col-md-6 col-sm-6 col-xs-12">
				<!--?php if($data[0]['telaah_ttdspd']==0 || $data[0]['telaah_ttdspt']==0){ ?>
					<a href="#" data-toggle="modal" data-target="#1">
					  <div class="info-box">
						<span class="info-box-icon bg-default"><i class="fa fa-file-alt"></i></span>
						<div class="info-box-content">
						  <span class="info-box-number">RINCIAN BIAYA PERJALANAN DINAS</span>
						</div>
						<!-- /.info-box-content -->
					  <!--/div>
					  <!-- /.info-box -->
					<!--/a>
				<!--?php } else { ?-->
					<a href="<?php echo base_url();?>telaah/laporan/rincian/index/<?php echo $this->uri->segment(4);?>?telaah_id=<?php echo $this->input->get('telaah_id');?>&&posisi=<?php echo $this->uri->segment(2);?>">
					  <div class="info-box">
						<span class="info-box-icon bg-yellow"><i class="fa fa-file-alt"></i></span>
						<div class="info-box-content">
						  <span class="info-box-number">RINCIAN BIAYA PERJALANAN DINAS</span>
						</div>
						<!-- /.info-box-content -->
					  </div>
					  <!-- /.info-box -->
					</a>
				<!--?php } ?-->	
				</div>
				
				<div class="col-md-6 col-sm-6 col-xs-12">
				<!--?php if($data[0]['telaah_ttdspd']==0 || $data[0]['telaah_ttdspt']==0){ ?>
					<a href="#" data-toggle="modal" data-target="#1">
					  <div class="info-box">
						<span class="info-box-icon bg-default"><i class="fa fa-file-alt"></i></span>
						<div class="info-box-content">
						  <span class="info-box-number">LAPORAN PERJALANAN</span>
						</div>
						<!-- /.info-box-content -->
					  <!--/div>
					  <!-- /.info-box -->
					<!--/a>
				<!--?php } else { ?-->
					<a href="<?php echo base_url();?>telaah/laporan/laporan_perjalanan/laporan/index/<?php echo $this->uri->segment(4);?>?telaah_id=<?php echo $this->input->get('telaah_id');?>&&posisi=<?php echo $this->uri->segment(2);?>">
					  <div class="info-box">
						<span class="info-box-icon bg-yellow"><i class="fa fa-file-alt"></i></span>
						<div class="info-box-content">
						  <span class="info-box-number">LAPORAN PERJALANAN</span>
						</div>
						<!-- /.info-box-content -->
					  </div>
					  <!-- /.info-box -->
					</a>
				<!--?php } ?-->	
				
				</div>
				<!-- /.col -->
			</div>
              <!-- /.box-body -->
              <div class="box-footer">
				 <div class="col-md-6">		
					<?php if($this->uri->segment(2)=="esselon"){?>
						<a href="<?php echo base_url();?>list_telaah/esselon" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
					<?php } else if($this->uri->segment(2)=="kadis"){?>
						<a href="<?php echo base_url();?>list_telaah/kadis" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
					<?php } else if($this->uri->segment(2)=="dprd"){?>
						<a href="<?php echo base_url();?>list_telaah/dprd" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
					<?php } else if($this->uri->segment(2)=="sekda"){?>
						<a href="<?php echo base_url();?>list_telaah/sekda" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
					<?php } else if($this->uri->segment(2)=="walikota"){?>
						<a href="<?php echo base_url();?>list_telaah/walikota" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
					<?php } else if($this->uri->segment(2)=="staff_sekda"){?>
						<a href="<?php echo base_url();?>list_telaah/staff_sekda" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
					<?php } else if($this->uri->segment(2)=="kapus"){?>
						<a href="<?php echo base_url();?>list_telaah/kapus" class="btn btn-danger btn-sm"><i class="fa fa-close"></i> Kembali</a>
						<?php } ?>				 
					
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
  
  <div class="modal fade" id="1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          SPPD dan SPT Wajib diisi
        </div>
      </div>
    </div>
  </div>
 
  <script>
		if(document.getElementById('ckeditor')){
			var ckeditor = CKEDITOR.replace('ckeditor');
		}
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