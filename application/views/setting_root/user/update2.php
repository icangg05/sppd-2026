  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Profil Pengguna
      </h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
             <?php if($entry[0]['photo']==""){?>
             <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url();?>assets2/dist/img/avatar5.png" alt="User profile picture">
             <?php } else {?>
             <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url();?>upload/profil/<?php echo $entry[0]['photo']?>" alt="User profile picture">
             <?php } ?>
             
             <h3 class="profile-username text-center"><?php echo $entry[0]['first_name']?> <?php echo $entry[0]['last_name']?></h3>
             <?php echo form_open_multipart('setting_root/user/update3'); ?>
             <input type="hidden" class="form-control" name="id" value="<?php echo $entry[0]['id']?>">
             <input type="file" class="form-control" name="userfile">
             <button type="submit" class="btn btn-primary btn-flat btn-block">Simpan</button>
             <?php echo form_close(); ?>
           </div>
           <!-- /.box-body -->
         </div>
         <!-- /.box -->
       </div>
       <!-- /.col -->
       <div class="col-md-9">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#settings" data-toggle="tab">Pengaturan</a></li>
          </ul>
          <div class="tab-content">
            
            <div class="active tab-pane" id="settings">
             <form action="<?php echo base_url();?>setting_root/user/update2" class="form-horizontal"  method="post" accept-charset="utf-8">
               <?php
               $message = $this->session->flashdata('notif');
               if($message){
                echo '<p class="alert alert-info text-center"><b>'.$message .'</b></p>';
              }
              ?>
              <?php if(validation_errors()){?>
              <div class="alert alert-danger text-center">
               <?php echo validation_errors(); ?>
             </div>
             <?php }?>
             <input type="hidden" class="form-control" name="user_id" value="<?php echo $entry[0]['id']?>">
             <div class="form-group">
              <label for="inputName" class="col-sm-2 control-label">Nama Depan</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="first_name" value="<?php echo $entry[0]['first_name']?>">
              </div>
            </div>
            <div class="form-group">
              <label for="inputEmail" class="col-sm-2 control-label">Nama Belakang</label>
              <div class="col-sm-10">
               <input type="text" class="form-control" name="last_name" value="<?php echo $entry[0]['last_name']?>">
             </div>
           </div>
           <div class="form-group">
            <label for="inputName" class="col-sm-2 control-label">Telepon</label>
            <div class="col-sm-10">
             <input type="text" class="form-control" name="phone" value="<?php echo $entry[0]['phone']?>">
           </div>
         </div>
         <div class="form-group">
          <label for="inputExperience" class="col-sm-2 control-label">User Name</label>
          <div class="col-sm-10">
           <input type="text" class="form-control" name="username" value="<?php echo $entry[0]['username']?>">
         </div>
       </div>
       <div class="form-group">
        <label for="inputSkills" class="col-sm-2 control-label">Email</label>
        <div class="col-sm-10">
         <input type="text" class="form-control" name="email" value="<?php echo $entry[0]['email']?>">
       </div>
     </div>
     <div class="form-group">
      <label for="inputSkills" class="col-sm-2 control-label">Password</label>
      <div class="col-sm-10">
       <input type="password" class="form-control" name="password" >
     </div>
   </div>
   <div class="form-group">
    <label for="inputSkills" class="col-sm-2 control-label">Ulangi Password</label>
    <div class="col-sm-10">
     <input type="password" class="form-control" name="password_confirm">
   </div>
 </div>
 <div class="form-group">
  <label for="inputSkills" class="col-sm-2 control-label">SKPD</label>
  
  <div class="col-sm-10">
    <select class="form-control select2" name="skpd_id">
	<?php if($this->ion_auth->get_users_groups()->row()->id == 9 && $this->ion_auth->user()->row()->skpd_id=="") { ?>
		<option value="0">Administrator</option>
	 <?php } else { ?>
		  <?php
			foreach ($skpd as $s) {
				if	($s->skpd_id==$entry[0]['skpd_id']){
					echo '<option value="'.$s->skpd_id.'" selected>'.$s->skpd_nama.'</option>';
				} else {
					echo '<option value="'.$s->skpd_id.'">'.$s->skpd_nama.'</option>';
				}
			}
		  ?>
	 <?php } ?> 
    
    <!-- <option value="<?php echo $entry[0]['skpd_id'];?>"><?php echo $entry[0]['skpd_nama'];?></option> -->
  </select>
</div>
</div>
<div class="form-group">
  <label for="inputSkills" class="col-sm-2 control-label">Pilih Group</label>
  <div class="col-sm-10">
   <select class="form-control" name="groups[]">
     <option value="<?php echo $entry[0]['group_id'];?>"><?php echo $entry[0]['group_name'];?></option>
   </select>
 </div>
</div>
<div class="form-group">
  <div class="col-sm-offset-2 col-sm-10">
    <button type="submit" class="btn btn-success btn-flat">Simpan</button>
    <a href="<?php echo base_url();?>beranda" class="btn btn-warning btn-flat">Kembali</a>
  </div>
</div>
</form>
</div>
<!-- /.tab-pane -->
</div>
<!-- /.tab-content -->
</div>
<!-- /.nav-tabs-custom -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
	var ckeditor = CKEDITOR.replace('ckeditor');
</script>
<script>
	$(function () {
		//Initialize Select2 Elements
		$(".select2").select2();
	});
</script>