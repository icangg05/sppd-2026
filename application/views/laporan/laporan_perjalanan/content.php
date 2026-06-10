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
            <h3 class="box-title">LAPORAN PERJALANAN DINAS</h3>
          </div>
          <div class="box-header with-border">
            <div class="col-md-7">
              <!-- <a href="<?php echo base_url();?>laporan/cetak_rbpd?telaah_id=<?php echo $this->input->get('telaah_id');?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak Data</a> -->
              <a href="<?php echo base_url();?>telaah/list_telaah/laporan/<?php echo $this->uri->segment(6)?>?telaah_id=<?php echo $this->input->get('telaah_id');?>" class="btn btn-danger btn-sm "><i class="fa fa-close"></i> Kembali</a>
            </div>
          </div>
          <!-- /.box-header -->
          <div class="table-responsive box-body">
            <?php
            $message = $this->session->flashdata('notif');
            if($message){
             echo '<p class="alert alert-info text-center"><b>'.$message .'</b></p>';
           }
           ?>
           <div class="table-responsive box-body">
            <table class="table table-bordered table-hover">
              <tr class='info'>
                <td style="width: 5px" colspan="4">List Laporan Perjalanan</td>
                <th style="width: 10px">
				  <?php if (($this->ion_auth->user()->row()->id!=1) && ($this->ion_auth->get_users_groups()->row()->id != 100) && ($this->ion_auth->get_users_groups()->row()->id != 9)){ ?>
					<a href="<?php echo base_url();?>telaah/laporan/laporan_perjalanan/laporan/create_view/<?php echo $this->uri->segment(6);?>?telaah_id=<?php echo $this->input->get('telaah_id');?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-success btn-sm ">Tambah Data</a>
				  <?php } ?>
				  <!--a href="<!--?php echo base_url();?>telaah/laporan/laporan_perjalanan/laporan/cetak/<!--?php echo $this->uri->segment(6);?>?telaah_id=<!--?php echo $this->input->get('telaah_id');?>&&posisi=<!--?php echo $this->input->get('posisi');?>" class="btn btn-primary btn-sm "><i class="fa fa-print"></i> Cetak</a-->
                </th>
              </tr>
              <tr>
                <th style="width: 1px">No</th>
                <!--<th style="width: 100px">Nama Laporan Perjalanan</th>-->
                <th style="width: 100px">Keterangan</th>
                <th style="width: 100px">Tanggal</th>
                <th style="width: 100px">File</th>
                <th style="width: 10px">#</th>
              </tr>
              <?php 
              $no=1;
              foreach($laporan_perjalanan as $v){
                $laporanperjalanan_id = base64_encode($this->encrypt->encode($v->laporanperjalanan_id, $this->session->userdata('encrypt_key')));	
                ?>
                <tr>
                  <td><?php echo $no;?></td>
                  <!--<td><-?php echo $v->laporanperjalanan_name?></td>-->
                  <td><?php echo $v->laporanperjalanan_desc?></td>
                  <td><?php echo $v->laporanperjalanan_date?></td>
                  <td><a href="<?php echo base_url()."upload/laporan_perjalanan/".$v->laporanperjalanan_file?>"><?php echo $v->laporanperjalanan_file?></a></td>
                  <td><a href="<?php echo base_url();?>telaah/laporan/laporan_perjalanan/laporan/update_view/<?php echo $this->uri->segment(6);?>?laporanperjalanan_id=<?php echo $laporanperjalanan_id;?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-sm btn-warning"><i class="fa fa-edit"></i> Edit</a>
                    <a href="<?php echo base_url();?>telaah/laporan/laporan_perjalanan/laporan/delete/<?php echo $this->uri->segment(6);?>?laporanperjalanan_id=<?php echo $laporanperjalanan_id;?>&&telaah_id=<?php echo $v->telaah_id;?>&&file=<?php echo $v->laporanperjalanan_file;?>&&posisi=<?php echo $this->input->get('posisi');?>" class="btn btn-sm btn-danger" onclick="return confirm('Anda Yakin ?');"><i class="fa fa-trash"></i> Hapus</a>
                  </td>
                </tr>
                <?php
                $no++;				
              } 
              ?>
            </table>
          </div>
          
          
        </div>
        <!-- /.box-body -->
        <div class="box-footer clearfix">
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
