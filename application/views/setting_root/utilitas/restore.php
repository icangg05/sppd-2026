        <div id="page-wrapper">
         <br>
         <div class="navbar">
           <div class="navbar-inner">
               <ul class="breadcrumb">
                   <li>
                       <a href="<?php echo base_url('setting_root/utilitas/restore_view')?>">Restore Database</a> <span class="divider"></span>	
                   </li>
               </ul>
           </div>
       </div>
       <div class="row">
       </div>
       <?php echo form_open("setting_root/utilitas/restore", 
       "class='form-horizontal' row-border")?> 
       <div class="col-lg-4">
        <div class="form-group">
          <input class="form-control" type='file' name="userfile" required>
      </div> 
      <div class="form-group">
          <button class="btn btn-primary"><span class="glyphicon glyphicon-hdd"></span> Restore Database</button>
      </div>
  </div>
  <?php echo form_close();?> 
  
</div>
<!-- /#page-wrapper -->
</div>
<!-- /#wrapper -->
