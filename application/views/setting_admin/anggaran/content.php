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
              <h3 class="box-title">Anggaran</h3>
            </div>
            <div class="box-header with-border">
             <?php echo form_open("setting_admin/anggaran/search");?>
             <div class="col-md-5">
			 <?php if($setting_anggaran[0]['status']==1){ ?>
				<a href="<?php echo base_url();?>setting_admin/anggaran/create_view" class="btn btn-success btn-flat">Tambah Data</a>
			 <?php } ?>
              <a href="<?php echo base_url();?>setting_admin/anggaran" class="btn btn-warning btn-flat">Refresh</a>
            </div>				
            <div class="col-md-2">
             <select class="form-control" name="column">
              <option value="nama_program">Program</option>
              <option value="nama_kegiatan">Kegiatan</option>
            </select>
          </div>				
            <div class="col-md-2">
             <select class="form-control" name="tahun">
             <?php
                for($i = 2020;$i<=date('Y');$i++){ ?>
                  <option value="<?php echo $i ?>" <?php if($tahun == $i){ echo "selected"; }?> ><?php echo $i ?></option>
                  
             <?php }
             ?>
              
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
         <?php
         $message = $this->session->flashdata('notif');
         if($message){
           echo '<p class="alert alert-info text-center"><b>'.$message .'</b></p>';
         }
         ?>
         <table class="table table-bordered table-striped table-hover">
          <tr class='info'>
            <th style="width: 100px">SKPD</th>
            <th style="width: 10px">Tahun</th>
            <th style="width: 100px">Program</th>
            <th style="width: 10px">Kegiatan</th>
            <th style="width: 50px">Kode Rekening</th>
            <th style="width: 10px">Uraian</th>
            <th style="width: 50px">Total Anggaran</th>
            <th style="width: 50px">Realisasi Anggaran</th>
            <th style="width: 50px">Sisa Anggaran</th>
            <th style="width: 100px">Aksi</th>
          </tr>
          <?php foreach($anggaran as $v){
           $id_anggaran = base64_encode($this->encrypt->encode($v->id_anggaran, $this->session->userdata('encrypt_key')));	
           ?>
           <tr>
            <td>
             <?php echo $v->skpd_nama?>
           </td>
            <td>
             <?php echo $v->tahun?>
           </td>
           <td>
             <?php echo $v->nama_program;?>
           </td>
           <td>
             <?php echo $v->nama_kegiatan;?>
           </td>
           <td>
             <?php echo $v->no_rekening;?>
           </td>
           <td>
             <?php echo $v->uraian;?>
           </td>
           <td>
             <?php echo number_format($v->pagu, 0, ',', '.') ;?>
           </td>
           <td>
             <?php $rincian_biaya =  $this->m_anggaran->cek_sisa_anggaran_skpd($v->id_anggaran);
					$pengeluaran_rill =  $this->m_anggaran->cek_pengeluaran_rill_skpd($v->id_anggaran);
					echo number_format($rincian_biaya[0]->jumlah + $pengeluaran_rill[0]->jumlah, 0, ',', '.') ;?>
           </td>
           <td>
             <?php  echo number_format($v->pagu - ($rincian_biaya[0]->jumlah + $pengeluaran_rill[0]->jumlah), 0, ',', '.'); ?>
           </td>
           <td>
			 <a href="<?php echo base_url();?>setting_admin/anggaran/detail_anggaran/<?php echo $id_anggaran?>" class="btn btn-sm btn-flat btn-primary btn-block"><i class="fa fa-eye"></i> DETAIL</a>
			 <a href="<?php echo base_url();?>setting_admin/anggaran/update_view?id_anggaran=<?php echo $id_anggaran?>" class="btn btn-sm btn-flat btn-warning btn-block"><i class="fa fa-edit"></i> EDIT</a>
             <a href="<?php echo base_url();?>setting_admin/anggaran/delete?id_anggaran=<?php echo $id_anggaran?>" class="btn btn-sm btn-flat btn-danger btn-block" onclick="return confirm('Anda Yakin ?');"><i class="fa fa-trash"></i> HAPUS</a></td>
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