


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


<form class="forms-sample" method="GET" action="<?php echo site_url('admin/book/finereport');?>">
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
                                <option value="1"><?php echo "Paid"; ?></option>
                                <option value="0"><?php echo "Non-Paid"; ?></option>
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
                                        <th><?php echo $this->lang->line('book_title'); ?></th>
                                        <th><?php echo $this->lang->line('issue_date'); ?></th>
                                        <th><?php echo $this->lang->line('due_date'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('return_date'); ?>
                                        </th>
                                        <th><?php echo $this->lang->line('id'); ?></th>
                                       <th><?php echo $this->lang->line('name'); ?></th>
                                       <th><?php echo $this->lang->line('class'); ?></th>
                                        <th><?php echo $this->lang->line('days'); ?></th>
                                        <th><?php echo $this->lang->line('fine'); ?></th>
                                        <th><?php echo $this->lang->line('discount'); ?></th>
                                        <th><?php echo $this->lang->line('sales_total_price'); ?></th>
                                        <th><?php echo $this->lang->line('paid'); ?></th>
                                        <th><?php echo $this->lang->line('paid'); ?> <?php echo $this->lang->line('date'); ?></th>
                                        <th class="no-print"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $priceSum = 0; $i=0; if(!empty($finelist)) {?>      
                                    <?php foreach ($finelist as $fine){ ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo $fine['other']; ?></td>
                                            <td class="mailbox-name"><?php echo $fine['book_title']; ?></td>
                                            <td class="mailbox-name"><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fine['issue_date'])) ?></td>
                                            <td class="mailbox-name"><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fine['return_date'])) ?></td>
                                            <td class="mailbox-name">
                                                    <?php
                                                      $date=date_create($fine['book_issues_created_at']);
                                                      echo date_format($date,"d/m/y");
                                                    ?>
                                            </td> 
                                            <td class="mailbox-name">
                                             <?php echo $fine['member_id']; ?> 
                                            </td> 
                                            <td class="mailbox-name">

                                                <?php echo $fine['firstname'] . " " . $fine['lastname']; ?> 
                                            </td>
                                            <td class="mailbox-name">
                                                <?php $class_name = $fine['class_name'];
                                                $section = $fine['section'];?>
                                                 <?php echo $class_name." ( ".$section." ) "; ?>
                                            </td>
                                            <td class="mailbox-name">
                                                    <?php echo $fine['days'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo "SR ". $fine['balance'] ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $fine['discount']. " Days " ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo $fine['total_fine'];
                                                    if($fine['status']==""){
                                                    $priceSum += floatval($fine['total_fine']);
                                                }
                                                    //$priceSum += floatval($fine['total_fine']);
                                                    if($fine['status']==1){
                                                    $priceSum += floatval($fine['total_fine']);
                                                }
                                                   if($fine['status']==0){
                                                    $priceSum += floatval($fine['total_fine']);
                                                }
                                                    ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php echo  $fine['amount_paid'] ?> 
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php if ($fine['status'] == 1) {
                                                        ?>
                                                    <?php
                                                      $date=date_create($fine['paid_date']);
                                                      echo date_format($date,"d/m/y");
                                                    ?>
                                                    <?php   } ?> 
                                                </td>
                                                <td class="no-print mailbox-name">
                                                        <a href="<?php echo base_url(); ?>admin/member/issue/<?php echo $fine['member_id'];?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="View Details">
                                                        <i class="fa fa-eye"></i>
                                            </a>
                                                    </td>
                                         </tr>   

                                  <?php  } ?>  
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
                                        <th id="newSubTotal">SR <?php  echo number_format(($priceSum) , 2, '.', '');?></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th colspan="2">Net.Amount (Exc.VAT)</th>
                                        <th id="finalAmount"><?php
                                            $priceSum= $priceSum /1.05;
                                            echo number_format(($priceSum) , 2, '.', '');
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
                              <?php $i++; } ?>
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







