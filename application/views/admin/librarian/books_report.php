


<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-line-chart"></i> <?php echo $title ?></h1>
    </section>
    <?php
    $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
    ?>
    <!-- Main content -->
    <section class="content" id="printArea">
        <div class="row" >
            <div class="col-sm-12">
                <div class="col-sm-12 visible-print">
                    <img src="<?php echo base_url(); ?>backend/images/s_logo.png" style="height: 80px;">
                </div>
                <div class="col-xs-4">
                    <div class="box box-default">
                        <div class="box-body">
                            <b> <label><?php echo $this->setting_model->getCurrentSchoolName() ?></label></b><br/>
                            <span ><?php echo $this->setting_model->getSchoolDetail()->dise_code ?></span><br/>
                            <span ><?php echo $this->setting_model->getSchoolDetail()->email ?></span><br/>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">

                </div>
                <div class="col-xs-4">
                    <div class="box box-default">
                        <div class="box-body">
                            <b> <label><?php echo "Report Details" ?></label></b><br/>
                            <span><b>Librarian: </b> <?php echo $this->customlib->getUserData()['name']; ?></span><br/>
                        <span><b>Date: </b><?php  echo date("Y-m-d"); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <div class="col-sm-12">


               <div class="box box-info">


<form class="forms-sample" method="GET" action="<?php echo site_url('admin/Book/Booksreport');?>">
                    <div class="box-header hidden-print">
                       <div class="col-lg-12" style="margin-bottom: 1%;">
                           <div class="form-group col-lg-2">
                               <label>Start Date</label>
                               <input type="date" name="start_date" id="start_date" class="form-control" placeholder="Search by Date" data-form="datepicker" value="<?php echo date('01/m/Y');?>" />
                           </div>
                           <div class="form-group col-lg-2">
                               <label>End Date</label>
                               <input type="date" data-form="datepicker" name="end_date" id="end_date" onchange="searchDate()" class="form-control" placeholder="Search by Date" value="<?php echo date('t/m/Y');?>"/>
                           </div>
                           <div class="form-group col-lg-2">
                               <label><?php echo $this->lang->line('status'); ?></label><small class="req"> *</small> 
                                <select  id="status" name="status" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>  
                                <option value="yes"><?php echo "Available"; ?></option>
                                <option value="no"><?php echo "Borrowed"; ?></option>
                                <option value="Damage"><?php echo "Damaged/Lost"; ?></option>
                                 </select>
                           </div>
                       </div>
                   </div>
                         <div class="box-footer text-right">
                                <button type="submit" name="submit" class="btn btn-sm btn-success hidden-print"  >Submit</button>
                         </div>
                 
                        <div class="box-body table-responsive">
                            <div>
            <table class="table table-hover table-striped table-bordered example" id="myTable">
                                  
                                    <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('barcode'); ?></th>
                                        <th><?php echo $this->lang->line('book_no'); ?></th>
                                        <th><?php echo $this->lang->line('book_title'); ?></th>
                                        
                                        <th><?php echo $this->lang->line('isbn_no'); ?></th>
                                        <th><?php echo $this->lang->line('publisher'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('author'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('subject'); ?></th>
                                        <th><?php echo $this->lang->line('location'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('tags'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if(isset($books)){    ?>
                                    <?php  foreach ($books as $book){ ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo $book['other']; ?></td>
                                            <td class="mailbox-name"><?php echo $book['book_no']; ?></td>
                                            <td class="mailbox-name"><?php echo $book['book_title']; ?></td>
                                            <td class="mailbox-name"><?php echo $book['isbn_no'] ?></td>

                                            <td class="mailbox-name"><?php echo $book['publish']; ?></td>
                                            <td class="mailbox-name"><?php echo $book['author']; ?></td>
                                            </td>
                                            <td class="mailbox-name"><?php echo $book['subject']; ?></td>
                                            <td class="mailbox-name"><?php echo $book['location']; ?></td>
                                            <td class="mailbox-name"><?php echo $book['class']; ?></td>
                                            <td class="mailbox-name"><?php echo $book['tags']; ?></td>
                                            <td class="mailbox-name">
                                                  <?php
                                                      $date=date_create($book['created_at']);
                                                      echo date_format($date,"d/m/y");
                                                    ?>   
                                            </td>
                                            <?php if($book['available']=="yes" && $book['is_active']=="yes" ){ ?>
                                            <td class="mailbox-name"><?php echo "Available"; ?>
                                            <?php }?>
                                             <?php if($book['available']=="no" && $book['is_active']=="yes") {?>
                                             <td class="mailbox-name"><?php echo "Borrowed"; ?>
                                         <?php }?>  
                                            </td>
                                            <?php if($book['available']=="no" && $book['is_active']=="no") {?>
                                             <td class="mailbox-name"><?php echo "Damaged/Lost"; ?>
                                         <?php }?>  
                                            </td>
                                            
                                        </tr>
                                    <?php   } ?>
                                    <?php   } ?>
                                    </tbody>
                                      
                
                                        
                                   

                                    

                                   

                                </table>
                            </div>

                            <div class="box-footer text-right">
                                <button type="button" class="btn btn-sm btn-success hidden-print"  onclick="printDiv()">Print</button>
                            </div>

                 
                        </div>
                </div>
            </div>
            </form>

        </div>
    </section>
</div>



<script type="text/javascript">
    function printDiv() {

        var printContents = document.getElementById('printArea').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;


        window.print();

        document.body.innerHTML = originalContents;
    }
</script>

<script type="text/javascript">
    function searchDate(){
        var input, table, tr, txtValue, td, filter, end_filter, total, tax, subTotal;
        total = 0;
        filter = document.getElementById('start_date').value;
        end_filter = document.getElementById('end_date').value;

        table = document.getElementById('myTable');
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[3];
window.alert(td.value);
            if (td) {
                txtValue = td.innerText;
                txtValue = txtValue.split(' ')[0];

                if (txtValue >= filter && txtValue <= end_filter) {
window.alert("yes");
                    total = total + parseFloat(tr[i].getElementsByTagName("td")[6].innerText);
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }

        document.getElementById('newSubTotal').innerHTML = total.toFixed(3);
        tax = <?php echo $sales_tax["sales_tax"]; ?>;
        tax = parseInt(tax)*total/100;
        document.getElementById('newTax').innerHTML = tax.toFixed(3);
        document.getElementById('finalAmount').innerHTML = (total + tax).toFixed(3);
    }
    // function searchFunction() {
    //     var input, filter, table, status, tr, td, i, txtValue, filter_2, orderBy;
    //     status = document.getElementById('status');
    //     filter_2 = status.options[status.selectedIndex].value.text;
    //
    //     if (filter_2 == null) {
    //         input = document.getElementById("order_by");
    //         filter = input.value;
    //         table = document.getElementById('myTable');
    //         tr = table.getElementsByTagName("tr");
    //
    //         for (i = 0; i < tr.length; i++) {
    //             td = tr[i].getElementsByTagName("td")[1];
    //             if (td) {
    //                 txtValue = td.textContent || td.innerText;
    //                 if (txtValue.indexOf(filter.charAt(0).toUpperCase()) > -1 || txtValue.indexOf(filter) > -1) {
    //                     tr[i].style.display = "";
    //                 } else {
    //                     tr[i].style.display = "none";
    //                 }
    //             }
    //         }
    //     }else{
    //         orderBy = document.getElementById('order_by').value;
    //         input = document.getElementById('status');
    //         filter = input.options[input.selectedIndex].text;
    //         table = document.getElementById('myTable');
    //         tr = table.getElementsByTagName("tr");
    //         for (i = 0; i < tr.length; i++) {
    //             td = tr[i].getElementsByTagName("td")[5];
    //             var td_1 = tr[i].getElementsByTagName("td")[1];
    //             if (td) {
    //                 txtValue = td.textContent || td.innerText;
    //                 var txtValue_1 = td_1.textContent || td_1.innerText;
    //                 if (txtValue.indexOf(filter) > -1 && ((txtValue_1.indexOf(orderBy.charAt(0).toUpperCase()) > -1 || txtValue_1.indexOf(orderBy) > -1))) {
    //                     tr[i].style.display = "";
    //                 } else {
    //                     tr[i].style.display = "none";
    //                 }
    //             }
    //         }
    //     }
    // }
    // function searchStatus() {
    //     var table, tr, td, txtValue;
    //     var orderBy = document.getElementById('order_by').value;
    //     if(orderBy.length == 0){
    //         var input = document.getElementById('status');
    //         var filter = input.options[input.selectedIndex].text;
    //         table = document.getElementById('myTable');
    //         tr = table.getElementsByTagName("tr");
    //         for (i = 0; i < tr.length; i++) {
    //             td = tr[i].getElementsByTagName("td")[5];
    //             if (td) {
    //                 txtValue = td.textContent || td.innerText;
    //                 if (txtValue.indexOf(filter) > -1) {
    //                     tr[i].style.display = "";
    //                 } else {
    //                     tr[i].style.display = "none";
    //                 }
    //             }
    //         }
    //     }else{
    //         var orderBy = document.getElementById('order_by').value;
    //         var input = document.getElementById('status');
    //         var filter = input.options[input.selectedIndex].text;
    //         table = document.getElementById('myTable');
    //         tr = table.getElementsByTagName("tr");
    //         for (i = 0; i < tr.length; i++) {
    //             td = tr[i].getElementsByTagName("td")[5];
    //             var td_1 = tr[i].getElementsByTagName("td")[1];
    //             if (td) {
    //                 txtValue = td.textContent || td.innerText;
    //                 var txtValue_1 = td_1.textContent || td_1.innerText;
    //                 if (txtValue.indexOf(filter) > -1 && ((txtValue_1.indexOf(orderBy.charAt(0).toUpperCase()) > -1 || txtValue_1.indexOf(orderBy) > -1))) {
    //                     tr[i].style.display = "";
    //                 } else {
    //                     tr[i].style.display = "none";
    //                 }
    //             }
    //         }
    //     }
    //
    //
    //
    // }


</script>







