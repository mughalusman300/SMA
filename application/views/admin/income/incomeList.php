<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>

<!-- Content Wrapper. Contains page content -->

<div class="content-wrapper">



    <section class="content-header">

        <h1>

            <i class="fa fa-usd"></i> <?php echo $this->lang->line('income'); ?></h1>

    </section>



    <!-- Main content -->

    <section class="content">

        <div class="row">

            <?php

            if ($this->rbac->hasPrivilege('income', 'can_add')) {

                ?>

                <div class="col-md-4">

                    <!-- Horizontal Form -->

                    <div class="box box-primary">

                        <div class="box-header with-border">

                            <h3 class="box-title"><?php echo $this->lang->line('add_income'); ?></h3>

                        </div><!-- /.box-header -->



                        <form id="form1" action="<?php echo base_url() ?>admin/income"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">

                            <div class="box-body">

                                <?php if ($this->session->flashdata('msg')) { ?>

                                    <?php echo $this->session->flashdata('msg') ?>

                                <?php } ?>

                                <?php

                                if (isset($error_message)) {

                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";

                                }

                                ?>

                                <?php echo $this->customlib->getCSRF(); ?>



                                <div class="form-group">

                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('income_head'); ?></label>



                                    <select autofocus="" id="inc_head_id" name="inc_head_id" class="form-control" >

                                        <option value=""><?php echo $this->lang->line('select'); ?></option>

                                        <?php

                                        foreach ($incheadlist as $inchead) {

                                            ?>

                                            <option value="<?php echo $inchead['id'] ?>"<?php

                                            if (set_value('inc_head_id') == $inchead['id']) {

                                                echo "selected = selected";

                                            }

                                            ?>><?php echo $inchead['income_category'] ?></option>



                                            <?php

                                            $count++;

                                        }

                                        ?>

                                    </select>



                                </div>



                                <div class="form-group">

                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?><small class="req"> *</small></label>

                                    <input id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name'); ?>" />

                                    <span class="text-danger"><?php echo form_error('name'); ?></span>

                                </div>

                                <div class="form-group">

                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('invoice_no'); ?></label>

                                    <input id="invoice_no" name="invoice_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('invoice_no'); ?>" />

                                    <span class="text-danger"><?php echo form_error('invoice_no'); ?></span>

                                </div>

                                <div class="form-group">

                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?><small class="req"> *</small></label>

                                    <input id="date" name="date" placeholder="" type="text" class="form-control"  value="<?php echo set_value('date'); ?>" readonly="readonly" />

                                    <span class="text-danger"><?php echo form_error('date'); ?></span>

                                </div>

                                <div class="form-group">

                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('amount'); ?><small class="req"> *</small></label>

                                    <input id="amount" name="amount" placeholder="" type="text" class="form-control"  value="<?php echo set_value('amount'); ?>" />

                                    <span class="text-danger"><?php echo form_error('amount'); ?></span>

                                </div>

                                <div class="form-group">

                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>

                                    <input id="documents" name="documents" placeholder="" type="file" class="filestyle form-control" data-height="40"  value="<?php echo set_value('documents'); ?>" />

                                    <span class="text-danger"><?php echo form_error('documents'); ?></span>

                                </div>

                                <div class="form-group">

                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>

                                    <textarea class="form-control" id="description" name="description" placeholder="" rows="3" placeholder="Enter ..."><?php echo set_value('description'); ?></textarea>

                                    <span class="text-danger"></span>

                                </div>

                            </div><!-- /.box-body -->



                            <div class="box-footer">

                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>

                            </div>

                        </form>

                    </div>



                </div><!--/.col (right) -->

                <!-- left column -->

            <?php } ?>

            <div class="col-md-<?php

            if ($this->rbac->hasPrivilege('income', 'can_add')) {

                echo "8";

            } else {

                echo "12";

            }

            ?>">

                <!-- general form elements -->

                <div class="box box-primary">

                    <div class="box-header ptbnull">

                        <h3 class="box-title titlefix"> <?php echo $this->lang->line('income_list'); ?></h3>

                        <div class="box-tools pull-right">

                        </div><!-- /.box-tools -->

                    </div><!-- /.box-header -->

                    <div class="box-body">

                        <div class="download_label"><?php echo $this->lang->line('income_list'); ?></div>

                        <div class="table-responsive mailbox-messages">

                            <table class="table table-hover table-striped table-bordered example">

                                <thead>

                                    <tr>

                                        <th><?php echo $this->lang->line('name'); ?>

                                        </th>

                                        <th><?php echo $this->lang->line('invoice_no'); ?>

                                        </th>

                                        <th><?php echo $this->lang->line('date'); ?>

                                        </th>

                                        <th><?php echo $this->lang->line('income_head'); ?>

                                        </th>

                                        <th><?php echo $this->lang->line('amount'); ?>

                                        </th>

                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php

                                    if (empty($incomelist)) {

                                        ?>



                                        <?php

                                    } else {

                                        foreach ($incomelist as $income) {

                                            ?>

                                            <tr>

                                                <td class="mailbox-name">

                                                    <a href="#" data-toggle="popover" class="detail_popover"><?php echo $income['name'] ?></a>



                                                    <div class="fee_detail_popover" style="display: none">

                                                        <?php

                                                        if ($income['note'] == "") {

                                                            ?>

                                                            <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>

                                                            <?php

                                                        } else {

                                                            ?>

                                                            <p class="text text-info"><?php echo $income['note']; ?></p>

                                                            <?php

                                                        }

                                                        ?>

                                                    </div>

                                                </td>

                                                <td class="mailbox-name">

                                                    <?php echo $income["invoice_no"]; ?>

                                                </td>

                                                <td class="mailbox-name">

                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($income['date'])) ?></td>



                                                <td class="mailbox-name">

                                                    <?php

                                                    $income_head = $income['income_category'];

                                                    echo "$income_head";

                                                    ?>





                                                </td>


                                                <?php

                                                $inc_head_id = $income['inc_head_id'];

                                                $arr1 = str_split($inc_head_id);

                                                ?>



                                                <td class="mailbox-name"><?php echo ($currency_symbol . $income['amount']); ?></td>

                                                <td class="mailbox-date pull-right">

                                                    <?php if ($income['documents']) {

                                                        ?>

                                                        <a href="<?php echo base_url(); ?>admin/income/download/<?php echo $income['documents'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">

                                                            <i class="fa fa-download"></i>

                                                        </a>

                                                    <?php }

                                                    ?>



                                                    <?php

                                                    if ($this->rbac->hasPrivilege('income', 'can_edit')) {

                                                        ?>

                                                        <a href="<?php echo base_url(); ?>admin/income/edit/<?php echo $income['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">

                                                            <i class="fa fa-pencil"></i>

                                                        </a>

                                                    <?php }
                                                    if ($this->rbac->hasPrivilege('income', 'can_view')) {

                                                        ?>
                                                        <button type="button"
                                                            data-invoice_id="<?php echo @$income['id']; ?>"
                                                            data-invoice_type="invoice_en"
                                                            class="btn btn-xs btn-default invoices_details"
                                                            title="<?php echo $this->lang->line('invoices_details'); ?>"
                                                            ><i class="fa fa-eye"></i> En</button>
                                                    <button type="button"
                                                            data-invoice_id="<?php echo @$income['id']; ?>"
                                                            data-invoice_type="invoice_ar"
                                                            class="btn btn-xs btn-default invoices_details"
                                                            title="<?php echo $this->lang->line('invoices_details'); ?>"
                                                            ><i class="fa fa-eye"></i> Ar</button>

                                                    <?php } ?>

                                                    <?php

                                                    if ($this->rbac->hasPrivilege('income', 'can_delete')) {

                                                        ?>

                                                        <a href="<?php echo base_url(); ?>admin/income/delete/<?php echo $income['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">

                                                            <i class="fa fa-remove"></i>

                                                        </a>

                                                    <?php } ?>

                                                </td>

                                            </tr>

                                            <?php

                                        }

                                    }

                                    ?>



                                </tbody>

                            </table><!-- /.table -->







                        </div><!-- /.mail-box-messages -->

                    </div><!-- /.box-body -->

                </div>

            </div><!--/.col (left) -->

            <!-- right column -->



        </div>



    </section><!-- /.content -->

