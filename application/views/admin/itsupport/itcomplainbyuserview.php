

<div class="content-wrapper" style="min-height: 348px;">  

    <section class="content-header">

        <h1>

            <i class="fa fa-sitemap"></i> <?php echo $this->lang->line('it_support'); ?></h1>

    </section>

    <section class="content">

        <div class="row">

            <?php if ($this->rbac->hasPrivilege('maintenance_request', 'can_add')) { ?>

                <div class="col-md-4">

                    <!-- Horizontal Form -->

                    <div class="box box-primary">

                        <div class="box-header with-border">

                            <h3 class="box-title"><?php echo $this->lang->line('add'); ?> <?php echo $this->lang->line('complaint'); ?></h3>

                        </div><!-- /.box-header -->



                        <form id="form1" action="<?php echo site_url('admin/itcomplain/itComplainByUser') ?>"   method="post" accept-charset="utf-8" enctype="multipart/form-data" >

                            <div class="box-body">



                                <?php echo $this->session->flashdata('msg') ?>



                                <div class="form-group">

                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('complaint_type'); ?></label><small class="req"> *</small> 



                                    <select name="complaint" class="form-control">

                                        <option value=""><?php echo $this->lang->line('select'); ?></option>  

                                        <?php foreach ($complaint_type as $key => $value) { ?>

                                            <option value="<?php print_r($value['complaint_type']); ?>" <?php if (set_value('complaint') == $value['complaint_type']) { ?>selected=""<?php } ?>><?php print_r($value['complaint_type']); ?></option>

                                        <?php } ?>                                       

                                    </select>

                                    <span class="text-danger"><?php echo form_error('complaint'); ?></span>



                                </div>



                                <div class="form-group">



                                    <label for="pwd"><?php echo $this->lang->line('source'); ?></label>  

                                    <select name="source" class="form-control">

                                        <option value=""><?php echo $this->lang->line('select'); ?></option>  

                                        <?php foreach ($complaintsource as $key => $value) { ?>

                                            <option value="<?php echo $value['source']; ?>" <?php if (set_value('source') == $value['source']) { ?>selected=""<?php } ?>><?php echo $value['source']; ?></option>

                                        <?php }

                                        ?>                 

                                    </select>

                                    <span class="text-danger"><?php echo form_error('source'); ?></span>

                                </div>

                                <div class="form-group">

                                    <label for="email"><?php echo $this->lang->line('phone'); ?></label> 

                                    <input type="text" class="form-control" value="<?php echo set_value('contact'); ?>"  name="contact">

                                </div>

                                <div class="form-group">

                                    <div class="form-group">

                                        <label for="pwd"><?php echo $this->lang->line('date'); ?></label>    

                                        <input type="text" class="form-control" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>"  name="date" id="date" readonly>
                                        <input type="hidden" class="form-control" value="<?php echo $this->customlib->getUserData()['id']; ?>"  name="staff_id">
                                        <input type="hidden" class="form-control" value="<?php echo $this->customlib->getUserData()['name']; ?>"  name="staff_name">

                                        <span class="text-danger"><?php echo form_error('date'); ?></span>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label for="pwd"><?php echo $this->lang->line('description'); ?></label>

                                    <textarea class="form-control" id="description" name="description"rows="3"><?php echo set_value('description'); ?></textarea>

                                </div>

                                <div class="form-group">

                                    <label for="exampleInputFile"><?php echo $this->lang->line('attach_document'); ?></label>

                                    <div><input class="filestyle form-control" type='file' name='file'  />

                                    </div>

                                    <span class="text-danger"><?php echo form_error('file'); ?></span></div>



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

            if ($this->rbac->hasPrivilege('maintenance_request', 'can_add')) {

                echo "8";

            } else {

                echo "12";

            }

            ?>">

                <!-- general form elements -->

                <div class="box box-primary">

                    <div class="box-header ptbnull">

                        <h3 class="box-title titlefix"><?php echo $this->lang->line('complaint'); ?> <?php echo $this->lang->line('list'); ?></h3>

                        <div class="box-tools pull-right">

                        </div><!-- /.box-tools -->

                    </div><!-- /.box-header -->

                    <div class="box-body">

                        <div class="download_label"></div>

                        <div class="table-responsive mailbox-messages">

                            <table class="table table-hover table-striped table-bordered example">

                                <thead>

                                    <tr>

                                        <th><?php echo $this->lang->line('complaint'); ?> #

                                        </th>

                                        <th>

                                            <?php echo $this->lang->line('complaint_type'); ?>

                                        </th>

                                        <th><?php echo $this->lang->line('name'); ?>

                                        </th>

                                        <th><?php echo $this->lang->line('phone'); ?>

                                        </th>

                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php

                                    if (empty($complaint_list)) {

                                        ?>



                                        <?php

                                    } else {

                                        foreach ($complaint_list as $key => $value) {

                                            ?>

                                            <tr>

                                                <td class="mailbox-name"><?php echo "MR-".$value['id']; ?></td>

                                                <td class="mailbox-name"><?php echo $value['complaint_type']; ?></td>



                                                <td class="mailbox-name"><?php echo $value['name']; ?> </td>

                                                <td class="mailbox-name"> <?php echo $value['contact']; ?></td>
                                                
                                                <td class="mailbox-name">  
                                                 <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date'])); ?>
                                                     
                                                 </td>
                                                <td class="mailbox-name"> <?php echo $value['status']; ?></td>
                                                <td class="mailbox-date pull-right" "="">

                                                    <a onclick="getRecord(<?php echo $value['id']; ?>)" class="btn btn-default btn-xs" data-target="#complaintdetails" title="view" data-toggle="modal" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing" data-original-title="View"><i class="fa fa-reorder"></i></a>

                                                    <?php if ($value['image'] !== "") { ?><a href="<?php echo base_url(); ?>admin/itcomplain/download/<?php echo $value['image']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="Download">

                                                            <i class="fa fa-download"></i>

                                                        </a>  <?php } ?> 

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



<!-- new END -->

<div id="complaintdetails" class="modal fade" role="dialog">

    <div class="modal-dialog modal-dialog2 modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title"><?php echo $this->lang->line('details'); ?></h4>

            </div>

            <div class="modal-body" id="getdetails">





            </div>

        </div>

    </div>

</div>

</div><!-- /.content-wrapper -->

<script type="text/javascript">

    $(document).ready(function () {

        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

        $('#date').datepicker({

            //  format: "dd-mm-yyyy",
            format: date_format,
            autoclose: true,
            startDate: new Date(),
            endDate: new Date()

        });


    });

    function getRecord(id) {

        //alert(id);

        $.ajax({

            url: '<?php echo base_url(); ?>admin/itcomplain/details/' + id,

            success: function (result) {

                //alert(result);

                $('#getdetails').html(result);

            }

        });

    }

</script>

