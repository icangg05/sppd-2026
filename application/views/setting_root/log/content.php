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

              <h3 class="box-title">STATUS LOG</h3>

            </div>

            <div class="box-header with-border">

             <?php echo form_open("setting_root/log/search_status_log/".$this->uri->segment(4));?>

             <div class="col-md-7">

              <a href="<?php echo base_url();?>setting_root/log/status_log/<?php echo $this->uri->segment(4)?>" class="btn btn-warning btn-flat">Refresh</a>

            </div>

            <div class="col-md-2">

             <select class="form-control" name="column">

              <option value="action">Action</option>

              <option value="action_table">Action Table</option>

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

            <!--th style="width: 300px">Kode SKPD</th-->

            <th style="width: 200px">TANGGAL</th>

            <th style="width: 200px">JAM</th>

            <th style="width: 200px">USER</th>

            <th style="width: 200px">AKSI</th>

            <th style="width: 200px">DI</th>

          </tr>

          <?php foreach($log as $v){

           $id_log = base64_encode($this->encrypt->encode($v->id_log, $this->session->userdata('encrypt_key')));	

           ?>

           <tr>

            <td><?php echo $v->date;?></td>

            <td><?php echo $v->time;?></td>

            <td><?php echo $v->username;?></td>

            <td><?php echo $v->action;?></td>

            <td><?php echo $v->action_table;?></td>

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