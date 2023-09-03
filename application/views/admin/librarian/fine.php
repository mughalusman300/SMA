


<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php echo $title ?> <small><?php echo $this->lang->line('class1'); ?></small></h1>
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
                            <span ><?php echo $this->setting_model->getSchoolDetail()->dise_code ?></span><br/>
                              <?php
                                if ($memberList->member_type == "student") {
                                echo"<b><label>Student Details</label></b><br/>"; 
                                echo"<span >Addmission No:".$memberList->admission_no."</span><br/>";   
                                $name = $memberList->firstname . " " . $memberList->lastname;    
                                echo"<span >Student Name:".$name."</span><br/>";
                                $class_name = $memberList->class . " "; 
                                $section=$memberList->section; 
                                echo"<span >Student Class:</b>".$class_name."( ".$section.")</span><br/>";
                                echo"<b><span >Father Name:</b>".$memberList->father_name."</span><br/>";
                                echo"<b><span >Father Id:</b>".$memberList->guardian_id."</span><br/>";
                                echo"<b><span >Phone No:</b>".$memberList->guardian_phone."</span><br/>";
                                echo"<b><span >Member Id:</b>".$memberList->id."</span><br/>";
                                echo"<b><span >Libray Card:</b>".$memberList->library_card_no."</span><br/>";
                                } else {
                                echo"<b><label>Staff Details</label></b><br/>";    
                                echo"<b><span >Name:</b>".$memberList->name."</span><br/>";
                                echo"<b><span >Member Id:</b>".$memberList->employee_id."</span><br/>";
                                echo"<b><span >Libray Card:</b>".$memberList->library_card_no."</span><br/>";
                                echo"<b><span >Department:</b>".$memberList->department."</span><br/>";
                                echo"<b><span >Designation:</b>".$memberList->designation."</span><br/>";
                                }
                                ?> 
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
<?php
foreach ($fine_detail as $fine) {
   $fine_days= $fine['days'];
   $fid= $fine['fid'];
   $balance= $fine['balance'];
   $total_fine = $fine['total_fine'];
   $status =$fine['status'];
   $bookactive =$fine['is_active'];
 }
 ?>                

<form id="form1" action="<?php echo site_url('admin/member/finedetail/'. $fid.'/'.$memberList->lib_member_id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                        <?php
                            if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                               
                            }
                            ?> 
                 <?php if(($status)== 0){?>            
                 <div class="box-header hidden-print">
                     <div class="col-lg-12" style="margin-bottom: 1%;">
                           <div class="form-group col-lg-3">
                            <input type="hidden" name="fid" value="<?php echo $fid;?>">
                            <input type="hidden" name="balance" value="<?php echo $balance;?>">
                               <label><b>Discount</b></label>
                               <input type="number" 
                               <?php if($bookactive=="yes") {?> 
                                min="0" 
                                max="<?php echo $balance;?>" 
                                <?php } ?> 
                                name="discount"  class="form-control"   placeholder="Discount Days" />   
                           </div>
                       </div>
                   
                  
                     <div class="col-lg-12">
                             <div class="form-group col-lg-3">
                            <label>Remarks</label>
                            <textarea rows="3" cols="50" name="remarks" class="form-control" placeholder="Remarks" required></textarea>  
                           </div>   
                       </div>
                   </div>
                 
                         <div class="box-footer text-right">
                                <button type="submit" class="btn btn-sm btn-success hidden-print"  >Submit</button>
                         </div>
                    <?php } ?>
                        <div class="box-body table-responsive">
                            <div>
            <table class="table table-hover table-striped table-bordered withoutPagination" id="myTable">
                                  
                                    <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('barcode'); ?></th>
                                        <th><?php echo $this->lang->line('book_title'); ?></th>
                                        <th><?php echo $this->lang->line('issue_date') ?></th>
                                        <th><?php echo $this->lang->line('due_date') ?></th>
                                        <th ><?php echo $this->lang->line('return_date') ?> </th>
                                        <th ><?php echo $this->lang->line('days') ?> </th>
                                        <th><?php echo $this->lang->line('fine') ?> </th>
                                        <th><?php echo $this->lang->line('discount') ?> </th>
                                        <th width="100"><?php echo $this->lang->line('remarks') ?> </th>
                                        <th> <?php echo $this->lang->line('paid') ?> </th>
                                        <th><?php echo $currency_symbol.' '.$this->lang->line('sales_total_price') ?> </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (empty($fine_detail)) {
                                        ?>

                                        <?php
                                    } else {
                                        $count = 1;
                                        foreach ($fine_detail as $fine) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name">
                                                    <?php echo $fine['other'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $fine['book_title'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fine['issue_date'])) ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fine['return_date'])) ?>       
                                                </td> 
                                                <td class="mailbox-name">
                                                  
                                                    <?php
                                                      $date=date_create($fine['book_issues_created_at']);
                                                      echo date_format($date,"d/m/Y");
                                                    ?>
                                                </td>      
                                                <td class="mailbox-name">
                                                   <?php echo $fine['days'] ?>
                                                </td>  
                                                <td class="mailbox-name">
                                                    <?php echo $fine['balance'].".00" ?>
                                                </td>            
                                                <td class="mailbox-date ">
                                                    <?php echo $fine['discount'].".00" ?>
                                                </td>
                                                <td  class="mailbox-date ">
                                                    <?php echo $fine['remarks'] ?>
                                                </td>
                                                <td class="mailbox-date">
                                                    <?php echo $fine['amount_paid'] ?>
                                                </td>
                                                <td class="mailbox-date">
                                                <?php 
                                                echo number_format($fine['total_fine'],2);
                                                 ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $count++;
                                        }
                                    }
                                    ?>

                                </tbody>
                                      <tr>
                                        <td colspan="6">
                                            <hr/>
                                        </td>
                                    </tr>
                
                                        <tr>
                                        <td colspan="6">
                                            <hr/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th colspan="2">Total Amount</th>
                                        <th id="newSubTotal">SR <?php echo $total_fine;?><!-- <?php echo $priceSum; ?> --></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th colspan="2">Net.Amount (Exc.VAT)</th>
                                        <th id="finalAmount"><?php
                                            $priceSum= $total_fine /1.05;
                                            echo number_format(($priceSum), 2, '.', '');
                                            ?></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th colspan="2">VAT (<?php echo $sales_tax["sales_tax"]; ?>%)  </th>
                                        <th id="newTax"><?php
                                            $tax = (int)$sales_tax["sales_tax"];
                                            echo number_format(($tax * $priceSum /100), 2, '.', '');
                                            ?> 
                                        </th>
                                    </tr>
                                </table>
                            </div>

                            <div class="box-footer text-right">
                                <button type="button" class="btn btn-sm btn-success hidden-print"  onclick="printDiv()">Print Receipt</button>
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







