 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script>
    $(document).ready(function(){
         
     var SITEURL = "<?php echo base_url(); ?>";
  
     var tglCurrent = $('#calendar').fullCalendar('getDate');
    var tahun = moment(tglCurrent).format('YYYY');
    var bulan = moment(tglCurrent).format('MM');
        var calendar = $('#calendar').fullCalendar({
            editable:true,
            header:{
                left:'prev,next today',
                center:'title',
                right:'month,agendaWeek,agendaDay'
            },
            events:"<?php echo base_url(); ?>setting_root/kalender/load/"+tahun,
            selectable:true,
            selectHelper:true,
          //   select:function(start, end, allDay)
          //   {
          //       var title = prompt("Enter Event Title");
          //       if(title)
          //       {
          //           var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
          //           var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
          //           $.ajax({
          //               url:"<?php echo base_url(); ?>fullcalendar/insert",
          //               type:"POST",
          //               data:{title:title, start:start, end:end},
          //               success:function()
          //               {
          //                   calendar.fullCalendar('refetchEvents');
          //                   alert("Added Successfully");
          //               }
          //           })
          //       }
          //   },
          //   editable:true,
          //   eventResize:function(event)
          //   {
          //       var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
          //       var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");

          //       var title = event.title;

          //       var id = event.id;

          //       $.ajax({
          //           url:"<?php echo base_url(); ?>fullcalendar/update",
          //           type:"POST",
          //           data:{title:title, start:start, end:end, id:id},
          //           success:function()
          //           {
          //               calendar.fullCalendar('refetchEvents');
          //               alert("Event Update");
          //           }
          //       })
          //   },
          //   eventDrop:function(event)
          //   {
          //       var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
          //       //alert(start);
          //       var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
          //       //alert(end);
          //       var title = event.title;
          //       var id = event.id;
          //       $.ajax({
          //           url:"<?php echo base_url(); ?>fullcalendar/update",
          //           type:"POST",
          //           data:{title:title, start:start, end:end, id:id},
          //           success:function()
          //           {
          //               calendar.fullCalendar('refetchEvents');
          //               alert("Event Updated");
          //           }
          //       })
          //   },
            eventClick:function(event)
            {
               //  if(confirm("Are you sure you want to remove it?"))
               //  {
                // var tglCurrent = $('#calendar').fullCalendar('getDate');
                // var year = moment(tglCurrent).format('YYYY');
                // var month = moment(tglCurrent).format('M');
                // alert('Year is ' + year + ' Month is ' + month);
                    // $.ajax({
                    //     url:"<!--?php echo base_url(); ?>setting_root/kalender/detail",
                    //     type:"POST",
                    //     data:{id:id},
                    //     success:function()
                    //     {
                    //         calendar.fullCalendar('refetchEvents');
                    //         alert('Event Removed');
                    //     }
                    // })
                    // location.href =SITEURL + 'setting_root/kalender/detail/'+event.kategori+'/'+event.id, '_blank';
                    window.open(
                         SITEURL + 'setting_root/kalender/detail/'+event.jenis_skpd+'/'+event.kategori+'/'+event.id,
                    '_blank' // <- This is what makes it open in a new window.
                    );
               //  }
            }
        });
    });
             
    </script>
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
              <h3 class="box-title">Kalender Perjalanan</h3>
            </div>
            <div class="box-header with-border">
            <div id="calendar"></div>
            </div>
          <!-- /.box-header -->
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