</div><!-- /.content-wrapper -->



<script type="text/javascript">

var base_url = '<?php echo base_url() ?>';
    function Popup(data)
    {

        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({"position": "absolute", "top": "1000000px"});
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

        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';



        $('#date').datepicker({

            //  format: "dd-mm-yyyy",

            format: date_format,

            endDate: '+0d',

            autoclose: true

        });



        $("#btnreset").click(function () {

            $("#form1")[0].reset();

        });



    });



    $(document).ready(function () {

        $('.detail_popover').popover({

            placement: 'right',

            trigger: 'hover',

            container: 'body',

            html: true,

            content: function () {

                return $(this).closest('td').find('.fee_detail_popover').html();

            }

        });

        $(document).on('click', '.invoices_details', function () {
            var array_to_print = [];
            var invoice_id = $(this).data('invoice_id');
            var invoice_type = $(this).data('invoice_type');
			if($.trim(invoice_type) == "invoice_ar")    {
				$.ajax({
					url: '<?php echo site_url("admin/invoices/print_invoices_set_ar") ?>',
					type: 'post',
					async: false,
					data: {'invoice_id': invoice_id},
					success: function (response) {
	
					}
				});
				}

            $.ajax({
                url: '<?php echo site_url("admin/income/print_invoices") ?>',
                type: 'post',
                data: {'invoice_id': invoice_id, 'invoice_type':invoice_type},
                success: function (response) {
                    Popup(response);
                }
            });
        });
    });

</script>