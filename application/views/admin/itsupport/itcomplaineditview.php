<div class="content-wrapper" style="min-height: 348px;">  

    <section class="content-header">

        <h1>

            <i class="fa fa-sitemap"></i> <?php echo $this->lang->line('it_support'); ?></h1>

    </section>

    <section class="content">

        <div class="row">

            <?php if ($this->rbac->hasPrivilege('maintenance_task', 'can_add') || $this->rbac->hasPrivilege('maintenance_task', 'can_edit')) { ?>

                <div class="col-md-4">

                    <!-- Horizontal Form -->

                    <div class="box box-primary">

                        <div class="box-header with-border">

                            <h3 class="box-title"><?php echo $this->lang->line('edit'); ?> <?php echo $this->lang->line('complaint'); ?></h3>

                        </div><!-- /.box-header -->

                        <form id="form1" action="<?php echo site_url('admin/itcomplain/edit/' . $complaint_data['id']) ?>"   method="post" accept-charset="utf-8" enctype="multipart/form-data" >

                            <div class="box-body">

                                <!-- <?php if ($this->session->flashdata('msg')) { ?>

                                    <?php echo $this->session->flashdata('msg') ?>

                                <?php } ?>

                                <?php

                                if (isset($error_message)) {

                                    echo "<div class='alert alert-danger'>" . $error_message . "</div>";

                                }

                                ?>

                                -->

                                <?php

                                ?>

                                <div class="form-group">

                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('complaint_type'); ?></label>



                                    <select name="complaint" class="form-control">

                                        <option value=""><?php echo $this->lang->line('select'); ?></option>  

                                        <?php

                                        foreach ($complaint_type as $key => $value) {

                                            print_r($value);

                                            ?>

                                            <option value="<?php echo $value['complaint_type']; ?>" <?php if (set_value('complaint', $complaint_data['complaint_type']) == $value['complaint_type']) echo "selected"; ?>><?php print_r($value['complaint_type']); ?></option>

    <?php } ?>                                       

                                    </select>

                                    <span class="text-danger"><?php echo form_error('complaint'); ?></span>



                                </div>



                                <div class="form-group">



                                    <label for="pwd"><?php echo $this->lang->line('source'); ?></label>  

                                    <select name="source" class="form-control">

                                        <option value=""><?php echo $this->lang->line('select'); ?></option>  

                                        <?php foreach ($complaintsource as $key => $value) { ?>

                                            <option value="<?php echo $value['source']; ?>"<?php if (set_value('source', $complaint_data['source']) == $value['source']) echo "selected"; ?>><?php echo $value['source']; ?></option>

                                        <?php }

                                        ?>                 

                                    </select>

                                    <span class="text-danger"><?php echo form_error('source'); ?></span>

                                </div>



                                <div class="form-group">

                                    <label for="pwd"><?php echo $this->lang->line('complained_by'); ?></label> <small class="req"> *</small> 

                                    <input type="text" class="form-control" value="<?php echo set_value('name', $complaint_data['name']); ?>"  name="name">

                                    <span class="text-danger"><?php echo form_error('name'); ?></span>

                                </div>

                                <div class="form-group">

                                    <label for="email"><?php echo $this->lang->line('phone'); ?></label> 

                                    <input type="text" class="form-control" value="<?php echo set_value('contact', $complaint_data['contact']); ?>"  name="contact">

                                </div>

                                <div class="form-group">

                                    <div class="form-group">

                                        <label for="pwd"><?php echo $this->lang->line('date'); ?></label>

                                        <input type="text" class="form-control" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($complaint_data['date']))); ?>"  name="date" id="date" readonly>

                                        <span class="text-danger"><?php echo form_error('date'); ?></span>

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label for="pwd"><?php echo $this->lang->line('description'); ?></label>

                                    <textarea class="form-control" id="description" name="description"rows="3"><?php echo set_value('description', $complaint_data['description']); ?></textarea>

                                </div>

                                <div class="form-group">

                                    <label for="pwd"><?php echo $this->lang->line('action_taken'); ?></label>

                                    <input type="text" class="form-control" value="<?php echo set_value('action_taken', $complaint_data['action_taken']); ?>"  name="action_taken">

                                    <span class="text-danger"><?php echo form_error('action_taken'); ?></span>

                                </div>

                                <div class="form-group">

                                    <label for="pwd"><?php echo $this->lang->line('assigned'); ?></label>

                                    <input type="text" class="form-control" value="<?php echo set_value('assigned', $complaint_data['assigned']); ?>"  name="assigned">

                                    <span class="text-danger"><?php echo form_error('assigned'); ?></span>

                                </div>

                                <div class="form-group">

                                    <label for="pwd"><?php echo $this->lang->line('note'); ?></label>

                                    <textarea class="form-control" id="description" name="note" name="note" rows="3"><?php echo set_value('note', $complaint_data['note']); ?></textarea>

                                    <span class="text-danger"><?php echo form_error('note'); ?></span>

                                </div>
                                <div class="form-group">


                             <label for="exampleInputEmail1"><?php echo $this->lang->line('status'); ?></label> 
                                <select  id="status" name="status" class="form-control" >
                                <option value="<?php echo set_value( 'status',$complaint_data['status']); ?>"><?php echo set_value( 'status',$complaint_data['status']); ?></option>  
                                <option value="Registered"><?php echo "Registered"; ?></option>
                                <option value="Under-Process"><?php echo "Under-Process"; ?></option>
                                <option value="Completed"><?php echo "Completed"; ?></option>
                                 </select>
                                <span class="text-danger"><?php echo form_error('status'); ?></span>
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

            if ($this->rbac->hasPrivilege('maintenance_task', 'can_add') || $this->rbac->hasPrivilege('maintenance_task', 'can_edit')) {

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

                                        <th><?php echo $this->lang->line('complaint_type'); ?>

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

                                                <td class="mailbox-name"> <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date'])); ?></td>
                                                <td class="mailbox-name"> <?php echo $value['status']; ?></td>
                                                <td class="mailbox-date pull-right" "="">

                                                    <a onclick="getRecord(<?php echo $value['id']; ?>)" class="btn btn-default btn-xs" data-target="#complaintdetails" data-toggle="modal" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing" data-original-title="View"><i class="fa fa-reorder"></i></a>

        <?php if ($value['image'] !== "") { ?><a href="<?php echo base_url(); ?>admin/itcomplain/download/<?php echo $value['image']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('download'); ?>">

                                                            <i class="fa fa-download"></i>

                                                        </a>  <?php } ?> 

        <?php if ($this->rbac->hasPrivilege('maintenance_task', 'can_edit')) { ?>    

                                                        <a href="<?php echo base_url(); ?>admin/itcomplain/edit/<?php echo $value['id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('edit'); ?>">

                                                            <i class="fa fa-pencil"></i>

                                                        </a>

                                                    <?php } ?>

                                                    <?php if ($this->rbac->hasPrivilege('complain', 'can_delete')) { ?>

            <?php if ($value['image'] !== "") { ?><a href="<?php echo base_url(); ?>admin/itcomplain/imagedelete/<?php echo $value['id']; ?>/<?php echo $value['image']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');" data-original-title="<?php echo $this->lang->line('delete'); ?>">

                                                                <i class="fa fa-remove"></i>

                                                            </a>

            <?php } else { ?>

                                                            <a href="<?php echo base_url(); ?>admin/itcomplain/delete/<?php echo $value['id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');" data-original-title="<?php echo $this->lang->line('delete'); ?>">

                                                                <i class="fa fa-remove"></i>

                                                            </a>

                                                        <?php }

                                                    }

                                                    ?>

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

            autoclose: true

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

