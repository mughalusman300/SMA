<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('invoices'); ?> </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <form role="form" id="action_form" action="<?php echo site_url('admin/invoices/index') ?>" method="post" class="">
                                    <div class="row">

                                        <?php echo $this->customlib->getCSRF(); ?>

                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('invoice_no'); ?></label>
                                                <input type="text" name="invoice_no" value="<?php echo $invoice_no; ?>" class="form-control" placeholder="<?php echo $this->lang->line('invoice_no'); ?>">
                                            </div>
                                        </div>

                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_parent'); ?></label>
                                                <input type="text" name="search_text" value="<?php echo $guardian_id; ?>" class="form-control" placeholder="<?php echo $this->lang->line('search_by_parent'); ?>">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('admission_no'); ?></label>
                                                <input type="text" name="admission_no" value="<?php echo $admission_no; ?>" class="form-control" placeholder="<?php echo $this->lang->line('admission_no'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">

                                            <div class="form-group">

                                                <label><?php echo $this->lang->line('date_from'); ?></label>

                                                <input  id="datefrom" name="date_from" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date_from', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly"/>

                                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>

                                            </div> 

                                        </div>

                                        <div class="col-sm-3">

                                            <div class="form-group">

                                                <label><?php echo $this->lang->line('date_to'); ?></label>

                                                <input  id="dateto" name="date_to" placeholder="" type="text" class="form-control date"  value="<?php echo set_value('date_to', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly"/>

                                            </div> 

                                        </div>


                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('status'); ?></label>
                                                <select class="form-control" name="status">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($invoices_status as $key => $value) {
                                                        ?>
                                                        <option value="<?php echo $value['status']; ?>" <?php if (set_value('status', $search_status) == $value['status']) echo "selected"; ?>><?php echo $value['status']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-3 col-md-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('invoice_type'); ?></label>
                                                <select class="form-control" name="invoice_type">
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
                                                    foreach ($invoices_types as $key => $value) {
                                                        ?>
                                                        <option value="<?php echo $value['inv_type']; ?>" <?php if (set_value('invoice_type', $invoice_type) == $value['inv_type']) echo "selected"; ?>><?php echo $value['inv_type']; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 col-md-4 col-md-offset-8">
                                            <div class="form-group">
                                                <input type="hidden" name="action" id="action" value="">
                                                <button type="button" name="search" id="search" value="search_full" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                                &nbsp;
                                                <button type="button" name="export" id="export" value="export" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-download"></i> Export Details</button>  &nbsp;
                                                <button type="button" name="export_all" id="export_all" value="export_all" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-download"></i> Export</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                            </div>


                        </div>
                    </div>
                </div>
                <?php
                if (isset($invoiceslist)) {
                    ?>
                    <div class="box box-info">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('invoices'); ?> <?php echo $this->lang->line('list'); ?>
                                </i> <?php echo form_error('student'); ?></h3>

                            <?php if (isset($parent_name) && $is_parent_search == "true" && $this->rbac->hasPrivilege('collect_fees', 'can_add')) { ?>
                                <div class="box-tools pull-right">
                                    <a  href="<?php echo base_url(); ?>studentfee/addFeeByParent/<?php echo $parent_name; ?>" class="btn btn-info btn-xs" data-toggle="tooltip" title="" data-original-title="">
                                        <?php echo $currency_symbol; ?> <?php echo $this->lang->line('collect_fees'); ?>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="box-body table-responsive">

                            <div class="download_label"><?php echo $this->lang->line('invoices'); ?> <?php echo $this->lang->line('list'); ?></div>
                            <table class="table table-striped table-bordered table-hover withoutPagination ">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('invoice_no'); ?></th>
                                        <th><?php echo $this->lang->line('guardian_id'); ?></th>
                                        <th><?php echo $this->lang->line('admission_no'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('amount'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('invoice_type'); ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;

                                    foreach ($invoiceslist as $invoice) {
                                        ?>
                                        <tr <?php echo (@trim($invoice['status']) == 'deleted') ? 'style="text-decoration: line-through;"' : '' ?>>
                                            <td><?php echo @$invoice['invoice_number']; ?></td>
                                            <td><?php echo @$invoice['guardian_id']; ?></td>
                                            <td><?php echo @$invoice['admission_no']; ?></td>
                                            <td><?php echo @$invoice['invoice_date']; ?></td>
                                            <td><?php echo @$currency_symbol . " " . @$invoice['invoice_amount']; ?></td>
                                            <td><?php echo @$invoice['status']; ?></td>
                                            <td><?php echo @$invoice['inv_type']; ?></td>
                                            <td>
        <?php if (trim(@$invoice['status']) == 'active') { ?>
                                                    <button type="button"
                                                            data-invoice_id="<?php echo @$invoice['id']; ?>"

                                                            class="btn btn-xs btn-default invoices_details"
                                                            title="<?php echo $this->lang->line('invoices_details'); ?>"
                                                            ><i class="fa fa-eye"></i> En</button>
                                                    <button type="button"
                                                            data-invoice_id="<?php echo @$invoice['id']; ?>"

                                                            class="btn btn-xs btn-default invoices_details_ar"
                                                            title="<?php echo $this->lang->line('invoices_details'); ?>"
                                                            ><i class="fa fa-eye"></i> Ar</button>
            <?php
            if ($this->rbac->hasPrivilege('invoices', 'can_delete')) {
                ?>
                                                        <button type="button"
                                                                data-invoice_id="<?php echo @$invoice['id']; ?>"

                                                                class="btn btn-xs btn-default invoices_delete"
                                                                title="<?php echo $this->lang->line('invoices_details'); ?>"
                                                                ><i class="fa fa-trash"></i> Revert</button>
                <?php
            }
            
             if (trim(@$invoice['credited']) <= 0)  {
                ?>
                                                        <button type="button"
                                                                data-invoice_id="<?php echo @$invoice['id']; ?>"

                                                                class="btn btn-xs btn-default invoice_credit_note"
                                                                title=""
                                                                ><i class="fa fa-clipboard"></i> Credit Note</button>
                <?php
            }           
            

            
        }
        ?>

                                            </td>
                                        </tr>
                                                <?php
                                            }
                                            $count++;
                                            ?>
                                </tbody></table>
                                    <?php echo $links; ?>
                        </div>
                    </div>
                            <?php
                        }
                        ?>
            </div>

        </div>

    </section>
</div>

<script type="text/javascript">
    $(".phone").text(function (i, text) {
        text = text.replace(/(\d{3})(\d{3})(\d{4})/, "$1-$2-$3");
        return text;
    });
</script>

<script type="text/javascript">

    $(document).ready(function () {

        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

        $(".date").datepicker({

            // format: "dd-mm-yyyy",

            format: date_format,

            autoclose: true,

            todayHighlight: true



        });

    });


    var base_url = '<?php echo base_url() ?>';
    function Popup(data)
    {

        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "-1000000px"});
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body>');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);


        return true;
    }
    $(document).ready(function () {
        $(document).on('click', '.invoices_details', function () {
            var array_to_print = [];
            var invoice_id = $(this).data('invoice_id');


            $.ajax({
                url: '<?php echo site_url("admin/invoices/print_invoices") ?>',
                type: 'post',
                data: {'invoice_id': invoice_id},
                success: function (response) {
                    Popup(response);
                }
            });
        });




        $(document).on('click', '.invoices_delete', function () {
            if (confirm("Are you sure you want to delete the invoice no" + $(this).data('invoice_id') + "?")) {

                var array_to_print = [];
                var invoice_id = $(this).data('invoice_id');
                $.ajax({
                    url: '<?php echo site_url("admin/invoices/delete_invoices") ?>',
                    type: 'post',
                    async: false,
                    data: {'invoice_id': invoice_id},
                    success: function (response) {
                        alert("Invoice successfully recycled.");
                        window.location.reload(true);
                    }
                });


            }
        });
        
      $(document).on('click', '.invoice_credit_note', function () {
            if (confirm("Are you sure you want to create credit note for the invoice no" + $(this).data('invoice_id') + "?")) {
                var invoice_id = $(this).data('invoice_id');
                $.ajax({
                    url: '<?php echo site_url("admin/invoices/create_invoice_note") ?>',
                    type: 'post',
                    async: false,
                    data: {'invoice_id': invoice_id},
                    success: function (response) {
                        alert("Invoice credit note created successfully.");
                       window.location.reload(true);
                    }
                });


            }
        });



        $(document).on('click', '.invoices_details_ar', function () {
            var array_to_print = [];
            var invoice_id = $(this).data('invoice_id');
            $.ajax({
                url: '<?php echo site_url("admin/invoices/print_invoices_set_ar") ?>',
                type: 'post',
                async: false,
                data: {'invoice_id': invoice_id},
                success: function (response) {

                }
            });

            $.ajax({
                url: '<?php echo site_url("admin/invoices/print_invoices_ar") ?>',
                type: 'post',
                async: false,
                data: {'invoice_id': invoice_id},
                success: function (response) {
                    Popup(response);
                }
            });

        });

        $("#search").click(function (e) {
            $("#action").val('search');
            $("#action_form").submit();
        });

        $("#export").click(function (e) {
            $("#action").val('export');
            $("#action_form").submit();
        });

        $("#export_all").click(function (e) {
            $("#action").val('export_all');
            $("#action_form").submit();
        });

    });


</script>
