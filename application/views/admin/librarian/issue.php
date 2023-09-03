
<div class="content-wrapper" style="min-height: 946px;">   
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i>  <?php echo $this->lang->line('library'); ?>
        </h1>
    </section>  
    <section class="content">
        <div class="row">         
            <div class="col-md-3">
                <?php
                if ($memberList->member_type == "student") {
                    $this->load->view('admin/librarian/_student');
                } else {
                    $this->load->view('admin/librarian/_teacher');
                }
                ?>       
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('issue_book'); ?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->


                  <div class="box-body">                            
                        
 <form id="form1" action="<?php echo site_url('admin/member/issue/' . $memberList->lib_member_id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            
     <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('book_barcode'); ?></label><small class="req"> *</small>
                                        <input type="text" name="book_id" class="form-control" placeholder="Search Barcode"/>
                                    </div>

                        <div class="form-group">
              <div class="box-footer">
                            <button type="submit" class="btn btn-sm btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                        </div>

                            </div>


                        </div><!-- /.box-body -->
</form>



         <form id="form1" action="<?php echo site_url('admin/member/issue/' . $memberList->lib_member_id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">

                        <div class="box-body"> 
                                                  
                            <?php
                            if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                                $this->session->mark_as_temp('msg', 5); 
                            }
                            ?>  
                            

                            <?php echo $this->customlib->getCSRF(); ?>

                            <input id="member_id" name="member_id"  type="hidden" class="form-control date"  value="<?php echo $memberList->lib_member_id; ?>" />

                         <!--    <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_barcode'); ?></label>
                                <input id="book_id" name="book_id"  type="text" class="form-control"  value="" placeholder="<?php echo $this->lang->line('book_barcode'); ?>" />
                                <span class="text-danger"><?php echo form_error('book_id'); ?></span>
                            </div> -->

                   <div class="form-group">
                                <label><?php echo $this->lang->line('book_barcode'); ?></label>
                               
                 <?php
                  if (isset($bok)) {
                    ?>     
                           <input  type="text" class="form-control" name="book_id" readonly 
                      <?php     
                     if (isset($totalborrowedbookchek)) {
                    ?>      
                        <?php foreach($bok as $student){ ?>
                            value="<?php echo $student['other'];?>"/>           
                        <?php }?>
                     <?php } ?>      
                  
                <?php } ?>

                            </div>


                        <div class="form-group">
                                <label><?php echo $this->lang->line('book_title'); ?></label>
                               
                                    <?php
                if (isset($bok)) {
                    ?>      
                        <input  type="text" class="form-control" readonly 
                        <?php
                        if (isset($totalborrowedbookchek)) {
                       ?>  
                        <?php foreach($bok as $student){ ?>
                        value="<?php echo $student['book_title'];?>"/>          
                        <?php }?>
                     <?php }?>      
                  
                <?php } ?>

                            </div>


                            <div class="form-group">
                                <label><?php echo $this->lang->line('due_date'); ?></label>
                                <input id="dateto" name="return_date"  type="text" class="form-control date"  value="<?php echo set_value('return_date', date($this->customlib->getSchoolDateFormat(), strtotime("+7days")) ); ?>"/>
                            </div>
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div> 
                <div class="box box-primary">
                    <div class="nav-tabs-custom">
                       <ul id="myTab" class="nav nav-tabs">
                            <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('list'); ?>  <?php echo $this->lang->line('view'); ?></a></li>
                            <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('fine'); ?> <?php echo $this->lang->line('details'); ?> <?php echo $this->lang->line('view'); ?></a></li>
                        </ul>   
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('book_issued'); ?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->

                    <div class="box-body">                            
                        <div class="table-responsive mailbox-messages">
                        <div class="tab-content">    
                         <div class="download_label"><?php echo $this->lang->line('book_issued'); ?></div>
                            <div class="tab-pane active table-responsive no-padding" id="tab_1">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('barcode'); ?></th>
                                        <th><?php echo $this->lang->line('book_title'); ?></th>
                                        <th><?php echo $this->lang->line('issue_date'); ?></th>
                                        <th><?php echo $this->lang->line('due_date'); ?></th>
                                        <th><?php echo $this->lang->line('return_date'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('days'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('extend');?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($issued_books)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($issued_books as $book) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php echo $book['other'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $book['book_title'] ?>
                                                </td>
                                                
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($book['issue_date'])) ?></td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($book['return_date'])) ?></td>   
                                                <td class="mailbox-name">
                                                    <?php if($book['is_returned'] == 1){
                                                    ?>
                                                    <?php
                                                      $date=date_create($book['created_at']);
                                                      echo date_format($date,"d/m/y");
                                                    ?>
                                                <?php }?>        
                                                </td> 
                                                      
                                                <td class="mailbox-name">
                                                <?php if ($book['is_returned'] == 0) {
                                                        ?>
                                                <?php 
                                                   $a= date("Y-m-d");
                                                   $b= $book['return_date'];
                                                   if (  $a>$b) {
                                                     echo "Overdue";
                                                  }
                                                  else{
                                                    echo "Due";
                                                  }
                                                   ?>
                                                 <?php
                                                    }
                                                    ?>      
                                                   </td>  
                                                   <td class="mailbox-name">
                                                 <?php if ($book['is_returned'] == 0) {
                                                        ?>   
                                                   <?php                                                 
                                                   $a= $book['return_date'];
                                                   $b= date("Y-m-d");
                                                   $date1=date_create("$a");
                                                   $date2=date_create("$b");
                                                   $diff=date_diff($date1,$date2);
                                                   if ($b > $a) {
                                                   echo $diff->format("%a");
                                                  }
                                                  else{
                                                    echo "0";
                                                  }
                                                   ?>
                                                  <?php
                                                    }
                                                    ?> 
                                                    </td> 
                                                <td class="mailbox-date ">
                                                  <?php if ($book['is_returned'] == 0) {
                                                        ?>
                                                <?php 
                                                   $a= $book['return_date'];
                                                   $b= date("Y-m-d");
                                                   if (  $a>=$b) {?>
                                                        <a href="<?php echo base_url(); ?>admin/member/bookreissue/<?php echo $book['id'] . "/" . $memberList->lib_member_id; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Extend" onclick="return confirm('Are you sure you want to extend this book for a week?')">
                                                            <i class="fa fa-plus"></i>
                                                        </a>
                                                       <?php }?>
                                                        <?php
                                                    }
                                                    ?>
                                                </td>          
                                                <td class="mailbox-date pull-right">
                                                    <?php if ($book['is_returned'] == 0) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/member/bookreturn/<?php echo $book['id'] . "/" . $memberList->lib_member_id; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Return" onclick="return confirm('Are you sure you want to return this book?')">
                                                            <i class="fa fa-mail-reply"></i>
                                                        </a>

                                                        <?php
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table-1 -->
                         </div>
                         <div class="tab-pane" id="tab_2">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>

                                        <th><?php echo $this->lang->line('barcode'); ?></th>
                                        <th><?php echo $this->lang->line('book_title'); ?></th>
                                        <th><?php echo $this->lang->line('issue_date'); ?></th>
                                        <th><?php echo $this->lang->line('due_date'); ?></th>
                                        <th><?php echo $this->lang->line('return_date'); ?></th>
                                        <th><?php echo $this->lang->line('days'); ?></th>
                                        <th><?php echo $this->lang->line('fine'); ?></th>
                                        <th><?php echo $this->lang->line('discount'); ?> <?php echo $this->lang->line('days'); ?></th>
                                        <th><?php echo $this->lang->line('total'); ?></th>
                                        <th><?php echo $this->lang->line('paid'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('discount'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (empty($fineList)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($fineList as $fine) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php echo $fine['other'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $fine['book_title'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fine['issue_date'])) ?></td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fine['return_date'])) ?></td>
                                                <td class="mailbox-name">   
                                                    <?php
                                                      $date=date_create($fine['created_at']);
                                                      echo date_format($date,"d/m/y");
                                                    ?>        
                                                </td>    
                                                <td class="mailbox-name">
                                                    <?php echo $fine['days'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo "SR ". $fine['balance'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $fine['discount']?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $fine['total_fine'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo  $fine['amount_paid'] ?> 
                                                </td>
                                                <td class="mailbox-date">
                                                <?php if ($fine['status'] == 0) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/member/finedetail/<?php echo $fine['fid'] . "/" . $memberList->lib_member_id; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="discount">
                                                            <i class="fa fa-percent"></i>
                                                        </a>
                                                 <?php }?>       
                                                </td>
                                               <td class="mailbox-date pull-right">
                                                  <?php if ($fine['status'] == 0) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/member/payfine/<?php echo $fine['fid'] . "/" . $memberList->lib_member_id; ?>"  data-toggle="tooltip" title="Pay Fine" onclick="return confirm('Are you sure you want to pay fine?')">
                                                            <i class="btn btn-xs btn-success">pay fine</i>
                                                        </a> 
                                                    <?php }?>
                                                    <?php if ($fine['status'] == 1) {
                                                        ?>
                                                        <a href="<?php echo base_url(); ?>admin/member/finedetail/<?php echo $fine['fid'] . "/" . $memberList->lib_member_id; ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="Detail">
                                                            <i class=" fa fa-eye"></i>
                                                        </a>
                                                    <?php }?>  


                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                        }
                                    }
                                    ?>

                                </tbody>
                            </table><!-- /.table2 -->
                         </div>   

                        </div>
                        </div><!-- /.mail-box-messages -->

                    </div><!-- /.box-body -->

                    </form>
                 </div> 
                  </div> 
            </div>
        </div>
    </section>
</div>


<script type="text/javascript">

    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $(".date").datepicker({
            // format: "dd-mm-yyyy",
            format: date_format,
            autoclose: true,
            todayHighlight: true,
            startDate: new Date()

        });
    });
    $(document).ready(function () {
    setTimeout(function() {$('.alert').fadeOut(3000);}, 3100);
    });
</script>
<script>
$(document).ready(function(){
    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        localStorage.setItem('activeTab', $(e.target).attr('href'));
    });
    var activeTab = localStorage.getItem('activeTab');
    if(activeTab){
        $('#myTab a[href="' + activeTab + '"]').tab('show');
    }
});
</script>

